<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */

$theme_check = workreap_new_theme_active();

if(!$theme_check){
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

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$section_width  = 'col-xs-12 col-sm-12 col-md-12 col-lg-7 col-xl-8';
	} else{
		$section_width  = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12';
	}
	?>
    <div class="<?php echo esc_attr( $section_width );?> page-section <?php echo sanitize_html_class($content_class); ?>">
        <div class="wt-haslayout search-page-header">
            <div class="border-left wt-haslayout">
                <h3><?php printf(esc_html__('Search Results for: %s' , 'workreap') , '<span>' . get_search_query() . '</span>'); ?></h3>
            </div>
            <div class="need-help wt-haslayout">
                <h4><?php  esc_html_e('Need a new search?','workreap');?> </h4>
                <p><?php  esc_html_e('If you didn\'t find what you were looking for, try a new search!','workreap');?></p>
            </div>
            <div class="wt-blog-search wt-haslayout">
				<?php get_search_form();?>
            </div>
        </div>
		<?php if ( have_posts() && strlen( trim(get_search_query()) ) != 0 ) {?>
			<?php get_template_part( 'template-parts/archive-templates/search', 'list' );} ?>
    </div>
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) {?>
        <aside id="wt-sidebar" class="col-xs-12 col-sm-4 col-md-12 col-lg-5 col-xl-4 <?php echo sanitize_html_class($aside_class); ?>">
            <div class="wt-sidebar">
				<?php dynamic_sidebar('sidebar-1'); ?>
            </div>
        </aside>
	<?php }
}else{
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('wr-theme-box'); ?>>
	<?php workreap_post_thumbnail(); ?>
    <header class="workreap-entry-header">
		<?php echo get_the_term_list($post->ID, 'category', '<ul class="workreap-cat-links"><li>', '</li><li>', '</li></ul>'); ?>
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
				<span class="workreap-byline">
                    <i class="wr-icon-message-square"></i>
					<span><?php comments_number(esc_html__('0 Comments' , 'workreap') , esc_html__('1 Comment' , 'workreap') , esc_html__('% Comments' , 'workreap')); ?></span>
                </span>
				<?php
				workreap_posted_on();
				?>
            </div>
		<?php endif; ?>
    </header>
    <div class="workreap-entry-summary">
		<?php the_excerpt(); ?>
    </div>
</article>
<?php
}