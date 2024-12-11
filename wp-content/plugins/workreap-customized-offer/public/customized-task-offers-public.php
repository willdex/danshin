<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://amentotech.com/
 * @since      1.0.0
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/public
 * @author     Amento Tech <info@amentotech.com>
 */
class Workreap_Customized_Task_Offers_Addon_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		wp_register_style( 'workreap-customized-offer', plugin_dir_url( __FILE__ ) . 'css/customized-offer.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'workreap-customized-offer' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( 'workreap-customized-task-offers', plugin_dir_url( __FILE__ ) . 'js/customized-task-offers.js', array( 'jquery' ), $this->version, true );
		
		if( is_page_template( 'templates/add-offer.php') ){
			wp_enqueue_script('sortable');
			wp_enqueue_script('plupload');
			wp_enqueue_style( 'select2' );
			wp_enqueue_script('select2');
			wp_enqueue_script('workreap-dashboard');
			wp_enqueue_script('workreap');
		}
		
		wp_enqueue_script( 'workreap-customized-task-offers');
		$data = array(
			'ajax_nonce'			=> wp_create_nonce('ajax_nonce'),
			'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
			'decline_task'      	=> esc_html__('Decline offer', 'customized-task-offer'),
			'decline_task_message'	=> esc_html__('Are you sure you want to decline this offer?', 'customized-task-offer'),
		);
		wp_localize_script('workreap-customized-task-offers', 'customized_task_offers_scripts_vars', $data );
	}

}
