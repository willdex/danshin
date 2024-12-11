<?php
/**
 * Merlin WP configuration file.
 *
 * @package   Merlin WP
 * @version   @@pkg.version
 * @link      https://merlinwp.com/
 * @author    Rich Tabor, from ThemeBeans.com & the team at ProteusThemes.com
 * @copyright Copyright (c) 2018, Merlin WP of Inventionn LLC
 * @license   Licensed GPLv3 for Open Source Use
 */

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and settings.
 */
$wizard = new Merlin(

	$config = array(
		'directory'            => 'inc/merlin',
		'merlin_url'           => 'merlin',
		'parent_slug'          => 'themes.php',
		'capability'           => 'manage_options',
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes',
		'dev_mode'             => false,
		'license_step'         => false,
		'license_required'     => false,
		'license_help_url'     => '',
		'edd_remote_api_url'   => '',
		'edd_item_name'        => '',
		'edd_theme_slug'       => '',
		'ready_big_button_url' => site_url(),
	),
	$strings = array(
		'admin-menu'               => esc_html__( 'Theme Setup', 'workreap' ),

		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'workreap' ),
		'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'workreap' ),
		'ignore'                   => esc_html__( 'Disable this wizard', 'workreap' ),

		'btn-skip'                 => esc_html__( 'Skip', 'workreap' ),
		'btn-next'                 => esc_html__( 'Next', 'workreap' ),
		'btn-start'                => esc_html__( 'Start', 'workreap' ),
		'btn-no'                   => esc_html__( 'Cancel', 'workreap' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'workreap' ),
		'btn-child-install'        => esc_html__( 'Install', 'workreap' ),
		'btn-content-install'      => esc_html__( 'Install', 'workreap' ),
		'btn-import'               => esc_html__( 'Import', 'workreap' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'workreap' ),
		'btn-license-skip'         => esc_html__( 'Later', 'workreap' ),

		/* translators: Theme Name */
		'license-header%s'         => esc_html__( 'Activate %s', 'workreap' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'workreap' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'workreap' ),
		'license-label'            => esc_html__( 'License key', 'workreap' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'workreap' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'workreap' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'workreap' ),

		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Welcome to %s', 'workreap' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'workreap' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'workreap' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'workreap' ),

		'child-header'             => esc_html__( 'Install Child Theme', 'workreap' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!', 'workreap' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'workreap' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'workreap' ),
		'child-action-link'        => esc_html__( 'Learn about child themes', 'workreap' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'workreap' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'workreap' ),

		'plugins-header'           => esc_html__( 'Install Plugins', 'workreap' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'workreap' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'workreap' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'workreap' ),
		'plugins-action-link'      => esc_html__( 'Advanced', 'workreap' ),

		'import-header'            => esc_html__( 'Import Content', 'workreap' ),
		'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'workreap' ),
		'import-action-link'       => esc_html__( 'Advanced', 'workreap' ),

		'ready-header'             => esc_html__( 'All done. Have fun!', 'workreap' ),

		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme.', 'workreap' ),
		'ready-action-link'        => esc_html__( 'Extras', 'workreap' ),
		'ready-big-button'         => esc_html__( 'View your website', 'workreap' ),
	)
);

function workreap_unset_default_widgets_args( $widget_areas ) {

	$widget_areas = array(
		'workreap-sidebar' => array(),
		'workreap-sidebar-f1' => array(),
		'workreap-sidebar-f2' => array(),
		'workreap-sidebar-f3' => array(),
	);

	return $widget_areas;

}
add_filter( 'merlin_unset_default_widgets_args', 'workreap_unset_default_widgets_args' );

function workreap_demo_content_data() {
	return [
		[
			'import_file_name'             => 'Workreap',
			'local_import_file'            => get_template_directory() . '/demo-data/content.xml',
			'local_import_widget_file'     => get_template_directory() . '/demo-data/widget.wie',
			'local_import_customizer_file' => get_template_directory() . '/demo-data/customizer.dat',
			'local_import_redux'           => [
				[
					'file_path'   => get_template_directory() . '/demo-data/redux.json',
					'option_name' => 'workreap_settings',
				],
			],
			'import_preview_image_url'     => get_template_directory() . '/demo-data/preview-image.jpg',
			'preview_url'                  => 'https://demos.codingeasel.com/projects/workreap/',
		]
	];
}
add_filter( 'merlin_import_files', 'workreap_demo_content_data' );

function workreap_after_import_setup() {

	//Set Permalink
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules();

	//Set Menus
	$main_menu = get_term_by( 'name', 'Header Menu', 'nav_menu' );
	$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

	$menus = wp_get_nav_menus();
	$nav_menu_item_args = array(
		'menu-item-object-id' => 0,
		'menu-item-parent-id' => 0,
		'menu-item-position' => 0,
		'menu-item-type' => 'custom',
		'menu-item-title' => 'Custom Link',
		'menu-item-url' => 'https://example.com',
		'menu-item-status' => 'publish',
	);

	foreach ($menus as $menu) {
		$menu_id = $menu->term_id;
		$menu_item_id = wp_update_nav_menu_item($menu_id, 0, $nav_menu_item_args);
		wp_delete_post($menu_item_id, true);
	}

	if(isset($main_menu) && $footer_menu ){
		set_theme_mod(
			'nav_menu_locations', array(
				'primary-menu' => $main_menu->term_id,
				'footer-menu' => $footer_menu->term_id,
			)
		);

		if(function_exists('update_field')){
			update_field( 'login_user_details', 'yes','term_' . $main_menu->term_id );
		}

	}

	// Assign front page and posts page.
	$front_page_slug = 'home-one';
	$blog_page_slug = 'blog';

	$front_page = get_page_by_path( $front_page_slug );
	$blog_page = get_page_by_path( $blog_page_slug );

	if ( isset($front_page->ID) ) {
		update_option( 'page_on_front', $front_page->ID );
		update_option( 'show_on_front', 'page' );
	}

	if ( isset($blog_page->ID) ) {
		update_option( 'page_for_posts', $blog_page->ID);
	}

	update_option('posts_per_page',9);

	//Search and Replace URLs
	if(did_action( 'elementor/loaded' )){
		global $wpdb;
		$old_url = 'https://demos.codingeasel.com/projects/workreap';
		$new_url = site_url();
		$value_like = '[%';

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->postmeta} " .
				"SET `meta_value` = REPLACE(`meta_value`, %s, %s) " .
				"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE %s;",
				$old_url,
				$new_url,
				$value_like
			)
		);
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

}
add_action( 'merlin_after_all_import', 'workreap_after_import_setup' );