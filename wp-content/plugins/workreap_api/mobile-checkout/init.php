<?php

/**
 * Templater
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           WorkreapAppApi
 *
 */
if (!class_exists('CheckoutPageTemplater')) {
	class CheckoutPageTemplater
	{

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * The array of templates that this plugin tracks.
		 */
		protected $templates;

		/**
		 * Returns an instance of this class. 
		 */
		public static function get_instance()
		{

			if (null == self::$instance) {
				self::$instance = new CheckoutPageTemplater();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct()
		{

			$this->templates = array();


			// Add a filter to the attributes metabox to inject template into the cache.
			if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {

				// 4.6 and older
				add_filter(
					'page_attributes_dropdown_pages_args',
					array($this, 'register_project_templates')
				);
			} else {

				// Add a filter to the wp 4.7 version attributes metabox
				add_filter(
					'theme_page_templates',
					array($this, 'add_new_template')
				);
			}

			// Add a filter to the save post to inject out template into the page cache
			add_filter(
				'wp_insert_post_data',
				array($this, 'register_project_templates')
			);


			// Add a filter to the template include to determine if the page has our 
			// template assigned and return it's path
			add_filter(
				'template_include',
				array($this, 'view_project_template')
			);


			// Add your templates to this array.
			$this->templates = array(
				'mobile-checkout.php' => esc_html__('Mobile Checkout', 'workreap_api'),
			);
		}

		/**
		 * Adds our template to the page dropdown for v4.7+
		 *
		 */
		public function add_new_template($posts_templates)
		{
			$posts_templates = array_merge($posts_templates, $this->templates);
			return $posts_templates;
		}

		/**
		 * Adds our template to the pages cache in order to trick WordPress
		 * into thinking the template file exists where it doens't really exist.
		 */
		public function register_project_templates($atts)
		{

			// Create the key used for the themes cache
			$cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

			// Retrieve the cache list. 
			// If it doesn't exist, or it's empty prepare an array
			$templates = wp_get_theme()->get_page_templates();
			if (empty($templates)) {
				$templates = array();
			}

			// New cache, therefore remove the old one
			wp_cache_delete($cache_key, 'themes');

			// Now add our template to the list of templates by merging our templates
			// with the existing templates array from the cache.
			$templates = array_merge($templates, $this->templates);

			// Add the modified cache to allow WordPress to pick it up for listing
			// available templates
			wp_cache_add($cache_key, $templates, 'themes', 1800);

			return $atts;
		}

		/**
		 * Checks if the template is assigned to the page
		 */
		public function view_project_template($template)
		{

			// Get global post
			global $post;

			// Return template if post is empty
			if (!$post) {
				return $template;
			}

			// Return default template if we don't have a custom one defined
			if (!isset($this->templates[get_post_meta(
				$post->ID,
				'_wp_page_template',
				true
			)])) {
				return $template;
			}

			$file = plugin_dir_path(__FILE__) . get_post_meta(
				$post->ID,
				'_wp_page_template',
				true
			);

			// Just to be safe, we check if the file exist first
			if (file_exists($file)) {
				return $file;
			} else {
				echo $file;
			}

			// Return template
			return $template;
		}
	}
	add_action('plugins_loaded', array('CheckoutPageTemplater', 'get_instance'));
}

if (!function_exists('workreap_app_checkout_css')) {
	function workreap_app_checkout_css()
	{
		if (isset($_GET['platform'])) { ?>
			<meta name="viewport" content="width=device-width, user-scalable=no">
			<style type="text/css">
				body.woocommerce-order-received .woocommerce-order {
					padding: 0;
					border: 0;
				}

				.preloader-outer,
				.tg-appavailable,
				.wt-innerbannerholder,
				.woocommerce-privacy-policy-link,
				.wt-demo-sidebar,
				.wt-go-dbbtn,
				header,
				footer {
					display: none !important;
				}

				.wc-credit-card-form.wc-payment-form {
					clear: both !important;
				}

				.wc-stripe-elements-field,
				.wc-stripe-iban-element-field {
					min-height: 40px;
					line-height: 40px;
				}

				.wt-main {
					padding: 0;
				}
			</style>
		<?php
		}
	}
	add_action('wp_head', 'workreap_app_checkout_css');
}

/**
 * Add hidden field on checkout
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_app_mobile_checkout_hidden_field')) {
	// add_action('woocommerce_after_order_notes', 'workreap_app_mobile_checkout_hidden_field');
	add_action('woocommerce_after_checkout_billing_form', 'workreap_app_mobile_checkout_hidden_field');
	function workreap_app_mobile_checkout_hidden_field($checkout)
	{
		$platform_val	= !empty($_GET['platform']) ? $_GET['platform'] : '';
		echo '<div id="user_link_hidden_checkout_field">
				<input type="hidden" class="input-hidden" name="platform" id="platform" value="' . $platform_val . '">
			</div>';
	}
}

/**
 * update hidden field value after checkout
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_app_save_mobile_checkout_hidden_field')) {
	add_action('woocommerce_checkout_update_order_meta', 'workreap_app_save_mobile_checkout_hidden_field');
	function workreap_app_save_mobile_checkout_hidden_field($order_id)
	{
		if (!empty($_POST['platform'])) {
			update_post_meta($order_id, '_platform', sanitize_text_field($_POST['platform']));
		}
	}
}

/**
 * Change URL after checkout
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_app_checkout_get_return_url')) {
	add_filter('woocommerce_get_checkout_order_received_url', 'workreap_app_checkout_get_return_url', 10, 2);
	function workreap_app_checkout_get_return_url($return_url, $order)
	{
		$platform	= get_post_meta($order->get_id(), '_platform', true);
		if (!empty($platform) && $platform === 'mobile') {
			$query_args = array(
				'platform' => 'mobile',
			);
			return add_query_arg($query_args, $return_url);
		} else {
			return $return_url;
		}
	}
}
