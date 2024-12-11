<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package workreap
 */
global $wp_query;
get_header();

$theme_check = workreap_new_theme_active();

if(!$theme_check){
	?>
    <div class="container">
        <div class="row">
            <div class="workreap-search-page wt-haslayout">
				<?php get_template_part('template-parts/content' , 'search'); ?>
            </div>
        </div>
    </div>
	<?php
}else{
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
					<?php if ( have_posts() ) {?>
                        <header class="page-header">
                            <h1 class="page-title">
								<?php printf( esc_html__( 'Search Results for: %s', 'workreap' ), '<span>' . get_search_query() . '</span>' );?>
                            </h1>
                        </header>
						<?php
						while ( have_posts() ){
							the_post();
							get_template_part( 'template-parts/content', 'search' );

						}

						if ($wp_query->found_posts > $show_posts) {
							if (function_exists('workreap_paginate')) {
								workreap_paginate($wp_query, 'col-12');
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
