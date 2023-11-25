<?php 
class TS_Custom_Product_Category{

	function __construct(){
		if( is_admin() ){
			add_action( 'product_cat_add_form_fields', array($this, 'add_category_fields'), 20 );
			add_action( 'product_cat_edit_form_fields', array($this, 'edit_category_fields'), 20, 2 );
			add_action( 'created_term', array($this, 'save_category_fields'), 10, 3 );
			add_action( 'edit_term', array($this, 'save_category_fields'), 10, 3 );
		}
	}
	
	function add_category_fields(){
		$default_sidebars = function_exists('loobek_get_list_sidebars')?loobek_get_list_sidebars():array();
		$sidebar_options = array();
		foreach( $default_sidebars as $key => $_sidebar ){
			$sidebar_options[$_sidebar['id']] = $_sidebar['name'];
		}
		
		$custom_blocks = function_exists('loobek_get_custom_block_options')?loobek_get_custom_block_options():array();
		
		$product_attributes = function_exists('loobek_get_product_attribute_taxonomies_options')?loobek_get_product_attribute_taxonomies_options():array();
		?>
		<div class="form-field ts-product-cat-upload-field">
			<label><?php esc_html_e( 'Icon', 'themesky' ); ?></label>
			<div class="preview-image">
				<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" />
			</div>
			<div class="button-wrapper">
				<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
				<input type="hidden" name="product_cat_icon_id" class="value-field" value="" />
				<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'themesky') ?></button>
				<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'themesky') ?></button>
			</div>
		</div>
		
		<div class="form-field ts-product-cat-upload-field">
			<label><?php esc_html_e( 'Breadcrumbs Background Image', 'themesky' ); ?></label>
			<div class="preview-image">
				<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" />
			</div>
			<div class="button-wrapper">
				<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
				<input type="hidden" name="product_cat_bg_breadcrumbs_id" class="value-field" value="" />
				<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'themesky') ?></button>
				<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'themesky') ?></button>
			</div>
		</div>
		
		<div class="form-field">
			<label for="layout"><?php esc_html_e( 'Layout', 'themesky' ); ?></label>
			<select name="layout" id="layout">
				<option value=""><?php esc_html_e('Default', 'themesky') ?></option>
				<option value="0-1-0"><?php esc_html_e('Fullwidth', 'themesky') ?></option>
				<option value="1-1-0"><?php esc_html_e('Left Sidebar', 'themesky') ?></option>
				<option value="0-1-1"><?php esc_html_e('Right Sidebar', 'themesky') ?></option>
				<option value="1-1-1"><?php esc_html_e('Left & Right Sidebar', 'themesky') ?></option>
			</select>
		</div>
		
		<div class="form-field">
			<label for="left_sidebar"><?php esc_html_e( 'Left Sidebar', 'themesky' ); ?></label>
			<select name="left_sidebar" id="left_sidebar">
				<option value=""><?php esc_html_e('Default', 'themesky') ?></option>
				<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
					<option value="<?php echo esc_attr($sidebar_id); ?>"><?php echo esc_html($sidebar_name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<div class="form-field">
			<label for="right_sidebar"><?php esc_html_e( 'Right Sidebar', 'themesky' ); ?></label>
			<select name="right_sidebar" id="right_sidebar">
				<option value=""><?php esc_html_e('Default', 'themesky') ?></option>
				<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
					<option value="<?php echo esc_attr($sidebar_id); ?>"><?php echo esc_html($sidebar_name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<div class="form-field">
			<label for="description_2"><?php esc_html_e( 'Description 2', 'themesky' ); ?></label>
			<select name="description_2" id="description_2" class="ts-post-select" data-post_type="ts_custom_block">
				<?php foreach( $custom_blocks as $id => $name ): ?>
					<option value="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php esc_html_e('Similar with Description. But, content is added in Custom Block', 'themesky'); ?></p>
		</div>
		
		<div class="form-field">
			<label for="bottom_description"><?php esc_html_e( 'Bottom Description', 'themesky' ); ?></label>
			<select name="bottom_description" id="bottom_description" class="ts-post-select" data-post_type="ts_custom_block">
				<?php foreach( $custom_blocks as $id => $name ): ?>
					<option value="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php esc_html_e('By default, description is added at the top of page. This option will add another description at the bottom', 'themesky'); ?></p>
		</div>
		
		<div class="form-field">
			<label for="product_attribute"><?php esc_html_e( 'Product Attribute', 'themesky' ); ?></label>
			<select name="product_attribute" id="product_attribute">
				<?php foreach( $product_attributes as $name => $label ): ?>
					<option value="<?php echo esc_attr($name); ?>"><?php echo esc_html($label); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php esc_html_e('Used for Special Filter Area', 'themesky'); ?></p>
		</div>
		<?php
	}
	
	function edit_category_fields( $term, $taxonomy ){
		$default_sidebars = function_exists('loobek_get_list_sidebars')?loobek_get_list_sidebars():array();
		$sidebar_options = array();
		foreach( $default_sidebars as $key => $_sidebar ){
			$sidebar_options[$_sidebar['id']] = $_sidebar['name'];
		}
		
		$custom_blocks = function_exists('loobek_get_custom_block_options')?loobek_get_custom_block_options():array();
		
		$product_attributes = function_exists('loobek_get_product_attribute_taxonomies_options')?loobek_get_product_attribute_taxonomies_options():array();
	
		$icon_id = get_term_meta($term->term_id, 'icon_id', true);
		$bg_breadcrumbs_id = get_term_meta($term->term_id, 'bg_breadcrumbs_id', true);
		$layout = get_term_meta($term->term_id, 'layout', true);
		$left_sidebar = get_term_meta($term->term_id, 'left_sidebar', true);
		$right_sidebar = get_term_meta($term->term_id, 'right_sidebar', true);
		$description_2 = get_term_meta($term->term_id, 'description_2', true);
		$bottom_description = get_term_meta($term->term_id, 'bottom_description', true);
		
		$product_attribute = get_term_meta($term->term_id, 'product_attribute', true);
		?>
		<tr class="form-field ts-product-cat-upload-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon', 'themesky' ); ?></label></th>
			<td>
				<div class="preview-image">
					<?php 
					if( empty($icon_id) ){
						$icon_src = wc_placeholder_img_src();
					}
					else{
						$icon_src = wp_get_attachment_image_url( $icon_id, 'thumbnail' );
					}
					?>
					<img src="<?php echo esc_url( $icon_src ); ?>" width="60px" height="60px" />
				</div>
				<div class="button-wrapper">
					<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
					<input type="hidden" name="product_cat_icon_id" class="value-field" value="<?php echo esc_attr($icon_id) ?>" />
					<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'themesky') ?></button>
					<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'themesky') ?></button>
				</div>
			</td>
		</tr>
		
		<tr class="form-field ts-product-cat-upload-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Breadcrumbs Background Image', 'themesky' ); ?></label></th>
			<td>
				<div class="preview-image">
					<?php 
					if( empty($bg_breadcrumbs_id) ){
						$bg_breadcrumbs_src = wc_placeholder_img_src();
					}
					else{
						$bg_breadcrumbs_src = wp_get_attachment_image_url( $bg_breadcrumbs_id, 'thumbnail' );
					}
					?>
					<img src="<?php echo esc_url( $bg_breadcrumbs_src ); ?>" width="60px" height="60px" />
				</div>
				<div class="button-wrapper">
					<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
					<input type="hidden" name="product_cat_bg_breadcrumbs_id" class="value-field" value="<?php echo esc_attr($bg_breadcrumbs_id) ?>" />
					<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'themesky') ?></button>
					<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'themesky') ?></button>
				</div>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Layout', 'themesky' ); ?></label></th>
			<td>
				<select name="layout" id="layout">
					<option value="" <?php selected($layout, ''); ?>><?php esc_html_e('Default', 'themesky') ?></option>
					<option value="0-1-0" <?php selected($layout, '0-1-0'); ?>><?php esc_html_e('Fullwidth', 'themesky') ?></option>
					<option value="1-1-0" <?php selected($layout, '1-1-0'); ?>><?php esc_html_e('Left Sidebar', 'themesky') ?></option>
					<option value="0-1-1" <?php selected($layout, '0-1-1'); ?>><?php esc_html_e('Right Sidebar', 'themesky') ?></option>
					<option value="1-1-1" <?php selected($layout, '1-1-1'); ?>><?php esc_html_e('Left & Right Sidebar', 'themesky') ?></option>
				</select>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Left Sidebar', 'themesky' ); ?></label></th>
			<td>
				<select name="left_sidebar" id="left_sidebar">
					<option value="" <?php selected($left_sidebar, ''); ?>><?php esc_html_e('Default', 'themesky') ?></option>
					<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
						<option value="<?php echo esc_attr($sidebar_id); ?>" <?php selected($left_sidebar, $sidebar_id); ?>><?php echo esc_html($sidebar_name); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Right Sidebar', 'themesky' ); ?></label></th>
			<td>
				<select name="right_sidebar" id="right_sidebar">
					<option value="" <?php selected($right_sidebar, ''); ?>><?php esc_html_e('Default', 'themesky') ?></option>
					<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
						<option value="<?php echo esc_attr($sidebar_id); ?>" <?php selected($right_sidebar, $sidebar_id); ?>><?php echo esc_html($sidebar_name); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Description 2', 'themesky' ); ?></label></th>
			<td>
				<select name="description_2" id="description_2" class="ts-post-select" data-post_type="ts_custom_block">
					<?php foreach( $custom_blocks as $id => $name ): ?>
						<option value="<?php echo esc_attr($id); ?>" <?php selected($description_2, $id); ?>><?php echo esc_html($name); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e('Similar with Description. But, content is added in Custom Block', 'themesky'); ?></p>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Bottom Description', 'themesky' ); ?></label></th>
			<td>
				<select name="bottom_description" id="bottom_description" class="ts-post-select" data-post_type="ts_custom_block">
					<?php foreach( $custom_blocks as $id => $name ): ?>
						<option value="<?php echo esc_attr($id); ?>" <?php selected($bottom_description, $id); ?>><?php echo esc_html($name); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e('By default, description is added at the top of page. This option will add another description at the bottom', 'themesky'); ?></p>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Product Attribute', 'themesky' ); ?></label></th>
			<td>
				<select name="product_attribute" id="product_attribute">
					<?php foreach( $product_attributes as $name => $label ): ?>
						<option value="<?php echo esc_attr($name); ?>" <?php selected($product_attribute, $name); ?>><?php echo esc_html($label); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e('Used for Special Filter Area', 'themesky'); ?></p>
			</td>
		</tr>
		<?php
	}
	
	function save_category_fields( $term_id, $tt_id, $taxonomy ){
		if( isset($_POST['product_cat_icon_id']) ){
			update_term_meta( $term_id, 'icon_id', esc_attr( $_POST['product_cat_icon_id'] ) );
		}
		
		if( isset($_POST['product_cat_bg_breadcrumbs_id']) ){
			update_term_meta( $term_id, 'bg_breadcrumbs_id', esc_attr( $_POST['product_cat_bg_breadcrumbs_id'] ) );
		}
	
		if( isset($_POST['layout']) ){
			update_term_meta( $term_id, 'layout', esc_attr( $_POST['layout'] ) );
		}
		
		if( isset($_POST['left_sidebar']) ){
			update_term_meta( $term_id, 'left_sidebar', esc_attr( $_POST['left_sidebar'] ) );
		}
		
		if( isset($_POST['right_sidebar']) ){
			update_term_meta( $term_id, 'right_sidebar', esc_attr( $_POST['right_sidebar'] ) );
		}
		
		if( isset($_POST['description_2']) ){
			update_term_meta( $term_id, 'description_2', esc_attr( $_POST['description_2'] ) );
		}
		
		if( isset($_POST['bottom_description']) ){
			update_term_meta( $term_id, 'bottom_description', esc_attr( $_POST['bottom_description'] ) );
		}
		
		if( isset($_POST['product_attribute']) ){
			update_term_meta( $term_id, 'product_attribute', esc_attr( $_POST['product_attribute'] ) );
		}
	}
}
new TS_Custom_Product_Category();


class TS_Custom_Product_Tag{

	function __construct(){
		if( is_admin() ){
			add_action( 'product_tag_add_form_fields', array($this, 'add_tag_fields'), 20 );
			add_action( 'product_tag_edit_form_fields', array($this, 'edit_tag_fields'), 20, 2 );
			add_action( 'created_term', array($this, 'save_tag_fields'), 10, 3 );
			add_action( 'edit_term', array($this, 'save_tag_fields'), 10, 3 );
		}
		add_action( 'admin_enqueue_scripts', array($this, 'register_admin_scripts') );
	}
	
	function register_admin_scripts(){
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
	}
	
	function add_tag_fields(){
	?>
		<div class="form-field ts-product-cat-upload-field">
			<label><?php esc_html_e( 'Icon', 'themesky' ); ?></label>
			<div class="preview-image">
				<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" />
			</div>
			<div class="button-wrapper">
				<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
				<input type="hidden" name="product_tag_icon_id" class="value-field" value="" />
				<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'themesky') ?></button>
				<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'themesky') ?></button>
			</div>
		</div>
		
		<div class="form-field">
			<label><?php esc_html_e( 'Text Color', 'themesky' ); ?></label>
			<input type="text" name="product_tag_text_color" class="ts_colorpicker" value="" size="20" aria-required="true" />
		</div>
		
		<div class="form-field">
			<label><?php esc_html_e( 'Background Color', 'themesky' ); ?></label>
			<input type="text" name="product_tag_background_color" class="ts_colorpicker" value="" size="20" aria-required="true" />
		</div>
		
		<script type="text/javascript">
			jQuery(function($){
				"use strict";
				
				$('.ts_colorpicker').wpColorPicker();
			});
		</script>
	<?php
	}
	
	function edit_tag_fields( $term, $taxonomy ){
		$icon_id = get_term_meta($term->term_id, 'icon_id', true);
		$text_color = get_term_meta($term->term_id, 'text_color', true);
		$background_color = get_term_meta($term->term_id, 'background_color', true);
		
		?>
		<tr class="form-field ts-product-cat-upload-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon', 'themesky' ); ?></label></th>
			<td>
				<div class="preview-image">
					<?php 
					if( empty($icon_id) ){
						$icon_src = wc_placeholder_img_src();
					}
					else{
						$icon_src = wp_get_attachment_image_url( $icon_id, 'thumbnail' );
					}
					?>
					<img src="<?php echo esc_url( $icon_src ); ?>" width="60px" height="60px" />
				</div>
				<div class="button-wrapper">
					<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
					<input type="hidden" name="product_tag_icon_id" class="value-field" value="<?php echo esc_attr($icon_id) ?>" />
					<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'themesky') ?></button>
					<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'themesky') ?></button>
				</div>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Text Color', 'themesky' ); ?></label></th>
			<td>
				<input name="product_tag_text_color" class="ts_colorpicker" data-default-color="<?php echo esc_attr($text_color);?>" type="text" value="<?php echo esc_attr($text_color);?>" size="20" aria-required="true">
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Background Color', 'themesky' ); ?></label></th>
			<td>
				<input name="product_tag_background_color" class="ts_colorpicker" data-default-color="<?php echo esc_attr($background_color);?>" type="text" value="<?php echo esc_attr($background_color);?>" size="20" aria-required="true">
			</td>
		</tr>
		
		<script type="text/javascript">
			jQuery(function($){
				"use strict";
				
				$('.ts_colorpicker').wpColorPicker();
			});
		</script>
		<?php
	}
	
	function save_tag_fields( $term_id, $tt_id, $taxonomy ){
		if( isset($_POST['product_tag_icon_id']) ){
			if( $_POST['product_tag_icon_id'] ){
				update_term_meta( $term_id, 'icon_id', esc_attr( $_POST['product_tag_icon_id'] ) );
			}
			else{
				delete_term_meta( $term_id, 'icon_id' );
			}
		}
		
		if( isset($_POST['product_tag_text_color']) ){
			if( $_POST['product_tag_text_color'] ){
				update_term_meta( $term_id, 'text_color', esc_attr( $_POST['product_tag_text_color'] ) );
			}
			else{
				delete_term_meta( $term_id, 'text_color' );
			}
		}
		
		if( isset($_POST['product_tag_background_color']) ){
			if( $_POST['product_tag_background_color'] ){
				update_term_meta( $term_id, 'background_color', esc_attr( $_POST['product_tag_background_color'] ) );
			}
			else{
				delete_term_meta( $term_id, 'background_color' );
			}
		}
	}
}

new TS_Custom_Product_Tag();