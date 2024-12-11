<?php
/**
 * Workreap header template
 *
 * @link https://themeforest.net/user/amentotech/portfolio
 *
 * @package workreap
 */
global $workreap_settings;
$site_loader      = ! empty( $workreap_settings['site_loader'] ) ? $workreap_settings['site_loader'] : '';
$loader_type      = ! empty( $workreap_settings['loader_type'] ) ? $workreap_settings['loader_type'] : '';
$loader_image     = ! empty( $workreap_settings['loader_image']['url'] ) ? $workreap_settings['loader_image']['url'] : '';
$preloader        = '';

$logo             = isset($workreap_settings['main_logo']['url']) && !empty($workreap_settings['main_logo']['url']) ? $workreap_settings['main_logo']['url'] : '';
$page_id          = get_the_ID();
$header_style     = ! empty( $workreap_settings['wr_header_style'] ) ? $workreap_settings['wr_header_style'] : 'style-one';
$header_container = ! empty( $workreap_settings['wr_header_container'] ) ? $workreap_settings['wr_header_container'] : 'container-fluid';
$topbar           = isset( $workreap_settings['workreap_header_top_bar'] ) && ! empty( $workreap_settings['workreap_header_top_bar'] ) ? $workreap_settings['workreap_header_top_bar'] : false;
$topbar_text      = isset( $workreap_settings['workreap_header_top_bar_text'] ) && ! empty( $workreap_settings['workreap_header_top_bar_text'] ) ? $workreap_settings['workreap_header_top_bar_text'] : '';
$topbar_btn_text  = isset( $workreap_settings['workreap_header_top_bar_btn_text'] ) && ! empty( $workreap_settings['workreap_header_top_bar_btn_text'] ) ? $workreap_settings['workreap_header_top_bar_btn_text'] : '';
$topbar_btn_link  = isset( $workreap_settings['workreap_header_top_bar_btn_link'] ) && ! empty( $workreap_settings['workreap_header_top_bar_btn_link'] ) ? $workreap_settings['workreap_header_top_bar_btn_link'] : '';

$header_class     = 'wr-header';

if(isset($post->ID)){
	//Meta Fields
	$header_type_meta        = get_post_meta( $post->ID, 'wr_header_style', true );
	$header_width_meta       = get_post_meta( $post->ID, 'wr_header_container', true );
	$header_transparent_meta = get_post_meta( $post->ID, 'wr_header_transparent', true );
	$header_white_meta       = get_post_meta( $post->ID, 'wr_header_white', true );
	$hide_topbar_meta        = get_post_meta( $post->ID, 'wr_header_topbar_hide', true );

	$header_style     = isset( $header_type_meta ) && !empty( $header_type_meta ) ? $header_type_meta : $header_style;
	$header_container = isset( $header_width_meta ) && !empty( $header_width_meta ) ? $header_width_meta : $header_container;
	$topbar           = isset( $hide_topbar_meta ) && !empty( $hide_topbar_meta ) ? false : $topbar;
	$header_class     .= isset( $header_transparent_meta ) && !empty( $header_transparent_meta ) ? ' wr-header-transparent' : '';

	if ( isset( $header_white_meta ) && ! empty( $header_white_meta ) ) {
		$logo         = isset($workreap_settings['transparent_logo']['url']) && !empty($workreap_settings['transparent_logo']['url']) ? $workreap_settings['transparent_logo']['url'] : '';
		$header_class .= ' wr-header-menu-white';
	}
}

$header_class .= ' wr-header-style-' . $header_style;
$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

if ( ! empty( $site_loader ) ) {
	if ( ! empty( $loader_type ) && $loader_type == 'default' ) { ?>
        <div class="preloader-outer">
            <div class="sv-preloader_holder">
                <div class="sv-loader">
                    <img class="fa-spin" src="<?php echo esc_url( get_template_directory_uri() . '/images/loader.svg' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'workreap' ); ?>"/>
                </div>
            </div>
        </div>
		<?php
	} elseif ( ! empty( $loader_image ) ) { ?>
        <div class="preloader-outer">
            <div class="sv-preloader_holder">
                <div class="sv-loader">
                    <img class="fa-spin" src="<?php echo esc_url( $loader_image ); ?>" alt="<?php esc_attr_e( 'Loading...', 'workreap' ); ?>"/>
                </div>
            </div>
        </div>
		<?php
	}
}

if (class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
	$did_header_location = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->do_location( 'header' );
	if($did_header_location){
		return;
	}
}

?>
<header class="<?php echo esc_attr( $header_class ); ?>">
	<?php if ( $topbar && ! empty( $topbar_text ) ) { ?>
        <div class="wr-header-topbar">
            <p class="wr-header-topbar-text"><?php echo esc_html( $topbar_text ); ?>
				<?php if ( ! empty( $topbar_btn_text ) && ! empty( $topbar_btn_link ) ) { ?>
                    <a href="<?php echo esc_url( $topbar_btn_link ); ?>" target="_blank"
                       class="wr-header-topbar-btn"><?php echo esc_html( $topbar_btn_text ); ?></a>
				<?php } ?>
            </p>
        </div>
	<?php } ?>
    <div class="wr-themenav_wrapper">
        <div class="<?php echo esc_attr( $header_container ); ?>">
            <nav class="wr-themenavwrap navbar-expand-xl">
                <strong class="wr-logo-wrapper">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wr-logo">
			            <?php if(!empty($logo)){ ?>
                            <img class="wr-theme-logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php esc_attr( $blogname ); ?>">
			            <?php }else{ ?>
                            <svg class="wr-theme-logo-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 145 32">
                                <path fill="#F1641D" class="wr-logo-icon" d="M23.634 1.935A30.868 30.868 0 0 1 27 16c0 5.064-1.214 9.844-3.367 14.066C28.618 27.355 32 22.072 32 16S28.618 4.645 23.634 1.935ZM16.44 1.6C15.714.573 14.482.021 13.245.235 5.722 1.542.001 8.103.001 16c0 7.897 5.721 14.458 13.245 15.764 1.236.214 2.47-.338 3.193-1.363A24.886 24.886 0 0 0 21.001 16c0-5.362-1.688-10.33-4.562-14.4Z"/>
                                <path fill="#1E1E1E" class="wr-logo-text" d="M53.188 13.354 50.146 24h-2.531L43.584 8.969h2.802l2.573 11.187h.156L52.073 8.97h2.386l3 11.187h.145L60.178 8.97h2.782L58.927 24h-2.52l-3.063-10.646h-.156Zm16 10.875c-3.417 0-5.48-2.229-5.48-5.927 0-3.677 2.094-5.927 5.48-5.927 3.396 0 5.479 2.24 5.479 5.927 0 3.698-2.073 5.927-5.48 5.927Zm0-2.166c1.802 0 2.823-1.375 2.823-3.76 0-2.376-1.031-3.761-2.823-3.761-1.802 0-2.834 1.385-2.834 3.76 0 2.386 1.032 3.76 2.834 3.76ZM77.27 24V12.604h2.49v1.771h.177c.312-1.125 1.541-1.958 3.01-1.958.375 0 .834.041 1.073.114v2.396c-.198-.073-.885-.146-1.323-.146-1.677 0-2.843 1.042-2.843 2.636V24H77.27Zm11.74-6.75 4.208-4.646h3l-4.542 4.886L96.532 24h-3.125l-3.657-4.885-.916.916V24H86.25V8.198h2.584v9.052h.177ZM98.52 24V12.604h2.49v1.771h.177c.312-1.125 1.541-1.958 3.01-1.958.375 0 .834.041 1.073.114v2.396c-.198-.073-.885-.146-1.323-.146-1.677 0-2.844 1.042-2.844 2.636V24h-2.583Zm13.365-9.573c-1.542 0-2.625 1.104-2.74 2.77h5.365c-.052-1.687-1.073-2.77-2.625-2.77Zm2.646 6.438h2.447c-.489 2.073-2.375 3.364-5.062 3.364-3.354 0-5.375-2.219-5.375-5.885 0-3.667 2.052-5.969 5.354-5.969 3.25 0 5.198 2.156 5.198 5.75v.833h-7.958v.136c.062 1.896 1.156 3.083 2.854 3.083 1.281 0 2.167-.469 2.542-1.312Zm9.052 1.291c1.562 0 2.729-1.01 2.729-2.333v-.906l-2.573.166c-1.448.094-2.125.615-2.125 1.542 0 .958.823 1.531 1.969 1.531Zm-.761 2.032c-2.198 0-3.771-1.334-3.771-3.417 0-2.063 1.552-3.25 4.323-3.417l2.938-.177v-.958c0-1.115-.75-1.74-2.146-1.74-1.188 0-2 .417-2.24 1.188h-2.437c.208-2.01 2.094-3.292 4.802-3.292 2.927 0 4.573 1.427 4.573 3.844V24h-2.49v-1.563h-.177c-.635 1.105-1.885 1.75-3.375 1.75Zm15.365-11.771c2.885 0 4.698 2.229 4.698 5.885 0 3.636-1.792 5.886-4.667 5.886-1.635 0-2.896-.709-3.479-1.917h-.177v5.52h-2.584V12.605h2.49v1.886h.177c.656-1.292 1.979-2.073 3.542-2.073Zm-.813 9.573c1.792 0 2.854-1.386 2.854-3.688 0-2.302-1.072-3.687-2.843-3.687-1.761 0-2.865 1.416-2.875 3.687.01 2.281 1.104 3.688 2.864 3.688Z"/>
                            </svg>
			            <?php } ?>
                    </a>
                </strong>
		        <?php
                if ( has_nav_menu( 'primary-menu' ) ) { ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'workreap' ); ?>">
                        <span class="wr-icon-menu"></span>
                    </button>
			        <?php
                    wp_nav_menu(
				        array(
					        'theme_location'  => 'primary-menu',
					        'menu_id'         => 'primary-menu',
					        'container_class' => 'collapse navbar-collapse wr-themenav',
					        'container_id'    => 'navbarNav',
					        'items_wrap'      => '<div class="menu-header-menu-container"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
				        )
			        );
                }else{ ?>
			        <p class="wr-menu-notice"><?php echo esc_html__('Primary menu not set','workreap') ?></p>
		        <?php } ?>
            </nav>
        </div>
    </div>
</header>