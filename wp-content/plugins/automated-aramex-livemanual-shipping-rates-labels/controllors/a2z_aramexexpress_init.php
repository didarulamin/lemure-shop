<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

use Google\Cloud\Translate\TranslateClient;

if (!class_exists('A2Z_Aramexexpress')) {
	class A2Z_Aramexexpress extends WC_Shipping_Method
	{
		/**
		 * Constructor for your shipping class
		 *
		 * @access public
		 * @return void
		 */
		public $hpos_enabled = false;
		public function __construct()
		{
			$this->id                 = 'az_aramexexpress';
			$this->method_title       = __('ARAMEX Express');  // Title shown in admin
			$this->title       = __('ARAMEX Express Shipping');
			// $this->method_description = __(''); // 
			$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
			$this->init();
			if (get_option("woocommerce_custom_orders_table_enabled") === "yes") {
 		        $this->hpos_enabled = true;
 		    }
		}

		/**
		 * Init your settings
		 *
		 * @access public
		 * @return void
		 */
		function init()
		{
			// Load the settings API
			$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
			$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

			// Save settings in admin if you have any defined
			add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
		}

		/**
		 * calculate_shipping function.
		 *
		 * @access public
		 * @param mixed $package
		 * @return void
		 */
		public function calculate_shipping($package = array())
		{
			// $Curr = get_option('woocommerce_currency');
			//      	global $WOOCS;
			//      	if ($WOOCS->default_currency) {
			// $Curr = $WOOCS->default_currency;
			//      	print_r($Curr);
			//      	}else{
			//      		print_r("No");
			//      	}
			//      	die();


			$execution_status = get_option('a2z_aramex_express_working_status');
			if (!empty($execution_status)) {
				if ($execution_status == 'stop_working') {
					return;
				}
			}

			$pack_aft_hook = apply_filters('a2z_aramexexpress_rate_packages', $package);

			if (empty($pack_aft_hook)) {
				return;
			}

			$general_settings = get_option('a2z_aramex_main_settings');
			$general_settings = empty($general_settings) ? array() : $general_settings;

			if (!is_array($general_settings)) {
				return;
			}

			//excluded Countries
			if (isset($general_settings['a2z_aramexexpress_exclude_countries'])) {

				if (in_array($pack_aft_hook['destination']['country'], $general_settings['a2z_aramexexpress_exclude_countries'])) {
					return;
				}
			}

			$aramex_core = array();
			$aramex_core['AD'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['AE'] = array('region' => 'AP', 'currency' => 'AED', 'weight' => 'KG_CM');
			$aramex_core['AF'] = array('region' => 'AP', 'currency' => 'AFN', 'weight' => 'KG_CM');
			$aramex_core['AG'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['AI'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['AL'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['AM'] = array('region' => 'AP', 'currency' => 'AMD', 'weight' => 'KG_CM');
			$aramex_core['AN'] = array('region' => 'AM', 'currency' => 'ANG', 'weight' => 'KG_CM');
			$aramex_core['AO'] = array('region' => 'AP', 'currency' => 'AOA', 'weight' => 'KG_CM');
			$aramex_core['AR'] = array('region' => 'AM', 'currency' => 'ARS', 'weight' => 'KG_CM');
			$aramex_core['AS'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['AT'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['AU'] = array('region' => 'AP', 'currency' => 'AUD', 'weight' => 'KG_CM');
			$aramex_core['AW'] = array('region' => 'AM', 'currency' => 'AWG', 'weight' => 'LB_IN');
			$aramex_core['AZ'] = array('region' => 'AM', 'currency' => 'AZN', 'weight' => 'KG_CM');
			$aramex_core['AZ'] = array('region' => 'AM', 'currency' => 'AZN', 'weight' => 'KG_CM');
			$aramex_core['GB'] = array('region' => 'EU', 'currency' => 'GBP', 'weight' => 'KG_CM');
			$aramex_core['BA'] = array('region' => 'AP', 'currency' => 'BAM', 'weight' => 'KG_CM');
			$aramex_core['BB'] = array('region' => 'AM', 'currency' => 'BBD', 'weight' => 'LB_IN');
			$aramex_core['BD'] = array('region' => 'AP', 'currency' => 'BDT', 'weight' => 'KG_CM');
			$aramex_core['BE'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['BF'] = array('region' => 'AP', 'currency' => 'XOF', 'weight' => 'KG_CM');
			$aramex_core['BG'] = array('region' => 'EU', 'currency' => 'BGN', 'weight' => 'KG_CM');
			$aramex_core['BH'] = array('region' => 'AP', 'currency' => 'BHD', 'weight' => 'KG_CM');
			$aramex_core['BI'] = array('region' => 'AP', 'currency' => 'BIF', 'weight' => 'KG_CM');
			$aramex_core['BJ'] = array('region' => 'AP', 'currency' => 'XOF', 'weight' => 'KG_CM');
			$aramex_core['BM'] = array('region' => 'AM', 'currency' => 'BMD', 'weight' => 'LB_IN');
			$aramex_core['BN'] = array('region' => 'AP', 'currency' => 'BND', 'weight' => 'KG_CM');
			$aramex_core['BO'] = array('region' => 'AM', 'currency' => 'BOB', 'weight' => 'KG_CM');
			$aramex_core['BR'] = array('region' => 'AM', 'currency' => 'BRL', 'weight' => 'KG_CM');
			$aramex_core['BS'] = array('region' => 'AM', 'currency' => 'BSD', 'weight' => 'LB_IN');
			$aramex_core['BT'] = array('region' => 'AP', 'currency' => 'BTN', 'weight' => 'KG_CM');
			$aramex_core['BW'] = array('region' => 'AP', 'currency' => 'BWP', 'weight' => 'KG_CM');
			$aramex_core['BY'] = array('region' => 'AP', 'currency' => 'BYR', 'weight' => 'KG_CM');
			$aramex_core['BZ'] = array('region' => 'AM', 'currency' => 'BZD', 'weight' => 'KG_CM');
			$aramex_core['CA'] = array('region' => 'AM', 'currency' => 'CAD', 'weight' => 'LB_IN');
			$aramex_core['CF'] = array('region' => 'AP', 'currency' => 'XAF', 'weight' => 'KG_CM');
			$aramex_core['CG'] = array('region' => 'AP', 'currency' => 'XAF', 'weight' => 'KG_CM');
			$aramex_core['CH'] = array('region' => 'EU', 'currency' => 'CHF', 'weight' => 'KG_CM');
			$aramex_core['CI'] = array('region' => 'AP', 'currency' => 'XOF', 'weight' => 'KG_CM');
			$aramex_core['CK'] = array('region' => 'AP', 'currency' => 'NZD', 'weight' => 'KG_CM');
			$aramex_core['CL'] = array('region' => 'AM', 'currency' => 'CLP', 'weight' => 'KG_CM');
			$aramex_core['CM'] = array('region' => 'AP', 'currency' => 'XAF', 'weight' => 'KG_CM');
			$aramex_core['CN'] = array('region' => 'AP', 'currency' => 'CNY', 'weight' => 'KG_CM');
			$aramex_core['CO'] = array('region' => 'AM', 'currency' => 'COP', 'weight' => 'KG_CM');
			$aramex_core['CR'] = array('region' => 'AM', 'currency' => 'CRC', 'weight' => 'KG_CM');
			$aramex_core['CU'] = array('region' => 'AM', 'currency' => 'CUC', 'weight' => 'KG_CM');
			$aramex_core['CV'] = array('region' => 'AP', 'currency' => 'CVE', 'weight' => 'KG_CM');
			$aramex_core['CY'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['CZ'] = array('region' => 'EU', 'currency' => 'CZK', 'weight' => 'KG_CM');
			$aramex_core['DE'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['DJ'] = array('region' => 'EU', 'currency' => 'DJF', 'weight' => 'KG_CM');
			$aramex_core['DK'] = array('region' => 'AM', 'currency' => 'DKK', 'weight' => 'KG_CM');
			$aramex_core['DM'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['DO'] = array('region' => 'AP', 'currency' => 'DOP', 'weight' => 'LB_IN');
			$aramex_core['DZ'] = array('region' => 'AM', 'currency' => 'DZD', 'weight' => 'KG_CM');
			$aramex_core['EC'] = array('region' => 'EU', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['EE'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['EG'] = array('region' => 'AP', 'currency' => 'EGP', 'weight' => 'KG_CM');
			$aramex_core['ER'] = array('region' => 'EU', 'currency' => 'ERN', 'weight' => 'KG_CM');
			$aramex_core['ES'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['ET'] = array('region' => 'AU', 'currency' => 'ETB', 'weight' => 'KG_CM');
			$aramex_core['FI'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['FJ'] = array('region' => 'AP', 'currency' => 'FJD', 'weight' => 'KG_CM');
			$aramex_core['FK'] = array('region' => 'AM', 'currency' => 'GBP', 'weight' => 'KG_CM');
			$aramex_core['FM'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['FO'] = array('region' => 'AM', 'currency' => 'DKK', 'weight' => 'KG_CM');
			$aramex_core['FR'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['GA'] = array('region' => 'AP', 'currency' => 'XAF', 'weight' => 'KG_CM');
			$aramex_core['GB'] = array('region' => 'EU', 'currency' => 'GBP', 'weight' => 'KG_CM');
			$aramex_core['GD'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['GE'] = array('region' => 'AM', 'currency' => 'GEL', 'weight' => 'KG_CM');
			$aramex_core['GF'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['GG'] = array('region' => 'AM', 'currency' => 'GBP', 'weight' => 'KG_CM');
			$aramex_core['GH'] = array('region' => 'AP', 'currency' => 'GHS', 'weight' => 'KG_CM');
			$aramex_core['GI'] = array('region' => 'AM', 'currency' => 'GBP', 'weight' => 'KG_CM');
			$aramex_core['GL'] = array('region' => 'AM', 'currency' => 'DKK', 'weight' => 'KG_CM');
			$aramex_core['GM'] = array('region' => 'AP', 'currency' => 'GMD', 'weight' => 'KG_CM');
			$aramex_core['GN'] = array('region' => 'AP', 'currency' => 'GNF', 'weight' => 'KG_CM');
			$aramex_core['GP'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['GQ'] = array('region' => 'AP', 'currency' => 'XAF', 'weight' => 'KG_CM');
			$aramex_core['GR'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['GT'] = array('region' => 'AM', 'currency' => 'GTQ', 'weight' => 'KG_CM');
			$aramex_core['GU'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['GW'] = array('region' => 'AP', 'currency' => 'XOF', 'weight' => 'KG_CM');
			$aramex_core['GY'] = array('region' => 'AP', 'currency' => 'GYD', 'weight' => 'LB_IN');
			$aramex_core['HK'] = array('region' => 'AM', 'currency' => 'HKD', 'weight' => 'KG_CM');
			$aramex_core['HN'] = array('region' => 'AM', 'currency' => 'HNL', 'weight' => 'KG_CM');
			$aramex_core['HR'] = array('region' => 'AP', 'currency' => 'HRK', 'weight' => 'KG_CM');
			$aramex_core['HT'] = array('region' => 'AM', 'currency' => 'HTG', 'weight' => 'LB_IN');
			$aramex_core['HU'] = array('region' => 'EU', 'currency' => 'HUF', 'weight' => 'KG_CM');
			$aramex_core['IC'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['ID'] = array('region' => 'AP', 'currency' => 'IDR', 'weight' => 'KG_CM');
			$aramex_core['IE'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['IL'] = array('region' => 'AP', 'currency' => 'ILS', 'weight' => 'KG_CM');
			$aramex_core['IN'] = array('region' => 'AP', 'currency' => 'INR', 'weight' => 'KG_CM');
			$aramex_core['IQ'] = array('region' => 'AP', 'currency' => 'IQD', 'weight' => 'KG_CM');
			$aramex_core['IR'] = array('region' => 'AP', 'currency' => 'IRR', 'weight' => 'KG_CM');
			$aramex_core['IS'] = array('region' => 'EU', 'currency' => 'ISK', 'weight' => 'KG_CM');
			$aramex_core['IT'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['JE'] = array('region' => 'AM', 'currency' => 'GBP', 'weight' => 'KG_CM');
			$aramex_core['JM'] = array('region' => 'AM', 'currency' => 'JMD', 'weight' => 'KG_CM');
			$aramex_core['JO'] = array('region' => 'AP', 'currency' => 'JOD', 'weight' => 'KG_CM');
			$aramex_core['JP'] = array('region' => 'AP', 'currency' => 'JPY', 'weight' => 'KG_CM');
			$aramex_core['KE'] = array('region' => 'AP', 'currency' => 'KES', 'weight' => 'KG_CM');
			$aramex_core['KG'] = array('region' => 'AP', 'currency' => 'KGS', 'weight' => 'KG_CM');
			$aramex_core['KH'] = array('region' => 'AP', 'currency' => 'KHR', 'weight' => 'KG_CM');
			$aramex_core['KI'] = array('region' => 'AP', 'currency' => 'AUD', 'weight' => 'KG_CM');
			$aramex_core['KM'] = array('region' => 'AP', 'currency' => 'KMF', 'weight' => 'KG_CM');
			$aramex_core['KN'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['KP'] = array('region' => 'AP', 'currency' => 'KPW', 'weight' => 'LB_IN');
			$aramex_core['KR'] = array('region' => 'AP', 'currency' => 'KRW', 'weight' => 'KG_CM');
			$aramex_core['KV'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['KW'] = array('region' => 'AP', 'currency' => 'KWD', 'weight' => 'KG_CM');
			$aramex_core['KY'] = array('region' => 'AM', 'currency' => 'KYD', 'weight' => 'KG_CM');
			$aramex_core['KZ'] = array('region' => 'AP', 'currency' => 'KZF', 'weight' => 'LB_IN');
			$aramex_core['LA'] = array('region' => 'AP', 'currency' => 'LAK', 'weight' => 'KG_CM');
			$aramex_core['LB'] = array('region' => 'AP', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['LC'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'KG_CM');
			$aramex_core['LI'] = array('region' => 'AM', 'currency' => 'CHF', 'weight' => 'LB_IN');
			$aramex_core['LK'] = array('region' => 'AP', 'currency' => 'LKR', 'weight' => 'KG_CM');
			$aramex_core['LR'] = array('region' => 'AP', 'currency' => 'LRD', 'weight' => 'KG_CM');
			$aramex_core['LS'] = array('region' => 'AP', 'currency' => 'LSL', 'weight' => 'KG_CM');
			$aramex_core['LT'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['LU'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['LV'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['LY'] = array('region' => 'AP', 'currency' => 'LYD', 'weight' => 'KG_CM');
			$aramex_core['MA'] = array('region' => 'AP', 'currency' => 'MAD', 'weight' => 'KG_CM');
			$aramex_core['MC'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['MD'] = array('region' => 'AP', 'currency' => 'MDL', 'weight' => 'KG_CM');
			$aramex_core['ME'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['MG'] = array('region' => 'AP', 'currency' => 'MGA', 'weight' => 'KG_CM');
			$aramex_core['MH'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['MK'] = array('region' => 'AP', 'currency' => 'MKD', 'weight' => 'KG_CM');
			$aramex_core['ML'] = array('region' => 'AP', 'currency' => 'COF', 'weight' => 'KG_CM');
			$aramex_core['MM'] = array('region' => 'AP', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['MN'] = array('region' => 'AP', 'currency' => 'MNT', 'weight' => 'KG_CM');
			$aramex_core['MO'] = array('region' => 'AP', 'currency' => 'MOP', 'weight' => 'KG_CM');
			$aramex_core['MP'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['MQ'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['MR'] = array('region' => 'AP', 'currency' => 'MRO', 'weight' => 'KG_CM');
			$aramex_core['MS'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['MT'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['MU'] = array('region' => 'AP', 'currency' => 'MUR', 'weight' => 'KG_CM');
			$aramex_core['MV'] = array('region' => 'AP', 'currency' => 'MVR', 'weight' => 'KG_CM');
			$aramex_core['MW'] = array('region' => 'AP', 'currency' => 'MWK', 'weight' => 'KG_CM');
			$aramex_core['MX'] = array('region' => 'AM', 'currency' => 'MXN', 'weight' => 'KG_CM');
			$aramex_core['MY'] = array('region' => 'AP', 'currency' => 'MYR', 'weight' => 'KG_CM');
			$aramex_core['MZ'] = array('region' => 'AP', 'currency' => 'MZN', 'weight' => 'KG_CM');
			$aramex_core['NA'] = array('region' => 'AP', 'currency' => 'NAD', 'weight' => 'KG_CM');
			$aramex_core['NC'] = array('region' => 'AP', 'currency' => 'XPF', 'weight' => 'KG_CM');
			$aramex_core['NE'] = array('region' => 'AP', 'currency' => 'XOF', 'weight' => 'KG_CM');
			$aramex_core['NG'] = array('region' => 'AP', 'currency' => 'NGN', 'weight' => 'KG_CM');
			$aramex_core['NI'] = array('region' => 'AM', 'currency' => 'NIO', 'weight' => 'KG_CM');
			$aramex_core['NL'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['NO'] = array('region' => 'EU', 'currency' => 'NOK', 'weight' => 'KG_CM');
			$aramex_core['NP'] = array('region' => 'AP', 'currency' => 'NPR', 'weight' => 'KG_CM');
			$aramex_core['NR'] = array('region' => 'AP', 'currency' => 'AUD', 'weight' => 'KG_CM');
			$aramex_core['NU'] = array('region' => 'AP', 'currency' => 'NZD', 'weight' => 'KG_CM');
			$aramex_core['NZ'] = array('region' => 'AP', 'currency' => 'NZD', 'weight' => 'KG_CM');
			$aramex_core['OM'] = array('region' => 'AP', 'currency' => 'OMR', 'weight' => 'KG_CM');
			$aramex_core['PA'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['PE'] = array('region' => 'AM', 'currency' => 'PEN', 'weight' => 'KG_CM');
			$aramex_core['PF'] = array('region' => 'AP', 'currency' => 'XPF', 'weight' => 'KG_CM');
			$aramex_core['PG'] = array('region' => 'AP', 'currency' => 'PGK', 'weight' => 'KG_CM');
			$aramex_core['PH'] = array('region' => 'AP', 'currency' => 'PHP', 'weight' => 'KG_CM');
			$aramex_core['PK'] = array('region' => 'AP', 'currency' => 'PKR', 'weight' => 'KG_CM');
			$aramex_core['PL'] = array('region' => 'EU', 'currency' => 'PLN', 'weight' => 'KG_CM');
			$aramex_core['PR'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['PT'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['PW'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['PY'] = array('region' => 'AM', 'currency' => 'PYG', 'weight' => 'KG_CM');
			$aramex_core['QA'] = array('region' => 'AP', 'currency' => 'QAR', 'weight' => 'KG_CM');
			$aramex_core['RE'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['RO'] = array('region' => 'EU', 'currency' => 'RON', 'weight' => 'KG_CM');
			$aramex_core['RS'] = array('region' => 'AP', 'currency' => 'RSD', 'weight' => 'KG_CM');
			$aramex_core['RU'] = array('region' => 'AP', 'currency' => 'RUB', 'weight' => 'KG_CM');
			$aramex_core['RW'] = array('region' => 'AP', 'currency' => 'RWF', 'weight' => 'KG_CM');
			$aramex_core['SA'] = array('region' => 'AP', 'currency' => 'SAR', 'weight' => 'KG_CM');
			$aramex_core['SB'] = array('region' => 'AP', 'currency' => 'SBD', 'weight' => 'KG_CM');
			$aramex_core['SC'] = array('region' => 'AP', 'currency' => 'SCR', 'weight' => 'KG_CM');
			$aramex_core['SD'] = array('region' => 'AP', 'currency' => 'SDG', 'weight' => 'KG_CM');
			$aramex_core['SE'] = array('region' => 'EU', 'currency' => 'SEK', 'weight' => 'KG_CM');
			$aramex_core['SG'] = array('region' => 'AP', 'currency' => 'SGD', 'weight' => 'KG_CM');
			$aramex_core['SH'] = array('region' => 'AP', 'currency' => 'SHP', 'weight' => 'KG_CM');
			$aramex_core['SI'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['SK'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['SL'] = array('region' => 'AP', 'currency' => 'SLL', 'weight' => 'KG_CM');
			$aramex_core['SM'] = array('region' => 'EU', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['SN'] = array('region' => 'AP', 'currency' => 'XOF', 'weight' => 'KG_CM');
			$aramex_core['SO'] = array('region' => 'AM', 'currency' => 'SOS', 'weight' => 'KG_CM');
			$aramex_core['SR'] = array('region' => 'AM', 'currency' => 'SRD', 'weight' => 'KG_CM');
			$aramex_core['SS'] = array('region' => 'AP', 'currency' => 'SSP', 'weight' => 'KG_CM');
			$aramex_core['ST'] = array('region' => 'AP', 'currency' => 'STD', 'weight' => 'KG_CM');
			$aramex_core['SV'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['SY'] = array('region' => 'AP', 'currency' => 'SYP', 'weight' => 'KG_CM');
			$aramex_core['SZ'] = array('region' => 'AP', 'currency' => 'SZL', 'weight' => 'KG_CM');
			$aramex_core['TC'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['TD'] = array('region' => 'AP', 'currency' => 'XAF', 'weight' => 'KG_CM');
			$aramex_core['TG'] = array('region' => 'AP', 'currency' => 'XOF', 'weight' => 'KG_CM');
			$aramex_core['TH'] = array('region' => 'AP', 'currency' => 'THB', 'weight' => 'KG_CM');
			$aramex_core['TJ'] = array('region' => 'AP', 'currency' => 'TJS', 'weight' => 'KG_CM');
			$aramex_core['TL'] = array('region' => 'AP', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['TN'] = array('region' => 'AP', 'currency' => 'TND', 'weight' => 'KG_CM');
			$aramex_core['TO'] = array('region' => 'AP', 'currency' => 'TOP', 'weight' => 'KG_CM');
			$aramex_core['TR'] = array('region' => 'AP', 'currency' => 'TRY', 'weight' => 'KG_CM');
			$aramex_core['TT'] = array('region' => 'AM', 'currency' => 'TTD', 'weight' => 'LB_IN');
			$aramex_core['TV'] = array('region' => 'AP', 'currency' => 'AUD', 'weight' => 'KG_CM');
			$aramex_core['TW'] = array('region' => 'AP', 'currency' => 'TWD', 'weight' => 'KG_CM');
			$aramex_core['TZ'] = array('region' => 'AP', 'currency' => 'TZS', 'weight' => 'KG_CM');
			$aramex_core['UA'] = array('region' => 'AP', 'currency' => 'UAH', 'weight' => 'KG_CM');
			$aramex_core['UG'] = array('region' => 'AP', 'currency' => 'USD', 'weight' => 'KG_CM');
			$aramex_core['US'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['UY'] = array('region' => 'AM', 'currency' => 'UYU', 'weight' => 'KG_CM');
			$aramex_core['UZ'] = array('region' => 'AP', 'currency' => 'UZS', 'weight' => 'KG_CM');
			$aramex_core['VC'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['VE'] = array('region' => 'AM', 'currency' => 'VEF', 'weight' => 'KG_CM');
			$aramex_core['VG'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['VI'] = array('region' => 'AM', 'currency' => 'USD', 'weight' => 'LB_IN');
			$aramex_core['VN'] = array('region' => 'AP', 'currency' => 'VND', 'weight' => 'KG_CM');
			$aramex_core['VU'] = array('region' => 'AP', 'currency' => 'VUV', 'weight' => 'KG_CM');
			$aramex_core['WS'] = array('region' => 'AP', 'currency' => 'WST', 'weight' => 'KG_CM');
			$aramex_core['XB'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'LB_IN');
			$aramex_core['XC'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'LB_IN');
			$aramex_core['XE'] = array('region' => 'AM', 'currency' => 'ANG', 'weight' => 'LB_IN');
			$aramex_core['XM'] = array('region' => 'AM', 'currency' => 'EUR', 'weight' => 'LB_IN');
			$aramex_core['XN'] = array('region' => 'AM', 'currency' => 'XCD', 'weight' => 'LB_IN');
			$aramex_core['XS'] = array('region' => 'AP', 'currency' => 'SIS', 'weight' => 'KG_CM');
			$aramex_core['XY'] = array('region' => 'AM', 'currency' => 'ANG', 'weight' => 'LB_IN');
			$aramex_core['YE'] = array('region' => 'AP', 'currency' => 'YER', 'weight' => 'KG_CM');
			$aramex_core['YT'] = array('region' => 'AP', 'currency' => 'EUR', 'weight' => 'KG_CM');
			$aramex_core['ZA'] = array('region' => 'AP', 'currency' => 'ZAR', 'weight' => 'KG_CM');
			$aramex_core['ZM'] = array('region' => 'AP', 'currency' => 'ZMW', 'weight' => 'KG_CM');
			$aramex_core['ZW'] = array('region' => 'AP', 'currency' => 'USD', 'weight' => 'KG_CM');
			$_aramex_carriers = array(
				//"Public carrier name" => "technical name",
				'CDS'                    => 'Domestic service Outbound',
				'RTC'                    => 'Domestic service Inbound',
				'EPX'                    => 'International service Outbound',
				'PDX'                    => 'Priority Document Express',
				'PPX'                    => 'Priority Parcel Express',
				'PLX'                    => 'Priority Letter Express',
				'DDX'                    => 'Deferred Document Express',
				'DPX'                    => 'Deferred Parcel Express ',
				'GDX'                    => 'Ground Document Express',
				'GPX'                    => 'Ground Parcel Express',
			);
			$custom_settings = array();
			$custom_settings['default'] = array(
				'a2z_aramexexpress_site_id' => $general_settings['a2z_aramexexpress_site_id'],
				'a2z_aramexexpress_site_pwd' => $general_settings['a2z_aramexexpress_site_pwd'],
				'a2z_aramexexpress_acc_no' => $general_settings['a2z_aramexexpress_acc_no'],
				'a2z_aramexexpress_acc_pin' => $general_settings['a2z_aramexexpress_acc_pin'],
				'a2z_aramexexpress_entity' => $general_settings['a2z_aramexexpress_entity'],
				'a2z_aramexexpress_shipper_name' => $general_settings['a2z_aramexexpress_shipper_name'],
				'a2z_aramexexpress_company' => $general_settings['a2z_aramexexpress_company'],
				'a2z_aramexexpress_mob_num' => $general_settings['a2z_aramexexpress_mob_num'],
				'a2z_aramexexpress_email' => $general_settings['a2z_aramexexpress_email'],
				'a2z_aramexexpress_address1' => $general_settings['a2z_aramexexpress_address1'],
				'a2z_aramexexpress_address2' => $general_settings['a2z_aramexexpress_address2'],
				'a2z_aramexexpress_city' => $general_settings['a2z_aramexexpress_city'],
				'a2z_aramexexpress_state' => $general_settings['a2z_aramexexpress_state'],
				'a2z_aramexexpress_zip' => $general_settings['a2z_aramexexpress_zip'],
				'a2z_aramexexpress_country' => $general_settings['a2z_aramexexpress_country'],
				'a2z_aramexexpress_gstin' => $general_settings['a2z_aramexexpress_gstin'],
				'a2z_aramexexpress_con_rate' => $general_settings['a2z_aramexexpress_con_rate'],
			);
			$vendor_settings = array();

			if (isset($general_settings['a2z_aramexexpress_v_enable']) && $general_settings['a2z_aramexexpress_v_enable'] == 'yes' && isset($general_settings['a2z_aramexexpress_v_rates']) && $general_settings['a2z_aramexexpress_v_rates'] == 'yes') {
				// Multi Vendor Enabled
				foreach ($pack_aft_hook['contents'] as $key => $value) {
					$product_id = $value['product_id'];
					$cus_aramex_account = apply_filters('hits_aramex_custom_account', "", $product_id);
					if ($this->hpos_enabled) {
					    $hpos_prod_data = wc_get_product($product_id);
					    $aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : $hpos_prod_data->get_meta("aramex_express_address");
					} else {
						$aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : get_post_meta($product_id, 'aramex_express_address', true);
					}
					if (empty($aramex_account) || $aramex_account == 'default') {
						$aramex_account = 'default';
						if (!isset($vendor_settings[$aramex_account])) {
							$vendor_settings[$aramex_account] = $custom_settings['default'];
						}

						$vendor_settings[$aramex_account]['products'][] = $value;
					}

					if ($aramex_account != 'default') {
						$cus_user_account_data = apply_filters('hits_aramex_custom_account_info', [], $aramex_account);
						$user_account = !empty($cus_user_account_data) ? $cus_user_account_data : get_post_meta($aramex_account, 'a2z_aramex_vendor_settings', true);
						$user_account = empty($user_account) ? array() : $user_account;
						if (!empty($user_account)) {
							if (!isset($vendor_settings[$aramex_account])) {

								$vendor_settings[$aramex_account] = $custom_settings['default'];

								if ($user_account['a2z_aramexexpress_site_id'] != '' && $user_account['a2z_aramexexpress_site_pwd'] != '' && $user_account['a2z_aramexexpress_acc_no'] != '') {

									$vendor_settings[$aramex_account]['a2z_aramexexpress_site_id'] = $user_account['a2z_aramexexpress_site_id'];

									if ($user_account['a2z_aramexexpress_site_pwd'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_site_pwd'] = $user_account['a2z_aramexexpress_site_pwd'];
									}

									if ($user_account['a2z_aramexexpress_acc_no'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_acc_no'] = $user_account['a2z_aramexexpress_acc_no'];
									}

									if ($user_account['a2z_aramexexpress_entity'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_entity'] = $user_account['a2z_aramexexpress_entity'];
									}

									if ($user_account['a2z_aramexexpress_acc_pin'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_acc_pin'] = $user_account['a2z_aramexexpress_acc_pin'];
									}

								}

								if ($user_account['a2z_aramexexpress_address1'] != '' && $user_account['a2z_aramexexpress_city'] != '' && $user_account['a2z_aramexexpress_state'] != '' && $user_account['a2z_aramexexpress_zip'] != '' && $user_account['a2z_aramexexpress_country'] != '' && $user_account['a2z_aramexexpress_shipper_name'] != '') {

									if ($user_account['a2z_aramexexpress_shipper_name'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_shipper_name'] = $user_account['a2z_aramexexpress_shipper_name'];
									}

									if ($user_account['a2z_aramexexpress_company'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_company'] = $user_account['a2z_aramexexpress_company'];
									}

									if ($user_account['a2z_aramexexpress_mob_num'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_mob_num'] = $user_account['a2z_aramexexpress_mob_num'];
									}

									if ($user_account['a2z_aramexexpress_email'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_email'] = $user_account['a2z_aramexexpress_email'];
									}

									if ($user_account['a2z_aramexexpress_address1'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_address1'] = $user_account['a2z_aramexexpress_address1'];
									}

									$vendor_settings[$aramex_account]['a2z_aramexexpress_address2'] = $user_account['a2z_aramexexpress_address2'];

									if ($user_account['a2z_aramexexpress_city'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_city'] = $user_account['a2z_aramexexpress_city'];
									}

									if ($user_account['a2z_aramexexpress_state'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_state'] = $user_account['a2z_aramexexpress_state'];
									}

									if ($user_account['a2z_aramexexpress_zip'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_zip'] = $user_account['a2z_aramexexpress_zip'];
									}

									if ($user_account['a2z_aramexexpress_country'] != '') {
										$vendor_settings[$aramex_account]['a2z_aramexexpress_country'] = $user_account['a2z_aramexexpress_country'];
									}

									$vendor_settings[$aramex_account]['a2z_aramexexpress_gstin'] = $user_account['a2z_aramexexpress_gstin'];
									$vendor_settings[$aramex_account]['a2z_aramexexpress_con_rate'] = $user_account['a2z_aramexexpress_con_rate'];
								}
							}

							$vendor_settings[$aramex_account]['products'][] = $value;
						}
					}
				}
			}

			if (empty($vendor_settings)) {
				$custom_settings['default']['products'] = $pack_aft_hook['contents'];
			} else {
				$custom_settings = $vendor_settings;
			}

			$mesage_time = date('c');
			$message_date = date('Y-m-d');
			$weight_unit = $dim_unit = '';
			if (!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') {
				$weight_unit = 'KG';
				$dim_unit = 'CM';
			} else {
				$weight_unit = 'LB';
				$dim_unit = 'IN';
			}

			if (!isset($general_settings['a2z_aramexexpress_packing_type'])) {
				return;
			}


			$woo_weight_unit = get_option('woocommerce_weight_unit');
			$woo_dimension_unit = get_option('woocommerce_dimension_unit');

			$aramex_mod_weight_unit = $aramex_mod_dim_unit = '';

			if (!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') {
				$aramex_mod_weight_unit = 'kg';
				$aramex_mod_dim_unit = 'cm';
			} elseif (!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'LB_IN') {
				$aramex_mod_weight_unit = 'lbs';
				$aramex_mod_dim_unit = 'in';
			} else {
				$aramex_mod_weight_unit = 'kg';
				$aramex_mod_dim_unit = 'cm';
			}

			$shipping_rates = array();
			if (isset($general_settings['a2z_aramexexpress_developer_rate']) && $general_settings['a2z_aramexexpress_developer_rate'] == 'yes') {
				echo "<pre>";
			}

			foreach ($custom_settings as $key => $value) {

				if (isset($general_settings['a2z_aramexexpress_auto_con_rate']) && $general_settings['a2z_aramexexpress_auto_con_rate'] == "yes") {
					$current_date = date('m-d-Y', time());
					$ex_rate_data = get_option('a2z_aramex_ex_rate' . $key);
					$ex_rate_data = !empty($ex_rate_data) ? $ex_rate_data : array();
					if (empty($ex_rate_data) || (isset($ex_rate_data['date']) && $ex_rate_data['date'] != $current_date)) {
						if (isset($general_settings['a2z_aramexexpress_country']) && !empty($general_settings['a2z_aramexexpress_country']) && isset($general_settings['a2z_aramexexpress_integration_key']) && !empty($general_settings['a2z_aramexexpress_integration_key'])) {
							$frm_curr = get_option('woocommerce_currency');
							$to_curr = isset($aramex_core[$general_settings['a2z_aramexexpress_country']]) ? $aramex_core[$general_settings['a2z_aramexexpress_country']]['currency'] : '';
							$ex_rate_Request = json_encode(array(
								'integrated_key' => $general_settings['a2z_aramexexpress_integration_key'],
								'from_curr' => $frm_curr,
								'to_curr' => $to_curr
							));

							$ex_rate_url = "https://app.hitshipo.com/get_exchange_rate.php";
							// $ex_rate_url = "http://localhost/hitshipo/get_exchange_rate.php";
							$ex_rate_response = wp_remote_post($ex_rate_url, array(
								'method'      => 'POST',
								'timeout'     => 45,
								'redirection' => 5,
								'httpversion' => '1.0',
								'blocking'    => true,
								'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
								'body'        => $ex_rate_Request,
								'sslverify'   => FALSE
							));
							$ex_rate_result = (is_array($ex_rate_response) && isset($ex_rate_response['body'])) ? json_decode($ex_rate_response['body'], true) : array();

							if (!empty($ex_rate_result) && isset($ex_rate_result['ex_rate']) && $ex_rate_result['ex_rate'] != "Not Found") {
								$ex_rate_result['date'] = $current_date;
								update_option('a2z_aramex_ex_rate' . $key, $ex_rate_result);
							} else {
								if (!empty($ex_rate_data)) {
									$ex_rate_data['date'] = $current_date;
									update_option('a2z_aramex_ex_rate' . $key, $ex_rate_data);
								}
							}
						}
					}
				}
				$to_city = $pack_aft_hook['destination']['city'];
				if (isset($general_settings['a2z_aramexexpress_translation']) && $general_settings['a2z_aramexexpress_translation'] == "yes") {
					if (isset($general_settings['a2z_aramexexpress_translation_key']) && !empty($general_settings['a2z_aramexexpress_translation_key'])) {
						include_once('classes/gtrans/vendor/autoload.php');
						if (!empty($to_city)) {
							if (!preg_match('%^[ -~]+$%', $to_city))      //Cheks english or not  /[^A-Za-z0-9]+/ 
							{
								$response = array();
								try {
									$translate = new TranslateClient(['key' => $general_settings['a2z_aramexexpress_translation_key']]);
									// Tranlate text
									$response = $translate->translate($to_city, [
										'target' => 'en',
									]);
								} catch (exception $e) {
									// echo "\n Exception Caught" . $e->getMessage(); //Error handling
								}
								if (!empty($response) && isset($response['text']) && !empty($response['text'])) {
									$to_city = $response['text'];
								}
							}
						}
					}
				}

				$shipping_rates[$key] = array();

				$orgin_postalcode_or_city = $this->a2z_get_zipcode_or_city($value['a2z_aramexexpress_country'], $value['a2z_aramexexpress_city'], $value['a2z_aramexexpress_zip']);

				$destination_postcode_city = $this->a2z_get_zipcode_or_city($pack_aft_hook['destination']['country'], $to_city, $pack_aft_hook['destination']['postcode']);

				$general_settings['a2z_aramexexpress_currency'] = isset($aramex_core[(isset($value['a2z_aramexexpress_country']) ? $value['a2z_aramexexpress_country'] : 'A2Z')]) ? $aramex_core[$value['a2z_aramexexpress_country']]['currency'] : '';

				$aramex_packs = $this->hit_get_aramex_packages($value['products'], $general_settings, $general_settings['a2z_aramexexpress_currency']);
				$dutiable = (($pack_aft_hook['destination']['country'] == $value['a2z_aramexexpress_country'])) ? "DOM" : "EXP";
				// echo "<pre>";
				// print_r($pack_aft_hook);
				// die();
				$ref = srand(time());
				$total_res = array();
				$order_total = 0;
				foreach ($pack_aft_hook['contents'] as $item_id => $values) {
					$order_total += (float) $values['line_subtotal'];
				}
				if (isset($general_settings['a2z_aramexexpress_carrier']) && !empty($general_settings['a2z_aramexexpress_carrier'])) {
					foreach ($general_settings['a2z_aramexexpress_carrier'] as $carrier => $carriername) {

						foreach ($aramex_packs as $pck) {

							$params = array(
								'ClientInfo'  			=> array(
									'AccountCountryCode'	=> isset($value['a2z_aramexexpress_country']) ? $value['a2z_aramexexpress_country'] : '',
									'AccountEntity'		 	=> isset($value['a2z_aramexexpress_entity']) ? $value['a2z_aramexexpress_entity'] : '',
									'AccountNumber'		 	=> isset($value['a2z_aramexexpress_acc_no']) ? $value['a2z_aramexexpress_acc_no'] : '',
									'AccountPin'		 	=> isset($value['a2z_aramexexpress_acc_pin']) ? $value['a2z_aramexexpress_acc_pin'] : '',
									'UserName'			 	=> isset($value['a2z_aramexexpress_site_id']) ? $value['a2z_aramexexpress_site_id'] : '',
									'Password'			 	=> isset($value['a2z_aramexexpress_site_pwd']) ? $value['a2z_aramexexpress_site_pwd'] : '',
									'Version'			 	=> 'v1.0'
								),

								'Transaction' 			=> array(
									'Reference1'			=> $ref
								),

								'OriginAddress' 	 	=> array(
									'City'					=> $value['a2z_aramexexpress_city'],
									'CountryCode'			=> $value['a2z_aramexexpress_country'],
									'State'					=> $value['a2z_aramexexpress_state'],
									'PostCode'				=> $value['a2z_aramexexpress_zip']
								),

								'DestinationAddress' 	=> array(
									'City'					=> $to_city,
									'CountryCode'			=> isset($pack_aft_hook['destination']['country']) ? $pack_aft_hook['destination']['country'] : '',
									'State'					=> isset($pack_aft_hook['destination']['state']) ? $pack_aft_hook['destination']['state'] : '',
									'PostCode'				=> isset($pack_aft_hook['destination']['postcode']) ? $pack_aft_hook['destination']['postcode'] : ''
								),
								'ShipmentDetails'		=> array(
									'PaymentType'			 => $general_settings['a2z_aramexexpress_pay_type'],
									'ProductGroup'			 => $dutiable,
									'ProductType'			 => $carrier,
									'ActualWeight' 			 => array('Value' => $pck['Weight']['Value'], 'Unit' => $pck['Weight']['Units']),
									'ChargeableWeight' 	     => array('Value' => $pck['Weight']['Value'], 'Unit' => $pck['Weight']['Units']),
									'NumberOfPieces'		 => count($pck['packed_products'])
								)
							);

							// echo "<pre>";
							// print_r($params);
							$soapClient = new SoapClient(dirname(__FILE__) . '/xml/aramex-rates-calculator-wsdl.wsdl', array('trace' => 0));
							$results = $soapClient->CalculateRate($params);
							// 				echo "REQUEST:\n" . htmlentities($soapClient->__getLastRequest()) . "\n";
// 				echo "Response:\n" . $soapClient->__getLastResponse() . "\n";
// 				echo "REQUEST HEADERS:\n" . $soapClient->__getLastRequestHeaders() . "\n";
							// return $results;
							$results->carrier = $carrier;
							$request[] = $params;
							$total_res[] = $results;
						}
					}
				}
				if (isset($total_res) && !empty($total_res)) {
					$filter_arr = array();
					foreach ($general_settings['a2z_aramexexpress_carrier'] as $carrer => $carr_status) {
						foreach ($total_res as $res_key => $single_res) {

							if ($carrer == $single_res->carrier) {
								$filter_arr[$carrer]['currency'] = $single_res->TotalAmount->CurrencyCode;
								if (isset($filter_arr[$carrer]['value']) &&  $filter_arr[$carrer]['value'] > 0) {
									$filter_arr[$carrer]['value'] += $single_res->TotalAmount->Value;
								} else {
									$filter_arr[$carrer]['value'] = $single_res->TotalAmount->Value;
								}
								$filter_arr[$carrer]['carrier'] = $single_res->carrier;
							}
						}
					}
				}
				
				// print_r($filter_arr);

				// die();
			
				if (isset($general_settings['a2z_aramexexpress_developer_rate']) && $general_settings['a2z_aramexexpress_developer_rate'] == 'yes') {
					echo "<pre>";
					echo "<h1> Request </h1><br/>";
					print_r($request);
					echo "<br/><h1> Response </h1><br/>";
					print_r($total_res);
					die();
				}

				if ($filter_arr && !empty($filter_arr)) {
					$rate = array();
					foreach ($filter_arr as $quote) {
						$rate_code = ((string) $quote['carrier']);
						$rate_cost = (float)((string) $quote['value']);

						// if (isset($general_settings['a2z_aramexexpress_excul_tax']) && $general_settings['a2z_aramexexpress_excul_tax'] == "yes") {
						// 	$rate_tax = (float)((string) $quote->TotalTaxAmount);
						// 	if (!empty($rate_tax) && $rate_tax > 0) {
						// 		$rate_cost -= $rate_tax;
						// 	}
						// }

						// if (empty($rate_cost) || $rate_cost == 0) {
						// 	return;
						// }

						if ($general_settings['a2z_aramexexpress_currency'] != get_option('woocommerce_currency')) {
							if (isset($general_settings['a2z_aramexexpress_auto_con_rate']) && $general_settings['a2z_aramexexpress_auto_con_rate'] == "yes") {
								$get_ex_rate = get_option('a2z_aramex_ex_rate' . $key, '');
								$get_ex_rate = !empty($get_ex_rate) ? $get_ex_rate : array();
								$exchange_rate = (!empty($get_ex_rate) && isset($get_ex_rate['ex_rate'])) ? $get_ex_rate['ex_rate'] : 0;
							} else {
								$exchange_rate = $value['a2z_aramexexpress_con_rate'];
							}
							if ($exchange_rate && $exchange_rate > 0) {
								$rate_cost /= $exchange_rate;
							}
						}
						$quote_cur_code = (string)$quote['currency'];

						// if ($general_settings['a2z_aramexexpress_currency'] != $quote_cur_code) {
							
						// 		$con_curr_code = (string)$quote['currency'];//(string)$con->CurrencyCode;
						// 		if (isset($con_curr_code) && $con_curr_code == $general_settings['a2z_aramexexpress_currency']) {
						// 			$rate_cost = (float)(string)$quote['value'];
						// 		}
							
						// }
						$rate[$rate_code] = $rate_cost;
						// $etd_time = '';
						// if (isset($quote->DeliveryDate) && isset($quote->DeliveryTime)) {

						// 	$formated_date = DateTime::createFromFormat('Y-m-d h:i:s', (string)$quote->DeliveryDate->DlvyDateTime);
						// 	$etd_date = $formated_date->format('d/m/Y');
						// 	$etd = apply_filters('hitstacks_aramexexpres_delivery_date', " (Etd.Delivery " . $etd_date . ")", $etd_date, $etd_time);
						// 	// print_r($etd_date);print_r($etd_time);
						// 	// print_r($etd);

						// 	// die();
						// }
					}

					$shipping_rates[$key] = $rate;
				}
			}

			if (isset($general_settings['a2z_aramexexpress_developer_rate']) && $general_settings['a2z_aramexexpress_developer_rate'] == 'yes') {
				die();
			}

			// Rate Processing

			if (!empty($shipping_rates)) {
				$i = 0;
				$final_price = array();
				foreach ($shipping_rates as $mkey => $rate) {
					$cheap_p = 0;
					$cheap_s = '';
					foreach ($rate as $key => $cvalue) {
						if ($i > 0) {

							if (!in_array($key, array('C', 'Q'))) {
								if ($cheap_p == 0 && $cheap_s == '') {
									$cheap_p = $cvalue;
									$cheap_s = $key;
								} else if ($cheap_p > $cvalue) {
									$cheap_p = $cvalue;
									$cheap_s = $key;
								}
							}
						} else {
							$final_price[] = array('price' => $cvalue, 'code' => $key, 'multi_v' => $mkey . '_' . $key);
						}
					}

					if ($cheap_p != 0 && $cheap_s != '') {
						foreach ($final_price as $key => $value) {
							$value['price'] = $value['price'] + $cheap_p;
							$value['multi_v'] = $value['multi_v'] . '|' . $mkey . '_' . $cheap_s;
							$final_price[$key] = $value;
						}
					}

					$i++;
				}



				foreach ($final_price as $key => $value) {

					$rate_cost = $value['price'];
					$rate_code = $value['code'];
					$multi_ven = $value['multi_v'];

					if (!empty($general_settings['a2z_aramexexpress_carrier_adj_percentage'][$rate_code])) {
						$rate_cost += $rate_cost * ($general_settings['a2z_aramexexpress_carrier_adj_percentage'][$rate_code] / 100);
					}
					if (!empty($general_settings['a2z_aramexexpress_carrier_adj'][$rate_code])) {
						$rate_cost += $general_settings['a2z_aramexexpress_carrier_adj'][$rate_code];
					}

					$rate_cost = round($rate_cost, 2);

					$carriers_available = isset($general_settings['a2z_aramexexpress_carrier']) && is_array($general_settings['a2z_aramexexpress_carrier']) ? $general_settings['a2z_aramexexpress_carrier'] : array();

					$carriers_name_available = isset($general_settings['a2z_aramexexpress_carrier_name']) && is_array($general_settings['a2z_aramexexpress_carrier']) ? $general_settings['a2z_aramexexpress_carrier_name'] : array();

					if (array_key_exists($rate_code, $carriers_available)) {
						$name = isset($carriers_name_available[$rate_code]) && !empty($carriers_name_available[$rate_code]) ? $carriers_name_available[$rate_code] : $_aramex_carriers[$rate_code];

						$rate_cost = apply_filters('hitstacks_aramexexpress_rate_cost', $rate_cost, $rate_code, $order_total);
						if ($rate_cost < 1) {
							continue;
						}
						if (isset($general_settings['a2z_aramexexpress_etd_date']) && $general_settings['a2z_aramexexpress_etd_date'] == 'yes') {

							$name .= $etd;
						}

						if (!isset($general_settings['a2z_aramexexpress_v_rates']) || $general_settings['a2z_aramexexpress_v_rates'] != 'yes') {
							$multi_ven = '';
						}




						// This is where you'll add your rates
						$rate = array(
							'id'       => 'a2z' . $rate_code,
							'label'    => $name,
							'cost'     => apply_filters("hitstacks_shipping_cost_conversion", $rate_cost),
							'meta_data' => array('a2z_multi_ven' => $multi_ven, 'a2z_aramex_service' => $rate_code)
						);

						// Register the rate

						$this->add_rate($rate);
					}
				}
			}
		}

		public function hit_get_aramex_packages($package, $general_settings, $orderCurrency, $chk = false)
		{
			switch ($general_settings['a2z_aramexexpress_packing_type']) {
				case 'box':
					return $this->box_shipping($package, $general_settings, $orderCurrency, $chk);
					break;
				case 'weight_based':
					return $this->weight_based_shipping($package, $general_settings, $orderCurrency, $chk);
					break;
				case 'per_item':
				default:
					return $this->per_item_shipping($package, $general_settings, $orderCurrency, $chk);
					break;
			}
		}
		private function weight_based_shipping($package, $general_settings, $orderCurrency, $chk = false)
		{
			// echo '<pre>';
			// print_r($package);
			// die();
			if (!class_exists('WeightPack')) {
				include_once 'classes/weight_pack/class-hit-weight-packing.php';
			}
			$max_weight = isset($general_settings['a2z_aramexexpress_max_weight']) && $general_settings['a2z_aramexexpress_max_weight'] != ''  ? $general_settings['a2z_aramexexpress_max_weight'] : 10;
			$weight_pack = new WeightPack('pack_ascending');
			$weight_pack->set_max_weight($max_weight);

			$package_total_weight = 0;
			$insured_value = 0;

			$ctr = 0;
			foreach ($package as $item_id => $values) {
				$ctr++;
				$product = $values['data'];
				$product_data = $product->get_data();
				$get_prod = wc_get_product($values['product_id']);

				if (!isset($product_data['weight']) || empty($product_data['weight'])) {

					if ($get_prod->is_type('variable')) {
						$parent_prod_data = $product->get_parent_data();

						if (isset($parent_prod_data['weight']) && !empty($parent_prod_data['weight'])) {
							$product_data['weight'] = !empty($parent_prod_data['weight'] ? $parent_prod_data['weight'] : 0.001);
						} else {
							$product_data['weight'] = 0.001;
						}
					} else {
						$product_data['weight'] = 0.001;
					}
				}

				$chk_qty = $chk ? $values['product_quantity'] : $values['quantity'];

				$weight_pack->add_item($product_data['weight'], $values, $chk_qty);
			}

			$pack   =   $weight_pack->pack_items();
			$errors =   $pack->get_errors();
			if (!empty($errors)) {
				//do nothing
				return;
			} else {
				$boxes    =   $pack->get_packed_boxes();
				$unpacked_items =   $pack->get_unpacked_items();

				$insured_value        =   0;

				$packages      =   array_merge($boxes, $unpacked_items); // merge items if unpacked are allowed
				$package_count  =   sizeof($packages);
				// get all items to pass if item info in box is not distinguished
				$packable_items =   $weight_pack->get_packable_items();
				$all_items    =   array();
				if (is_array($packable_items)) {
					foreach ($packable_items as $packable_item) {
						$all_items[]    =   $packable_item['data'];
					}
				}
				//pre($packable_items);
				$order_total = '';

				$to_ship  = array();
				$group_id = 1;
				foreach ($packages as $package) { //pre($package);
					$packed_products = array();
					if (($package_count  ==  1) && isset($order_total)) {
						$insured_value  =  (isset($product_data['product_price']) ? $product_data['product_price'] : $product_data['price']) * (isset($values['product_quantity']) ? $values['product_quantity'] : $values['quantity']);
					} else {
						$insured_value  =   0;
						if (!empty($package['items'])) {
							foreach ($package['items'] as $item) {

								$insured_value        =   $insured_value; //+ $item->price;
							}
						} else {
							if (isset($order_total) && $package_count) {
								$insured_value  =   $order_total / $package_count;
							}
						}
					}
					$packed_products    =   isset($package['items']) ? $package['items'] : $all_items;
					// Creating package request
					$package_total_weight   = $package['weight'];

					$insurance_array = array(
						'Amount' => $insured_value,
						'Currency' => $orderCurrency
					);

					$group = array(
						'GroupNumber' => $group_id,
						'GroupPackageCount' => 1,
						'Weight' => array(
							'Value' => round($package_total_weight, 3),
							'Units' => (isset($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') ? 'KG' : 'LBS'
						),
						'packed_products' => $packed_products,
					);
					$group['InsuredValue'] = $insurance_array;
					$group['packtype'] = 'BOX';

					$to_ship[] = $group;
					$group_id++;
				}
			}
			return $to_ship;
		}
		private function box_shipping($package, $general_settings, $orderCurrency, $chk = false)
		{
			if (!class_exists('HIT_Boxpack')) {
				include_once 'classes/hit-box-packing.php';
			}
			$boxpack = new HIT_Boxpack();
			$boxes = isset($general_settings['a2z_aramexexpress_boxes']) ? $general_settings['a2z_aramexexpress_boxes'] : array();
			if (empty($boxes)) {
				return false;
			}
			// $boxes = unserialize($boxes);
			// Define boxes
			foreach ($boxes as $key => $box) {
				if (!$box['enabled']) {
					continue;
				}
				$box['pack_type'] = !empty($box['pack_type']) ? $box['pack_type'] : 'BOX';

				$newbox = $boxpack->add_box($box['length'], $box['width'], $box['height'], $box['box_weight'], $box['pack_type']);

				if (isset($box['id'])) {
					$newbox->set_id(current(explode(':', $box['id'])));
				}

				if ($box['max_weight']) {
					$newbox->set_max_weight($box['max_weight']);
				}

				if ($box['pack_type']) {
					$newbox->set_packtype($box['pack_type']);
				}
			}

			// Add items
			foreach ($package as $item_id => $values) {

				$product = $values['data'];
				$product_data = $product->get_data();
				$get_prod = wc_get_product($values['product_id']);
				$parent_prod_data = [];

				if ($get_prod->is_type('variable')) {
					$parent_prod_data = $product->get_parent_data();
				}

				if (isset($product_data['weight']) && !empty($product_data['weight'])) {
					$item_weight = round($product_data['weight'] > 0.001 ? $product_data['weight'] : 0.001, 3);
				} else {
					$item_weight = (isset($parent_prod_data['weight']) && !empty($parent_prod_data['weight'])) ? (round($parent_prod_data['weight'] > 0.001 ? $parent_prod_data['weight'] : 0.001, 3)) : 0.001;
				}

				if (isset($product_data['width']) && isset($product_data['height']) && isset($product_data['length']) && !empty($product_data['width']) && !empty($product_data['height']) && !empty($product_data['length'])) {
					$item_dimension = array(
						'Length' => max(1, round($product_data['length'], 3)),
						'Width' => max(1, round($product_data['width'], 3)),
						'Height' => max(1, round($product_data['height'], 3))
					);
				} elseif (isset($parent_prod_data['width']) && isset($parent_prod_data['height']) && isset($parent_prod_data['length']) && !empty($parent_prod_data['width']) && !empty($parent_prod_data['height']) && !empty($parent_prod_data['length'])) {
					$item_dimension = array(
						'Length' => max(1, round($parent_prod_data['length'], 3)),
						'Width' => max(1, round($parent_prod_data['width'], 3)),
						'Height' => max(1, round($parent_prod_data['height'], 3))
					);
				}

				if (isset($item_weight) && isset($item_dimension)) {

					// $dimensions = array($values['depth'], $values['height'], $values['width']);
					$chk_qty = $chk ? $values['product_quantity'] : $values['quantity'];
					for ($i = 0; $i < $chk_qty; $i++) {
						$boxpack->add_item($item_dimension['Width'], $item_dimension['Height'], $item_dimension['Length'], $item_weight, round($product_data['price']), array(
							'data' => $values
						));
					}
				} else {
					//    $this->debug(sprintf(__('Product #%s is missing dimensions. Aborting.', 'wf-shipping-aramex'), $item_id), 'error');
					return;
				}
			}

			// Pack it
			$boxpack->pack();
			$packages = $boxpack->get_packages();
			$to_ship = array();
			$group_id = 1;
			foreach ($packages as $package) {
				if ($package->unpacked === true) {
					//$this->debug('Unpacked Item');
				} else {
					//$this->debug('Packed ' . $package->id);
				}

				$dimensions = array($package->length, $package->width, $package->height);

				sort($dimensions);
				$insurance_array = array(
					'Amount' => round($package->value),
					'Currency' => $orderCurrency
				);


				$group = array(
					'GroupNumber' => $group_id,
					'GroupPackageCount' => 1,
					'Weight' => array(
						'Value' => round($package->weight, 3),
						'Units' => (isset($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') ? 'KG' : 'LBS'//(isset($general_settings['weg_dim']) && $general_settings['weg_dim'] === 'yes') ? 'KG' : 'LBS'
					),
					'Dimensions' => array(
						'Length' => max(1, round($dimensions[2], 3)),
						'Width' => max(1, round($dimensions[1], 3)),
						'Height' => max(1, round($dimensions[0], 3)),
						'Units' => (isset($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') ? 'CM' : 'IN'//(isset($general_settings['weg_dim']) && $general_settings['weg_dim'] === 'yes') ? 'CM' : 'IN'
					),
					'InsuredValue' => $insurance_array,
					'packed_products' => array(),
					'package_id' => $package->id,
					'packtype' => 'BOX'
				);

				if (!empty($package->packed) && is_array($package->packed)) {
					foreach ($package->packed as $packed) {
						$group['packed_products'][] = $packed->get_meta('data');
					}
				}

				if (!$package->packed) {
					foreach ($package->unpacked as $unpacked) {
						$group['packed_products'][] = $unpacked->get_meta('data');
					}
				}

				$to_ship[] = $group;

				$group_id++;
			}

			return $to_ship;
		}
		private function per_item_shipping($package, $general_settings, $orderCurrency, $chk = false)
		{
			$to_ship = array();
			$group_id = 1;

			// Get weight of order
			foreach ($package as $item_id => $values) {
				$product = $values['data'];
				$product_data = $product->get_data();
				$get_prod = wc_get_product($values['product_id']);
				$parent_prod_data = [];

				if ($get_prod->is_type('variable')) {
					$parent_prod_data = $product->get_parent_data();
				}

				$group = array();
				$insurance_array = array(
					'Amount' => round($product_data['price']),
					'Currency' => $orderCurrency
				);

				if (isset($product_data['weight']) && !empty($product_data['weight'])) {
					$aramex_per_item_weight = round($product_data['weight'] > 0.001 ? $product_data['weight'] : 0.001, 3);
				} else {
					$aramex_per_item_weight = (isset($parent_prod_data['weight']) && !empty($parent_prod_data['weight'])) ? (round($parent_prod_data['weight'] > 0.001 ? $parent_prod_data['weight'] : 0.001, 3)) : 0.001;
				}

				$group = array(
					'GroupNumber' => $group_id,
					'GroupPackageCount' => 1,
					'Weight' => array(
						'Value' => $aramex_per_item_weight,
						'Units' => (isset($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') ? 'KG' : 'LBS'
					),
					'packed_products' => array($product)
				);

				if (isset($product_data['width']) && isset($product_data['height']) && isset($product_data['length']) && !empty($product_data['width']) && !empty($product_data['height']) && !empty($product_data['length'])) {

					$group['Dimensions'] = array(
						'Length' => max(1, round($product_data['length'], 3)),
						'Width' => max(1, round($product_data['width'], 3)),
						'Height' => max(1, round($product_data['height'], 3)),
						'Units' => (isset($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') ? 'CM' : 'IN'
					);
				} elseif (isset($parent_prod_data['width']) && isset($parent_prod_data['height']) && isset($parent_prod_data['length']) && !empty($parent_prod_data['width']) && !empty($parent_prod_data['height']) && !empty($parent_prod_data['length'])) {
					$group['Dimensions'] = array(
						'Length' => max(1, round($parent_prod_data['length'], 3)),
						'Width' => max(1, round($parent_prod_data['width'], 3)),
						'Height' => max(1, round($parent_prod_data['height'], 3)),
						'Units' => (isset($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM') ? 'CM' : 'IN'
					);
				}

				$group['packtype'] = 'BOX';

				$group['InsuredValue'] = $insurance_array;

				$chk_qty = $chk ? $values['product_quantity'] : $values['quantity'];

				for ($i = 0; $i < $chk_qty; $i++)
					$to_ship[] = $group;

				$group_id++;
			}

			return $to_ship;
		}
		private function a2z_get_zipcode_or_city($country, $city, $postcode)
		{
			$no_postcode_country = array(
				'AE', 'AF', 'AG', 'AI', 'AL', 'AN', 'AO', 'AW', 'BB', 'BF', 'BH', 'BI', 'BJ', 'BM', 'BO', 'BS', 'BT', 'BW', 'BZ', 'CD', 'CF', 'CG', 'CI', 'CK',
				'CL', 'CM', 'CR', 'CV', 'DJ', 'DM', 'DO', 'EC', 'EG', 'ER', 'ET', 'FJ', 'FK', 'GA', 'GD', 'GH', 'GI', 'GM', 'GN', 'GQ', 'GT', 'GW', 'GY', 'HK', 'HN', 'HT', 'IE', 'IQ', 'IR',
				'JM', 'JO', 'KE', 'KH', 'KI', 'KM', 'KN', 'KP', 'KW', 'KY', 'LA', 'LB', 'LC', 'LK', 'LR', 'LS', 'LY', 'ML', 'MM', 'MO', 'MR', 'MS', 'MT', 'MU', 'MW', 'MZ', 'NA', 'NE', 'NG', 'NI',
				'NP', 'NR', 'NU', 'OM', 'PA', 'PE', 'PF', 'PY', 'QA', 'RW', 'SA', 'SB', 'SC', 'SD', 'SL', 'SN', 'SO', 'SR', 'SS', 'ST', 'SV', 'SY', 'TC', 'TD', 'TG', 'TL', 'TO', 'TT', 'TV', 'TZ',
				'UG', 'UY', 'VC', 'VE', 'VG', 'VN', 'VU', 'WS', 'XA', 'XB', 'XC', 'XE', 'XL', 'XM', 'XN', 'XS', 'YE', 'ZM', 'ZW'
			);

			$postcode_city = !in_array($country, $no_postcode_country) ? $postcode_city = "<Postalcode>{$postcode}</Postalcode>" : '';
			if (!empty($city)) {
				$postcode_city .= "<City>{$city}</City>";
			}
			return $postcode_city;
		}
		public function a2z_aramex_is_eu_country($countrycode, $destinationcode)
		{
			$eu_countrycodes = array(
				'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE',
				'ES', 'FI', 'FR', 'GB', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV',
				'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK',
				'HR', 'GR'

			);
			return (in_array($countrycode, $eu_countrycodes) && in_array($destinationcode, $eu_countrycodes));
		}
		/**
		 * Initialise Gateway Settings Form Fields
		 */
		public function init_form_fields()
		{
			$this->form_fields = array('a2z_aramexexpress' => array('type' => 'a2z_aramexexpress'));
		}
		public function generate_a2z_aramexexpress_html()
		{
			$general_settings = get_option('a2z_aramex_main_settings');
			$general_settings = empty($general_settings) ? array() : $general_settings;
			if (!empty($general_settings)) {
				wp_redirect(admin_url('options-general.php?page=hit-aramex-express-configuration'));
			}

			if (isset($_POST['configure_the_plugin'])) {
				// global $woocommerce;
				// $countries_obj   = new WC_Countries();
				// $countries   = $countries_obj->__get('countries');
				// $default_country = $countries_obj->get_base_country();

				// if (!isset($general_settings['a2z_aramexexpress_country'])) {
				// 	$general_settings['a2z_aramexexpress_country'] = $default_country;
				// 	update_option('a2z_aramex_main_settings', $general_settings);
				// }
				wp_redirect(admin_url('options-general.php?page=hit-aramex-express-configuration'));
			}
?>
			<style>
				.card {
					background-color: #fff;
					border-radius: 5px;
					width: 800px;
					max-width: 800px;
					height: auto;
					text-align: center;
					margin: 10px auto 100px auto;
					box-shadow: 0px 1px 20px 1px hsla(213, 33%, 68%, .6);
				}

				.content {
					padding: 20px 20px;
				}


				h2 {
					text-transform: uppercase;
					color: #000;
					font-weight: bold;
				}


				.boton {
					text-align: center;
				}

				.boton button {
					font-size: 18px;
					border: none;
					outline: none;
					color: #166DB4;
					text-transform: capitalize;
					background-color: #fff;
					cursor: pointer;
					font-weight: bold;
				}

				button:hover {
					text-decoration: underline;
					text-decoration-color: #166DB4;
				}
			</style>
			<!-- Fuente Mulish -->


			<div class="card">
				<div class="content">
					<div class="logo">
						<img src="<?php echo plugin_dir_url(__FILE__); ?>views/haramex.png" style="width:150px;" alt="logo DELL" />
					</div>
					<h2><strong>HITShipo + ARAMEX Express</strong></h2>
					<p style="font-size: 14px;line-height: 27px;">
						<?php _e('Welcome to HITSHIPO! You are at just one-step ahead to configure the ARAMEX Express with HITSHIPO.', 'a2z_aramexexpress') ?><br>
						<?php _e('We have lot of features that will take your e-commerce store to another level.', 'a2z_aramexexpress') ?><br><br>
						<?php _e('HITSHIPO helps you to save time, reduce errors, and worry less when you automate your tedious, manual tasks. HITSHIPO + our plugin can generate shipping labels, Commercial invoice, display real time rates, track orders, audit shipments, and supports both domestic & international ARAMEX services.', 'a2z_aramexexpress') ?><br><br>
						<?php _e('Make your customers happier by reacting faster and handling their service requests in a timely manner, meaning higher store reviews and more revenue.', 'a2z_aramexexpress') ?><br>
					</p>

				</div>
				<div class="boton" style="padding-bottom:10px;">
					<button class="button-primary" name="configure_the_plugin" style="padding:8px;">Configure the plugin</button>
				</div>
			</div>
<?php
			echo '<style>button.button-primary.woocommerce-save-button{display:none;}</style>';
		}
	}
}
