<?php

if ( ! defined( 'THEME_VERSION' ) ) {
	define( 'THEME_VERSION', '3.0.4' );
}

require_once get_template_directory() . '/inc/tgmp/init.php';

$theme_check = workreap_new_theme_active();

if(!$theme_check){

	if ( ! function_exists( 'workreap_theme_setup' ) ){
		function workreap_theme_setup() {
			global $pagenow;

			load_theme_textdomain('workreap' , get_template_directory() . '/languages');

			// Add default posts and comments RSS feed links to head.
			add_theme_support('automatic-feed-links');

			//Let WordPress manage the document title
			add_theme_support('title-tag');

			//Enable support for Post Thumbnails on posts and pages.
			add_theme_support('post-thumbnails');

			// This theme uses wp_nav_menu() in one location.
			register_nav_menus(array (
				'primary-menu'   	=> esc_html__('Header Main Menu' , 'workreap'),
				'freelancers'   	=> esc_html__('Freelancers Menu' , 'workreap') ,
				'employers'   		=> esc_html__('Employers Menu' , 'workreap') ,
				'footer-menu' 		=> esc_html__('Footer Menu' , 'workreap') ,
				'pages-menu' 		=> esc_html__('Side bar pages Menu' , 'workreap') ,
				'categories-menu' 	=> esc_html__('Categories Menu' , 'workreap') ,
			));

			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );

			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );

			// Add support for editor styles.
			add_theme_support( 'editor-styles' );

			// Add custom editor font sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => esc_html__( 'Small', 'workreap' ),
						'size'      => 14,
						'slug'      => 'small',
					),
					array(
						'name'      => esc_html__( 'Normal', 'workreap' ),
						'size'      => 16,
						'slug'      => 'normal',
					),
					array(
						'name'      => esc_html__( 'Large', 'workreap' ),
						'size'      => 36,
						'slug'      => 'large',
					),
					array(
						'name'      => esc_html__( 'Extra Large', 'workreap' ),
						'size'      => 48,
						'slug'      => 'extra-large',
					),
				)
			);

			//theme default color
			add_theme_support(
				'editor-color-palette', array(
				array(
					'name' => esc_html__( 'Theme Color', 'workreap' ),
					'slug' => 'strong-theme-color',
					'color' => '#ff5851',
				),
				array(
					'name' => esc_html__( 'Theme Light text color', 'workreap' ),
					'slug' => 'light-gray',
					'color' => '#767676',
				),
				array(
					'name' => esc_html__( 'Theme Very Light text color', 'workreap' ),
					'slug' => 'very-light-gray',
					'color' => '#eee',
				),
				array(
					'name' => esc_html__( 'Theme Dark text color', 'workreap' ),
					'slug' => 'very-dark-gray',
					'color' => '#323232',
				),
			) );

			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support('html5' , array (
				'search-form' ,
				'comment-form' ,
				'comment-list' ,
				'gallery' ,
				'caption'
			));

			//Enable support for Post Thumbnails on posts and pages.
			add_theme_support('post-formats' , array (
				''
			));

			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );

			//Woocommerce
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );

			// Set up the WordPress core custom background feature.
			add_theme_support('custom-background' , apply_filters('workreap_custom_background_args' , array (
				'default-color' => 'ffffff' ,
				'default-image' => ''
			)));


			add_theme_support('custom-header' , array (
				'default-color' => '' ,
				'flex-width'    => true ,
				'flex-height'   => true ,
				'default-image' => ''
			));

			if (!get_option('workreap_theme_installation')) {
				update_option('workreap_theme_installation' , 'installed');
				wp_redirect(admin_url('themes.php?page=install-required-plugins'));
			}

			add_filter('edit_user_profile' , 'workreap_edit_user_profile_edit' , 10 , 1);
			add_filter('show_user_profile' , 'workreap_edit_user_profile_edit' , 10 , 1);
			add_action('edit_user_profile_update' , 'workreap_personal_options_save');
			add_action('personal_options_update' , 'workreap_personal_options_save');
		}
		add_action( 'after_setup_theme', 'workreap_theme_setup' );
	}

	if(!function_exists('workreap_scripts')){
		function workreap_scripts() {
			$theme_version = wp_get_theme('workreap');
			wp_register_style('workreap-style', get_template_directory_uri().'/style.css', array(), $theme_version->get('Version'));
			wp_register_style('workreap-responsive', get_template_directory_uri().'/css/responsive.css', array(), $theme_version->get('Version'));
			wp_register_style('workreap-dbresponsive', get_template_directory_uri().'/css/dbresponsive.css', array(), $theme_version->get('Version'));
			wp_register_style('workreap-dashboard',  get_template_directory_uri().'/css/dashboard.css', array(), $theme_version->get('Version'));
			wp_enqueue_style('workreap-style');
			wp_enqueue_style('workreap-responsive');
			if (is_page_template('directory/dashboard.php')) {
				wp_enqueue_style('workreap-dashboard');
				wp_enqueue_style('workreap-dbresponsive');
			}
		}
		add_action('wp_enqueue_scripts', 'workreap_scripts', 88);
	}

}else{
	if ( ! function_exists( 'workreap_setup' ) ){
		function workreap_setup() {

			//Make theme available for translation
			load_theme_textdomain( 'workreap', get_template_directory() . '/languages' );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			//Let WordPress manage the document title.
			add_theme_support( 'title-tag' );

			//Enable support for Post Thumbnails on posts and pages.
			add_theme_support( 'post-thumbnails' );

			// This theme uses wp_nav_menu() in one location.
			register_nav_menus(
				array(
					'primary-menu' 	=> esc_html__( 'Primary menu', 'workreap' ),
					'footer-menu' 	=> esc_html__( 'Footer menu', 'workreap' ),
				)
			);

			//Switch default core markup for search form, comment form, and comments
			add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );

			//Enable support for Post Formats.
			add_theme_support('post-formats' , array ( '' ));

			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );

			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );

			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );

			// Add support for editor styles.
			add_theme_support( 'editor-styles' );
			// Add custom editor font sizes.

			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => esc_html__( 'Small', 'workreap' ),
						'size'      => 14,
						'slug'      => 'small',
					),
					array(
						'name'      => esc_html__( 'Normal', 'workreap' ),
						'size'      => 16,
						'slug'      => 'normal',
					),
					array(
						'name'      => esc_html__( 'Large', 'workreap' ),
						'size'      => 36,
						'slug'      => 'large',
					),
					array(
						'name'      => esc_html__( 'Extra Large', 'workreap' ),
						'size'      => 48,
						'slug'      => 'extra-large',
					),
				)
			);

			//theme default color
			add_theme_support(
				'editor-color-palette', array(
				array(
					'name' => esc_html__( 'Theme Color', 'workreap' ),
					'slug' => 'strong-theme-color',
					'color' => '#ff5851',
				),
				array(
					'name' => esc_html__( 'Theme Light text color', 'workreap' ),
					'slug' => 'light-gray',
					'color' => '#676767',
				),
				array(
					'name' => esc_html__( 'Theme Very Light text color', 'workreap' ),
					'slug' => 'very-light-gray',
					'color' => '#eee',
				),
				array(
					'name' => esc_html__( 'Theme Dark text color', 'workreap' ),
					'slug' => 'very-dark-gray',
					'color' => '#0A0F26',
				),
			) );

			// Add support for woocommerce
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}
		add_action( 'after_setup_theme', 'workreap_setup' );
	}

	//Content Width
	if(!function_exists('workreap_content_width')){
		function workreap_content_width() {
			$GLOBALS['content_width'] = apply_filters( 'workreap_content_width', 1296 );
		}
		add_action( 'after_setup_theme', 'workreap_content_width', 0 );
	}

	//Register widget area
	if(!function_exists('workreap_widgets_init')){
		function workreap_widgets_init() {

			register_sidebar(
				array(
					'name'          => esc_html__( 'Default sidebar', 'workreap' ),
					'id'            => 'workreap-sidebar',
					'description'   => esc_html__( 'Default archive sidebar', 'workreap' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s wr-widgetbox">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="wr-sidetitle"><h5>',
					'after_title'   => '</h5></div>',
				)
			);

			if( defined('WORKREAP_DIRECTORY') ){

				register_sidebar(
					array(
						'name'          => esc_html__( 'Footer sidebar 1', 'workreap' ),
						'id'            => 'workreap-sidebar-f1',
						'description'   => esc_html__( 'For footer first section', 'workreap' ),
						'before_widget' => '<div id="%1$s" class="%2$s wr-widgetbox">',
						'after_widget'  => '</div>',
						'before_title'  => '<div class="wr-sidetitle"><h5>',
						'after_title'   => '</h5></div>',
					)
				);
				register_sidebar(
					array(
						'name'          => esc_html__( 'Footer sidebar 2', 'workreap' ),
						'id'            => 'workreap-sidebar-f2',
						'description'   => esc_html__( 'For footer second column', 'workreap' ),
						'before_widget' => '<div id="%1$s" class="%2$s wr-widgetbox">',
						'after_widget'  => '</div>',
						'before_title'  => '<div class="wr-sidetitle"><h5>',
						'after_title'   => '</h5></div>',
					)
				);

				register_sidebar(
					array(
						'name'          => esc_html__( 'Footer sidebar 3', 'workreap' ),
						'id'            => 'workreap-sidebar-f3',
						'description'   => esc_html__( 'For footer third column', 'workreap' ),
						'before_widget' => '<div id="%1$s" class="%2$s wr-widgetbox">',
						'after_widget'  => '</div>',
						'before_title'  => '<div class="wr-sidetitle"><h5>',
						'after_title'   => '</h5></div>',
					)
				);
			}

		}
		add_action( 'widgets_init', 'workreap_widgets_init' );
	}

	//Enqueue scripts and styles
	if(!function_exists('workreap_scripts')){
		function workreap_scripts() {
			global $workreap_settings;
			$header_search		= !empty($workreap_settings['header_search']) ? $workreap_settings['header_search'] : false;
			$loading_duration	= !empty($workreap_settings['loading_duration']) ? $workreap_settings['loading_duration'] : 500;
			$theme_version 		= wp_get_theme('workreap');
			$workreap_custom_css	= '';

			//register css
			wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), $theme_version->get('Version'));
			wp_register_style( 'nouislider', get_template_directory_uri() . '/css/nouislider.min.css', array(), $theme_version->get('Version'),'all');
			wp_enqueue_style( 'workreap-icons', get_template_directory_uri(). '/css/workreap-icons.css', array(),$theme_version->get('Version'), 'all' );
			wp_enqueue_style( 'fontawesome', get_template_directory_uri(). '/css/fontawesome/all.min.css', array(), $theme_version->get('Version'), 'all' );
			wp_enqueue_style( 'workreap-style', get_stylesheet_uri(), array(), $theme_version->get('Version') );
			wp_enqueue_style( 'workreap-main', get_template_directory_uri(). '/css/main.css', array('workreap-style'), $theme_version->get('Version'), 'all' );
			wp_enqueue_style( 'select2', get_template_directory_uri() . '/css/select2.min.css',  array(), $theme_version->get('Version'));
			wp_enqueue_style( 'workreap-responsive', get_template_directory_uri() . '/css/responsive-style.css',  array(), $theme_version->get('Version'));

			//register js
			wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/vendor/bootstrap.min.js', array( 'jquery' ), $theme_version->get('Version'), true );
			wp_enqueue_script( 'nouislider', get_template_directory_uri() . '/js/vendor/nouislider.min.js', array( 'jquery' ), $theme_version->get('Version'), true );
			wp_enqueue_script( 'select2', get_template_directory_uri() . 'js/vendor/select2.min.js', array(), $theme_version->get('Version'), true );
			wp_enqueue_script( 'workreap-callbacks', get_template_directory_uri() . '/js/callbacks.js', array('jquery'), $theme_version->get('Version'), true );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			if( is_page_template( 'templates/search-freelancer.php' ) ){
				wp_enqueue_style('nouislider');
				wp_enqueue_script('nouislider');
			}
			wp_localize_script('workreap-callbacks', 'workreap_vars', array(
				'loading_duration'			=> $loading_duration,
				'ajaxurl'					=> admin_url('admin-ajax.php'),
			));
		}
		add_action( 'wp_enqueue_scripts', 'workreap_scripts' );
	}

	//Enqueue Google Font
	if (!function_exists('workreap_enqueue_google_fonts')) {
		function workreap_enqueue_google_fonts() {
			$protocol = is_ssl() ? 'https' : 'http';

			//Default theme font famlies
			$font_families	= array();
			$font_families[] = 'Inter:400,500,600,700,900';

			$query_args = array (
				'family' => implode('%7C' , $font_families) ,
				'subset' => 'latin,latin-ext' ,
			);

			$theme_fonts = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');

			wp_enqueue_style('workreap-fonts-enqueue' , esc_url_raw($theme_fonts), array () , null);
		}
		add_action('wp_enqueue_scripts' , 'workreap_enqueue_google_fonts');
	}

	//Include files
	require get_template_directory() . '/inc/template-tags.php';
	require get_template_directory() . '/inc/template-functions.php';
	if ( defined( 'JETPACK__VERSION' ) ) {
		require get_template_directory() . '/inc/jetpack.php';
	}

	if(!class_exists('OCDI_Plugin')){
		require_once get_template_directory() . '/inc/merlin/vendor/autoload.php';
		require_once get_template_directory() . '/inc/merlin/class-merlin.php';
		require_once get_template_directory() . '/inc/merlin/merlin-config.php';
	}
}