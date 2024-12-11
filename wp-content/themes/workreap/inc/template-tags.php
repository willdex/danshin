<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package workreap
 */

if ( ! function_exists( 'workreap_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function workreap_posted_on() {
		$time_string = '<time class="workreap-entry-date workreap-published workreap-updated" datetime="%1$s">%2$s</time>';
		
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( '%s', 'post date', 'workreap' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="workreap-posted-on"><i class="wr-icon-calendar"></i>' . $posted_on . '</span>';

	}
endif;

if ( ! function_exists( 'workreap_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function workreap_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'workreap' ),
			'<span class="workreap-author workreap-vcard"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="workreap-byline"><i class="wr-icon-user"></i>&nbsp;' . $byline . '</span>';

	}
endif;

if ( ! function_exists( 'workreap_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function workreap_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() && is_singular() ) {
			$categories_list = get_the_category_list();
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '%1$s', $categories_list );
			}
		}else if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list();
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="workreap-cat-links"><i class="wr-icon-folder"></i>' . esc_html__( 'Posted in', 'workreap' ) . '</span>%1$s', $categories_list );
			}

			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'workreap' ) );
		}
	}
endif;

if ( ! function_exists( 'workreap_post_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function workreap_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :?>
			<div class="workreap-post-thumbnail">
				<?php the_post_thumbnail('full'); ?>
			</div><!-- .post-thumbnail -->
		<?php else : ?>
			<a class="workreap-post-thumbnail" href="<?php echo esc_url(get_the_permalink() ); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>
			<?php
		endif; // End is_singular().
	}
}

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Start WordPress body
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}


if ( ! function_exists( 'workreap_post_tags' ) ) :
	/**
	 * Prints HTML with meta information for the current post tags.
	 */
	function workreap_post_tags() {
		$tag_list = get_the_tag_list( '<ul class="post-categories"><li>', '</li><li>', '</li></ul>' );
		if(!empty($tag_list)){
			printf( '<div class="wr-theme-box"><span class="workreap-cat-links"><i class="wr-icon-tag"></i>' . esc_html__( 'Tags', 'workreap' ) . '%1$s</span></div>', $tag_list );
		}
	}
endif;