<?php

include_once(plugin_dir_path(__DIR__) . 'admin/services/HesabfaLogService.php');
include_once(plugin_dir_path(__DIR__) . 'admin/services/HesabfaWpFaService.php');

/**
 * The admin-specific functionality of the plugin.
 *
 * @class      Ssbhesabfa_Admin
 * @version    1.90.53
 * @since      1.0.0
 * @package    ssbhesabfa
 * @subpackage ssbhesabfa/admin
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 * @author     HamidReza Gharahzadeh <hamidprime@gmail.com>
 */
class Ssbhesabfa_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Check DB ver on plugin update and do necessary actions
     *
     * @since    1.0.7
     */
    public function ssbhesabfa_update_db_check()
    {
        $current_db_ver = get_site_option('ssbhesabfa_db_version');
        if ($current_db_ver === false || $current_db_ver < 1.1) {
            global $wpdb;
            $table_name = $wpdb->prefix . "ssbhesabfa";

            $sql = "ALTER TABLE $table_name
                    ADD `id_ps_attribute` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `id_ps`;";

            if (!$wpdb->query($sql)) {
                HesabfaLogService::log(array("Cannot alter table $table_name. Current DB Version: $current_db_ver"));
            } else {
                update_option('ssbhesabfa_db_version', 1.1);
                HesabfaLogService::log(array("Alter table $table_name. Current DB Version: $current_db_ver"));
            }
        }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ssbhesabfa_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ssbhesabfa_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style('fontiran_css', plugin_dir_url(__FILE__) . 'css/fontiran.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ssbhesabfa-admin.css?v=1', array(), $this->version, 'all');
        wp_enqueue_style('bootstrap_css', plugin_dir_url(__FILE__) . 'css/bootstrap.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ssbhesabfa_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ssbhesabfa_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ssbhesabfa-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script('bootstrap_js', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), $this->version, false);
    }

    private function load_dependencies()
    {
        /**
         * The class responsible for defining all actions that occur in the Dashboard
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/ssbhesabfa-admin-display.php';

        /**
         * The class responsible for defining function for display Html element
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/ssbhesabfa-html-output.php';

        /**
         * The class responsible for defining function for display general setting tab
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/ssbhesabfa-admin-setting.php';

        /**
         * The class responsible for defining function for admin area
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/ssbhesabfa-admin-functions.php';
    }

    /**
     * WC missing notice for the admin area.
     *
     * @since    1.0.0
     */
    public function ssbhesabfa_missing_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('Hesabfa Plugin requires the %s to work!', 'ssbhesabfa'), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">' . __('WooCommerce', 'ssbhesabfa') . '</a>') . '</p></div>';
    }

    /**
     * Hesabfa Plugin Live mode notice for the admin area.
     *
     * @since    1.0.0
     */
    public function ssbhesabfa_live_mode_notice()
    {
        echo '<div class="error"><p>' . __('Hesabfa Plugin need to connect to Hesabfa Accounting, Please check the API credential!', 'ssbhesabfa') . '</p></div>';
    }

    public function ssbhesabfa_business_expired_notice()
    {
        echo '<div class="error"><p>' . __('Cannot connect to Hesabfa. Business expired.', 'ssbhesabfa') . '</p></div>';
    }

    /**
     * Missing hesabfa default currency notice for the admin area.
     *
     * @since    1.0.0
     */
    public function ssbhesabfa_currency_notice()
    {
        echo '<div class="error"><p>' . __('Hesabfa Plugin cannot works! because WooCommerce currency in not match with Hesabfa.', 'ssbhesabfa') . '</p></div>';
    }

    /*
     * Action - Ajax 'export products' from Hesabfa/Export tab
     * @since	1.0.0
     */
    public function adminExportProductsCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $batch = wc_clean($_POST['batch']);
            $totalBatch = wc_clean($_POST['totalBatch']);
            $total = wc_clean($_POST['total']);
            $updateCount = wc_clean($_POST['updateCount']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->exportProducts($batch, $totalBatch, $total, $updateCount);

            if ($result['error']) {
                if ($updateCount === -1) {
                    $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productExportResult=false&error=-1');
                } else {
                    $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productExportResult=false');
                }
            } else {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productExportResult=true&processed=' . $result['updateCount']);
            }

            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    public function adminImportProductsCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $batch = wc_clean($_POST['batch']);
            $totalBatch = wc_clean($_POST['totalBatch']);
            $total = wc_clean($_POST['total']);
            $updateCount = wc_clean($_POST['updateCount']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->importProducts($batch, $totalBatch, $total, $updateCount);
            $import_count = $result['updateCount'];

            if ($result['error']) {
                if ($import_count === -1) {
                    $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productImportResult=false&error=-1');
                } else {
                    $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productImportResult=false');
                }
            } else {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productImportResult=true&processed=' . $import_count);
            }

            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    /*
     * Action - Ajax 'export products Opening Quantity' from Hesabfa/Export tab
     * @since	1.0.6
     */
    public function adminExportProductsOpeningQuantityCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $batch = wc_clean($_POST['batch']);
            $totalBatch = wc_clean($_POST['totalBatch']);
            $total = wc_clean($_POST['total']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->exportOpeningQuantity($batch, $totalBatch, $total);
            if ($result['error']) {
                if ($result['errorType'] == 'shareholderError') {
                    $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productOpeningQuantityExportResult=false&shareholderError=true');
                } else if ($result['errorType'] == 'noProduct') {
                    $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productOpeningQuantityExportResult=false&noProduct=true');
                } else {
                    $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productOpeningQuantityExportResult=false');
                }
            } else {
                if ($result["done"] == true)
                    update_option('ssbhesabfa_use_export_product_opening_quantity', true);
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&productOpeningQuantityExportResult=true');
            }

            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    /*
     * Action - Ajax 'export customers' from Hesabfa/Export tab
     * @since	1.0.0
     */
    public function adminExportCustomersCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $batch = wc_clean($_POST['batch']);
            $totalBatch = wc_clean($_POST['totalBatch']);
            $total = wc_clean($_POST['total']);
            $updateCount = wc_clean($_POST['updateCount']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->exportCustomers($batch, $totalBatch, $total, $updateCount);

            if ($result["error"]) {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&customerExportResult=false');
            } else {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=export&customerExportResult=true&processed=' . $result["updateCount"]);
            }
            echo json_encode($result);

            die(); // this is required to return a proper result
        }
    }

    /*
     * Action - Ajax 'Sync Changes' from Hesabfa/Sync tab
     * @since	1.0.0
     */
    public function adminSyncChangesCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {
            include(plugin_dir_path(__DIR__) . 'includes/class-ssbhesabfa-webhook.php');
            new Ssbhesabfa_Webhook();

            $redirect_url = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&changesSyncResult=true');
            echo $redirect_url;

            die(); // this is required to return a proper result
        }
    }

    /*
     * Action - Ajax 'Sync Products' from Hesabfa/Sync tab
     * @since	1.0.0
     */
    public function adminSyncProductsCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $batch = wc_clean($_POST['batch']);
            $totalBatch = wc_clean($_POST['totalBatch']);
            $total = wc_clean($_POST['total']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->syncProducts($batch, $totalBatch, $total);
            if ($result['error']) {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&productSyncResult=false');
                echo json_encode($result);
            } else {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&productSyncResult=true');
                echo json_encode($result);
            }
            die(); // this is required to return a proper result
        }
    }

    /*
     * Action - Ajax 'Sync Orders from Hesabfa/Sync tab
     * @since	1.0.0
     */
    public function adminSyncOrdersCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $batch = wc_clean($_POST['batch']);
            $totalBatch = wc_clean($_POST['totalBatch']);
            $total = wc_clean($_POST['total']);
            $updateCount = wc_clean($_POST['updateCount']);
            $from_date = wc_clean($_POST['date']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->syncOrders($from_date, $batch, $totalBatch, $total, $updateCount);

            if (!$result['error'])
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&orderSyncResult=true&processed=' . $result["updateCount"]);
            else {
                switch ($result['error']) {
                    case 'fiscalYearError':
                        $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&orderSyncResult=false&fiscal=true');
                        break;
                    case 'inputDateError':
                        $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&orderSyncResult=false');
                        break;
                    default:
                        $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&orderSyncResult=true&processed=' . $updateCount);
                }
            }

            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    /*
    * Action - Ajax 'Update Products' from Hesabfa/Sync tab
    * @since	1.0.0
    */
    public function adminUpdateProductsCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $batch = wc_clean($_POST['batch']);
            $totalBatch = wc_clean($_POST['totalBatch']);
            $total = wc_clean($_POST['total']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->updateProductsInHesabfaBasedOnStore($batch, $totalBatch, $total);

            if ($result['error']) {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&$productUpdateResult=false');
            } else {
                $result["redirectUrl"] = admin_url('admin.php?page=ssbhesabfa-option&tab=sync&$productUpdateResult=true');
            }
            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    public function adminSubmitInvoiceCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $orderId = wc_clean($_POST['orderId']);

            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->setOrder($orderId);
            if ($result)
                $func->setOrderPayment($orderId);

            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }


    public function adminSyncProductsManuallyCallback()
    {
        HesabfaLogService::writeLogStr('===== Sync Products Manually =====');

        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $page = wc_clean($_POST["page"]);
            $rpp = wc_clean($_POST["rpp"]);
            if (!$page) $page = 1;
            if (!$rpp) $rpp = 10;

            if (isset($_POST["data"])) {
                $data = wc_clean($_POST['data']);
                $data = str_replace('\\', '', $data);
                $data = json_decode($data, true);
            } else {
                $errors = true;
            }

            $func = new Ssbhesabfa_Admin_Functions();
            $res = $func->syncProductsManually($data);
            if ($res["result"] == true) {
                $redirect_url = admin_url("admin.php?page=hesabfa-sync-products-manually&p=$page&rpp=$rpp&result=true");
            } else {
                $data = implode(",", $res["data"]);
                $redirect_url = admin_url("admin.php?page=hesabfa-sync-products-manually&p=$page&rpp=$rpp&result=false&data=$data");
            }
            echo $redirect_url;

            die(); // this is required to return a proper result
        }
    }

    public function adminClearPluginDataCallback()
    {
        HesabfaLogService::writeLogStr('===== Clear Plugin Data =====');
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $hesabfaApi = new Ssbhesabfa_Api();
            $result = $hesabfaApi->fixClearTags();
            if (!$result->Success) {
                HesabfaLogService::log(array("ssbhesabfa - Cannot clear tags. Error Message: " . (string)$changes->ErrorMessage . ". Error Code: " . (string)$changes->ErrorCode));
            }

            global $wpdb;
            $options = $wpdb->get_results("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '%ssbhesabfa%'");
            foreach ($options as $option) {
                delete_option($option->option_name);
            }

            $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ssbhesabfa");

            die();
        }
    }

    public function adminInstallPluginDataCallback()
    {
        HesabfaLogService::writeLogStr('===== Install Plugin Data =====');
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            // create table and settings
            require_once plugin_dir_path(__DIR__) . 'includes/class-ssbhesabfa-activator.php';
            Ssbhesabfa_Activator::activate();

            die();
        }
    }

    //This functions related to set webhook
    public function ssbhesabfa_init_internal()
    {
        add_rewrite_rule('ssbhesabfa-webhook.php$', 'index.php?ssbhesabfa_webhook=1', 'top');
        $this->checkForSyncChanges();
    }

    private function checkForSyncChanges()
    {
        $syncChangesLastDate = get_option('ssbhesabfa_sync_changes_last_date');
        if (!isset($syncChangesLastDate) || $syncChangesLastDate == false) {
            add_option('ssbhesabfa_sync_changes_last_date', new DateTime());
            $syncChangesLastDate = new DateTime();
        }

        $nowDateTime = new DateTime();
        $diff = $nowDateTime->diff($syncChangesLastDate);

        if ($diff->i >= 3) {
            HesabfaLogService::writeLogStr('===== Sync Changes Automatically =====');
            update_option('ssbhesabfa_sync_changes_last_date', new DateTime());
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ssbhesabfa-webhook.php';
            new Ssbhesabfa_Webhook();
        }
    }

    public function ssbhesabfa_query_vars($query_vars)
    {
        $query_vars[] = 'ssbhesabfa_webhook';
        return $query_vars;
    }

    public function custom_hesabfa_column_order_list($columns)
    {
        $reordered_columns = array();

        // Inserting columns to a specific location
        foreach ($columns as $key => $column) {
            $reordered_columns[$key] = $column;
            if ($key == 'order_status') {
                // Inserting after "Status" column
                $reordered_columns['hesabfa-column-invoice-number'] = __('Invoice in Hesabfa', 'ssbhesabfa');
                $reordered_columns['hesabfa-column-submit-invoice'] = __('Submit Invoice', 'ssbhesabfa');
            }
        }
        return $reordered_columns;
    }

    public function custom_orders_list_column_content($column, $post_id)
    {
        global $wpdb;

        switch ($column) {
            case 'hesabfa-column-invoice-number' :
                // Get custom post meta data
                $row = $wpdb->get_row("SELECT `id_hesabfa` FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id_ps` = $post_id AND `obj_type` = 'order'");

                //$my_var_one = get_post_meta( $post_id, '_the_meta_key1', true );
                if (!empty($row))
                    echo '<mark class="order-status"><span>' . $row->id_hesabfa . '</span></mark>';
                else
                    echo '<small></small>';
                break;

            case 'hesabfa-column-submit-invoice' :
                echo '<a role="button" class="button btn-submit-invoice" ';
                echo "data-order-id='$post_id'>";
                echo __('Submit Invoice', 'ssbhesabfa');
                echo '</a>';
                break;
        }
    }

    public function ssbhesabfa_parse_request(&$wp)
    {
        if (array_key_exists('ssbhesabfa_webhook', $wp->query_vars)) {
            include(plugin_dir_path(__DIR__) . 'includes/ssbhesabfa-webhook.php');
            exit();
        }
        return;
    }

    //Hooks
    //Contact
    public function ssbhesabfa_hook_edit_user(WP_User $user)
    {
        $wpFaService = new HesabfaWpFaService();
        $code = isset($user) ? $wpFaService->getCustomerCodeByWpId($user->ID) : '';
        ?>
        <hr>
        <table class="form-table">
            <tr>
                <th><label for="user_hesabfa_code"
                           class="text-info"><?php echo __('Contact Code in Hesabfa', 'ssbhesabfa'); ?></label></th>
                <td>
                    <input
                            type="text"
                            value="<?php echo $code; ?>"
                            name="user_hesabfa_code"
                            id="user_hesabfa_code"
                            class="regular-text"
                    ><br/>
                    <div class="description mt-2">
                        <?php echo __("The contact code of this user in Hesabfa, if you want to map this user "
                            . "to a contact in Hesabfa, enter the Contact code.", 'ssbhesabfa'); ?>
                    </div>
                </td>
            </tr>
        </table>
        <hr>
        <?php
    }

    public function ssbhesabfa_hook_user_register($id_customer)
    {
        $user_hesabfa_code = $_REQUEST['user_hesabfa_code'];
        if (isset($user_hesabfa_code) && $user_hesabfa_code !== "") {
            $wpFaService = new HesabfaWpFaService();
            $wpFaOld = $wpFaService->getWpFaByHesabfaId('customer', $user_hesabfa_code);
            $wpFa = $wpFaService->getWpFa('customer', $id_customer);

            if (!$wpFaOld || !$wpFa || $wpFaOld->id !== $wpFa->id) {
                if ($wpFaOld)
                    $wpFaService->delete($wpFaOld);

                if ($wpFa) {
                    $wpFa->idHesabfa = $user_hesabfa_code;
                    $wpFaService->update($wpFa);
                } else {
                    $wpFa = new WpFa();
                    $wpFa->objType = 'customer';
                    $wpFa->idWp = $id_customer;
                    $wpFa->idHesabfa = intval($user_hesabfa_code);
                    $wpFaService->save($wpFa);
                }
            }
        }

        $function = new Ssbhesabfa_Admin_Functions();
        $function->setContact($id_customer);
    }

    public function ssbhesabfa_hook_delete_user($id_customer)
    {
        $wpFaService = new HesabfaWpFaService();
        $id_obj = $wpFaService->getWpFaId('customer', $id_customer);
        if ($id_obj != false) {
            global $wpdb;
            $row = $wpdb->get_row("SELECT `id_hesabfa` FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id` = $id_obj AND `obj_type` = 'customer'");

            if (is_object($row)) {
                $hesabfaApi = new Ssbhesabfa_Api();
                $hesabfaApi->contactDelete($row->id_hesabfa);
            }

            global $wpdb;
            $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id_ps' => $id_customer));

            HesabfaLogService::log(array("Customer deleted. Customer ID: $id_customer"));
        }
    }

    //Invoice
    public function ssbhesabfa_hook_order_status_change($id_order, $from, $to)
    {
        HesabfaLogService::writeLogStr("===== Order Status Hook =====");
        $function = new Ssbhesabfa_Admin_Functions();

        foreach (get_option('ssbhesabfa_invoice_status') as $status) {
            HesabfaLogService::writeLogStr("status: $status");

            if ($status == $to) {
                $orderResult = $function->setOrder($id_order);
                if ($orderResult) {
                    // set payment
                    foreach (get_option('ssbhesabfa_payment_status') as $statusPayment) {
                        if ($statusPayment == $to)
                            $function->setOrderPayment($id_order);
                    }
                }
            }
        }

        foreach (get_option('ssbhesabfa_invoice_return_status') as $status) {
            if ($status == $to)
                $function->setOrder($id_order, 2, $function->getInvoiceCodeByOrderId($id_order));
        }
    }

    public function ssbhesabfa_hook_payment_confirmation($id_order, $from, $to)
    {
        foreach (get_option('ssbhesabfa_payment_status') as $status) {
            if ($status == $to) {
                $function = new Ssbhesabfa_Admin_Functions();
                $function->setOrderPayment($id_order);
            }
        }
    }

    //Item
    private $call_time = 1;

    public function ssbhesabfa_hook_new_product($id_product)
    {
        if (get_option("ssbhesabfa_inside_product_edit", 0) === 1)
            return;

        if ($this->call_time === 1) {
            $this->call_time++;
            return;
        } else {
            $this->call_time = 1;
        }

        if (get_option("ssbhesabfa_do_not_submit_product_automatically", "no") === "yes")
            return;
        $function = new Ssbhesabfa_Admin_Functions();
        $function->setItems(array($id_product));
    }

    public function ssbhesabfa_hook_save_product_variation($id_attribute)
    {
        HesabfaLogService::writeLogStr("=== ssbhesabfa_hook_save_product_variation ===");

        //change hesabfa item code
        $variable_field_id = "ssbhesabfa_hesabfa_item_code_" . $id_attribute;
        $code = $_POST[$variable_field_id];
        $id_product = $_POST['product_id'];

        if ($code === "")
            return;

        if (isset($code)) {
            global $wpdb;
            $row = $wpdb->get_row("SELECT * FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id_hesabfa` = " . $code . " AND `obj_type` = 'product'");

            if (is_object($row)) {
                if ($row->id_ps == $id_product && $row->id_ps_attribute == $id_attribute) {
                    return false;
                }

                echo '<div class="error"><p>' . __('The new Item code already used for another Item', 'ssbhesabfa') . '</p></div>';
                HesabfaLogService::log(array("The new Item code already used for another Item. Product ID: $id_product"));
            } else {
                $row2 = $wpdb->get_row("SELECT * FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id_ps` = $id_product AND `obj_type` = 'product' AND `id_ps_attribute` = $id_attribute");

                if (is_object($row2)) {
                    $wpdb->update($wpdb->prefix . 'ssbhesabfa', array(
                        'id_hesabfa' => (int)$code,
                    ), array(
                        'id_ps' => $id_product,
                        'id_ps_attribute' => $id_attribute,
                        'obj_type' => 'product',
                    ));
                } else if ((int)$code !== 0) {
                    $wpdb->insert($wpdb->prefix . 'ssbhesabfa', array(
                        'id_hesabfa' => (int)$code,
                        'id_ps' => (int)$id_product,
                        'id_ps_attribute' => $id_attribute,
                        'obj_type' => 'product',
                    ));
                }
            }
        }

        //add attribute if not exists
        $func = new Ssbhesabfa_Admin_Functions();
        $wpFaService = new HesabfaWpFaService();
        $code = $wpFaService->getProductCodeByWpId($id_product, $id_attribute);
        if ($code == null) {
            $func->setItems(array($id_product));
        }
    }

    //ToDo: check why base product not deleted
    public function ssbhesabfa_hook_delete_product($id_product)
    {
        HesabfaLogService::writeLogStr("===== Product Delete Hook =====");

        $func = new Ssbhesabfa_Admin_Functions();
        $wpFaService = new HesabfaWpFaService();
        $hesabfaApi = new Ssbhesabfa_Api();
        global $wpdb;

        $variations = $func->getProductVariations($id_product);
        if ($variations != false) {
            foreach ($variations as $variation) {
                $id_attribute = $variation->get_id();
                $code = $wpFaService->getProductCodeByWpId($id_product, $id_attribute);
                if ($code != false) {
                    $hesabfaApi->itemDelete($code);
                    $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id_hesabfa' => $code, 'obj_type' => 'product'));
                    HesabfaLogService::log(array("Product variation deleted. Product ID: $id_product-$id_attribute"));
                }
            }
        }

        $code = $wpFaService->getProductCodeByWpId($id_product);
        if ($code != false) {
            $hesabfaApi->itemDelete($code);
            $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id_hesabfa' => $code, 'obj_type' => 'product'));
            HesabfaLogService::log(array("Product deleted. Product ID: $id_product"));
        }
    }

    public function ssbhesabfa_hook_delete_product_variation($id_attribute)
    {
//        $func = new Ssbhesabfa_Admin_Functions();
        $hesabfaApi = new Ssbhesabfa_Api();
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id_ps_attribute` = $id_attribute AND `obj_type` = 'product'");

        if (is_object($row)) {
            $hesabfaApi->itemDelete($row->id_hesabfa);

            $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id' => $row->id));
            HesabfaLogService::log(array("Product variation deleted. Product ID: $row->id_ps-$id_attribute"));
        }
    }

    public function ssbhesabfa_hook_product_options_general_product_data()
    {
        $wpFaService = new HesabfaWpFaService();
        $value = isset($_GET['post']) ? $wpFaService->getProductCodeByWpId($_GET['post']) : '';
        $args = array(
            'id' => 'ssbhesabfa_hesabfa_item_code_0',
            'label' => __('Hesabfa base item code', 'ssbhesabfa'),
            'desc_tip' => true,
            'description' => __('The base Item code of this product in Hesabfa, if you want to map this product to another item in Hesabfa, enter the new Item code.', 'ssbhesabfa'),
            'value' => $value,
            'type' => 'number',
        );
        woocommerce_wp_text_input($args);
    }

    public function ssbhesabfa_hook_process_product_meta($post_id)
    {
        $itemCode = isset($_POST['ssbhesabfa_hesabfa_item_code_0']) ? $_POST['ssbhesabfa_hesabfa_item_code_0'] : '';

        if ($itemCode === "")
            return;

        if (isset($itemCode)) {
            global $wpdb;
            $row = $wpdb->get_row("SELECT * FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id_hesabfa` = " . $itemCode . " AND `obj_type` = 'product'");

            if (is_object($row)) {
                //ToDo: show error to customer in BO
                echo '<div class="error"><p>' . __('The new Item code already used for another Item', 'ssbhesabfa') . '</p></div>';
                HesabfaLogService::log(array("The new Item code already used for another Item. Product ID: $post_id"));
            } else {
                $row2 = $wpdb->get_row("SELECT * FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id_ps` = $post_id AND `obj_type` = 'product' AND `id_ps_attribute` = 0");
                if (is_object($row2)) {
                    $wpdb->update($wpdb->prefix . 'ssbhesabfa', array(
                        'id_hesabfa' => (int)$itemCode,
                    ), array(
                        'id_ps' => $post_id,
                        'id_ps_attribute' => 0,
                        'obj_type' => 'product',
                    ));
                } else if ((int)$itemCode !== 0) {
                    $wpdb->insert($wpdb->prefix . 'ssbhesabfa', array(
                        'id_hesabfa' => (int)$itemCode,
                        'id_ps' => (int)$post_id,
                        'id_ps_attribute' => 0,
                        'obj_type' => 'product',
                    ));
                }
            }
        }
    }

    public function ssbhesabfa_hook_product_after_variable_attributes($loop, $variation_data, $variation)
    {
        $wpFaService = new HesabfaWpFaService();
        $value = isset($_POST['product_id']) ? $wpFaService->getProductCodeByWpId($_POST['product_id'], $variation->ID) : '';
        $args = array(
            'id' => 'ssbhesabfa_hesabfa_item_code_' . $variation->ID,
            'label' => __('Hesabfa variable item code', 'ssbhesabfa'),
            'desc_tip' => true,
            'description' => __('The variable Item code of this product variable in Hesabfa, if you want to map this product to another item in Hesabfa, enter the new Item code.', 'ssbhesabfa'),
            'value' => $value,
        );
        woocommerce_wp_text_input($args);
    }

    /*
    * Action - Ajax 'clean log file' from Hesabfa/Log tab
    * @since	1.0.0
    */
    public function adminCleanLogFileCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {
            $func = new Ssbhesabfa_Admin_Functions();
            $result = $func->cleanLogFile();

            if ($result) {
                $redirect_url = admin_url('admin.php?page=ssbhesabfa-option&tab=log&cleanLogResult=true');
            } else {
                $redirect_url = admin_url('admin.php?page=ssbhesabfa-option&tab=log&cleanLogResult=false');
            }
            echo $redirect_url;

            die(); // this is required to return a proper result
        }
    }

    // custom data tab in edit product page in admin panel
    function add_hesabfa_product_data_tab($product_data_tabs)
    {
        $product_data_tabs['hesabfa'] = array(
            'label' => __('Hesabfa', 'ssbhesabfa'),
            'target' => 'panel_product_data_hesabfa',
        );
        return $product_data_tabs;
    }

    function add_hesabfa_product_data_fields()
    {
        global $woocommerce, $post;

        $funcs = new Ssbhesabfa_Admin_Functions();
        $items = array();
        $id_product = $post->ID;
        $product = new WC_Product($id_product);

        if ($product->get_status() === "auto-draft") {
            ?>
            <div id="panel_product_data_hesabfa" class="panel woocommerce_options_panel"
                 data-product-id="<?php echo $id_product ?>">
                هنوز محصول ذخیره نشده است.
                <br>
                پس از ذخیره محصول، در این قسمت می توانید ارتباط محصول و متغیرهای آن با حسابفا
                را مدیریت کنید.
            </div>
            <?php
            return;
        }

        $items[] = ssbhesabfaItemService::mapProduct($product, $id_product, false);
        $items[0]["Quantity"] = $product->get_stock_quantity();
        $items[0]["Id"] = $id_product;
        $i = 1;

        $variations = $funcs->getProductVariations($id_product);
        if ($variations) {
            foreach ($variations as $variation) {
                $items[] = ssbhesabfaItemService::mapProductVariation($product, $variation, $id_product, false);
                $items[$i]["Quantity"] = $variation->get_stock_quantity();
                $items[$i]["Id"] = $variation->get_id();
                $i++;
            }
        }

        ?>
        <div id="panel_product_data_hesabfa" class="panel woocommerce_options_panel"
             data-product-id="<?php echo $id_product ?>">
            <table class="table table-striped">
                <tr class="small fw-bold">
                    <td>نام کالا</td>
                    <td>کد در حسابفا</td>
                    <td>ذخیره کد</td>
                    <td>حذف ارتباط</td>
                    <td>بروزرسانی قیمت و موجودی</td>
                    <td>قیمت</td>
                    <td>موجودی</td>
                </tr>
                <?php
                foreach ($items as $item) {
                    ?>
                    <tr>
                        <td><?php echo $item["Name"] ?></td>
                        <td><input type="text" value="<?php echo $item["Code"] ?>"
                                   id="hesabfa-item-<?php echo $item["Id"] ?>" style="width: 75px;"
                                   class="hesabfa-item-code" data-id="<?php echo $item["Id"] ?>"></td>
                        <td><input type="button" value="ذخیره" data-id="<?php echo $item["Id"] ?>"
                                   class="button hesabfa-item-save"></td>
                        <td><input type="button" value="حذف ارتباط" data-id="<?php echo $item["Id"] ?>"
                                   class="button hesabfa-item-delete-link"></td>
                        <td><input type="button" value="بروزرسانی" data-id="<?php echo $item["Id"] ?>"
                                   class="button button-primary hesabfa-item-update"></td>
                        <td id="hesabfa-item-price-<?php echo $item["Id"] ?>"><?php echo $item["SellPrice"] ?></td>
                        <td id="hesabfa-item-quantity-<?php echo $item["Id"] ?>"><?php echo $item["Quantity"] ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <input type="button" value="ذخیره همه" id="hesabfa-item-save-all" class="button">
            <input type="button" value="حذف ارتباط همه" id="hesabfa-item-delete-link-all" class="button">
            <input type="button" value="بروزرسانی همه" id="hesabfa-item-update-all" class="button button-primary">

        </div>
        <?php

    }

    function adminChangeProductCodeCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $productId = (int)wc_clean($_POST['productId']);
            $attributeId = (int)wc_clean($_POST['attributeId']);
            if ($productId == $attributeId) $attributeId = 0;
            $code = (int)wc_clean($_POST['code']);
            $result = array();

            if (!$code) {
                $result["error"] = true;
                $result["message"] = "کد کالا وارد نشده است.";
                echo json_encode($result);
                die();
                return;
            }

            $wpFaService = new HesabfaWpFaService();
            $wpFa = $wpFaService->getWpFaByHesabfaId('product', $code);
            if ($wpFa) {
                $result["error"] = true;
                $result["message"] = "این کد به کالای دیگری متصل است. \n" . $wpFa->idWp . " - " . $wpFa->idWpAttribute;
                echo json_encode($result);
                die();
                return;
            }

            $api = new Ssbhesabfa_Api();
            $response = $api->itemGet($code);
            if (!$response->Success) {
                $result["error"] = true;
                $result["message"] = "کالایی با کد وارد شده در حسابفا پیدا نشد.";
                echo json_encode($result);
                die();
                return;
            }

            $wpFa = $wpFaService->getWpFa('product', $productId, $attributeId);
            if ($wpFa) {
                $wpFa->idHesabfa = $code;
                $wpFaService->update($wpFa);
            } else {
                $wpFa = new WpFa();
                $wpFa->idHesabfa = $code;
                $wpFa->idWp = $productId;
                $wpFa->idWpAttribute = $attributeId;
                $wpFa->objType = 'product';
                $wpFaService->save($wpFa);
            }
            $result["error"] = false;
            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    function adminDeleteProductLinkCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $productId = wc_clean($_POST['productId']);
            $attributeId = wc_clean($_POST['attributeId']);
            if ($productId == $attributeId) $attributeId = 0;
            $result = array();

            $wpFaService = new HesabfaWpFaService();
            $wpFa = $wpFaService->getWpFa('product', $productId, $attributeId);
            if ($wpFa)
                $wpFaService->delete($wpFa);

            $result["error"] = false;
            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    function adminUpdateProductCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            if (get_option('ssbhesabfa_item_update_price', 'no') == 'no' &&
                get_option('ssbhesabfa_item_update_quantity', 'no') == 'no') {
                $result["error"] = true;
                $result["message"] = "خطا: در تنظیمات افزونه، گزینه های بروزرسانی قیمت و موجودی محصول بر اساس حسابفا فعال نیستند.";
                echo json_encode($result);
                die();
            }

            $productId = wc_clean($_POST['productId']);
            $attributeId = wc_clean($_POST['attributeId']);

            if (get_option('ssbhesabfa_item_update_quantity', 'no') == 'yes')
                update_post_meta($attributeId, '_manage_stock', 'yes');

            if ($productId == $attributeId) $attributeId = 0;
            $result = array();

            $wpFaService = new HesabfaWpFaService();
            $wpFa = $wpFaService->getWpFa('product', $productId, $attributeId);
            if ($wpFa) {
                $api = new Ssbhesabfa_Api();
                $warehouse = get_option('ssbhesabfa_item_update_quantity_based_on', "-1");
                if ($warehouse == "-1")
                    $response = $api->itemGet($wpFa->idHesabfa);
                else
                    $response = $api->itemGetQuantity($warehouse, array($wpFa->idHesabfa));

                if ($response->Success) {
                    $item = $warehouse == "-1" ? $response->Result : $response->Result[0];
                    $newProps = Ssbhesabfa_Admin_Functions::setItemChanges($item);
                    $result["error"] = false;
                    $result["newPrice"] = $newProps["newPrice"];
                    $result["newQuantity"] = $newProps["newQuantity"];
                } else {
                    $result["error"] = true;
                    $result["message"] = "کالا در حسابفا پیدا نشد.";
                }
            }

            echo json_encode($result);
            die();
        }
    }

    function adminChangeProductsCodeCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {
            $wpFaService = new HesabfaWpFaService();

            $productId = (int)wc_clean($_POST['productId']);
            $itemsData = wc_clean($_POST['itemsData'], true);
            $result = array();
            $codes = [];

            foreach ($itemsData as $itemData) {
                $attributeId = (int)$itemData["attributeId"];
                $code = (int)$itemData["code"];
                if ($productId == $attributeId) $attributeId = 0;
                $codes[] = str_pad($code, 6, "0", STR_PAD_LEFT);

                if (!$code) {
                    $result["error"] = true;
                    $result["message"] = "کد کالا وارد نشده است.";
                    echo json_encode($result);
                    die();
                    return;
                }

                $wpFa = $wpFaService->getWpFaByHesabfaId('product', $code);
                $wpFa2 = $wpFaService->getWpFa('product', $productId, $attributeId);
                if ($wpFa && $wpFa2 && $wpFa->id != $wpFa2->id) {
                    $result["error"] = true;
                    $result["message"] = "این کد ($code) به کالای دیگری متصل است. \n" . $wpFa->idWp . " - " . $wpFa->idWpAttribute;
                    echo json_encode($result);
                    die();
                    return;
                }
            }

            $api = new Ssbhesabfa_Api();
            $filters = array(array("Property" => "Code", "Operator" => "in", "Value" => $codes));
            $response = $api->itemGetItems(array('Filters' => $filters));
            if ($response->Success) {
                $items = $response->Result->List;
                foreach ($codes as $code) {
                    $found = false;
                    foreach ($items as $item) {
                        if ($item->Code == $code)
                            $found = true;
                    }
                    if (!$found) {
                        $result["error"] = true;
                        $result["message"] = "کالایی با کد $code در حسابفا پیدا نشد.";
                        echo json_encode($result);
                        die();
                        return;
                    }
                }
            } else {
                $result["error"] = true;
                $result["message"] = "کالایی با کد وارد شده در حسابفا پیدا نشد.";
                echo json_encode($result);
                die();
                return;
            }


            foreach ($itemsData as $itemData) {
                $attributeId = (int)$itemData["attributeId"];
                $code = (int)$itemData["code"];
                if ($productId == $attributeId) $attributeId = 0;

                $wpFa = $wpFaService->getWpFa('product', $productId, $attributeId);
                if ($wpFa) {
                    $wpFa->idHesabfa = $code;
                    $wpFaService->update($wpFa);
                } else {
                    $wpFa = new WpFa();
                    $wpFa->idHesabfa = $code;
                    $wpFa->idWp = $productId;
                    $wpFa->idWpAttribute = $attributeId;
                    $wpFa->objType = 'product';
                    $wpFaService->save($wpFa);
                }
            }

            $result["error"] = false;
            echo json_encode($result);
            die();
        }
    }

    function adminDeleteProductsLinkCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            $productId = wc_clean($_POST['productId']);
            $result = array();

            $wpFaService = new HesabfaWpFaService();
            $wpFaService->deleteAll($productId);

            $result["error"] = false;
            echo json_encode($result);
            die(); // this is required to return a proper result
        }
    }

    function adminUpdateProductAndVariationsCallback()
    {
        if (is_admin() && (defined('DOING_AJAX') || DOING_AJAX)) {

            if (get_option('ssbhesabfa_item_update_price', 'no') == 'no' &&
                get_option('ssbhesabfa_item_update_quantity', 'no') == 'no') {
                $result["error"] = true;
                $result["message"] = "خطا: در تنظیمات افزونه، گزینه های بروزرسانی قیمت و موجودی محصول بر اساس حسابفا فعال نیستند.";
                echo json_encode($result);
                die();
            }

            $api = new Ssbhesabfa_Api();
            $wpFaService = new HesabfaWpFaService();

            $productId = wc_clean($_POST['productId']);
            $productAndCombinations = $wpFaService->getProductAndCombinations($productId);
            $result = array();
            if (count($productAndCombinations) == 0) {
                $result["error"] = true;
                $result["message"] = "هیچ ارتباطی پیدا نشد.";
                echo json_encode($result);
                die();
            }
            $codes = [];
            $ssbhesabfa_item_update_quantity = get_option('ssbhesabfa_item_update_quantity', 'no');
            foreach ($productAndCombinations as $p) {
                $codes[] = str_pad($p->idHesabfa, 6, "0", STR_PAD_LEFT);

                if ($ssbhesabfa_item_update_quantity == 'yes')
                    update_post_meta($p->idWpAttribute == 0 ? $p->idWp : $p->idWpAttribute, '_manage_stock', 'yes');
            }

            $filters = array(array("Property" => "Code", "Operator" => "in", "Value" => $codes));
            $warehouse = get_option('ssbhesabfa_item_update_quantity_based_on', "-1");
            if ($warehouse == "-1")
                $response = $api->itemGetItems(array('Filters' => $filters));
            else
                $response = $api->itemGetQuantity($warehouse, $codes);

            if ($response->Success) {
                $items = $warehouse == "-1" ? $response->Result->List : $response->Result;
                $newData = [];
                $result["error"] = false;
                foreach ($items as $item) {
                    $newProps = Ssbhesabfa_Admin_Functions::setItemChanges($item);
                    $wpFa = $wpFaService->getWpFaByHesabfaId('product', $item->Code);
                    $newData[] = array("newPrice" => $newProps["newPrice"],
                        "newQuantity" => $newProps["newQuantity"],
                        "attributeId" => $wpFa->idWpAttribute > 0 ? $wpFa->idWpAttribute : $wpFa->idWp);
                }
                $result["newData"] = $newData;
            } else {
                $result["error"] = true;
                $result["message"] = "کالایی با کد وارد شده در حسابفا پیدا نشد.";
                echo json_encode($result);
                die();
                return;
            }

            echo json_encode($result);
            die();
        }
    }

}
