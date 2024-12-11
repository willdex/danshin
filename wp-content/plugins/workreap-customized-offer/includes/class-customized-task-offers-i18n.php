<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://amentotech.com/
 * @since      1.0.0
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/includes
 * @author     Amento Tech <info@amentotech.com>
 */
class Workreap_Customized_Task_Offers_Addon_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'customized-task-offer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
