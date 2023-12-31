<?php
get_header();

$theme_options = loobek_get_theme_options();

$page_column_class = loobek_page_layout_columns_class($theme_options['ts_blog_layout'], $theme_options['ts_blog_left_sidebar'], $theme_options['ts_blog_right_sidebar']);

$extra_class = '';
if( is_search() ){
	loobek_breadcrumbs_title(true, true, esc_html__( 'Search Results for: ', 'loobek' ) . get_search_query());
	$extra_class = 'show-breadcrumb-'.$theme_options['ts_breadcrumb_layout'];
}
?>
<div class="index-template page-container <?php echo esc_attr($extra_class) ?> <?php echo esc_attr($page_column_class['main_class']); ?>">
	<!-- Left Sidebar -->
	<?php if( $page_column_class['left_sidebar'] ): ?>
		<aside id="left-sidebar" class="ts-sidebar">
			<?php dynamic_sidebar( $theme_options['ts_blog_left_sidebar'] ); ?>
		</aside>
	<?php endif; ?>			
	
	<div id="main-content">	
		<div id="primary" class="site-content">
			
			<?php	
				if( have_posts() ):
					echo '<div class="list-posts columns-' . loobek_get_theme_options('ts_blog_columns') . '">';
					while( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() ); 
					endwhile;
					echo '</div>';
				else:
					echo '<div class="alert alert-error">';
						echo '<h3>'.esc_html__('Sorry. There are no posts to display!', 'loobek').'</h3>';
						echo '<p class="ts-aligncenter">'.esc_html__('Try researching for something else.', 'loobek').'</p>';
					echo '</div>';
					echo '<div class="search-wrapper">';
						get_search_form();
					echo '</div>';
				endif;
				
				loobek_pagination();
			?>

		</div>
	</div>
	
	
	<!-- Right Sidebar -->
	<?php if( $page_column_class['right_sidebar'] ): ?>
		<aside id="right-sidebar" class="ts-sidebar">
			<?php dynamic_sidebar( $theme_options['ts_blog_right_sidebar'] ); ?>
		</aside>
	<?php endif; ?>	
		
</div>
<?php get_footer(); ?>