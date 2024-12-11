<?php

/**
 * Plugin installation and activation for WordPress themes.
 *
 * Please note that this is a drop-in library for a theme or plugin.
 * The authors of this library (Thomas, Gary and Juliette) are NOT responsible
 * for the support of your plugin or theme. Please contact the plugin
 * or theme author for support.
 *
 * @package   TGM-Plugin-Activation
 * @version   2.6.1
 * @link      http://tgmpluginactivation.com/
 * @author    Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright Copyright (c) 2011, Thomas Griffin
 * @license   GPL-2.0+
 */
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/tgmp/class-tgm-plugin-activation.php';

if (!function_exists('workreap_plugin_activation')) {

	add_action('tgmpa_register', 'workreap_plugin_activation');
	add_filter( 'ocdi/register_plugins', 'workreap_plugin_activation' );

	/**
	 * Register the required plugins for this theme.
	 *
	 * In this example, we register two plugins - one included with the TGMPA library
	 * and one from the .org repo.
	 *
	 * The variable passed to tgmpa_register_plugins() should be an array of plugin
	 * arrays.
	 *
	 * This function is hooked into tgmpa_init, which is fired within the
	 * TGM_Plugin_Activation class constructor.
	 */
	function workreap_plugin_activation() {

		$plugins = array();
		$directory = get_template_directory() . '/inc/plugins/';

		$theme_check = workreap_new_theme_active();

		$plugins = array(
			array(
				'name'      => esc_html__('Redux Framework', 'workreap'),
				'slug'      => 'redux-framework',
				'required'  => true,
				'preselected' => true,
			),
			array(
				'name'      => esc_html__('Advanced Custom Fields', 'workreap'),
				'slug'      => 'advanced-custom-fields',
				'required'  => true,
				'preselected' => true,
			),
			array(
				'name'      => esc_html__('Elementor', 'workreap'),
				'slug'      => 'elementor',
				'required'  => true,
				'preselected' => true,
			),
			array(
				'name'      => esc_html__('WooCommerce', 'workreap'),
				'slug'      => 'woocommerce',
				'required'  => true,
				'preselected' => true,
			),
			array(
				'name'      => esc_html__('Workreap', 'workreap'),
				'slug'      => 'workreap',
				'source'    => $directory . 'workreap.zip',
				'required'  => true,
				'preselected' => true,
				'version'   => '3.0.4'
			),
			array(
				'name'      => esc_html__('Workreap - Hourly Addon', 'workreap'),
				'slug'      => 'workreap-hourly-addon',
				'source'    => $directory . 'workreap-hourly-addon.zip',
				'preselected' => true,
				'version'   => '3.0.4'
			),
			array(
				'name'      => esc_html__('Workreap - Customized Offers', 'workreap'),
				'slug'      => 'workreap-customized-offer',
				'source'    => $directory . 'workreap-customized-offer.zip',
				'preselected' => true,
				'version'   => '3.0.4'
			),
			array(
				'name'      => esc_html__('Contact Form 7', 'workreap'),
				'slug'      => 'contact-form-7',
				'preselected' => true,
			)
		);

		if(!$theme_check){

			$protocol = is_ssl() ? 'https' : 'http';
			$app_api        = $protocol.'://amentotech.com/autoupdate/workreap/';
			$unyson_core    = $protocol.'://amentotech.com/plugins/unyson.zip';

			$app_plugin = $app_api.'workreap_api.zip';

			$plugins = array(
				array(
					'name'          => esc_html__('Unyson', 'workreap'),
					'slug'          => 'unyson',
					'source' 		=> $unyson_core,
					'required' 		=> true,
				),
				array(
					'name'          => esc_html__('Elementor', 'workreap'),
					'slug'          => 'elementor',
					'required'      => true,
				),
				array(
					'name' 		=> esc_html__('Workreap Core', 'workreap'),
					'slug' 		=> 'workreap_core',
					'source' 	=> $directory.'workreap_core.zip',
					'required' 	=> true,
					'version'   => '3.0.4'
				),
				array(
					'name' 			=> esc_html__('Workreap CRON', 'workreap'),
					'slug' 			=> 'workreap_cron',
					'source' 		=> $directory.'workreap_cron.zip',
					'required' 		=> true,
				),
				array(
					'name' 			=> esc_html__('Workreap Mobile APP REST API( Optional - Please deactivate if not using mobile app )', 'workreap'),
					'slug' 			=> 'workreap_api',
					'source' 		=> $app_plugin,
					'required' 		=> false,
				),
				array(
					'name'          => esc_html__('Woocommerce', 'workreap'),
					'slug'          => 'woocommerce',
					'required'      => true,
				),
				array(
					'name'      => esc_html__('Contact Form 7', 'workreap'),
					'slug'      => 'contact-form-7',
				),
				array(
					'name' 		=> esc_html__('AtomChat', 'workreap'),
					'slug' 		=> 'atomchat',
				)
			);

		}

		$config = array(
			'id'           => 'tgmpa',               // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                    // Default absolute path to pre-packaged plugins.
			'menu'         => 'install-required-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',          // Parent menu slug.
			'capability'   => 'manage_options',      // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                  // Show admin notices or not.
			'dismissable'  => true,                  // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                    // If 'dismissible' is false, this message will be output at top of nag.
			'is_automatic' => false,                 // Automatically activate plugins after installation or not.
			'message'      => '',                    // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => esc_html__('Install Required Plugins', 'workreap'),
				'menu_title'                      => esc_html__('Install Plugins', 'workreap'),
				'installing'                      => esc_html__('Installing Plugin: %s', 'workreap'), // %s = plugin name.
				'oops'                            => esc_html__('Something went wrong with the plugin API.', 'workreap'),
				'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'workreap'), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'workreap'), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'workreap'), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'workreap'), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'workreap'), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'workreap'), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'workreap'), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'workreap'), // %1$s = plugin name(s).
				'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'workreap'),
				'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins', 'workreap'),
				'return'                          => esc_html__('Return to Required Plugins Installer', 'workreap'),
				'plugin_activated'                => esc_html__('Plugin activated successfully.', 'workreap'),
				'complete'                        => esc_html__('All plugins installed and activated successfully. %s', 'workreap'), // %s = dashboard link.
				'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);
		tgmpa($plugins, $config);
		return $plugins;
	}
}

$theme_check = workreap_new_theme_active();

if(!function_exists('workreap_ocdi_demo_content_data') && $theme_check){
	add_filter( 'ocdi/import_files', 'workreap_ocdi_demo_content_data' );
	function workreap_ocdi_demo_content_data() {
		return [
			[
				'import_file_name'             => 'Workreap',
				'local_import_file'            => trailingslashit(get_template_directory()) . 'demo-data/content.xml',
				'local_import_widget_file'     => trailingslashit(get_template_directory()) . 'demo-data/widget.wie',
				'local_import_customizer_file' => trailingslashit(get_template_directory()) . 'demo-data/customizer.dat',
				'local_import_redux'           => [
					[
						'file_path'   => trailingslashit(get_template_directory()) . 'demo-data/redux.json',
						'option_name' => 'workreap_settings',
					],
				],
				'import_preview_image_url'     => trailingslashit(get_template_directory_uri()) . 'demo-data/preview-image.jpg',
				'preview_url'                  => 'https://demos.codingeasel.com/projects/workreap/',
			]
		];
	}
}