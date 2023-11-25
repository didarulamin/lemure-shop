<?php 
$theme_options 	= loobek_get_theme_options();
$instagram_url 	= $theme_options['ts_instagram_url'];
$tiktok_url 	= $theme_options['ts_tiktok_url'];
$youtube_url 	= $theme_options['ts_youtube_url'];
$twitter_url 	= $theme_options['ts_twitter_url'];
$linkedin_url 	= $theme_options['ts_linkedin_url'];
$facebook_url 	= $theme_options['ts_facebook_url'];
$pinterest_url 	= $theme_options['ts_pinterest_url'];
$custom_url 	= $theme_options['ts_custom_social_url'];
$custom_text 	= $theme_options['ts_custom_social_text'];
$custom_class 	= $theme_options['ts_custom_social_class'];
$style 			= $theme_options['ts_social_style'];
?>
<div class="social-icons <?php echo esc_attr($style) ?>">
	<ul class="list-icons">
		
		<?php if( $instagram_url ): ?>
		<li class="instagram">
			<a href="<?php echo esc_url($instagram_url); ?>" target="_blank"><span><?php esc_html_e('Instagram', 'themesky'); ?></span><i class="icomoon-instagram"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if( $tiktok_url ): ?>
		<li class="tiktok">
			<a href="<?php echo esc_url($tiktok_url); ?>" target="_blank"><span><?php esc_html_e('TikTok', 'themesky'); ?></span><i class="icomoon-tik-tok"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if( $youtube_url ): ?>
		<li class="youtube">
			<a href="<?php echo esc_url($youtube_url); ?>" target="_blank"><span><?php esc_html_e('Youtube', 'themesky'); ?></span><i class="icomoon-youtube"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if( $linkedin_url ): ?>
		<li class="linkedin">
			<a href="<?php echo esc_url($linkedin_url); ?>" target="_blank"><span><?php esc_html_e('LinkedIn', 'themesky'); ?></span><i class="icomoon-linkedin"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if( $twitter_url ): ?>
		<li class="twitter">
			<a href="<?php echo esc_url($twitter_url); ?>" target="_blank"><span><?php esc_html_e('Twitter', 'themesky'); ?></span><i class="icomoon-twitter"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if( $facebook_url ): ?>
		<li class="facebook">
			<a href="<?php echo esc_url($facebook_url); ?>" target="_blank"><span><?php esc_html_e('Facebook', 'themesky'); ?></span><i class="icomoon-facebook"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if( $pinterest_url ): ?>
		<li class="pinterest">
			<a href="<?php echo esc_url($pinterest_url); ?>" target="_blank"><span><?php esc_html_e('Pinterest', 'themesky'); ?></span><i class="icomoon-pinterest"></i></a>
		</li>
		<?php endif; ?>
		
		<?php if( $custom_url ): ?>
		<li class="custom">
			<a href="<?php echo esc_url($custom_url); ?>" target="_blank"><span><?php echo esc_html($custom_text); ?></span><i class="<?php echo esc_attr( $custom_class ); ?>"></i></a>
		</li>
		<?php endif; ?>
	</ul>
</div>