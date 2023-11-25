<?php
/*
Plugin Name: noon payments
Plugin URI: https://www.noonpayments.com/
Description: Extends WooCommerce with noon payments.
Version: 1.0.2
Supported WooCommerce Versions: 3.7,3.8,3.9,4.2,4.3
Author: noon payments
Author URI: https://www.noonpayments.com/
Copyright: © 2021 noon payments. All rights reserved.
*/

add_action('plugins_loaded', 'woocommerce_noonpay_init', 0);

function woocommerce_noonpay_init()
{

	if (!class_exists('WC_Payment_Gateway')) return;
	/**
	 * Localisation
	 */
	load_plugin_textdomain('wc-noonpay', false, dirname(plugin_basename(__FILE__)) . '/languages');

	if (isset($_GET['msg']) && $_GET['msg'] != '') {
		add_action('the_content', 'shownoonpayMessage');
	}

	function shownoonpayMessage($content)
	{
		return '<div class="box ' . htmlentities($_GET['type']) . '-box">' . htmlentities(urldecode($_GET['msg'])) . '</div>' . $content;
	}
	/**
	 * Gateway class
	 */
	class WC_Noonpay extends WC_Payment_Gateway
	{
		protected $msg = array();

		protected $logger;

		public function __construct()
		{
			global $wpdb;
			// Go wild in here
			$this->id = 'noonpay';
			$this->method_title = __('noon payments Gateway','noonpay');
			$this->method_description = __('Collect payments via cards, Apple Pay, Google Pay, Samsung Pay, Union Pay, PayPal, Click to pay, etc.','noonpay');
			$this->icon = WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/images/noonpaymentslogo.png';
			$this->has_fields = false;
			$this->init_form_fields();
			$this->init_settings();
			$this->title = $this -> settings['title'];
			$this->description = $this->settings['description'];			
			$this->gateway_module = $this->settings['gateway_module'];
			$this->gateway_redirect = $this->settings['gateway_redirect'];
			$this->redirect_page_id = $this->settings['redirect_page_id'];
			$this->styleprofile = $this->settings['styleprofile'];
			$this->business_identifier = $this->settings['business_identifier'];
			$this->gateway_url = $this->settings['gateway_url'];
			$this->application_identifier = $this->settings['application_identifier'];
			$this->authorization_key = $this->settings['authorization_key'];
			$this->credential_key = $this->business_identifier.".".$this->application_identifier.":".$this->authorization_key;
			$this->category = $this->settings['category'];
			$this->language = $this->settings['language'];
			$this->paymentAction = $this->settings['paymentAction'];


			$this->msg['message'] = "";
			$this->msg['class'] = "";

			add_action('init', array(&$this, 'check_noonpay_response'));

			add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'check_noonpay_response'));

			add_action('valid-noonpay-request', array(&$this, 'SUCCESS'));

			if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
			} else {
				add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
			}

			add_action('woocommerce_receipt_noonpay', array(&$this, 'receipt_page'));
			//add_action('woocommerce_thankyou_noonpay', array(&$this, 'thankyou_page'));
			
			if ($this->settings['enabled'] == 'yes') //Update session cookies
            {
                $this->manage_session();
            }
			
			$this->logger = wc_get_logger();
		}

		function init_form_fields()
		{

			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'noonpay'),
					'type' => 'checkbox',
					'label' => __('Enable noon payments', 'noonpay'),
					'default' => 'no'
				),
				'title' => array(
                    'title'       => 'Title',
                    'type'        => 'text',
                    'description' => 'Title to appear at checkout',
                    'default'     => 'Pay by cards, Apple Pay, Google Pay, Samsung Pay, Union Pay, PayPal, Click to pay, etc.',
                ),
				'description' => array(
					'title' => __('Description:', 'noonpay'),
					'type' => 'textarea',
					'description' => __('This controls the description which the user sees during checkout.', 'noonpay'),
					'default' => __('Pay securely through noon paymemts.', 'noonpay')
				),
				'gateway_module' => array(
					'title' => __('Gateway Mode', 'noonpay'),
					'type' => 'select',
					'options' => array("0" => "Select", "test" => "Test", "live" => "Live"),
					'description' => __('Mode of gateway subscription.', 'noonpay')
				),
				'gateway_url' => array(
					'title' => __('Gateway Url', 'noonpay'),
					'type' => 'text',
					'description' =>  __('Gateway Url to connect to', 'noonpay')
				),
				'gateway_redirect' => array(
					'title' => __('Operating Mode', 'noonpay'),
					'type' => 'select',
					'options' => array("redirect" => "Redirect", "popup" => "Lightbox"),
					'description' => __('Redirect the customer or popup a dialog.', 'noonpay')
				),
				'business_identifier' => array(
					'title' => __('Business Identifier', 'noonpay'),
					'type' => 'text',
					'description' =>  __('Business Identifier (case sensitive)', 'noonpay')
				),
				'application_identifier' => array(
					'title' => __('Application Identifier', 'noonpay'),
					'type' => 'text',
					'description' =>  __('Application Identifier (case sensitive)', 'noonpay')
				),
				'authorization_key' => array(
					'title' => __('Authorization Key', 'noonpay'),
					'type' => 'text',
					'description' =>  __('Key (case sensitive)', 'noonpay')
				),
				'paymentAction' => array(
					'title' => __('Payment Action', 'noonpay'),
					'type' => 'select',
					'options' => array("SALE" => "Sale", "AUTHORIZE" => "Authorize"),
					'description' =>  __('Payment action - request Authorize or Sale ', 'noonpay')
				),
				'category' => array(
					'title' => __('Order route category', 'noonpay'),
					'type' => 'text',
					'description' =>  __('Order route category. E.g. pay', 'noonpay')
				),
				'language' => array(
					'title' => __('Payment language', 'noonpay'),
					'type' => 'select',
					'options' => array("0" => "Select", "en" => "English", "ar" => "Arabic"),
					'description' =>  __("Language to display the checkout page in.", 'noonpay')
				),
				'styleprofile' => array(
					'title' => __('Style Profile', 'noonpay'),
					'type' => 'text',
					'description' =>  __("Style Profile name configured in Merchant Panel (optional)", 'noonpay')
				),
				'redirect_page_id' => array(
					'title' => __('Return Page'),
					'type' => 'select',
					'options' => $this->get_pages('Select Page'),
					'description' => "Page to redirect to after processing payment."
				)
			);
		}

		/**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 **/
		public function admin_options()
		{
			echo '<h3>' . __('noon payments', 'noonpay') . '</h3>';
			echo '<p>' . __('A popular gateways for online shopping.') . '</p>';			
			if (PHP_VERSION_ID < 70300) {
                echo "<h1 style=\"color:red;\">**Notice: noon payments plugin requires PHP v7.3 or higher.<br />
	  		 		Plugin will not work properly below PHP v7.3 due to SameSite cookie restriction.</h1>";
            }
			echo '<table class="form-table">';
			$this->generate_settings_html();
			echo '</table>';
		}

		/**
		 *  There are no payment fields, but we want to show the description if set.
		 **/
		function payment_fields()
		{			
			if ($this->description) echo wpautop(wptexturize($this->description));			
		}

		/**
		 * Receipt Page
		 **/
		function receipt_page($order)
		{
			echo '<p>' . __('Thank you for your order, please wait as you will be automatically redirected to noon payments.', 'noonpay') . '</p>';
			echo $this->generate_noonpay_form($order);
		}

		/**
		 * Process the payment and return the result
		 **/
		function process_payment($order_id)
		{
			$order = new WC_Order($order_id);

			if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
				return array(
					'result' => 'success',
					'redirect' => add_query_arg(
						'order',
						$order->id,
						add_query_arg('key', $order->get_order_key(), $order->get_checkout_payment_url(true))
					)
				);
			} else {
				return array(
					'result' => 'success',
					'redirect' => add_query_arg(
						'order',
						$order->id,
						add_query_arg('key', $order->get_order_key(), get_permalink(get_option('woocommerce_pay_page_id')))
					)
				);
			}
		}

		/**
		 * Check for valid noon pay server callback
		 **/
		function check_noonpay_response()
		{

			global $woocommerce;
			
			$redirect_url ='';
			
			if (!isset($_GET['wc-api'])) {
				//invalid response	
				$this->msg['class'] = 'error';
				$this->msg['message'] = "Invalid payment gateway response...";

				wc_add_notice($this->msg['message'], $this->msg['class']);

				$redirect_url = add_query_arg(array('msg' => urlencode($this->msg['message']), 'type' => $this->msg['class']), $redirect_url);

				wp_redirect($redirect_url);
				exit;
			}
			
			if ($_GET['wc-api'] == get_class($this)) {
				$responsedata = $_GET;
				//the orderId in the response is the noon payments order reference number
				if (isset($responsedata['orderId']) && !empty($responsedata['orderId']) ) {

					$txnid = WC()->session->get('noopay_order_id');;
					$order_id = explode('_', $txnid);
					$order_id = (int) $order_id[0];    //get rid of time part
					
					$order = new WC_Order($order_id);
					$noonReference = $responsedata['orderId'];

					$action = $this->paymentAction;

					$this->msg['class'] = 'error';
					$this->msg['message'] = "Thank you for shopping with us. However, the transaction has been declined.";
					
					if ($this->verify_payment($order, $noonReference, $txnid)) {
						$this->msg['message'] = "Thank you for shopping with us. Your account has been charged and your transaction is successful for Order Id: $order_id <br/>
						We will be shipping your order to you soon.<br/><br/>";
						if($this->paymentAction == 'AUTHORIZE')
							$this->msg['message'] = "Thank you for shopping with us. Your payment has been authorized for Order Id: $order_id <br/>
						We will be shipping your order to you soon.<br/><br/>";

						$this->msg['class'] = 'success';

						if ($order->status == 'processing' || $order->status == 'completed') {
							//do nothing
						} else {
							//complete the order
							$order->payment_complete();
							$order->add_order_note('noon payments has processed the payment - '. $action . ' Ref Number: ' . $responsedata['orderReference']);
							$order->add_order_note($this->msg['message']);
							$order->add_order_note("Paid using noon payments");
							$woocommerce->cart->empty_cart();
							$redirect_url = $order->get_checkout_order_received_url();
						}
					} else {
						//failed
						$this->msg['class'] = 'error';
						$this->msg['message'] = "Thank you for shopping with us. However, the payment failed<br/><br/>";
						$order->update_status('failed');
						$order->add_order_note('Failed');
						$order->add_order_note($this->msg['message']);
					}						
					
				}
			}

			//manage msessages
			if (function_exists('wc_add_notice')) {
				wc_clear_notices();			
				if($this->msg['class']!='success'){
					wc_add_notice($this->msg['message'], $this->msg['class']);
				}
			} else {
				if ($this->msg['class'] != 'success') {					
					$woocommerce->add_error($this->msg['message']);
				}
				$woocommerce->set_messages();
			}

			if($redirect_url == '')
				$redirect_url = ($this->redirect_page_id == "" || $this->redirect_page_id == 0) ? get_site_url() . "/" : get_permalink($this->redirect_page_id);
			//For wooCoomerce 2.0
			//$redirect_url = add_query_arg( array('msg'=> urlencode($this -> msg['message']), 'type'=>$this -> msg['class']), $redirect_url );
			wp_redirect($redirect_url);
			exit;
		}

		// Verify the payment
		private function verify_payment($order, $noonReference, $txnid)
		{
			global $woocommerce;

			try {
				
				$url = $this->gateway_url.'/'.$noonReference;

				$headerField = 'Key_Live';
				$headerValue = base64_encode($this->credential_key);
	
				if ($this->gateway_module == 'test')
				{
					$headerField = 'Key_Test';				
				}
	
				$header = array();
				$header[] = 'Content-type: application/json';
				$header[] = 'Authorization: '.$headerField.' '.$headerValue;
			
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSLVERSION, 6);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_ENCODING, '');
				curl_setopt($curl, CURLOPT_TIMEOUT, 60);
				
				$response = curl_exec($curl);
				$curlerr = curl_error($curl);		

				if ($curlerr != '')
					return false;
				else {
					$res = json_decode($response);

					if (isset($res->resultCode) && $res->resultCode == 0)
					{
						if (isset($res->result->transactions[0]->status) && $res->result->transactions[0]->status == 'SUCCESS') {
							if (isset($res->result->order->totalCapturedAmount) && isset($res->result->order->totalSalesAmount)
									&& isset($res->result->order->totalRemainingAmount) && isset($res->result->order->reference))
							{
								$capturedAmount = $res->result->order->totalCapturedAmount;
								$saleAmount = $res->result->order->totalSalesAmount;
								$txn_id_ret = $res->result->order->reference;
								$remainingAmount = $res->result->order->totalRemainingAmount;
								$orderAmount = $order->order_total;

								if ($this->paymentAction == "SALE" && $orderAmount == $saleAmount && $capturedAmount >= $orderAmount	&& $txn_id_ret == $txnid) {								
									return true;								
								}
								else if ($this->paymentAction == "AUTHORIZE" && $orderAmount == $remainingAmount	&& $txn_id_ret == $txnid) {								
									return true;								
								} else {
									return false;
								}
							}
						}
					}					
				}
				return false;
			} catch (Exception $e) {
				return false;
			}
		}

		/**
		 * Generate noo payment button link
		 **/
		public function generate_noonpay_form($order_id)
		{

			global $woocommerce;

			$order = new WC_Order($order_id);

			$order_currency = $order->get_currency();

			$redirect_url =  get_site_url() . "/";

			//For wooCoomerce 2.0
			$redirect_url = add_query_arg('wc-api', get_class($this), $redirect_url);
			$order_id = $order_id . '_' . date("ymd") . ':' . rand(1, 100);

			$productInfo = "";	  
	  	    $order_items = $order->get_items();
			foreach($order_items as $item_id => $item_data)
			{
				$product = wc_get_product( $item_data['product_id'] );
				if ($product->get_sku() != "")
					$productInfo .= $product->get_sku()." " ;				
			}
			if ($productInfo != "") {
				$productInfo = trim($productInfo);
				if(strlen($productInfo) > 50)
					$productInfo = substr($productInfo,0,50);
			}
			else 
				$productInfo = "Product Info";			
			
			$postValues =  array();
			$orderValues = array();
			$confiValue = array();

			$postValues['apiOperation'] = 'INITIATE';
			$orderValues['name'] = $productInfo;
			$orderValues['channel'] = 'web';
			$orderValues['reference'] = $order_id;
			$orderValues['amount'] = $order->order_total;
			$orderValues['currency'] = $order->get_currency();
			$orderValues['category'] = $this->category;
			
			$confiValue['locale'] = $this->language;
			$confiValue['paymentAction'] = $this->paymentAction;
			$confiValue['returnUrl'] = $redirect_url;
			if(!Empty($this->styleprofile))
				$confiValue['styleProfile'] = $this->styleprofile;
			
			$postValues['order'] = $orderValues;
			$postValues['configuration'] = $confiValue;
		
			$postJson = json_encode($postValues);
		
			$action = '';
			$jsscript = '';
			$url = $this->gateway_url;
			$headerField = 'Key_Live';
			$headerValue = base64_encode($this->credential_key);

			if ($this->gateway_module == 'test')
			{
				$headerField = 'Key_Test';				
			}

			$header = array();
			$header[] = 'Content-type: application/json';
			$header[] = 'Authorization: '.$headerField.' '.$headerValue;		

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSLVERSION, 6);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_ENCODING, '');		
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson);
			$response = curl_exec($curl);
			$curlerr = curl_error($curl);

			if ($curlerr != '')
			{
				wc_print_notice(  $curlerr,"error");
				return false;
			}
			else {
				$res = json_decode($response);				
				if (isset($res->resultCode) && $res->resultCode == 0 && 
						isset($res->result->checkoutData->postUrl) && isset($res->result->order->id))
				{
					$action = $res->result->checkoutData->postUrl;
					$jsscript = $res->result->checkoutData->jsUrl;
					$orderReference = $res->result->order->id;
					if (empty($action) || empty($jsscript) || empty($orderReference))
					{
						wc_print_notice(  'Payment Action could not be initiated. Verify credentials/checkout info.',"error");
						return false;
					}
					else 
					{
						//add txnid and orderReference to session to validate order
						WC()->session->set( 'noopay_order_id', $order_id );
					}
				}
				else
				{
					wc_print_notice(  'Gateway did not return any response. Contact Administrator.',"error");
					return false;
				}
				
			}			
			
			if ($this->gateway_redirect == 'redirect')
			{
				$html = "<html><body><form action=\"" . $action . "\" method=\"post\" id=\"paynoon_form\" name=\"paynoon_form\">
									<button style='display:none' id='submit_noonpay_payment_form' name='submit_noonpay_payment_form'>Pay Now</button>
						</form>
						<script type=\"text/javascript\">document.getElementById(\"paynoon_form\").submit();</script>
						</body></html>";
			}
			else
			{
				$html = "<html><body><form>	
						<button id='submit_noonpay_payment_form' name='submit_noonpay_payment_form' onClick='javascript:noonDoPayment(); return false;'>Pay Now</button>
						</form>
						<script type='text/javascript' src=" . $jsscript . "></script>
						<script type='text/javascript'>
						function noonResponseCallBack(data) 
						{
							var returnurl= '$redirect_url';
							if (data && data != null)
								window.location.href = returnurl + '&merchantReference=' + data.merchantReference + '&orderId=' + data.orderId + '&paymentType=' + data.paymentType;
						}				

						function noonDoPayment()
						{
								var settings ={	Checkout_Method: 1, SecureVerification_Method: 1,	Call_Back: noonResponseCallBack, Frame_Id: 'noonPaymentFrame'	};
								var tries = 0;
								var noonTimer = setInterval(() => {
									if (typeof ProcessCheckoutPayment == 'function') { 
										clearInterval(noonTimer)
										ProcessCheckoutPayment(settings);
									}
									else
									{						
										if (++tries > 20)
										{
											clearInterval(noonTimer);
											alert('Failed to contact payment gateway. Please try again.');
										}
									}
								}, 500);			
				
							return false;
						}			
						noonDoPayment();				
						</script>
						</body></html>";
			}

			return $html;
		}


		function get_pages($title = false, $indent = true)
		{
			$wp_pages = get_pages('sort_column=menu_order');
			$page_list = array();
			if ($title) $page_list[] = $title;
			foreach ($wp_pages as $page) {
				$prefix = '';
				// show indented child pages?
				if ($indent) {
					$has_parent = $page->post_parent;
					while ($has_parent) {
						$prefix .=  ' - ';
						$next_page = get_page($has_parent);
						$has_parent = $next_page->post_parent;
					}
				}
				// add to page list array array
				$page_list[$page->ID] = $prefix . $page->post_title;
			}
			return $page_list;
		}
		
		/**
		* Session patch CSRF Samesite=None; Secure
		**/
		function manage_session()
        {
            $context = array('source' => $this->id);
            try
            {
                if (PHP_VERSION_ID >= 70300) {
                    $options = session_get_cookie_params();
                    $options['samesite'] = 'None';
                    $options['secure'] = true;
                    unset($options['lifetime']);
                    $cookies = $_COOKIE;
                    foreach ($cookies as $key => $value) {
                        if (!preg_match('/cart/', $key)) {
                            setcookie($key, $value, $options);
                        }
                    }
                } else {
                    $this->logger->error("noon payment plugin does not support this PHP version for cookie management.
												Required PHP v7.3 or higher.", $context);
                }
            } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $context);
            }
        }
	}

		

	/**
	 * Add the Gateway to WooCommerce
	 **/
	function woocommerce_add_noonpay_gateway($methods)
	{
		$methods[] = 'WC_Noonpay';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'woocommerce_add_noonpay_gateway');
}
