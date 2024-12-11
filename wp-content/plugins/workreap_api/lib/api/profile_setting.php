<?php

/**
 * APP API to set profile settings
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           WorkreapAppApi
 *
 */
if (!class_exists('AndroidAppProfileSettingRoutes')) {

	class AndroidAppProfileSettingRoutes extends WP_REST_Controller
	{

		/**
		 * Register the routes for the objects of the controller.
		 */
		public function register_routes()
		{
			$version 	= '1';
			$namespace 	= 'api/v' . $version;
			$base 		= 'profile';

			register_rest_route(
				$namespace,
				'/' . $base . '/setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_freelancer_profile'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/verification_request',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'verification_request'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			register_rest_route(
				$namespace,
				'/' . $base . '/send_verification_request',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'send_verification_request'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/cancel_verification_request',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'cancel_verification_request'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/update_freelancer_profile',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_freelancer_profile'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			register_rest_route(
				$namespace,
				'/' . $base . '/update_freelancer_education',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_freelancer_education'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			register_rest_route(
				$namespace,
				'/' . $base . '/update_profile',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_profile_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get social profile settings 
			register_rest_route(
				$namespace,
				'/' . $base . '/get_social_profile_setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_social_profile_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//update social profile settings 
			register_rest_route(
				$namespace,
				'/' . $base . '/update_social_profile_setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_social_profile_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//update brochures settings 
			register_rest_route(
				$namespace,
				'/' . $base . '/update_brochures_setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_brochures_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get payout settings 
			register_rest_route(
				$namespace,
				'/' . $base . '/get_payout_setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_payout_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//update payout settings 
			register_rest_route(
				$namespace,
				'/' . $base . '/update_payout_setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_payout_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get payout listings 
			register_rest_route(
				$namespace,
				'/' . $base . '/get_payout_listings',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_payout_listings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get payout listings 
			register_rest_route(
				$namespace,
				'/' . $base . '/get_users_payouts',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_users_payouts'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get payout listings 
			register_rest_route(
				$namespace,
				'/' . $base . '/get_freelancer_availble_balance',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_freelancer_availble_balance'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get payout listings 
			register_rest_route(
				$namespace,
				'/' . $base . '/payout_submit_withdraw',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'payout_submit_withdraw'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get account settings
			register_rest_route(
				$namespace,
				'/' . $base . '/get_account_settings',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_account_settings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//update account settings
			register_rest_route(
				$namespace,
				'/' . $base . '/update_account_settings',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_account_settings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/update_password',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_password'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//delete account
			register_rest_route(
				$namespace,
				'/' . $base . '/delete_account',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'delete_account'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get billing address
			register_rest_route(
				$namespace,
				'/' . $base . '/get_billing_settings',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_billing_settings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get dispute form data
			register_rest_route(
				$namespace,
				'/' . $base . '/dispute_form',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'dispute_form'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//create dispute
			register_rest_route(
				$namespace,
				'/' . $base . '/create_dispute',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'create_dispute'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get dispute listings
			register_rest_route(
				$namespace,
				'/' . $base . '/get_dispute_listings',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_dispute_listings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get invoice listings
			register_rest_route(
				$namespace,
				'/' . $base . '/get_invoice_listings',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_invoice_listings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get invoice listings
			register_rest_route(
				$namespace,
				'/' . $base . '/download_invoice',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'download_invoice'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//remove invoice pdf
			register_rest_route(
				$namespace,
				'/' . $base . '/remove_invoice_file',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'remove_invoice_file'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get invoice detail
			register_rest_route(
				$namespace,
				'/' . $base . '/get_invoice_detail',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_invoice_detail'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get education and experience
			register_rest_route(
				$namespace,
				'/' . $base . '/get_profile_tab_settings',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_profile_tab_settings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get profile basic
			register_rest_route(
				$namespace,
				'/' . $base . '/get_profile_basic',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_profile_basic'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//Update education and experience
			register_rest_route(
				$namespace,
				'/' . $base . '/update_tab_settings',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_tabe_settings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get profile basic
			register_rest_route(
				$namespace,
				'/' . $base . '/get_employer_profle',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_employer_profle'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//Update profile email
			register_rest_route(
				$namespace,
				'/' . $base . '/update_profile_email',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_profile_email'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//disable account
			register_rest_route(
				$namespace,
				'/' . $base . '/update_block_setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_block_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//account settings
			register_rest_route(
				$namespace,
				'/' . $base . '/get_setting',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_setting'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// Delete account reason
			register_rest_route(
				$namespace,
				'/' . $base . '/get_remove_reasons',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_remove_reasons'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/help_faq',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'help_support_faq'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/help_support_query',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'help_support_query'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * get account settings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_setting($request)
		{
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$post_id    = workreap_get_linked_profile_id($user_id);
			$user_type  = apply_filters('workreap_get_user_type', $user_id);
			$json		= array();
			$account_settings = array();

			if (empty($user_id)) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('something went wrong', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$profile_blocked	    = get_post_meta($post_id, '_profile_blocked', true);
			$profile_blocked	    = !empty($profile_blocked) ? $profile_blocked : 'off';
			$hourly_rate	        = get_post_meta($post_id, '_hourly_rate_settings', true);
			$hourly_rate	        = !empty($hourly_rate) ? $hourly_rate : 'off';
			$project_notification	= get_post_meta($post_id, '_project_notification', true);
			$project_notification   = !empty($project_notification) ? $project_notification : 'off';

			if ($user_type === 'freelancer' && !empty($user_id)) {
				$account_data['profile_blocked'] = $profile_blocked;
				$account_data['hourly_rate'] = $hourly_rate;
				$account_data['project_notification'] = $project_notification;
			}
			if ($user_type === 'employer' && !empty($user_id)) {
				$account_data['profile_blocked'] = $profile_blocked;
			}
			$account_settings['account_settings'] = $account_data;
			return new WP_REST_Response($account_settings, 200);
		}



		/**
		 * Update profile block settings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_block_setting($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$post_id    = workreap_get_linked_profile_id($user_id);
			$user_type  = apply_filters('workreap_get_user_type', $user_id);
			$disable_account = !empty($request['disable_account']) ? $request['disable_account'] : 'off';
			$disable_hourly_rate = !empty($request['disable_hourly_rate']) ? $request['disable_hourly_rate'] : 'off';
			$disable_project_notification = !empty($request['disable_project_notification']) ? $request['disable_project_notification'] : 'off';
			$json		= array();
			if (empty($user_id)) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('something went wrong', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if ($user_type === 'freelancer' || $user_type === 'employer') {
				if ($user_type === 'freelancer' && !empty($user_id)) {
					update_post_meta($post_id, '_profile_blocked', $disable_account);
					update_post_meta($post_id, '_hourly_rate_settings', $disable_hourly_rate);
					update_post_meta($post_id, '_project_notification', $disable_project_notification);
				}
				if ($user_type === 'employer' && !empty($user_id)) {
					update_post_meta($post_id, '_profile_blocked', $disable_account);
				}
				$json['type'] = 'success';
				$json['message'] = esc_html__('Profile has been updated', 'workreap_api');
				return new WP_REST_Response($json, 200);
			}
		}


		/**
		 * Update profile email address
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_profile_email($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$json		= array();
			$items		= array();

			$post_id 		 = workreap_get_linked_profile_id($user_id);
			if (empty($post_id)) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Post ID is not found', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$user_identity  = $user_id;
			$useremail 		= !empty($request['useremail']) ? $request['useremail'] : '';


			if (empty($useremail)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Email field is requird', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if (!is_email($useremail)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please add a valid email address', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$account_types_permissions	= '';
			if (function_exists('fw_get_db_settings_option')) {
				$account_types_permissions 	= fw_get_db_settings_option('account_types_permissions', $default_value = null);
			}

			$switch_user_id	= get_user_meta($user_identity, 'switch_user_id', true);
			$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';

			if (!empty($switch_user_id) && !empty($account_types_permissions) && $account_types_permissions == 'yes') {
				$user_details	= get_userdata($switch_user_id);
				if (!empty($user_details->user_email) && $user_details->user_email == $useremail) {
					global	$wpdb;
					$query	= "UPDATE " . $wpdb->prefix . "users SET `user_email` = '" . $useremail . "'  
								WHERE ID='" . $user_identity . "'";

					$user_update = $wpdb->query(
						$wpdb->prepare($query)
					);

					$json['type'] = 'success';
					$json['message'] = esc_html__('Your email has been updated', 'workreap_api');
					return new WP_REST_Response($json, 200);
				} else {
					$user_data = wp_update_user(array('ID' => $user_id, 'user_email' => $useremail));
					if (!is_wp_error($user_data)) {
						global	$wpdb;
						$query	= "UPDATE " . $wpdb->prefix . "users SET `user_email` = '" . $useremail . "'  
								WHERE ID='" . $switch_user_id . "'";

						$user_update = $wpdb->query(
							$wpdb->prepare($query)
						);

						$json['type'] = 'success';
						$json['message'] = esc_html__('Your email has been updated', 'workreap_api');
						return new WP_REST_Response($json, 200);
					} else {
						$error_string = $user_data->get_error_message();
						$json['type'] = 'error';

						if (!empty($error_string)) {
							$json['message'] = $error_string;
						} else {
							$json['message'] = esc_html__('Error occurred', 'workreap_api');
						}

						return new WP_REST_Response($json, 203);
					}
				}
			} else {

				$user_data = wp_update_user(array('ID' => $user_id, 'user_email' => $useremail));

				if (is_wp_error($user_data)) {
					$error_string = $user_data->get_error_message();
					$json['type'] = 'error';

					if (!empty($error_string)) {
						$json['message'] = $error_string;
					} else {
						$json['message'] = esc_html__('Error occurred', 'workreap_api');
					}

					return new WP_REST_Response($json, 203);
				} else {
					$json['type'] = 'success';
					$json['message'] = esc_html__('Your email has been updated', 'workreap_api');
				}

				return new WP_REST_Response($json, 200);
			}
		}

		/**
		 * Get employer details
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_employer_profle($request)
		{
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$item		= array();
			if (!empty($user_id)) {
				$profile_id		= workreap_get_linked_profile_id($user_id);
				$first_name		= get_user_meta($user_id, 'first_name', true);
				$last_name 		= get_user_meta($user_id, 'last_name', true);
				$user_info 		= get_userdata($user_id);
				$post_object 	= get_post($profile_id);
				$tag_line 	 	= workreap_get_tagline($profile_id);

				$job_company_name = $job_title = $employees = $department = $user_phone_number = '';
				if (function_exists('fw_get_db_post_option')) {
					$address    		= fw_get_db_post_option($profile_id, 'address', true);
					$latitude			= fw_get_db_post_option($profile_id, 'latitude', true);
					$longitude			= fw_get_db_post_option($profile_id, 'longitude', true);
					$countries			= fw_get_db_post_option($profile_id, 'country', true);
					$job_title			= fw_get_db_post_option($profile_id, 'company_job_title', true);
					$department     	= fw_get_db_post_option($profile_id, 'department', true);
					$employees     	 	= fw_get_db_post_option($profile_id, 'no_of_employees', true);
					$job_company_name	= fw_get_db_post_option($profile_id, 'company_name', true);
					$user_phone_number  = fw_get_db_post_option($profile_id, 'user_phone_number');
				}

				$countries	= !empty($countries[0]) ? intval($countries[0]) : '';

				if (!empty($countries)) {
					$locations	= get_term_by('id', $countries, 'locations');
				}

				if (function_exists('fw_get_db_post_option')) {
					$hide_departments   = fw_get_db_settings_option('hide_departments', $default_value = null);
				}

				$brochures_data = array();
				if (function_exists('fw_get_db_post_option')) {
					$brochures	= fw_get_db_post_option($profile_id, 'brochures');
					if (!empty($brochures)) {
						foreach ($brochures as $doc) {
							$attachment		= array();
							$attachment['size'] 			= !empty($doc) ? size_format(filesize(get_attached_file($doc['attachment_id']))) : '';
							$attachment['name']				= !empty($doc) ? get_the_title($doc['attachment_id']) : '';
							$attachment['url']				= !empty($doc['url']) ? $doc['url'] : '';
							$attachment['attachment_id']	= !empty($doc['attachment_id']) ? $doc['attachment_id'] : '';
							$brochures_data[]				= $attachment;
						}
					}
				}

				$item['location']			= isset($locations->name) && !empty($locations->name) ? $locations->name : '';
				$item['location_slug']		= isset($locations->slug) && !empty($locations->slug) ? $locations->slug : '';
				$item['display_name']		= !empty($user_info->display_name) ? esc_attr($user_info->display_name) : '';
				$item['first_name']			= !empty($first_name) ? esc_attr($first_name) : '';
				$item['last_name']			= !empty($last_name) ? esc_attr($last_name) : '';
				$item['content'] 	 		= !empty($post_object->post_content) ? $post_object->post_content : '';
				$item['tag_line'] 			= !empty($tag_line) ? $tag_line : '';
				$item['address']			= !empty($address) ? esc_attr($address) : '';
				$item['latitude']			= !empty($latitude) ? esc_attr($latitude) : '';
				$item['longitude']			= !empty($longitude) ? esc_attr($longitude) : '';
				$item['company_name']		= !empty($job_company_name) ? esc_attr($job_company_name) : '';
				$item['job_title']			= !empty($job_title) ? esc_attr($job_title) : '';
				$item['department']			= !empty($department[0]) ? $department[0] : '';
				$item['employees']			= !empty($employees) ? $employees : '';
				$item['phone_number']		= !empty($user_phone_number) ? $user_phone_number : '';
				$item['brochures']			= !empty($brochures_data) ? $brochures_data : array();
				$item['type']				= 'success';
				$item['message']			= esc_html__('profile Settings.', 'workreap_api');
				$items						= maybe_unserialize($item);
				return new WP_REST_Response($items, 200);
			}
		}

		/**
		 * Get profile basic
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_profile_basic($request)
		{
			$user_id				= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$json					= array();
			$json['user_type']		= '';
			$json['profile_img']	= '';
			$json['banner_img']		= '';

			$user_data            	= get_userdata($user_id);
			$json['user_email']     = $user_data->data->user_email;
			$profile_id				= workreap_get_linked_profile_id($user_id);

			if ('freelancer' === apply_filters('workreap_get_user_type', $user_id)) {
				$json['user_type']		= 'freelancer';
				$json['profile_img'] 	= apply_filters(
					'workreap_freelancer_avatar_fallback',
					workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $profile_id),
					array('width' => 100, 'height' => 100)
				);
				$json['banner_img'] 	= apply_filters(
					'workreap_freelancer_banner_fallback',
					workreap_get_freelancer_banner(array('width' => 1920, 'height' => 400), $profile_id),
					array('width' => 1920, 'height' => 400)
				);
			} else if ('employer' == apply_filters('workreap_get_user_type', $user_id)) {
				$json['user_type']		= 'employer';
				$json['profile_img'] 	=  apply_filters(
					'workreap_employer_avatar_fallback',
					workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id),
					array('width' => 100, 'height' => 100)
				);
				$json['banner_img'] 	=  apply_filters(
					'workreap_employer_banner_fallback',
					workreap_get_employer_banner(array('width' => 1920, 'height' => 400), $profile_id),
					array('width' => 100, 'height' => 400)
				);
			}

			$thumb_id 						= fw_get_db_post_option($profile_id, 'banner_image', true);
			$json['banner_attachment_id']	= '';

			if (!empty($thumb_id['attachment_id'])) {
				$json['banner_attachment_id']	= intval($thumb_id['attachment_id']);
			}

			$attachment_id 			= get_post_thumbnail_id($profile_id);
			$json['attachment_id']	= !empty($attachment_id) ? intval($attachment_id) : '';
			$json					= maybe_unserialize($json);
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Update education and experience listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		/**
		 * Update education and experience listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_tabe_settings($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json = array();
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$edit_type		= !empty($request['edit_type']) ? $request['edit_type'] : '';
			$post_id 		 = workreap_get_linked_profile_id($user_id);

			if (empty($post_id)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Post ID is not found', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$fw_options             = fw_get_db_post_option($post_id);

			if (!empty($edit_type) && $edit_type === 'edu_exp') {

				//Experience
				$experiences = array();
				$experience  = !empty($request['experience']) ? $request['experience'] : array();
				if (!empty($experience)) {
					$counter = 0;
					do_action('workreap_update_profile_strength', 'experience', true);
					foreach ($experience as $key => $value) {
						if (!empty($value['title'])) {
							$experiences[$counter]['title']       = $value['title'];
							$experiences[$counter]['company']     = $value['company'];
							$experiences[$counter]['startdate']   = apply_filters('workreap_picker_date_format', $value['startdate']);
							$experiences[$counter]['enddate']     = apply_filters('workreap_picker_date_format', $value['enddate']);
							$experiences[$counter]['description'] = $value['description'];
							$counter++;
						}
					}
				} else {
					do_action('workreap_update_profile_strength', 'experience', false);
				}

				update_post_meta($post_id, '_experience', $experiences);

				//Education        
				$educations = array();
				$education  = !empty($request['education']) ? $request['education'] : array();
				if (!empty($education)) {
					$counter = 0;
					foreach ($education as $key => $value) {
						if (!empty($value['title'])) {
							$educations[$counter]['title']          = $value['title'];
							$educations[$counter]['institute']      = $value['institute'];
							$educations[$counter]['startdate']      = apply_filters('workreap_picker_date_format', $value['startdate']);
							$educations[$counter]['enddate']        = apply_filters('workreap_picker_date_format', $value['enddate']);
							$educations[$counter]['description']    = sanitize_textarea_field($value['description']);
							$counter++;
						}
					}
				}

				update_post_meta($post_id, '_educations', $educations);

				$fw_options['experience']         = $experiences;
				$fw_options['education']          = $educations;
			} elseif (!empty($edit_type) && $edit_type === 'videos') {
				$fw_options['videos']  = !empty($request['videos']) ? $request['videos'] : array();
			} elseif (!empty($edit_type) && $edit_type === 'specialization') {
				//specializations
				$specialization 		= !empty($request['specialization']) ? $request['specialization'] : array();
				$spec_keys 				= array();
				$specialization_new 	= array();
				$specialization_term 	= array();

				$counter = 0;
				if (!empty($specialization)) {
					foreach ($specialization as $key => $value) {
						if (!in_array($value['spec'], $spec_keys)) {
							$spec_keys[] 									= $value['spec'];
							$specialization_new[$counter]['spec'][0] 		= $value['spec'];
							$specialization_new[$counter]['value'] 			= $value['value'];
							$specialization_term[] 							= $value['spec'];
							$counter++;
						}
					}
				}

				wp_set_post_terms($post_id, $specialization_term, 'wt-specialization');
				$fw_options['specialization']             = $specialization_new;
			} elseif (!empty($edit_type) && $edit_type === 'industrial_experience') {
				//specializations
				$industrial_experiences = !empty($request['industrial_experiences']) ? $request['industrial_experiences'] : array();
				$exp_keys 	= array();
				$industrial_experiences_new 	= array();
				$industrial_experiences_term 	= array();

				$counter = 0;
				if (!empty($industrial_experiences)) {
					foreach ($industrial_experiences as $key => $value) {
						if (!in_array($value['exp'], $exp_keys)) {
							$exp_keys[] = $value['exp'];
							$industrial_experiences_new[$counter]['exp'][0] = $value['exp'];
							$industrial_experiences_new[$counter]['value']  = $value['value'];
							$industrial_experiences_term[] = $value['exp'];
							$counter++;
						}
					}

					if (!empty($industrial_experiences_term)) {
						wp_set_post_terms($post_id, $industrial_experiences_term, 'wt-industrial-experience');
					}

					$fw_options['industrial_experiences']	= $industrial_experiences_new;
				}
			} elseif (!empty($edit_type) && $edit_type === 'awards') {
				$awards_data  = !empty($request['awards']) ? json_decode(stripslashes($request['awards']), true) : array();

				$award_files_arr = array();
				if (!empty($awards_data)) {
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$index_count = 0;
					foreach ($awards_data as $key => $project_award_obj) {

						$project_award_files 	= !empty($_FILES['project_award_image_' . $key]) ? $_FILES['project_award_image_' . $key] : '';
						if (!empty($project_award_files['name'])) {
							$uploaded_image  		= wp_handle_upload($project_award_files, array('test_form' => false));
							$file_name			 	= basename($project_award_files['name']);
							$file_type 		 		= wp_check_filetype($uploaded_image['file']);

							/* Prepare an array of post data for the attachment. */
							$attachment_details = array(
								'guid' 				=> $uploaded_image['url'],
								'post_mime_type' 	=> $file_type['type'],
								'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
								'post_content' 		=> '',
								'post_status' 		=> 'inherit'
							);

							$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
							$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
							wp_update_attachment_metadata($attach_id, $attach_data);

							$docs_attached['attachment_id']  		= (int)$attach_id;
							$docs_attached['url']            		= wp_get_attachment_url($attach_id);
							$doc_attached['title']					= $project_award_obj['title'];
							$doc_attached['date']					= $project_award_obj['date'];
							$doc_attached['image']					= $docs_attached;

							$award_files_arr[$index_count] 			= $doc_attached;
						} else {

							$award_files_arr[$index_count] = array(
								'title'		=> $project_award_obj['title'],
								'date'		=> $project_award_obj['date'],
								'image'		=> $project_award_obj['image'],
							);
						}

						$index_count++;
					}
				}

				update_post_meta($post_id, '_awards', $award_files_arr);
				$fw_options['awards']  	= $award_files_arr;

			} elseif (!empty($edit_type) && $edit_type === 'projects') {
				$projects_data  = !empty($request['project']) ? json_decode(stripslashes($request['project']), true) : array();

				$project_files_arr = array();
				if (!empty($projects_data)) {
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$index_counter = 0;
					foreach ($projects_data as $key => $project_obj) {

						$project_image_files 	= !empty($_FILES['project_image_' . $key]) ? $_FILES['project_image_' . $key] : '';
						if (!empty($project_image_files['name'])) {
							$uploaded_image  		= wp_handle_upload($project_image_files, array('test_form' => false));
							$file_name			 	= basename($project_image_files['name']);
							$file_type 		 		= wp_check_filetype($uploaded_image['file']);

							/* Prepare an array of post data for the attachment. */
							$attachment_details = array(
								'guid' 				=> $uploaded_image['url'],
								'post_mime_type' 	=> $file_type['type'],
								'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
								'post_content' 		=> '',
								'post_status' 		=> 'inherit'
							);

							$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
							$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
							wp_update_attachment_metadata($attach_id, $attach_data);

							$docs_attached['attachment_id']  		= (int)$attach_id;
							$docs_attached['url']            		= wp_get_attachment_url($attach_id);
							$doc_attached['title']					= $project_obj['title'];
							$doc_attached['link']					= $project_obj['link'];
							$doc_attached['image']					= $docs_attached;

							$project_files_arr[$index_counter] 			= $doc_attached;
						} else {

							$project_files_arr[$index_counter] = array(
								'title'		=> $project_obj['title'],
								'link'		=> $project_obj['link'],
								'image'		=> $project_obj['image'],
							);
						}

						$index_counter++;
					}
				}

				update_post_meta($post_id, '_projects', $project_files_arr);
				$fw_options['projects']  	= $project_files_arr;
			} elseif (!empty($edit_type) && $edit_type === 'faq') {
				$faq 					= !empty($request['faq']) ? $request['faq'] : array();
				$fw_options['faq']      = $faq;
			} elseif (!empty($edit_type) && $edit_type === 'gallery') {

				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				require_once(ABSPATH . 'wp-includes/pluggable.php');

				$total_gallery_imgs = !empty($request['images_gallery_new']) ? $request['images_gallery_new'] : 0;
				$total_gallery_imgs = !empty($total_gallery_imgs) ? $total_gallery_imgs : 0;
				/* old images array */
				$old_images = !empty($request['images_gallery']) ? json_decode(stripslashes($request['images_gallery']), true) : '';

				/* already attached images */
				$gallery_old_attachment       = fw_get_db_post_option($post_id, 'images_gallery', true);

				if (!empty($gallery_old_attachment)) {
					$gallery_old_attachment 	= wp_list_pluck($gallery_old_attachment, 'attachment_id');
				}

				/* delete all images if sending null/empty */
				if (empty($old_images) && !empty($gallery_old_attachment)) {
					foreach ($gallery_old_attachment as $delete_media) {
						if (!empty($delete_media)) {
							wp_delete_attachment($post_id, $delete_media, true);
						}
					}

					$fw_options['images_gallery']	= array();
				}

				/* upload new images if exist */
				$newyUploadImage = array();
				if (!empty($_FILES) && $total_gallery_imgs != 0) {
					$new_index	= !empty($fw_options['images_gallery']) ?  max(array_keys($fw_options['images_gallery'])) : 0;

					for ($x = 0; $x < $total_gallery_imgs; $x++) {
						$new_index = $new_index + 1;
						$gallery_image_files 	= $_FILES['gallery_images' . $x];
						$uploaded_image  		= wp_handle_upload($gallery_image_files, array('test_form' => false));
						$file_name			 	= basename($gallery_image_files['name']);
						$file_type 		 		= wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' 				=> $uploaded_image['url'],
							'post_mime_type' 	=> $file_type['type'],
							'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' 		=> '',
							'post_status' 		=> 'inherit'
						);
						$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);

						$gallery['attachment_id']  = (int)$attach_id;
						$gallery['url']            = wp_get_attachment_url($attach_id);
						$fw_options['images_gallery'][$new_index]	= $gallery;
						$newyUploadImage[] = $gallery;
					}
				}

				/* delete some images that not send in request */
				if (!empty($old_images) && !empty($gallery_old_attachment)) {
					$newArr = array();
					$db_saved_images = !empty($fw_options['images_gallery']) ? $fw_options['images_gallery'] : array();
					if (!empty($db_saved_images)) {
						foreach ($db_saved_images as $imgsVal) {
							foreach ($old_images as $oldVal) {
								if ($imgsVal['attachment_id'] == $oldVal['attachment_id']) {
									$newArr[] = array(
										'attachment_id' => (int)$imgsVal['attachment_id'],
										'url' 			=> $imgsVal['url']
									);
								}
							}
						}

						$imgNew_arr = array_merge($newyUploadImage, $newArr);
						$fw_options['images_gallery'] = $imgNew_arr;
					}
				}
			}

			/* Update User Profile */
			fw_set_db_post_option($post_id, null, $fw_options);

			$json['type']		= 'success';
			$json['message']	= esc_html__('Settings have been updated', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}



		/**
		 * Get education and experience listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_profile_tab_settings($request)
		{
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';

			$json		= array();
			$items		= array();

			$post_id 		 = workreap_get_linked_profile_id($user_id);
			if (empty($post_id)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Post ID is not found', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if (function_exists('fw_get_db_post_option')) {
				$education 	= fw_get_db_post_option($post_id, 'education', true);
				$experience = fw_get_db_post_option($post_id, 'experience', true);
				$projects 	= fw_get_db_post_option($post_id, 'projects', true);
				$videos 	= fw_get_db_post_option($post_id, 'videos', true);
				$specializations 	 = fw_get_db_post_option($post_id, 'specialization', true);
				$industrial_experiences 	 = fw_get_db_post_option($post_id, 'industrial_experiences', true);
				$awards = fw_get_db_post_option($post_id, 'awards', true);
			}

			$projects_array	= array();
			if (!empty($projects)) {
				$counter = 0;
				foreach ($projects as $key => $value) {
					$projects_array[$counter]['title'] 		= !empty($value['title']) ? stripslashes($value['title']) : '';
					$projects_array[$counter]['link'] 		= !empty($value['link']) ? esc_url($value['link']) : '';
					$image									= array();
					$image 									= !empty($value['image']) ? ($value['image']) : '';

					$projects_array[$counter]['image']['attachment_id']	= !empty($value['image']['attachment_id']) ? $value['image']['attachment_id'] : 0;
					$projects_array[$counter]['image']['url']			= !empty($value['image']['attachment_id']) ? wp_get_attachment_url($value['image']['attachment_id']) : '';
					$counter++;
				}
			}

			$awards_array	= array();
			if (!empty($awards) && is_array($awards)) {
				$counter = 0;
				foreach ($awards as $key => $value) {
					$awards_array[$counter]['title'] 		= !empty($value['title']) ? stripslashes($value['title']) : '';

					$date 		= !empty($value['date']) ? str_replace('/', '-', $value['date']) : '';
					$image 		= !empty($value['image']) ? $value['image'] : array();
					$attachment_id	= !empty($image['attachment_id']) ? $image['attachment_id'] : 0;
					$image_url 	= !empty($attachment_id) ? wp_get_attachment_image_src($attachment_id, 'workreap_latest_articles_widget', true) : '';

					if (empty($image_url[0])) {
						$image_data = get_template_directory_uri() . '/images/awards-65x65.jpg';
					} else {
						$image_data = $image_url[0];
					}
					$awards_array[$counter]['date']	= $date;
					$awards_array[$counter]['url']	= $image_data;
					$awards_array[$counter]['award_date']		= !empty($date) ? date_i18n('F Y', strtotime($date)) : '';
					$awards_array[$counter]['attachment_id']	= !empty($attachment_id) ? $attachment_id : '';
					$file_size 									= !empty($image) ? filesize(get_attached_file($attachment_id)) : '';
					$awards_array[$counter]['name']				= !empty($image) ? esc_html(get_the_title($attachment_id)) : '';
					$filetype        						= !empty($image) ? esc_url($image['url']) : '';
					$awards_array[$counter]['ext']			= !empty($filetype['ext']) ? $filetype['ext'] : '';
					$counter++;
				}
			}
			$specializations_list	= array();
			if (!empty($specializations) && is_array($specializations)) {
				$skill_count = 0;
				foreach ($specializations as $key => $value) {
					$skill_count++;
					$term_id 	= !empty($value['spec'][0]) ? $value['spec'][0] : '';
					$specializations_list[$skill_count]['title'] 		= !empty($term_id) ? workreap_get_term_name($term_id, 'wt-specialization') : '';
					$specializations_list[$skill_count]['skill'] 		= !empty($value['value']) ? $value['value'] : '';
					$specializations_list[$skill_count]['key'] 			= $term_id;
				}
			}

			$industrial_experiences_list	= array();
			if (!empty($industrial_experiences) && is_array($industrial_experiences)) {
				$skill_count = 0;
				foreach ($industrial_experiences as $key => $value) {
					$skill_count++;
					$term_id 	= !empty($value['exp'][0]) ? $value['exp'][0] : '';
					$industrial_experiences_list[$skill_count]['title'] 		= !empty($term_id) ? workreap_get_term_name($term_id, 'wt-industrial-experience') : '';
					$industrial_experiences_list[$skill_count]['skill'] 		= !empty($value['value']) ? $value['value'] : '';
					$industrial_experiences_list[$skill_count]['key'] 			= $term_id;
				}
			}

			$educations	= array();
			if (!empty($education)) {
				$counter = 0;
				foreach ($education as $key => $value) {
					if (!empty($value['institute'])) {
						$period 								= '';
						$educations[$counter]['title']          = $value['title'];
						$educations[$counter]['institute']      = $value['institute'];
						$educations[$counter]['title'] 			= !empty($value['title']) ? stripslashes($value['title']) : '';
						$educations[$counter]['institute']  	= !empty($value['institute']) ? stripslashes($value['institute']) : '';
						$educations[$counter]['description'] 	= !empty($value['description']) ? wp_kses_post(stripslashes($value['description'])) : '';

						$startdate 		= !empty($value['startdate']) ? str_replace('/', '-', $value['startdate']) : '';
						$enddate 		= !empty($value['enddate']) ? str_replace('/', '-', $value['enddate']) : '';
						$start_date		= !empty($startdate) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix', $startdate))) : '';
						$end_date 		= !empty($enddate) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix', $enddate))) : '';

						$educations[$counter]['startdate']  	= $startdate;
						$educations[$counter]['enddate']  		= $enddate;


						if (empty($end_date)) {
							$end_date = '';
						} else {
							$end_date	= ' - ' . $end_date;
						}

						if (!empty($start_date)) {
							$period = $start_date . $end_date;
						}
						$educations[$counter]['period'] 			= $period;
						$counter++;
					}
				}
			}

			$experiences	= array();
			if (!empty($experience)) {
				$counter = 0;
				foreach ($experience as $key => $value) {
					if (!empty($value['title'])) {
						$period 							= '';
						$experiences[$counter]['title']     = !empty($value['title']) ? stripslashes($value['title']) : '';
						$experiences[$counter]['company']   = !empty($value['company']) ? stripslashes($value['company']) : '';

						$startdate 		= !empty($value['startdate']) ? str_replace('/', '-', $value['startdate']) : '';
						$enddate 		= !empty($value['enddate']) ? str_replace('/', '-', $value['enddate']) : '';

						$description 	= !empty($value['details']) ? wp_kses_post(stripslashes($value['details'])) : '';
						$start_date		= !empty($startdate) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix', $startdate))) : '';
						$end_date 		= !empty($enddate) ? date_i18n('F Y', strtotime(apply_filters('workreap_date_format_fix', $enddate))) : '';
						$experiences[$counter]['startdate']  	= $startdate;
						$experiences[$counter]['enddate']  		= $enddate;
						if (empty($end_date)) {
							$end_date = '';
						} else {
							$end_date	= ' - ' . $end_date;
						}

						if (!empty($start_date)) {
							$period = $start_date . $end_date;
						}
						$experiences[$counter]['period'] 			= $period;
						$counter++;
					}
				}
			}

			$json['education']		= maybe_unserialize($educations);
			$json['experience']		= maybe_unserialize($experiences);
			$json['projects']		= maybe_unserialize($projects_array);
			$json['videos']			= maybe_unserialize($videos);
			$json['awards']			= maybe_unserialize($awards_array);
			$json['specializations']			= array_values($specializations_list);
			$json['industrial_experiences']		= array_values($industrial_experiences_list);

			return new WP_REST_Response($json, 200);
		}

		/**
		 * Get invoice detail
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_invoice_detail($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

			$order_id		= !empty($request['invoice_id']) ? intval($request['invoice_id']) : 0;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$user_type		= apply_filters('workreap_get_user_type', $user_id);

			$json = $items = array();
			$billing_address	= '';
			$date_format		= get_option('date_format');
			$order      		= wc_get_order($order_id);
			$data_created		= $order->get_date_created();
			$get_date_paid		= $order->get_date_paid();
			$get_total			= $order->get_total();
			$get_taxes			= $order->get_taxes();
			$get_subtotal		= $order->get_subtotal();
			$billing_address	= $order->get_formatted_billing_address();

			$project_title	= '';
			$details		= array();
			$counter		= 0;
			$payment_type_title	= esc_html__('Project title:', 'workreap_api');

			if (!empty($order->get_items())) {
				foreach ($order->get_items() as $item_id => $item) {
					$counter++;
					$total 				= $item->get_total();
					$tax 				= $item->get_subtotal_tax();
					$woo_product_data	= $item->get_meta('cus_woo_product_data', true);
					$payment_type		= $item->get_meta('payment_type', true);
					$processing_fee		= !empty($woo_product_data['processing_fee']) ? $woo_product_data['processing_fee'] : 0.0;

					$project_title		= $item->get_name();
					$employer_id		= $item->get_meta('employer_id', true);
					$current_project	= $item->get_meta('current_project', true);
					$addons				= '';
					$freelancer_id		= $item->get_meta('freelancer_id', true);
					$to_billing_address	= !empty($freelancer_id) ? esc_html(workreap_user_billing_address($freelancer_id)) : '';

					if (!empty($current_project)) {
						$project_title	= get_the_title($current_project);
						if (!empty($woo_product_data['addons'])) {
							foreach ($woo_product_data['addons'] as $key => $service_item) {
								$addons	.= get_the_title($key);
							}
						}

						if (!empty($woo_product_data['milestone_id'])) {
							$addons	.= get_the_title($woo_product_data['milestone_id']);
						}
					} elseif (!empty($woo_product_data['project_id'])) {
						$project_title	= get_the_title($woo_product_data['project_id']);

						if (!empty($woo_product_data['milestone_id'])) {
							$addons	.= get_the_title($woo_product_data['milestone_id']);
						}
					} elseif (!empty($woo_product_data['service_id'])) {
						$project_title	= get_the_title($woo_product_data['service_id']);
						if (!empty($woo_product_data['addons'])) {
							foreach ($woo_product_data['addons'] as $key => $service_item) {
								$addons	.= get_the_title($key);
							}
						}
					}

					if (!empty($payment_type) && $payment_type === 'subscription') {
						$payment_type_title	= esc_html__('Package:', 'workreap_api');
					} else if (!empty($payment_type) && $payment_type === 'hiring_service') {
						$payment_type_title	= esc_html__('Service title:', 'workreap_api');
					} else if (!empty($payment_type) && $payment_type === 'milestone') {
						$payment_type_title	= esc_html__('Milestone title:', 'workreap_api');
					} else if (!empty($payment_type) && $payment_type === 'hiring') {
						$payment_type_title	= esc_html__('Project title:', 'workreap_api');
					}

					$details['type_title']		= $payment_type_title;
					$details['title']			= $project_title;
					$details['desc']			= $addons;
					$details['counter']			= $counter;
					$details['cost']			= workreap_price_format($total, 'return');
					$details['taxes']			= workreap_price_format($tax, 'return');
					$details['amount']			= workreap_price_format($total, 'return');
					$details['process_fee']		= workreap_price_format($processing_fee, 'return');
				}
			}

			$admin_shares		= $item->get_meta('admin_shares', true);
			$freelancer_shares	= $item->get_meta('freelancer_shares', true);
			$from_billing_address		= !empty($user_id) ? workreap_user_billing_address($user_id) : '';

			if (function_exists('fw_get_db_settings_option')) {
				$main_logo = fw_get_db_settings_option('main_logo');
			}

			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			if (!empty($main_logo['url'])) {
				$logo = $main_logo['url'];
			} else {
				$logo = get_template_directory_uri() . '/images/logo.png';
			}

			if (!empty($get_subtotal)) {
				$items['subtotal']			= workreap_price_format($get_subtotal, 'return');
			}

			if (!empty($get_taxes)) {
				$items['taxes']				= workreap_price_format($get_taxes, 'return');
			}

			if (!empty($user_type) && $user_type === 'freelancer') {
				if (!empty($admin_shares)) {
					$items['admin_fee']	= workreap_price_format($admin_shares, 'return');
				}
				if (!empty($freelancer_shares)) {
					$items['total']	= workreap_price_format($freelancer_shares, 'return');
				}

				if (function_exists('fw_get_db_settings_option')) {
					$invoice_address 	= fw_get_db_settings_option('invoice_address');
					$billing_address	= !empty($invoice_address) ? $invoice_address : $billing_address;
				}
			} else if (!empty($user_type) && $user_type === 'employer') {
				$items['total']				= workreap_price_format($get_total, 'return');
			}

			$items['created_date']			= !empty($data_created) ? date($date_format, strtotime($data_created)) : '';
			$items['main_logo']				= $logo;
			$items['invoice_details']		= $details;
			$items['from_billing_address']	= $from_billing_address;
			$items['billing_address']		= $billing_address;
			$items['billing_to']			= esc_html($to_billing_address);
			return new WP_REST_Response($items, 200);
		}

		/**
		 * Get invoice listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_invoice_listings($request)
		{
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$offset 		= ($page_number - 1) * $limit;

			$json		= array();
			$items		= array();
			$user_type		= apply_filters('workreap_get_user_type', $user_id);
			$date_format	= get_option('date_format');
			$price_symbol	= workreap_get_current_currency();
			if (class_exists('WooCommerce')) {
				if (!empty($user_type) && $user_type === 'freelancer') {
					$customer_orders = wc_get_orders(
						apply_filters(
							'woocommerce_my_account_my_orders_query',
							array(
								'page' 			 	=> $page_number,
								'paginate' 		  	=> true,
								'limit' 			=> $limit,
								'freelancer_id'     => $user_id,
							)
						)
					);
				} else {
					$customer_orders = wc_get_orders(
						apply_filters(
							'woocommerce_my_account_my_orders_query',
							array(
								'customer' 	=> $user_id,
								'page' 		=> $page_number,
								'paginate' 	=> true,
								'limit' 	=> $limit,
							)
						)
					);
				}

				if (!empty($customer_orders->orders)) {
					$count_post 	= count($customer_orders->orders);
					foreach ($customer_orders->orders as $customer_order) {
						$order      	= wc_get_order($customer_order);
						$data_created	= $order->get_date_created();
						$actions 		= wc_get_account_orders_actions($order);

						$item[] = array(
							'post_id' => intval($order->get_id()),
							'created_date' => date($date_format, strtotime($data_created)),
							'price' => html_entity_decode(workreap_price_format($order->get_total(), 'return')),
							'actions' => array_values($actions),
						);
					}

					$json['type']		= 'success';
					$json['invoices'] = maybe_unserialize($item);
					return new WP_REST_Response($json, 200);
				} else {
					$json['type']		= 'error';
					$json['message']	= esc_html__('No orders found', 'workreap_api');
					$json['invoices'] = array();
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Please install WooCommerce', 'workreap_api');
				return new WP_REST_Response($items, 203);
			}
		}


		/**
		 * Download invoices
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function download_invoice($data)
		{
			require WorkreapGlobalSettings::get_plugin_path() . 'libraries/dompdf/autoload.inc.php';
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_identity	= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$order_id		= !empty($request['order_id']) ? intval($request['order_id']) : 0;

			$json = $actual_link = array();

			if (!empty($order_id) && !empty($user_identity) && class_exists('WooCommerce')) {

				$profile_id = workreap_get_linked_profile_id($user_identity);
				$user_type	= apply_filters('workreap_get_user_type', $user_identity);

				$is_verified 	= get_post_meta($profile_id, '_is_verified', true);
				$is_verified    = !empty($is_verified) ? $is_verified : '';

				$dashboard_args   = array(
					'user_identity' => intval($user_identity),
					'profile_id'    => intval($profile_id),
					'user_type'     => $user_type,
					'ref'     		=> 'invoices',
					'mode'          => 'detail',
					'id'            => $order_id,
					'is_verified'   => $is_verified,
					'pdfDownload'	=> 1
				);

				ob_start();
				do_action('workreap_api_pdf_download', $dashboard_args);
				$invoice_body   = ob_get_clean();
				$dompdf = new Dompdf\Dompdf;
				$file_name  = 'invoice-' . $user_identity . '.pdf';
				$border_image = get_template_directory() . '/images/border.jpg';

				$html = '<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>' . esc_html__('Invoice', 'workreap_api') . ' # ' . $user_identity . '</title>
					<style>
						@page {
							margin: 10px 0px 50px 0px;
						}

						header {
							position: fixed;
							top: -20px;
							left: 0px;
							right: 0px;
							height: 50px;
							font-family: sans-serif;
							background: url(' . $border_image . ');
							background-size:1px;
							background-size: 100% 2px;
							background-repeat: no-repeat;
						}
		
						footer {
							position: fixed; 
							bottom: -60px; 
							left: 0px; 
							right: 0px;
							height: 50px; 
						}
						table { border-collapse: collapse; }
					</style>
					</head>
					<body>';
				$html .= $invoice_body;
				$html .= '
					</body>
				</html>';

				$dompdf->loadHtml($html);
				$dompdf->setPaper('A4', 'portrait');
				//$dompdf->render();

				$upload             = wp_upload_dir();
				$upload_dir         = $upload['basedir'];
				$upload_rel_dir     = $upload['baseurl'] . '/invoices/';
				$upload_dir         = $upload_dir . '/invoices/';

				//create directory if not exists
				if (!is_dir($upload_dir)) {
					wp_mkdir_p($upload_dir);
				}

				$filename   = rand(100, 2500) . $order_id . date('Y-m-d-H-i-s') . '.pdf';
				$file_name  = $upload_dir . $filename;
				$file_url   = $upload_rel_dir . $filename;

				if (ob_get_contents()) {
					ob_end_flush();
				}

				$pdf_gen = $dompdf->output();

				if (!file_put_contents($file_name, $pdf_gen)) {
					$actual_link = array(
						'file_path' => '',
						'file_url'  => ''
					);
				} else {
					$actual_link = array(
						'file_path' => $file_name,
						'file_url'  => $file_url
					);
				}

				$json['type'] 	 	= 'success';
				$json['title']		= esc_html__('Invoice download', 'workreap_api');
				$json['current_url'] 		= $actual_link;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 	 		= 'error';
				$json['title']			= esc_html__('Failed!', 'workreap_api');
				$json['message']		= esc_html__('Something went wrong!', 'workreap_api');
				$json['current_url'] 	= $actual_link;
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Remove invoice after download
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function remove_invoice_file($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_identity	= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$file_path		= !empty($request['file_path']) ? ($request['file_path']) : '';

			if (empty($user_identity)) {
				$json['type'] 		= 'error';
				$json['title']      = esc_html__('Oops!', 'workreap_api');
				$json['message'] 	= esc_html__('User Id is missing!', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if (!empty($file_path)) {
				wp_delete_file($file_path);
				$json['type'] 	 	= 'success';
				$json['title']		= esc_html__('Success', 'workreap_api');
				$json['message']	= esc_html__('File removed successfully!', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 	 	= 'error';
				$json['title']		= esc_html__('Error', 'workreap_api');
				$json['message']	= esc_html__('Something went wrong!', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get dispute listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_dispute_listings($request)
		{
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$offset 		= ($page_number - 1) * $limit;
			$json			= array();
			$items			= array();
			$post_id 		= workreap_get_linked_profile_id($user_id);
			$user_type		= apply_filters('workreap_get_user_type', $user_id);
			$order 			= 'DESC';
			$sorting 		= 'ID';

			$args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'disputes',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('publish', 'pending'),
				'author' 			=> $user_id,
				'paged' 		 	=> $page_number,
				'suppress_filters'  => false
			);

			$query 			= new WP_Query($args);
			$count_post 	= $query->found_posts;
			if ($query->have_posts()) {

				while ($query->have_posts()) : $query->the_post();
					global $post;
					$post_project		= get_post_meta($post->ID, '_dispute_project', true);
					$post_status		= get_post_status($post->ID);

					if ($post_status === 'publish') {
						$post_status	= esc_html__('Resolved', 'workreap_api');
					} elseif ($post_status === 'pending') {
						$post_status	= esc_html__('Pending', 'workreap_api');
					}

					$project_title	= esc_html__('NILL', 'workreap_api');
					if (!empty($post_project)) {
						$project_title	= get_the_title($post_project);
					}
					$feedback   			= fw_get_db_post_option($post->ID, 'feedback');
					$item['count_totals']   = !empty($count_post) ? intval($count_post) : 0;
					$item['post_id']		= $post->ID;
					$item['feedback']		= !empty($feedback) ? $feedback : '';
					$item['title']			= get_the_title();
					$item['project_title']	= $project_title;
					$item['post_status']	= $post_status;
					$items[]			    = maybe_unserialize($item);
				endwhile;
				wp_reset_postdata();

				$json['type']		= 'success';
				$json['message']	= esc_html__('List of disputes', 'workreap_api');
				$json['list']		= $items;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('No disputes found', 'workreap_api');
				$json['list']		= $items;
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Create dispute
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function create_dispute($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json				= array();
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$get_user_type		= apply_filters('workreap_get_user_type', $user_id);

			$fields	= array(
				'project' 		=> esc_html__('No project/service is selected', 'workreap_api'),
				'reason' 		=> esc_html__('Please select the reason', 'workreap_api'),
				'description' 	=> esc_html__('Please add dispute description', 'workreap_api'),
			);

			foreach ($fields as $key => $item) {
				if (empty($request[$key])) {
					$json['type'] 	 = "error";
					$json['message'] = $item;
					return new WP_REST_Response($json, 203);
				}
			}

			//Create dispute
			$username   	= workreap_get_username($user_id);
			$linked_profile = workreap_get_linked_profile_id($user_id);
			$project      	= sanitize_text_field($request['project']);
			$title      	= sanitize_text_field($request['reason']);
			$description    = !empty($request['description']) ? ($request['description']) : '';
			$list			= workreap_project_ratings('dispute_options');
			$dispute_title  = !empty($list[$title]) ? $list[$title] : rand(1, 9999);
			$get_post_type	= get_post_type($project);


			$dispute_args = array(
				'posts_per_page' => -1,
				'post_type' 		=> array('disputes'),
				'orderby' 			=> 'ID',
				'order' 			=> 'DESC',
				'post_status' 		=> array('pending', 'publish'),
				'author' 			=> $user_id,
				'suppress_filters'  => false,
				'meta_query'		=> array(
					'relation' 		=> 'AND',
					array(
						'key' 			=> '_dispute_project',
						'value' 			=> $project,
						'compare' 		=> '='
					)
				)
			);

			$dispute_is = get_posts($dispute_args);
			if (!empty($dispute_is)) {
				$json['type'] = "error";
				$json['message'] = esc_html__("You have already submitted the dispute against this project.", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$project_id		= get_post_meta($project, '_project_id', true);
			$dispute_against	= '';

			if (!empty($get_user_type) && $get_user_type === 'freelancer') {
				if (!empty($get_post_type) && ($get_post_type === 'services-orders')) {
					$postdata 			= get_post($project);
					$project_author		= $postdata->post_author;
					$dispute_against   	= workreap_get_username($project_author);

					$author_data            = get_userdata($project_author);
					$dispute_email_to       = $author_data->data->user_email;

					$service_id				= get_post_meta($postdata->ID, '_service_id', true);
					$dispute_project_title	= get_the_title($service_id);
					$dispute_project_link	= get_the_permalink($service_id);
				} else if (!empty($get_post_type) && ($get_post_type === 'proposals')) {
					$project_id			= get_post_meta($project, '_project_id', true);
					$postdata 			= get_post($project_id);
					$project_author		= $postdata->post_author;
					$dispute_against   	= workreap_get_username($project_author);

					$author_data            = get_userdata($project_author);
					$dispute_email_to       = $author_data->data->user_email;
					$dispute_project_title	= $postdata->post_title;
					$dispute_project_link	= get_the_permalink($postdata->ID);
				}
			} else if (!empty($get_user_type) && $get_user_type === 'employer') {
				if (!empty($get_post_type) && ($get_post_type === 'services-orders')) {
					$service_author		= get_post_meta($project, '_service_author', true);
					$dispute_against   	= workreap_get_username($service_author);
					$author_data            = get_userdata($service_author);
					$dispute_email_to  = $author_data->data->user_email;

					$service_id				= get_post_meta($project, '_service_id', true);
					$dispute_project_title	= get_the_title($service_id);
					$dispute_project_link	= get_the_permalink($service_id);
				} else if (!empty($get_post_type) && ($get_post_type === 'proposals')) {
					$project_id			= get_post_meta($project, '_project_id', true);
					$postdata 			= get_post($project);
					$project_author		= $postdata->post_author;
					$dispute_against   	= workreap_get_username($project_author);

					$author_data            = get_userdata($project_author);
					$dispute_email_to       = $author_data->data->user_email;
					$dispute_project_title	= $postdata->post_title;
					$dispute_project_link	= get_the_permalink($postdata->ID);
				}
			}

			$dispute_post  = array(
				'post_title'    => wp_strip_all_tags($dispute_title), //proposal title
				'post_status'   => 'pending',
				'post_content'  => $description,
				'post_author'   => $user_id,
				'post_type'     => 'disputes',
			);

			$dispute_id    		= wp_insert_post($dispute_post);
			update_post_meta($dispute_id, '_send_by', $user_id);
			update_post_meta($dispute_id, '_dispute_key', $title);
			update_post_meta($dispute_id, '_dispute_project', $project); //propsal ID
			update_post_meta($dispute_id, '_project_id', $project_id);
			update_post_meta($project, 'dispute', 'yes');

			$post_type_object = get_post_type_object('proposals');
			$link = !empty($post_type_object->_edit_link) ? admin_url(sprintf($post_type_object->_edit_link . '&action=edit', $project)) : '';

			//Send email to user
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapSendDispute')) {
					$email_helper = new WorkreapSendDispute();
					$emailData = array();
					$emailData['project_link']  	= $link;
					$emailData['project_title']  	= get_the_title($project);
					$emailData['user_name']  		= $username;
					$emailData['user_link']     	= get_the_permalink($linked_profile);
					$emailData['message']      		= $description;
					$emailData['dispute_subject']   = $dispute_title;
					$emailData['dispute_author']    = $username;
					$emailData['dispute_against']   = $dispute_against;
					$emailData['dispute_email_to']  = $dispute_email_to;

					$email_helper->send($emailData);

					$emailData['project_link']  	= !empty($dispute_project_link) ?  $dispute_project_link : get_the_permalink($project);
					$emailData['project_title']  	= !empty($dispute_project_title) ?  $dispute_project_title : get_the_title($project);
					$email_helper->dispute_notify($emailData);
				}
			}

			//Push notification
			$push	= array();

			$push['sender_id']			= $user_id;
			$push['dispute_against']	= !empty($author_data->data->ID) ? $author_data->data->ID : 0;
			$push['project_id']			= !empty($project_id) ? $project_id : 0;
			$push['message']			= $description;

			$push['%dispute_author%']	= workreap_get_username($user_id);
			$push['%dispute_against%']	= !empty($author_data->data->ID) ? workreap_get_username($author_data->data->ID) : 0;
			$push['%message%']			= $description;
			$push['%replace_message%']	= $description;
			$push['%project_link%']		= $dispute_project_link;
			$push['%project_title%']	= $dispute_project_title;
			$push['type']				= 'dispute';

			do_action('workreap_user_push_notify', array($push['dispute_against']), '', 'pusher_dispute_user_content', $push);

			$json['type'] = "success";
			$json['message'] = esc_html__("We have received your dispute, soon we will get back to you.", 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Remove Account
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function dispute_form($request)
		{
			$json	= array();

			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$user_type			= apply_filters('workreap_get_user_type', $user_id);
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			$list			 	= workreap_project_ratings('dispute_options');

			if (!empty($user_type) && $user_type === 'freelancer') {
				$dispute_args = array(
					'posts_per_page' => -1,
					'post_type' 		=> array('proposals'),
					'orderby' 			=> 'ID',
					'order' 			=> 'DESC',
					'author'			=> $user_id,
					'post_status' 		=> array('publish', 'cancelled'),
					'suppress_filters'  => false,
					'meta_query'		=> array(
						'relation' => 'AND',
						array(
							'key' 			=> '_send_by',
							'value' 			=> $linked_profile,
							'compare' 		=> '='
						),
						array(
							'key' => 'dispute',
							'compare' => 'NOT EXISTS'
						),
					)
				);

				$dispute_service_args = array(
					'posts_per_page' => -1,
					'post_type' 		=> array('services-orders'),
					'orderby' 			=> 'ID',
					'order' 			=> 'DESC',
					'post_status' 		=> array('cancelled', 'hired'),
					'suppress_filters'  => false,
					'meta_query'		=> array(
						array(
							'key' 			=> '_service_author',
							'value' 			=> $user_id,
							'compare' 		=> '='
						),
						array(
							'key' => 'dispute',
							'compare' => 'NOT EXISTS'
						),
					)
				);
			} else {
				$dispute_args = array(
					'posts_per_page' => -1,
					'post_type' 		=> array('proposals'),
					'orderby' 			=> 'ID',
					'order' 			=> 'DESC',
					'post_status' 		=> array('cancelled'),
					'suppress_filters'  => false,
					'meta_query'		=> array(
						'relation' 		=> 'AND',
						array(
							'key' 			=> '_employer_user_id',
							'value' 			=> $user_id,
							'compare' 		=> '='
						),
						array(
							'key' 		=> 'dispute',
							'compare' 	=> 'NOT EXISTS'
						),
					)
				);

				$dispute_service_args = array(
					'posts_per_page' => -1,
					'post_type' 		=> array('services-orders'),
					'orderby' 			=> 'ID',
					'order' 			=> 'DESC',
					'post_status' 		=> array('cancelled'),
					'author' 			=> $user_id,
					'suppress_filters'  => false,
					'meta_query'		=> array(
						array(
							'key' 		=> 'dispute',
							'compare' 	=> 'NOT EXISTS'
						),
					)
				);
			}


			$dispute_query 				= get_posts($dispute_args);
			$dispute_service_query 		= get_posts($dispute_service_args);

			if (!empty($dispute_query) && !empty($dispute_service_query)) {
				$dispute_query	= array_merge($dispute_query, $dispute_service_query);
			} else if (empty($dispute_query) && !empty($dispute_service_query)) {
				$dispute_query	= $dispute_service_query;
			} else if (!empty($dispute_query) && empty($dispute_service_query)) {
				$dispute_query	= $dispute_query;
			} else {
				$dispute_query	= array();
			}

			$json['options']		= $list;
			$json['dispute_query']	= array_values($dispute_query);

			return new WP_REST_Response($json, 200);
		}

		/**
		 * Remove Account
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function delete_account($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json 				= array();
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			if (!empty($user_id)) {
				$email				= !empty($request['email']) ? sanitize_email($request['email']) : '';
				$password			= !empty($request['password']) ? sanitize_text_field($request['password']) : '';
				$retype				= !empty($request['retype']) ? sanitize_text_field($request['retype']) : '';
				$reason				= !empty($request['reason']) ? sanitize_text_field($request['reason']) : '';
				$description		= !empty($request['description']) ? sanitize_textarea_field($request['description']) : '';
				$registration_type	= !empty($request['registration_type']) ? sanitize_textarea_field($request['registration_type']) : '';
				$user				= get_userdata($user_id);

				if (empty($user)) {
					$json['type'] = 'error';
					$json['message'] = esc_html__('User not exist', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
				$user_name 	 	= $user->user_login;
				$user_email	 	= $user->user_email;
				$post_id		= workreap_get_linked_profile_id($user_id);

				if (strtolower($user_email) != strtolower($email) && $registration_type === 'social') {
					$json['type'] = 'error';
					$json['message'] = esc_html__('Something went wrong!', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				if ($registration_type === 'social' && !empty($email)) {
					if (empty($reason)) {
						$json['type'] = 'error';
						$json['message'] = esc_html__('Reason field is missing.', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				} else {
					if (empty($password) || empty($retype) || empty($reason)) {
						$json['type'] = 'error';
						$json['message'] = esc_html__('Some fields are missing.', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}

				$is_password 	= wp_check_password($password, $user->user_pass, $user_id);
				if ($registration_type === 'social' && !empty($email)) {
					$is_password = true;
				}

				if ($is_password) {
					require_once(ABSPATH . 'wp-admin/includes/user.php');
					wp_delete_user($user_id);
					wp_delete_post($post_id, true);

					$reason = workreap_get_account_delete_reasons($reason);

					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapDeleteAccount')) {
							$email_helper = new WorkreapDeleteAccount();
							$emailData = array();

							$emailData['username'] 			= sanitize_text_field($user_name);
							$emailData['reason'] 			= sanitize_text_field($reason);
							$emailData['email'] 			= sanitize_email($user_email);
							$emailData['description'] 		= sanitize_textarea_field($description);
							$email_helper->send($emailData);
						}
					}

					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('You account has been deleted.', 'workreap_api');

					return new WP_REST_Response($json, 200);
				} else {
					$json['type'] = 'error';
					$json['message'] = esc_html__('Password doesn\'t match', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('User id is required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Update password
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_password($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$old_password		= !empty($request['password']) ? sanitize_text_field($request['password']) : '';
			$password			= !empty($request['retype']) ? sanitize_text_field($request['retype']) : '';
			$json				= array();

			if (empty($old_password) || empty($password)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Current and new password fields are required.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$user_info 	= get_userdata($user_id);
			$user_pass	= !empty($user_info->user_pass) ? $user_info->user_pass : '';

			$is_password 	= wp_check_password($old_password, $user_pass, $user_id);
			$account_types_permissions	= '';
			if (function_exists('fw_get_db_settings_option')) {
				$account_types_permissions 	= fw_get_db_settings_option('account_types_permissions', $default_value = null);
			}
			if ($is_password) {

				if (empty($old_password)) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Please add your new password.', 'workreap_api');
					return new WP_REST_Response($json, 203);
				} else {
					$switch_user_id	= get_user_meta($user_id, 'switch_user_id', true);
					$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';

					if (!empty($switch_user_id) && !empty($account_types_permissions) && $account_types_permissions == 'yes') {
						wp_update_user(array('ID' => $switch_user_id, 'user_pass' => sanitize_text_field($password)));
					}
					wp_update_user(array('ID' => $user_id, 'user_pass' => $password));
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Password Updated.', 'workreap_api');
					return new WP_REST_Response($json, 200);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Old Password doesn\'t matched with the existing password', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Account setting
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_account_settings($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json				= array();
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			$user_type	 	 	= apply_filters('workreap_get_user_type', $user_id);
			$settings			= function_exists('workreap_get_account_settings') ? workreap_get_account_settings($user_type) : array();
			$current_settings	= array();

			if (!empty($settings)) {
				foreach ($settings as $key => $value) {
					update_post_meta($linked_profile, $key, $request[$key]);
				}
			}

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Account is updated successfully.', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Get Billing setting
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_billing_settings($request)
		{
			$json				= array();
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$billing_first_name	= get_user_meta($user_id, 'billing_first_name', true);
			$billing_last_name	= get_user_meta($user_id, 'billing_last_name', true);
			$billing_company	= get_user_meta($user_id, 'billing_company', true);
			$billing_address_1	= get_user_meta($user_id, 'billing_address_1', true);
			$billing_country	= get_user_meta($user_id, 'billing_country', true);
			$billing_city		= get_user_meta($user_id, 'billing_city', true);
			$billing_postcode	= get_user_meta($user_id, 'billing_postcode', true);
			$billing_phone		= get_user_meta($user_id, 'billing_phone', true);
			$billing_email		= get_user_meta($user_id, 'billing_email', true);

			$json['first_name']	= !empty($billing_first_name) ? $billing_first_name : '';
			$json['last_name']	= !empty($billing_last_name) ? $billing_last_name : '';
			$json['company']	= !empty($billing_company) ? $billing_company : '';
			$json['address_1']	= !empty($billing_address_1) ? $billing_address_1 : '';
			$json['country']	= !empty($billing_country) ? $billing_country : '';
			$json['city']		= !empty($billing_city) ? $billing_city : '';
			$json['postcode']	= !empty($billing_postcode) ? $billing_postcode : '';
			$json['phone']		= !empty($billing_phone) ? $billing_phone : '';
			$json['email']		= !empty($billing_email) ? $billing_email : '';

			return new WP_REST_Response($json, 200);
		}

		/**
		 * Get Account setting
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_account_settings($request)
		{
			$json	= array();
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			$user_type	 	 	= apply_filters('workreap_get_user_type', $user_id);
			$settings			= function_exists('workreap_get_account_settings') ? workreap_get_account_settings($user_type) : array();
			$current_settings	= array();

			if (!empty($settings)) {
				foreach ($settings as $key => $value) {
					$db_value	= get_post_meta($linked_profile, $key, true);
					$json[$key]['db_value']	= !empty($db_value) ?  $db_value : 'off';
					$json[$key]['key']		= $key;
					$json[$key]['text']		= $value;
				}

				$current_settings['account_settings']	= array_values($json);
			}

			return new WP_REST_Response($current_settings, 200);
		}

		/**
		 * Update payout settings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_payout_listings($request)
		{
			global $wpdb;
			$json	= array();
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';

			$payments			= workreap_get_payments_workreap($user_id);
			$table_name 		= $wpdb->prefix . "wt_payouts_history";
			$earning_sql		= "SELECT * FROM $table_name where ( user_id =" . $user_id . " AND status= 'completed')";
			$total_query 		= "SELECT COUNT(1) FROM (${earning_sql}) AS combined_table";
			$total 				= $wpdb->get_var($total_query);
			$total				= !empty($total) ? intval($total) : 0;
			$items_per_page 	= get_option('posts_per_page');
			$page 				= isset($request['page']) ? abs((int) $request['page']) : 1;
			$offset 			= ($page * $items_per_page) - $items_per_page;
			$payments 			= $wpdb->get_results($earning_sql . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}");
			$total_pages		= ceil($total / $items_per_page);
			$date_formate		= get_option('date_format');
			$payrols_list		= workreap_get_payouts_lists();
			$payments			= !empty($payments) ? maybe_unserialize($payments) : array();


			$payment_list	= array();

			if (!empty($payments)) {
				foreach ($payments  as $key => $item) {
					$list_item	= !empty($item) ? maybe_unserialize($item) : array();
					if (!empty($list_item)) {
						foreach ($list_item as $ukey => $data) {
							$payment_list[$key][$ukey]	= !empty($data) ? maybe_unserialize($data) : array();
						}
					}
				}
			}

			$json['payment_list'] 	 = !empty($payment_list) ? maybe_unserialize($payment_list) : array();
			$json['payout_list'] 	 = $payrols_list;
			$json['total'] 	 		 = $total;
			$json['type'] 	 		= 'success';
			$json['message'] 		= esc_html__('Payout settings updated', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Update payout settings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_payout_setting($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json	= array();
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$data 			= array();
			$payrols		= workreap_get_payouts_lists();
			$fields			= !empty($payrols[$request['payout_settings']['type']]['fields']) ? $payrols[$request['payout_settings']['type']]['fields'] : array();

			if (!empty($fields)) {
				foreach ($fields as $key => $field) {
					if ($field['required'] === true && empty($request['payout_settings'][$key])) {
						$json['type'] 		= 'error';
						$json['message'] 	= $field['message'];
						return new WP_REST_Response($json, 203);
					}
				}
			}

			update_user_meta($user_id, 'payrols', $request['payout_settings']);

			$json['type'] 	 = 'success';
			$json['message'] = esc_html__('Payout settings updated', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * update brochures settings 
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_payout_setting($request)
		{
			$json 			= array();
			$payrols		= array();
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$contents		= get_user_meta($user_id, 'payrols', true);
			if (function_exists('workreap_get_payouts_lists')) {
				$payrols		= workreap_get_payouts_lists();
			}

			if (!empty($payrols)) {
				foreach ($payrols as $key => $value) {
					if ($key === 'paypal') {
						$paypal_email = !empty($contents['paypal_email']) ? $contents['paypal_email'] : '';
						$payrols['paypal']['fields']['paypal_email']['value'] = $paypal_email;
					} elseif ($key === 'payoneer') {
						$payoneer_email = !empty($contents['payoneer_email']) ? $contents['payoneer_email'] : '';
						$payrols['payoneer']['fields']['payoneer_email']['value'] = $payoneer_email;
					} elseif ($key === 'bacs') {
						$bank_account_name 		= !empty($contents['bank_account_name']) ? $contents['bank_account_name'] : '';
						$bank_account_number 	= !empty($contents['bank_account_number']) ? $contents['bank_account_number'] : '';
						$bank_name 				= !empty($contents['bank_name']) ? $contents['bank_name'] : '';
						$bank_routing_number 	= !empty($contents['bank_routing_number']) ? $contents['bank_routing_number'] : '';
						$bank_iban 				= !empty($contents['bank_iban']) ? $contents['bank_iban'] : '';
						$bank_bic_swift 		= !empty($contents['bank_bic_swift']) ? $contents['bank_bic_swift'] : '';

						$payrols['bacs']['fields']['bank_account_name']['value'] = $bank_account_name;
						$payrols['bacs']['fields']['bank_account_number']['value'] = $bank_account_number;
						$payrols['bacs']['fields']['bank_name']['value'] = $bank_name;
						$payrols['bacs']['fields']['bank_routing_number']['value'] = $bank_routing_number;
						$payrols['bacs']['fields']['bank_iban']['value'] = $bank_iban;
						$payrols['bacs']['fields']['bank_bic_swift']['value'] = $bank_bic_swift;
					}
				}
			}

			$json['options']			= !empty($contents) && is_array($contents) ? 'yes' : '';
			$json['payout_settings']	= $payrols;
			$json['saved_settings']		= $contents;

			$json['type'] = 'success';
			$json['message'] = esc_html__('Payout settings', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Get freelancer availble balance
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_freelancer_availble_balance($request)
		{
			$json 		= array();
			$linked_profile			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$payrols		= array();
			if (function_exists('workreap_get_payouts_lists')) {
				$payrols		= workreap_get_payouts_lists();
			}


			$current_balance	= workreap_get_sum_earning_freelancer($linked_profile, 'hired', 'freelancer_amount');
			$current_balance	= !empty($current_balance) ? $current_balance : 0;

			$total_pending		= workreap_sum_freelancer_withdraw(array('publish', 'pending'), intval($linked_profile));
			$total_pending		= !empty($total_pending) ? floatval($total_pending) : 0;

			$totalamount    	= workreap_sum_user_earning('completed', 'freelancer_amount', intval($linked_profile));

			$available_balance	= 0;
			if (!empty($totalamount->total_amount)) {
				$balance_remaining	= floatval($totalamount->total_amount) - floatval($total_pending);
				$available_balance    = !empty($balance_remaining) && $balance_remaining > 0  ? floatval($totalamount->total_amount) - floatval($total_pending) : 0;
			}


			$json['type'] = 'success';
			$json['title'] = esc_html__('Success', 'workreap_api');
			$json['message'] = esc_html__('Available balance', 'workreap_api');
			$json['available_balance'] = $available_balance;
			$json['current_balance'] = $current_balance;
			return new WP_REST_Response($json, 200);
		}

		/**
		 * get user payout
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_users_payouts($request)
		{
			$json 		= array();
			$user_identity	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$payrols		= array();
			if (function_exists('workreap_get_payouts_lists')) {
				$payrols		= workreap_get_payouts_lists();
			}

			$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
			$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
			//paged works on single pages, page - works on homepage
			$paged = max($pg_page, $pg_paged);
			$show_posts = get_option('posts_per_page');
			$order 		= 'DESC';
			$sorting 	= 'ID';

			$args = array(
				'posts_per_page' 	=> $show_posts,
				'paged' 		 	=> $paged,
				'post_type' 		=> 'withdraw',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('publish', 'pending'),
				'author' 			=> $user_identity,
				'suppress_filters'  => false
			);
			$query 				= new WP_Query($args);
			$total_withdraw 	= $query->found_posts;
			$payrols_list		= workreap_get_payouts_lists();
			$user_payouts	= array();
			$count_item		= 0;
			while ($query->have_posts()) : $query->the_post();
				global $post;
				$price	= get_post_meta($post->ID, '_withdraw_amount', true);
				$price	= !empty($price) ? $price : '';
				$publish_date		= get_the_date('', $post->ID);
				$account_type_key	= get_post_meta($post->ID, '_payment_method', true);
				$account_type		= !empty($payrols_list[$account_type_key]['title']) ? $payrols_list[$account_type_key]['title'] : $account_type_key;
				$status				= get_post_status($post->ID);
				$status_data		= !empty($status) && $status === 'pending' ? esc_html__('Pending', 'workreap_api') : esc_html__('Processed', 'workreap_api');
				$account_details	= get_post_meta($post->ID, '_account_details', true);

				$db_saved	= maybe_unserialize($account_details);
				foreach ($payrols_list[$account_type_key]['fields'] as $key => $field) {
					if (!empty($field['show_this']) && $field['show_this'] == true) {
						$current_val	= !empty($db_saved[$key]) ? $db_saved[$key] : 0;
					}
				}

				$user_payouts[]	= array(
					'publish_date'			=> $publish_date,
					'payout_detail_title'	=> $field['title'],
					'payout_detail_value'	=> $current_val,
					'price'					=> $price,
					'account_type'			=> $account_type,
					'status_data'			=> $status_data,
				);
				$count_item++;
			endwhile;
			wp_reset_postdata();
			$json					= array();
			$json['type'] 			= 'success';
			$json['message'] 		= esc_html__('Payout listing', 'workreap_api');
			$json['user_payouts']	= $user_payouts;
			return new WP_REST_Response($json, 200);
		}

		/**
		 * submit withdraw
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function payout_submit_withdraw($data)
		{
			global $wpdb, $current_user, $post;
			$json = array();
			$insert_payouts = '';
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$payment_method		= !empty($request['withdraw']['gateway']) ? esc_html($request['withdraw']['gateway']) : '';
			$amount				= !empty($request['withdraw']['amount']) ? floatval($request['withdraw']['amount']) : 0;
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$payment_setting	= worrketic_hiring_payment_setting();
			$total_pending		= workreap_sum_freelancer_withdraw(array('publish', 'pending'), $user_id);
			$total_pending		= !empty($total_pending) ? floatval($total_pending) : 0;

			$totalamount    = workreap_sum_user_earning('completed', 'freelancer_amount', $user_id);
			$total_amount   = !empty($totalamount->total_amount) ? floatval($totalamount->total_amount) - floatval($total_pending) : 0;

			if (!empty($payment_setting['minamount']) && $total_amount < $payment_setting['minamount']) {
				$json['type']       = 'error';
				$json['message'] 	= esc_html__("You are not allowed to withdraw amount below the", 'workreap_api') . ' ' . workreap_price_format($payment_setting['minamount'], 'return');
				return new WP_REST_Response($json, 203);
			}

			if ($amount > $total_amount) {
				$json['type']       = 'error';
				$json['message'] 	= esc_html__("We are Sorry! you do not have sufficient amount for the withdrawal", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$contents	= get_user_meta($user_id, 'payrols', true);

			if ($payment_method === 'paypal') {
				if (!empty($contents['payrol']) && $contents['payrol'] === 'paypal') {
					//only for migration from release 1.0.7
					$email	= !empty($contents['email']) ? $contents['email'] : "";
				} else {
					$email	= !empty($contents['paypal_email']) ? $contents['paypal_email'] : "";
				}

				$insert_payouts	= serialize(array('paypal_email' => $email));

				//check if email is valid
				if (empty($email) || !is_email($email)) {
					$json['type'] 	 = "error";
					$json['message'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else if ($payment_method === 'bacs') {
				$bank_details	= array();
				$bank_details['bank_account_name']		= !empty($contents['bank_account_name']) ? $contents['bank_account_name'] : "";
				$bank_details['bank_account_number']	= !empty($contents['bank_account_number']) ? $contents['bank_account_number'] : "";
				$bank_details['bank_name']				= !empty($contents['bank_name']) ? $contents['bank_name'] : "";
				$bank_details['bank_routing_number']	= !empty($contents['bank_routing_number']) ? $contents['bank_routing_number'] : "";
				$bank_details['bank_iban']				= !empty($contents['bank_iban']) ? $contents['bank_iban'] : "";
				$bank_details['bank_bic_swift']			= !empty($contents['bank_bic_swift']) ? $contents['bank_bic_swift'] : "";

				$bank_details   = apply_filters('payout_bank_transfer_filter_details', $bank_details, $contents);
				$insert_payouts	= serialize($bank_details);

				if (empty($contents['bank_iban']) || empty($contents['bank_account_number'])) {
					$json['type'] 	 = "error";
					$json['message'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$payout_details	= array();
				$fields	= workreap_get_payouts_lists($payment_method);

				if (!empty($fields[$payment_method]['fields'])) {
					foreach ($fields[$payment_method]['fields'] as $key => $field) {
						if (!empty($field['show_this']) && $field['show_this'] == true) {
							if (!empty($contents[$key])) {
								$payout_details[$key]	= $contents[$key];
							}
						}
					}
				}
				$insert_payouts	= serialize($payout_details);
				if (empty($payout_details)) {
					$json['type'] 	 = "error";
					$json['message'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			}


			//Process withdraw
			$account_details	= !empty($insert_payouts) ? $insert_payouts : '';

			$user_name = !empty($user_id) ? workreap_get_username($user_id) . '-' . $amount : '';
			$withdraw_post = array(
				'post_title'    => wp_strip_all_tags($user_name),
				'post_status'   => 'pending',
				'post_author'   => $user_id,
				'post_type'     => 'withdraw',
			);

			$withdraw_id    = wp_insert_post($withdraw_post);
			$current_date   = current_time('mysql');

			update_post_meta($withdraw_id, '_withdraw_amount', $amount);
			update_post_meta($withdraw_id, '_payment_method', $payment_method);
			update_post_meta($withdraw_id, '_timestamp', strtotime($current_date));
			update_post_meta($withdraw_id, '_year', date('Y', strtotime($current_date)));
			update_post_meta($withdraw_id, '_month', date('m', strtotime($current_date)));
			update_post_meta($withdraw_id, '_account_details', $account_details);

			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapSendPayoutsNotification')) {
					$emailData              = array();
					$user_name              = workreap_get_username($user_id);
					$post_id				= workreap_get_linked_profile_id($user_id);
					$emailData['user_name']	= !empty($user_name) ? $user_name : '';
					$emailData['user_link'] = admin_url('post.php?post=' . $post_id . '&action=edit');
					$emailData['amount']    = !empty($amount) ? workreap_price_format($amount, 'return') : '';
					$emailData['detail']    = admin_url('edit.php?post_type=withdraw&author=' . $user_id);
					$email_helper = new WorkreapSendPayoutsNotification();
					$email_helper->send_withdraw_request_to_admin($emailData);
				}
			}

			$json['type'] 	 	= "success";
			$json['message']	= esc_html__('Your withdrawal request has been submitted. We will process your withdrawal request', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}


		/**
		 * get verification document
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function verification_request($request)
		{
			$json 	 	= array();
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$post_id	= workreap_get_linked_profile_id($user_id);
			$verification_files	= get_post_meta($post_id, 'verification_attachments', true);
			$identity_verified	= get_post_meta($post_id, 'identity_verified', true);

			$verification_files	= !empty($verification_files) ? $verification_files : array();
			$identity_verified	= !empty($identity_verified) ? $identity_verified : 0;

			$json['verification_files']		= $verification_files;
			$json['identity_verified']		= $identity_verified;


			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Upload Identity Information', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}
		/**
		 * update verification document
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function send_verification_request($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json 	 	= array();
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$post_id	= workreap_get_linked_profile_id($user_id);

			$total_documents 		= !empty($request['document_size']) ? $request['document_size'] : 0;
			$required = array(
				'name'   				=> esc_html__('Name is required', 'workreap_api'),
				'contact_number'  		=> esc_html__('Contact number is required', 'workreap_api'),
				'verification_number'   => esc_html__('Verification number is required', 'workreap_api'),
				'address'   			=> esc_html__('Address is required', 'workreap_api'),
			);

			foreach ($required as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] = 'error';
					$json['message'] = $value;
					return new WP_REST_Response($json, 203);
				}
			}
			if (empty($total_documents)) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Please upload a document', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$verification_files	= array();

			$verification_files['info']['name'] 				= !empty($request['name']) ? esc_html($request['name']) : '';
			$verification_files['info']['contact_number']  		= !empty($request['contact_number']) ? esc_html($request['contact_number']) : '';
			$verification_files['info']['verification_number']  = !empty($request['verification_number']) ? esc_html($request['verification_number']) : '';
			$verification_files['info']['address'] 				= !empty($request['addres']) ? esc_html($request['address']) : '';

			if (!empty($_FILES) && $total_documents != 0) {

				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				require_once(ABSPATH . 'wp-includes/pluggable.php');

				$counter	= 0;
				for ($x = 0; $x < $total_documents; $x++) {
					$document_files 	= $_FILES['documents_documents' . $x];
					$uploaded_image  	= wp_handle_upload($document_files, array('test_form' => false));
					$file_name		 	= basename($document_files['name']);
					$file_type 		 	= wp_check_filetype($uploaded_image['file']);

					// Prepare an array of post data for the attachment.
					$attachment_details = array(
						'guid' 				=> $uploaded_image['url'],
						'post_mime_type' 	=> $file_type['type'],
						'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
						'post_content' 		=> '',
						'post_status' 		=> 'inherit'
					);

					$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
					$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);

					wp_update_attachment_metadata($attach_id, $attach_data);

					$documents['attachment_id']		= $attach_id;
					$documents['name']				= get_the_title($attach_id);
					$documents['url']				= wp_get_attachment_url($attach_id);
					$verification_files[]			= $documents;
				}
			}

			update_post_meta($post_id, 'verification_attachments', $verification_files);
			update_post_meta($post_id, 'identity_verified', 0);

			//Send an email to admin
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapIdentityVerification')) {
					$email_helper 				= new WorkreapIdentityVerification();
					$username   				= workreap_get_username($user_id);
					$current_user				= get_user_by('id', $user_id);
					$emailData 					= array();
					$emailData['user_name']  	= $username;
					$emailData['user_link']  	= admin_url('users.php') . '?s=' . $current_user->user_email;
					$emailData['user_email']  	= $current_user->user_email;

					$email_helper->send_verification_to_admin($emailData);
				}
			}

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Successfully! submitted your request for verification', 'workreap_api');

			return new WP_REST_Response($json, 200);
		}

		/**
		 * update verification document
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function cancel_verification_request($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json 	 	= array();
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$post_id	= workreap_get_linked_profile_id($user_id);

			update_post_meta($post_id, 'verification_attachments', '');
			update_post_meta($post_id, 'identity_verified', 0);

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Successfully! deleted your verification request', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * update brochures settings 
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_brochures_setting($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id	= !empty($request['id']) ? intval($request['id']) : '';
			$json 		= array();
			$brochures_array 		= !empty($request['brochures']) ? json_decode($request['brochures'], true) : array();
			$total_attachments 		= !empty($request['size']) ? $request['size'] : 0;
			$linked_profile  		= workreap_get_linked_profile_id($user_id);
			$fw_options             = fw_get_db_post_option($linked_profile);
			$brochures				= array();
			if (empty($user_id)) {
				$json['type']   	= 'error';
				$json['message']    = esc_html__('Some error occur, please try again later', 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else {
				//Brochures files
				if (!empty($_FILES) && !empty($total_attachments) && $total_attachments != 0) {

					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$counter	= 0;
					for ($x = 0; $x < $total_attachments; $x++) {
						$submitted_files = $_FILES['brochures_files' . $x];
						$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
						$file_name		 = basename($submitted_files['name']);
						$file_type 		 = wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' 				=> $uploaded_image['url'],
							'post_mime_type' 	=> $file_type['type'],
							'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' 		=> '',
							'post_status' 		=> 'inherit'
						);

						$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);

						$attachments						= array();
						$attachments['url']					= wp_get_attachment_url($attach_id);
						$attachments['attachment_id']		= $attach_id;
						$brochures[$attach_id]				= $attachments;
					}
				}
				if (!empty($brochures_array)) {
					$counter	= 0;
					foreach ($brochures_array as $key => $val) {
						$attachments						= array();
						$attach_id							= !empty($val['attachment_id']) ? $val['attachment_id'] : '';
						$attachments['url']					= wp_get_attachment_url($attach_id);
						$attachments['attachment_id']		= $attach_id;
						$brochures[$attach_id]				= $attachments;
						$counter++;
					}
				}

				$fw_options['brochures']	= $brochures;
				fw_set_db_post_option($linked_profile, null, $fw_options);

				$json['type']   	= 'success';
				$json['message']    = esc_html__('Brochures is updated successfully', 'workreap_api');
				return new WP_REST_Response($json, 200);
			}
		}

		/**
		 * Upload image to media
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function upload_media_file($file)
		{
			if (!empty($file)) {

				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				require_once(ABSPATH . 'wp-includes/pluggable.php');

				$attachments	= array();

				$submitted_files = $file;
				$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
				$file_name		 = basename($submitted_files['name']);
				$file_type 		 = wp_check_filetype($uploaded_image['file']);

				// Prepare an array of post data for the attachment.
				$attachment_details = array(
					'guid' => $uploaded_image['url'],
					'post_mime_type' => $file_type['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
					'post_content' => '',
					'post_status' => 'inherit'
				);

				$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
				$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
				wp_update_attachment_metadata($attach_id, $attach_data);


				$attachments['attachment_id']		= $attach_id;
				$attachments['url']					= wp_get_attachment_url($attach_id);

				return $attachments;
			}
		}

		/**
		 * Update social profile links
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_social_profile_setting($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id	= !empty($request['id']) ? intval($request['id']) : 0;
			$json 		= array();
			$user_type			= apply_filters('workreap_get_user_type', $user_id);
			$linked_profile  	= workreap_get_linked_profile_id($user_id);

			$socialmediaurls	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$socialmediaurls	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
			}

			$fw_options             = fw_get_db_post_option($linked_profile);

			$socialmediaurl 		= !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';
			if (!empty($socialmediaurl) && $socialmediaurl  === 'enable') {
				$social_settings    	= function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('yes') : array();
				if (!empty($social_settings)) {
					foreach ($social_settings as $key => $val) {
						$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
						if (!empty($enable_value) && $enable_value === 'enable') {
							$social_val	= !empty($request['basics'][$key]) ? esc_attr($request['basics'][$key]) : '';
							$fw_options[$key]           = $social_val;
						}
					}

					fw_set_db_post_option($linked_profile, null, $fw_options);
					$json['message']    = esc_html__('Social profiles has been updated', 'workreap_api');
					$json['type']   	= 'success';
					return new WP_REST_Response($json, 200);
				}
			}


			$json['message']    = esc_html__('Settings has been disabled by admin', 'workreap_api');
			$json['type']   	= 'error';
			return new WP_REST_Response($json, 203);
		}

		/**
		 * Get social profile links
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_social_profile_setting($request)
		{
			$user_id		= !empty($request['id']) ? intval($request['id']) : '';
			$json =  $profiles = array();

			$user_type			= apply_filters('workreap_get_user_type', $user_id);
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			$json['message']    = esc_html__('No profiles found', 'workreap_api');

			if (!empty($user_type)) {
				if ($user_type === 'employer') {
					$socialmediaurls	= array();
					if (function_exists('fw_get_db_settings_option')) {
						$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
					}

					$socialmediaurl 		= !empty($socialmediaurls) ? $socialmediaurls['gadget'] : '';
				} else if ($user_type === 'freelancer') {
					$socialmediaurls	= array();

					if (function_exists('fw_get_db_settings_option')) {
						$socialmediaurls	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
					}

					$socialmediaurl 		= !empty($socialmediaurls) ? $socialmediaurls['gadget'] : '';
				}

				$social_settings    = function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('no') : array();
				if (!empty($socialmediaurl) && $socialmediaurl  === 'enable') {
					if (!empty($social_settings)) {
						foreach ($social_settings as $key => $val) {
							$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
							if (!empty($enable_value) && $enable_value === 'enable') {
								$title			= !empty($key) ?  ucfirst($key) : "";
								$slug			= !empty($key) ?  $key : "";
								$icon			= !empty($val['icon']) ? $val['icon'] : '';
								$classes		= !empty($val['classses']) ? $val['classses'] : '';
								$placeholder	= !empty($val['placeholder']) ? $val['placeholder'] : '';
								$color			= !empty($val['color']) ? $val['color'] : '#484848';

								$social_url	= '';
								if (function_exists('fw_get_db_post_option')) {
									$social_url	= fw_get_db_post_option($linked_profile, $key, null);
								}

								$social_url	= !empty($social_url) ? $social_url : '';

								$profiles[$key]['title']		= $title;
								$profiles[$key]['slug']			= $slug;
								$profiles[$key]['classes']		= $classes;
								$profiles[$key]['color']		= $color;
								$profiles[$key]['icon']			= $icon;
								$profiles[$key]['social_url']	= $social_url;
								$profiles[$key]['placeholder']	= $placeholder;
							}
						}

						$json['list']   	= array_values($profiles);
						$json['type']   	= 'success';
						$json['message']    = esc_html__('Social profiles lists', 'workreap_api');

						return new WP_REST_Response($json, 200);
					}
				} else {
					$json['type']   	= 'error';
					$json['message']    = esc_html__('Settings has been disabled by admin', 'workreap_api');
				}
			}

			$json['type']   	= 'error';

			return new WP_REST_Response($json, 203);
		}

		/**
		 * Update Freelancer edu & exp
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_freelancer_education($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json			= $educations 	= $experiences 	= array();
			$user_id		= !empty($request['user_id']) ? sanitize_text_field($request['user_id']) : '';
			$education  	= !empty($request['settings']['education']) ? $request['settings']['education'] : array();
			$experience  	= !empty($request['settings']['experience']) ? $request['settings']['experience'] : array();
			$profile_id  	= workreap_get_linked_profile_id($user_id);
			$post_id		= $profile_id;
			if (empty($user_id)) {
				$json['type'] 		= 'error';
				$json['message']    = esc_html__('Some error occur, please try again later', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
			if (!empty($experience)) {
				$counter = 0;
				do_action('workreap_update_profile_strength', 'experience', true, $post_id);
				foreach ($experience as $key => $value) {
					if (!empty($value['title'])) {
						$experiences[$counter]['title']       = sanitize_text_field($value['title']);
						$experiences[$counter]['company']     = sanitize_text_field($value['job']);
						$experiences[$counter]['startdate']   = apply_filters('workreap_picker_date_format', $value['startdate']);
						$experiences[$counter]['enddate']     = apply_filters('workreap_picker_date_format', $value['enddate']);
						$experiences[$counter]['description'] = $value['details'];
						$counter++;
					}
				}
			} else {
				do_action('workreap_update_profile_strength', 'experience', false, $post_id);
			}

			if (!empty($education)) {
				$counter = 0;
				foreach ($education as $key => $value) {
					if (!empty($value['degree'])) {
						$educations[$counter]['title']          = sanitize_text_field($value['degree']);
						$educations[$counter]['institute']      = sanitize_text_field($value['university']);
						$educations[$counter]['startdate']      = apply_filters('workreap_picker_date_format', $value['startdate']);
						$educations[$counter]['enddate']        = apply_filters('workreap_picker_date_format', $value['enddate']);
						$educations[$counter]['description']    = sanitize_textarea_field($value['details']);
						$counter++;
					}
				}
			}

			update_post_meta($post_id, '_experience', $experiences);
			update_post_meta($post_id, '_educations', $educations);
			$fw_options             		= fw_get_db_post_option($post_id);
			$fw_options['experience']       = $experiences;
			$fw_options['education']        = $educations;
			fw_set_db_post_option($post_id, null, $fw_options);

			$json['type']   	= 'success';
			$json['message']    = esc_html__('Profile is updated successfully', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Update Freelancer basic Profile Data
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_freelancer_profile($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json				= array();
			$user_id			= !empty($request['basics']['user_id']) ? intval($request['basics']['user_id']) : '';
			$first_name			= !empty($request['basics']['first_name']) ? sanitize_text_field($request['basics']['first_name']) : '';
			$last_name			= !empty($request['basics']['last_name']) ? sanitize_text_field($request['basics']['last_name']) : '';
			$display_name		= !empty($request['basics']['display_name']) ? sanitize_text_field($request['basics']['display_name']) : '';
			$country			= !empty($request['basics']['country']) ? ($request['basics']['country']) : '';
			$tag_line			= !empty($request['basics']['tag_line']) ? sanitize_text_field($request['basics']['tag_line']) : '';
			$max_price			= !empty($request['basics']['max_price']) ? sanitize_text_field($request['basics']['max_price']) : '';
			$content			= !empty($request['basics']['content']) ? sanitize_text_field($request['basics']['content']) : '';
			$gender				= !empty($request['basics']['gender']) ? sanitize_text_field($request['basics']['gender']) : '';
			$per_hour_rate		= !empty($request['basics']['per_hour_rate']) ? sanitize_text_field($request['basics']['per_hour_rate']) : 0;
			$latitude			= !empty($request['basics']['latitude']) ? sanitize_text_field($request['basics']['latitude']) : '';
			$longitude			= !empty($request['basics']['longitude']) ? sanitize_text_field($request['basics']['longitude']) : '';
			$address			= !empty($request['basics']['address']) ? sanitize_text_field($request['basics']['address']) : '';
			$languages			= !empty($request['settings']['languages']) ? ($request['settings']['languages']) : array();
			$freelancer_type 	= !empty($request['settings']['freelancer_type']) ? ($request['settings']['freelancer_type']) : '';
			$english_level		= !empty($request['settings']['english_level']) ? sanitize_text_field($request['settings']['english_level']) : '';

			$profile_id  	= workreap_get_linked_profile_id($user_id);
			$post_id		= $profile_id;

			if (empty($user_id)) {
				$json['type'] 		= 'error';
				$json['message']    = esc_html__('Some error occur, please try again later', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$profile_mandatory	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$profile_mandatory	= fw_get_db_settings_option('freelancer_profile_required');
			}

			if (!empty($profile_mandatory)) {
				$freelancer_required = workreap_freelancer_required_fields();
				foreach ($profile_mandatory as $key) {
					if ($key === 'freelancer_type') {
						if (empty($request['settings'][$key])) {
							$json['type']   	= 'error';
							$json['message']    =  $freelancer_required[$key];
							return new WP_REST_Response($json, 203);
						}
					} else if ($key === 'skills') {
						if (empty($request['settings'][$key]) && empty($request['settings']['custom_skills'])) {
							$json['type']   	= 'error';
							$json['message']    =  $freelancer_required[$key];
							return new WP_REST_Response($json, 203);
						}
					} else {
						if (empty($request['basics'][$key])) {
							$json['type']   	= 'error';
							$json['message']    =  $freelancer_required[$key];
							return new WP_REST_Response($json, 203);
						}
					}
				}
			}

			$phone_setting 		= '';
			$phone_mandatory	= '';
			if (function_exists('fw_get_db_settings_option')) {
				$freelancer_price_option = fw_get_db_settings_option('freelancer_price_option', $default_value = null);
				$phone_option		= fw_get_db_settings_option('phone_option');
				$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
				$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
			}

			if (!empty($phone_setting) && $phone_setting == 'enable' && !empty($phone_mandatory) && $phone_mandatory == 'enable') {
				if (empty($request['basics']['user_phone_number'])) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Phone number is required', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			}
			if (!empty($first_name) && !empty($last_name)) {
				update_user_meta($user_id, 'first_name', $first_name);
				update_user_meta($user_id, 'last_name', $last_name);
				if (!empty($display_name)) {
					$post_title	= $display_name;
					$user_info	= array('ID' => $user_id, 'display_name' => $display_name);
					wp_update_user($user_info);
				} else {
					$post_title	= esc_html(get_the_title($post_id));
				}
			}

			$freelancer_user = array(
				'ID'           => $post_id,
				'post_title'   => $post_title,
				'post_content' => $content,
			);
			wp_update_post($freelancer_user);

			$custom_skills 	= !empty($request['settings']['custom_skills']) ? $request['settings']['custom_skills'] : array();
			$skills 		= !empty($request['settings']['skills']) ? $request['settings']['skills'] : array();

			$skill_keys = $skills_new = $skills_term = $skills_names = array();
			$counter 		= 0;
			if (!empty($skills)) {
				foreach ($skills as $key => $value) {
					if (!in_array($value['skill'], $skill_keys)) {
						$skill_val							= !empty($value['skill']) ? $value['skill'] : '';
						$skill_keys[] 						= $skill_val;
						$skills_new[$counter]['skill'][0] 	= $skill_val;
						$skills_new[$counter]['value'] 		= $value['value'];
						$skills_term[] 						= $skill_val;
						$counter++;

						if (!empty($skill_val)) {
							$skills_names[] = get_term($skill_val)->name;
						}
					}
				}

				//Prepare Params
				$params_array['post_obj'] 		= $request;
				$params_array['user_identity'] 	= $user_id;
				$params_array['user_role'] 		= apply_filters('workreap_get_user_type', $user_id);
				$params_array['type'] 			= 'skills';

				//child theme : update extra settings
				do_action('wt_process_profile_child', $params_array);

				if (!empty($skills_term)) {
					wp_set_post_terms($post_id, $skills_term, 'skills');
					do_action('workreap_update_profile_strength', 'skills', true, $post_id);
				} else {
					do_action('workreap_update_profile_strength', 'skills', false, $post_id);
				}
			}

			if (!empty($custom_skills)) {
				$fw_options             = fw_get_db_post_option($post_id);
				$skills_custom_term = $skills_custom_keys = $skills_custom_new = $custom_term_email = array();
				$custom_skill_counter   = 0;

				foreach ($custom_skills as $key => $val) {
					$slugify_skill 	= !empty($val['skill']) ? sanitize_title($val['skill']) : '';
					$term_exists 	= term_exists($slugify_skill, 'skills');
					if ($term_exists !== null) {
						$insert_term = $term_exists;
					} else {
						$insert_term = wp_insert_term(
							esc_html($val['skill']),
							'skills',
							array(
								'slug'        => $slugify_skill,
								'parent'      => intval(0),
							)
						);

						update_term_meta($insert_term['term_id'], 'skill_term_status', 'draft');
						$custom_term_email[] = $slugify_skill;
					}

					if (!in_array($val['value'], $skills_custom_keys)) {
						$skills_custom_keys[] 									= $insert_term['term_id'];
						$skills_custom_new[$custom_skill_counter]['skill'][0] 	= $insert_term['term_id'];
						$skills_custom_new[$custom_skill_counter]['value'] 		= $val['value'];
						$skills_custom_term[] 									= $insert_term['term_id'];
						$custom_skill_counter++;
					}
				}

				$final_term_array 	= array_merge($skills_term, $skills_custom_term);
				$final_skills_array = array_merge($skills_new, $skills_custom_new);

				if (!empty($skills_custom_term)) {
					wp_set_post_terms($post_id, $final_term_array, 'skills');

					update_post_meta($post_id, '_skills', $final_skills_array);
					$skills_new						= $final_skills_array;
				}
			}

			//Update tagline Profile health
			if (!empty($tag_line)) {
				do_action('workreap_update_profile_strength', 'tagline', true, $post_id);
			} else {
				do_action('workreap_update_profile_strength', 'tagline', false, $post_id);
			}

			//Update identity verification Profile health
			$identity_verified	= get_post_meta($post_id, 'identity_verified', true);
			if (!empty($identity_verified)) {
				do_action('workreap_update_profile_strength', 'identity_verification', true, $post_id);
			} else {
				do_action('workreap_update_profile_strength', 'identity_verification', false, $post_id);
			}

			update_post_meta($post_id, '_gender', $gender);
			update_post_meta($post_id, '_tag_line', $tag_line);
			update_post_meta($post_id, '_perhour_rate', $per_hour_rate);
			update_post_meta($post_id, '_address', $address);
			update_post_meta($post_id, '_country', $country);
			update_post_meta($post_id, '_latitude', $latitude);
			update_post_meta($post_id, '_longitude', $longitude);
			update_post_meta($post_id, '_skills_names', $skills_names);

			//Set country for unyson
			$locations = get_term_by('slug', $country, 'locations');

			$location = array();
			if (!empty($locations)) {
				$location[0] = $locations->term_id;
				wp_set_post_terms($post_id, $locations->term_id, 'locations');
			}

			//update languages
			if (!empty($languages)) {
				$lang		= array();
				$lang_slugs	= array();
				foreach ($languages as $key => $item) {
					$lang[] = $item;
				}

				if (!empty($lang)) {
					wp_set_post_terms($post_id, $lang, 'languages');
				}
			}
			//update english level
			if (!empty($english_level)) {
				update_post_meta($post_id, '_english_level', $english_level);
				if (function_exists('fw_set_db_post_option')) {
					fw_set_db_post_option($post_id, 'english_level', $english_level);
				}
			}

			$fw_options             		 = fw_get_db_post_option($post_id);
			if (!empty($freelancer_price_option) && $freelancer_price_option === 'enable') {
				$fw_options['max_price']     = $max_price;
				update_post_meta($post_id, '_max_price', $max_price);
			}

			if (!empty($phone_setting) && $phone_setting == 'enable') {
				$fw_options['user_phone_number']        = !empty($request['basics']['user_phone_number']) ? $request['basics']['user_phone_number'] : '';
			}

			$fw_options['gender']             = $gender;
			$fw_options['tag_line']           = $tag_line;
			$fw_options['_perhour_rate']      = $per_hour_rate;
			$fw_options['address']            = $address;
			$fw_options['longitude']          = $longitude;
			$fw_options['latitude']           = $latitude;
			$fw_options['country']            = $location;
			$fw_options['skills']             = $skills_new;
			$fw_options['freelancer_type']    = $freelancer_type;

			//update freelancer type
			$freelancer_type = '';
			if (!empty($request['settings']['freelancer_type'])) {

				$freelancer_type	=  $request['settings']['freelancer_type'];
				update_post_meta($post_id, '_freelancer_type', $freelancer_type);

				$freelancer_type_array	= !empty($freelancer_type) && is_array($freelancer_type) ? $freelancer_type : array($freelancer_type);
				do_action('workreap_update_term_taxonomy_meta', $request);
				wp_set_object_terms($post_id, $freelancer_type_array, 'freelancer_type');
			}

			/* banner image */
			$banner_img_url = '';
			$banner_base64		= !empty($request['banner_base64']) ?  $request['banner_base64']  : '';
			if (apply_filters('workreap_is_feature_allowed', 'wt_banner', $user_id) === true) {
				if (!empty($banner_base64)) {

					if (!empty($banner_base64['image']['attachment_id'])) {
						$fw_options['banner_image']	= $banner_base64['image'];
					} else {
						$banner_attachment_id = '';
						if (class_exists('AndroidApp_uploadmedia')) {
							$android_upload = new AndroidApp_uploadmedia();
							$banner_attachment_id 	= $android_upload->upload_media($banner_base64);
						}
						$banner_attachment_id	= !empty($banner_attachment_id) ? ($banner_attachment_id) : 0;
						if (!empty($banner_attachment_id)) {
							$post_banner	= fw_get_db_post_option($post_id, 'banner_image', $default_value = null);
							$thumnail_id	= !empty($post_banner['attachment_id']) ? intval($post_banner['attachment_id']) : 0;
							if (!empty($thumnail_id)) {
								wp_delete_attachment($thumnail_id);
							}
							$banner_img_url = wp_get_attachment_url($banner_attachment_id);
							$banner_image	= array(
								'attachment_id' => intval($banner_attachment_id),
								'url'			=> $banner_img_url,
								'name'			=> get_the_title($banner_attachment_id)
							);
							$fw_options['banner_image']	= $banner_image;
						}
					}
				}
			}

			/* profile image */
			$profile_base64		= !empty($request['profile_base64']) ?  $request['profile_base64'] : array();
			if (!empty($profile_base64)) {
				$avatar_id = '';
				if (class_exists('AndroidApp_uploadmedia')) {
					$android_upload = new AndroidApp_uploadmedia();
					$avatar_id = $android_upload->upload_media($profile_base64);
				}

				if (!empty($avatar_id)) {
					$thumnail_id	= get_post_thumbnail_id($post_id);
					wp_delete_attachment($thumnail_id);
					set_post_thumbnail($post_id, $avatar_id);
					update_post_meta($post_id, '_have_avatar', 1);
					do_action('workreap_update_profile_strength', 'avatar', true);
				} else {
					update_post_meta($post_id, '_have_avatar', 0);
					do_action('workreap_update_profile_strength', 'avatar', false);
				}
			}

			/* Resume */
			$profile_resume		= array();
			$resume				= !empty($request['basics']['resume_base64']) ?  $request['basics']['resume_base64']  : '';
			if (!empty($resume)) {
				$resume_id = 0;
				if (class_exists('AndroidApp_uploadmedia')) {
					$android_upload = new AndroidApp_uploadmedia();
					$resume_id = $android_upload->upload_media($resume);
				}
				$resume_id		= !empty($resume_id) ? intval($resume_id) : 0;
				if (!empty($resume_id)) {
					$profile_resume		= array(
						'attachment_id' => $resume_id,
						'url'			=> wp_get_attachment_url($resume_id)
					);
					$fw_options['resume']	= $profile_resume;
				}
			} else {
				$fw_options['resume']	= $profile_resume;
			}

			$profile_gallery = array();
			if (!empty($request['basics']['images_gallery'])) {
				$fw_options['images_gallery'] 	= $request['basics']['images_gallery'];
			}

			if (!empty($request['basics']['images_gallery_new'])) {
				$new_index				= !empty($fw_options['images_gallery']) ?  max(array_keys($fw_options['images_gallery'])) : 0;
				$downloads_files		= array();
				$total_gallery_imgs 	= !empty($request['images_gallery_new']) ? $request['images_gallery_new'] : 0;
				if (!empty($_FILES) && $total_gallery_imgs != 0) {

					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$counter	= 0;
					for ($x = 0; $x < $total_gallery_imgs; $x++) {
						$new_index++;
						$gallery_image_files 	= $_FILES['gallery_images' . $x];
						$uploaded_image  		= wp_handle_upload($gallery_image_files, array('test_form' => false));
						$file_name			 	= basename($gallery_image_files['name']);
						$file_type 		 		= wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' 				=> $uploaded_image['url'],
							'post_mime_type' 	=> $file_type['type'],
							'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' 		=> '',
							'post_status' 		=> 'inherit'
						);

						$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);

						wp_update_attachment_metadata($attach_id, $attach_data);
						$gallery						= array();
						$gallery['attachment_id']		= $attach_id;
						$gallery['url']					= wp_get_attachment_url($attach_id);

						$fw_options['images_gallery'][$new_index]	= $gallery;
					}
				}
			}
			//Update User Profile
			fw_set_db_post_option($post_id, null, $fw_options);

			/* profile image url */
			$profile_img_url = get_the_post_thumbnail_url($post_id, 'workreap_blog_grid');
			$profile_img_url = !empty($profile_img_url) ? $profile_img_url : '';
			/* banner image url */
			$banner_img_url = fw_get_db_post_option($post_id, 'banner_image', true);
			$banner_img_url = !empty($banner_img_url) ? $banner_img_url['url'] : '';


			$json['type']   			= 'success';
			$json['message']    		= esc_html__('Profile is updated successfully', 'workreap_api');
			$json['banner_img_url']    	= $banner_img_url;
			$json['profile_img_url']    = $profile_img_url;
			return new WP_REST_Response($json, 200);
		}
		/**
		 * Update Profile Data
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_profile_setting($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json = $locations = $location = array();
			$user_id		= !empty($request['id']) ? intval($request['id']) : 0;
			$type			= !empty($request['user_type']) ? esc_attr($request['user_type']) : '';
			$update_type	= !empty($request['update_type']) ? esc_attr($request['update_type']) : 'profile';

			$common_fileds_meta	= array(
				'longitude'			=> '_longitude',
				'latitude'			=> '_latitude',
				'country'			=> '_country',
				'address'			=> '_address',
				'tag_line'			=> '_tag_line'
			);

			$employers_fields	= array(
				'department'			=> '_department',
				'no_of_employees'		=> 'no_of_employees',
			);

			$freelancer_fields	= array(
				'_perhour_rate'			=> '_perhour_rate',
				'gender'				=> '_gender'
			);

			if (!empty($user_id) && !empty($type)) {
				$profile_id 		= workreap_get_linked_profile_id($user_id);
				foreach ($common_fileds_meta as $key => $val) {
					$value		= !empty($request[$key]) ? $request[$key] : '';

					//Update tagline Profile health
					if ($key === 'tag_line') {
						if (!empty($value)) {
							do_action('workreap_update_profile_strength', 'tagline', true);
						} else {
							do_action('workreap_update_profile_strength', 'tagline', false);
						}
					}

					if (!empty($value)) {
						if ($key === 'country') {
							$locations = get_term_by('id', $value, 'locations');
							update_post_meta($profile_id, '_country', $locations->slug);
							wp_set_post_terms($profile_id, $locations->term_id, 'locations');
							$location[0]	= $locations->term_id;
							fw_set_db_post_option($profile_id, 'country', $location);
						} else {
							fw_set_db_post_option($profile_id, $key,  $value);
							update_post_meta($profile_id, $val, $value);
						}
					}
				}

				//for employers
				if ($type === 'employer') {
					foreach ($employers_fields as $key => $val) {
						$value		= !empty($request[$key]) ?  $request[$key] : '';
						if (!empty($value)) {
							if ($val === 'department') {
								update_post_meta($profile_id, $val, $value);
								fw_set_db_post_option($profile_id, $key, array('0'	=> $value));
							} else {
								fw_set_db_post_option($profile_id, $key,  $value);
								update_post_meta($profile_id, $val, $value);
							}
						}
					}
				} else if ($type === 'freelancer') {
					foreach ($freelancer_fields as $key => $val) {
						$value		= !empty($request[$key]) ?  $request[$key] : '';
						if (!empty($value)) {
							fw_set_db_post_option($profile_id, $key,  $value);
							update_post_meta($profile_id, $val, $value);
						}
					}

					//Update identity verification Profile health
					$identity_verified	= get_post_meta($profile_id, 'identity_verified', true);
					if (!empty($identity_verified)) {
						do_action('workreap_update_profile_strength', 'identity_verification', true);
					} else {
						do_action('workreap_update_profile_strength', 'identity_verification', false);
					}
				}

				//update user name
				$first_name		= !empty($request['first_name']) ? esc_attr($request['first_name']) : '';
				$last_name		= !empty($request['last_name']) ? esc_attr($request['last_name']) : '';

				if (!empty($first_name) && !empty($last_name)) {
					update_user_meta($user_id, 'first_name', $first_name);
					update_user_meta($user_id, 'last_name', $last_name);
					$update_post	= array(
						'ID'			=> $profile_id,
						'post_title'	=> $first_name . ' ' . $last_name
					);
					wp_update_post($update_post);
				}
				$json['type']   	= 'success';
				$json['message']    = esc_html__('Profile is updated successfully', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {

				$json['type']   	= 'error';
				$json['message']    = esc_html__('Required fields are missing', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Profile Data
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_freelancer_profile($request)
		{
			$user_id	= !empty($request['id']) ? intval($request['id']) : '';
			$json = $items = $location = array();
			if (!empty($user_id)) {
				$profile_id	= workreap_get_linked_profile_id($user_id);
				$user_type	= !empty($user_id) ? apply_filters('workreap_get_user_type', $user_id) : '';
				if (!empty($profile_id)) {
					$first_name		= get_user_meta($user_id, 'first_name', true);
					$last_name		= get_user_meta($user_id, 'last_name', true);
					$user_info		= get_userdata($user_id);
					$post_object	= get_post($profile_id);
					$tag_line		= workreap_get_tagline($profile_id);

					$hide_perhour = $frc_remove_freelancer_type	= $frc_remove_languages = 'no';
					$frc_remove_experience = $freelancer_faq_option = $frc_english_level = 'no';

					/*
					* Profile Photo Profile Setting
					*/
					if (has_post_thumbnail($profile_id)) {
						$attachment_id 			= get_post_thumbnail_id($profile_id);
						$image_url 				= !empty($attachment_id) ? wp_get_attachment_image_src($attachment_id, 'workreap_freelancer', true) : '';
						$file_size 				= !empty(get_attached_file($attachment_id)) ? filesize(get_attached_file($attachment_id)) : '';
						$document_name   		= !empty($attachment_id) ? esc_html(get_the_title($attachment_id)) : '';
					}

					/*
					* Banner Photo
					*/
					if (apply_filters('workreap_is_feature_allowed', 'wt_banner', $user_id) === true) {
						$banner_image = $banner_image_info  =  array();
						if (function_exists('fw_get_db_post_option')) {
							$banner_image       = fw_get_db_post_option($profile_id, 'banner_image', true);
						}

						//Banner image
						$banner_file_size 		= !empty($banner_image['attachment_id']) ? filesize(get_attached_file($banner_image['attachment_id'])) : '';
						$banner_document_name	= !empty($banner_image['attachment_id']) ? esc_html(get_the_title($banner_image['attachment_id'])) : '';
						$banner_filetype        = !empty($banner_image['attachment_id']) ? wp_check_filetype($banner_image['url']) : '';
						$banner_extension  		= !empty($banner_filetype['ext']) ? $banner_filetype['ext'] : '';
						$banner_image_url 		= !empty($banner_image['attachment_id']) ? wp_get_attachment_image_src($banner_image['attachment_id'], 'workreap_blog_grid', true) : array();
						$banner_image_url		= !empty($banner_image_url) ? $banner_image_url[0] : '';

						$banner_image_info_attachment_id			= !empty($banner_image['attachment_id']) ? $banner_image['attachment_id'] : '';
						$banner_image_info_banner_file_size		= $banner_file_size;
						$banner_image_info_banner_extension		= $banner_extension;
						$banner_image_info_banner_image_url		= $banner_image_url;
						$banner_image_info_banner_document_name	= $banner_document_name;

						$banner_image_info = array(
							'attachment_id'				=> $banner_image_info_attachment_id,
							'banner_file_size'			=> $banner_image_info_banner_file_size,
							'banner_extension'			=> $banner_image_info_banner_extension,
							'banner_image_url'			=> $banner_image_info_banner_image_url,
							'banner_document_name'		=> $banner_image_info_banner_document_name,
						);
					}

					if (function_exists('fw_get_db_settings_option')) {
						$freelancer_price_option 		= fw_get_db_settings_option('freelancer_price_option', $default_value = null);
						$hide_perhour			 		= fw_get_db_settings_option('hide_freelancer_perhour', $default_value = null);
						$frc_remove_freelancer_type	 	= fw_get_db_settings_option('frc_remove_freelancer_type', $default_value = 'no');
						$frc_remove_languages		 	= fw_get_db_settings_option('frc_remove_languages', $default_value = 'no');
						$frc_english_level			 	= fw_get_db_settings_option('frc_english_level', $default_value = 'no');
						$phone_option					= fw_get_db_settings_option('phone_option', $default_value = null);
						$gallery_option 				= fw_get_db_settings_option('freelancer_gallery_option', $default_value = null);
						$upload_resume 					= fw_get_db_settings_option('upload_resume', $default_value = null);
						$allow_skills					= fw_get_db_settings_option('allow_skills');
						$display_type					= fw_get_db_settings_option('display_type', $default_value = 'number');
						$field_type						= !empty($display_type) && ($display_type === 'number') ? '%' : 'year';
						$socialmediaurls				= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
						$frc_remove_experience 			= fw_get_db_settings_option('frc_remove_experience', $default_value = null);
						$frc_remove_education 			= fw_get_db_settings_option('frc_remove_education', $default_value = null);
						$freelancer_faq_option			= fw_get_db_settings_option('freelancer_faq_option', $default_value = null);
					}

					/* Profile social settings */
					$social_array			= array();
					$socials_status			= 'disable';
					if (!empty($socialmediaurls['gadget']) && $socialmediaurls['gadget'] === 'enable') {
						$socials_status 		= $socialmediaurls['gadget'];
						$social_settings    	= function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('no') : array();
						if (!empty($social_settings) && $socialmediaurls['gadget']) {
							foreach ($social_settings as $key => $val) {
								$enable_value   	= !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
								if (!empty($enable_value) && $enable_value === 'enable') {
									$social_data_title			= !empty($key) ? ucfirst($key) : '';
									$social_data_slug			= !empty($key) ? $key : '';
									$social_data_icon			= !empty($val['icon']) ? $val['icon'] : '';
									$social_data_classes		= !empty($val['classses']) ? $val['classses'] : '';
									$social_data_placeholder	= !empty($val['placeholder']) ? $val['placeholder'] : '';
									$social_data_color			= !empty($val['color']) ? $val['color'] : '#484848';

									$social_url		= '';
									if (function_exists('fw_get_db_post_option')) {
										$social_url	= fw_get_db_post_option($profile_id, $key, null);
									}
									$social_url					= !empty($social_url) ? $social_url : '';
									$social_data_social_url		= !empty($social_url) ? esc_url($social_url) : '';

									$social_array[] = array(
										'title'			=> $social_data_title,
										'slug'			=> $social_data_slug,
										'icon'			=> $social_data_icon,
										'classes'		=> $social_data_classes,
										'placeholder'	=> $social_data_placeholder,
										'color'			=> $social_data_color,
										'social_url'	=> $social_data_social_url
									);
								}
							}
						}
					}

					$faqs = $gallery_images = $profile_photo = $resume_doc = $skills_array = array();
					$experience = $projects = $freelancer_type_array = $location_array = array();
					$projects_array = $awards_array = $videos = $specialization_array = array();
					$default_img 			= get_template_directory_uri() . '/images/project-65x65.jpg';
					$default_award_img 		= get_template_directory_uri() . '/images/awards-65x65.jpg';

					$industrial_experiences_array	= array();
					if (function_exists('fw_get_db_post_option')) {
						$address    = fw_get_db_post_option($profile_id, 'address', true);
						$latitude	= fw_get_db_post_option($profile_id, 'latitude', true);
						$longitude	= fw_get_db_post_option($profile_id, 'longitude', true);
						$countries	= fw_get_db_post_option($profile_id, 'country', true);
						$skills 	= fw_get_db_post_option($profile_id, 'skills', true);
						$awards 	= fw_get_db_post_option($profile_id, 'awards', true);
						$videos 	= fw_get_db_post_option($profile_id, 'videos', true);

						if (!empty($freelancer_faq_option) && $freelancer_faq_option == 'yes') {
							$faqs 		= fw_get_db_post_option($profile_id, 'faq', true);
						}

						$faqs = (!empty($faqs) && is_array($faqs)) ? $faqs : array();


						if (empty($frc_remove_experience) || $frc_remove_experience == 'no') {
							$experiences 	    = fw_get_db_post_option($profile_id, 'experience', true);

							foreach ($experiences as $key => $value) {
								$enddate 		= !empty($value['enddate']) ? str_replace('/', '-', $value['enddate']) : '';
								$enddate 		= apply_filters('workreap_date_format_field', $enddate);
								$startdate 		= !empty($value['enddate']) ? str_replace('/', '-', $value['startdate']) : '';
								$startdate 		= apply_filters('workreap_date_format_field', $startdate);

								$experience_title       = sanitize_text_field($value['title']);
								$experience_company     = sanitize_text_field($value['company']);
								$experience_startdate   = sanitize_text_field($startdate);
								$experience_enddate     = sanitize_text_field($enddate);
								$experience_description = $value['description'];

								$experience[] = array(
									'title'			=> $experience_title,
									'company'		=> $experience_company,
									'startdate'		=> $experience_startdate,
									'enddate'		=> $experience_enddate,
									'description'	=> $experience_description,
								);
							}
						}

						if (empty($frc_remove_education) || $frc_remove_education == 'no') {
							$educations 	    = fw_get_db_post_option($profile_id, 'education', true);
							foreach ($educations as $key => $value) {

								$enddate   = !empty($value['enddate']) ? str_replace('/', '-', $value['enddate']) : '';
								$enddate   = !empty($enddate) ? apply_filters('workreap_date_format_field', $enddate) : '';
								$startdate = !empty($value['enddate']) ? str_replace('/', '-', $value['startdate']) : '';
								$startdate = !empty($startdate) ? apply_filters('workreap_date_format_field', $startdate) : '';

								$education_title          = sanitize_text_field($value['title']);
								$education_institute      = sanitize_text_field($value['institute']);
								$education_startdate      = sanitize_text_field($startdate);
								$education_enddate        = sanitize_text_field($enddate);
								$education_description    = sanitize_textarea_field($value['description']);

								$education[] = array(
									'title'			=>	$education_title,
									'institute'		=>	$education_institute,
									'startdate'		=>	$education_startdate,
									'enddate'		=>	$education_enddate,
									'description'	=>	$education_description,
								);
							}
						}

						if (!empty($awards) && is_array($awards)) {
							foreach ($awards as $key => $value) {
								$new_award_title   	= !empty($value['title']) ? stripslashes($value['title']) : '';
								$new_award_date   		= !empty($value['date']) ? date_i18n('F Y', strtotime($value['date'])) : '';
								$image   					= !empty($value['image']) ? $value['image'] : array();
								$img_url   					= !empty($image) ? wp_get_attachment_image_src($image['attachment_id'], 'workreap_latest_articles_widget', true) : '';
								$image_data					= '';

								if (empty($img_url[0])) {
									$image_data = $default_img;
								} else {
									$image_data = $img_url[0];
								}

								$new_award_file_size   		= !empty($image['attachment_id']) ? filesize(get_attached_file($image['attachment_id'])) : '';
								$new_award_file_name   		= !empty($image['attachment_id']) ? esc_html(get_the_title($image['attachment_id'])) : '';
								$new_award_file_type   		= !empty($image['attachment_id']) ? wp_check_filetype($image['url']) : '';
								$new_award_attachment_id   	= !empty($image['attachment_id']) ? intval($image['attachment_id']) : 0;
								$new_award_img_url   		= $image_data;

								$awards_array[] = array(
									'title' 			=> $new_award_title,
									'date' 				=> $new_award_date,
									'file_size' 		=> $new_award_file_size,
									'file_name' 		=> $new_award_file_name,
									'file_type' 		=> $new_award_file_type,
									'attachment_id' 	=> $new_award_attachment_id,
									'img_url' 			=> $new_award_img_url,
								);
							}
						}

						$projects 		    = fw_get_db_post_option($profile_id, 'projects', true);
						if (!empty($projects) && is_array($projects)) {
							foreach ($projects as $key => $value) {
								$new_project_title   	= !empty($value['title']) ? stripslashes($value['title']) : '';
								$new_project_link   	= !empty($value['link']) ? esc_url($value['link']) : '#';
								$image   				= !empty($value['image']) ? $value['image'] : array();
								$img_url   				= !empty($image) ? wp_get_attachment_image_src($image['attachment_id'], 'workreap_latest_articles_widget', true) : '';
								$image_data				= '';

								if (empty($img_url[0])) {
									$image_data = $default_img;
								} else {
									$image_data = $img_url[0];
								}

								$new_project_file_size   		= !empty($image['attachment_id']) ? filesize(get_attached_file($image['attachment_id'])) : '';
								$new_project_file_name   		= !empty($image['attachment_id']) ? esc_html(get_the_title($image['attachment_id'])) : '';
								$new_project_file_type   		= !empty($image['url']) ? esc_url($image['url']) : '';
								$new_project_attachment_id   	= !empty($image['attachment_id']) ? intval($image['attachment_id']) : 0;

								if (!empty($new_project['file_type']['ext']) && $new_project['file_type']['ext'] === 'pdf') {
									$image_data	= get_template_directory_uri() . '/images/pdf.jpg';
								}
								$new_project_img_url   			= esc_url($image_data);

								$projects_array[] = array(
									'title' 		=> $new_project_title,
									'link' 			=> $new_project_link,
									'file_size' 	=> $new_project_file_size,
									'file_name' 	=> $new_project_file_name,
									'file_type' 	=> $new_project_file_type,
									'attachment_id' => $new_project_attachment_id,
									'img_url' 		=> $new_project_img_url
								);
							}
						}

						$specializations 	 = fw_get_db_post_option($profile_id, 'specialization', true);
						if (!empty($specializations) && is_array($specializations)) {
							foreach ($specializations as $specialization_data) {
								$specializations_id 			= !empty($specialization_data['spec'][0]) ? $specialization_data['spec'][0] : '';
								$specializations_val_title	= !empty($specializations_id) ? workreap_get_term_name($specializations_id, 'wt-specialization') : '';
								$specializations_val_id		= !empty($specialization_data['spec'][0]) ? intval($specialization_data['spec'][0]) : 0;
								$specializations_val_val		= !empty($specialization_data['value']) ? intval($specialization_data['value']) : 0;

								$specialization_array[] = array(
									'id'   		=> $specializations_val_id,
									'title'   	=> $specializations_val_title,
									'val'   	=> $specializations_val_val
								);
							}
						}

						$industrial_experiences 	 = fw_get_db_post_option($profile_id, 'industrial_experiences', true);
						if (!empty($industrial_experiences) && is_array($industrial_experiences)) {
							foreach ($industrial_experiences as $industrial_data) {
								$industrial_id 							= !empty($industrial_data['exp'][0]) ? $industrial_data['exp'][0] : '';
								$industrial_experiences_val_title	= !empty($industrial_id) ? workreap_get_term_name($industrial_id, 'wt-industrial-experience') : '';
								$industrial_experiences_val_id		= !empty($industrial_data['exp'][0]) ? intval($industrial_data['exp'][0]) : 0;
								$industrial_experiences_val_val		= !empty($industrial_data['value']) ? intval($industrial_data['value']) : 0;

								$industrial_experiences_array[] = array(
									'id'	=> $industrial_experiences_val_id,
									'title'	=> $industrial_experiences_val_title,
									'val'	=> $industrial_experiences_val_val
								);
							}
						}

						if (!empty($skills) && is_array($skills)) {

							foreach ($skills as $skill_data) {
								if (!empty($skill_data['skill'][0])) {
									$skill_slug 		= get_term_by('id', $skill_data['skill'][0], 'skills');
									$skills_val_id		= !empty($skill_data['skill'][0]) ? intval($skill_data['skill'][0]) : 0;
									$skills_val_name	= !empty($skill_slug->name) ? $skill_slug->name : '';
									$skills_val_slug	= !empty($skill_slug->slug) ? $skill_slug->slug : '';
									$skills_val_val		= !empty($skill_data['value']) ? intval($skill_data['value']) : 0;

									$skills_array[]		= array(
										'id'		=> $skills_val_id,
										'name'		=> $skills_val_name,
										'slug'		=> $skills_val_slug,
										'val'		=> $skills_val_val,
									);
								}
							}
						}

						if (!empty($freelancer_price_option) && $freelancer_price_option === 'enable') {
							$max_price     	= fw_get_db_post_option($profile_id, 'max_price', true);
						}
						$countries	= !empty($countries[0]) ? intval($countries[0]) : '';
						if (!empty($countries)) {
							$locations 	= get_term_by('id', $countries, 'locations');
							if ($locations) {
								$location_array	= array(
									'id'	=> $locations->term_id,
									'slug'	=> $locations->slug,
									'name'	=> $locations->name,
								);
							}
						}

						$hourly_rate = '';
						if (!empty($hide_perhour) && $hide_perhour === 'no') {
							$hourly_rate	= get_post_meta($profile_id, '_perhour_rate', true);
						}

						if (!empty($phone_option['gadget']) && $phone_option['gadget'] == 'enable') {
							$user_phone_number  = fw_get_db_post_option($profile_id, 'user_phone_number');
						}
						$gender     			= fw_get_db_post_option($profile_id, 'gender', true);

						if (!empty($gallery_option) && $gallery_option === 'enable') {
							$freelancer_gallery     = fw_get_db_post_option($profile_id, 'images_gallery', $default_value = null);

							if (!empty($freelancer_gallery)) {
								foreach ($freelancer_gallery as $key => $gallery_image) {
									$gallery_thumnail_image_url 	= !empty($gallery_image['attachment_id']) ? wp_get_attachment_image_src($gallery_image['attachment_id'], 'workreap_freelancer', true) : '';
									$gallery_image_url 				= !empty($gallery_image['url']) ? $gallery_image['url'] : '';
									$attachment_id					= !empty($gallery_image['attachment_id']) ? intval($gallery_image['attachment_id']) : 0;
									$gallery_images[$attachment_id]['file_size']   		= !empty($attachment_id) ? filesize(get_attached_file($attachment_id)) : '';
									$gallery_images[$attachment_id]['file_name']   		= !empty($attachment_id) ? esc_html(get_the_title($attachment_id)) : '';

									$gallery_images[$attachment_id]['attachment_id']	= $attachment_id;
									$gallery_images[$attachment_id]['attachment_url']	= !empty($gallery_image_url) ? esc_url($gallery_image_url) : '';
								}
							}
							$gallery_images	= array_values($gallery_images);
						}

						if (!empty($upload_resume) && $upload_resume === 'yes') {
							$resume  		 	= fw_get_db_post_option($profile_id, 'resume', true);
							if (!empty($resume)) {
								$file_detail   		= Workreap_file_permission::getDecrpytFile($resume);
								$name            	= $file_detail['filename'];

								$resume_doc_attachment_id   = !empty($resume['attachment_id']) ? $resume['attachment_id'] : '';
								$resume_doc_document_name   = !empty($name) ? $name : '';
								$resume_doc_file_size       = !empty($resume['attachment_id']) && !empty(get_attached_file($resume['attachment_id'])) ? filesize(get_attached_file($resume['attachment_id'])) : '';
								$resume_doc_filetype        = !empty($resume['url']) ? wp_check_filetype($resume['url']) : '';

								$resume_doc = array(
									'attachment_id'		=> $resume_doc_attachment_id,
									'document_name'		=> $resume_doc_document_name,
									'file_size'			=> $resume_doc_file_size,
									'filetype'			=> $resume_doc_filetype,
								);
							}
						}
					}

					if (!empty($frc_remove_freelancer_type) && $frc_remove_freelancer_type == 'no') {
						$db_freelancer_type 	= get_post_meta($profile_id, '_freelancer_type', true);
						if (!empty($db_freelancer_type) && is_array($db_freelancer_type)) {
							foreach ($db_freelancer_type as $freelancer_type) {
								$freelancer_type_array[]	= $freelancer_type;
							}
						} else if (!empty($db_freelancer_type)) {
							$freelancer_type_array[]	= $db_freelancer_type;
						}
					}

					if (!empty($frc_english_level) && $frc_english_level == 'no') {
						$db_english_level	= get_post_meta($profile_id, '_english_level', true);
					}

					if (!empty($frc_remove_languages) && $frc_remove_languages == 'no') {
						$db_languages	= wp_get_post_terms($profile_id, 'languages');
						$db_languages 	= implode(',', wp_list_pluck($db_languages, 'slug'));
						$db_languages   = explode(",", $db_languages);
					}

					$attachment_id	= get_post_thumbnail_id($profile_id);
					if (!empty($attachment_id)) {
						$image_url 			= !empty($attachment_id) ? wp_get_attachment_image_src($attachment_id, 'workreap_freelancer', true) : '';
						$file_size 			= !empty(get_attached_file($attachment_id)) ? filesize(get_attached_file($attachment_id)) : '';
						$document_name   	= !empty($attachment_id) ? esc_html(get_the_title($attachment_id)) : '';
						$file_url     		= !empty($image_url) ? wp_check_filetype($image_url[0]) : '';
						$file_extention  	= !empty($file_url['ext']) ? $file_url['ext'] : '';

						$profile_photo = array(
							'attachment_id'		=> $attachment_id,
							'image_url'			=> $image_url[0],
							'file_size'			=> $file_size,
							'document_name'		=> $document_name,
							'extention'			=> $file_extention,
						);
					}

					$items = array(
						'display_name'				=> !empty($user_info->display_name) ? esc_attr($user_info->display_name) : '',
						'first_name'				=> !empty($first_name) ? esc_attr($first_name) : '',
						'last_name'					=> !empty($last_name) ? esc_attr($last_name) : '',
						'content' 	 				=> !empty($post_object->post_content) ? $post_object->post_content : '',
						'tag_line' 					=> !empty($tag_line) ? $tag_line : '',
						'address'					=> !empty($address) ? esc_attr($address) : '',
						'latitude'					=> !empty($latitude) ? esc_attr($latitude) : '',
						'longitude'					=> !empty($longitude) ? esc_attr($longitude) : '',
						'location'					=> isset($location_array) && !empty($location_array) ? $location_array : '',
						'per_hour_rate'     		=> !empty($hourly_rate) ? $hourly_rate : '',
						'gender'					=> !empty($gender) ? esc_attr($gender) : '',
						'max_price'					=> !empty($max_price) ? esc_attr($max_price) : '',
						'freelancer_types'			=> !empty($db_freelancer_type) ? ($db_freelancer_type) : array(),
						'english_level'				=> !empty($db_english_level) ? esc_attr($db_english_level) : '',
						'phone_number'				=>  !empty($user_phone_number) ? $user_phone_number : '',
						'languages'					=> !empty($db_languages) ? ($db_languages) : '',
						'freelancer_gallery'		=> !empty($gallery_images) ? ($gallery_images) : '',
						'profile_photo'				=> !empty($profile_photo) ? ($profile_photo) : array(),
						'banner_image_info'			=> !empty($banner_image_info) ? ($banner_image_info) : array(),
						'resume_doc'				=> !empty($resume_doc) ? ($resume_doc) : '',
						'skill_field_type'			=> !empty($field_type) ? ($field_type) : '',
						'skills'					=> !empty($skills_array) ? ($skills_array) : array(),
						'experience'				=> !empty($experience) ? $experience : array(),
						'education'					=> !empty($education) ? $education : array(),
						'projects'					=> !empty($projects_array) ? $projects_array : array(),
						'awards'					=> !empty($awards_array) ? $awards_array : array(),
						'videos'					=> !empty($videos) ? $videos : array(),
						'specializations'			=> !empty($specialization_array) ? $specialization_array : array(),
						'industrial_experiences'	=> !empty($industrial_experiences_array) ? $industrial_experiences_array : array(),
						'socials_status'			=> !empty($socials_status) ? $socials_status : 'disable',
						'social_media'				=> !empty($social_array) ? $social_array : array(),
						'faqs'						=> !empty($faqs) ? array_values($faqs) : array(),
						'type'						=> 'success',
						'message'					=> esc_html__('profile Settings.', 'workreap_api'),
					);

					return new WP_REST_Response($items, 200);
				} else {
					$json['type']   	= 'error';
					$json['message']    = esc_html__('Profile not exist!', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type']   	= 'error';
				$json['message']    = esc_html__('User id is required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Reason for delete account
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_remove_reasons($data)
		{
			$headers    	= $data->get_headers();
			$request     	= !empty($data->get_params()) ? $data->get_params() : array();
			$reasons		= workreap_get_account_delete_reasons();
			$reasons_arr 	= array();

			if (!empty($reasons)) {
				foreach ($reasons as $key => $value) {
					$reasons_arr[] = array(
						'key' => $key,
						'value' => $value,
					);
				}
			}

			$json = array(
				'type'   	=> 'success',
				'reason'  	=> $reasons_arr,
			);

			return new WP_REST_Response($json, 200);
		}

		/**
		 * Help and support FAQ's
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function help_support_faq($request)
		{
			$help = array();
			if (function_exists('fw_get_db_settings_option')) {
				$help 	= fw_get_db_settings_option('help_support');
			}
			$access		= !empty($help['gadget']) ? $help['gadget'] : '';

			if (!empty($access) && $access === 'enable') {
				$title			= !empty($help['enable']['help_title']) ? $help['enable']['help_title'] : '';
				$desc			= !empty($help['enable']['help_desc']) ? $help['enable']['help_desc'] : '';
				$faqs			= !empty($help['enable']['faq'])	? $help['enable']['faq'] : '';
				$query_type		= workreap_support_type();
				$query_types	= !empty($query_type) ? $query_type : array();

				$faqs_arr = array();
				if (!empty($faqs)) {
					foreach ($faqs as $faq) {
						if (!empty($faq['faq_question'])) {
							$faqs_arr[] = array(
								'question' 	=> $faq['faq_question'],
								'answer' 	=> $faq['faq_answer'],
							);
						}
					}
				}

				$query_arr = array();
				if (!empty($query_type)) {
					foreach ($query_types as $key => $val) {
						$query_arr[] = array(
							'key' 		=> esc_attr($key),
							'value' 	=> esc_attr($val)
						);
					}
				}

				$help_support = array(
					'title' 	=> $title,
					'desc' 		=> $desc,
					'faqs' 		=> $faqs_arr,
					'query' 	=> $query_arr,
				);

				$json['type']   	= 'success';
				$json['result']    	= $help_support;
				return new WP_REST_Response($json, 200);
			} else {
				$json = array(
					'type' 		=> 'error',
					'message' 	=> esc_html__('Access is disabled.', 'workreap_api'),
				);

				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Support Query
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function help_support_query($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id 		= !empty($request['user_id']) ? $request['user_id'] : 0;
			$query_type 	= !empty($request['query_type']) ? $request['query_type'] : '';
			$details 		= !empty($request['details']) ? $request['details'] : '';

			if (empty($details)) {
				$json = array(
					'type' 		=> 'error',
					'message' 	=> esc_html__('Message is required.', 'workreap_api'),
				);

				return new WP_REST_Response($json, 203);
			} else if (empty($query_type)) {
				$json = array(
					'type' 		=> 'error',
					'message' 	=> esc_html__('Query type is required.', 'workreap_api'),
				);

				return new WP_REST_Response($json, 203);
			} else if (!empty($details) && !empty($query_type)) {
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapHelp')) {
						$email_helper = new WorkreapHelp();
						$user_data = get_userdata($user_id);
						$emailData 	  = array();
						$user_name 			= workreap_get_username($user_id);
						$profile 			= workreap_get_linked_profile_id($user_id);
						$user_profile 		= get_the_permalink($profile);

						$emailData['user_name'] 		= esc_attr($user_name);
						$emailData['user_email'] 		= esc_attr($user_data->user_email);
						$emailData['user_link'] 		= esc_url($user_profile);
						$emailData['query_type'] 		= esc_attr($query_type);
						$emailData['message'] 			= esc_html($details);

						$email_helper->send_admin_help($emailData);
					}
				}

				$json = array(
					'type' 		=> 'error',
					'message' 	=> esc_html__('Message has sent.', 'workreap_api'),
				);

				return new WP_REST_Response($json, 200);
			} else {
				$json = array(
					'type' 		=> 'error',
					'message' 	=> esc_html__('Some error occur, please try again later.', 'workreap_api'),
				);

				return new WP_REST_Response($json, 203);
			}
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidAppProfileSettingRoutes;
		$controller->register_routes();
	}
);
