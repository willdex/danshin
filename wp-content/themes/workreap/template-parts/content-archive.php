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
	<?php workreap_post_thumbnail(); ?>
	<header class="workreap-entry-header">
		<?php echo get_the_term_list($post->ID, 'category', '<ul class="workreap-cat-links"><li>', '</li><li>', '</li></ul>'); ?>
		<?php
		if ( is_singular() ) {
			the_title( '<h4 class="workreap-entry-title">', '</h4>' );
		}else{
			the_title( '<h4 class="workreap-page-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' );
		}

		if ( 'post' === get_post_type() ) {?>
			<div class="workreap-entry-meta">
				<span class="workreap-byline">
                    <i class="wr-icon-message-square"></i>
					<span><?php comments_number(esc_html__('0 Comments' , 'workreap') , esc_html__('1 Comment' , 'workreap') , esc_html__('% Comments' , 'workreap')); ?></span>
                </span>
				<?php
					workreap_posted_on();
				?>
			</div>
		<?php } ?>
	</header>
	<div class="workreap-entry-content">
		<?php
		if (!empty(get_the_excerpt())) {
			the_excerpt();
		}
		
		wp_link_pages(
			array(
				'before' => '<div class="workreap-page-links">' . esc_html__( 'Pages:', 'workreap' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article>