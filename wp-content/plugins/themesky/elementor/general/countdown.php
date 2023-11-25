<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Countdown extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-countdown';
    }
	
	public function get_title(){
        return esc_html__( 'TS Countdown', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-countdown';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'date'
            ,array(
                'label' 	=> esc_html__( 'Date', 'themesky' )
                ,'type' 	=> Controls_Manager::DATE_TIME
                ,'default' 	=> date( 'Y-m-d', strtotime('+1 day') )
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'text_typography'
				,'selector'			=> '{{WRAPPER}} .ts-countdown'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '30'
							,'unit' 	=> 'px'
						)
						,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
					)
					,'line_height'		=> array(
						'default' 		=> array(
							'size' 		=> '42'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'	=> array('text_decoration', 'text_transform', 'font_style', 'word_spacing')
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		if( empty($settings['date']) ){
			return;
		}
		
		$time = strtotime($settings['date']);
		
		if( $time === false ){
			return;
		}
		
		$current_time = current_time('timestamp');
		
		if( $time < $current_time ){
			return;
		}
		
		$settings['seconds'] = $time - $current_time;
		
		ts_countdown( $settings );
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Countdown() );