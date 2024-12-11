<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Charity
 */

if ( !function_exists( 'workreap_posted_on' ) ):
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function workreap_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_html( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_html( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'workreap' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . esc_html( $time_string ) . '</a>'
		);

		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'workreap' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on col-grey">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK

	}
endif;

if ( !function_exists( 'workreap_entry_footer' ) ):
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function workreap_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' == get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'workreap' ) );
			if ( $categories_list && workreap_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( 'In %1$s', 'workreap' ) . '</span>', $categories_list ); // WPCS: XSS OK
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'workreap' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'workreap' ) . '</span>', $tags_list ); // WPCS: XSS OK
			}
		}

		if ( !is_single() && !post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'workreap' ), esc_html__( '1 Comment', 'workreap' ), esc_html__( '% Comments', 'workreap' ) );
			echo '</span>';
		}

	}
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
if ( !function_exists( 'workreap_categorized_blog' ) ) {
	function workreap_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'workreap_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields' => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number' => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'workreap_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so workreap_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so workreap_categorized_blog should return false.
			return false;
		}
	}
}

/**
 * Returns Prev/Next Links
 *
 * @return html
 */
if ( ! function_exists( 'workreap_the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function workreap_the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<div class="nav-links">
			<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'workreap' ) ); ?></div>
			<?php endif; ?>
			<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'workreap' ) ); ?></div>
			<?php endif; ?>
		</div>
	</nav>
	<?php
}
endif;