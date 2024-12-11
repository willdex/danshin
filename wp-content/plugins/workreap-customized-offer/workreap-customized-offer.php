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
 * @package           Workreap_Customized_Task_Offers_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Workreap - Customized Offers
 * Plugin URI:        https://codecanyon.net/user/amentotech/portfolio
 * Description:       This addon will allow to create an customised offer to the buyers.
 * Version:           3.0.4
 * Author:            Amentotech
 * Author URI:        https://themeforest.net/user/amentotech/portfolio
 * Text Domain:       customized-task-offer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WORKREAP_CUSTOMIZED_TASK_OFFERS_VERSION', '3.0.4' );
define( 'WORKREAP_CUSTOMIZED_TASK_OFFERS', plugin_dir_path( __FILE__ ));
define( 'WORKREAP_CUSTOMIZED_TASK_OFFERS_URI', plugin_dir_url( __FILE__ ));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-customized-task-offers-activator.php
 */
function workreap_customized_task_offers_addon_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customized-task-offers-activator.php';
	Workreap_Customized_Task_Offers_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-customized-task-offers-deactivator.php
 */
function workreap_customized_task_offers_addon_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customized-task-offers-deactivator.php';
	Workreap_Customized_Task_Offers_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'workreap_customized_task_offers_addon_activate' );
register_deactivation_hook( __FILE__, 'workreap_customized_task_offers_addon_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'helpers/customized-task-offers-emails.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-customized-task-offers.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/functions.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/ajax-hooks.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/hooks.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/template-loader.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function workreap_customized_task_offers_addon_run() {
	$plugin = new Workreap_Customized_Task_Offers_Addon();
	$plugin->run();

}
workreap_customized_task_offers_addon_run();

/**
 * Load plugin textdomain
 *
 * @since 1.0.0
 */
add_action( 'init', 'workreap_customized_task_offers_addon_load_textdomain' );
function workreap_customized_task_offers_addon_load_textdomain() {
  	load_plugin_textdomain( 'customized-task-offer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}