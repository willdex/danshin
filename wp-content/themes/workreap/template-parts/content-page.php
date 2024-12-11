<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */

$theme_check = workreap_new_theme_active();

if(!$theme_check){

	$archive_enable_sidebar    	 = 'enable';
	$archive_sidebar_position    = 'right';
	$blog_view					 = 'list';

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$section_width = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
	} else{
		$section_width = 'col-xs-12 col-sm-12 col-md-7 col-lg-8';
	}

	if( function_exists('fw_get_db_settings_option')  ){
		$sidebar_type	= fw_get_db_settings_option('archive_sidebar', $default_value = null);
	}

	if (isset($sidebar_type) && $sidebar_type === 'right') {
		$aside_class   = 'pull-right';
		$content_class = 'pull-left';
	} else {
		$aside_class   = 'pull-left';
		$content_class = 'pull-right';
	}

	?>
    <div class="<?php echo esc_attr( $section_width );?> page-section <?php echo sanitize_html_class($content_class); ?>">
		<?php
		if ( have_posts() ) {
			get_template_part( 'template-parts/archive-templates/content', 'list' );
		} else{
			get_template_part( 'template-parts/content', 'none' );
		}
		?>
    </div>
	<?php if ( is_active_sidebar( 'sidebar-1' ) && $archive_enable_sidebar === 'enable' ) {?>
        <aside id="wt-sidebar" class="col-xs-12 col-sm-12 col-md-5 col-lg-4 <?php echo sanitize_html_class($aside_class); ?>">
            <div class="wt-sidebar">
				<?php dynamic_sidebar('sidebar-1'); ?>
            </div>
        </aside>
	<?php }
}else{ ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('wr-theme-box'); ?>>
    <header class="entry-header wr-checkoutheader">
		<?php the_title( '<h4 class="workreap-entry-title">', '</h4>' ); ?>
    </header>
	<?php workreap_post_thumbnail(); ?>

    <div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="workreap-page-links">' . esc_html__( 'Pages:', 'workreap' ),
				'after' => '</div>',
			)
		);
		?>
    </div>
</article>
<?php
}
				