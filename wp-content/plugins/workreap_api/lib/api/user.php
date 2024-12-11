<?php

/**
 * APP API to manage users
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           WorkreapAppApi
 *
 */
if (!class_exists('AndroidApp_User_Route')) {

	class AndroidApp_User_Route extends WP_REST_Controller
	{

		/**
		 * Register the routes for the user.
		 */
		public function register_routes()
		{
			$version 	= '1';
			$namespace 	= 'api/v' . $version;
			$base 		= 'user';

			//user login
			register_rest_route(
				$namespace,
				'/' . $base . '/do_login',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_items'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'user_login'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// For signup
			register_rest_route(
				$namespace,
				'/' . $base . '/signup',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'signup'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// For signup
			register_rest_route(
				$namespace,
				'/' . $base . '/register_social',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'registration_social'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// For signup
			register_rest_route(
				$namespace,
				'/' . $base . '/social_second_form',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'registration_social_second_form'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// For signup
			register_rest_route(
				$namespace,
				'/' . $base . '/resend_code',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'resend_code'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// For verification code
			register_rest_route(
				$namespace,
				'/' . $base . '/account_verification',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'account_verification'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// For package info
			register_rest_route(
				$namespace,
				'/' . $base . '/check_package',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'check_package'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// For package info
			register_rest_route(
				$namespace,
				'/' . $base . '/get_access',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_access'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/get_user_balance',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_user_balance'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//user login
			register_rest_route(
				$namespace,
				'/' . $base . '/identity_verfication',
				array(
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'identity_verfication'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//identity status
			register_rest_route(
				$namespace,
				'/' . $base . '/identity_status',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'identity_status'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			//workreap_cancel_verification_request

			register_rest_route(
				$namespace,
				'/' . $base . '/cancel_verification_request',
				array(
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'workreap_cancel_verification_request'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//user login
			register_rest_route(
				$namespace,
				'/' . $base . '/do_logout',
				array(
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'do_logout'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//favorite List
			register_rest_route(
				$namespace,
				'/' . $base . '/favorite',
				array(
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'do_favorite'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//forgot password
			register_rest_route(
				$namespace,
				'/' . $base . '/forgot_password',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_items'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'get_forgot_password'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//User Reporting
			register_rest_route(
				$namespace,
				'/' . $base . '/reporting',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_items'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'reporting_user'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/create_checkout_page',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array($this, 'create_checkout_page'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//get theme settings 
			register_rest_route(
				$namespace,
				'/' . $base . '/notification_count',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array($this, 'notification_count'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			//get theme settings 
			register_rest_route(
				$namespace,
				'/' . $base . '/get_theme_settings',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array($this, 'get_theme_settings'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// update billing 
			register_rest_route(
				$namespace,
				'/' . $base . '/update_billing',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_billing'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//Update onesignal player ids
			register_rest_route(
				$namespace,
				'/' . $base . '/update_onesignal_playerids',
				array(
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'update_onesignal_player'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//remove onesignal player id
			register_rest_route(
				$namespace,
				'/' . $base . '/remove_onesignal_playerids',
				array(
					array(
						'methods' => WP_REST_Server::CREATABLE,
						'callback' => array(&$this, 'remove_onesignal_playerids'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);
		}
		/**
		 * Get theme settings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function get_theme_settings($data)
		{
			global $current_user;
			$json					= array();
			$headers    			= $data->get_headers();
			$request    			= !empty($data->get_params()) ? $data->get_params() : array();
			$user_id				= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$job_type 				= workreap_get_job_option();
			$job_statuses			= worktic_job_statuses();
			$login_register  		= fw_get_db_settings_option('enable_login_register');
			$phone_option	  		= fw_get_db_settings_option('phone_option');
			$hide_perhour	        = fw_get_db_settings_option('hide_freelancer_perhour', $default_value = null);
			$is_register			= !empty($login_register['enable']['registration']['gadget']) ? $login_register['enable']['registration']['gadget'] : '';
			$login_signup_type		= !empty($login_register['enable']['login_signup_type']) ? $login_register['enable']['login_signup_type'] : '';
			$default_role			= !empty($login_register['enable']['default_role']) ? $login_register['enable']['default_role'] : '';
			$remove_username		= !empty($login_register['enable']['remove_username']) ? $login_register['enable']['remove_username'] : '';
			$hide_loaction			= !empty($login_register['enable']['registration']['enable']['hide_loaction']) ? $login_register['enable']['registration']['enable']['hide_loaction'] : '';
			$term_text				= !empty($login_register['enable']['registration']['enable']['term_text']) ? $login_register['enable']['registration']['enable']['term_text'] : '';
			$term_page_link			= !empty($login_register['enable']['registration']['enable']['terms_link'][0]) ? get_the_permalink($login_register['enable']['registration']['enable']['terms_link'][0]) : '';
			$remove_registration	= !empty($login_register['enable']['remove_role_registration']) && $login_register['enable']['remove_role_registration'] != 'both' ? $login_register['enable']['remove_role_registration'] : '';
			$job_experience__type   = fw_get_db_settings_option('job_experience_option', $default_value = null);

			if (function_exists('fw_get_db_settings_option')) {
				$json['term_text']						= !empty($term_text) ? $term_text : '';
				$json['default_role']					= !empty($default_role) ? $default_role : '';
				$json['hide_loaction']					= !empty($hide_loaction) ? $hide_loaction : '';
				$json['hide_perhours']			    	= !empty($hide_perhour) ? $hide_perhour : 'no';
				$json['phone_setting']					= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
				$json['term_page_link']					= !empty($term_page_link) ? $term_page_link : '';
				$json['phone_mandatory']				= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
				$json['remove_username']				= !empty($remove_username) ? $remove_username : '';
				$json['phone_option_reg']				= !empty($phone_option['enable']['phone_option_registration']) ? $phone_option['enable']['phone_option_registration'] : '';
				$json['login_signup_type']				= !empty($login_signup_type) ? $login_signup_type : '';
				$json['registration_option']			= !empty($is_register) ? $is_register : '';
				$json['remove_registration']			= !empty($remove_registration) ? $remove_registration : '';

				$json['hide_map']						= fw_get_db_settings_option('hide_map');
				$json['verify_user']					= fw_get_db_settings_option('verify_user', 'none');
				$json['remove_saved']					= fw_get_db_settings_option('remove_saved');
				$json['chat_settings']					= fw_get_db_settings_option('chat');
				$json['system_access']					= fw_get_db_settings_option('system_access', 'paid');
				$json['switch_account']					= fw_get_db_settings_option('account_types_permissions');
				$json['default_skills']					= fw_get_db_settings_option('default_skills');
				$json['calendar_format']				= fw_get_db_settings_option('calendar_format');
				$json['calendar_locale']				= fw_get_db_settings_option('calendar_locale');
				$json['icon_total_jobs']				= workreap_add_protocol(fw_get_db_settings_option('total_jobs'));
				$json['price_filter_end']				= fw_get_db_settings_option('price_filter_end', 100);
				$json['shortname_option']				= fw_get_db_settings_option('shortname_option');
				$json['icon_saved_items']				= workreap_add_protocol(fw_get_db_settings_option('saved_items'));
				$json['projects_per_page']				= fw_get_db_settings_option('projects_per_page');
				$json['services_per_page']				= fw_get_db_settings_option('services_per_page');
				$json['review_service_status']			= fw_get_db_settings_option('service_status');
				$json['icon_new_messages']				= workreap_add_protocol(fw_get_db_settings_option('new_messages'));
				$json['job_option_setting']     		= fw_get_db_settings_option('job_option', $default_value = null);
				$json['price_filter_start']				= fw_get_db_settings_option('price_filter_start', 0);
				$json['application_access']				= fw_get_db_settings_option('application_access', 'both');
				$json['employers_per_page']				= fw_get_db_settings_option('employers_per_page');
				$json['hide_online_status']				= fw_get_db_settings_option('hide_status');
				$json['remove_location_job']			= fw_get_db_settings_option('remove_location_job');
				$json['portfolios_per_page']			= fw_get_db_settings_option('portfolios_per_page');
				$json['icon_package_expiry']			= workreap_add_protocol(fw_get_db_settings_option('package_expiry'));
				$json['services_categories']			= fw_get_db_settings_option('services_categories', 'yes');
				$json['freelancers_per_page']			= fw_get_db_settings_option('freelancers_per_page');
				$json['remove_response_time']			= fw_get_db_settings_option('remove_response_time');
				$json['remove_dilivery_time']			= fw_get_db_settings_option('remove_dilivery_time');
				$json['icon_total_employers']			= workreap_add_protocol(fw_get_db_settings_option('total_employers'));
				$json['identity_verification']			= fw_get_db_settings_option('identity_verification');
				$json['remove_service_videos']			= fw_get_db_settings_option('remove_service_videos');
				$json['icon_latest_proposals']			= workreap_add_protocol(fw_get_db_settings_option('latest_proposals'));
				$json['icon_total_freelancers']			= workreap_add_protocol(fw_get_db_settings_option('total_freelancers'));
				$json['icon_total_ongoing_job']			= workreap_add_protocol(fw_get_db_settings_option('total_ongoing_job'));
				$json['icon_total_completed_job']		= workreap_add_protocol(fw_get_db_settings_option('total_completed_job'));
				$json['icon_total_cancelled_job']		= workreap_add_protocol(fw_get_db_settings_option('total_cancelled_job'));
				$json['icon_current_balance_img']		= workreap_add_protocol(fw_get_db_settings_option('current_balance_img'));
				$json['icon_total_sold_services']		= workreap_add_protocol(fw_get_db_settings_option('total_sales_services'));
				$json['remove_chat_from_packages']		= fw_get_db_settings_option('remove_chat', 'no');
				$json['icon_avalible_balance_img']		= workreap_add_protocol(fw_get_db_settings_option('avalible_balance_img'));
				$json['remove_project_attachments']		= fw_get_db_settings_option('remove_project_attachments');
				$json['icon_total_ongoing_services']	= workreap_add_protocol(fw_get_db_settings_option('total_ongoing_services'));
				$json['icon_total_completed_services']	= workreap_add_protocol(fw_get_db_settings_option('total_completed_services'));
				$json['icon_total_cancelled_services']	= workreap_add_protocol(fw_get_db_settings_option('total_cancelled_services'));
				$json['icon_total_completed_services']	= workreap_add_protocol(fw_get_db_settings_option('total_completed_services'));
				$json['icon_total_completed_services']	= workreap_add_protocol(fw_get_db_settings_option('total_completed_services'));
				$json['employer_identity_verification']	= fw_get_db_settings_option('employer_identity_verification');

				/* app slider work */
				$app_slider_home	    = fw_get_db_settings_option('app_slider', $default_value = null);
				$app_slider_home	    = !empty($app_slider_home) ? $app_slider_home : array();
				$app_slider = array();

				if (!empty($app_slider_home)) {
					if ($app_slider_home['gadget'] === 'enable') {
						if (!empty($app_slider_home['enable']['links'])) {
							foreach ($app_slider_home['enable']['links'] as $slider_val) {
								if (!empty($slider_val['app_slider_image'])) {

									$slideImage = array();
									if (!empty($slider_val['app_slider_image'])) {
										$slideImage = array(
											'id' => $slider_val['app_slider_image']['attachment_id'],
											'url' => workreap_add_http($slider_val['app_slider_image']['url']),
										);
									}

									$app_slider[] = array(
										'image' => !empty($slideImage) ? $slideImage : '',
										'title'	=> !empty($slider_val['app_slider_heading']) ? $slider_val['app_slider_heading'] : '',
										'desc'	=> !empty($slider_val['app_slider_desc']) ? $slider_val['app_slider_desc'] : '',
									);
								}
							}
						}
					}
				}
				$json['app_slider']			= $app_slider;

				/* gender settings */
				$gender_option = array();
				$gender_settings				= fw_get_db_settings_option('gender_settings');
				$json['show_gender'] = !empty($gender_settings['gadget'] && $gender_settings['gadget'] == 'yes') ? 'yes' : 'no';
				if (!empty($gender_settings) && $gender_settings['gadget'] == 'yes') {
					$gender_options = apply_filters('workreap_gender_types', array());
					if (!empty($gender_options)) {
						foreach ($gender_options as $key => $gender_value) {
							$gender_option[$key] = $gender_value;
						}
					}
				}
				$json['gender_settings'] = $gender_option;

				/* Jobs settings */
				$jobs_settings	= $job_experience	= array();
				if (!empty($job_experience__type['gadget']) && $job_experience__type['gadget'] === 'enable') {
					$project_experience	= workreap_get_taxonomy_array('project_experience');
					foreach ($project_experience as $experience) {
						$id		= $experience->term_id;
						$name	= $experience->name;
						$slug	= $experience->slug;
						$job_taxonomy	= array(
							'id'	=> $id,
							'name'	=> $name,
							'slug'	=> $slug,
						);
						$job_experience[]	= $job_taxonomy;
					}
				} else {
					$job_experience	= esc_html('Please Turn on project experience option.', 'workreap_api');
				}

				$jobs_settings['remove_project_level']		= fw_get_db_settings_option('remove_project_level');
				$jobs_settings['remove_project_duration']	= fw_get_db_settings_option('remove_project_duration');
				$jobs_settings['remove_location_type']		= fw_get_db_settings_option('job_option');
				$jobs_settings['job_experience_option']		= fw_get_db_settings_option('job_experience_option');
				$jobs_settings['project_type']				= fw_get_db_settings_option('project_type_show');
				$jobs_settings['remove_english_level']		= fw_get_db_settings_option('remove_english_level');
				$jobs_settings['remove_freelancer_type']	= fw_get_db_settings_option('remove_freelancer_type');
				$jobs_settings['remove_languages']			= fw_get_db_settings_option('remove_languages');
				$jobs_settings['multiselect_freelancertype'] = fw_get_db_settings_option('multiselect_freelancertype');
				$jobs_settings['job_milestone_option']		= fw_get_db_settings_option('job_milestone_option');
				$jobs_settings['job_price_option']			= fw_get_db_settings_option('job_price_option');
				$jobs_settings['job_price_option']			= fw_get_db_settings_option('job_price_option');
				$jobs_settings['attachment_display']		= fw_get_db_settings_option('attachment_display');
				$jobs_settings['job_faq_option']			= fw_get_db_settings_option('job_faq_option');
				$jobs_settings['get_job_location']			= workreap_get_job_option();
				$jobs_settings['get_job_type']				= workreap_get_job_type();
				$jobs_settings['get_employees_list']		= worktic_get_employees_list();
				$jobs_settings['project_experience']		= $job_experience;
				$jobs_settings['allow_delete_project']		= fw_get_db_settings_option('allow_delete_project', $default_value = null);
				$json['project_settings']					= $jobs_settings;

				//Service Settings
				$services_settings		= array();
				$services_settings['minimum_service_price']			= fw_get_db_settings_option('minimum_service_price');
				$services_settings['limit_service_images']			= fw_get_db_settings_option('default_service_images');
				$services_settings['remove_service_addon']			= fw_get_db_settings_option('remove_service_addon');
				$services_settings['remove_service_languages']		= fw_get_db_settings_option('remove_service_languages');
				$services_settings['remove_service_english_level']	= fw_get_db_settings_option('remove_service_english_level');
				$services_settings['remove_service_downloadable']	= fw_get_db_settings_option('remove_service_downloadable');
				$services_settings['services_categories']			= fw_get_db_settings_option('services_categories');
				$services_settings['service_faq_option']			= fw_get_db_settings_option('service_faq_option');
				$services_settings['service_video_option']			= fw_get_db_settings_option('service_video_option');
				$json['services_settings']							= $services_settings;

				//Freelancers Settings 
				$freelancers_settings	= array();
				$freelancers_settings['freelancer_social_profile_settings']		= fw_get_db_settings_option('freelancer_social_profile_settings');
				$freelancers_settings['freelancer_price_option']				= fw_get_db_settings_option('freelancer_price_option');
				$freelancers_settings['detail_page_stats']						= fw_get_db_settings_option('freelancer_stats');
				$freelancers_settings['hide_detail_page_earning']				= fw_get_db_settings_option('hide_freelancer_earning');
				$freelancers_settings['freelancer_gallery_option']				= fw_get_db_settings_option('freelancer_gallery_option');
				$freelancers_settings['frc_remove_freelancer_type']				= fw_get_db_settings_option('frc_remove_freelancer_type');
				$freelancers_settings['frc_remove_awards']						= fw_get_db_settings_option('frc_remove_awards');
				$freelancers_settings['frc_remove_experience']					= fw_get_db_settings_option('frc_remove_experience');
				$freelancers_settings['frc_remove_education']					= fw_get_db_settings_option('frc_remove_education');
				$freelancers_settings['freelancertype_multiselect']				= fw_get_db_settings_option('freelancertype_multiselect');
				$freelancers_settings['freelancer_industrial_experience']		= fw_get_db_settings_option('freelancer_industrial_experience');
				$freelancers_settings['freelancer_specialization']				= fw_get_db_settings_option('freelancer_specialization');
				$freelancers_settings['frc_remove_languages']					= fw_get_db_settings_option('frc_remove_languages');
				$freelancers_settings['frc_english_level']						= fw_get_db_settings_option('frc_english_level');
				$freelancers_settings['skills_display_type']					= fw_get_db_settings_option('display_type');
				$freelancers_settings['freelancer_insights']					= fw_get_db_settings_option('freelancer_insights');
				$freelancers_settings['allow_custom_skills']					= fw_get_db_settings_option('allow_skills');
				$freelancers_settings['portfolio']								= fw_get_db_settings_option('portfolio');
				$freelancers_settings['hide_freelancer_perhour']				= fw_get_db_settings_option('hide_freelancer_perhour');
				$freelancers_settings['upload_resume']							= fw_get_db_settings_option('upload_resume');
				$freelancers_settings['freelancer_faq_option']					= fw_get_db_settings_option('freelancer_faq_option');
				$freelancers_settings['freelancer_profile_health']				= fw_get_db_settings_option('profile_strength_fields');
				$freelancers_percent			                        		= fw_get_db_settings_option('hide_profiles');
				$freelancers_settings['freelancer_health_percent']				= 0;
				if (!empty($freelancers_percent['gadget']) && $freelancers_percent['gadget'] === 'yes') {
					if (!empty($freelancers_percent['yes']['define_percentage'])) {
						$freelancers_settings['freelancer_health_percent']		= intval($freelancers_percent['yes']['define_percentage']);
					}
				}

				/* freelancer commition type for project */
				$freelancers_proj_percent	= fw_get_db_settings_option('service_fee');
				$commition_tier_arr = $commition_arr = array();
				$commition_type		= !empty($freelancers_proj_percent['gadget']) ? $freelancers_proj_percent['gadget'] : '';
				if ($commition_type === 'fixed') {
					$commition_arr['fixed'] = $freelancers_proj_percent['fixed']['amount'];
				} elseif ($commition_type === 'percentage') {
					$commition_arr['percentage'] = $freelancers_proj_percent['percentage']['percentage'];
				} elseif ($commition_type === 'comissions_tiers') {
					$commition_tier = !empty($freelancers_proj_percent['comissions_tiers']) ? $freelancers_proj_percent['comissions_tiers'] : array();
					if (!empty($commition_tier)) {
						foreach ($commition_tier['add_tiers'] as $tier_val) {
							$commition_tier_arr[] = array(
								'type' 			=> $tier_val['type'],
								'range' 		=> $tier_val['range'],
								'amount' 		=> $tier_val['amount'],
							);
						}
						$commition_arr['comissions_tiers'] = $commition_tier_arr;
					}
				}
				$freelancers_settings['remove_project_commition'] = ($commition_type === 'none') ? 'yes' : 'no';
				$freelancers_settings['freelancer_project_commition_settings'] = $commition_arr;

				/* freelancer commition for services */
				$freelancers_service_commition			= fw_get_db_settings_option('service_commision');
				$service_commition_tier_arr = $commition_arr 	= array();
				$service_commition_type		= !empty($freelancers_service_commition['gadget']) ? $freelancers_service_commition['gadget'] : '';
				if ($service_commition_type === 'fixed') {
					$commition_arr['fixed'] = $freelancers_service_commition['fixed']['amount'];
				} elseif ($service_commition_type === 'percentage') {
					$commition_arr['percentage'] = $freelancers_service_commition['percentage']['percentage'];
				} elseif ($service_commition_type === 'comissions_tiers') {
					$commition_tier = !empty($freelancers_service_commition['comissions_tiers']) ? $freelancers_service_commition['comissions_tiers'] : array();
					if (!empty($commition_tier)) {
						foreach ($commition_tier['add_tiers'] as $tier_val) {
							$service_commition_tier_arr[] = array(
								'type' 			=> $tier_val['type'],
								'range' 		=> $tier_val['range'],
								'amount' 		=> $tier_val['amount'],
							);
						}
						$commition_arr['comissions_tiers'] = $service_commition_tier_arr;
					}
				}
				$freelancers_settings['remove_service_commition'] = ($service_commition_type === 'none') ? 'yes' : 'no';
				$freelancers_settings['freelancer_service_commition_settings'] = $commition_arr;

				$json['freelancers_settings']					= $freelancers_settings;
				$json['protocol'] 								= is_ssl() ? 'https:' : 'http:';
				//Employers Settings 
				$employers_settings	= array();
				$employers_settings['comapny_name']				= fw_get_db_settings_option('comapny_name');
				$employers_settings['company_job_title']		= fw_get_db_settings_option('company_job_title');
				$employers_settings['hide_brochures']			= fw_get_db_settings_option('hide_brochures');
				$employers_settings['hide_departments']			= fw_get_db_settings_option('hide_departments');
				$employers_settings['hide_payout_employers']	= fw_get_db_settings_option('hide_payout_employers');
				$employers_settings['employer_insights']		= fw_get_db_settings_option('employer_insights');
				$employers_settings['employer_social_profile_settings']	 = fw_get_db_settings_option('employer_social_profile_settings');

				$json['employers_settings']	= $employers_settings;

				/* google connects */
				$social_settings	= array();
				$social_settings['google']['enable_google_connect'] = fw_get_db_settings_option('enable_google_connect');
				$social_settings['google']['redirect_url'] 			= fw_get_db_settings_option('redirect_url');
				$social_settings['google']['client_id'] 			= fw_get_db_settings_option('client_id');
				$social_settings['google']['client_secret'] 		= fw_get_db_settings_option('client_secret');
				$social_settings['google']['app_name'] 				= fw_get_db_settings_option('app_name');
				$social_settings['google']['g_prefix'] 				= fw_get_db_settings_option('g_prefix');

				/* facebook */
				$social_settings['facebook']['enable_facebook_connect'] = fw_get_db_settings_option('enable_facebook_connect');
				$social_settings['facebook']['redirect_url'] 			= fw_get_db_settings_option('redirect_url');
				$social_settings['facebook']['app_id'] 					= fw_get_db_settings_option('app_id');
				$social_settings['facebook']['app_secret'] 				= fw_get_db_settings_option('app_secret');
				$social_settings['facebook']['fb_prefix'] 				= fw_get_db_settings_option('fb_prefix');

				/* linkedin */
				$social_settings['linkedin']['enable_linkedin_connect'] 		= fw_get_db_settings_option('enable_linkedin_connect');
				$social_settings['linkedin']['redirect_linkedin_url'] 			= fw_get_db_settings_option('redirect_linkedin_url');
				$social_settings['linkedin']['linkedin_client_id'] 				= fw_get_db_settings_option('linkedin_client_id');
				$social_settings['linkedin']['linkedin_client_secret'] 			= fw_get_db_settings_option('linkedin_client_secret');
				$social_settings['linkedin']['linkedin_prefix'] 				= fw_get_db_settings_option('linkedin_prefix');

				$json['social_connect_settings']	= $social_settings;
			}

			$currency					= workreap_get_current_currency();
			$json['currency_symbol'] 	= !empty($currency['symbol']) ? $currency['symbol'] : '$';

			$json['job_types']			= $job_type;
			$json['job_status']			= $job_statuses;
			$json['dispute_options']	= workreap_project_ratings('dispute_options');
			$json['project_ratings']	= workreap_project_ratings();
			$json['services_ratings']	= workreap_project_ratings('services_ratings');

			$employers_settings['employer_social_profile_settings']	 = fw_get_db_settings_option('employer_social_profile_settings');
			$json['delete_account_hide']	= fw_get_db_settings_option('delete_account_hide');
			$json['help_support']			= fw_get_db_settings_option('help_support');
			$json['show_profile_strength']	= fw_get_db_settings_option('show_strength');

			// User meta --> access

			$user_meta		= array();
			$user_meta['access_type']['service_access']	= '';
			$user_meta['access_type']['job_access']		= '';
			if (apply_filters('workreap_system_access', 'service_base') === true) {
				$user_meta['access_type']['service_access']	= 'yes';
			}
			if (apply_filters('workreap_system_access', 'job_base') === true) {
				$user_meta['access_type']['job_access']	= 'yes';
			}
			$service_fee 		= '';
			if (function_exists('fw_get_db_settings_option')) {
				$service_fee    	= fw_get_db_settings_option('service_fee');
			}

			$service_fee 		= '';
			if (function_exists('fw_get_db_settings_option')) {
				$service_fee    	= fw_get_db_settings_option('service_fee');
			}

			$user_meta['theme_name']		= get_bloginfo('name');
			$user_meta['service_fee']		= $service_fee;
			$currency						= workreap_get_current_currency();
			$user_meta['rating_evaluation']	= workreap_project_ratings();
			$user_meta['currency_symbol']	= !empty($currency['symbol']) ? $currency['symbol'] : '$';
			$json['user_meta']				= $user_meta;


			// Filters for freelancers search
			$freelancer_search_filters	= array();
			$freelancer_search_filters['freelancer_avatar_search']			= fw_get_db_settings_option('freelancer_avatar_search');
			$freelancer_search_filters['freelancer_locations']				= fw_get_db_settings_option('freelancer_locations');
			$freelancer_search_filters['freelancer_skills']					= fw_get_db_settings_option('freelancer_skills');
			$freelancer_search_filters['freelancer_per_rate']				= fw_get_db_settings_option('freelancer_rate');
			$freelancer_search_filters['freelancer_type']					= fw_get_db_settings_option('freelancer_type');
			$freelancer_search_filters['freelancer_english']				= fw_get_db_settings_option('freelancer_english');
			$freelancer_search_filters['freelancer_industrial_exprience']	= fw_get_db_settings_option('freelancer_industrial_exprience');
			$freelancer_search_filters['freelancer_specializations']		= fw_get_db_settings_option('freelancer_specializations');
			$freelancer_search_filters['freelancer_languages']				= fw_get_db_settings_option('freelancer_languages');
			$json['freelancer_search_filters']								= $freelancer_search_filters;

			// Filters for job search
			$jobs_search_filters	= array();
			$jobs_search_filters['project_search_restrict']	= fw_get_db_settings_option('project_search_restrict');
			$jobs_search_filters['job_type']				= fw_get_db_settings_option('job_type');
			$jobs_search_filters['job_categories']			= fw_get_db_settings_option('job_categories');
			$jobs_search_filters['job_locations']			= fw_get_db_settings_option('job_locations');
			$jobs_search_filters['job_skills']				= fw_get_db_settings_option('job_skills');
			$jobs_search_filters['job_length']				= fw_get_db_settings_option('job_length');
			$jobs_search_filters['job_freelancer_type']		= fw_get_db_settings_option('job_freelancer_type');
			$jobs_search_filters['job_option_type']			= fw_get_db_settings_option('job_option_type');
			$jobs_search_filters['job_english_level']		= fw_get_db_settings_option('job_english_level');
			$jobs_search_filters['job_exprience_type']		= fw_get_db_settings_option('job_exprience_type');
			$jobs_search_filters['job_languages']			= fw_get_db_settings_option('job_languages');
			$json['jobs_search_filters']					= $jobs_search_filters;

			// Filters for employers search 
			$employers_search_filters	= array();
			$employers_search_filters['employers_search_restrict']	= fw_get_db_settings_option('employers_search_restrict');
			$employers_search_filters['employer_department']		= fw_get_db_settings_option('employer_department');
			$employers_search_filters['employer_employees']			= fw_get_db_settings_option('employer_employees');
			$employers_search_filters['employer_locations']			= fw_get_db_settings_option('employer_locations');
			$employers_search_filters['employer_locations']			= fw_get_db_settings_option('employer_locations');
			$json['employers_search_filters']						= $employers_search_filters;

			// Filters for services search 
			$services_search_filters	= array();

			$services_search_filters['services_search_restrict']	= fw_get_db_settings_option('services_search_restrict');
			$services_search_filters['services_locations']			= fw_get_db_settings_option('services_locations');
			$services_search_filters['services_dilivery']			= fw_get_db_settings_option('services_dilivery');
			$services_search_filters['services_response']			= fw_get_db_settings_option('services_response');
			$services_search_filters['services_languages']			= fw_get_db_settings_option('services_languages');
			$services_search_filters['services_price']				= fw_get_db_settings_option('services_price');
			$services_search_filters['services_english_level']		= fw_get_db_settings_option('services_english_level');
			$json['services_search_filters']						= $services_search_filters;
			if (!empty($user_id)) {
				//$json['package_status']	= apply_filters('workreap_is_listing_free', false, $user_id);
				$json['package_status']	= apply_filters('workreap_show_packages_if_expired', $user_id);
			}
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Resend Verification code
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function resend_code($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$json		= array();

			if (empty($user_id)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('User ID is required.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else {
				$user_info 	= get_userdata($user_id);
				$key_hash 	= rand(1000, 9999);
				update_user_meta($user_id, 'confirmation_key', $key_hash);
				$code 		= $key_hash;
				$email 		= $user_info->user_email;
				$name  		= workreap_get_username($user_id);
				$blogname   = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);


				//Send verification code
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapRegisterEmail')) {
						$email_helper = new WorkreapRegisterEmail();
						$emailData = array();
						$emailData['name'] 				= $name;
						$emailData['email']				= $email;
						$emailData['verification_code'] = $code;
						$emailData['site'] = $blogname;
						$email_helper->send_verification($emailData);
					}
				}

				$json['type'] = 'success';
				$json['message'] = esc_html__('Verification code has sent on your email', 'workreap_api');
				return new WP_REST_Response($json, 200);
			}
		}

		/**
		 * Signup user for application
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function signup($request)
		{
			$json			= array();
			$validations 	= array(
				'username' 			=> esc_html__('User Name field is required', 'workreap_api'),
				'first_name' 		=> esc_html__('First Name is required', 'workreap_api'),
				'last_name' 		=> esc_html__('Last Name is required.', 'workreap_api'),
				'email'  			=> esc_html__('Email field is required.', 'workreap_api'),
				'password' 			=> esc_html__('Password field is required', 'workreap_api'),
				'verify_password' 	=> esc_html__('Verify Password field is required.', 'workreap_api'),
				'user_type'  		=> esc_html__('User type field is required.', 'workreap_api'),
				'termsconditions'  	=> esc_html__('You should agree to terms and conditions.', 'workreap_api'),
			);

			$phone_option_reg	= '';
			if (function_exists('fw_get_db_settings_option')) {
				$phone_option			= fw_get_db_settings_option('phone_option');
				$enable_login_register 	= fw_get_db_settings_option('enable_login_register');
				$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
				$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
				$phone_option_reg	= !empty($phone_option['enable']['phone_option_registration']) ? $phone_option['enable']['phone_option_registration'] : '';
			}

			if (!empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_mandatory) && $phone_mandatory == 'enable') {
				$validations['user_phone_number']	= esc_html__('Phone number is required', 'workreap_api');
			}

			$hide_loaction			= !empty($enable_login_register['enable']['registration']['enable']['hide_loaction']) ? $enable_login_register['enable']['registration']['enable']['hide_loaction'] : '';
			$login_signup_type		= !empty($enable_login_register['enable']['login_signup_type']) ? $enable_login_register['enable']['login_signup_type'] : '';
			if (!empty($phone_mandatory) && $phone_mandatory == 'enable' && !empty($login_signup_type) && $login_signup_type != 'single_step' && !empty($hide_loaction) && $hide_loaction === 'no') {
				$validations['location']	= esc_html__('Location field is required', 'workreap_api');
			}

			if (
				!empty($enable_login_register['gadget'])
				&& !empty($enable_login_register['enable']['remove_username'])
				&& $enable_login_register['gadget'] === 'enable'
				&& $enable_login_register['enable']['remove_username'] === 'yes'
			) {
				unset($validations['username']);
			}

			foreach ($validations as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				}

				//Validate email address
				if ($key === 'email') {
					if (!is_email($request['email'])) {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Please add a valid email address.', 'workreap_api');
						return new WP_REST_Response($json, 203);
					} elseif (email_exists($request['email'])) {
						$json['type'] = 'error';
						$json['message'] = esc_html__('This email is already registered', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}

				if ($key === 'password') {
					if (strlen($request[$key]) < 6) {
						$json['type'] 	 	= 'error';
						$json['message'] 	= esc_html__('Password length should be minimum 6', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}

				if ($key === 'verify_password') {
					if ($request['password'] != $request['verify_password']) {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Password does not match.', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}

				// if ($key == 'user_type') {
				// 	if ($request['user_type'] == 'employer') {
				// 		$employees  = !empty($request['employees']) ? esc_attr($request['employees']) : '';
				// 		$department = !empty($request['department']) ? esc_attr($request['department']) : '';
				// 		if (empty($employees) || empty($department)) {
				// 			$json['type'] 		= 'error';
				// 			$json['message'] 	= esc_html__('Employee and department fields are required.', 'workreap_api');
				// 			return new WP_REST_Response($json, 203);
				// 		}
				// 	}
				// }
			}

			$username	=  !empty($request['username']) ? $request['username'] : '';

			if (!empty($username) && username_exists($username)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Username already registered', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$username 	= !empty($request['username']) ? esc_attr($request['username']) : $request['email'];
			$first_name = !empty($request['first_name']) ? esc_attr($request['first_name']) : '';
			$last_name 	= !empty($request['last_name']) ? esc_attr($request['last_name']) : '';
			$gender 	= !empty($request['gender']) ? esc_attr($request['gender']) : '';
			$email 		= !empty($request['email']) ? ($request['email']) : '';
			$location   = !empty($request['location']) ? esc_attr($request['location']) : '';
			$password  	= !empty($request['password']) ? esc_attr($request['password']) : '';
			$user_type 	= !empty($request['user_type']) ? esc_attr($request['user_type']) : '';
			$department = !empty($request['department']) ? esc_attr($request['department']) : '';
			$employees  = !empty($request['employees']) ? esc_attr($request['employees']) : '';

			//Set User Role
			$db_user_role = 'employers';
			if ($user_type === 'freelancer') {
				$db_user_role = 'freelancers';
			} else {
				$db_user_role = 'employers';
			}

			//User Registration
			$random_password = $password;
			$full_name 		 = $first_name . ' ' . $last_name;
			$user_nicename   = sanitize_title($full_name);

			$userdata = array(
				'user_login'  		=>  $username,
				'user_pass'    		=>  $random_password,
				'user_email'   		=>  $email,
				'user_nicename'   	=>  $user_nicename,
				'display_name'   	=>  $full_name,
			);

			$user_identity 	 = wp_insert_user($userdata);

			if (is_wp_error($user_identity)) {
				$json['type'] 		= "error";
				$json['message'] 	= esc_html__("Some error occur, please try again later.", 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else {
				global $wpdb;
				wp_update_user(array('ID' => esc_sql($user_identity), 'role' => esc_sql($db_user_role), 'user_status' => 1));

				$wpdb->update(
					$wpdb->prefix . 'users',
					array('user_status' => 1),
					array('ID' => esc_sql($user_identity))
				);

				update_user_meta($user_identity, 'first_name', $first_name);
				update_user_meta($user_identity, 'last_name', $last_name);
				update_user_meta($user_identity, 'gender', esc_attr($gender));

				update_user_meta($user_identity, 'show_admin_bar_front', false);
				update_user_meta($user_identity, 'full_name', esc_attr($full_name));

				$key_hash = rand(1000, 9999);
				update_user_meta($user_identity, '_is_verified', 'no');
				update_user_meta($user_identity, 'confirmation_key', $key_hash);

				$protocol = is_ssl() ? 'https' : 'http';

				$verify_link = esc_url(add_query_arg(array(
					'key' => $key_hash . '&verifyemail=' . $email
				), home_url('/', $protocol)));

				//Create Post
				$user_post = array(
					'post_title'    => wp_strip_all_tags($full_name),
					'post_status'   => 'publish',
					'post_author'   => $user_identity,
					'post_type'     => $db_user_role,
				);

				$post_id    = wp_insert_post($user_post);

				if (!is_wp_error($post_id)) {

					$fw_options = array();

					//Update user linked profile
					update_user_meta($user_identity, '_linked_profile', $post_id);
					if (!empty($login_signup_type) && $login_signup_type != 'single_step' && !empty($hide_loaction) && $hide_loaction === 'no') {
						wp_set_post_terms($post_id, $location, 'locations');
					}
					update_post_meta($post_id, '_is_verified', 'no');

					if ($db_user_role == 'employers') {

						update_post_meta($post_id, '_user_type', 'employer');
						update_post_meta($post_id, '_employees', $employees);
						update_post_meta($post_id, '_followers', '');

						//update department
						if (!empty($department)) {
							$department_term = get_term_by('term_id', $department, 'department');
							if (!empty($department_term)) {
								wp_set_post_terms($post_id, $department, 'department');
							}
						}

						//Fw Options
						$fw_options['department']         = array($department);
						$fw_options['no_of_employees']    = $employees;

						$json['no_of_employeees'] 	= $employees;
						$json['department'] 		= array($department);
					} elseif ($db_user_role == 'freelancers') {
						update_post_meta($post_id, '_user_type', 'freelancer');
						update_post_meta($post_id, '_perhour_rate', '');
						update_post_meta($post_id, 'rating_filter', 0);
						update_post_meta($post_id, '_freelancer_type', 'rising_talent');
						update_post_meta($post_id, '_featured_timestamp', 0);
						update_post_meta($post_id, '_english_level', 'basic');
						//extra data in freelancer
						update_post_meta($post_id, '_gender', $gender);
						$fw_options['_perhour_rate']    = '';
						$fw_options['gender']    		= $gender;
					}
					$location_data 	= array();
					if (!empty($login_signup_type) && $login_signup_type != 'single_step' && !empty($hide_loaction) && $hide_loaction === 'no') {
						$locations 		= get_term_by('slug', $location, 'locations');

						if (!empty($locations)) {
							$location_data[0] = $locations->term_id;
							wp_set_post_terms($post_id, $locations->term_id, 'locations');
						}
					}

					$verify_user = '';
					if (function_exists('fw_get_db_post_option')) {
						$dir_latitude 	= fw_get_db_settings_option('dir_latitude');
						$dir_longitude 	= fw_get_db_settings_option('dir_longitude');
						$verify_user 	= fw_get_db_settings_option('verify_user', $default_value = null);
						$verify_user	= !empty($verify_user) ? $verify_user : '';
					} else {
						$dir_latitude	= '';
						$dir_longitude	= '';
					}

					$verify_user	= !empty($verify_user) ? $verify_user : '';
					$tagline		= '';
					update_post_meta($post_id, '_tag_line', $tagline);
					update_post_meta($post_id, '_address', '');
					update_post_meta($post_id, '_latitude', $dir_latitude);
					update_post_meta($post_id, '_longitude', $dir_longitude);

					if (!empty($phone_option_reg) && $phone_option_reg == 'enable' && !empty($phone_setting) && $phone_setting == 'enable') {
						$user_phone_number  				= !empty($request['user_phone_number']) ? $request['user_phone_number'] : '';
						$fw_options['user_phone_number']    = $user_phone_number;
					}

					$fw_options['address']    	= '';
					$fw_options['longitude']    = $dir_longitude;
					$fw_options['latitude']    	= $dir_latitude;
					$fw_options['tag_line']     = $tagline;

					//Update User Profile
					$fw_options['country']            = $location_data;
					fw_set_db_post_option($post_id, null, $fw_options);

					//update privacy settings
					$settings		 = workreap_get_account_settings($user_type);
					if (!empty($settings)) {
						foreach ($settings as $key => $value) {
							$val = $key === '_profile_blocked' ? 'off' : 'on';
							update_post_meta($post_id, $key, $val);
						}
					}

					update_post_meta($post_id, '_linked_profile', $user_identity);
					$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						$emailData = array();
						$emailData['name'] 				= $first_name;
						$emailData['password'] 			= $random_password;
						$emailData['email'] 			= $email;
						$emailData['verification_code'] = $key_hash;
						$emailData['site'] 				= $blogname;
						$emailData['verification_link'] 		= $verify_link;

						//Welcome Email
						if ($db_user_role === 'employers') {
							if (class_exists('WorkreapRegisterEmail')) {
								$email_helper = new WorkreapRegisterEmail();
								$email_helper->send_employer_email($emailData);
							}
						} else if ($db_user_role === 'freelancers') {
							if (class_exists('WorkreapRegisterEmail')) {
								$email_helper = new WorkreapRegisterEmail();
								$email_helper->send_freelacner_email($emailData);
							}
						}

						//Send code
						if (isset($verify_user) && $verify_user === 'verified') {
							$json['verify_user'] 			= 'verified';
							if (class_exists('WorkreapRegisterEmail')) {
								$email_helper = new WorkreapRegisterEmail();
								$email_helper->send_verification($emailData);
							}
						} else {
							$json['verify_user'] 			= 'none';
						}

						//Send admin email
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_admin_email($emailData);
						}
					}

					//Push notification
					$push									= array();
					$push['receiver_id']					= $user_identity;
					$push['%name%']							= workreap_get_username($user_identity);
					$push['%email%']						= $email;
					$push['%password%']						= $random_password;
					$push['%site%']							= $blogname;
					$push['type']							= 'registration';
					$push['%verification_link%']			= $verify_link;
					$push['%replace_email%']				= $email;
					$push['%replace_password%']				= $random_password;
					$push['%replace_site%']					= $blogname;
					$push['%replace_verification_link%']	= $verify_link;

					if ($db_user_role == 'employers') {
						do_action('workreap_user_push_notify', array($user_identity), '', 'pusher_employer_content', $push);
					} elseif ($db_user_role == 'freelancers') {
						do_action('workreap_user_push_notify', array($user_identity), '', 'pusher_freelancers_content', $push);
					}

					do_action('workreap_user_push_notify', array($user_identity), '', 'pusher_verify_content', $push);
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Some error occurs, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				if (isset($verify_user) && $verify_user === 'none') {
					$json_message = esc_html__("Your account have been created. Please wait while your account is verified by the admin.", 'workreap_api');
				} else {
					$json_message = esc_html__("Your account have been created. Please verify your account through verification code, an email have been sent your email address.", 'workreap_api');
				}

				/* JWT Authentication */
				$newuserArr['userId']	= $user_identity;
				$authObj			 	= new WORKREAPAPI_JWT(WorkreapAppGlobalSettings::get_plugin_name(), WorkreapAppGlobalSettings::get_plugin_verion());
				$authToken				= $authObj->getToken($newuserArr);

				$json['type'] 			= 'success';
				$json['user_id']		= $user_identity;
				$json['message'] 		= $json_message;
				$json['authToken'] 		= $authToken;
				return new WP_REST_Response($json, 200);
			}
		}

		/**
		 * Account verification
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function account_verification($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$code 			= !empty($request['code']) ? esc_attr($request['code']) : '';
			$confirmation_key = get_user_meta($user_id, 'confirmation_key', true);
			$confirmation_key = !empty($confirmation_key) ? $confirmation_key : '';
			if (empty($user_id)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('User ID is required field.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if (empty($code)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Verification code is required field.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if ($code === $confirmation_key) {
				update_user_meta($user_id, '_is_verified', 'yes');

				//update post for users verification
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				update_post_meta($linked_profile, '_is_verified', 'yes');

				$user_type						= workreap_get_user_type($user_id);
				$freelancer_package_id			= workreap_get_package_type('package_type', 'trail_freelancer');
				$employer_package_id			= workreap_get_package_type('package_type', 'trail_employer');

				if ($user_type === 'employer' && !empty($employer_package_id)) {
					workreap_update_pakage_data($employer_package_id, $user_id, '', 'employer');
				} else if ($user_type === 'freelancer' && !empty($freelancer_package_id)) {
					workreap_update_pakage_data($freelancer_package_id, $user_id, '', 'freelancer');
				}

				$json['type']		= 'success';
				$json['signup']		= 'yes';
				$json['message'] 	= esc_html__('Your account has been verified successfully!', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No kiddies please', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}
		/**
		 * Create temp chekcout data
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function create_checkout_page($data)
		{
			global $wpdb;
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$payment_data = !empty($request['payment_data']) ? $request['payment_data'] : array();

			if (!empty($payment_data)) {
				$pages = query_posts(array(
					'post_type' => 'page',
					'meta_key'  => '_wp_page_template',
					'meta_value' => 'mobile-checkout.php'
				));

				if (empty($pages)) {
					$json['type'] = "error";
					$json['message'] = esc_html__("Contact with admin to set mobile checkout page.", 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				$insert_data = "insert into `" . MOBILE_APP_TEMP_CHECKOUT . "` set `temp_data`='" . stripslashes($request['payment_data']) . "'";
				$wpdb->query($insert_data);

				if (isset($wpdb->insert_id)) {
					$data_id = $wpdb->insert_id;
				} else {
					$data_id = $wpdb->print_error();
				}

				$json['type'] 		= "success";
				$json['message'] 	= esc_html__("You order has been placed, Please pay to make it complete", "workreap_api");

				$url = null;
				if (!empty($pages[0])) {
					$url = get_page_link($pages[0]->ID) . '?order_id=' . $data_id . '&platform=mobile';
				}

				$json['url'] 		= esc_url_raw($url);
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] = "error";
				$json['message'] = esc_html__("Invalid Param Data", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get a collection of items
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_items($request)
		{
			$items['data'] = array();
			return new WP_REST_Response($items, 200);
		}

		public function get_access()
		{
			$json			= array();
			$user_meta		= array();
			$user_meta['access_type']['service_access']	= '';
			$user_meta['access_type']['job_access']		= '';
			if (apply_filters('workreap_system_access', 'service_base') === true) {
				$user_meta['access_type']['service_access']	= 'yes';
			}
			if (apply_filters('workreap_system_access', 'job_base') === true) {
				$user_meta['access_type']['job_access']	= 'yes';
			}
			$service_fee 		= '';
			if (function_exists('fw_get_db_settings_option')) {
				$service_fee    	= fw_get_db_settings_option('service_fee');
			}

			$service_fee 		= '';
			if (function_exists('fw_get_db_settings_option')) {
				$service_fee    	= fw_get_db_settings_option('service_fee');
			}

			$user_meta['theme_name']	= get_bloginfo('name');
			$user_meta['service_fee']	= $service_fee;
			$currency						= workreap_get_current_currency();
			$user_meta['rating_evaluation']	= workreap_project_ratings();
			$user_meta['currency_symbol']	= !empty($currency['symbol']) ? $currency['symbol'] : '$';
			$json	= maybe_unserialize($user_meta);
			return new WP_REST_Response($json, 200);
		}

		public function get_user_balance()
		{
			$json			= array();
			$user_meta		= array();
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$current_balance = workreap_get_sum_earning_freelancer($user_id, 'completed', 'freelancer_amount');

			$json['type'] 	 = "success";
			$json['message'] = esc_html__("Data returned", 'workreap_api');
			$json['balance'] = workreap_price_format($current_balance, 'return');

			return new WP_REST_Response($json, 200);
		}

		/**
		 * Favorite items
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function do_favorite($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json			= array();
			$user_id 		= !empty($request['user_id']) ? $request['user_id'] : '';
			$favorite_id 	= !empty($request['favorite_id']) ? $request['favorite_id'] : '';
			$type		 	= !empty($request['type']) ? $request['type'] : '';

			if (!empty($user_id) && !empty($favorite_id)) {
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				if (!empty($linked_profile)) {
					if (!empty($type)) {
						if ($type === '_saved_freelancers') {

							$wishlist 			= get_post_meta($linked_profile, '_saved_freelancers', true);
							$wishlist   		= !empty($wishlist) && is_array($wishlist) ? $wishlist : array();
							$favorite_id   		= workreap_get_linked_profile_id($favorite_id);
							$wishlist[] 		= $favorite_id;
							$wishlist   		= array_unique($wishlist);
							update_post_meta($linked_profile, '_saved_freelancers', $wishlist);
							$json['type'] 		= "success";
							$json['message'] 	= esc_html__("Freelancers is successfull added in your favorite list.", 'workreap_api');
							return new WP_REST_Response($json, 200);
						} elseif ($type === '_saved_projects') {

							$wishlist 			= get_post_meta($linked_profile, '_saved_projects', true);
							$wishlist   		= !empty($wishlist) && is_array($wishlist) ? $wishlist : array();
							$wishlist[] 		= $favorite_id;
							$wishlist   		= array_unique($wishlist);
							update_post_meta($linked_profile, '_saved_projects', $wishlist);
							$json['type'] 		= "success";
							$json['message'] 	= esc_html__("Job is successfull added in your favorite list.", 'workreap_api');
							return new WP_REST_Response($json, 200);
						} elseif ($type === '_following_employers') {

							$wishlist 			= get_post_meta($linked_profile, '_following_employers', true);
							$wishlist   		= !empty($wishlist) && is_array($wishlist) ? $wishlist : array();
							$favorite_id   		= workreap_get_linked_profile_id($favorite_id);
							$wishlist[] 		= $favorite_id;
							$wishlist   		= array_unique($wishlist);
							update_post_meta($linked_profile, '_following_employers', $wishlist);
							$json['type'] 		= "success";
							$json['message'] 	= esc_html__("Company is successfull added in your favorite list.", 'workreap_api');
							return new WP_REST_Response($json, 200);
						} elseif ($type === '_saved_services') {

							$wishlist 			= get_post_meta($linked_profile, '_saved_services', true);
							$wishlist   		= !empty($wishlist) && is_array($wishlist) ? $wishlist : array();
							$favorite_id   		= $favorite_id;
							$wishlist[] 		= $favorite_id;
							$wishlist   		= array_unique($wishlist);
							update_post_meta($linked_profile, '_saved_services', $wishlist);
							$json['type'] 		= "success";
							$json['message'] 	= esc_html__("Service is successfull added in your favorite list.", 'workreap_api');
							return new WP_REST_Response($json, 200);
						}
					} else {

						$json['type'] = "error";
						$json['message'] = esc_html__("Type field is required.", 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				} else {

					$json['type'] = "error";
					$json['message'] = esc_html__("Invalid User id.", 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			}
		}

		/**
		 * Set Forgot Password
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_forgot_password($request)
		{
			global $wpdb;
			$json		= array();
			$user_input = !empty($request['email']) ? $request['email'] : '';

			if (empty($user_input)) {

				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please add email address.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else if (!is_email($user_input)) {

				$json['type'] 		= "error";
				$json['message'] 	= esc_html__("Please add a valid email address.", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$user_data = get_user_by('email', $user_input);
			if (empty($user_data) || $user_data->caps['administrator'] == 1) {

				//the condition $user_data->caps[administrator] == 1 to prevent password change for admin users.
				//if you prefer to offer password change for admin users also, just delete that condition.
				$json['type'] 		= "error";
				$json['message'] 	= esc_html__("Invalid E-mail address!", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$user_id    = $user_data->ID;
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
			$username   = workreap_get_username($user_id);
			$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

			if (empty($key)) {

				//generate reset key
				$key = wp_generate_password(20, false);
				$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
			}

			$protocol 	= is_ssl() ? 'https' : 'http';
			$reset_link	= esc_url(add_query_arg(array('action' => 'reset_pwd', 'key' => $key, 'login' => $user_login), home_url('/', $protocol)));

			//Send email to user
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapGetPassword')) {

					//$email_helper = new WorkreapGetPasswordEmail();
					$email_helper = new WorkreapGetPassword();
					$emailData = array();
					$emailData['username']  = $username;
					$emailData['email']     = $user_email;
					$emailData['link']      = $reset_link;
					$email_helper->send($emailData);
				}
			}

			//Push notification
			$push								= array();
			$push['receiver_id']				= $user_id;
			$push['%name%']						= $username;
			$push['%link%']						= $reset_link;
			$push['%account_email%']			= $user_email;
			$push['%replace_link%']				= $reset_link;
			$push['%replace_account_email%']	= $user_email;
			$push['type']						= 'reset_password';
			do_action('workreap_user_push_notify', array($push['receiver_id']), '', 'pusher_lp_content', $push);

			$json['type'] 		= "success";
			$json['message'] 	= esc_html__("A link has been sent, please check your email.", 'workreap_api');
			$json				= maybe_unserialize($json);
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Login user for application
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function user_login($request)
		{
			$json 	= $newuserArr =  array();
			$items 	=  array();
			if (!empty($request['username']) && !empty($request['password'])) {
				$creds = array(
					'user_login' 			=> $request['username'],
					'user_password' 		=> $request['password'],
					'remember' 				=> true
				);

				$user = wp_signon($creds, false);

				if (is_wp_error($user)) {

					$json['type']		= 'error';
					$json['message']	= esc_html__('Some error occur, please try again later.', 'workreap_api');
					return new WP_REST_Response($json, 203);
				} else {

					unset($user->allcaps);
					unset($user->filter);

					$user_metadata	= array();
					$profile_data	= array();
					$shipping		= array();
					$billing		= array();

					$profile_id		= workreap_get_linked_profile_id($user->data->ID);

					$banner_image	= array();
					if (function_exists('fw_get_db_post_option')) {
						$banner_image       = fw_get_db_post_option($profile_id, 'banner_image', true);
					}

					if ('freelancer' === apply_filters('workreap_get_user_type', $user->data->ID)) {
						$user_pmetadata['user_type']	= 'freelancer';
						$user_pmetadata['profile_img'] 	= apply_filters(
							'workreap_freelancer_avatar_fallback',
							workreap_get_freelancer_avatar(array('width' => 355, 'height' => 352), $profile_id),
							array('width' => 355, 'height' => 352)
						);

						$user_pmetadata['banner_img'] 	= apply_filters(
							'workreap_freelancer_banner_fallback',
							workreap_get_freelancer_banner(array('width' => 355, 'height' => 352), $profile_id),
							array('width' => 355, 'height' => 352)
						);
					} else if ('employer' == apply_filters('workreap_get_user_type', $user->data->ID)) {
						$user_pmetadata['user_type']	= 'employer';
						$user_pmetadata['profile_img'] 	=  apply_filters(
							'workreap_employer_avatar_fallback',
							workreap_get_employer_avatar(array('width' => 355, 'height' => 352), $profile_id),
							array('width' => 355, 'height' => 352)
						);
						$user_pmetadata['banner_img'] 	=  apply_filters(
							'workreap_employer_banner_fallback',
							workreap_get_employer_banner(array('width' => 355, 'height' => 352), $profile_id),
							array('width' => 355, 'height' => 352)
						);
					}

					$is_register_complete	= get_user_meta($user->data->ID, 'is_registration_complete', true);
					$is_registration_complete	= !empty($is_register_complete) ? $is_register_complete : false;
					$first_name	= get_user_meta($user->data->ID, 'first_name', true);
					$last_name	= get_user_meta($user->data->ID, 'last_name', true);
					$first_name	= !empty($first_name) ? $first_name : '';
					$last_name	= !empty($last_name) ? $last_name : '';

					$permission			= '';
					if (apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $user->data->ID) === true) {
						$permission		= 'allow';
					} else {
						$permission		= 'notallow';
					}

					/* milestone allowerd in post */
					if (function_exists('fw_get_db_settings_option')) {
						$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
					}
					$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

					$registration_type		= get_user_meta($user->data->ID, 'registration_type', true);
					$registration_type 		= !empty($registration_type) ? $registration_type : '';

					$user_meta	= array(
						'id' 						=> $user->data->ID,
						'profile_id'				=> $profile_id,
						'milestone'					=> $milestone,
						'first_name' 				=> $first_name,
						'last_name' 				=> $last_name,
						'chat_permission'			=> $permission,
						'user_login' 				=> $user->data->user_login,
						'user_pass' 				=> $user->data->user_pass,
						'user_email' 				=> $user->data->user_email,
						'is_registration_complete'	=> $is_registration_complete,
						'registration_type'			=> $registration_type,
					);

					if (function_exists('fw_get_db_settings_option')) {

						$chat_settings    			= fw_get_db_settings_option('chat');
						$user_meta['chat_type']		= !empty($chat_settings['gadget']) ? $chat_settings['gadget'] : 'inbox';
						$user_meta['host']			=  !empty($chat_settings['chat']['host']) ?  $chat_settings['chat']['host'] : '';
						$user_meta['port']			=  !empty($chat_settings['chat']['port']) ?  $chat_settings['chat']['port'] : '';
					}

					$post_meta	= array(
						'_tag_line' 			=> '_tag_line',
						'_gender' 				=> '_gender',
						'_is_verified' 			=> '_is_verified',
						'_featured_timestamp' 	=> '_featured_timestamp'
					);

					foreach ($post_meta as $key => $usermeta) {
						$user_pmetadata[$key] = get_post_meta($profile_id, $key, true);
					}

					$user_meta['service_access']	= '';
					$user_meta['job_access']		= '';

					if (apply_filters('workreap_system_access', 'service_base') === true) {
						$user_meta['service_access']	= 'yes';
					}

					if (apply_filters('workreap_system_access', 'job_base') === true) {
						$user_meta['job_access']	= 'yes';
					}

					$user_meta['listing_type']	= 'free';
					if (apply_filters('workreap_is_listing_free', false, $user->data->ID) === false) {
						$user_meta['listing_type']	= 'paid';
					}

					if (class_exists('WC_Customer')) {
						$customer 	= new WC_Customer($user->data->ID);
						$shipping	= $customer->get_shipping();
						$billing	= $customer->get_billing();
					}

					/* JWT Authentication */
					$newuserArr['userId']			= $user->ID;
					$authObj			  			= new WORKREAPAPI_JWT(WorkreapAppGlobalSettings::get_plugin_name(), WorkreapAppGlobalSettings::get_plugin_verion());
					$authToken						= $authObj->getToken($newuserArr);

					$user_pmetadata['full_name']	= get_the_title($profile_id);
					$profile_data = array(
						'unread_notification_count' => apply_filters('workreap_count_unread_push_notification', $user->data->ID, 'return'),
						'shipping' 	=> maybe_unserialize($shipping),
						'billing' 	=> maybe_unserialize($billing),
						'pmeta' 	=> maybe_unserialize($user_pmetadata),
						'umeta' 	=> maybe_unserialize($user_meta),
					);

					$json = array(
						'authToken' 	=> $authToken,
						'profile' 		=> $profile_data,
						'type' 			=> 'success',
						'message' 		=> esc_html__('You are logged in', 'workreap_api'),
					);

					$items	= maybe_unserialize($json);

					return new WP_REST_Response($items, 200);
				}
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('user name and password are required fields.', 'workreap_api');
				$json				= maybe_unserialize($json);
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Logout user from the application
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */

		public function do_logout($request)
		{
			$json  = array();

			if (!empty($request['user_id'])) {
				$user_id 	= $request['user_id'];
				$sessions 	= WP_Session_Tokens::get_instance($user_id);

				// we have got the sessions, destroy them all!
				$sessions->destroy_all();

				$json['type'] 		= "success";
				$json['message'] 	= esc_html__('You are logged out successfully', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= "error";
				$json['message'] 	= esc_html__('User ID required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Notification count
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function notification_count($request)
		{

			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$json		= array();
			$json['unread_notification_count'] 		= !empty($user_id) ? apply_filters('workreap_count_unread_push_notification', $user_id, 'return') : 0;
			return new WP_REST_Response($json, 200);
		}

		/**
		 * Report user from the application
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function reporting_user($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$type 			= !empty($request['type']) ? esc_attr($request['type']) : '';
			$reported_id 	= !empty($request['id']) ? intval($request['id']) : '';
			$user_id 		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$description 	= !empty($request['description']) ? esc_attr($request['description']) : '';
			$reason 		= !empty($request['reason']) ? esc_attr($request['reason']) : '';
			$json 			= array();

			if (empty($reason) || empty($description)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('All the fields are required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if (empty($user_id)) {
				$json['type']		= 'error';
				$json['message'] 	= esc_html__('You must login before report', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$reasons			= workreap_get_report_reasons();
			$linked_profile   	= workreap_get_linked_profile_id($user_id);
			$title				= !empty($reasons[$reason]) ? $reasons[$reason] : rand(1, 999999);

			//Create Post
			$user_post = array(
				'post_title'    => wp_strip_all_tags($title),
				'post_status'   => 'publish',
				'post_content'  => $description,
				'post_author'   => $user_id,
				'post_type'     => 'reports',
			);

			$post_id    = wp_insert_post($user_post);


			if (!is_wp_error($post_id)) {
				update_post_meta($post_id, '_report_type', $type);
				update_post_meta($post_id, '_user_by', $linked_profile);
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapReportUser')) {
						$email_helper = new WorkreapReportUser();
						$emailData = array();
						$emailData['name'] 				= get_the_title($reported_id);
						$emailData['user_link'] 		= get_edit_post_link($linked_profile);
						$emailData['message'] 			= $description;
						$emailData['reported_by'] 		= workreap_get_username($linked_profile);

						if (!empty($type) && $type === 'employer') {
							$emailData['reported_employer'] 	= get_the_title($reported_id);
							$emailData['employer_link'] 		= get_edit_post_link($reported_id);
							$email_helper->send_employer_report($emailData);
						} else if (!empty($type) && $type === 'project') {
							$emailData['reported_project'] 	= get_the_title($reported_id);
							$emailData['project_link'] 		= get_edit_post_link($reported_id);
							$email_helper->send_project_report($emailData);
						} else if (!empty($type) && $type === 'freelancer') {
							$emailData['reported_freelancer'] 	= get_the_title($reported_id);
							$emailData['freelancer_link'] 		= get_edit_post_link($reported_id);
							$email_helper->send_freelancer_report($emailData);
						} else if (!empty($type) && $type === 'service') {
							$emailData['reported_service'] 	= get_the_title($reported_id);
							$emailData['service_link'] 		= get_edit_post_link($reported_id);
							$email_helper->send_service_report($emailData);
						}
					}
				}

				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your report has submitted', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occurs, please try again later', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Chcek Package
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function check_package($request)
		{
			$type 			= !empty($request['type']) ? esc_attr($request['type']) : '';
			$user_id 		= !empty($request['id']) ? intval($request['id']) : '';
			$json  			= array();
			if (!empty($user_id)) {
				if (apply_filters('workreap_is_feature_allowed', 'packages', $user_id) === false) {
					if ($type === 'featured_service') {
						if (apply_filters('workreap_featured_service', $user_id) === false) {
							$json['type'] 		= 'error';
							$json['message'] 	= esc_html__('You’ve consumed all you points to add featured service.', 'workreap_api');
							$items[] 			= $json;
							return new WP_REST_Response($items, 203);
						} else {
							$json['type'] 		= 'success';
							$json['message'] 	= esc_html__('You have points to add new', 'workreap_api');
							return new WP_REST_Response($json, 200);
						}
					} elseif (apply_filters('workreap_is_feature_job', $type, $user_id) === false) {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('You’ve consumed all you points to add new.', 'workreap_api');
						$items[] 			= $json;
						return new WP_REST_Response($items, 203);
					} else {
						$json['type'] 		= 'success';
						$json['message'] 	= esc_html__('You have points to add new', 'workreap_api');
						return new WP_REST_Response($json, 200);
					}
				}
			}
		}

		/**
		 * Update billing detail
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function update_billing($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id 		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$json  			= array();
			$billing_fields	= apply_filters('workreap_billing_fields', '');
			$billing_fields	= !empty($billing_fields) ? $billing_fields : array();
			if (!empty($billing_fields) && is_array($billing_fields)) {
				foreach ($billing_fields as $meta_key => $meta_value) {
					$value	= !empty($request['billing'][$meta_key]) ? $request['billing'][$meta_key] : '';
					update_user_meta($user_id, $meta_key, sanitize_text_field($value));
				}
			}

			$json['type'] 	 		= "success";
			$json['message']        = esc_html__('Details has been updated', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * * Signup from google social
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function registration_social($data)
		{
			$json		= array();
			$request	= !empty($data->get_params()) ? $data->get_params() : array();
			$email 		= !empty($request['email']) ? sanitize_email($request['email']) : '';

			if (!empty($email)) {
				$user_ID 	= email_exists($email);

				if ($user_ID) { // Already register
					$user_data      = get_user_by('email', $email);
					$user_data      = !empty($user_data) ? $user_data : array();
					$user_identity  = !empty($user_data) ? $user_data->ID : 0;
					$profile_id		= workreap_get_linked_profile_id($user_identity);
					$user_type    	= apply_filters('workreap_get_user_type', $user_identity);

					if ('freelancer' === $user_type) {
						$user_pmetadata['user_type']	= 'freelancer';
						$user_pmetadata['profile_img'] 	= apply_filters(
							'workreap_freelancer_avatar_fallback',
							workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $profile_id),
							array('width' => 100, 'height' => 100)
						);
						$user_pmetadata['banner_img'] 	= apply_filters(
							'workreap_freelancer_banner_fallback',
							workreap_get_freelancer_banner(array('width' => 100, 'height' => 100), $profile_id),
							array('width' => 100, 'height' => 100)
						);
					} else if ('employer' == $user_type) {
						$user_pmetadata['user_type']	= 'employer';
						$user_pmetadata['profile_img'] 	=  apply_filters(
							'workreap_employer_avatar_fallback',
							workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id),
							array('width' => 100, 'height' => 100)
						);
						$user_pmetadata['banner_img'] 	=  apply_filters(
							'workreap_employer_banner_fallback',
							workreap_get_employer_banner(array('width' => 100, 'height' => 100), $profile_id),
							array('width' => 100, 'height' => 100)
						);
					}

					$permission			= '';
					if (apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $user_identity) === true) {
						$permission		= 'allow';
					} else {
						$permission		= 'notallow';
					}

					/* milestone allowerd in post */
					if (function_exists('fw_get_db_settings_option')) {
						$milestone 		= fw_get_db_settings_option('job_milestone_option', $default_value = null);
					}
					$milestone			= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

					$is_register_complete	= get_user_meta($user_identity, 'is_registration_complete', true);
					$is_registration_complete	= !empty($is_register_complete) ? $is_register_complete : false;

					$registration_type	= get_user_meta($user_identity, 'registration_type', true);
					$registration_type 	= !empty($registration_type) ? $registration_type : '';

					$user_meta	= array(
						'id' 							=> $user_identity,
						'profile_id'					=> $profile_id,
						'user_login' 					=> $user_data->display_name,
						'user_pass' 					=> $user_data->user_pass,
						'first_name' 					=> $user_data->first_name,
						'last_name' 					=> $user_data->last_name,
						'user_email' 					=> $user_data->user_email,
						'chat_permission'				=> $permission,
						'milestone'						=> $milestone,
						'is_registration_complete'		=> $is_registration_complete,
						'registration_type'				=> $registration_type,
					);

					if (function_exists('fw_get_db_settings_option')) {
						$chat_settings    			= fw_get_db_settings_option('chat');
						$user_meta['chat_type']		= !empty($chat_settings['gadget']) ? $chat_settings['gadget'] : 'inbox';
						$user_meta['host']			=  !empty($chat_settings['chat']['host']) ?  $chat_settings['chat']['host'] : '';
						$user_meta['port']			=  !empty($chat_settings['chat']['port']) ?  $chat_settings['chat']['port'] : '';
					}

					$post_meta	= array(
						'_tag_line' 			=> '_tag_line',
						'_gender' 				=> '_gender',
						'_is_verified' 			=> '_is_verified',
						'_featured_timestamp' 	=> '_featured_timestamp'
					);

					foreach ($post_meta as $key => $usermeta) {
						$user_pmetadata[$key] = get_post_meta($profile_id, $key, true);
					}

					$user_meta['service_access'] = $user_meta['job_access']		= '';

					if (apply_filters('workreap_system_access', 'service_base') === true) {
						$user_meta['service_access']	= 'yes';
					}

					if (apply_filters('workreap_system_access', 'job_base') === true) {
						$user_meta['job_access']	= 'yes';
					}

					$user_meta['listing_type']	= 'free';
					if (apply_filters('workreap_is_listing_free', false, $user_identity) === false) {
						$user_meta['listing_type']	= 'paid';
					}

					if (class_exists('WC_Customer')) {
						$customer 	= new WC_Customer($user_identity);
						$shipping	= $customer->get_shipping();
						$billing	= $customer->get_billing();
					}

					/* JWT Authentication */
					$newuserArr['userId']			= $user_identity;
					$authObj			  			= new WORKREAPAPI_JWT(WorkreapAppGlobalSettings::get_plugin_name(), WorkreapAppGlobalSettings::get_plugin_verion());
					$authToken						= $authObj->getToken($newuserArr);

					$user_pmetadata['full_name']	= get_the_title($profile_id);
					$profile_data = array(
						'unread_notification_count' => apply_filters('workreap_count_unread_push_notification', $user_identity, 'return'),
						'shipping' 	=> maybe_unserialize($shipping),
						'billing' 	=> maybe_unserialize($billing),
						'pmeta' 	=> maybe_unserialize($user_pmetadata),
						'umeta' 	=> maybe_unserialize($user_meta),
					);

					$json = array(
						'authToken' 	=> $authToken,
						'profile' 		=> $profile_data,
						'type' 			=> 'success',
						'message' 		=> esc_html__('You are logged in', 'workreap_api'),
					);

					$items	= maybe_unserialize($json);
					return new WP_REST_Response($items, 200);
				} else {
					$username       = !empty($request['name']) ? sanitize_text_field($request['name']) : '';
					$last_name      = (strpos($username, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $username);
					$first_name     = trim(preg_replace('#' . preg_quote($last_name, '#') . '#', '', $username));
					$full_name    	= $first_name . ' ' . $last_name;
					$login_type		= !empty($request['login_type']) ? $request['login_type'] : '';
					$login_id		= !empty($request['id']) ? $request['id'] : '';
					$user_token		= !empty($request['user_token']) ? $request['user_token'] : '';

					if (function_exists('fw_get_db_settings_option')) {
						$g_prefix 			= fw_get_db_settings_option('g_prefix');
					}

					$prefix = '';
					if (isset($login_type) && ($login_type === 'google' || $login_type === 'apple') && !empty($g_prefix)) {
						$prefix	= $g_prefix;
					}

					$register_type = ($login_type === 'google' || $login_type === 'apple') ? 'social' : 'web';

					$sanitized_user_login = sanitize_title($prefix . $username);
					if (!validate_username($sanitized_user_login)) {
						$sanitized_user_login = sanitize_title($login_type . $login_id);
					}
					$defaul_user_name = $sanitized_user_login;

					$i = 1;
					while (username_exists($sanitized_user_login)) {
						$sanitized_user_login = $defaul_user_name . $i;
						$i++;
					}

					$userdata = array(
						'user_login'  		=>  $username,
						'user_pass'    		=>  NULL,
						'user_email'   		=>  $email,  
						'user_nicename'   	=>  $full_name,  
						'display_name'   	=>  $full_name,  
					);
					$ID = wp_insert_user( $userdata );

					//$ID = wp_create_user($sanitized_user_login, '', $email);

					if (!is_wp_error($ID)) {
						wp_update_user(array('ID' => esc_sql($ID), 'role' => 'subscriber', 'user_status' => 1));
						update_user_meta($ID, 'show_admin_bar_front', false);
						update_user_meta($ID, 'register_with_social', 'yes');
						update_user_meta($ID, 'company_name', $username);
						update_user_meta($ID, 'first_name', $username);
						update_user_meta($ID, 'email', sanitize_email($email));
						update_user_meta($ID, 'rich_editing', 'true');
						update_user_meta($ID, '_is_verified', 'no');
						update_user_meta($ID, 'registration_type', $register_type);
						//upload avatar
						$url		= !empty($request['picture']) ? $request['picture'] : '';
						if (!empty($url)) {
							$filename	= $login_id . '.jpg';
							$uploaddir 	= wp_upload_dir();
							$uploadfile = $uploaddir['path'] . '/' . $filename;
							$request_url  = wp_remote_get($url);
							$image_string = wp_remote_retrieve_body($request_url);
							$fileSaved 	  = file_put_contents($uploaddir['path'] . "/" . $filename, $image_string);

							$wp_filetype = wp_check_filetype($filename, null);
							$attachment = array(
								'post_mime_type' => $wp_filetype['type'],
								'post_title' => $filename,
								'post_content' => '',
								'post_status' => 'inherit'
							);

							$attach_id = wp_insert_attachment($attachment, $uploadfile);
							require_once(ABSPATH . "wp-admin" . '/includes/image.php');

							$attach_data = wp_generate_attachment_metadata($attach_id, $uploadfile);
							wp_update_attachment_metadata($attach_id,  $attach_data);
							update_user_meta($ID, 'social_avatar', $attach_id);
						}

						/* condition for check registration complete */
						update_user_meta($ID, 'is_registration_complete', false);

						/* update reandom password */
						$user_info = get_userdata($ID);
						update_user_meta($ID, 'new_' . $login_type . '_default_password', $user_info->user_pass);

						$json['type'] 			= 'success';
						$json['second_form'] 	= true;
						$json['user_id'] 		= $ID;
						return new WP_REST_Response($json, 200);
					} else {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Something went wrong!', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Email is missing', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * social register 2nd form
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Request
		 */
		public function registration_social_second_form($data)
		{
			$json				= array();
			$request			= !empty($data->get_params()) ? $data->get_params() : array();
			$email 				= !empty($request['email']) ? sanitize_email($request['email']) : '';

			if (!empty($email)) {
				$location 			= !empty($request['location']) ? esc_html($request['location']) : '';
				$user_type 			= !empty($request['user_type']) ? esc_html($request['user_type']) : '';
				$termsconditions 	= !empty($request['termsconditions']) ? esc_html($request['termsconditions']) : '';
				$employees 			= !empty($request['employees']) ? esc_html($request['employees']) : '';
				$department 		= !empty($request['department']) ? esc_html($request['department']) : '';
				$user_id 			= !empty($request['user_id']) ? intval($request['user_id']) : '';
				$username       	= !empty($request['name']) ? sanitize_text_field($request['name']) : '';
				$login_type			= !empty($request['login_type']) ? $request['login_type'] : '';
				$last_name      	= (strpos($username, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $username);
				$first_name     	= trim(preg_replace('#' . preg_quote($last_name, '#') . '#', '', $username));
				$full_name    		= $first_name . ' ' . $last_name;

				if ($user_type == 'company') {
					if (empty($employees) || empty($department)) {
						$json['type'] = 'error';
						$json['message'] = esc_html__('Employee and department fields are required.', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}

				/* update email in apple case */
				if (!empty($email) && $login_type == 'apple') {
					$args = array(
						'ID'         => $user_id,
						'user_email' => esc_attr($email)
					);
					wp_update_user($args);
				}

				$switch_account	= 'no';
				$user_identity 	 = $user_id;

				/* update shortner names */
				$shortname_option	= '';
				$verify_user = 'no';
				if (function_exists('fw_get_db_settings_option')) {
					$shortname_option	= fw_get_db_settings_option('shortname_option', $default_value = null);
					$verify_user 	= fw_get_db_settings_option('social_verify_user', $default_value = null);
					$milestone	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
				}

				//Set User Role
				$db_user_role = 'employers';
				if ($user_type === 'freelancer') {
					$db_user_role = 'freelancers';
				} else {
					$db_user_role = 'employers';
				}

				//If not switch account
				if (!empty($switch_account) && $switch_account === 'no') {

					update_user_meta($user_identity, '_is_verified', 'no');
					update_user_meta($user_identity, 'termsconditions', $termsconditions);

					//verification link
					$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
					update_user_meta($user_identity, 'confirmation_key', $key_hash);
					$protocol = is_ssl() ? 'https' : 'http';
					$verify_link = esc_url(add_query_arg(array('key' => $key_hash . '&verifyemail=' . $email), home_url('/', $protocol)));

					//Create Post
					$user_post = array(
						'post_title'    => wp_strip_all_tags($full_name),
						'post_status'   => 'publish',
						'post_author'   => $user_identity,
						'post_type'     => $db_user_role,
					);

					$post_id    = wp_insert_post($user_post);

					if (!empty($shortname_option) && $shortname_option === 'enable') {
						$shor_name			= workreap_get_username($user_identity);
						$shor_name_array	= array(
							'ID'        => $post_id,
							'post_name'	=> sanitize_title($shor_name)
						);
						wp_update_post($shor_name_array);
					}

					update_post_meta($post_id, '_linked_profile', $user_identity);

					//Send email to users
					if (class_exists('Workreap_Email_helper')) {

						$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
						$emailData = array();
						$emailData['name'] 				= workreap_get_username($user_identity);
						$emailData['password'] 			= '';
						$emailData['email'] 			= $email;
						$emailData['verification_link'] = $verify_link;
						$emailData['site'] = $blogname;

						//Welcome Email
						if ($db_user_role === 'employers') {
							if (class_exists('WorkreapRegisterEmail')) {
								$email_helper = new WorkreapRegisterEmail();
								$email_helper->send_employer_email($emailData);
							}
						} else if ($db_user_role === 'freelancers') {
							if (class_exists('WorkreapRegisterEmail')) {
								$email_helper = new WorkreapRegisterEmail();
								$email_helper->send_freelacner_email($emailData);
							}
						}

						//Send code
						if (!empty($verify_user) && $verify_user === 'yes') {
							$json['verify_user'] 			= 'verified';
							if (class_exists('WorkreapRegisterEmail')) {
								$email_helper = new WorkreapRegisterEmail();
								$email_helper->send_verification($emailData);
							}
						} else {
							update_user_meta($user_identity, '_is_verified', 'yes');
							update_post_meta($post_id, '_is_verified', 'yes');
						}

						//Send admin email
						if (class_exists('WorkreapRegisterEmail')) {
							$email_helper = new WorkreapRegisterEmail();
							$email_helper->send_admin_email($emailData);
						}
					}
				}

				if (!is_wp_error($post_id)) {
					$fw_options = array();

					//update social profile
					$social_avatar = get_user_meta($user_id, 'social_avatar', true);
					$social_avatar	= !empty($social_avatar) ? $social_avatar : '';
					if (!empty($social_avatar)) {
						delete_post_thumbnail($post_id);
						set_post_thumbnail($post_id, $social_avatar);
					}

					//Update user linked profile
					update_user_meta($user_identity, '_linked_profile', $post_id);
					update_post_meta($post_id, '_linked_profile', $user_identity);
					wp_set_post_terms($post_id, $location, 'locations');
					update_user_meta($user_identity, 'is_registration_complete', true);

					global $wpdb;
					$wp_user_object = new WP_User($user_identity);
					$wp_user_object->set_role($db_user_role);

					$wpdb->update(
						$wpdb->prefix . 'users',
						array('user_status' => 1),
						array('ID' => intval($user_identity))
					);

					if ($db_user_role == 'employers') {
						update_post_meta($post_id, '_user_type', 'employer');
						update_post_meta($post_id, '_employees', $employees);
						update_post_meta($post_id, '_followers', '');

						//update department
						if (!empty($department)) {
							$department_term = get_term_by('term_id', $department, 'department');
							if (!empty($department_term)) {
								wp_set_post_terms($post_id, $department, 'department');
							}
						}

						//Fw Options
						$fw_options['department']         = array($department);
						$fw_options['no_of_employees']    = $employees;

						$user_pmetadata['user_type']	= 'employer';
						$user_pmetadata['profile_img'] 	=  apply_filters(
							'workreap_employer_avatar_fallback',
							workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $post_id),
							array('width' => 100, 'height' => 100)
						);
						$user_pmetadata['banner_img'] 	=  apply_filters(
							'workreap_employer_banner_fallback',
							workreap_get_employer_banner(array('width' => 100, 'height' => 100), $post_id),
							array('width' => 100, 'height' => 100)
						);
					} elseif ($db_user_role == 'freelancers') {
						update_post_meta($post_id, '_user_type', 'freelancer');
						update_post_meta($post_id, '_perhour_rate', '');
						update_post_meta($post_id, 'rating_filter', 0);
						update_post_meta($post_id, '_freelancer_type', 'rising_talent');
						update_post_meta($post_id, '_featured_timestamp', 0);
						update_post_meta($post_id, '_invitation_count', 0);
						update_post_meta($post_id, '_english_level', 'basic');
						update_post_meta($post_id, '_have_avatar', 0);
						update_post_meta($post_id, '_profile_health_filter', 0);
						//extra data in freelancer
						update_post_meta($post_id, '_gender', '');
						$fw_options['_perhour_rate']    = '';
						$fw_options['gender']    		= '';

						/* user postmeta */
						$user_pmetadata['user_type']	= 'freelancer';
						$user_pmetadata['profile_img'] 	= apply_filters(
							'workreap_freelancer_avatar_fallback',
							workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $post_id),
							array('width' => 100, 'height' => 100)
						);
						$user_pmetadata['banner_img'] 	= apply_filters(
							'workreap_freelancer_banner_fallback',
							workreap_get_freelancer_banner(array('width' => 100, 'height' => 100), $post_id),
							array('width' => 100, 'height' => 100)
						);
					}

					//Set country for unyson
					$locations = get_term_by('slug', $location, 'locations');
					$location_data = array();
					if (!empty($locations)) {
						$location_data[0] = $locations->term_id;
						wp_set_post_terms($post_id, $locations->term_id, 'locations');
					}

					if (function_exists('fw_get_db_post_option')) {
						$dir_latitude 	= fw_get_db_settings_option('dir_latitude');
						$dir_longitude 	= fw_get_db_settings_option('dir_longitude');
					} else {
						$dir_latitude	= '';
						$dir_longitude	= '';
					}

					//add extra fields as a null
					$tagline	= '';
					update_post_meta($post_id, '_tag_line', $tagline);
					update_post_meta($post_id, '_address', '');
					update_post_meta($post_id, '_latitude', $dir_latitude);
					update_post_meta($post_id, '_longitude', $dir_longitude);

					$fw_options['address']    	= '';
					$fw_options['longitude']    = $dir_longitude;
					$fw_options['latitude']    	= $dir_latitude;
					$fw_options['tag_line']     = $tagline;
					//end extra data

					//Update User Profile
					$fw_options['country']            = $location_data;
					fw_set_db_post_option($post_id, null, $fw_options);

					//update privacy settings
					$settings		 = workreap_get_account_settings($user_type);
					if (!empty($settings)) {
						foreach ($settings as $key => $value) {
							$val = ($key === '_profile_blocked') ? 'off' : 'on';
							update_post_meta($post_id, $key, $val);
						}
					}

					//If not switch account
					if (!empty($switch_account) && $switch_account == 'no') {
						if (!empty($wp_user_object->roles[0]) && $wp_user_object->roles[0] === 'freelancers') {
							$user_type = 'freelancer';
						} else if (!empty($wp_user_object->roles[0]) && $wp_user_object->roles[0] === 'employers') {
							$user_type = 'employer';
						} else {
							$user_type = 'subscriber';
						}

						$freelancer_package_id			= workreap_get_package_type('package_type', 'trail_freelancer');
						$employer_package_id			= workreap_get_package_type('package_type', 'trail_employer');

						if ($user_type === 'employer' && !empty($employer_package_id)) {
							workreap_update_pakage_data($employer_package_id, $user_identity, '', $user_type);
						} else if ($user_type === 'freelancer' && !empty($freelancer_package_id)) {
							workreap_update_pakage_data($freelancer_package_id, $user_identity, '', $user_type);
						}
					}

					$user_array = array();
					$user_array['user_login'] 	 = $email;

					$profile_id		= workreap_get_linked_profile_id($user_identity);

					$post_meta	= array(
						'_tag_line' 			=> '_tag_line',
						'_gender' 				=> '_gender',
						'_is_verified' 			=> '_is_verified',
						'_featured_timestamp' 	=> '_featured_timestamp'
					);

					foreach ($post_meta as $key => $usermeta) {
						$user_pmetadata[$key] = get_post_meta($profile_id, $key, true);
					}

					$permission			= '';
					if (apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $user_identity) === true) {
						$permission		= 'allow';
					} else {
						$permission		= 'notallow';
					}

					/* milestone allowerd in post */
					$milestone		= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
					$profile_data = $shipping = $billing = array();

					/* User metaData */
					$user_meta	= array(
						'profile_id'		=> $profile_id,
						'id' 				=> $user_identity,
						'user_login' 		=> $username,
						'first_name' 		=> $first_name,
						'last_name' 		=> $last_name,
						'user_email' 		=> $email,
						'chat_permission'	=> $permission,
						'milestone'			=> $milestone
					);

					if (function_exists('fw_get_db_settings_option')) {
						$chat_settings    			=  fw_get_db_settings_option('chat');
						$user_meta['host']			=  !empty($chat_settings['chat']['host']) ?  $chat_settings['chat']['host'] : '';
						$user_meta['port']			=  !empty($chat_settings['chat']['port']) ?  $chat_settings['chat']['port'] : '';
						$user_meta['chat_type']		=  !empty($chat_settings['gadget']) ? $chat_settings['gadget'] : 'inbox';
					}

					$user_meta['service_access']	=  $user_meta['job_access'] = '';

					if (apply_filters('workreap_system_access', 'service_base') === true) {
						$user_meta['service_access']	= 'yes';
					}

					if (apply_filters('workreap_system_access', 'job_base') === true) {
						$user_meta['job_access']	= 'yes';
					}

					$user_meta['listing_type']	= 'free';
					if (apply_filters('workreap_is_listing_free', false, $user_identity) === false) {
						$user_meta['listing_type']	= 'paid';
					}

					$registration_type	= get_user_meta($user_identity, 'registration_type', true);
					$user_meta['registration_type'] = !empty($registration_type) ? $registration_type : 'social';

					/* Billing info */
					if (class_exists('WC_Customer')) {
						$customer 	= new WC_Customer($user_identity);
						$shipping	= $customer->get_shipping();
						$billing	= $customer->get_billing();
					}

					$user_pmetadata['full_name']	= get_the_title($profile_id);
					$profile_data = array(
						'unread_notification_count' => apply_filters('workreap_count_unread_push_notification', $user_identity, 'return'),
						'shipping' 	=> maybe_unserialize($shipping),
						'billing' 	=> maybe_unserialize($billing),
						'pmeta' 	=> maybe_unserialize($user_pmetadata),
						'umeta' 	=> maybe_unserialize($user_meta),
					);

					/* JWT Authentication */
					$newuserArr['userId']	= $user_identity;
					$authObj			 	= new WORKREAPAPI_JWT(WorkreapAppGlobalSettings::get_plugin_name(), WorkreapAppGlobalSettings::get_plugin_verion());
					$authToken				= $authObj->getToken($newuserArr);

					$json = array(
						'authToken' 	=> $authToken,
						'profile' 		=> $profile_data,
						'type' 			=> 'success',
						'message' 		=> esc_html__('You are logged in', 'workreap_api'),
					);

					return new WP_REST_Response(maybe_unserialize($json), 200);
				} else {
					$json['type'] = 'error';
					$json['message'] = esc_html__('Some error occurs, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Email is missing', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Identity verfication
		 */
		public function identity_verfication($data)
		{

			$json					= array();
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$fields	= array(
				'user_id'				=> esc_html__('Something went wrong', 'workreap_api'),
				'name'					=> esc_html__('Name is required', 'workreap_api'),
				'phone'					=> esc_html__('Phone is required', 'workreap_api'),
				'verification_number'	=> esc_html__('CNIC/Passport/NIN/SSN number is required', 'workreap_api'),
				'address'				=> esc_html__('Address is required', 'workreap_api'),
			);
			foreach ($fields as $key => $val) {
				$json['title']		= esc_html__('Failed!', 'workreap_api');
				$json['type'] 	 	= 'error';
				if (empty($request[$key])) {
					$json['message']	= $val;
					return new WP_REST_Response($json, 200);
				}
			}

			$identity_array			                        = array();
			$user_id										= !empty($request['user_id']) ? $request['user_id'] : 0;
			$identity_array['info']['name'] 				= !empty($request['name']) ? sanitize_text_field($request['name']) : '';
			$identity_array['info']['contact_number']  		= !empty($request['phone']) ? sanitize_text_field($request['phone']) : '';
			$identity_array['info']['verification_number']  = !empty($request['verification_number']) ? sanitize_text_field($request['verification_number']) : '';
			$identity_array['info']['address'] 				= !empty($request['address']) ? sanitize_textarea_field($request['address']) : '';
			$profile_id										= workreap_get_linked_profile_id($user_id);

			$total_service_imgs	= !empty($request['document_size']) ? $request['document_size'] : array();

			if (!empty($_FILES) && $total_service_imgs != 0) {
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				require_once(ABSPATH . 'wp-includes/pluggable.php');
				$newyUploadImage = array();
				if (!empty($_FILES) && $total_service_imgs != 0) {

					for ($x = 0; $x < $total_service_imgs; $x++) {
						$identity_verfication_files 	= $_FILES['identity_verfication_attachment' . $x];
						$uploaded_image  				= wp_handle_upload($identity_verfication_files, array('test_form' => false));
						$file_name			 			= basename($identity_verfication_files['name']);
						$file_type 		 				= wp_check_filetype($uploaded_image['file']);

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

						$gallery['attachment_id']	= (int)$attach_id;
						$gallery['url']            	= wp_get_attachment_url($attach_id);
						$gallery['name']      		= $file_name;
						$identity_array[] 			= $gallery;
					}
				}
			}

			update_post_meta($profile_id, 'verification_attachments', $identity_array);
			update_post_meta($profile_id, 'identity_verified', 0);

			$json['type'] 		= 'success';
			$json['title']		= esc_html__('Success!', 'workreap_api');
			$json['message'] 	= esc_html__('Verfication document has been sent.', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

		/**
		 * get identity verfication status
		 *
		 * @since    1.0.0
		 */
		public function identity_status($data)
		{
			$json					= $profileImage = array();
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			$userId 				= !empty($request['user_id']) ? esc_html($request['user_id']) : '';
			$userId					= workreap_get_linked_profile_id($userId);

			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			if (!empty($userId)) {
				$verification_attachments  	= get_post_meta($userId, 'verification_attachments', true);
				$verification_attachments	= !empty($verification_attachments) ? $verification_attachments : array();
				$identity_verified  		= get_post_meta($userId, 'identity_verified', true);
				$identity_verified			= !empty($identity_verified) ? $identity_verified : 0;


				if (empty($identity_verified) && empty($verification_attachments)) {
					$json['type'] 		    		= 'success';
					$json['title'] 					= esc_html__('Verification required', 'workreap_api');
					$json['message'] 				= esc_html__('You must verify your identity, please submit the required documents to get verified.As soon as you will be verified then you will be able to get online appointments and other site features.', 'workreap_api');
					$json['identity_verification'] 	= 0;
					return new WP_REST_Response($json, 200);
				}
				if (empty($identity_verified) && !empty($verification_attachments)) {
					$json['type'] 		    		= 'success';
					$json['title'] 					= esc_html__('Woohoo!', 'workreap_api');
					$json['message'] 				= esc_html__('You have successfully submitted your documents. buckle up We will verify and respond to your request very soon.', 'workreap_api');;
					$json['identity_verification'] 	= 2;
					return new WP_REST_Response($json, 200);
				}
				if (!empty($identity_verified)  && !empty($verification_attachments)) {
					$json['type'] 		    		= 'success';
					$json['title'] 					= esc_html__('Hurray!', 'workreap_api');
					$json['message'] 				= esc_html__('We have successfully completed your indentity verification. you’re now ready to use full features.', 'workreap_api');
					$json['identity_verification'] 	= 1;
					return new WP_REST_Response($json, 200);
				}
				if (!empty($identity_verified) && $identity_verified == 1) {
					$json['type'] 		    		= 'success';
					$json['title'] 					= esc_html__('Hurray!', 'workreap_api');
					$json['message'] 				= esc_html__('We have successfully completed your indentity verification. you’re now ready to use full features.', 'workreap_api');
					$json['identity_verification'] 	= 1;
					return new WP_REST_Response($json, 200);
				}
			} else {
				$json['type'] 		    = 'error';
				$json['title'] 			= esc_html__('Failed!', 'workreap_api');
				$json['message'] 		= esc_html__('Something went wrong.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * get identity verfication status
		 *
		 * @since    1.0.0
		 */
		public function workreap_cancel_verification_request($data)
		{
			$json					= array();
			global $current_user;
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			$userId 				= !empty($request['user_id']) ? esc_html($request['user_id']) : '';
			$post_id				= workreap_get_linked_profile_id($userId);
			$current_user			= workreap_get_linked_profile_id($current_user->ID);

			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}
			if (empty($userId)) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('User id os required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
			if (!empty($post_id)) {
				update_post_meta($post_id, 'verification_attachments', '');
				update_post_meta($post_id, 'identity_verified', 0);
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Successfully! deleted your verification request', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']	 = 'error';
				$json['message'] = esc_html__('You are not allowed to perform this action.!!', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Update players ids
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_onesignal_player($request)
		{
			$json			= array();

			$user_id 		= !empty($request['id']) ? $request['id'] : '';
			$playerId 		= !empty($request['playerId']) ? $request['playerId'] : '';

			if (!empty($user_id) && !empty($playerId)) {
				$player_ids = array();
				$player_ids  = get_user_meta($user_id, 'user_playerID_onesignal', true);

				if (empty($player_ids)) {
					$player_ids = array();
				}

				if (!is_array($player_ids)) {
					$player_ids = array();
				}

				if (!in_array($playerId, $player_ids)) {
					array_push($player_ids, $playerId);
				}

				array_unique($player_ids);
				//update user meta with player ID
				update_user_meta($user_id, 'user_playerID_onesignal', $player_ids);
				$json['type']		= 'success';
				$json['playerId']	= $player_ids;
				$json['message']	= esc_html__('Player ID is updated!', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] = "error";
				$json['message'] = esc_html__("Invalid User id.", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Remove player id from usermeta
		 */
		public function remove_onesignal_playerids($request)
		{
			$json			= array();
			$user_id 		= !empty($request['id']) ? $request['id'] : '';
			$playerId 		= !empty($request['playerId']) ? $request['playerId'] : '';

			if (!empty($user_id) && !empty($playerId)) {
				$player_ids = get_user_meta($user_id, 'user_playerID_onesignal', true);
				if (!empty($player_ids)) {
					$is_playerId_exist = array_search($playerId, $player_ids);
					if ($is_playerId_exist !== false) {
						unset($player_ids[$is_playerId_exist]);

						update_user_meta($user_id, 'user_playerID_onesignal', $player_ids);
						$json['type'] = "success";
						$json['message'] = esc_html__("Player id removed!", 'workreap_api');
						return new WP_REST_Response($json, 200);
					} else {
						$json['type'] = "error";
						$json['message'] = esc_html__("Player id not exist", 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				} else {
					$json['type'] = "error";
					$json['message'] = esc_html__("Something is missing", 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type'] = "error";
				$json['message'] = esc_html__("Something is missing", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidApp_User_Route;
		$controller->register_routes();
	}
);
