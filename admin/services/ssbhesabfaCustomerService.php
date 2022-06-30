<?php


class ssbhesabfaCustomerService
{
    public static $countries;
    public static $states;

    public static function mapCustomer($code, $id_customer,$id_order = null, $type = 'first'): array
    {       
        self::getCountriesAndStates();

        $customer = new WC_Customer($id_customer);
        $firstName = $customer->get_first_name() ? $customer->get_first_name() : $customer->get_billing_first_name();
        $lastName = $customer->get_last_name() ? $customer->get_last_name() : $customer->get_billing_last_name();
        $name = $firstName . ' ' . $lastName;
        $nodeFamily = get_option('ssbhesabfa_contact_automatic_save_node_family') == 'yes'? 'اشخاص :' . get_option('ssbhesabfa_contact_node_family') :null;
        if($id_order)
            HesabfaLogService::writeLogStr(get_post_meta( $id_order,'_billing_hesabfa_nationalcode'));

        if (empty($name) || $name === ' ')
            $name = __('Not Defined', 'ssbhesabfa');

        $hesabfaCustomer = array();

        switch ($type) {
            case 'first':
            case 'billing':
                $country_name = self::$countries[$customer->get_billing_country()];
                $state_name = self::$states[$customer->get_billing_country()][$customer->get_billing_state()];

                $hesabfaCustomer = array(
                    'Code' => $code,
                    'Name' => $name,
                    'FirstName' => Ssbhesabfa_Validation::contactFirstNameValidation($firstName),
                    'LastName' => Ssbhesabfa_Validation::contactLastNameValidation($lastName),
                    // 'NationalCode' => Ssbhesabfa_Validation::contactNationalCodeValidation($nationalcode),
                    'ContactType' => 1,
                    'NodeFamily' => $nodeFamily,
                    'Address' => Ssbhesabfa_Validation::contactAddressValidation($customer->get_billing_address_1() . ' ' . $customer->get_billing_address_2()),
                    'City' => Ssbhesabfa_Validation::contactCityValidation($customer->get_billing_city()),
                    'State' => Ssbhesabfa_Validation::contactStateValidation($state_name),
                    'Country' => Ssbhesabfa_Validation::contactCountryValidation($country_name),
                    'PostalCode' => Ssbhesabfa_Validation::contactPostalCodeValidation($customer->get_billing_postcode()),
                    'Phone' => Ssbhesabfa_Validation::contactPhoneValidation($customer->get_billing_phone()),
                    'Email' => Ssbhesabfa_Validation::contactEmailValidation($customer->get_email()),
                    'Tag' => json_encode(array('id_customer' => $id_customer)),
                    'Note' => __('Customer ID in OnlineStore: ', 'ssbhesabfa') . $id_customer,
                );
                break;
            case 'shipping':
                $country_name = self::$countries[$customer->get_shipping_country()];
                $state_name = self::$states[$customer->get_shipping_country()][$customer->get_shipping_state()];

                $hesabfaCustomer = array(
                    'Code' => $code,
                    'Name' => $name,
                    'FirstName' => Ssbhesabfa_Validation::contactFirstNameValidation($firstName),
                    'LastName' => Ssbhesabfa_Validation::contactLastNameValidation($lastName),
                    'ContactType' => 1,
                    'NodeFamily' => $nodeFamily,
                    'Address' => Ssbhesabfa_Validation::contactAddressValidation($customer->get_shipping_address()),
                    'City' => Ssbhesabfa_Validation::contactCityValidation($customer->get_shipping_city()),
                    'State' => Ssbhesabfa_Validation::contactStateValidation($state_name),
                    'Country' => Ssbhesabfa_Validation::contactCountryValidation($country_name),
                    'PostalCode' => Ssbhesabfa_Validation::contactPostalCodeValidation($customer->get_shipping_postcode()),
                    'Phone' => Ssbhesabfa_Validation::contactPhoneValidation($customer->get_billing_phone()),
                    'Email' => Ssbhesabfa_Validation::contactEmailValidation($customer->get_email()),
                    'Tag' => json_encode(array('id_customer' => $id_customer)),
                    'Note' => __('Customer ID in OnlineStore: ', 'ssbhesabfa') . $id_customer,
                );
                break;
        }
        // $additionalFields =  self::getAdditionalCheckoutFileds($id_order);
        
        // $hesabfaCustomer['NationalCode'] = $additionalFields['NationalCode'] ?? null;
        // $hesabfaCustomer['EconomicCode'] = $additionalFields['EconomicCode'] ?? null;
        // $hesabfaCustomer['RegistrationNumber'] = $additionalFields['RegistrationNumber'] ?? null;
        // $hesabfaCustomer['Website'] = $additionalFields['Website'] ?? null;
        // // ...

        // HesabfaLogService::writeLogObj($hesabfaCustomer);
        return self::correctCustomerData($hesabfaCustomer);
    }

    public static function mapGuestCustomer($code, $id_order): array
    {
        $order = new WC_Order($id_order);

        $name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        if (empty($order->get_billing_first_name()) && empty($order->get_billing_last_name())) {
            $name = __('Guest Customer', 'ssbhesabfa');
        }
        $nodeFamily = get_option('ssbhesabfa_contact_automatic_save_node_family') == 'yes'? 'اشخاص :' . get_option('ssbhesabfa_contact_node_family') :null;


        $country_name = self::$countries[$order->get_billing_country()];
        $state_name = self::$states[$order->get_billing_country()][$order->get_billing_state()];

        $hesabfaCustomer = array(
            'Code' => $code,
            'Name' => $name,
            'FirstName' => Ssbhesabfa_Validation::contactFirstNameValidation($order->get_billing_first_name()),
            'LastName' => Ssbhesabfa_Validation::contactLastNameValidation($order->get_billing_last_name()),
            'ContactType' => 1,
            'NodeFamily' => $nodeFamily,
            'Address' => Ssbhesabfa_Validation::contactAddressValidation($order->get_billing_address_1() . ' ' . $order->get_billing_address_2()),
            'City' => Ssbhesabfa_Validation::contactCityValidation($order->get_billing_city()),
            'State' => Ssbhesabfa_Validation::contactStateValidation($state_name),
            'Country' => Ssbhesabfa_Validation::contactCountryValidation($country_name),
            'PostalCode' => Ssbhesabfa_Validation::contactPostalCodeValidation($order->get_billing_postcode()),
            'Phone' => Ssbhesabfa_Validation::contactPhoneValidation($order->get_billing_phone()),
            'Email' => Ssbhesabfa_Validation::contactEmailValidation($order->get_billing_email()),
            'Tag' => json_encode(array('id_customer' => 0)),
            'Note' => __('Customer registered as a GuestCustomer.', 'ssbhesabfa'),
        );
        // $additionalFields =  self::getAdditionalCheckoutFileds($id_order);
        
        // $hesabfaCustomer['NationalCode'] = $additionalFields['NationalCode'] ?? null;
        // $hesabfaCustomer['EconomicCode'] = $additionalFields['EconomicCode'] ?? null;
        // $hesabfaCustomer['RegistrationNumber'] = $additionalFields['RegistrationNumber'] ?? null;
        // $hesabfaCustomer['Website'] = $additionalFields['Website'] ?? null;
        // HesabfaLogService::writeLogObj($hesabfaCustomer);

        return self::correctCustomerData($hesabfaCustomer);
    }

    private static function getMobileFromPhone($phone) {
        if(preg_match("/^09\d{9}$/", $phone))
            return $phone;
        else if(preg_match("/^9\d{9}$/", $phone))
            return '0' . $phone;
        else if(preg_match("/^989\d{9}$/", $phone))
            return str_replace('98', '0' ,$phone);
        else return '';
    }

    private static function correctCustomerData($hesabfaCustomer) {
        if($hesabfaCustomer["Phone"] == '')
            unset($hesabfaCustomer["Phone"]);
        else {
            $mobile = self::getMobileFromPhone($hesabfaCustomer["Phone"]);
            if($mobile)
                $hesabfaCustomer["Mobile"] = $mobile;
        }
        if($hesabfaCustomer["Email"] == '')
            unset($hesabfaCustomer["Email"]);
        if($hesabfaCustomer["Address"] == '')
            unset($hesabfaCustomer["Address"]);
        if($hesabfaCustomer["PostalCode"] == '')
            unset($hesabfaCustomer["PostalCode"]);
        if($hesabfaCustomer["City"] == '')
            unset($hesabfaCustomer["City"]);
        if($hesabfaCustomer["State"] == '')
            unset($hesabfaCustomer["State"]);
        if($hesabfaCustomer["Country"] == '')
            unset($hesabfaCustomer["Country"]);
        return $hesabfaCustomer;
    }

    private static function getCountriesAndStates()
    {
        if (!isset(self::$countries)) {
            $countries_obj = new WC_Countries();
            self::$countries = $countries_obj->get_countries();
            self::$states = $countries_obj->get_states();
        }
    }

    private static function getNationalCode(){

    }

    private static function getAdditionalCheckoutFileds($id_order) {
        $NationalCode = '_billing_hesabfa_nationalcode';
        $EconomicCode = '_billing_hesabfa_economiccode';
        $RegistrationNumber = '_billing_hesabfa_registerationnumber';
        $Website = '_billing_hesabfa_website';

        $fields = array();

        HesabfaLogService::writeLogStr(get_post_meta( $id_order,'_billing_hesabfa_nationalcode'));


        $NationalCode_isActive = get_option('ssbhesabfa_contact_NationalCode_checkbox_hesabfa');
        $EconomicCode_isAactive = get_option('ssbhesabfa_contact_EconomicCode_checkbox_hesabfa');
        $RegistrationNumber_isActive = get_option('ssbhesabfa_contact_RegistrationNumber_checkbox_hesabfa');
        $Website_isActive = get_option('ssbhesabfa_contact_Website_checkbox_hesabfa');

       

        
        // add additional fileds to chechout
        if($add_additional_fileds == '1'){
            // add fileds form by hesabfa
            
            $fields['NationalCode'] = get_post_meta( $id_order,$NationalCode) ?? null;
            $fields['EconomicCode'] = get_post_meta( $id_order,$EconomicCode) ?? null;
            $fields['RegistrationNumber'] = get_post_meta( $id_order,$RegistrationNumber) ?? null;
            $fields['Website'] = get_post_meta( $id_order,$Website) ?? null;


        }elseif($add_additional_fileds == '2'){
            // add fields by other ways and get met
            $NationalCode = get_option('ssbhesabfa_contact_NationalCode_text_hesabfa');
            $EconomicCode = get_option('ssbhesabfa_contact_EconomicCode_text_hesabfa');
            $RegistrationNumber = get_option('ssbhesabfa_contact_RegistrationNumber_text_hesabfa');
            $Website = get_option('ssbhesabfa_contact_Website_text_hesabfa');
            
            if($NationalCode_isActive == 'yes')
                $fields['NationalCode'] = get_post_meta( $id_order,$NationalCode) ?? null;
            
            if($EconomicCode_isActive == 'yes')
                $fields['EconomicCode'] = get_post_meta( $id_order,$EconomicCode) ?? null;
            
            if($RegistrationNumber_isActive == 'yes')
            $fields['RegistrationNumber'] = get_post_meta( $id_order,$RegistrationNumber) ?? null;

          
            $fields['Website'] = get_post_meta( $id_order,$Website) ?? null;
        }

        // get postmeta fields 
        
        return $fields;

    
    }
}