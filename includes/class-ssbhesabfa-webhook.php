<?php

include_once(plugin_dir_path(__DIR__) . 'admin/services/HesabfaLogService.php');
include_once(plugin_dir_path(__DIR__) . 'admin/services/HesabfaWpFaService.php');

/*
 * @package    ssbhesabfa
 * @subpackage ssbhesabfa/includes
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 * @author     HamidReza Gharahzadeh <hamidprime@gmail.com>
 */

class Ssbhesabfa_Webhook
{
    public $invoicesObjectId = array();
    public $invoiceItemsCode = array();
    public $itemsObjectId = array();
    public $contactsObjectId = array();
    public $warehouseReceiptsObjectId = array();

    public function __construct()
    {
        HesabfaLogService::writeLogStr("===== Webhook Called =====");
        $wpFaService = new HesabfaWpFaService();

        $hesabfaApi = new Ssbhesabfa_Api();
        $lastChange = get_option('ssbhesabfa_last_log_check_id');
        $changes = $hesabfaApi->settingGetChanges($lastChange + 1);

        if ($changes->Success) {
            update_option('ssbhesabfa_business_expired', 0);

            foreach ($changes->Result as $item) {
                if (!$item->API) {
                    switch ($item->ObjectType) {
                        case 'Invoice':
                            $this->invoicesObjectId[] = $item->ObjectId;
                            foreach (explode(',', $item->Extra) as $invoiceItem) {
                                if ($invoiceItem != '') {
                                    $this->invoiceItemsCode[] = $invoiceItem;
                                }
                            }
                            break;
                        case 'WarehouseReceipt':
                            $this->warehouseReceiptsObjectId[] = $item->ObjectId;
                            break;
                        case 'Product':
                            //if Action was deleted
                            if ($item->Action == 53) {
                                $wpFa = $wpFaService->getWpFaByHesabfaId('product', $item->Extra);
                                if ($wpFa) {
                                    global $wpdb;
                                    $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id' => $wpFa->id));
                                }
                                break;
                            }
                            $this->itemsObjectId[] = $item->ObjectId;
                            break;
                        case 'Contact':
                            //if Action was deleted
                            if ($item->Action == 33) {
                                $id_obj = $wpFaService->getWpFaIdByHesabfaId('customer', $item->Extra);
                                global $wpdb;
                                $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id' => $id_obj));
                                break;
                            }

                            $this->contactsObjectId[] = $item->ObjectId;
                            break;
                    }
                }
            }

            //remove duplicate values
            $this->invoiceItemsCode = array_unique($this->invoiceItemsCode);
            $this->contactsObjectId = array_unique($this->contactsObjectId);
            $this->itemsObjectId = array_unique($this->itemsObjectId);
            $this->invoicesObjectId = array_unique($this->invoicesObjectId);

            $this->setChanges();

            //set LastChange ID
            $lastChange = end($changes->Result);
            if (is_object($lastChange))
                update_option('ssbhesabfa_last_log_check_id', $lastChange->Id);
            else if ($changes->LastId)
                update_option('ssbhesabfa_last_log_check_id', $changes->LastId);

        } else {
            HesabfaLogService::log(array("ssbhesabfa - Cannot check last changes. Error Message: " . (string)$changes->ErrorMessage . ". Error Code: " . (string)$changes->ErrorCode));
            if ($changes->ErrorCode == 108) {
                update_option('ssbhesabfa_business_expired', 1);
                add_action('admin_notices', array(__CLASS__, 'ssbhesabfa_business_expired_notice'));
            }
            return false;
        }

        return true;
    }

    public function ssbhesabfa_business_expired_notice()
    {
        echo '<div class="error"><p>' . __('Cannot connect to Hesabfa. Business expired.', 'ssbhesabfa') . '</p></div>';
    }

    public function setChanges()
    {
        //Invoices
        if (!empty($this->invoicesObjectId)) {
            $invoices = $this->getObjectsByIdList($this->invoicesObjectId, 'invoice');
            if ($invoices != false) {
                foreach ($invoices as $invoice) {
                    $this->setInvoiceChanges($invoice);
                }
            }
        }

        //Contacts
//        if (!empty($this->contactsObjectId)) {
//            $contacts = $this->getObjectsByIdList(array_unique($this->contactsObjectId), 'contact');
//            if ($contacts != false) {
//                foreach ($contacts as $contact) {
//                    $this->setContactChanges($contact);
//                }
//            }
//        }

        //Items
        $items = array();

        if (!empty($this->warehouseReceiptsObjectId)) {
            $receipts = $this->getObjectsByIdList($this->warehouseReceiptsObjectId, 'WarehouseReceipt');
            if ($receipts != false) {
                foreach ($receipts as $receipt) {
                    foreach ($receipt->Items as $item)
                        array_push($this->invoiceItemsCode, $item->ItemCode);
                }
            }
        }

        if (!empty($this->itemsObjectId)) {
            $objects = $this->getObjectsByIdList($this->itemsObjectId, 'item');
            if ($objects != false) {
                foreach ($objects as $object) {
                    array_push($items, $object);
                }
            }
        }

        if (!empty($this->invoiceItemsCode)) {
            $objects = $this->getObjectsByCodeList($this->invoiceItemsCode);

            if ($objects != false) {
                foreach ($objects as $object) {
                    array_push($items, $object);
                }
            }
        }

        if (!empty($items)) {
            update_option("ssbhesabfa_inside_product_edit", 1);
            try {
                foreach ($items as $item) {
                    Ssbhesabfa_Admin_Functions::setItemChanges($item);
                }
            } catch (Exception $e) {
            } finally {
                update_option("ssbhesabfa_inside_product_edit", 0);
            }
        }

        return true;
    }

    // use in webhook call when invoice change
    public function setInvoiceChanges($invoice)
    {
        if (!is_object($invoice))
            return false;

        $wpFaService = new HesabfaWpFaService();

        //1.set new Hesabfa Invoice Code if changes
        $number = $invoice->Number;
        $json = json_decode($invoice->Tag);
        if (is_object($json)) {
            $id_order = $json->id_order;
        } else {
            $id_order = 0;
        }

        if ($invoice->InvoiceType == 0) {
            //check if Tag not set in hesabfa
            if ($id_order == 0) {
                HesabfaLogService::log(array("This invoice is not defined in OnlineStore. Invoice Number: " . $number));
            } else {
                //check if order exist in wooCommerce
                $id_obj = $wpFaService->getWpFaId('order', $id_order);
                if ($id_obj != false) {
                    global $wpdb;
                    $row = $wpdb->get_row("SELECT `id_hesabfa` FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id` = $id_obj");
                    if (is_object($row) && $row->id_hesabfa != $number) {
                        $id_hesabfa_old = $row->id_hesabfa;
                        //ToDo: number must int in hesabfa, what can i do
                        $wpdb->update($wpdb->prefix . 'ssbhesabfa', array('id_hesabfa' => $number), array('id' => $id_obj));
                        HesabfaLogService::log(array("Invoice Number changed. Old Number: $id_hesabfa_old. New ID: $number"));
                    }
                }
            }
        }
    }

    // use in webhook call when contact change
    public function setContactChanges($contact)
    {
        if (!is_object($contact)) {
            return false;
        }

        //1.set new Hesabfa Contact Code if changes
        $code = $contact->Code;

        $json = json_decode($contact->Tag);
        if (is_object($json)) {
            $id_customer = $json->id_customer;
        } else {
            $id_customer = 0;
        }

        //check if Tag not set in hesabfa
        if ($id_customer == 0) {
            HesabfaLogService::log(array("This Customer is not define in OnlineStore. Customer code: $code"));

            return false;
        }

        //check if customer exist in wordpress
        $wpFaService = new HesabfaWpFaService();
        $id_obj = $wpFaService->getWpFaId('customer', $id_customer);

        if ($id_obj != false) {
            global $wpdb;
            $row = $wpdb->get_row("SELECT `id_hesabfa` FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id` = $id_obj");

            if (is_object($row) && $row->id_hesabfa != $code) {
                $id_hesabfa_old = $row->id_hesabfa;
                $wpdb->update($wpdb->prefix . 'ssbhesabfa', array('id_hesabfa' => (int)$code), array('id' => $id_obj));

                HesabfaLogService::log(array("Contact Code changed. Old ID: $id_hesabfa_old. New ID: $code"));
            }
        }

        return true;
    }

    public function getObjectsByIdList($idList, $type)
    {
        $hesabfaApi = new Ssbhesabfa_Api();
        switch ($type) {
            case 'item':
                $result = $hesabfaApi->itemGetById($idList);
                break;
            case 'contact':
                $result = $hesabfaApi->contactGetById($idList);
                break;
            case 'invoice':
                $result = $hesabfaApi->invoiceGetByIdList($idList);
                break;
            case 'WarehouseReceipt':
                $result = $hesabfaApi->warehouseReceiptGetByIdList($idList);
                break;
            default:
                return false;
        }

        if (is_object($result) && $result->Success) {
            return $result->Result;
        }

        return false;
    }

    public function getObjectsByCodeList($codeList)
    {

        $filters = array(array("Property" => "Code", "Operator" => "in", "Value" => $codeList));
        $hesabfaApi = new Ssbhesabfa_Api();

        $warehouse = get_option('ssbhesabfa_item_update_quantity_based_on', "-1");
        if ($warehouse == "-1")
            $result = $hesabfaApi->itemGetItems(array('Take' => 100000, 'Filters' => $filters));
        else
            $result = $hesabfaApi->itemGetQuantity($warehouse, $codeList);

        //$result = $hesabfaApi->itemGetItems($queryInfo);

        if (is_object($result) && $result->Success) {
            return $warehouse == "-1" ? $result->Result->List : $result->Result;
        }

        return false;
    }
}
