=== Automated Aramex Express live/manual shipping rates, labels and pickup ===
Contributors: a2zplugins, hitstacks
Tags: Aramex, Aramex Express, automated, shipping rates, shipping label, Aramex Woocommerce
Requires at least: 4.0.1
Tested up to: 6.3
Requires PHP: 5.6
Stable tag: 2.2.1
License: GPLv3 or later License
URI: http://www.gnu.org/licenses/gpl-3.0.html

(Fully automated) Real-time rates, shipping label, pickup, invoice, multi vendor,etc. supports all countries. 

== Description ==

Aramex shipping plugin, integrate seamlessly with Aramex for real-time shipping rates, label printing, shipping rate previews on product pages, and much more.

= What this product does for you = 

> Provides a shipping method suitable to your customers

The most popular Aramex shipping Plugin for WooCommerce that offers label printing (Premium), a custom boxing algorithm (Premium), shipping rate preview (no login needed), and more, you can be sure that your customers always pay just the right amount for delivery and you'll save enough time to focus on what really matters. 

Our highly customizeable and powerful shipping modules provide consistent, easy-to-use and flexible shipping for any shop, including shipping rate previews on product pages and much more.

= Features =

* Display Aramex shipping rates on the product page without requiring the customer to log-in.
 
* Get real time shipping rates directly from the Aramex systems based on your company's Aramex account.
 
* (Premium) Generate & print labels directly from the backoffice order page.
 
* Shipping rates are calculated by weight and dimensions or one of the Aramex boxes:
* Single Box - Assign one box size which will be used for all products.
* (Premium) Multiple Boxes (Fixed Size) - Define how many products can fit in a box (number), or calculate only by weight.
* (Premium) Multiple Boxes (Product Dimensions) - Define all the box sizes you use for shipping, assign dimensions to your products, and the module will automatically calculate (using an algorithm we developed) how many boxes are needed to fit all the products (always trying to use the smallest / lowest number of packages).
 
* Option to set free shipping by Product, Category, Manufacturer or Supplier.
 
* All Aramex Services and package types are supported, and you can select which shipping options should be available per Zone. 
 
* Each shipping method can have its own Free Shipping Limit, Additional Fee, and Insurance.
 
* Smart caching system is used for maximum speed.

* Enable/disable testing mode in module configuration.


Plugin Tags: <blockquote>Aramex, Aramex Shipping, Aramex Shipping Method, Aramex WooCommerce, Aramex Priority Document Express, Aramex Priority Parcel Express, Domestic Aramex, Aramex for woocommerce, Aramex for worldwide shiping, Aramex plugin, create shipment, Aramex shipping, Aramex shipping rates</blockquote>


= About Aramex =

Aramex is a multinational logistics, courier and package delivery company based in Dubai, United Arab Emirates (UAE).It is the first Arab-based company to be listed on the NASDAQ stock exchange. Aramex is listed on the Dubai Financial Market.The company expanded its operations to 120 locations in 33 countries, primarily emerging markets in the Middle East and Southeast Asia by 2001. The company's strategy was to enter high-growth markets characterized by high populations and liberalizing economies.
= About [HITShipo](https://hitstacks.com/hitshipo.php) =

We are Web Development Company in India. We are planning for High Quality WordPress, Woocommerce, Edd Downloads Plugins. We are launched on 4th Nov 2018. 

= What a2Z Plugins Group Tell to Customers? =

> "Make Your Shop With Smile"

Useful filters:

1) To Sort the rates from Lowest to Highest

> add_filter( 'woocommerce_package_rates' , 'hitshipo_sort_shipping_methods', 10, 2 );
> function hitshipo_sort_shipping_methods( $rates, $package ) {
>   if ( empty( $rates ) ) return;
>       if ( ! is_array( $rates ) ) return;
> uasort( $rates, function ( $a, $b ) { 
>   if ( $a == $b ) return 0;
>       return ( $a->cost < $b->cost ) ? -1 : 1; 
>  } );
>       return $rates;
> }

2) Add custom multi-vendor

> = Send vendor id for product =

> add_filter('hits_aramex_custom_account', 'hits_aramex_custom_account_fun', 10, 2);
> function hits_aramex_custom_account_fun($ven_id="", $prod_id=""){
> 	if(!empty($prod_id)){
> 		if (class_exists('WeDevs_Dokan')) {
> 			$ven_id = get_post_field('post_author', $prod_id);
>		}
>	}
> 	return $ven_id;
> }

> = Send account and address info for vendor =

> add_filter('hits_aramex_custom_account_info', 'hits_aramex_custom_account_info_fun', 10, 2);
> function hits_aramex_custom_account_info_fun($ven_data=[], $ven_id=""){
> 	if(!empty($ven_id)){
> 		if (class_exists('WeDevs_Dokan')) {
> 			$vendor = dokan()->vendor->get($ven_id);
> 			$vendor_address = $vendor->get_address();
> 			$ven_data['a2z_aramexexpress_site_id'] = "";
> 			$ven_data['a2z_aramexexpress_site_pwd'] = "";
> 			$ven_data['a2z_aramexexpress_acc_no'] = "";
> 			$ven_data['a2z_aramexexpress_entity'] = "";
> 			$ven_data['a2z_aramexexpress_acc_pin'] = "";
> 			$ven_data['a2z_aramexexpress_shipper_name'] = $vendor->get_name();
> 			$ven_data['a2z_aramexexpress_company'] = $vendor->get_shop_name();
> 			$ven_data['a2z_aramexexpress_mob_num'] = $vendor->get_phone();
> 			$ven_data['a2z_aramexexpress_email'] = $vendor->get_email();
> 			$ven_data['a2z_aramexexpress_address1'] = isset($vendor_address['street_1']) ? $vendor_address['street_1'] : "";
> 			$ven_data['a2z_aramexexpress_address2'] = isset($vendor_address['street_2']) ? $vendor_address['street_2'] : "";
> 			$ven_data['a2z_aramexexpress_city'] = isset($vendor_address['city']) ? $vendor_address['city'] : "";
> 			$ven_data['a2z_aramexexpress_state'] = isset($vendor_address['state']) ? $vendor_address['state'] : "";
> 			$ven_data['a2z_aramexexpress_zip'] = isset($vendor_address['zip']) ? $vendor_address['zip'] : "";
> 			$ven_data['a2z_aramexexpress_country'] = isset($vendor_address['country']) ? $vendor_address['country'] : "";
> 			$ven_data['a2z_aramexexpress_gstin'] = "";
> 			$ven_data['a2z_aramexexpress_con_rate'] = "";
> 			$ven_data['a2z_aramexexpress_v_email'] = $vendor->get_email();
> 			$ven_data['a2z_aramexexpress_def_inter'] = "PPX";
> 			$ven_data['a2z_aramexexpress_def_dom'] = "CDS";
> 		}
> 	}
> 	return $ven_data;
> }

> = Assign vendor email to receive shipment docs on mail =

> add_filter('hits_aramex_custom_account_email', 'hits_aramex_custom_account_email_fun', 10, 2, 3);
> function hits_aramex_custom_account_email_fun($ven_email="", $user_email="", $ven_id=""){
> 	return $user_email;
> }

== Screenshots ==
1. Configuration - Aramex Details.
2. Configuration - Aramex Shipper Address.
3. Configuration - Aramex Packing Method.
4. Configuration - Aramex Available Services.
5. Configuration - Aramex Configure Shipping Label.
6. Configuration - Site Linked.
7. Output - Front office Rate.
8. Output - Back Office Create Shipment.
9. Output - Aramex Label.


== Changelog ==

= 2.2.1 =
*Release Date - 15 September 2023*
	> Minor improvements

= 2.2.0 =
*Release Date - 8 September 2023*
	> Added translation support and additional payment type

= 2.1.0 =
*Release Date - 6 September 2023*
	> Added custom multi-vendor support

= 2.0.1 =
*Release Date - 1 September 2023*
	> Minor improvements

= 2.0.0 =
*Release Date - 1 September 2023*
	> Added HPOS and order page UI on cumstom pages support

= 1.3.0 =
*Release Date - 26 June 2023*
	> Reduced configurations on initial setup

= 1.2.18 =
*Release Date - 19 April 2023*
	> Fix fatal error when passing empty value for weight and dimension conversion on PHP 8

= 1.2.17 =
*Release Date - 27 March 2023*
	>minor bugfix

= 1.2.16 =
*Release Date - 21 March 2023*
	>minor bugfix

= 1.2.15 =
*Release Date - 22 February 2023*
	>minor bug fix

= 1.2.14 =
*Release Date - 20 February 2023*
	>updated meeting link

= 1.2.13 =
*Release Date - 07 February 2023*
	>minor improvement

= 1.2.12 =
*Release Date - 25 january 2023*
	>minor improvement

= 1.2.11 =
*Release Date - 28 December 2022*
	>minor improvement

= 1.2.10 =
*Release Date - 17 November 2022*
	>update tested version

= 1.2.9 =
*Release Date - 31 October 2022*
	>minor bug fix

= 1.2.8 =
*Release Date - 30 september 2022*
	>minor bug fix

= 1.2.7 =
*Release Date - 30 september 2022*
	>minor improvement

= 1.2.6 =
*Release Date - 16 september 2022*
	>minor improvement

= 1.2.5 =
*Release Date - 12 August 2022*
	>Add new button,plugin name change

= 1.2.4 =
*Release Date - 10 August 2022*
	> minor bug fix 

= 1.2.3 =
*Release Date - 10 August 2022*
	> minor bug fix 

= 1.2.2 =
*Release Date - 01 August 2022*
	> minor bug fix and integration field

= 1.2.1 =
*Release Date - 21 July 2022*
	> add new button

= 1.2.0 =
*Release Date - 20 July 2022*
	> SHIPPING LABEL AUTOMATION

= 1.1.1 =
*Release Date - 10 June 2022*
	> minor bugfixing

= 1.1.0 =
*Release Date - 06 June 2022*
	> added new password field
	
= 1.0.2 =
*Release Date - 15 July 2021*
	> Wordpress version updated

= 1.0.1 =
*Release Date - 25 May 2021*
	> Added Icon images

= 1.0.0 =
*Release Date - 06 May 2021*
	> Initial Version
