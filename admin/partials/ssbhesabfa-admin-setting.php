<?php

include_once(plugin_dir_path(__DIR__) . 'services/HesabfaLogService.php');

/**
 * @class      Ssbhesabfa_Setting
 * @version    1.93.59
 * @since      1.0.0
 * @package    ssbhesabfa
 * @subpackage ssbhesabfa/admin/setting
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 * @author     HamidReza Gharahzadeh <hamidprime@gmail.com>
 */
class Ssbhesabfa_Setting
{

	/**
	 * Hook in methods
	 * @since    1.0.0
	 * @access   static
	 */
	public static function init()
	{
		add_action('ssbhesabfa_home_setting', array(__CLASS__, 'ssbhesabfa_home_setting'));

		add_action('ssbhesabfa_catalog_setting', array(__CLASS__, 'ssbhesabfa_catalog_setting'));
		add_action('ssbhesabfa_catalog_setting_save_field', array(__CLASS__, 'ssbhesabfa_catalog_setting_save_field'));

		add_action('ssbhesabfa_customers_setting', array(__CLASS__, 'ssbhesabfa_customers_setting'));
		add_action('ssbhesabfa_customers_setting_save_field', array(__CLASS__, 'ssbhesabfa_customers_setting_save_field'));

		add_action('ssbhesabfa_invoice_setting', array(__CLASS__, 'ssbhesabfa_invoice_setting'));
		add_action('ssbhesabfa_invoice_setting_save_field', array(__CLASS__, 'ssbhesabfa_invoice_setting_save_field'));

		add_action('ssbhesabfa_payment_setting', array(__CLASS__, 'ssbhesabfa_payment_setting'));
		add_action('ssbhesabfa_payment_setting_save_field', array(__CLASS__, 'ssbhesabfa_payment_setting_save_field'));

		add_action('ssbhesabfa_api_setting', array(__CLASS__, 'ssbhesabfa_api_setting'));
		add_action('ssbhesabfa_api_setting_save_field', array(__CLASS__, 'ssbhesabfa_api_setting_save_field'));

		add_action('ssbhesabfa_export_setting', array(__CLASS__, 'ssbhesabfa_export_setting'));

		add_action('ssbhesabfa_sync_setting', array(__CLASS__, 'ssbhesabfa_sync_setting'));

		add_action('ssbhesabfa_log_setting', array(__CLASS__, 'ssbhesabfa_log_setting'));
	}

	public static function ssbhesabfa_home_setting()
	{
		?>
        <h3 class="h3 hesabfa-tab-page-title mt-4"><?php esc_attr_e('Hesabfa Accounting', 'ssbhesabfa'); ?></h3>
        <p class="p mt-4 hesabfa-p hesabfa-f-12 ms-3"
           style="text-align: justify"><?php esc_attr_e('This module helps connect your (online) store to Hesabfa online accounting software. By using this module, saving products, contacts, and orders in your store will also save them automatically in your Hesabfa account. Besides that, just after a client pays a bill, the receipt document will be stored in Hesabfa as well. Of course, you have to register your account in Hesabfa first. To do so, visit Hesabfa at the link here www.hesabfa.com and sign up for free. After you signed up and entered your account, choose your business, then in the settings menu/API, you can find the API keys for the business and import them to the plugin’s settings. Now your module is ready to use.', 'ssbhesabfa'); ?></p>
        <p class="p hesabfa-p hesabfa-f-12"><?php esc_attr_e('For more information and a full guide to how to use Hesabfa and WooCommerce Plugin, visit Hesabfa’s website and go to the “Accounting School” menu.', 'ssbhesabfa'); ?></p>

        <div class="alert alert-danger hesabfa-f mt-4">
            <strong>هشدارها</strong>
            <br>
            <ul class="mt-2">
                <li> *
                    افزونه حسابفا از کد کالاها و مشتریان و از شماره فاکتور جهت شناسایی آنها استفاده می کند،
                    بنابراین پس از ثبت کالاها و مشتریان در حسابفا کد آنها را در حسابفا تغییر ندهید، و همچنین پس از ثبت
                    فاکتور،
                    شماره فاکتور را در حسابفا نباید تغییر دهید.
                </li>
                <li>
                    * با حذف افزونه از وردپرس، جدول ارتباط بین افزونه و حسابفا نیز از دیتابیس وردپرس حذف می شود
                    و کلیه ارتباطات از بین می رود.
                </li>
            </ul>
        </div>


        <div class="row" style="margin-left: 10px;">
            <div class="col">
                <h4 class="h4 hesabfa-tab-page-title mt-4"><?php esc_attr_e('Plugin Tutorial Video', 'ssbhesabfa'); ?></h4>
                <video controls poster="https://www.hesabfa.com/img/woocommerc-plugin-help-cover.jpg" id="hesabfa-tutorial-video" style="border: 1px solid gray" class="mt-3">
                    <source src="https://www.hesabfa.com/file/woocommerce/woocommerce-plugin-tutorial.mp4" type="video/mp4"></source>
                </video>
            </div>
            <div class="col-3">
                <h4 class="h4 hesabfa-tab-page-title mt-4 mb-3"><?php esc_attr_e('Titles', 'ssbhesabfa'); ?></h4>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(0)">
                    نصب افزونه
                    <br><strong class="text-info">00:00</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(75)">
                    اتصال افزونه به حسابفا
                    <br><strong class="text-info">01:15</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(246)">
                    تنظیمات افزونه
                    <br><strong class="text-info">04:06</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(520)">
                    تعریف محصول و لینک کردن محصول به حسابفا
                    <br><strong class="text-info">08:40</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1378)">
                    خروجی محصولات به حسابفا
                    <br><strong class="text-info">22:58</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1462)">
                    خروجی موجودی اول دوره محصولات به حسابفا
                    <br><strong class="text-info">24:22</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1495)">
                    خروجی مشتریان
                    <br><strong class="text-info">24:55</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1525)">
                    ورود محصولات از حسابفا به فروشگاه
                    <br><strong class="text-info">25:25</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1592)">
                    همسان سازی قیمت و موجودی محصولات
                    <br><strong class="text-info">26:32</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1645)">
                    همسان سازی سفارشات
                    <br><strong class="text-info">27:25</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1758)">
                    بروزرسانی محصولات در حسابفا بر اساس فروشگاه
                    <br><strong class="text-info">29:18</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(1805)">
                    سفارشات و ثبت فاکتور در حسابفا
                    <br><strong class="text-info">30:05</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(2120)">
                    پشتیبان گیری از جدول افزونه در دیتابیس
                    <br><strong class="text-info">35:20</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(2502)">
                    لاگ رویدادها و خطاها
                    <br><strong class="text-info">41:42</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(3046)">
                    حذف محصولات
                    <br><strong class="text-info">50:46</strong>
                </div>
                <div class="hesabfa-plugin-tutorial-chapter" onclick="hesabfaTutorialJumpTo(3143)">
                    محصول پیش نویس
                    <br><strong class="text-info">52:23</strong>
                </div>
            </div>
        </div>

		<?php
	}


	public static function ssbhesabfa_catalog_setting_fields()
	{
        $warehouses = Ssbhesabfa_Setting::ssbhesabfa_get_warehouses();

        $fields[] = array('title' => __('Catalog Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'catalog_options');

		$fields[] = array(
			'title' => __('Update Price', 'ssbhesabfa'),
			'desc' => __('Update Price after change in Hesabfa', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_item_update_price',
			'default' => 'no',
			'type' => 'checkbox'
		);

		$fields[] = array(
			'title' => __('Update Quantity', 'ssbhesabfa'),
			'desc' => __('Update Quantity after change in Hesabfa', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_item_update_quantity',
			'default' => 'no',
			'type' => 'checkbox'
		);

        $fields[] = array(
            'title' => __("Update product's quantity based on", 'ssbhesabfa'),
            'id' => 'ssbhesabfa_item_update_quantity_based_on',
            'type' => 'select',
            'options' => $warehouses,
        );

        $fields[] = array(
			'title' => "",
			'desc' => __('Do not submit product in Hesabfa automatically by saving product in woocommerce', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_do_not_submit_product_automatically',
			'default' => 'no',
			'type' => 'checkbox'
		);

		$fields[] = array(
			'title' => "",
			'desc' => __('Do not update product price in Hesabfa by editing product in woocommerce', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_do_not_update_product_price_in_hesabfa',
			'default' => 'no',
			'type' => 'checkbox'
		);

		$fields[] = array(
			'title' => "",
			'desc' => __('Do not update product barcode in Hesabfa by saving product in woocommerce', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_do_not_update_product_barcode_in_hesabfa',
			'default' => 'no',
			'type' => 'checkbox'
		);

		$fields[] = array(
			'title' => "",
			'desc' => __('Do not update product category in Hesabfa by saving product in woocommerce', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_do_not_update_product_category_in_hesabfa',
			'default' => 'no',
			'type' => 'checkbox'
		);

		$fields[] = array(
			'title' => "",
			'desc' => __('Do not update product code in Hesabfa by saving product in woocommerce', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_do_not_update_product_product_code_in_hesabfa',
			'default' => 'no',
			'type' => 'checkbox'
		);

        $options_to_update_sale_price = array();
        $options_to_update_sale_price[0] = __("The Sale price does not change", 'ssbhesabfa');
        $options_to_update_sale_price[1] = __("The Sale price gets removed", 'ssbhesabfa');
        $options_to_update_sale_price[2] = __("The sale price get changes in proportion to the regular price", 'ssbhesabfa');

        $fields[] = array(
            'title' => __("Update sale price", 'ssbhesabfa'),
            'id' => 'ssbhesabfa_item_update_sale_price',
            'type' => 'select',
            'options' => $options_to_update_sale_price,
        );

		$fields[] = array('type' => 'sectionend', 'id' => 'catalog_options');

		return $fields;
	}

	public static function ssbhesabfa_catalog_setting()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_catalog_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		?>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
			<?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit hesabfa-p">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
		<?php
	}

	public static function ssbhesabfa_catalog_setting_save_field()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_catalog_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		$Html_output->save_fields($ssbhesabf_setting_fields);
	}


	public static function ssbhesabfa_customers_setting_fields()
	{

		$fields[] = array('title' => __('Customers Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'customer_options');

		$fields[] = array(
			'title' => __('Update Customer Address', 'ssbhesabfa'),
			'desc' => __('Choose when update Customer address in Hesabfa.', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_contact_address_status',
			'type' => 'select',
			'options' => array('1' => __('Use first customer address', 'ssbhesabfa'), '2' => __('update address with Invoice address', 'ssbhesabfa'), '3' => __('update address with Delivery address', 'ssbhesabfa')),
		);

		$fields[] = array(
			'title' => __('Customer\'s Group', 'ssbhesabfa'),
			'desc' => __('Enter a Customer\'s Group in Hesabfa', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_contact_node_family',
			'type' => 'text',
			'default' => 'مشتریان فروشگاه آن‌لاین'
		);

        $fields[] = array(
            'title' => __('Save Customer\'s group', 'ssbhesabfa'),
            'desc' => __('Automatically save Customer\'s group in hesabfa', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_contact_automatic_save_node_family',
            'default' => 'yes',
            'type' => 'checkbox'
        );
        $fields[] = array(
            'title' => __('Customer\'s detail auto save and update', 'ssbhesabfa'),
            'desc' => __('Save and update Customer\'s detail automatically in hesabfa', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_contact_automatically_save_in_hesabfa',
            'type' => 'checkbox',
            'default' => 'yes'
        );
		$fields[] = array('type' => 'sectionend', 'id' => 'customer_options');

		return $fields;
	}

	public static function ssbhesabfa_customers_setting()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_customers_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		?>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
			<?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit hesabfa-p">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
		<?php
	}

	public static function ssbhesabfa_customers_setting_save_field()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_customers_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		$Html_output->save_fields($ssbhesabf_setting_fields);
	}


	public static function ssbhesabfa_invoice_setting_fields()
	{
		$projects = Ssbhesabfa_Setting::ssbhesabfa_get_projects();
		$salesmen = Ssbhesabfa_Setting::ssbhesabfa_get_salesmen();

		$fields[] = array('title' => __('Invoice Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'invoice_options');

		$fields[] = array(
			'title' => __('Add invoice in which status', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_invoice_status',
			'type' => 'multiselect',
			'options' => array(
				'pending' => __('Pending payment', 'ssbhesabfa'),
				'processing' => __('Processing', 'ssbhesabfa'),
				'on-hold' => __('On hold', 'ssbhesabfa'),
				'completed' => __('Completed', 'ssbhesabfa'),
				'cancelled' => __('Cancelled', 'ssbhesabfa'),
				'refunded' => __('Refunded', 'ssbhesabfa'),
				'failed' => __('Failed', 'ssbhesabfa'),
				'checkout-draft' => __('Draft', 'ssbhesabfa'),
			),
		);

		$fields[] = array(
			'title' => __('Return sale invoice status', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_invoice_return_status',
			'type' => 'multiselect',
			'options' => array(
				'pending' => __('Pending payment', 'ssbhesabfa'),
				'processing' => __('Processing', 'ssbhesabfa'),
				'on-hold' => __('On hold', 'ssbhesabfa'),
				'completed' => __('Completed', 'ssbhesabfa'),
				'cancelled' => __('Cancelled', 'ssbhesabfa'),
				'refunded' => __('Refunded', 'ssbhesabfa'),
				'failed' => __('Failed', 'ssbhesabfa'),
				'checkout-draft' => __('Draft', 'ssbhesabfa'),
			),
		);

		$fields[] = array(
			'title' => __("Invoice's Project", 'ssbhesabfa'),
			'id' => 'ssbhesabfa_invoice_project',
			'type' => 'select',
			'options' => $projects,
		);

		$fields[] = array(
			'title' => __("Invoice's Salesman", 'ssbhesabfa'),
			'id' => 'ssbhesabfa_invoice_salesman',
			'type' => 'select',
			'options' => $salesmen,
		);
        $fields[] = array(
            'title' => __('Invoice draft save', 'ssbhesabfa'),
            'desc' => __('Invoice registration in draft mode in hesabfa', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_invoice_draft_save_in_hesabfa',
            'type' => 'checkbox',
            'default' => 'no'
        );


        $fields[] = array('type' => 'sectionend', 'id' => 'invoice_options');

		return $fields;
	}

	public static function ssbhesabfa_invoice_setting()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_invoice_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		?>
        <div class="alert alert-warning hesabfa-f">
            <strong>توجه</strong><br>
            در اینجا تعیین کنید که فاکتور سفارش در چه مرحله ای در حسابفا ثبت شود.
            و چه زمان برای یک سفارش فاکتور برگشت از فروش ثبت شود.
        </div>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
			<?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit hesabfa-p">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
		<?php
	}

	public static function ssbhesabfa_invoice_setting_save_field()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_invoice_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		$Html_output->save_fields($ssbhesabf_setting_fields);
	}

	public static function ssbhesabfa_payment_setting_fields()
	{
		$banks = Ssbhesabfa_Setting::ssbhesabfa_get_banks();

        $payment_gateways = new WC_Payment_Gateways;
		$available_payment_gateways = $payment_gateways->get_available_payment_gateways();

        $fields[] = array('title' => __('Payment methods Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'payment_options');

		$fields[] = array(
			'title' => __('Add payment in which status', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_payment_status',
			'type' => 'multiselect',
			'options' => array(
				'pending' => __('Pending payment', 'ssbhesabfa'),
				'processing' => __('Processing', 'ssbhesabfa'),
				'on-hold' => __('On hold', 'ssbhesabfa'),
				'completed' => __('Completed', 'ssbhesabfa'),
				'cancelled' => __('Cancelled', 'ssbhesabfa'),
				'refunded' => __('Refunded', 'ssbhesabfa'),
				'failed' => __('Failed', 'ssbhesabfa'),
				'checkout-draft' => __('Draft', 'ssbhesabfa'),
			),
		);

		foreach ($available_payment_gateways as $gateway) {
			$fields[] = array(
				'title' => $gateway->title,
				'id' => 'ssbhesabfa_payment_method_' . $gateway->id,
				'type' => 'select',
				'options' => $banks,
			);
		}

		$fields[] = array('type' => 'sectionend', 'id' => 'payment_options');

		return $fields;
	}

	public static function ssbhesabfa_payment_setting()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_payment_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		?>
        <div class="alert alert-warning hesabfa-f">
            <strong>توجه</strong><br>
            در اینجا تعیین کنید که رسید دریافت وجه فاکتور در چه وضعیتی ثبت شود
            و در هر روش پرداخت، رسید در چه بانکی ثبت شود.
        </div>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
			<?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit hesabfa-p">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
		<?php
	}

	public static function ssbhesabfa_payment_setting_save_field()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_payment_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		$Html_output->save_fields($ssbhesabf_setting_fields);
	}


	public static function ssbhesabfa_api_setting_fields()
	{
		$fields[] = array('title' => __('API Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'api_options');

		$fields[] = array(
			'title' => __('API Key', 'ssbhesabfa'),
			'desc' => __('Find API key in Setting->Financial Settings->API Menu', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_account_api',
			'type' => 'text',
		);

		$fields[] = array(
			'title' => __('Email', 'ssbhesabfa'),
			'desc' => __('Enter a Hesabfa email account', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_account_username',
			'type' => 'email',
		);

		$fields[] = array(
			'title' => __('Password', 'ssbhesabfa'),
			'desc' => __('Enter a Hesabfa password', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_account_password',
			'type' => 'password',
		);

		$fields[] = array(
			'title' => __('Login token', 'ssbhesabfa'),
			'desc' => __('Find Login token in Setting->Financial Settings->API Menu', 'ssbhesabfa'),
			'id' => 'ssbhesabfa_account_login_token',
			'type' => 'text',
		);

        $fields[] = array(
            'title' => __("API Address", 'ssbhesabfa'),
            'id' => 'ssbhesabfa_api_address',
            'type' => 'select',
            'options' => array("Server 1 (Cloudflare)","Server 2 (Arvancloud)")
        );

		$fields[] = array('type' => 'sectionend', 'id' => 'api_options');

		return $fields;
	}

	public static function ssbhesabfa_api_setting()
	{
		$businessInfo = self::getSubscriptionInfo();
		$isBusinessInfo = false;
		if ($businessInfo["expireDate"] != '' && $businessInfo["expireDate"] != null) {
			$isBusinessInfo = true;
			$expireDate = strtotime($businessInfo["expireDate"]);
			$expireDateStr = date("Y/m/d", $expireDate);
		}

		$ssbhesabf_setting_fields = self::ssbhesabfa_api_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		?>
        <div class="alert alert-warning hesabfa-f">
            <strong>توجه</strong><br>
            <ul class="mx-4" style="list-style-type:square">
                <li>
                    برای اتصال به API حسابفا و فعال شدن این افزونه باید در اینجا
                    کلید API و توکن ورود به کسب و کار خود را وارد کنید.
                </li>
                <li>
                    اگر برای اتصال به API حسابفا از توکن ورود استفاده کنید
                    نیازی به وارد کردن ایمیل و رمز عبور نیست.
                </li>
                <li>
                    برای پیدا کردن توکن ورود و کلید API، در حسابفا به قسمت تنظیمات، تنظیمات API مراجعه کنید.
                </li>
                <li>
                    اگر می خواهید کسب و کار دیگری را به افزونه متصل کنید، ابتدا باید یک بار افزونه را
                    حذف و مجدد نصب کنید تا جدول ارتباطات کسب و کار قبلی با افزونه حذف گردد.
                </li>
            </ul>
        </div>
        <div class="card hesabfa-card hesabfa-f <?php echo $isBusinessInfo ? '' : 'd-none' ?>">
            <strong>اطلاعات کسب و کار</strong>
            <div class="row mt-2">
                <div class="col">نام کسب و کار:</div>
                <div class="col text-info fw-bold"><?php echo $businessInfo["businessName"] ?></div>
                <div class="col">طرح:</div>
                <div class="col text-info fw-bold"><?php echo $businessInfo["plan"] ?></div>
            </div>
            <div class="row mt-2">
                <div class="col">اعتبار سند:</div>
                <div class="col text-info fw-bold"><?php echo $businessInfo["credit"] ?></div>
                <div class="col">تاریخ انقضا:</div>
                <div class="col text-info fw-bold"><?php echo $expireDateStr ?></div>
            </div>
        </div>

        <div class="alert alert-danger hesabfa-f mt-2" id="changeBusinessWarning">
            <strong>هشدار</strong><br>
            برای اتصال یک کسب و کار دیگر به افزونه، ابتدا باید یک بار افزونه را حذف و مجدد
            نصب کنید تا جدول ارتباطات افزونه با کسب و کار قبل حذف گردد.
        </div>

        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
			<?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit hesabfa-p">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
		<?php
	}

	public static function ssbhesabfa_api_setting_save_field()
	{
		$ssbhesabf_setting_fields = self::ssbhesabfa_api_setting_fields();
		$Html_output = new Ssbhesabfa_Html_output();
		$Html_output->save_fields($ssbhesabf_setting_fields);

		Ssbhesabfa_Setting::ssbhesabfa_set_webhook();
	}

	public static function ssbhesabfa_export_setting()
	{
		// Export - Bulk product export offers
		$productExportResult = (isset($_GET['productExportResult'])) ? wc_clean($_GET['productExportResult']) : null;
		$productImportResult = (isset($_GET['productImportResult'])) ? wc_clean($_GET['productImportResult']) : null;
		$error = (isset($_GET['error'])) ? wc_clean($_GET['error']) : null;

		if (!is_null($productExportResult) && $productExportResult === 'true') {
			$processed = (isset($_GET['processed'])) ? wc_clean($_GET['processed']) : null;
			if ($processed == 0) {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . __('No products were exported, All products were exported or there are no product', 'ssbhesabfa');
				echo '</div>';
			} else {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . sprintf(__('Export products completed. %s products added/updated.', 'ssbhesabfa'), $processed);
				echo '</div>';
			}
		} elseif ($productExportResult === 'false') {
			if (!is_null($error) && $error === '-1') {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . __('Export products fail. Hesabfa has already contained products.', 'ssbhesabfa');
				echo '</div>';
			} else {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . __('Export products fail. Please check the log file.', 'ssbhesabfa');
				echo '</div>';
			}
		}

		if (!is_null($productImportResult) && $productImportResult === 'true') {
			$processed = (isset($_GET['processed'])) ? wc_clean($_GET['processed']) : null;
			if ($processed == 0) {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . __('No products were imported, All products were imported or there are no product', 'ssbhesabfa');
				echo '</div>';
			} else {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . sprintf(__('Import products completed. %s products added/updated.', 'ssbhesabfa'), $processed);
				echo '</div>';
			}
		} elseif ($productImportResult === 'false') {
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . __('Import products fail. Please check the log file.', 'ssbhesabfa');
			echo '</div>';
		}

		// Export - Product opening quantity export offers
		$productOpeningQuantityExportResult = (isset($_GET['productOpeningQuantityExportResult'])) ? wc_clean($_GET['productOpeningQuantityExportResult']) : null;
		if (!is_null($productOpeningQuantityExportResult) && $productOpeningQuantityExportResult === 'true') {
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . __('Export product opening quantity completed.', 'ssbhesabfa');
			echo '</div>';
		} elseif (!is_null($productOpeningQuantityExportResult) && $productOpeningQuantityExportResult === 'false') {
			$shareholderError = (isset($_GET['shareholderError'])) ? wc_clean($_GET['shareholderError']) : null;
			$noProduct = (isset($_GET['noProduct'])) ? wc_clean($_GET['noProduct']) : null;
			if ($shareholderError == 'true') {
				echo '<div class="error">';
				echo '<p class="hesabfa-p">' . __('Export product opening quantity fail. No Shareholder exists, Please define Shareholder in Hesabfa', 'ssbhesabfa');
				echo '</div>';
			} elseif ($noProduct == 'true') {
				echo '<div class="error">';
				echo '<p class="hesabfa-p">' . __('No product available for Export product opening quantity.', 'ssbhesabfa');
				echo '</div>';
			} else {
				echo '<div class="error">';
				echo '<p class="hesabfa-p">' . __('Export product opening quantity fail. Please check the log file.', 'ssbhesabfa');
				echo '</div>';
			}
		}

		// Export - Bulk customer export offers
		$customerExportResult = (isset($_GET['customerExportResult'])) ? wc_clean($_GET['customerExportResult']) : null;

		if (!is_null($customerExportResult) && $customerExportResult === 'true') {
			$processed = (isset($_GET['processed'])) ? wc_clean($_GET['processed']) : null;
			if ($processed == 0) {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . __('No customers were exported, All customers were exported or there are no customer', 'ssbhesabfa');
				echo '</div>';
			} else {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . sprintf(__('Export customers completed. %s customers added.', 'ssbhesabfa'), $processed);
				echo '</div>';
			}
		} elseif (!is_null($customerExportResult) && $customerExportResult === 'false') {
			if (!is_null($error) && $error === '-1') {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . __('Export customers fail. Hesabfa has already contained customers.', 'ssbhesabfa');
				echo '</div>';
			} else {
				echo '<div class="updated">';
				echo '<p class="hesabfa-p">' . __('Export customers fail. Please check the log file.', 'ssbhesabfa');
				echo '</div>';
			}
		}

		?>
        <div class="notice notice-info">
            <p class="hesabfa-p"><?php echo __('Export can take several minutes.', 'ssbhesabfa') ?></p>
        </div>
        <br>
        <form class="card hesabfa-card" id="ssbhesabfa_export_products" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=export'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-export-product-submit"></label>
                    <div>
                        <button class="button button-primary hesabfa-f" id="ssbhesabfa-export-product-submit"
                                name="ssbhesabfa-export-product-submit"><?php echo __('Export Products', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2"><?php echo __('Export and add all online store products to Hesabfa', 'ssbhesabfa'); ?></p>
                <div class="progress mt-1 mb-2" style="height: 5px; max-width: 400px; border: 1px solid silver"
                     id="exportProductsProgress">
                    <div class="progress-bar progress-bar-striped bg-success" id="exportProductsProgressBar"
                         role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
                <div class="p-2 hesabfa-f">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات محصولات لینک نشده از فروشگاه وارد حسابفا می شوند.</li>
                        <li>اگر محصولات از قبل هم در فروشگاه تعریف شده اند و هم در حسابفا و به هم لینک نشده اند باید از
                            گزینه
                            همسان سازی دستی محصولات استفاده کنید.
                        </li>
                        <li>با انجام این عملیات موجودی محصولات وارد حسابفا نمی شود و برای وارد کردن موجودی محصولات
                            فروشگاه
                            در حسابفا، باید از گزینه استخراج موجودی اول دوره استفاده کنید.
                        </li>
                    </ul>
                </div>
            </div>
        </form>

        <form class="card hesabfa-card hesabfa-f" id="ssbhesabfa_export_products_opening_quantity" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=export'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-export-product-opening-quantity-submit"></label>
                    <div>
                        <button class="button button-primary hesabfa-f"
                                id="ssbhesabfa-export-product-opening-quantity-submit"
                                name="ssbhesabfa-export-product-opening-quantity-submit"<?php if (get_option('ssbhesabfa_use_export_product_opening_quantity') == true) echo 'disabled'; ?>><?php echo __('Export Products opening quantity', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2"><?php echo __('Export the products quantity and record the \'products opening quantity\' in the Hesabfa', 'ssbhesabfa'); ?></p>
                <div class="progress mt-1 mb-2" style="height: 5px; max-width: 400px; border: 1px solid silver"
                     id="exportProductsOpeningQuantityProgress">
                    <div class="progress-bar progress-bar-striped bg-success"
                         id="exportProductsOpeningQuantityProgressBar" role="progressbar" style="width: 0%;"
                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="p-2 hesabfa-f">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات موجودی کنونی محصولات در فروشگاه بعنوان موجودی اول دوره محصولات در حسابفا
                            ثبت می شوند.
                        </li>
                        <li>بطور کلی فقط یک بار باید از این گزینه استفاده کنید،
                            که این کار باید پس از خروج محصولات به حسابفا و یا پس از همسان سازی دستی تمام محصولات
                            انجام شود.
                        </li>
                    </ul>
                </div>
            </div>
        </form>

        <form class="card hesabfa-card hesabfa-f" id="ssbhesabfa_export_customers" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=export'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-export-customer-submit"></label>
                    <div>
                        <button class="button button-primary hesabfa-f" id="ssbhesabfa-export-customer-submit"
                                name="ssbhesabfa-export-customer-submit"><?php echo __('Export Customers', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2"><?php echo __('Export and add all online store customers to Hesabfa.', 'ssbhesabfa'); ?></p>
                <div class="progress mt-1 mb-2" style="height: 5px; max-width: 400px; border: 1px solid silver"
                     id="exportCustomersProgress">
                    <div class="progress-bar progress-bar-striped bg-success" id="exportCustomersProgressBar"
                         role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
                <div class="p-2 hesabfa-f">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات مشتریان لینک نشده از فروشگاه وارد حسابفا می شوند.</li>
                        <li>
                            اگر یک مشتری بیش از یک بار وارد حسابفا شده است می توانید از گزینه ادغام تراکنش ها در حسابفا
                            استفاده کنید.
                        </li>
                    </ul>
                </div>
            </div>
        </form>

        <form class="card hesabfa-card hesabfa-f" id="ssbhesabfa_import_products" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=export'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-import-product-submit"></label>
                    <div>
                        <button class="button button-primary hesabfa-f" id="ssbhesabfa-import-product-submit"
                                name="ssbhesabfa-import-product-submit"><?php echo __('Import Products', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2">
					<?php echo __('Import and add all products from Hesabfa to online store', 'ssbhesabfa'); ?>
                </p>
                <div class="progress mt-1 mb-2" style="height: 5px; max-width: 400px; border: 1px solid silver"
                     id="importProductsProgress">
                    <div class="progress-bar progress-bar-striped bg-success" id="importProductsProgressBar"
                         role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
                <div class="p-2">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات محصولات لینک نشده از حسابفا وارد فروشگاه می شوند.</li>
                        <li>اگر محصولات از قبل هم در فروشگاه تعریف شده اند و هم در حسابفا و به هم لینک نشده اند باید از
                            گزینه
                            همسان سازی دستی محصولات استفاده کنید.
                        </li>
                        <li>محصولات در وضعیت خصوصی وارد فروشگاه می شوند و سپس هر زمان مایل بودید می توانید وضعیت را به
                            منتشر شده تغییر دهید.
                        </li>
                        <li>تمامی محصولات بعنوان محصول ساده (و نه متغیر) وارد فروشگاه می شوند.</li>
                    </ul>
                </div>
            </div>
        </form>
		<?php
	}

	public static function ssbhesabfa_sync_setting()
	{
		$result = self::getProductsCount();
		$storeProductsCount = $result["storeProductsCount"];
		$hesabfaProductsCount = $result["hesabfaProductsCount"];
		$linkedProductsCount = $result["linkedProductsCount"];

		// Sync - Bulk changes sync offers
		$changesSyncResult = (isset($_GET['changesSyncResult'])) ? wc_clean($_GET['changesSyncResult']) : false;
		if (!is_null($changesSyncResult) && $changesSyncResult == 'true') {
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . __('Sync completed, All hesabfa changes synced successfully.', 'ssbhesabfa');
			echo '</div>';
		}

		// Sync - Bulk product sync offers
		$productSyncResult = (isset($_GET['productSyncResult'])) ? wc_clean($_GET['productSyncResult']) : null;
		if (!is_null($productSyncResult) && $productSyncResult == 'true') {
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . __('Sync completed, All products price/quantity synced successfully.', 'ssbhesabfa');
			echo '</div>';
		} elseif (!is_null($productSyncResult) && !$productSyncResult == 'false') {
			echo '<div class="error">';
			echo '<p class="hesabfa-p">' . __('Sync products fail. Please check the log file.', 'ssbhesabfa');
			echo '</div>';
		}

		// Sync - Bulk invoice sync offers
		$orderSyncResult = (isset($_GET['orderSyncResult'])) ? wc_clean($_GET['orderSyncResult']) : null;

		if (!is_null($orderSyncResult) && $orderSyncResult === 'true') {
			$processed = (isset($_GET['processed'])) ? wc_clean($_GET['processed']) : null;
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . sprintf(__('Order sync completed. %s order added.', 'ssbhesabfa'), $processed);
			echo '</div>';
		} elseif (!is_null($orderSyncResult) && $orderSyncResult === 'false') {
			$fiscal = (isset($_GET['fiscal'])) ? wc_clean($_GET['fiscal']) : false;

			if ($fiscal === 'true') {
				echo '<div class="error">';
				echo '<p class="hesabfa-p">' . __('The date entered is not within the fiscal year.', 'ssbhesabfa');
				echo '</div>';
			} else {
				echo '<div class="error">';
				echo '<p class="hesabfa-p">' . __('Cannot sync orders. Please enter valid Date format.', 'ssbhesabfa');
				echo '</div>';
			}
		}

		// Sync - Bulk product update
		$productUpdateResult = (isset($_GET['$productUpdateResult'])) ? wc_clean($_GET['$productUpdateResult']) : null;
		if (!is_null($productUpdateResult) && $productUpdateResult == 'true') {
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . __('Update completed successfully.', 'ssbhesabfa');
			echo '</div>';
		} elseif (!is_null($productUpdateResult) && !$productUpdateResult == 'false') {
			echo '<div class="error">';
			echo '<p class="hesabfa-p">' . __('Update failed. Please check the log file.', 'ssbhesabfa');
			echo '</div>';
		}
		?>

        <div class="notice notice-info mt-3">
            <p class="hesabfa-p"><?php echo __('Number of products in store:', 'ssbhesabfa') . ' <b>' . $storeProductsCount . '</b>' ?></p>
            <p class="hesabfa-p"><?php echo __('Number of products in hesabfa:', 'ssbhesabfa') . ' <b>' . $hesabfaProductsCount . '</b>' ?></p>
            <p class="hesabfa-p"><?php echo __('Number of linked products:', 'ssbhesabfa') . ' <b>' . $linkedProductsCount . '</b>' ?></p>
        </div>

        <div class="notice notice-info">
            <p class="hesabfa-p"><?php echo __('Sync can take several minutes.', 'ssbhesabfa') ?></p>
        </div>

        <br>
        <form class="card hesabfa-card hesabfa-f d-none" id="ssbhesabfa_sync_changes" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-sync-changes-submit"></label>
                    <div>
                        <button class="button button-primary hesabfa-f" id="ssbhesabfa-sync-changes-submit"
                                name="ssbhesabfa-sync-changes-submit"><?php echo esc_attr_e('Sync Changes', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2"><?php echo __('Sync all Hesabfa changes with Online Store.', 'ssbhesabfa'); ?></p>
                <div class="p-2 hesabfa-f">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات کالاها، مشتریان و سفارشاتی که تا کنون در حسابفا ثبت نشده اند در حسابفا
                            ثبت می شوند.
                        </li>
                        <li>توجه کنید که بصورت نرمال با فعالسازی افزونه و تکمیل تنظیمات API
                            این همسان سازی بصورت خودکار انجام می شود و این گزینه صرفاْ برای مواقعی است که به دلایل فنی
                            مثل قطع اتصال فروشگاه با حسابفا و یا خطا و باگ این همسان سازی صورت نگرفته است.
                        </li>
                    </ul>
                </div>
            </div>
        </form>

        <form class="card hesabfa-card hesabfa-f" id="ssbhesabfa_sync_products" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-sync-products-submit"></label>
                    <div>
                        <button class="button button-primary hesabfa-f" id="ssbhesabfa-sync-products-submit"
                                name="ssbhesabfa-sync-products-submit"><?php echo __('Sync Products Quantity and Price', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2"><?php echo __('Sync quantity and price of products in hesabfa with online store.', 'ssbhesabfa'); ?></p>
                <div class="progress mt-1 mb-2" style="height: 5px; max-width: 400px; border: 1px solid silver"
                     id="syncProductsProgress">
                    <div class="progress-bar progress-bar-striped bg-success" id="syncProductsProgressBar"
                         role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
                <div class="p-2 hesabfa-f">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات موجودی و قیمت محصولات در فروشگاه، بر اساس قیمت و موجودی آنها در حسابفا
                            تنظیم می شود.
                        </li>
                        <li>این عملیات بر اساس تنظیمات صورت گرفته در تب محصولات انجام می شود.</li>
                    </ul>
                </div>
            </div>
        </form>

        <form class="card hesabfa-card hesabfa-f" id="ssbhesabfa_sync_orders" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-sync-orders-submit"></label>
                    <div>
                        <input type="date" id="ssbhesabfa_sync_order_date" name="ssbhesabfa_sync_order_date" value=""
                               class="datepicker"/>
                        <button class="button button-primary hesabfa-f" id="ssbhesabfa-sync-orders-submit"
                                name="ssbhesabfa-sync-orders-submit"><?php echo __('Sync Orders', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2"><?php echo __('Sync/Add orders in online store with hesabfa from above date.', 'ssbhesabfa'); ?></p>
                <div class="progress mt-1 mb-2" style="height: 5px; max-width: 400px; border: 1px solid silver"
                     id="syncOrdersProgress">
                    <div class="progress-bar progress-bar-striped bg-success" id="syncOrdersProgressBar"
                         role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
                <div class="p-2 hesabfa-f">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات سفارشات فروشگاه که در حسابفا ثبت نشده اند از تاریخ انتخاب شده بررسی و در
                            حسابفا ثبت می شوند.
                        </li>
                        <li>توجه کنید که بصورت نرمال با فعالسازی افزونه و تکمیل تنظیمات API
                            این همسان سازی بصورت خودکار انجام می شود و این گزینه صرفاْ برای مواقعی است که به دلایل فنی
                            مثل قطع اتصال فروشگاه با حسابفا و یا خطا و باگ این همسان سازی صورت نگرفته است.
                        </li>
                    </ul>
                </div>
            </div>
        </form>

        <form class="card hesabfa-card hesabfa-f" id="ssbhesabfa_update_products" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-update-products-submit"></label>
                    <div>
                        <button class="button button-primary hesabfa-f" id="ssbhesabfa-update-products-submit"
                                name="ssbhesabfa-update-products-submit"><?php echo __('Update Products in Hesabfa based on store', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p class="hesabfa-p mt-2"><?php echo __('Update products in hesabfa based on products definition in store.', 'ssbhesabfa'); ?></p>
                <div class="progress mt-1 mb-2" style="height: 5px; max-width: 400px; border: 1px solid silver"
                     id="updateProductsProgress">
                    <div class="progress-bar progress-bar-striped bg-success" id="updateProductsProgressBar"
                         role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
                <div class="p-2 hesabfa-f">
                    <label class="fw-bold mb-2">نکات مهم:</label>
                    <ul>
                        <li>با انجام این عملیات ویژگی محصولات مثل نام و قیمت در حسابفا، بر اساس فروشگاه بروزرسانی می
                            شود.
                        </li>
                        <li>در این عملیات موجودی کالا در حسابفا تغییری نمی کند و بروز رسانی نمی شود.</li>
                    </ul>
                </div>
            </div>
        </form>

		<?php
	}

	public static function getProductsCount()
	{
		$storeProductsCount = self::getProductCountsInStore();
		$hesabfaProductsCount = self::getProductCountsInHesabfa();
		$linkedProductsCount = self::getLinkedProductsCount();

		return array("storeProductsCount" => $storeProductsCount,
			"hesabfaProductsCount" => $hesabfaProductsCount,
			"linkedProductsCount" => $linkedProductsCount);
	}

	public static function getProductCountsInHesabfa()
	{
		$hesabfa = new Ssbhesabfa_Api();

		$filters = array(array("Property" => "ItemType", "Operator" => "=", "Value" => 0));

		$response = $hesabfa->itemGetItems(array('Take' => 1, 'Filters' => $filters));
		if ($response->Success) {
			return $response->Result->FilteredCount;
		} else return 0;
	}

	public static function getLinkedProductsCount()
	{
		global $wpdb;
		return $wpdb->get_var("SELECT COUNT(*) FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `obj_type` = 'product'");
	}

	public static function getProductCountsInStore()
	{
		global $wpdb;
		return $wpdb->get_var("SELECT COUNT(*) FROM `" . $wpdb->prefix . "posts` WHERE `post_type` IN ('product','product_variation') AND `post_status` IN ('publish', 'private', 'draft')  ");
	}

	public static function getSubscriptionInfo()
	{
		$businessName = '';
		$credit = '';
		$expireDate = '';
		$plan = '';

		$hesabfa = new Ssbhesabfa_Api();
		$response = $hesabfa->settingGetSubscriptionInfo();
		if ($response->Success) {
			$businessName = $response->Result->Name;
			$credit = $response->Result->Credit;
			$expireDate = $response->Result->ExpireDate;
			$plan = $response->Result->Subscription;
		}

		return array("businessName" => $businessName,
			"credit" => $credit,
			"expireDate" => $expireDate,
			"plan" => $plan);
	}

	public static function ssbhesabfa_set_webhook()
	{
		$url = get_site_url() . '/index.php?ssbhesabfa_webhook=1&token=' . substr(wp_hash(AUTH_KEY . 'ssbhesabfa/webhook'), 0, 10);

		$hookPassword = get_option('ssbhesabfa_webhook_password');

		$ssbhesabfa_api = new Ssbhesabfa_Api();
		$response = $ssbhesabfa_api->settingSetChangeHook($url, $hookPassword);

		if (is_object($response)) {
			if ($response->Success) {
				update_option('ssbhesabfa_live_mode', 1);
				update_option('ssbhesabfa_business_expired', 0);

				//set the last log ID if is not set
				$lastChanges = get_option('ssbhesabfa_last_log_check_id');
				if (!$lastChanges) $lastChanges = 0;
				$changes = $ssbhesabfa_api->settingGetChanges($lastChanges);
				if ($changes->Success) {
					if (get_option('ssbhesabfa_last_log_check_id') == 0) {
						$lastChange = end($changes->Result);
						update_option('ssbhesabfa_last_log_check_id', $lastChange->Id);
					}
				} else {
					echo '<div class="error">';
					echo '<p class="hesabfa-p">' . __('Cannot check the last change ID. Error Message: ', 'ssbhesabfa') . $changes->ErrorMessage . '</p>';
					echo '</div>';

					HesabfaLogService::log(array("Cannot get item changes. Error Message: $changes->ErrorMessage. Error Code: $changes->ErrorCode"));
				}


				//check if date in fiscalYear
				if (Ssbhesabfa_Admin_Functions::isDateInFiscalYear(date('Y-m-d H:i:s')) === 0) {
					echo '<div class="error">';
					echo '<p class="hesabfa-p">' . __('The fiscal year has passed or not arrived. Please check the fiscal year settings in Hesabfa.', 'ssbhesabfa') . '</p>';
					echo '</div>';

					update_option('ssbhesabfa_live_mode', 0);
				}

				//check the Hesabfa default currency
				$default_currency = $ssbhesabfa_api->settingGetCurrency();
				if ($default_currency->Success) {
					$woocommerce_currency = get_woocommerce_currency();
					$hesabfa_currency = $default_currency->Result->Currency;
					if ($hesabfa_currency == $woocommerce_currency || ($hesabfa_currency == 'IRR' && $woocommerce_currency == 'IRT') || ($hesabfa_currency == 'IRT' && $woocommerce_currency == 'IRR')) {
						update_option('ssbhesabfa_hesabfa_default_currency', $hesabfa_currency);
					} else {
						update_option('ssbhesabfa_hesabfa_default_currency', 0);
						update_option('ssbhesabfa_live_mode', 0);

						echo '<div class="error">';
						echo '<p class="hesabfa-p">' . __('Hesabfa and WooCommerce default currency must be same.', 'ssbhesabfa');
						echo '</div>';
					}
				} else {
					echo '<div class="error">';
					echo '<p class="hesabfa-p">' . __('Cannot check the Hesabfa default currency. Error Message: ', 'ssbhesabfa') . $default_currency->ErrorMessage . '</p>';
					echo '</div>';

					HesabfaLogService::log(array("Cannot check the Hesabfa default currency. Error Message: $default_currency->ErrorMessage. Error Code: $default_currency->ErrorCode"));
				}

				if (get_option('ssbhesabfa_live_mode')) {
					echo '<div class="updated">';
					echo '<p class="hesabfa-p">' . __('API Setting updated. Test Successfully', 'ssbhesabfa') . '</p>';
					echo '</div>';
				}
			} else {
				update_option('ssbhesabfa_live_mode', 0);

				if ($response->ErrorCode === 108) {
					echo '<div class="error">';
					echo '<p class="hesabfa-p">' . __('Cannot connect to Hesabfa. Business expired.', 'ssbhesabfa') . $response->ErrorMessage . '</p>';
					echo '</div>';
					update_option('ssbhesabfa_business_expired', 1);
				} else {
					echo '<div class="error">';
					echo '<p class="hesabfa-p">' . __('Cannot set Hesabfa webHook. Error Message:', 'ssbhesabfa') . $response->ErrorMessage . '</p>';
					echo '</div>';
					update_option('ssbhesabfa_business_expired', 0);
				}

				HesabfaLogService::log(array("Cannot set Hesabfa webHook. Error Message: $response->ErrorMessage. Error Code: $response->ErrorCode"));
			}
		} else {
			update_option('ssbhesabfa_live_mode', 0);

			echo '<div class="error">';
			echo '<p class="hesabfa-p">' . __('Cannot connect to Hesabfa servers. Please check your Internet connection', 'ssbhesabfa') . '</p>';
			echo '</div>';

			HesabfaLogService::log(array("Cannot connect to Hesabfa servers. Please check your Internet connection"));
		}

		return $response;
	}

	public static function ssbhesabfa_get_banks()
	{
		$ssbhesabfa_api = new Ssbhesabfa_Api();
		$banks = $ssbhesabfa_api->settingGetBanks();

		if (is_object($banks) && $banks->Success) {
			$available_banks = array();
			$available_banks[-1] = __('No need to set!', 'ssbhesabfa');
			foreach ($banks->Result as $bank) {
				if ($bank->Currency == get_woocommerce_currency() || (get_woocommerce_currency() == 'IRT' && $bank->Currency == 'IRR') || (get_woocommerce_currency() == 'IRR' && $bank->Currency == 'IRT')) {
					$available_banks[$bank->Code] = $bank->Name . ' - ' . $bank->Branch . ' - ' . $bank->AccountNumber;
				}
			}

			if (empty($available_banks)) {
				$available_banks[0] = __('Define at least one bank in Hesabfa', 'ssbhesabfa');
			}

			return $available_banks;
		} else {
			update_option('ssbhesabfa_live_mode', 0);

			echo '<div class="error">';
			echo '<p class="hesabfa-p">' . __('Cannot get Banks detail.', 'ssbhesabfa') . '</p>';
			echo '</div>';

			HesabfaLogService::log(array("Cannot get banks detail. Error Code: $banks->ErrorCode. Error Message: $banks->ErrorMessage."));
			return array('0' => __('Cannot get Banks detail.', 'ssbhesabfa'));
		}
	}

	public static function ssbhesabfa_get_projects()
	{
		$ssbhesabfa_api = new Ssbhesabfa_Api();
		$projects = $ssbhesabfa_api->settingGetProjects();

		if (is_object($projects) && $projects->Success) {
			$available_projects = array();
			$available_projects[-1] = __('No need to set!', 'ssbhesabfa');
			foreach ($projects->Result as $project) {
                if($project->Active)
				    $available_projects[$project->Title] = $project->Title;
			}
			return $available_projects;
		} else {
			update_option('ssbhesabfa_live_mode', 0);
			echo '<div class="error">';
			echo '<p class="hesabfa-p">' . __('Cannot get Projects detail.', 'ssbhesabfa') . '</p>';
			echo '</div>';
			HesabfaLogService::log(array("Cannot get projects detail. Error Code: $projects->ErrorCode. Error Message: $projects->ErrorMessage."));
			return array('0' => __('Cannot get projects detail.', 'ssbhesabfa'));
		}
	}

	public static function ssbhesabfa_get_salesmen()
	{
		$ssbhesabfa_api = new Ssbhesabfa_Api();
		$salesmen = $ssbhesabfa_api->settingGetSalesmen();

		if (is_object($salesmen) && $salesmen->Success) {
			$available_salesmen = array();
			$available_salesmen[-1] = __('No need to set!', 'ssbhesabfa');
			foreach ($salesmen->Result as $salesman) {
				if($salesman->Active)
					$available_salesmen[$salesman->Code] = $salesman->Name;
			}
			return $available_salesmen;
		} else {
			update_option('ssbhesabfa_live_mode', 0);
			echo '<div class="error">';
			echo '<p class="hesabfa-p">' . __('Cannot get Salesmen detail.', 'ssbhesabfa') . '</p>';
			echo '</div>';
			HesabfaLogService::log(array("Cannot get salesmen detail. Error Code: $salesmen->ErrorCode. Error Message: $salesmen->ErrorMessage."));
			return array('0' => __('Cannot get salesmen detail.', 'ssbhesabfa'));
		}
	}

	public static function ssbhesabfa_log_setting()
	{
		$cleanLogResult = (isset($_GET['cleanLogResult'])) ? wc_clean($_GET['cleanLogResult']) : null;

		if (!is_null($cleanLogResult) && $cleanLogResult === 'true') {
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . __('The log file was cleared.', 'ssbhesabfa') . '</p>';
			echo '</div>';
		} elseif ($cleanLogResult === 'false') {
			echo '<div class="updated">';
			echo '<p class="hesabfa-p">' . __('Log file not found.', 'ssbhesabfa') . '</p>';
			echo '</div>';
		}

		self::ssbhesabfa_tab_log_html();
	}

	public static function ssbhesabfa_tab_log_html()
	{
		?>
        <div style="padding-left: 20px">
            <h3 class="hesabfa-tab-page-title"><?php echo __('Events and bugs log', 'ssbhesabfa') ?></h3>
            <div class="flex">
                <div style="display: inline-block; ">
                    <form id="ssbhesabfa_clean_log" autocomplete="off"
                          action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=log'); ?>"
                          method="post">
                        <div>
                            <label for="ssbhesabfa-log-clean-submit"></label>
                            <div>
                                <button class="button button-primary hesabfa-f" id="ssbhesabfa-log-clean-submit"
                                        name="ssbhesabfa-log-clean-submit"><?php echo __('Clean log', 'ssbhesabfa'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="display: inline-block; margin-right: 10px;">
                    <label for="ssbhesabfa-log-download-submit"></label>
                    <div>
                        <a class="button button-secondary hesabfa-f" target="_blank"
                           href="<?php echo WP_CONTENT_URL ?>/ssbhesabfa.log">
							<?php echo __('Download log file', 'ssbhesabfa'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <br>
			<?php
			if (file_exists(WP_CONTENT_DIR . '/ssbhesabfa.log') &&
				(filesize(WP_CONTENT_DIR . '/ssbhesabfa.log') / 1000) > 1000) {

				$fileSizeInMb = ((filesize(WP_CONTENT_DIR . '/ssbhesabfa.log') / 1000) / 1000);
				$fileSizeInMb = round($fileSizeInMb, 2);

				$str = __('The log file size is large, clean log file.', 'ssbhesabfa');

				echo '<div class="notice notice-warning">' .
					'<p class="hesabfa-p">' . $str . ' (' . $fileSizeInMb . 'MB)' . '</p>'
					. '</div>';

			} else if (file_exists(WP_CONTENT_DIR . '/ssbhesabfa.log')) {

				$logFileContent = HesabfaLogService::readLog();
				echo '<textarea rows="35"  style="width: 100%; box-sizing: border-box; direction: ltr; margin-left: 10px; background-color: whitesmoke">' . $logFileContent . '</textarea>';

			}
			?>
        </div>
		<?php
	}

    public static function ssbhesabfa_get_warehouses()
    {
        $ssbhesabfa_api = new Ssbhesabfa_Api();
        $warehouses = $ssbhesabfa_api->settingGetWarehouses();

        if(is_object($warehouses) && $warehouses->ErrorCode == 199)
        {
            $available_warehouses = array();
            $available_warehouses[-1] = __('Accounting quantity (Total inventory)', 'ssbhesabfa');
            return $available_warehouses;
        }

        if (is_object($warehouses) && $warehouses->Success) {
            $available_warehouses = array();
            $available_warehouses[-1] = __('Accounting quantity (Total inventory)', 'ssbhesabfa');
            foreach ($warehouses->Result as $warehouse) {
                    $available_warehouses[$warehouse->Code] = $warehouse->Name;
            }
            return $available_warehouses;
        } else {
            update_option('ssbhesabfa_live_mode', 0);
            echo '<div class="error">';
            echo '<p class="hesabfa-p">' . __('Cannot get warehouses.', 'ssbhesabfa') . '</p>';
            echo '</div>';
            HesabfaLogService::log(array("Cannot get Warehouses. Error Code: $warehouses->ErrorCode. Error Message: $warehouses->ErrorMessage."));
            return array('0' => __('Cannot get warehouses.', 'ssbhesabfa'));
        }
    }

}

Ssbhesabfa_Setting::init();
