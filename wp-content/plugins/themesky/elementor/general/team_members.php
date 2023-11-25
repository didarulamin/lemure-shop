<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Team_Members extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-team-members';
    }
	
	public function get_title(){
        return esc_html__( 'TS Team Members', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-person';
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
            'limit'
            ,array(
                'label'     => esc_html__( 'Number of Members', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 6
				,'min'      => 1
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label'     	=> esc_html__( 'Include these Members', 'themesky' )
                ,'type'      	=> 'ts_autocomplete'
                ,'default'   	=> ''
                ,'options'   	=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'ts_team'
				)
				,'multiple' 	=> true
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label' 		=> esc_html__( 'Columns', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '5'
				,'options'		=> array(
						'1'		=> '1'
						,'2'	=> '2'
						,'3'	=> '3'
						,'4'	=> '4'
						,'5'	=> '5'
						,'6'	=> '6'
				)			
                ,'description' => ''
            )
        );
		
		$this->add_control(
            'target'
            ,array(
                'label' 		=> esc_html__( 'Target', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '_blank'
				,'options'		=> array(
					'_blank'	=> esc_html__( 'New Window Tab', 'themesky' )
					,'_self'	=> esc_html__( 'Self', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_responsive_control(
            'text_align'
			,array(
				'label' 		=> esc_html__( 'Alignment', 'themesky' )
				,'type' 		=> Controls_Manager::CHOOSE
				,'options' 		=> array(
					'left' 		=> array(
						'title' 	=> esc_html__( 'Left', 'themesky' )
						,'icon' 	=> 'eicon-text-align-left'
					)
					,'center'	=> array(
						'title' 	=> esc_html__( 'Center', 'themesky' )
						,'icon' 	=> 'eicon-text-align-center'
					)
					,'right' 	=> array(
						'title' 	=> esc_html__( 'Right', 'themesky' )
						,'icon' 	=> 'eicon-text-align-right'
					)
				)
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-team-members' => 'text-align: {{VALUE}};'
				)
				,'default' 		=> ''
				,'toggle' 		=> true
				,'prefix_class' => 'text%s-'
			)
        );
		
		$this->add_control(
            'img_effect'
            ,array(
                'label' 		=> esc_html__( 'Image Effect', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'default'
				,'options'		=> array(									
					'default' 		=> esc_html__('Default', 'themesky')
					,'' 			=> esc_html__('None', 'themesky')
				)			
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'separator'	=> 'before'
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_slider'
            ,array(
                'label' 	=> esc_html__( 'Slider', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'is_slider'
            ,array(
                'label' 		=> esc_html__( 'Enable Slider', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_product_slider_controls_basic();
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'limit'			=> 6
			,'ids'			=> ''
			,'columns'		=> 5
			,'target'		=> '_blank'
			,'is_slider'	=> 1			
			,'show_nav'		=> 0				
			,'auto_play'	=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		$columns = absint($columns);
		if( !in_array( $columns, array(1, 2, 3, 4, 5, 6) ) ){
			$columns = 4;
		}
		
		global $post, $ts_team_members;
		$thumb_size_name = isset($ts_team_members->thumb_size_name)?$ts_team_members->thumb_size_name:'ts_team_thumb';
		
		$args = array(
					'post_type'				=> 'ts_team'
					,'post_status'			=> 'publish'
					,'img_effect'			=> 'default'
					,'posts_per_page'		=> $limit
				);

		if( $ids ){
			$args['post__in'] = $ids;
			$args['orderby'] = 'post__in';
		}
		
		$team = new WP_Query($args);
		
		if( $team->have_posts() ){
			$classes = array();
			$classes[] = 'ts-team-members ts-shortcode';
			$classes[] = 'columns-'.$columns;
			if( $is_slider ){
				$classes[] = 'ts-slider';
				if( $show_nav ){
					$classes[] = 'show-nav middle-thumbnail';
				}
			}
			if( $img_effect ){
				$classes[] = 'has-effect-img';
			}
			
			$data_attr = array();
			if( $is_slider ){
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-columns="'.$columns.'"';
			}
			$key = -1;
			?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ) ?>" <?php echo implode(' ', $data_attr); ?>>
				<div class="items <?php echo $is_slider?'loading':''; ?>">
				<?php
				while( $team->have_posts() ){
					$team->the_post();
					$profile_link = get_post_meta($post->ID, 'ts_profile_link', true);
					if( $profile_link == '' ){
						$profile_link = '#';
					}
					$name = get_the_title($post->ID);
					$role = get_post_meta($post->ID, 'ts_role', true);
					
					$facebook_link = get_post_meta($post->ID, 'ts_facebook_link', true);
					$twitter_link = get_post_meta($post->ID, 'ts_twitter_link', true);
					$linkedin_link = get_post_meta($post->ID, 'ts_linkedin_link', true);
					$rss_link = get_post_meta($post->ID, 'ts_rss_link', true);
					$dribbble_link = get_post_meta($post->ID, 'ts_dribbble_link', true);
					$pinterest_link = get_post_meta($post->ID, 'ts_pinterest_link', true);
					$instagram_link = get_post_meta($post->ID, 'ts_instagram_link', true);
					$custom_link = get_post_meta($post->ID, 'ts_custom_link', true);
					$custom_link_icon_class = get_post_meta($post->ID, 'ts_custom_link_icon_class', true);
					
					$social_content = '';
					
					if( $facebook_link ){
						$social_content .= '<a class="facebook" href="'.esc_url($facebook_link).'" target="'.$target.'"><i class="icomoon-facebook"></i></a>';
					}
					if( $twitter_link ){
						$social_content .= '<a class="twitter" href="'.esc_url($twitter_link).'" target="'.$target.'"><i class="icomoon-twitter"></i></a>';
					}
					if( $linkedin_link ){
						$social_content .= '<a class="linked" href="'.esc_url($linkedin_link).'" target="'.$target.'"><i class="icomoon-linkedin"></i></a>';
					}
					if( $rss_link ){
						$social_content .= '<a class="rss" href="'.esc_url($rss_link).'" target="'.$target.'"><i class="icomoon-rss"></i></a>';
					}
					if( $dribbble_link ){
						$social_content .= '<a class="dribbble" href="'.esc_url($dribbble_link).'" target="'.$target.'"><i class="icomoon-dribbble"></i></a>';
					}
					if( $pinterest_link ){
						$social_content .= '<a class="pinterest" href="'.esc_url($pinterest_link).'" target="'.$target.'"><i class="icomoon-pinterest"></i></a>';
					}
					if( $instagram_link ){
						$social_content .= '<a class="instagram" href="'.esc_url($instagram_link).'" target="'.$target.'"><i class="icomoon-instagram"></i></a>';
					}
					if( $custom_link ){
						$social_content .= '<a class="custom" href="'.esc_url($custom_link).'" target="'.$target.'"><i class="'.esc_attr($custom_link_icon_class).'"></i></a>';
					}
					
					?>
					<div class="item">
						<div class="team-content">
							<?php if( has_post_thumbnail() ): ?>
							<div class="image-thumbnail">
								<figure>
									<a href="<?php echo esc_url($profile_link); ?>" target="<?php echo esc_attr($target) ?>">
									<?php the_post_thumbnail($thumb_size_name); ?>
									</a>
								</figure>
							</div>
							<?php endif; ?>
							
							<header class="team-info">
								<h6 class="name"><a href="<?php echo esc_url($profile_link); ?>" target="<?php echo esc_attr($target) ?>"><?php echo esc_html($name); ?></a></h6>
								<div class="member-role"><?php echo esc_html($role); ?></div>
								<?php if( $social_content ): ?>
								<div class="member-social"><?php echo $social_content; ?></div>
								<?php endif; ?>
							</header>
						</div>
						
					</div>
					<?php
				}
				?>
				</div>
			</div>
			<?php
		}
		
		wp_reset_postdata();
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Team_Members() );