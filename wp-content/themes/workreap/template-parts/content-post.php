<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */
?>
<?php if(!empty(get_the_content())){?>
<article id="post-<?php the_ID(); ?>" <?php post_class('wr-theme-box'); ?>>
	<div class="workreap-entry-content">
		<?php
			the_content();
			
			wp_link_pages(
				array(
					'before' => '<div class="workreap-page-links">' . esc_html__( 'Pages:', 'workreap' ),
					'after'  => '</div>',
				)
			);
		?>
	</div>
</article>
<?php }?>