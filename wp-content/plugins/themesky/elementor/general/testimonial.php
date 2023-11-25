<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Testimonial extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-testimonial';
    }
	
	public function get_title(){
        return esc_html__( 'TS Testimonial', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-testimonial';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 50, 'thumb-label' => esc_html__('Lazy Load - Content Height', 'themesky') ) );
		
		$this->add_control(
            'title'
            ,array(
                'label' 		=> esc_html__( 'Title', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'style'
            ,array(
                'label' 			=> esc_html__( 'Item Style', 'themesky' )
                ,'type' 			=> Controls_Manager::SELECT
                ,'default' 			=> 'style-1'
				,'options'			=> array(
					'style-1'		=> esc_html__( 'Style 01', 'themesky' )
					,'style-2'		=> esc_html__( 'Style 02', 'themesky' )
					,'style-3'		=> esc_html__( 'Style 03', 'themesky' )
				)			
                ,'description' 		=> ''
            )
        );
		
		$this->add_control(
            'source'
            ,array(
                'label' 			=> esc_html__( 'Source', 'themesky' )
                ,'type' 			=> Controls_Manager::SELECT
                ,'default' 			=> 'testimonial'
				,'options'			=> array(
					'testimonial'	=> esc_html__( 'Testimonial Posts', 'themesky' )
					,'product'		=> esc_html__( 'Product Reviews', 'themesky' )
				)			
                ,'description' 		=> ''
            )
        );
		
		$this->add_control(
            'categories'
            ,array(
                'label' 		=> esc_html__( 'Categories', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'ts_testimonial_cat'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
				,'condition'	=> array( 'source' => 'testimonial' )
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label' 		=> esc_html__( 'Specific Testimonials', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'ts_testimonial'
				)
				,'multiple' 	=> true
				,'label_block' 	=> true
				,'condition'	=> array( 'source' => 'testimonial' )
            )
        );
		
		$this->add_control(
            'show_best_reviews'
            ,array(
                'label' 		=> esc_html__( 'Show Best Reviews', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
                ,'description' 	=> esc_html__( 'Disable this option if you want to specify reviews', 'themesky' )
				,'condition'	=> array( 'source' => 'product' )
            )
        );
		
		$this->add_control(
            'review_ids'
            ,array(
                'label' 		=> esc_html__( 'Specific Reviews', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'product_review'
				)
				,'multiple' 	=> true
				,'label_block' 	=> true
                ,'description' 	=> ''
				,'condition'	=> array( 'source' => 'product', 'show_best_reviews!' => '1' )
            )
        );
		
		$this->add_control(
            'min_rating'
            ,array(
                'label' 		=> esc_html__( 'Min Rating', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '4'
				,'options'		=> array(
						'1'			=> '1'
						,'2'		=> '2'
						,'3'		=> '3'
						,'4'		=> '4'
						,'5'		=> '5'
				)			
                ,'description' 	=> esc_html__( 'Only show reviews whose ratings are greater than or equal this value', 'themesky' )
				,'condition'	=> array( 'source' => 'product' )
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label' 		=> esc_html__( 'Columns', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '3'
				,'options'		=> array(
						'1'			=> '1'
						,'2'		=> '2'
						,'3'		=> '3'
						,'4'		=> '4'
				)			
                ,'description' 	=> esc_html__( 'Number of Columns', 'themesky' )
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     => esc_html__( 'Limit', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 6
				,'min'      => 1
            )
        );
		
		$this->add_control(
            'show_image'
            ,array(
                'label' 		=> esc_html__( 'Show Image', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_byline'
            ,array(
                'label' 		=> esc_html__( 'Show Byline/Customer Name', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_avatar'
            ,array(
                'label' 		=> esc_html__( 'Show Avatar', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
				,'condition'	=> array( 'show_byline' => '1' )
            )
        );
		
		$this->add_control(
            'show_title'
            ,array(
                'label' 		=> esc_html__( 'Show Title', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_rating'
            ,array(
                'label' 		=> esc_html__( 'Show Rating', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_responsive_control(
			'rating_size'
			,array(
				'label' 		=> esc_html__( 'Rating Size', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'range' 		=> array(
					'px' 		=> array(
						'min' 	=> 10
						,'max' 	=> 50
					)
				)
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-testimonial-wrapper .rating
					,{{WRAPPER}} .ts-testimonial-wrapper .rating span:before
					,{{WRAPPER}} .ts-testimonial-wrapper .rating:before' => 'font-size: {{SIZE}}px;'
				)
				,'condition'	=> array( 'show_rating' => '1' )
			)
		);
		
		$this->add_control(
            'excerpt_words'
            ,array(
                'label'     => esc_html__( 'Number of Words in Excerpt', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 40
				,'min'      => 1
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
					'{{WRAPPER}} .ts-testimonial-wrapper' => 'text-align: {{VALUE}};'
				)
				,'default' 		=> ''
				,'toggle' 		=> true
				,'prefix_class' => 'text%s-'
			)
        );
		
		$this->add_responsive_control(
			'heading_padding'
			,array(
				'label' 		=> esc_html__( 'Item Padding', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'separator'	=> 'before'
			)
		);
		
		$this->add_control(
            'background_color'
            ,array(
                'label'     	=> esc_html__( 'Item Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'separator'	=> 'before'
				,'selectors'	=> array(
					'{{WRAPPER}} .testimonial-content' => 'background-color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_style'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_control(
            'heading_button_title'
            ,array(
                'label'     	=> esc_html__( 'Heading Title', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'title_style'
            ,array(
                'label' 		=> esc_html__( 'Title Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'title-default'
				,'options'		=> array(
					'title-default'		=> esc_html__( 'Default', 'themesky' )
					,'title-center'		=> esc_html__( 'Center', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_title_and_style_controls();
		
		$this->add_control(
            'excerpt_button_title'
            ,array(
                'label'     	=> esc_html__( 'Excerpt', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'excerpt_typography'
				,'selector'			=> '{{WRAPPER}} .testimonial-content .content'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '18'
							,'unit' 	=> 'px'
						)
						,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
					)
					,'line_height'		=> array(
						'default' 		=> array(
							'size' 		=> '28'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'	=> array('text_decoration', 'font_style', 'word_spacing')
			)
		);
		
		$this->add_control(
            'rating_title'
            ,array(
                'label'     	=> esc_html__( 'Rating', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		$this->add_control(
            'rating_color'
            ,array(
                'label'     	=> esc_html__( 'Rating Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-testimonial-wrapper .rating:before,
					{{WRAPPER}} .ts-testimonial-wrapper .rating span:before' => 'color: {{VALUE}} !important'
				)
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
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_product_slider_controls_full();
		
		$this->add_control(
            'disable_slider_responsive'
            ,array(
                'label' 		=> esc_html__( 'Disable Slider Responsive', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'You should only enable this option when Columns is 1 or 2', 'themesky' )
            )
        );
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'						=> 0
			,'categories'					=> array()
			,'columns'						=> 1
			,'source'						=> 'testimonial'
			,'style'						=> 'style-1'
			,'title'						=> ''
			,'title_style'					=> ''
			,'limit'						=> 5
			,'ids'							=> array()
			,'show_best_reviews'			=> 1
			,'min_rating'					=> 4
			,'review_ids'					=> array()
			,'show_image'					=> 0
			,'show_avatar'					=> 0
			,'show_title'					=> 1
			,'show_byline'					=> 0
			,'show_rating'					=> 1
			,'background_color'				=> ''
			,'excerpt_words'				=> 40
			,'is_slider'					=> 1
			,'show_nav'						=> 0
			,'show_dots'					=> 0
			,'show_scrollbar'				=> 0
			,'auto_play'					=> 0
			,'disable_slider_responsive'	=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( $source == 'product' && !class_exists('WooCommerce') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'testimonial' ) ){
			return;
		}

		$items = array();
		
		if( $source == 'testimonial' ){
			global $post, $ts_testimonials;
			
			$args = array(
				'post_type'				=> 'ts_testimonial'
				,'post_status'			=> 'publish'
				,'posts_per_page' 		=> $limit
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
			);
		
			if( is_array($categories) && count($categories) > 0 ){
				$args['tax_query'] = array(
										array(
											'taxonomy' 			=> 'ts_testimonial_cat'
											,'terms' 			=> $categories
											,'field' 			=> 'term_id'
											,'include_children' => false
										)
									);
			}
			
			if( is_array($ids) && count($ids) > 0 ){
				$args['post__in'] = $ids;
				$args['orderby'] = 'post__in';
			}
			
			$testimonials = new WP_Query($args);
			
			if( $testimonials->have_posts() ){
				while( $testimonials->have_posts() ){
					$testimonials->the_post();
					$item = array();
					
					$item['content'] = wp_trim_words( $post->post_content, $excerpt_words );
					
					$item['byline'] = get_post_meta($post->ID, 'ts_byline', true);
					$item['rating'] = get_post_meta($post->ID, 'ts_rating', true);
					$item['avatar'] = $ts_testimonials->get_avatar($post->ID);
					
					$item['title'] 	= get_the_title($post->ID);
					$item['url'] 	= get_post_meta($post->ID, 'ts_url', true);
					$item['image'] 	= $ts_testimonials->get_image($post->ID);
					
					$items[] = $item;
				}
			}
		}
		else{
			$args = array(
				'post_type'	=> 'product'
				,'orderby'	=> 'comment_date_gmt'
				,'order'	=> 'DESC'
				,'status'	=> 'approve'
				,'number'	=> $limit
				,'meta_query'	=> array(
					array(
						'key'			=> 'rating'
						,'compare'		=> '>='
						,'value'		=> $min_rating
					)
				)
			);
			
			if( $show_best_reviews ){
				$args['orderby'] 	= 'meta_value';
				$args['order'] 		= 'DESC';
				$args['meta_key'] 	= 'rating';
			}
			else{
				if( is_array($review_ids) && count($review_ids) > 0 ){
					$args['comment__in'] 	= $review_ids;
					$args['orderby'] 		= 'comment__in';
				}
			}
			
			$reviews = get_comments( $args );
			if( is_array($reviews) && !empty($reviews) ){
				foreach( $reviews as $review ){
					$item = array();
					$item['content'] = wp_trim_words( $review->comment_content, $excerpt_words );
					$item['byline']	= $review->comment_author;
					$item['rating'] = intval( get_comment_meta( $review->comment_ID, 'rating', true ) );
					$item['avatar'] = get_avatar( $review->comment_author_email );
					
					$product = wc_get_product( $review->comment_post_ID );
					if( is_object($product) ){
						$item['title'] 	= $product->get_name();
						$item['url'] 	= $product->get_permalink();
						$item['image'] 	= $product->get_image();
					}
					else{
						$item['title'] 	= '';
						$item['url'] 	= '';
						$item['image'] 	= '';
					}
					
					$items[] = $item;
				}
			}
		}
		
		if( $items ){
			$classes = array();
			$classes[] = $style;
			$classes[] = $title_style;
			$classes[] = 'columns-'.$columns;
			
			if( $background_color ){
				$classes[] = 'has-background';
			}
			
			if( !$show_avatar ){
				$classes[] = 'no-avatar';
			}
			if( $show_title && $show_byline && $style == 'style-1' ){
				$classes[] = 'show-more-text';
			}
			
			if( $is_slider ){
				$classes[] = 'ts-slider';
				if( $show_scrollbar ){
					$classes[] = 'show-scrollbar';
					$show_dots = 0;
				}
				if( $show_dots ){
					$classes[] = 'show-dots';
				}
				if( $show_nav ){
					$classes[] = 'show-nav';
				}
			}
			
			$data_attr = array();
			if( $is_slider ){
				$data_attr[] = 'data-columns="'.$columns.'"';
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-dots="'.$show_dots.'"';
				$data_attr[] = 'data-scrollbar="'.$show_scrollbar.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-disable_responsive="'.$disable_slider_responsive.'"';
			}
			?>
			<div class="ts-testimonial-wrapper ts-shortcode <?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo implode(' ', $data_attr); ?>>

				<?php if( $title ){ ?>
				<header class="shortcode-heading-wrapper">
					<h2 class="shortcode-title">
						<?php echo esc_html($title); ?>
					</h2>
				</header>
				<?php } ?>
				
				<div class="items <?php echo $is_slider?'loading':'' ?>">
				<?php
				foreach( $items as $item ){
					$rating_percent = '0';
					if( $item['rating'] != '-1' && $item['rating'] != '' ){
						$rating_percent = $item['rating'] * 100 / 5;
					}
					
					?>
					<div class="item">
						<div class="testimonial-content">
							
							<?php if( $style == 'style-2' ){ ?>
								<div class="content-top">
								
									<?php if( $show_image && $item['image'] ){ ?>
									<div class="image">
										<?php echo $item['image']; ?>
									</div>
									<?php } ?>
								
									<?php
									if( $show_title ){
										$this->item_title_html( $item );
									}
									?>
								
								</div>
							<?php } ?>
							
							<?php if( $show_rating && $item['rating'] != '-1' && $item['rating'] != '' ){ ?>
							<div class="rating-wrapper">
								<div class="rating" title="<?php printf( esc_html__('Rated %s out of 5', 'themesky'), $item['rating'] ); ?>">
									<span style="width: <?php echo $rating_percent . '%'; ?>"><?php printf( esc_html__('Rated %s out of 5', 'themesky'), $item['rating'] ); ?></span>
								</div>
							</div>
							<?php } ?>
							
							<div class="content">
								<?php echo esc_html($item['content']); ?>
							</div>
							
							<?php if( $style == 'style-3' ){ ?>
								<div class="content-middle"><?php $this->item_avatar_byline_html( $item, $show_avatar, $show_byline ); ?></div>
							<?php } ?>
							
							<div class="content-bottom">
								
								<?php
								if( $style != 'style-3' ){
									$this->item_avatar_byline_html( $item, $show_avatar, $show_byline );
								}
								?>
								
								<?php if( $show_title && $style == 'style-1' ){ ?>
									
									<span><?php esc_html_e('in', 'themesky'); ?></span>
									
									<?php
									$this->item_title_html( $item );
								}
								?>
								
								<?php if( $show_image && $item['image'] && $style != 'style-2' ){ ?>
								<div class="image">
									<?php echo $item['image']; ?>
								</div>
								<?php } ?>
								
								<?php
								if( $show_title && $style == 'style-3' ){
									$this->item_title_html( $item );
								}
								?>
								
							</div>
							
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
	
	function item_title_html( $item ){
		if( $item['url'] ){
		?>
			<a class="title" href="<?php echo esc_url($item['url']); ?>" target="_blank"><?php echo esc_html($item['title']); ?></a>
		<?php }else{ ?>
			<span class="title"><?php echo esc_html($item['title']); ?></span>
		<?php 
		}
	}
	
	function item_avatar_byline_html( $item, $show_avatar, $show_byline ){
		if( $show_byline ){
			if( $show_avatar && $item['avatar'] ){
			?>
			<div class="avatar">
				<?php echo $item['avatar']; ?>
			</div>
			<?php
			}
			?>
			<span class="author">
				<?php echo esc_html($item['byline']); ?>
			</span>
			<?php
		}
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Testimonial() );