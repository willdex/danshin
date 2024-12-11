<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */
global $wp_query;
get_header();
$theme_check = workreap_new_theme_active();

if(!$theme_check){ ?>
    <div class="container">
        <div class="row">
            <div class="workreap-home-page haslayout">
				<?php get_template_part( 'template-parts/content', 'page' ); ?>
            </div>
        </div>
    </div>
<?php }else{
	$show_posts    	= get_option('posts_per_page');
	$section_col = 'col-xl-8 col-xxl-9';
	if ( !is_active_sidebar( 'workreap-sidebar' ) ) {
		$section_col = 'col-xl-12';
	}
	?>
    <div class="wr-main-section">
        <div class="container">
            <div class="row wr-blogs-bottom">
                <div class="<?php echo esc_attr($section_col);?>">
					<?php
					if ( have_posts() ) {
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'archive' );
						endwhile;
						if ($wp_query->found_posts > $show_posts) {
							if (function_exists('workreap_pagination')) {
								echo workreap_pagination('' , $show_posts);
							}
						}
					}else{
						get_template_part( 'template-parts/content', 'none' );
					}
					?>
                </div>
				<?php if ( is_active_sidebar( 'workreap-sidebar' ) ) {?>
                    <div class="col-xl-4 col-xxl-3">
                        <aside>
                            <div class="wr-asidewrapper">
                                <a href="javascript:void(0)" class="wr-dbmenu"><i class="wr-icon-layout"></i></a>
                                <div class="wr-aside-menu">
									<?php get_sidebar();?>
                                </div>
                            </div>
                        </aside>
                    </div>
				<?php }?>
            </div>
        </div>
    </div>
	<?php
}
get_footer();