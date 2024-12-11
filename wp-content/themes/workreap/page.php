<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */

get_header();

$theme_check = workreap_new_theme_active();

if(!$theme_check){
	$sidebar_type  = 'full';
	$sd_sidebar	   = '';
	$section_width = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
	if (function_exists('workreap_sidebars_get_current_position')) {
		$current_position = workreap_sidebars_get_current_position($post->ID);
		if ( !empty($current_position['sd_layout']) && $current_position['sd_layout'] !== true && $current_position['sd_layout'] !== 'default' ) {
			$sidebar_type  		= !empty($current_position['sd_layout']) ? $current_position['sd_layout'] : 'full';
			$sd_sidebar	   		= !empty($current_position['sd_sidebar']) ? $current_position['sd_sidebar'] : '';

			if(!empty($sd_sidebar)){
				$section_width 		= 'col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8';
			}
		}else{
			if (function_exists('fw_get_db_settings_option')) {
				$sd_layout_pages    = fw_get_db_settings_option('sd_layout_pages');
				$sd_sidebar_pages   = fw_get_db_settings_option('sd_sidebar_pages');
				$sidebar_type  		= !empty($sd_layout_pages) ? $sd_layout_pages : 'full';
				$sd_sidebar	   		= !empty($sd_sidebar_pages) ? $sd_sidebar_pages : '';

				if(!empty($sd_sidebar)){
					$section_width 		= 'col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8';
				}
			}
		}
	}

	$height = 466;
	$width  = 1170;

	if (isset($sidebar_type) && ( $sidebar_type == 'full' )) {
		while (have_posts()) : the_post();
			global $post;
			?>
            <div class="container">
                <div class="wt-haslayout wt-haslayout page-data wt-boxed-section">
					<?php
					do_action('workreap_prepare_section_wrapper_before');
					$thumbnail = workreap_prepare_thumbnail($post->ID , $width , $height);
					if( $thumbnail ){?>
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" >
						<?php
					}

					the_content();

					wp_link_pages( array(
						'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
						'after'       => '</ul></nav></div>',
					) );

					// If comments are open or we have at least one comment, load up the comment template.
					if (comments_open() || get_comments_number()) :
						comments_template();
					endif;
					do_action('workreap_prepare_section_wrapper_after');
					?>
                </div>
            </div>
		<?php
		endwhile;
	} else {
		if (isset($sidebar_type) && $sidebar_type == 'right') {
			$aside_class   = 'order-last';
			$content_class = 'pull-left';
		} else {
			$aside_class   = 'pull-left';
			$content_class = 'wt-order-first';
		}
		?>
        <div class="container">
            <div class="wt-haslayout page-data wt-boxed-section">
				<?php do_action('workreap_prepare_section_wrapper_before'); ?>
                <div class="row">
					<?php
					if (function_exists('workreap_sidebars_get_current_position')) {
						if (isset($sidebar_type) && $sidebar_type !== 'full' && !empty($sd_sidebar)) {?>
                            <aside class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 sidebar-section <?php echo sanitize_html_class($aside_class); ?>" id="wt-sidebar">
                                <div class="wt-sidebar page-dynamic-sidebar">
                                    <div class="mmobile-floating-apply">
                                        <span><?php esc_html_e('Open Sidebar', 'workreap'); ?></span>
                                        <i class="fa fa-filter"></i>
                                    </div>
                                    <div class="floating-mobile-filter">
                                        <div class="wt-filter-scroll wt-collapse-filter">
                                            <a class="wt-mobile-close" href="#" onclick="event_preventDefault(event);"><i class="lnr lnr-cross"></i></a>
											<?php dynamic_sidebar( $sd_sidebar );?>
                                        </div>
                                    </div>
                                </div>
                            </aside>
						<?php }}?>
                    <div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>  page-section twocolumn-page-section">
						<?php
						while (have_posts()) : the_post();
							global $post;
							$thumbnail = workreap_prepare_thumbnail($post->ID , $width , $height);
							if( $thumbnail ){?>
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" >
							<?php }

							the_content();
							wp_link_pages( array(
								'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
								'after'       => '</ul></nav></div>',
							) );

							// If comments are open or we have at least one comment, load up the comment template.
							if (comments_open() || get_comments_number()) :
								comments_template();
							endif;
						endwhile;
						?>

                    </div>

                </div>
				<?php do_action('workreap_prepare_section_wrapper_after'); ?>
            </div>
        </div>
	<?php }
}else{
	$section_col = 'col-xl-8';
	if ( !is_active_sidebar( 'workreap-sidebar' ) ) {
		$section_col = 'col-xl-12';
	}
	?>
    <div class="wr-main-section">
        <div class="container">
            <div class="row wr-blogs-bottom">
                <div class="col-xl-12">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
                </div>
            </div>
        </div>
    </div>
	<?php
}
get_footer();
