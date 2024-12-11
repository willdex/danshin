<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('wr-theme-box'); ?>>
	<header class="workreap-entry-header">
		<?php
		if ( is_singular() ) {
			the_title( '<h4 class="workreap-entry-title">', '</h4>' );
		}else{
			the_title( '<h4 class="workreap-entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' );
		}

		if ( 'post' === get_post_type() ) {?>
			<div class="workreap-entry-meta">
				<?php
					workreap_posted_on();
					workreap_posted_by();
				?>
			</div>
		<?php } ?>
	</header>

	<?php workreap_post_thumbnail(); ?>
	<?php if ( !empty( get_the_content() ) ){ ?>
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
	<?php } ?>
	<footer class="workreap-entry-footer">
		<?php workreap_entry_footer(); ?>
	</footer>
</article>
