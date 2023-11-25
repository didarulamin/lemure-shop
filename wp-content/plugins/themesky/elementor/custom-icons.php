<?php
class TS_Elementor_Custom_Icons{
	function __construct(){
        add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_icomoon_icons' ) );  
    }
	
	function add_icomoon_icons( $icomoon_icons_args = array() ){
	    $icomoon_icons = array(
			'cable-bold'
			,'camcorder-bold'
			,'drone-bold'
			,'drone-control-bold'
			,'memory-bold'
			,'accessories'
			,'creams'
			,'eyes'
			,'lips'
			,'lotions'
			,'mask'
			,'palettes'
			,'sun-protection'
			,'none'
			,'ice-skating'
			,'skateboarding'
			,'volleyball'
			,'tennis'
			,'swimming'
			,'rugby'
			,'hiking'
			,'golf'
			,'football'
			,'fitness'
			,'bike'
			,'basketball'
			,'dot'
			,'ruler'
			,'security'
			,'water-protection'
			,'camcorder'
			,'drone'
			,'drone-control'
			,'leaf-3'
			,'diamond'
			,'multi-star'
			,'email-2'
			,'location'
			,'material'
			,'phone-2'
			,'smartphone'
			,'christmas-star'
			,'tool-equipment'
			,'battery'
			,'connection'
			,'play'
			,'biology'
			,'heart-health'
			,'leaf-2'
			,'leaf'
			,'towel'
			,'snowshoeing'
			,'downhill-skiing'
			,'snowboarding'
			,'truck-2'
			,'percentage-2'
			,'headphones-2'
			,'gift-box-2'
			,'gift-box'
			,'headphones'
			,'truck'
			,'check'
			,'coupon-2'
			,'percentage'
			,'search'
			,'nav'
			,'close2'
			,'plus2'
			,'close'
			,'cart'
			,'minus'
			,'plus'
			,'heart-fill'
			,'arrow-down'
			,'arrow-left'
			,'arrow-right'
			,'arrow-up'
			,'behance'
			,'calendar'
			,'cloud'
			,'compare'
			,'coupon'
			,'dribble'
			,'dropbox'
			,'email'
			,'facebook'
			,'feedly'
			,'filter'
			,'flickr'
			,'grid-1'
			,'grid-2'
			,'grid-3'
			,'grid-4'
			,'grid-5'
			,'heart'
			,'instagram'
			,'linkedin'
			,'network'
			,'paypal'
			,'phone'
			,'pinterest'
			,'reddit'
			,'search'
			,'skype'
			,'spotify'
			,'star'
			,'tik-tok'
			,'tumblr'
			,'twitter'
			,'user'
			,'viber'
			,'vimeo'
			,'yahoo'
			,'youtube'
	    );
	    
	    $icomoon_icons_args['ts-icomoon-icon'] = array(
	        'name'           => 'ts-icomoon-icon'
	        ,'label'         => esc_html__( 'Icomoon Icons', 'themesky' )
	        ,'labelIcon'     => 'icomoon icomoon-grid-4'
	        ,'prefix'        => 'icomoon-'
	        ,'displayPrefix' => 'icomoon'
	        ,'url'           => get_template_directory_uri() . '/css/icomoon-icons.css'
	        ,'icons'         => $icomoon_icons
	        ,'ver'           => THEMESKY_VERSION
	    );

	    return $icomoon_icons_args;
	}
}

new TS_Elementor_Custom_Icons();