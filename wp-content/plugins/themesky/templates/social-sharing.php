<?php $post_link = get_permalink(); ?>

<div class="ts-social-sharing">
	<ul>
		<?php do_action('ts_social_sharing_buttons_before'); ?>
		
		<li class="linkedin">
			<a href="http://linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url($post_link); ?>&amp;title=<?php echo esc_attr(sanitize_title(get_the_title())); ?>" target="_blank"><i class="icomoon-linkedin"></i></a>
		</li>
		
		<li class="facebook">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($post_link); ?>" target="_blank"><i class="icomoon-facebook"></i></a>
		</li>
		
		<li class="twitter">
			<a href="https://twitter.com/intent/tweet?text=<?php echo esc_url($post_link); ?>" target="_blank"><i class="icomoon-twitter"></i></a>
		</li>
		
		<li class="pinterest">
			<?php $image_link  = wp_get_attachment_url( get_post_thumbnail_id() );?>
			<a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url($post_link); ?>&amp;media=<?php echo esc_url($image_link);?>" target="_blank"><i class="icomoon-pinterest"></i></a>
		</li>
		
		<li class="viber">
			<a href="viber://forward?text=<?php echo esc_url($post_link); ?>" target="_blank"><i class="icomoon-viber"></i></a>
		</li>
		
		<?php do_action('ts_social_sharing_buttons_after'); ?>
	</ul>
</div>