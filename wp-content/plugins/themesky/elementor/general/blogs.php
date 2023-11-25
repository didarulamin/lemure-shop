<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Blogs extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-blogs';
    }
	
	public function get_title(){
        return esc_html__( 'TS Blogs', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-posts-grid';
	}
	
	public function get_script_depends(){
		if( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ){
			return array('isotope');
		}
		
		return array();
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 240 ) );
		
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
            'layout'
            ,array(
                'label' 		=> esc_html__( 'Layout', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'grid'
				,'options'		=> array(
					'grid'		=> esc_html__( 'Grid', 'themesky' )
					,'list'		=> esc_html__( 'List', 'themesky' )
					,'masonry'	=> esc_html__( 'Masonry', 'themesky' )
					,'overlap'	=> esc_html__( 'Overlap', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'style'
            ,array(
                'label' 		=> esc_html__( 'Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'style-1'
				,'options'		=> array(
					'style-1'		=> esc_html__( 'Style 1', 'themesky' )
					,'style-2'		=> esc_html__( 'Style 2', 'themesky' )
				)			
                ,'description' 	=> ''
				,'condition' 	=> array( 
					'layout' 	=> array('grid', 'list', 'masonry') 
				)
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label' 		=> esc_html__( 'Columns', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '3'
				,'options'		=> array(
						'1'		=> '1'
						,'2'	=> '2'
						,'3'	=> '3'
						,'4'	=> '4'
				)			
                ,'description' 	=> esc_html__( 'Number of Columns', 'themesky' )
				,'condition' 	=> array( 
					'layout' 	=> array('grid', 'masonry', 'overlap') 
				)
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     	=> esc_html__( 'Limit', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 5
				,'min'      	=> 1
				,'description' 	=> esc_html__( 'Number of Posts', 'themesky' )
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
					,'name'		=> 'category'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'orderby'
            ,array(
                'label' 		=> esc_html__( 'Order By', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'none'
				,'options'		=> array(
					'none'		=> esc_html__( 'None', 'themesky' )
					,'ID'		=> esc_html__( 'ID', 'themesky' )
					,'date'		=> esc_html__( 'Date', 'themesky' )
					,'name'		=> esc_html__( 'Name', 'themesky' )
					,'title'	=> esc_html__( 'Title', 'themesky' )
				)		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'order'
            ,array(
                'label' 		=> esc_html__( 'Order', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'DESC'
				,'options'		=> array(
					'DESC'		=> esc_html__( 'Descending', 'themesky' )
					,'ASC'		=> esc_html__( 'Ascending', 'themesky' )
				)		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_load_more'
            ,array(
                'label' 		=> esc_html__( 'Load More Button', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
				,'condition' 	=> array( 
					'layout' 	=> array('masonry') 
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'load_more_text'
            ,array(
                'label' 		=> esc_html__( 'Load More Button Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> 'Load more'		
                ,'description' 	=> ''
                ,'condition' 	=> array( 
					'layout' 	=> array('masonry') 
				)
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_item'
            ,array(
                'label' 		=> esc_html__( 'Item', 'themesky' )
                ,'tab'   		=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'show_title'
            ,array(
                'label' 		=> esc_html__( 'Post Title', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_thumbnail'
            ,array(
                'label' 		=> esc_html__( 'Post Thumbnail', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'thumbnail_position'
            ,array(
                'label' 		=> esc_html__( 'Thumbnail Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'thumbnail-left'
				,'options'		=> array(
					'thumbnail-left'		=> esc_html__( 'Left', 'themesky' )
					,'thumbnail-right'		=> esc_html__( 'Right', 'themesky' )
				)			
                ,'description' 	=> ''
				,'condition' 	=> array( 
					'layout' 	=> array('list') 
				)
            )
        );
		
		$this->add_control(
            'show_categories'
            ,array(
                'label' 		=> esc_html__( 'Post Categories', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_author'
            ,array(
                'label' 		=> esc_html__( 'Post Author', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description'	=> ''
            )
        );
		
		$this->add_control(
            'show_comment'
            ,array(
                'label' 		=> esc_html__( 'Post Comment', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_date'
            ,array(
                'label' 		=> esc_html__( 'Post Date', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_excerpt'
            ,array(
                'label' 		=> esc_html__( 'Post Excerpt', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'excerpt_words'
            ,array(
                'label'     	=> esc_html__( 'Number of Words in Excerpt', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 14
				,'min'      	=> 1
				,'condition'	=> array( 
					'show_excerpt' => '1' 
				)
            )
        );
		
		$this->add_control(
            'show_read_more'
            ,array(
                'label' 		=> esc_html__( 'Read More Button', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'read_more_style'
            ,array(
                'label' 		=> esc_html__( 'Read More Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'read-more-style-text'
				,'options'		=> array(
					'read-more-style-text'		=> esc_html__( 'Text', 'themesky' )
					,'read-more-style-button'	=> esc_html__( 'Button', 'themesky' )
				)			
                ,'description' 	=> ''
				,'condition'	=> array( 
					'show_read_more' => '1' 
				)
            )
        );
		
		$this->add_control(
            'has_divider'
            ,array(
                'label' 		=> esc_html__( 'Divider', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'item_spacing'
            ,array(
                'label' 		=> esc_html__( 'Spacing', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'spacing-default'
				,'options'		=> array(
					'spacing-default'		=> esc_html__( 'Default', 'themesky' )
					,'spacing-large'		=> esc_html__( 'Large', 'themesky' )
				)			
                ,'description' 	=> ''
				,'condition' 	=> array( 
					'has_divider!' 	=> '1'
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_slider'
            ,array(
                'label' 	=> esc_html__( 'Slider', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
				,'condition' 	=> array( 
					'layout' 	=> array('grid', 'list', 'overlap') 
				)
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
				,'condition' 	=> array( 
					'layout' 	=> array('grid', 'list', 'overlap') 
				)
            )
        );
		
		$this->add_product_slider_controls_basic();
		
		$this->add_control(
            'disable_slider_responsive'
            ,array(
                'label' 		=> esc_html__( 'Disable Slider Responsive', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'You should only enable this option when Columns is 1 or 2', 'themesky' )
				,'condition' 	=> array( 
					'layout' 	=> array('grid', 'list', 'overlap') 
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
            'blog_title_font'
            ,array(
                'label'     	=> esc_html__( 'Blog Title', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 		=> esc_html__( 'Typography', 'themesky' )
				,'name' 		=> 'title_typography'
				,'selector'		=> '{{WRAPPER}} .ts-blogs .entry-title'
				,'exclude'		=> array('text_decoration', 'font_style', 'word_spacing')
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'					=> 0
			,'title'					=> ''
			,'title_style'				=> 'title-default'
			,'layout'					=> 'grid'
			,'style'					=> 'style-1'
			,'columns'					=> 3
			,'thumbnail_position'		=> 'thumbnail-left'
			,'categories'				=> array()
			,'limit'					=> 5
			,'orderby'					=> 'none'
			,'order'					=> 'DESC'
			,'show_title'				=> 1
			,'show_thumbnail'			=> 1
			,'show_categories'			=> 0
			,'show_author'				=> 1
			,'show_date'				=> 1
			,'show_comment'				=> 0
			,'show_excerpt'				=> 0
			,'show_read_more'			=> 1
			,'item_spacing'				=> 'spacing-default'
			,'read_more_style'			=> 'read-more-style-text'
			,'excerpt_words'			=> 14
			,'has_divider'				=> 0
			,'is_slider'				=> 1
			,'show_nav'					=> 0
			,'auto_play'				=> 0
			,'disable_slider_responsive'=> 0
			,'show_load_more'			=> 0
			,'load_more_text'			=> 'LOAD MORE'
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !is_numeric($excerpt_words) ){
			$excerpt_words = 20;
		}
		
		$is_masonry = 0;
		if( $layout == 'masonry' ){
			wp_enqueue_script( 'isotope' );
			$is_masonry = 1;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'blog' ) ){
			return;
		}
		
		$columns = absint($columns);
		if( !in_array($columns, array(1, 2, 3, 4, 6)) ){
			$columns = 4;
		}
		
		$args = array(
			'post_type' 			=> 'post'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts' 	=> 1
			,'posts_per_page'		=> $limit
			,'orderby'				=> $orderby
			,'order'				=> $order
			,'tax_query'			=> array()
		);
		
		if( is_array($categories) && count($categories) > 0 ){
			$args['tax_query'][] = array(
										'taxonomy' 	=> 'category'
										,'terms' 	=> $categories
										,'field' 	=> 'term_id'
										,'include_children' => false
									);
		}
		
		global $post;
		$posts = new WP_Query($args);
		
		if( $posts->have_posts() ):
			if( $posts->post_count <= 1 ){
				$is_slider = 0;
			}
			if( $is_slider || $posts->max_num_pages == 1 ){
				$show_load_more = 0;
			}
			
			$classes = array();
			$classes[] = 'ts-blogs-wrapper ts-shortcode ts-blogs';
			$classes[] = $title_style;
			$classes[] = $item_spacing;
			
			if( $layout == 'list' ){
				$columns = 1;
				$classes[] = $thumbnail_position;
			}
			if( $is_slider ){
				$classes[] = 'ts-slider loading';
				if( $show_nav ){
					$classes[] = 'show-nav middle-thumbnail rows-1';
				}
			}
			if( $is_masonry ){
				$classes[] = 'ts-masonry loading';
			}
			if( $has_divider ){
				$classes[] = 'has-divider';
			}
			$classes[] = 'layout-'. $layout;
			
			if( $layout == 'overlap' ){
				$style = 'style-1';
			}
			$classes[] = $style;
			$classes[] = 'columns-'.$columns;
			
			$data_attr = array();
			if( $is_slider ){
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-columns="'.$columns.'"';
				$data_attr[] = 'data-disable_responsive="'.$disable_slider_responsive.'"';
			}
			
			if( is_array($categories) ){
				$categories = implode(',', $categories);
			}
			
			$atts = compact('layout', 'style', 'columns', 'categories', 'limit', 'orderby', 'order'
							,'show_title', 'show_thumbnail', 'show_author', 'show_categories'
							,'show_date', 'show_comment', 'show_excerpt', 'show_read_more', 'read_more_style', 'excerpt_words'
							,'is_slider', 'show_nav', 'auto_play', 'disable_slider_responsive', 'is_masonry', 'show_load_more');
			?>
			<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>" <?php echo implode(' ', $data_attr); ?>>
				
				<?php if( $title ): ?>
				<header class="shortcode-heading-wrapper">
					<h2 class="shortcode-title">
						<?php echo esc_html($title); ?>
					</h2>
				</header>
				<?php endif; ?>
				
				<div class="content-wrapper">
					<div class="blogs items">
						<?php ts_get_blog_items_content($atts, $posts); ?>
					</div>
					<?php if( $show_load_more ): ?>
					<div class="load-more-wrapper">
						<a href="#" class="load-more button" data-total_pages="<?php echo $posts->max_num_pages; ?>" data-paged="2"><?php echo esc_html($load_more_text) ?></a>
					</div>
					<?php endif; ?>
				</div>
			</div>
		<?php
		endif;
		wp_reset_postdata();
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Blogs() );