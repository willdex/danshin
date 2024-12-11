<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package workreap
 */
global $post, $workreap_settings;
get_header();
$theme_check = workreap_new_theme_active();
if(!$theme_check){
	$default_title   = esc_html__('The page you are looking for, does not exist', 'workreap');
	if ( ! function_exists('fw_get_db_settings_option') ) {
		$img_404 = '';
		$desc    = '';
	} else {
		$img_404 = fw_get_db_settings_option('404_banner');
		$title 	 = fw_get_db_settings_option('404_title');
		$desc 	 = fw_get_db_settings_option('404_description');
	}
	$title = !empty( $title ) ?  $title : $default_title;
	?>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
                <div class="wt-404errorpage">
					<?php if( !empty( $img_404['url'] ) ) { ?>
                        <figure class="wt-404errorimg">
                            <img src="<?php echo esc_url( $img_404['url']  );?>" alt="<?php esc_attr_e('404 Page','workreap');?>">
                        </figure>
					<?php } ?>
                    <div class="wt-404errorcontent">
                        <div class="wt-title">
                            <h3><?php echo esc_html( $title);?></h3>
                        </div>
						<?php if( !empty( $desc ) ) { ?>
                            <div class="wt-description 404page-desc">
                                <p><?php echo esc_html( $desc );?>&nbsp;<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Homepage','workreap');?></a></p>
                            </div>
						<?php } ?>
						<?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}else{
	$title_404			= !empty($workreap_settings['title_404']) ? $workreap_settings['title_404'] : esc_html__('You running into nowhere','workreap');
	$subtitle_404		= !empty($workreap_settings['subtitle_404']) ? $workreap_settings['subtitle_404'] : esc_html__('Uhoo! Page not found','workreap');
	$description_404	= !empty($workreap_settings['description_404']) ? $workreap_settings['description_404'] : esc_html__( 'It looks like nothing was found on this path. Maybe you should try with another link or make a quick new keyword search below?', 'workreap' );;
	$image_404			= !empty($workreap_settings['image_404']['url']) ? $workreap_settings['image_404']['url'] : get_template_directory_uri().'/images/404.svg';
	?>
    <div class="wr-main-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 m-auto">
                    <div class="wr-errorpage">
						<?php if(!empty($image_404)){?>
                            <figure>
                                <img src="<?php echo esc_url($image_404);?>" alt="<?php esc_attr_e('Page not found','workreap')?> image">
                            </figure>
						<?php } ?>
						<?php if( !empty($title_404) || !empty($subtitle_404) || !empty($description_404) ){?>
                            <div class="wr-notfound-title">
								<?php if( !empty($title_404) ){?>
                                    <h4><?php echo esc_html($title_404);?></h4>
								<?php } ?>
								<?php if( !empty($subtitle_404) ){?>
                                    <h2><?php echo esc_html($subtitle_404);?></h2>
								<?php } ?>
								<?php if( !empty($description_404) ){?>
                                    <p><?php echo esc_html($description_404);?></p>
								<?php } ?>
                            </div>
						<?php } ?>
						<?php get_search_form();?>
                        <div class="wr-description">
                            <p><?php echo sprintf( __( "You can also start from scratch. Go to %sHomepage%s instead", 'workreap' ), '<a href="'.esc_url(home_url('/')).'">', '</a>' );?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}
get_footer();
