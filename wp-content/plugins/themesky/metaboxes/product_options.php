<?php 
$options = array();
$default_sidebars = function_exists('loobek_get_list_sidebars')? loobek_get_list_sidebars(): array();
$sidebar_options = array(
				'0'	=> esc_html__('Default', 'themesky')
				);
foreach( $default_sidebars as $key => $_sidebar ){
	$sidebar_options[$_sidebar['id']] = $_sidebar['name'];
}

$custom_blocks = function_exists('loobek_get_custom_block_options') ? loobek_get_custom_block_options() : array();

$size_chart_ids = array( '' => '' );
$args = array(
	'post_type'			=> 'ts_size_chart'
	,'post_status' 		=> 'publish'
	,'posts_per_page' 	=> -1
);

$size_charts = new WP_Query($args);
if( $size_charts->have_posts() ){
	foreach( $size_charts->posts as $p ){
		$size_chart_ids[$p->ID] = $p->post_title;
	} 
}

wp_reset_postdata();

$options[] = array(
				'id'		=> 'prod_layout_heading'
				,'label'	=> esc_html__('Product Layout', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);
			
$options[] = array(
				'id'		=> 'prod_layout'
				,'label'	=> esc_html__('Product Layout', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> array(
									'0'			=> esc_html__('Default', 'themesky')
									,'0-1-0'  	=> esc_html__('Fullwidth', 'themesky')
									,'1-1-0' 	=> esc_html__('Left Sidebar', 'themesky')
									,'0-1-1' 	=> esc_html__('Right Sidebar', 'themesky')
									,'1-1-1' 	=> esc_html__('Left & Right Sidebar', 'themesky')
								)
			);
			
$options[] = array(
				'id'		=> 'prod_left_sidebar'
				,'label'	=> esc_html__('Left Sidebar', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $sidebar_options
			);
			
$options[] = array(
				'id'		=> 'prod_right_sidebar'
				,'label'	=> esc_html__('Right Sidebar', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $sidebar_options
			);

$options[] = array(
				'id'		=> 'prod_custom_tab_heading'
				,'label'	=> esc_html__('Custom Tab', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);
			
$options[] = array(
				'id'		=> 'prod_custom_tab'
				,'label'	=> esc_html__('Custom Tab', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> array(
									'0'		=> esc_html__('Default', 'themesky')
									,'1'	=> esc_html__('Override', 'themesky')
								)
			);
			
$options[] = array(
				'id'		=> 'prod_custom_tab_title'
				,'label'	=> esc_html__('Custom Tab Title', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'prod_custom_tab_content'
				,'label'	=> esc_html__('Custom Tab Content', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $custom_blocks
				,'select_post' => 'ts_custom_block'
			);		
			
$options[] = array(
				'id'		=> 'prod_breadcrumb_heading'
				,'label'	=> esc_html__('Breadcrumbs', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);

$options[] = array(
				'id'		=> 'bg_breadcrumbs'
				,'label'	=> esc_html__('Breadcrumb Background Image', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'upload'
			);
			
$options[] = array(
				'id'		=> 'prod_video_heading'
				,'label'	=> esc_html__('Video', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);

$options[] = array(
				'id'		=> 'prod_video_url'
				,'label'	=> esc_html__('Video URL', 'themesky')
				,'desc'		=> esc_html__('Enter Youtube or Vimeo video URL', 'themesky')
				,'type'		=> 'text'
			);

$options[] = array(
				'id'		=> 'prod_360_heading'
				,'label'	=> esc_html__('360 Gallery', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);

$options[] = array(
				'id'		=> 'prod_360_gallery'
				,'label'	=> ''
				,'desc'		=> ''
				,'class'	=> 'context-normal'
				,'type'		=> 'gallery'
			);

$options[] = array(
				'id'		=> 'prod_size_chart_heading'
				,'label'	=> esc_html__('Size Chart', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);	

$options[] = array(
				'id'		=> 'prod_size_chart'
				,'label'	=> esc_html__('Size Chart', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $size_chart_ids
				,'select_post' => 'ts_size_chart'
			);

$options[] = array(
				'id'		=> 'prod_bottom_summary_content_heading'
				,'label'	=> esc_html__('Bottom Summary Content', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);	

$options[] = array(
				'id'		=> 'prod_bottom_summary_content'
				,'label'	=> esc_html__('Bottom Summary Content', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $custom_blocks
				,'select_post' => 'ts_custom_block'
			);

$options[] = array(
				'id'		=> 'prod_custom_content_heading'
				,'label'	=> esc_html__('Custom Content', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);	

$options[] = array(
				'id'		=> 'prod_custom_content'
				,'label'	=> esc_html__('Custom Content', 'themesky')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $custom_blocks
				,'select_post' => 'ts_custom_block'
			);			
?>