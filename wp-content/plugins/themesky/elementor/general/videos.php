<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Videos extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-videos';
    }
	
	public function get_title(){
        return esc_html__( 'TS Videos', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-video-playlist';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 		=> esc_html__( 'General', 'themesky' )
                ,'tab'   		=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'title'
            ,array(
                'label' 		=> esc_html__( 'Title', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$repeater = new Elementor\Repeater();

		$repeater->add_control(
			'video_type'
			,array(
				'label' 	=> esc_html__( 'Source', 'themesky' )
				,'type' 	=> Controls_Manager::SELECT
				,'default' 	=> 'hosted'
				,'options'	=> array(
					'youtube' 		=> esc_html__( 'YouTube', 'themesky' )
					,'vimeo' 		=> esc_html__( 'Vimeo', 'themesky' )
					,'hosted' 		=> esc_html__( 'Self Hosted', 'themesky' )
				)
			)
		);
		
		$repeater->add_control(
            'youtube_url'
            ,array(
                'label' 		=> esc_html__( 'Link', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
				,'placeholder' 	=> esc_html__( 'Enter your URL (YouTube)', 'themesky' )
                ,'default' 		=> ''		
                ,'description' 	=> ''
                ,'label_block' 	=> true
				,'condition'	=> array( 'video_type' => 'youtube' )
            )
        );
		
		$repeater->add_control(
            'vimeo_url'
            ,array(
                'label' 		=> esc_html__( 'Link', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
				,'placeholder' 	=> esc_html__( 'Enter your URL (Vimeo)', 'themesky' )
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'condition'	=> array( 'video_type' => 'vimeo' )
            )
        );
		
		$repeater->add_control(
            'insert_url'
            ,array(
                'label' 		=> esc_html__( 'External URL', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'	
                ,'description' 	=> ''
				,'condition'	=> array( 'video_type' => 'hosted' )
            )
        );
		
		$repeater->add_control(
            'hosted_url'
            ,array(
                'label' 		=> esc_html__( 'Choose File', 'themesky' )
                ,'type' 		=> Controls_Manager::MEDIA
                ,'default' 		=> array( 'url' => '' )
                ,'description' 	=> ''
                ,'media_type' 	=> 'video'
				,'condition'	=> array( 'video_type' => 'hosted', 'insert_url!' => '1' )
            )
        );
		
		$repeater->add_control(
            'external_url'
            ,array(
                'label' 		=> esc_html__( 'Link', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
				,'placeholder' 	=> esc_html__( 'Enter your URL', 'themesky' )
                ,'default' 		=> ''
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'condition'	=> array( 'video_type' => 'hosted', 'insert_url' => '1' )
            )
        );
		
		$repeater->add_control(
			'show_image_overlay'
			,array(
				'label' 		=> esc_html__( 'Image Overlay', 'themesky' )
				,'type' 		=> Controls_Manager::SWITCHER
				,'default' 		=> '0'
				,'return_value' => '1'
			)
		);
		
		$repeater->add_control(
            'image_overlay'
            ,array(
                'label' 		=> esc_html__( 'Choose Image', 'themesky' )
                ,'type' 		=> Controls_Manager::MEDIA
                ,'default' 		=> array( 'url' => '' )
                ,'description' 	=> ''
				,'condition'	=> array( 'show_image_overlay' => '1' )
            )
        );
		
		$repeater->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type()
			,array(
				'label' 		=> esc_html__( 'Image Size', 'themesky' )
				,'name' 		=> 'image_overlay'
				,'default' 		=> 'full'
				,'condition'	=> array( 'show_image_overlay' => '1' )
			)
		);
		
		$this->add_control(
			'videos'
			,array(
				'label' 		=> esc_html__( 'Videos', 'themesky' )
				,'type' 		=> Controls_Manager::REPEATER
				,'fields' 		=> $repeater->get_controls()
				,'default' 		=> array()
				,'prevent_empty'=> false
			)
		);
		
		$this->add_control(
			'aspect_ratio'
			,array(
				'label' 	=> esc_html__( 'Aspect Ratio', 'themesky' )
				,'type' 	=> Controls_Manager::SELECT
				,'options' 	=> array(
					'169' 	=> '16:9'
					,'219' 	=> '21:9'
					,'43' 	=> '4:3'
					,'32' 	=> '3:2'
					,'11' 	=> '1:1'
					,'916' 	=> '9:16'
				)
				,'selectors_dictionary' => array(
					'169' 	=> '1.77777' // 16 / 9
					,'219' 	=> '2.33333' // 21 / 9
					,'43' 	=> '1.33333' // 4 / 3
					,'32' 	=> '1.5' // 3 / 2
					,'11' 	=> '1' // 1 / 1
					,'916' 	=> '0.5625' // 9 / 16
				)
				,'default' 	=> '169'
				,'selectors' => array(
					'{{WRAPPER}} .elementor-wrapper' => '--video-aspect-ratio: {{VALUE}}'
				)
			)
		);
		
		$this->add_responsive_control(
            'grid_columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'min'      	=> 1
				,'default' 		=> 3
				,'devices' 	=> array( 
					'desktop'
					,'tablet' 
					,'mobile' 
				)
				,'selectors' => array(
					'{{WRAPPER}} .grid-layout .videos' => ' -webkit-column-count: {{VALUE}}; -moz-column-count: {{VALUE}}; column-count: {{VALUE}};'
				)
            )
        );
		
		$this->add_control(
			'show_play_icon'
			,array(
				'label' 		=> esc_html__( 'Play Icon', 'themesky' )
				,'type' 		=> Controls_Manager::SWITCHER
				,'default' 		=> '1'
				,'return_value' => '1'
				,'description' 	=> esc_html__( 'Only available if has Image Overlay', 'themesky' )
			)
		);
		
		$this->add_control(
			'play_icon'
			,array(
				'label' 			=> esc_html__( 'Icon', 'themesky' )
				,'type' 			=> Controls_Manager::ICONS
				,'label_block' 		=> false
				,'skin' 			=> 'inline'
				,'skin_settings' 	=> array(
					'inline' 	=> array(
						'none' 	=> array(
							'label' => esc_html__( 'Default', 'themesky' )
							,'icon' => 'eicon-play'
						)
						,'icon' => array(
							'icon' => 'eicon-star'
						)
					)
				)
				,'recommended' 	=> array(
					'fa-regular' 	=> array(
						'play-circle'
					)
					,'fa-solid' => array(
						'play'
						,'play-circle'
					)
				)
				,'condition'	=> array( 'show_play_icon' => '1' )
			)
		);
		
		$this->add_control(
			'lightbox'
			,array(
				'label' 		=> esc_html__( 'Lightbox', 'themesky' )
				,'type' 		=> Controls_Manager::SWITCHER
				,'default' 		=> '0'
				,'return_value' => '1'
				,'description' 	=> esc_html__( 'Only available if has Image Overlay', 'themesky' )
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
                ,'type'			=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'min'      	=> 1
				,'default' 		=> 3
            )
        );
		
		$this->add_control(
            'partial_view'
            ,array(
                'label' 		=> esc_html__( 'Partial View', 'themesky' )
                ,'type'			=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'Only available if Columns is even', 'themesky' )
            )
        );
		
		$this->add_product_slider_controls_basic();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_style'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_control(
            'heading_title_font'
            ,array(
                'label'     	=> esc_html__( 'Heading Title', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'title_style'
            ,array(
                'label' 		=> esc_html__( 'Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'title-default'
				,'options'		=> array(
					'title-default'			=> esc_html__( 'Default', 'themesky' )
					,'title-center'			=> esc_html__( 'Center', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_title_and_style_controls();
		
		$this->add_control(
            'play_icon_style'
            ,array(
                'label'     	=> esc_html__( 'Play Icon', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
			'play_icon_color'
			,array(
				'label' 		=> esc_html__( 'Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} .elementor-custom-embed-play i' 	=> 'color: {{VALUE}}'
					,'{{WRAPPER}} .elementor-custom-embed-play svg' => 'fill: {{VALUE}}'
				)
			)
		);
		
		$this->add_responsive_control(
			'play_icon_size'
			,array(
				'label' 	=> esc_html__( 'Size', 'themesky' )
				,'type' 	=> Controls_Manager::SLIDER
				,'range' 	=> array(
					'px' 	=> array(
						'min' 	=> 10
						,'max' 	=> 300
					)
				)
				,'selectors' => array(
					'{{WRAPPER}} .elementor-custom-embed-play i' 	=> 'font-size: {{SIZE}}px'
					,'{{WRAPPER}} .elementor-custom-embed-play svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;'
				)
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'title'					=> ''
			,'title_style'			=> 'title-default'
			,'videos'				=> array()
			,'aspect_ratio'			=> '169'
			,'show_play_icon'		=> 1
			,'play_icon'			=> array( 'value' => '', 'library' => '' )
			,'lightbox'				=> 0
			,'is_slider'			=> 0
			,'columns'				=> 3
			,'partial_view'			=> 0
			,'show_nav'				=> 0
			,'auto_play'			=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( empty($videos) ){
			return;
		}
		
		$classes = array('ts-videos-elementor-widget');
		
		$classes[] = 'ts-shortcode';
		$classes[] = $title_style;
		if( $is_slider ){
			$classes[] = 'ts-slider';
			if( $show_nav ){
				$classes[] = 'show-nav middle-thumbnail';
			}
			if( $partial_view ){
				$classes[] = 'partial-view';
			}
		}
		else{
			$classes[] = 'grid-layout';
		}
		
		$data_attr = array();
		if( $is_slider ){
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
			$data_attr[] = 'data-columns="'.$columns.'"';
		}
		?>
		<div class="<?php echo implode(' ', $classes); ?>" <?php echo implode(' ', $data_attr) ?>>
			<?php if( $title ){ ?>
			<header class="shortcode-heading-wrapper">
				<h2 class="shortcode-title">
					<?php echo esc_html($title); ?>
				</h2>
			</header>
			<?php } ?>
			
			<div class="videos items <?php echo $is_slider ? 'loading' : '' ?>">
			<?php
			foreach( $videos as $index => $video ){
				if( 'hosted' === $video['video_type'] ){
					if( $video['insert_url'] ){
						$video_url = $video['external_url'];
					}else{
						$video_url = $video['hosted_url']['url'];
					}
				}
				else{
					$video_url = $video[ $video['video_type'] . '_url' ];
				}
				
				if( !$video_url ){
					continue;
				}
				
				$has_image_overlay = $video['show_image_overlay'] && !empty($video['image_overlay']['url']);
				$enable_lightbox = $lightbox && $has_image_overlay;
				
				$classes = array();
				?>
				<div class="item">
				<?php
				if( 'hosted' === $video['video_type'] ){
					$classes[] = 'e-hosted-video';
					ob_start();
					?>
					<video class="elementor-video" src="<?php echo esc_url( $video_url ); ?>" preload="<?php echo $has_image_overlay ? 'none' : 'metadata' ?>" controls></video>
					<?php
					$video_html = ob_get_clean();
				}
				else{
					$video_url = $this->parse_video_link( $video_url );
					ob_start();
					?>
					<iframe class="elementor-video" src="<?php echo esc_url($video_url); ?>" width="100%" height="100%" allowfullscreen="1" frameborder="0"></iframe>
					<?php
					$video_html = ob_get_clean();
				}
				
				$classes[] = 'elementor-wrapper';
				$classes[] = 'elementor-open-' . ( $enable_lightbox ? 'lightbox' : 'inline' );
				?>
				<div class="<?php echo implode(' ', $classes); ?>">
					<?php
					if( !$enable_lightbox ){
						echo $video_html;
					}
					
					if( $has_image_overlay ){
						$render_attr_id = 'image-overlay-' . $video['_id'];
						$this->add_render_attribute( $render_attr_id, 'class', 'elementor-custom-embed-image-overlay' );
						
						if( $enable_lightbox ){
							if( 'hosted' === $video['video_type'] ){
								$lightbox_url = $video_url;
							}
							else{
								$lightbox_url = Elementor\Embed::get_embed_url( $video_url, array('autoplay' => '1'), array() );
							}
							
							$lightbox_options = array(
								'type' 			=> 'video'
								,'videoType' 	=> $video['video_type']
								,'url' 			=> $lightbox_url
								,'modalOptions' => array(
									'id' 						=> 'elementor-lightbox-' . $video['_id']
									,'entranceAnimation' 		=> ''
									,'entranceAnimation_tablet' => ''
									,'entranceAnimation_mobile' => ''
									,'videoAspectRatio' 		=> $aspect_ratio
								)
							);
							
							if( 'hosted' === $video['video_type'] ){
								$lightbox_options['videoParams'] = array();
							}
							
							$this->add_render_attribute( $render_attr_id, array(
								'data-elementor-open-lightbox' 	=> 'yes'
								,'data-elementor-lightbox' 		=> wp_json_encode( $lightbox_options )
								,'data-e-action-hash' 			=> Elementor\Plugin::instance()->frontend->create_action_hash( 'lightbox', $lightbox_options )
							) );
							
							if( Elementor\Plugin::$instance->editor->is_edit_mode() ){
								$this->add_render_attribute( $render_attr_id, array(
									'class' => 'elementor-clickable'
								) );
							}
						}
						else{
							$this->add_render_attribute( $render_attr_id, 'style', 'background-image: url(' . $video['image_overlay']['url'] . ');' );
						}
						?>
						<div <?php $this->print_render_attribute_string( $render_attr_id ); ?>>
							<?php
							if( $enable_lightbox ){
								Elementor\Group_Control_Image_Size::print_attachment_image_html( $video, 'image_overlay' );
							}
							
							if( $show_play_icon ){
							?>
								<div class="elementor-custom-embed-play" role="button" aria-label="<?php $this->print_a11y_text( $video['image_overlay'] ); ?>" tabindex="0">
									<?php
									if( empty( $play_icon['value'] ) ){
										$play_icon = array(
											'library' 	=> 'eicons'
											,'value' 	=> 'eicon-play'
										);
									}
									Elementor\Icons_Manager::render_icon( $play_icon, array( 'aria-hidden' => 'true' ) );
									?>
									<span class="elementor-screen-only"><?php $this->print_a11y_text( $video['image_overlay'] ); ?></span>
								</div>
							<?php
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
				<?php
			}
			?>
			</div>
		</div>
		<?php
	}
	
	function parse_video_link( $video_url ){
		if( strstr($video_url, 'youtube.com') || strstr($video_url, 'youtu.be') ){
			preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match);
			if( count($match) >= 2 ){
				return 'https://www.youtube.com/embed/' . $match[1];
			}
		}
		elseif( strstr($video_url, 'vimeo.com') ){
			preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $video_url, $match);
			if( count($match) >= 2 ){
				return 'https://player.vimeo.com/video/' . $match[1];
			}
			else{
				$video_id = explode('/', $video_url);
				if( is_array($video_id) && !empty($video_id) ){
					$video_id = $video_id[count($video_id) - 1];
					return 'https://player.vimeo.com/video/' . $video_id;
				}
			}
		}
		return $video_url;
	}
	
	function print_a11y_text( $image_overlay ) {
		if( empty( $image_overlay['alt'] ) ){
			echo esc_html__( 'Play Video', 'themesky' );
		}else{
			echo esc_html__( 'Play Video about', 'themesky' ) . ' ' . esc_attr( $image_overlay['alt'] );
		}
	}
}

$widgets_manager->register( new TS_Elementor_Widget_videos() );