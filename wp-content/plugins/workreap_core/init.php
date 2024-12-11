<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0
 * @package           Workreap Core
 *
 * @Workreap Core
 * Plugin Name:       Workreap Core
 * Plugin URI:        https://themeforest.net/user/amentotech/portfolio
 * Description:       This plugin have the core functionality for Workreap WordPress Theme
 * Version:           3.0.4
 * Author:            Amentotech
 * Author URI:        https://themeforest.net/user/amentotech
 * Text Domain:       workreap_core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define plugin basename
 */

define( 'Workreap_Basename', plugin_basename(__FILE__));
define( 'WORKREAPPLUGINPATH', plugin_dir_path( __FILE__ ));
define( 'WorkreapCoreURI', plugin_dir_url( __FILE__ ));

if( !function_exists( 'workreap_core_load_last' ) ) {
	function workreap_core_load_last() {
		$plugin_path   			= preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
		$workreap_plugin 		= plugin_basename(trim($plugin_path));
		$active_plugins 		= get_option('active_plugins');
		$workreap_plugin_key 	= array_search($workreap_plugin, $active_plugins);
		array_splice($active_plugins, $workreap_plugin_key, 1);
		array_push($active_plugins, $workreap_plugin);
		update_option('active_plugins', $active_plugins);
	}

	add_action("activated_plugin", "workreap_core_load_last");
}


/**
 * Get template from plugin or theme.
 *
 * @param string $file  Templat`e file name.
 * @param array  $param Params to add to template.
 *
 * @return string
 */
function workreap_template_exsits( $file, $param = array() ) {
	extract( $param );
	if ( is_dir( get_stylesheet_directory() . '/extend/' ) ) {
		if ( file_exists( get_stylesheet_directory() . '/extend/' . $file . '.php' ) ) {
			$template_load = get_stylesheet_directory() . '/extend/' . $file . '.php';
		} else {
			$template_load = WorkreapGlobalSettings::get_plugin_path() . '/' . $file . '.php';
		}
	} else {
		$template_load = WorkreapGlobalSettings::get_plugin_path() . '/' . $file . '.php';
	}

	return $template_load;
}

/**
 * @override parent theme files
 * @return string
 */
function workreap_override_templates($file) {
	if ( file_exists( get_stylesheet_directory() . $file ) ) {
		$template_load = get_stylesheet_directory() . $file;
	} else if ( file_exists( get_template_directory() . $file ) ) {
		$template_load = get_template_directory() . $file;
	} else {
		$template_load = plugin_dir_path( __FILE__ ) . $file;
	}
	return $template_load;
}

if(defined('WORKREAP_DIRECTORY_URI')){
	if ( !function_exists( 'deactivate_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugins_to_deactivate = array(
		'workreap/init.php',
		'workreap-hourly-addon/workreap-hourly-addon.php',
	);
	foreach ( $plugins_to_deactivate as $plugin ) {
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
		}
	}
	return;
}

/**
 * Plugin configuration file,
 * It include getter & setter for global settings
 */
require plugin_dir_path( __FILE__ ) . 'config.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-system.php';
require plugin_dir_path( __FILE__ ) . 'includes/core-functions.php';

include workreap_template_exsits( 'chat/class-chat-system' );
include workreap_template_exsits( 'hooks/hooks' );
include workreap_template_exsits( 'helpers/EmailHelper' );
include workreap_template_exsits( 'shortcodes/class-authentication' );
include workreap_template_exsits( 'libraries/mailchimp/class-mailchimp' );

require plugin_dir_path( __FILE__ ) . 'widgets/config.php';
require plugin_dir_path( __FILE__ ) . 'elementor/base.php';
require plugin_dir_path( __FILE__ ) . 'elementor/config.php';
require plugin_dir_path( __FILE__ ) . 'libraries/mailchimp/class-mailchimp-oath.php';
require plugin_dir_path( __FILE__ ) . 'helpers/register.php';
require plugin_dir_path( __FILE__ ) . 'import-users/class-readcsv.php';
require plugin_dir_path( __FILE__ ) . 'admin/settings/settings.php';
include workreap_template_exsits( 'import-users/class-import-user' );
require plugin_dir_path( __FILE__ ) . 'social-connect/class-facebook.php';
require plugin_dir_path( __FILE__ ) . 'social-connect/class-linkedin.php';
require plugin_dir_path( __FILE__ ) . 'libraries/recaptchalib/recaptchalib.php';


//require_once ( plugin_dir_path( __FILE__ ) . '/framework-customizations/includes/option-types.php'); 
require_once ( 	plugin_dir_path( __FILE__ ) . 'includes/sidebars.php'); //Theme sidebars
require_once ( 	plugin_dir_path( __FILE__ ) . 'one-signal/init.php');
require_once ( 	plugin_dir_path( __FILE__ ) . 'includes/hooks.php'); //Hooks
require_once (  plugin_dir_path( __FILE__ ) . 'directory/front-end/hooks.php');
require_once (  plugin_dir_path( __FILE__ ) . 'directory/front-end/functions.php');
require_once (  plugin_dir_path( __FILE__ ) . 'directory/front-end/woo-hooks.php');
if (function_exists('workreap_override_templates')) {
	require_once workreap_override_templates( 'includes/scripts.php' );
	require_once workreap_override_templates( 'includes/class-headers.php' );
    require_once workreap_override_templates( 'includes/class-footers.php' );
    require_once workreap_override_templates( 'includes/class-titlebars.php' );
    require_once workreap_override_templates( 'includes/class-notifications.php' );
	require_once workreap_override_templates( 'directory/front-end/class-dashboard-menu.php' );
	require_once workreap_override_templates( 'includes/typo.php' );
	require_once workreap_override_templates( 'includes/constants.php' );
}
require_once ( 	plugin_dir_path( __FILE__ ) . 'includes/functions.php'); //Theme functionality
require_once (  plugin_dir_path( __FILE__ ) . 'directory/back-end/dashboard.php');
require_once (  plugin_dir_path( __FILE__ ) . 'directory/back-end/hooks.php');
require_once (  plugin_dir_path( __FILE__ ) . 'directory/back-end/functions.php');
require_once (  plugin_dir_path( __FILE__ ) . 'directory/front-end/ajax-hooks.php');
require_once (  plugin_dir_path( __FILE__ ) . 'directory/front-end/filepermission/class-file-permission.php');
require_once (  plugin_dir_path( __FILE__ ) . 'directory/front-end/term_walkers.php'); //Term walkers
require_once ( 	plugin_dir_path( __FILE__ ) . 'includes/google_fonts.php');
require_once ( 	plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce.php');
require_once ( 	plugin_dir_path( __FILE__ ) . 'includes/jetpack.php');
require_once ( 	plugin_dir_path( __FILE__ )	. 'includes/template-tags.php');
require_once ( 	plugin_dir_path( __FILE__ ) . 'includes/redius-search/location_check.php');


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if( !function_exists( 'run_Workreap' ) ) {
	function run_Workreap() {
		$plugin = new Workreap_Core();
		$plugin->run();
	
	}
	run_Workreap();
}

/**
 * @init            Save rewrite slugs
 * @package         Rewrite Slug
 * @subpackage      Rewrite slugs
 * @since           1.0
 * @desc            This Function Will Produce All Tabs View.
 */
if (!function_exists('workreap_set_custom_rewrite_rule')) {
	function workreap_set_custom_rewrite_rule() {
		global $wp_rewrite;
		$settings = (array) workreap_get_theme_settings();
		
		if( !empty( $settings['post'] ) ){
			foreach ( $settings['post'] as $post_type => $slug ) {
				if(!empty( $slug )){
					$args = get_post_type_object($post_type);
					if( !empty( $args ) ){
						$args->rewrite["slug"] = $slug;
						register_post_type($args->name, $args);
					}
				}
			}
		}

		if( !empty( $settings['term'] ) ){
			foreach ( $settings['term'] as $term => $slug ) {
				if(!empty( $slug ) ){
					$tax = get_taxonomy($term);
					if( !empty( $tax ) ){
						$tax->rewrite["slug"] = $slug;
						register_taxonomy($term, $tax->object_type[0],(array)$tax);
					}
				}
			}
		}

		$wp_rewrite->flush_rules();
	} 
	add_action('init', 'workreap_set_custom_rewrite_rule');
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'init', 'workreap_load_textdomain' );
function workreap_load_textdomain() {
  load_plugin_textdomain( 'workreap_core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
