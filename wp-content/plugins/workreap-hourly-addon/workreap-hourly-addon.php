<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://amentotech.com/
 * @since             1.0.0
 * @package           Workreap_Hourly_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Workreap - Hourly Addons
 * Plugin URI:        https://codecanyon.net/user/amentotech/portfolio
 * Description:       This addon will allow the employers to post the hourly based projects.
 * Version:           3.0.4
 * Author:            Amentotech
 * Author URI:        https://amentotech.com/
 * Text Domain:       workreap-hourly-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WORKREAP_HOURLY_ADDON_VERSION', '3.0.4' );
define( 'WORKREAP_HOURLY_ADDON_URI', plugin_dir_url( __FILE__ ));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-workreap-hourly-addon-activator.php
 */
function workreap_hourly_addon_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-workreap-hourly-addon-activator.php';
	Workreap_Hourly_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-workreap-hourly-addon-deactivator.php
 */
function workreap_hourly_addon_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-workreap-hourly-addon-deactivator.php';
	Workreap_Hourly_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'workreap_hourly_addon_activate' );
register_deactivation_hook( __FILE__, 'workreap_hourly_addon_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'helpers/hourly-addon-emails.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-workreap-hourly-addon.php';
require plugin_dir_path( __FILE__ ) . 'includes/public-function.php';
require plugin_dir_path( __FILE__ ) . 'includes/ajax-hooks.php';
require plugin_dir_path( __FILE__ ) . 'includes/hooks.php';
require plugin_dir_path( __FILE__ ) . 'includes/interval-hooks.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function workreap_hourly_addon_run() {
	$plugin = new Workreap_Hourly_Addon();
	$plugin->run();

}
workreap_hourly_addon_run();

/**
 * Load plugin textdomain
 *
 * @since 1.0.0
 */
add_action( 'init', 'workreap_hourly_addon_load_textdomain' );
function workreap_hourly_addon_load_textdomain() {
  load_plugin_textdomain( 'workreap-hourly-addon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Admin notice
 *
 * @since 1.0.0
 */
if (!function_exists('workreap_hourly_activation_notice')) {
	function workreap_hourly_activation_notice(){?>
		<div class="error">
			<p><?php echo wp_kses( __( 'Please install the <a href="https://codecanyon.net/item/workreap-a-freelancer-marketplace-wordpress-plugin/35344021?s_rank=7">Workreap</a> parent plugin to use this hourly addon', 'workreap-hourly-addon'),array('a'	=> array('href'  => array(),'title' => array())));?></p>
		</div>
	<?php
	}
}

/**
 * Workreap plugin activation check
 *
 * @since 1.0.0
 */
if (function_exists('is_plugin_active')) {
	if ( !is_plugin_active('workreap/init.php') ) {
		deactivate_plugins('workreap-hourly-addon/workreap-hourly-addon.php');
		add_action( 'admin_notices', 'workreap_hourly_activation_notice' );
	}
}