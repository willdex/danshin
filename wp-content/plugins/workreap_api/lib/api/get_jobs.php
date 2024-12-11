<?php
if (!class_exists('AndroidAppGetJobsRoutes')) {

	class AndroidAppGetJobsRoutes extends WP_REST_Controller
	{

		/**
		 * Register the routes for the objects of the controller.
		 */
		public function register_routes()
		{
			$version 	= '1';
			$namespace 	= 'api/v' . $version;
			$base 		= 'listing';

			register_rest_route(
				$namespace,
				'/' . $base . '/get_jobs',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_listing'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/update_job',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_job'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/edit_job',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'edit_job'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/get_proposals_listing',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_proposals_listing'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			/* delete employer job */
			register_rest_route(
				$namespace,
				'/' . $base . '/delete_listing',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'delete_listing'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			/* jobs by country */
			register_rest_route(
				$namespace,
				'/' . $base . '/country_listing',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'jobs_country_listing'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			/* Get Services quotes*/
			register_rest_route(
				$namespace,
				'/' . $base . '/get_qoutes',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_qoutes_listing'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);


			/* Get freelancer Posted services*/
			register_rest_route(
				$namespace,
				'/' . $base . '/get_freelancer_posted_services',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_freelancer_posted_services'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			/* Get freelancer Posted services*/
			register_rest_route(
				$namespace,
				'/' . $base . '/get_user_by_chat_qoutes',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_user_by_chat_qoutes'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			/* Get Services quotes*/
			register_rest_route(
				$namespace,
				'/' . $base . '/remove_qoutes',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'remove_qoutes'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			/* Get Services quotes*/
			register_rest_route(
				$namespace,
				'/' . $base . '/add_qoutes',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'add_qoutes'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			/* Decline Quote Request*/
			register_rest_route(
				$namespace,
				'/' . $base . '/decline_qoutes',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'decline_qoutes'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * Edit job
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function edit_job($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id				= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$user_profile_id		= workreap_get_linked_profile_id($user_id);
			$user_profile_id		= !empty($user_profile_id) ? intval($user_profile_id) : 0;
			$user_type				= apply_filters('workreap_get_user_type', $user_id);
			$job_id					= !empty($request['job_id']) ? intval($request['job_id']) : '';
			$json					= array();

			if (!empty($user_id) && !empty($job_id)) {
				$job_title 		= get_the_title($job_id);
				$job_content	= get_post_field('post_content', $job_id);
				$job_url	= get_the_permalink($job_id);

				$db_project_level = $db_country = $db_project_type = $project_cost = $db_address = $db_freelancer_level = '';
				$db_deadline = $db_expiry_date = '';
				$show_attachments = 'off';
				if (function_exists('fw_get_db_post_option')) {
					$db_project_level     = fw_get_db_post_option($job_id, 'project_level');
					$db_project_duration  = fw_get_db_post_option($job_id, 'project_duration');
					$db_english_level     = fw_get_db_post_option($job_id, 'english_level');
					$db_freelancer_level  = fw_get_db_post_option($job_id, 'freelancer_level');

					$db_project_type      = fw_get_db_post_option($job_id, 'project_type');
					$project_cost 		= !empty($db_project_type['fixed']['project_cost']) ? $db_project_type['fixed']['project_cost'] : '';
					$max_cost 			= !empty($db_project_type['fixed']['max_price']) ? $db_project_type['fixed']['max_price'] : '';
					$hourly_rate 		= !empty($db_project_type['hourly']['hourly_rate']) ? $db_project_type['hourly']['hourly_rate'] : '';
					$estimated_hours	= !empty($db_project_type['hourly']['estimated_hours']) ? $db_project_type['hourly']['estimated_hours'] : '';

					$db_address     	  	= fw_get_db_post_option($job_id, 'address');
					$db_latitude     	  	= fw_get_db_post_option($job_id, 'latitude');
					$db_longitude     	  	= fw_get_db_post_option($job_id, 'longitude');
					$db_country 			= fw_get_db_post_option($job_id, 'country', true);
					$show_attachments   	= fw_get_db_post_option($job_id, 'show_attachments');
					$db_project_documents   = fw_get_db_post_option($job_id, 'project_documents');
					$db_expiry_date     	= fw_get_db_post_option($job_id, 'expiry_date');
					$db_deadline     		= fw_get_db_post_option($job_id, 'deadline');
				}

				$db_hide_map 	= 'no';
				$milestone 		= array();
				$job_price_option = $job_option_setting = '';
				if (function_exists('fw_get_db_settings_option')) {
					$job_price_option   	= fw_get_db_settings_option('job_price_option', $default_value = null);
					$job_option_setting   	= fw_get_db_settings_option('job_option', $default_value = null);
					$milestone         		= fw_get_db_settings_option('job_milestone_option', $default_value = null);
					$db_hide_map 			= fw_get_db_settings_option('hide_map');
				}

				/* milestones */
				$milestone_enable				= !empty($milestone['gadget']) ? $milestone['gadget'] : array();
				$require_milestone				= 'off';
				if (!empty($milestone_enable) && $milestone_enable === 'enable') {
					$_milestone   	= get_post_meta($job_id, '_milestone', true);
					$require_milestone	= !empty($_milestone) ? $_milestone : 'off';
				}

				$milestone_arr = array();
				if (!empty($milestone) && $milestone['gadget'] == 'enable') {
					$milestone_arr[] = array(
						'total_budget' 		=> !empty($milestone['enable']['total_budget']) ? $milestone['enable']['total_budget'] : '',
						'in_escrow' 		=> !empty($milestone['enable']['in_escrow']) ? $milestone['enable']['in_escrow'] : '',
						'milestone_paid' 	=> !empty($milestone['enable']['milestone_paid']) ? $milestone['enable']['milestone_paid'] : '',
						'remainings' 		=> !empty($milestone['enable']['remainings']) ? $milestone['enable']['remainings'] : '',
					);
				}

				/* job nature */
				$db_job_nature = '';
				if (!empty($job_option_setting) && $job_option_setting === 'enable') {
					$db_job_nature      = fw_get_db_post_option($job_id, 'job_option');
				}

				$db_max_price = '';
				if (!empty($job_price_option) && $job_price_option === 'enable') {
					$db_max_price      = fw_get_db_post_option($job_id, 'max_price');
				}

				/* is featured */
				$is_featured   	= get_post_meta($job_id, '_featured_job_string', true);
				$is_featured	= !empty($is_featured) ? 'on' : 'off';

				/* country */
				$country_arr = array();
				if (!empty($db_country)) {
					$job_country = get_term_by('id', $db_country[0], 'locations');
					$icon          	= fw_get_db_term_option($job_country->term_id, 'locations', 'image');
					$flag 			= !empty($icon['url']) ? workreap_add_http($icon['url']) : '';
					$country_arr[] = array(
						'id' 		=> $job_country->term_id,
						'name' 		=> $job_country->name,
						'slug' 		=> $job_country->slug,
						'flag' 		=> $flag,
					);
				}

				/* project level */
				$project_level = array();
				if (!empty($db_project_level)) {
					$project_level_obj = get_term_by('slug', $db_project_level, 'project_levels');
					$project_level[] = array(
						'id' => $project_level_obj->term_id,
						'name' => $project_level_obj->name,
						'slug' => $project_level_obj->slug,
					);
				}

				/* english level */
				$english_level = array();
				if (!empty($db_english_level)) {
					$english_level_obj = get_term_by('slug', $db_english_level, 'english_level');
					$english_level[] = array(
						'id' => $english_level_obj->term_id,
						'name' => $english_level_obj->name,
						'slug' => $english_level_obj->slug,
					);
				}

				/* languages */
				$job_lang = array();
				$job_languages = wp_get_post_terms($job_id, 'languages');
				if (!empty($job_languages) && is_array($job_languages)) {
					foreach ($job_languages as $key => $lang) {
						$job_lang[] = array(
							'id' 		=> $lang->term_id,
							'name' 		=> $lang->name,
							'slug' 		=> $lang->slug,
						);
					}
				}

				/* categories */
				$job_cat_items = array();
				$db_project_cat = wp_get_post_terms($job_id, 'project_cat', array('fields' => 'all'));
				$db_project_cat	= !empty($db_project_cat) ? $db_project_cat : array();
				if (!empty($db_project_cat)) {
					$serv_count	= 0;
					foreach ($db_project_cat as $cat) {
						$serv_count++;
						$job_cat_items[] = array(
							'id' => !empty($cat->term_id) ? $cat->term_id : '',
							'name' => !empty($cat->name) ? $cat->name : '',
							'slug' => !empty($cat->slug) ? $cat->slug : '',
						);
					}
				}

				/* faq */
				$faq_arr = array();
				$faqs 	= fw_get_db_post_option($job_id, 'faq');
				$faqs 	= !empty($faqs) ? $faqs : array();
				if (!empty($faqs)) {
					foreach ($faqs as $faq) {
						$faq_arr[] = array(
							'faq_question' 	=> $faq['faq_question'],
							'faq_answer' 	=> $faq['faq_answer'],
						);
					}
				}

				/* project type */
				$db_project_type 	 		= !empty($db_project_type['gadget']) ? $db_project_type['gadget'] : '';

				/* project duration */
				$project_duration = array();
				if (!empty($db_project_duration)) {
					$project_duration_obj = get_term_by('slug', $db_project_duration, 'durations');
					$project_duration[] = array(
						'id' => $project_duration_obj->term_id,
						'name' => $project_duration_obj->name,
						'slug' => $project_duration_obj->slug,
					);
				}

				/* country */
				$job_counry = array();
				$job_counry_slug = get_post_meta($job_id, '_country', true);
				$job_counry_slug = !empty($job_counry_slug) ? $job_counry_slug : '';
				if (!empty($job_counry_slug)) {
					$service_counry_obj = get_term_by('slug', $job_counry_slug, 'locations');
					$job_counry[] = array(
						'id' 		=> $service_counry_obj->term_id,
						'name' 		=> $service_counry_obj->name,
						'slug' 		=> $service_counry_obj->slug,
					);
				}

				/* skills */
				$skills_data = array();
				$db_skills 		= wp_get_post_terms($job_id, 'skills');
				$db_skills		= !empty($db_skills) ? $db_skills : array();
				if (!empty($db_skills)) {
					foreach ($db_skills as $skills) {
						$skills_data[] = array(
							'id' 	=> $skills->term_id,
							'name' 	=> $skills->name,
							'slug' 	=> $skills->slug,
						);
					}
				}

				/* freelancer type */
				$freelancer_type = array();
				if (!empty($db_freelancer_level[0])) {
					$freelancer_level_obj = get_term_by('slug', $db_freelancer_level[0], 'freelancer_type');
					$freelancer_type[] = array(
						'id' 		=> !empty($freelancer_level_obj->term_id) ? $freelancer_level_obj->term_id : '',
						'name' 		=> !empty($freelancer_level_obj->name) ? $freelancer_level_obj->name : '',
						'slug' 		=> !empty($freelancer_level_obj->slug) ? $freelancer_level_obj->slug : '',
					);
				}

				/* project experience */
				$proj_experience_arr = array();
				$proj_experience = wp_get_post_terms($job_id, 'project_experience', array('fields' => 'all'));
				$proj_experience	= !empty($proj_experience) ? $proj_experience : array();
				if (!empty($proj_experience)) {
					foreach ($proj_experience as $experience_obj) {
						$proj_experience_arr[] = array(
							'id' 		=> $experience_obj->term_id,
							'name' 		=> $experience_obj->name,
							'slug' 		=> $experience_obj->slug,
						);
					}
				}

				/* project attachment files */
				$proj_files_arr = array();
				$project_files				= !empty($db_project_documents) ? $db_project_documents : array();
				if (!empty($project_files)) {
					foreach ($project_files as $file_project) {
						$attachment_id	= !empty($file_project['attachment_id']) ? $file_project['attachment_id'] : '';
						$file_size 		= !empty($file_project) ? filesize(get_attached_file($attachment_id)) : '';
						$filetype       = !empty($file_project) ? wp_check_filetype($file_project['url']) : '';
						$doc_url 		= !empty($file_project['url']) ? esc_url($file_project['url']) : '';
						$file_detail  	= Workreap_file_permission::getDecrpytFile($file_project);
						$name        	= $file_detail['filename'];

						$proj_files_arr[] = array(
							'attachment_id' 	=> (int)$attachment_id,
							'name' 				=> $name,
							'size' 				=> $file_size,
							'url' 				=> $doc_url,
							'fileType' 			=> $filetype,
						);
					}
				}

				$item = array(
					'job_id' 				=> $job_id,
					'project_title' 		=> !empty($job_title) ? esc_html($job_title) : '',
					'project_content' 		=> !empty($job_content) ?  $job_content : '',
					'job_link' 				=> !empty($job_url) ? esc_url($job_url) : '',
					'is_featured'			=> $is_featured,
					'location' 				=> $country_arr,
					'project_level' 		=> $project_level,
					'english_level' 		=> $english_level,
					'languages' 			=> $job_lang,
					'categories' 			=> $job_cat_items,
					'faq' 					=> $faq_arr,
					'project_type' 			=> $db_project_type,
					'project_duration' 		=> $project_duration,
					'project_cost' 			=> $project_cost,
					'max_price' 			=> $db_max_price,
					'hourly_rate' 			=> $hourly_rate,
					'estimated_hours' 		=> $estimated_hours,
					'expiry_date' 			=> $db_expiry_date,
					'deadline_date' 		=> $db_deadline,
					'show_attanchents' 		=> $show_attachments,
					'attanchents' 			=> $proj_files_arr,
					'skills' 				=> $skills_data,
					'job_type' 				=> $db_job_nature,
					'project_experience'	=> $proj_experience_arr,
					'country' 				=> $job_counry,
					'address' 				=> $db_address,
					'longitude' 			=> $db_longitude,
					'latitude' 				=> $db_latitude,
					'hide_map' 				=> $db_hide_map,
					'required_milestone'	=> $require_milestone,
					'milestone'				=> $milestone_arr,
					'freelancer_type' 		=> $freelancer_type,
				);
				return new WP_REST_Response($item, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Something is missing!', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Job's by country
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function jobs_country_listing($request)
		{
			$profile_id		= !empty($request['profile_id']) ? intval($request['profile_id']) : '';
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$offset 		= ($page_number - 1) * $limit;
			$json = $items  = $country_arr = array();

			if (!empty($profile_id)) {
				$saved_projects	= get_post_meta($profile_id, '_saved_projects', true);
			} else {
				$saved_projects	= array();
			}

			$is_verified		= get_post_meta($profile_id, '_is_verified', true);
			$_is_verified 		= !empty($is_verified) ? $is_verified : '';

			$country_args 	= array(
				'post_type' 	=> 'projects',
				'taxonomy'  	=> 'locations',
				'hide_empty' 	=> false,
			);

			$location_terms = get_terms($country_args);

			if (!empty($location_terms) && count($location_terms) > 0) {
				foreach ($location_terms as $location_obj) {
					$country_id 		= $location_obj->term_id;
					$country_name 		= $location_obj->name;
					$country_slug 		= $location_obj->slug;
					$taxonomy_name 		= $location_obj->taxonomy;
					$related_jobs 		= $location_obj->count;

					/* country image/icon */
					$icon          	= fw_get_db_term_option($country_id, 'locations', 'image');
					$location_flag 	= !empty($icon['url']) ? workreap_add_http($icon['url']) : '';

					/* jobs array relted to country */
					$country_jobs_args = array(
						'posts_per_page' 	  	=> -1,
						'post_type' 	 	  	=> 'projects',
						'post_status' 	 	  	=> 'publish',
						'ignore_sticky_posts' 	=> 1,
						'meta_query' => array(
							array(
								'key'     => '_country',
								'value'   => $country_slug,
								'compare' => '=',
							),
						),
					);

					$country_jobs_arr 		= array();
					$country_jobs_query 	= get_posts($country_jobs_args);
					if (!empty($country_jobs_query) && count($country_jobs_query) > 0) {
						$defult		= get_template_directory_uri() . '/images/featured.png';
						if (function_exists('fw_get_db_settings_option')) {
							$featured_image		= fw_get_db_settings_option('featured_job_img');
							$featured_bg_color	= fw_get_db_settings_option('featured_job_bg');
							$tag		  		= !empty($featured_image['url']) ? $featured_image['url'] : $defult;
							$color		  		= !empty($featured_bg_color) ? $featured_bg_color : '#f1c40f';
						} else {
							$color				= '';
							$tag				= '';
						}

						foreach ($country_jobs_query as $country_jobs_obj) {
							$project_id			= $country_jobs_obj->ID;
							$job_link			= get_the_permalink($project_id);

							$job_faq = $project_documents =  array();
							$project_cost = $hourly_rate = $estimated_hours = '';
							if (function_exists('fw_get_db_post_option')) {
								$job_faq 			= fw_get_db_post_option($project_id, 'faq');
								$project_type 		= fw_get_db_post_option($project_id, 'project_type', true);
								$project_duration   = fw_get_db_post_option($project_id, 'project_duration', true);
								$project_documents  = fw_get_db_post_option($project_id, 'project_documents', true);
								$project_documents	= !empty($project_documents) ? $project_documents : array();
								$db_project_type 	= fw_get_db_post_option($project_id, 'project_type', true);
								$expiry_date 		= fw_get_db_post_option($project_id, 'expiry_date', true);
								$deadline_date   	= fw_get_db_post_option($project_id, 'deadline', true);
								$project_cost 		= !empty($db_project_type['fixed']['project_cost']) ? $db_project_type['fixed']['project_cost'] : '';
								$hourly_rate 		= !empty($db_project_type['hourly']['hourly_rate']) ? $db_project_type['hourly']['hourly_rate'] : '';
								$estimated_hours	= !empty($db_project_type['hourly']['estimated_hours']) ? $db_project_type['hourly']['estimated_hours'] : '';
							}

							//Featured Jobs
							$featured_job	= get_post_meta($project_id, '_featured_job_string', true);
							if (!empty($featured_job) && !empty($color) && !empty($tag)) {
								$featured_url		= workreap_add_http($tag);
								$featured_color		= $color;
							} else {
								$featured_url		= '';
								$featured_color		= '';
							}

							/* is favourite */
							if (!empty($saved_projects)  &&  in_array($project_id, $saved_projects)) {
								$favorit	= 'yes';
							} else {
								$favorit	= 'no';
							}

							/* job location */
							$job_location		= workreap_get_location($project_id);

							/* job/project level */
							$project_level		= apply_filters('workreap_filter_project_level', $project_id);

							/* job options */
							$job_option	= get_post_meta($project_id, '_job_option', true);
							$job_option	= !empty($job_option) ? workreap_get_job_option($job_option) : '';

							/* job faq */
							$job_faq_option		= fw_get_db_settings_option('job_faq_option');
							if (!empty($job_faq_option) && $job_faq_option == 'faq') {
								$job_faq	= !empty($faq) ? $faq : array();
							}

							/* project type */
							$project_type   	= !empty($project_type['gadget']) ? ucfirst($project_type['gadget']) : '';

							/* duration */
							$duration_list 		= worktic_job_duration_list();
							$project_duration	= !empty($project_duration) ? $duration_list[$project_duration] : '';

							/* expiry date */
							$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';

							/* deadline date */
							$deadline_date	  = !empty($deadline_date) ? workreap_date_format_fix($deadline_date) : '';

							/* attachments */
							$docs						= array();
							if (!empty($project_documents) && is_array($project_documents)) {
								$docs_count	= 0;
								foreach ($project_documents as $value) {
									$docs_count++;
									$docs[$docs_count]['document_name']   	= !empty(get_the_title($value['attachment_id'])) ? get_the_title($value['attachment_id']) : '';
									$docs[$docs_count]['file_size']			= !empty(filesize(get_attached_file($value['attachment_id']))) ? size_format(filesize(get_attached_file($value['attachment_id'])), 2) : '';
									$docs[$docs_count]['filetype']        	= wp_check_filetype($value['url']);
									$docs[$docs_count]['extension']       	= !empty($filetype['ext']) ? $filetype['ext'] : '';
									$docs[$docs_count]['url']				= workreap_add_http($value['url']);
								}
							}
							$job_attanchents	= array_values($docs);

							/* skills */
							$project_skills 					= wp_get_post_terms($project_id, 'skills');
							$skills					= array();
							if (!empty($project_skills)) {
								$sk_count	= 0;
								foreach ($project_skills as $key => $skill) {
									$sk_count++;
									$term_link 							= get_term_link($skill->term_id, 'skills');
									$skills[$sk_count]['skill_link']	= $term_link;
									$skills[$sk_count]['skill_name']	= $skill->name;
								}
							}
							$job_skills				= array_values($skills);

							/* employer name */
							$employer_name		= get_the_title($profile_id);

							/* employer avatar */
							$employer_avatar	= $avatar = apply_filters(
								'workreap_employer_avatar_fallback',
								workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id),
								array('width' => 100, 'height' => 100)
							);

							$country_jobs_arr[] = array(
								'job_id' 			=> $project_id,
								'job_link' 			=> $job_link,
								'project_title' 	=> get_the_title($project_id),
								'project_content' 	=> wp_strip_all_tags(get_post_field('post_content', $project_id)),
								'featured_url' 		=> $featured_url,
								'featured_color' 	=> $featured_color,
								'favorit' 			=> $favorit,
								'location' 			=> $job_location,
								'project_level' 	=> $project_level,
								'job_type' 			=> $job_option,
								'_is_verified' 		=> $_is_verified,
								'faq' 				=> $job_faq,
								'project_type' 		=> $project_type,
								'project_duration'	=> $project_duration,
								'project_cost' 		=> $project_cost,
								'hourly_rate' 		=> $hourly_rate,
								'estimated_hours' 	=> $estimated_hours,
								'expiry_date' 		=> $expiry_date,
								'deadline_date' 	=> $deadline_date,
								'attanchents' 		=> $job_attanchents,
								'skills' 			=> $job_skills,
								'employer_name' 	=> $employer_name,
								'employer_avatar' 	=> $employer_avatar,
								'count_totals' 		=> count($country_jobs_query),
							);
						}
						wp_reset_postdata();
					}

					/* country array */
					$country_arr[] = array(
						'country_id' 		=> $country_id,
						'country_name' 		=> $country_name,
						'country_slug' 		=> $country_slug,
						'country_flag' 		=> $location_flag,
						'taxonomy_name' 	=> $taxonomy_name,
						'related_jobs' 		=> $related_jobs,
						'active_jobs' 		=> count($country_jobs_query),
						'jobs' 				=> $country_jobs_arr,
					);
				}
				wp_reset_postdata();
				$items 	= $country_arr;
				return new WP_REST_Response($items, 200);
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Some error occur, please try again later', 'workreap_api');
				$items[] = $json;
				return new WP_REST_Response($items, 203);
			}
		}

		/**
		 * Get Proposal Listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_proposals_listing($request)
		{
			$json				= array();
			$items				= array();
			$project_id			= !empty($request['project_id']) ? intval($request['project_id']) : '';
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$pg_page 			= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
			$pg_paged 			= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var

			$user_identity 	 = $user_id;
			$url_identity 	 = $user_identity;
			$linked_profile  = workreap_get_linked_profile_id($user_identity);
			$post_id 		 = $linked_profile;
			$meta_query_args = array();

			$show_posts 			= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
			$edit_id				= $project_id;
			$post_author			= get_post_field('post_author', $edit_id);
			$hired_freelancer_id	= get_post_meta($edit_id, '_freelancer_id', true);

			$job_status				= get_post_status($edit_id);
			$milestone				= array();

			if (function_exists('fw_get_db_settings_option')) {
				$milestone         	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}

			$milestone		= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
			$paged 			= max($pg_page, $pg_paged);


			$offline_package		= worrketic_hiring_payment_setting();
			$offline_package		= !empty($offline_package['type']) ? $offline_package['type'] : '';

			$query_args = array(
				'posts_per_page' => $show_posts,
				'post_type' 			=> 'proposals',
				'paged' 		 	  	=> $paged,
				'suppress_filters' 		=> false,
			);

			$meta_query_args[] = array(
				'key' 			=> '_project_id',
				'value' 		=> $edit_id,
				'compare' 		=> '='
			);
			$query_relation = array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);


			$pquery = new WP_Query($query_args);
			$count_post = $pquery->found_posts;

			if ($pquery->have_posts()) {
				while ($pquery->have_posts()) : $pquery->the_post();
					global $post;
					$author_id 			= get_the_author_meta('ID');
					$linked_profile 	= workreap_get_linked_profile_id($author_id);
					$freelancer_title 	= esc_html(get_the_title($linked_profile));

					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs 	= fw_get_db_post_option($post->ID, 'proposal_docs', true);
					} else {
						$proposal_docs	= '';
					}

					$proposal_docs = !empty($proposal_docs) && is_array($proposal_docs) ?  count($proposal_docs) : 0;
					$freelancer_avatar = apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 225, 'height' => 225), $linked_profile),
						array('width' => 225, 'height' => 225)
					);

					$order_id	= get_post_meta($post->ID, '_order_id', true);
					$order_id	= !empty($order_id) ? intval($order_id) : 0;
					$order_url	= '';
					if (!empty($order_id)) {
						if (class_exists('WooCommerce')) {
							$order		= wc_get_order($order_id);
							$order_url	= $order->get_view_order_url();
						}
					}

					$chat_option	= array();
					if (function_exists('fw_get_db_settings_option')) {
						$chat_option	= fw_get_db_settings_option('proposal_message_option', $default_value = null);
					}

					if (!empty($chat_option) && $chat_option === 'enable') {
						$json['linked_profile']	 = $linked_profile;
					}


					if (!empty($milestone) && $milestone === 'enable') {
						$_milestone   	= get_post_meta($edit_id, '_milestone', true);
						$is_milestone	= !empty($_milestone) ? $_milestone : 'off';
						if (!empty($is_milestone) && $is_milestone === 'on') {
							$json['milestone_enabled']	 = 'yes';
						} else if (empty($order_id)) {
							$json['milestone_enabled']	 = 'no';
						}
					} else if (empty($order_id)) {
						$json['milestone_enabled']	 = 'no';
					}


					$project_type    	= fw_get_db_post_option($project_id, 'project_type');
					$proposed_amount  	= get_post_meta($post->ID, '_amount', true);
					$total_amount		= '';
					$json['proposed_amount'] = apply_filters('workreap_price_format', $proposed_amount, 'return');

					if (!empty($project_type['gadget']) && $project_type['gadget'] === 'fixed') {
						$proposed_duration  = get_post_meta($post->ID, '_proposed_duration', true);
						$duration_list		= worktic_job_duration_list();
						$duration			= !empty($duration_list[$proposed_duration]) ? $duration_list[$proposed_duration] : '';

						if (!empty($duration)) {
							$json['per_hour_amount'] = $duration;
						}
					}

					if (!empty($project_type['gadget']) && $project_type['gadget'] === 'hourly') {
						$estimeted_time		= get_post_meta($post->ID, '_estimeted_time', true);
						$per_hour_amount	= get_post_meta($post->ID, '_per_hour_amount', true);
						$estimeted_time		= !empty($estimeted_time) ? $estimeted_time : 0;
						$per_hour_amount	= !empty($per_hour_amount) ? $per_hour_amount : 0;
						$total_amount		= apply_filters('workreap_price_format', $per_hour_amount, 'return');

						if (!empty($estimeted_time)) {
							$json['estimated_hours']	 = esc_html__('Estimated hours', 'workreap_api') . ' (' . $estimeted_time . ')';
						}

						if (!empty($per_hour_amount)) {
							$json['per_hour_amount']	 = esc_html__('Amount per hour', 'workreap_api') . ' (' . $total_amount . ')';
						}
					}

					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs = fw_get_db_post_option($post->ID, 'proposal_docs');
					}

					$proposal_docs = !empty($proposal_docs) ?  $proposal_docs : array();

					if (!empty($linked_profile)) {
						$reviews_data 	= get_post_meta($linked_profile, 'review_data');
						$reviews_rate	= !empty($reviews_data[0]['wt_average_rating']) ? floatval($reviews_data[0]['wt_average_rating']) : 0;
						$total_rating	= !empty($reviews_data[0]['wt_total_rating']) ? intval($reviews_data[0]['wt_total_rating']) : 0;
					} else {
						$reviews_rate	= 0;
						$total_rating	= 0;
					}

					$round_rate 		= number_format((float) $reviews_rate, 1);
					$rating_average		= ($round_rate / 5) * 100;


					if (function_exists('fw_get_db_post_option')) {
						$identity_verification    	= fw_get_db_settings_option('identity_verification');
						$email_verify_icon    		= fw_get_db_settings_option('email_verify_icon');
						$identity_verify_icon    	= fw_get_db_settings_option('identity_verify_icon');
					}

					$is_verified 		= get_post_meta($linked_profile, '_is_verified', true);
					$identity_verified 	= get_post_meta($linked_profile, 'identity_verified', true);

					/* show button for accept milestone and start project (if project is milestone based) */
					$accept_milestone 		= 'hide';
					$project_status			= get_post_status($project_id);
					$proposal_hiring_status	= get_post_meta($post->ID, '_proposal_status', true);
					$proposal_hiring_status	= !empty($proposal_hiring_status) ? $proposal_hiring_status : '';
					if (!empty($proposal_hiring_status)  && $proposal_hiring_status === 'pending' && $project_status === 'publish') {
						$accept_milestone = 'show';
					}

					/* proposal status */
					if ($job_status === 'hired') {
						$job_status	= esc_html__('Hired', 'workreap_api');
					} elseif ($job_status === 'completed') {
						$job_status	= esc_html__('Completed', 'workreap_api');
					} elseif ($job_status !== 'hired') {
						$job_status	= esc_html__('Pending', 'workreap_api');
					} elseif (!empty($job_status) && $job_status === 'cancelled') {
						$job_status	= esc_html__('Cancelled', 'workreap_api');
					} else {
						$job_status	= esc_html__('Pending', 'workreap_api');
					}

					$json['proposal_id']	 	 	= $post->ID;
					$json['project_id']	 	 		= $project_id;
					$json['project_title']	 	 	= get_the_title($project_id);
					$json['freelancer_avatar']	 	= $freelancer_avatar;
					$json['freelancer_title']	 	= $freelancer_title;
					$json['job_status']	 		 	= $job_status;
					$json['hired_freelancer_id'] 	= $hired_freelancer_id;
					$json['proposal_author_id']	 	= $author_id;
					$json['reviews_rate']	 		= $reviews_rate;
					$json['total_rating']	 		= $total_rating;
					$json['round_rate']	 	 		= $round_rate;
					$json['rating_average']	 		= $rating_average;
					$json['cover_latter']	 		= nl2br(stripslashes(get_the_content('', true, $post->ID)));;
					$json['proposal_docs']	 		= $proposal_docs;
					$json['is_verified']	 		= $is_verified;
					$json['identity_verified']		= $identity_verified;
					$json['accept_milestone_btn']	= $accept_milestone;

				endwhile;
				wp_reset_postdata();
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No porposal listing found', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Porposal listing', 'workreap_api');
			$items[] 			= $json;
			return new WP_REST_Response($items, 200);
		}

		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_job($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json = $items = $job_files = $submitted_files = array();
			$user_id = !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$hide_map 			= 'show';

			if (function_exists('fw_get_db_settings_option')) {
				$hide_map					= fw_get_db_settings_option('hide_map');
				$job_status					= fw_get_db_settings_option('job_status');
				$remove_freelancer_type   	= fw_get_db_settings_option('remove_freelancer_type');
				$remove_english_level   	= fw_get_db_settings_option('remove_english_level');
				$remove_project_level   	= fw_get_db_settings_option('remove_project_level');
				$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');
				$project_mandatory			= fw_get_db_settings_option('project_required');
			}

			$job_status	= !empty($job_status) ? $job_status : 'publish';
			$current 	= !empty($request['id']) ? intval($request['id']) : '';

			if (apply_filters('workreap_is_job_posting_allowed', 'wt_jobs', $user_id) === false) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You’ve consumed all you points to add new job.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			/* required fields */
			$required = array(
				'title'   			=> esc_html__('Job title is required', 'workreap_api'),
				'project_level'  	=> esc_html__('Project level is required', 'workreap_api'),
				'project_duration'  => esc_html__('Project duration is required', 'workreap_api'),
				'english_level'   	=> esc_html__('English level is required', 'workreap_api'),
				'project_type' 		=> esc_html__('Please select job type.', 'workreap_api'),
				'categories' 		=> esc_html__('Please select at-least one category', 'workreap_api'),
				'country'           => esc_html__('Country is required', 'workreap_api'),
			);
			if (isset($hide_map) && $hide_map === 'show') {
				$required['address'] = esc_html__('Address is required', 'workreap_api');
			}

			//remove location
			if (!empty($remove_location_job) && $remove_location_job === 'yes') {
				unset($required['address']);
				unset($required['latitude']);
				unset($required['longitude']);
				unset($required['country']);
			}

			$required	= apply_filters('workreap_filter_post_job_fields', $required);
			if (!empty($project_mandatory)) {
				$job_required  = workreap_jobs_required_fields();
				foreach ($project_mandatory as $key) {
					if (empty($_POST['job'][$key])) {
						$json['type'] 		= 'error';
						$json['message'] 	= $job_required[$key];
						return new WP_REST_Response($json, 203);
					}
				}
			}

			//remove english level
			if (!empty($remove_english_level) && $remove_english_level === 'yes') {
				unset($required['english_level']);
			}

			if (!empty($remove_project_level) && $remove_project_level === 'yes') {
				unset($required['project_level']);
			}
			if (!empty($remove_project_duration) && $remove_project_duration === 'yes') {
				unset($required['project_duration']);
			}

			$multiselect_freelancertype = 'disable';
			if (function_exists('fw_get_db_settings_option')) {
				$job_option_setting         = fw_get_db_settings_option('job_option', $default_value = null);
				$multiselect_freelancertype = fw_get_db_settings_option('multiselect_freelancertype', $default_value = null);
				$job_experience_single  	= fw_get_db_settings_option('job_experience_option', $default_value = null);
				$job_price_option           = fw_get_db_settings_option('job_price_option', $default_value = null);
				$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}

			$job_price_option 			= !empty($job_price_option) ? $job_price_option : '';
			$job_option_setting 		= !empty($job_option_setting) ? $job_option_setting : '';
			$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

			if (!empty($job_option_setting) && $job_option_setting === 'enable') {
				$required['job_option']	= esc_html__('Project location type is required', 'workreap_api');
			}

			/* set array of requested values */
			$title				= !empty($request['title']) ? sanitize_text_field($request['title']) : '';
			$description		= !empty($request['description']) ? $request['description'] : '';
			$project_level		= !empty($request['project_level']) ? $request['project_level'] : '';
			$project_duration	= !empty($request['project_duration']) ? $request['project_duration'] : '';
			$english_level		= !empty($request['english_level']) ? $request['english_level'] : '';
			$hourly_rate		= !empty($request['hourly_rate']) ? $request['hourly_rate'] : '';
			$project_cost		= !empty($request['project_cost']) ? $request['project_cost'] : '';
			$expiry_date        = !empty($request['expiry_date']) ? $request['expiry_date'] : '';
			$total_attachments 	= !empty($request['size']) ? $request['size'] : 0;
			$show_attachments   = !empty($request['show_attachments']) ? $request['show_attachments'] : 'off';
			$max_price          = !empty($request['max_price']) ? $request['max_price'] : '';
			$estimated_hours    = !empty($request['estimated_hours']) ? $request['estimated_hours'] : '';
			$is_featured      	= !empty($request['is_featured']) ? $request['is_featured'] : '';
			$deadline         	= !empty($request['deadline']) ? $request['deadline'] : '';
			$expiry_date      	= !empty($request['expiry_date']) ? $request['expiry_date'] : '';
			$languages        	= !empty($request['languages']) ? json_decode($request['languages'], true) : array();
			$categories      	= !empty($request['categories']) ? json_decode($request['categories'], true) : array();
			$skills          	= !empty($request['skills']) ? json_decode($request['skills'], true) : array();
			$project_type		= !empty($request['project_type']) ? $request['project_type'] : '';
			$address    		= !empty($request['address']) ? $request['address'] : '';
			$country    		= !empty($request['country']) ? $request['country'] : '';
			$latitude   		= !empty($request['latitude']) ? $request['latitude'] : '';
			$longitude  		= !empty($request['longitude']) ? $request['longitude'] : '';
			$job_option_text	= !empty($request['job_option']) ? $request['job_option'] : '';
			$faq 				= !empty($request['faq']) ? json_decode(stripslashes($request['faq']), true) : array();
			if (!empty($job_experience_single['gadget']) && $job_experience_single['gadget'] === 'enable') {
				if ($job_experience_single['enable']['multiselect_experience'] === 'single') {
					$experiences		= !empty($request['experiences']) ? array($request['experiences']) : array();
				} else {
					$experiences      	= !empty($request['experiences']) ? json_decode($request['experiences'], true) : array();
				}
			}

			if ($multiselect_freelancertype === 'enable') {
				$freelancer_level	= !empty($request['freelancer_level']) ? json_decode($request['freelancer_level'], true)  : array();
			} else {
				$freelancer_level	= !empty($request['freelancer_level']) ? array($request['freelancer_level']) : '';
			}

			$validation_arr = array(
				'title' 			=> $title,
				'description' 		=> $description,
				'project_level' 	=> $project_level,
				'project_duration' 	=> $project_duration,
				'english_level' 	=> $english_level,
				'project_type' 		=> $project_type,
				'freelancer_level' 	=> $freelancer_level,
				'hourly_rate' 		=> $hourly_rate,
				'project_cost' 		=> $project_cost,
				'expiry_date' 		=> $expiry_date,
				'total_attachments' => $total_attachments,
				'show_attachments' 	=> $show_attachments,
				'max_price' 		=> $max_price,
				'estimated_hours' 	=> $estimated_hours,
				'is_featured' 		=> $is_featured,
				'deadline' 			=> $deadline,
				'expiry_date' 		=> $expiry_date,
				'languages' 		=> $languages,
				'categories' 		=> $categories,
				'skills' 			=> $skills,
				'address'			=> $address,
				'country'			=> $country,
				'latitude'			=> $latitude,
				'longitude'			=> $longitude,
				'job_option'		=> $job_option_text,
				'faq'				=> $faq,
				'experiences'		=> $experiences,
			);

			foreach ($required as $key => $value) {
				if (empty($validation_arr[$key])) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				}

				if ($key === 'project_type' && $project_type === 'hourly' && empty($hourly_rate)) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Per hour rate is required', 'workreap_api');
					return new WP_REST_Response($json, 203);
				} else if ($key === 'project_type' && $project_type === 'hourly' && empty($estimated_hours)) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Estimated hours is required', 'workreap_api');
					return new WP_REST_Response($json, 203);
				} else if ($key == 'project_type' && $project_type === 'hourly' && !empty($max_price) && $max_price < $hourly_rate) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Maximum project cost should not be less than minimum project cost', 'workreap_api');
					return new WP_REST_Response($json, 203);
				} else if ($key == 'project_type' && $project_type === 'fixed' && !empty($max_price) && $max_price < $project_cost) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Maximum project cost should not be less than minimum project cost', 'workreap_api');
					return new WP_REST_Response($json, 203);
				} else if ($key == 'project_type' && $project_type === 'fixed' && empty($project_cost)) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Project cost is required', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			}

			if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
				$current 			= !empty($request['id']) ? intval($request['id']) : '';
				$post_author = get_post_field('post_author', $current);
				$post_id 	 = $current;
				$status 	 = get_post_status($post_id);

				if (intval($post_author) === intval($user_id)) {
					$article_post = array(
						'ID' 			=> $current,
						'post_title' 	=> !empty($title) ? $title : rand(1, 999999),
						'post_content' 	=> $description,
						'post_status' 	=> $status,
					);

					wp_update_post($article_post);
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				//change status on update
				do_action('workreap_update_post_status_action', $post_id, 'project'); //Admin will get an email to publish it

			} else {
				//Create new jop Post
				$user_post = array(
					'post_title'    => wp_strip_all_tags($title),
					'post_status'   => $job_status,
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'projects',
				);

				$post_id    		= wp_insert_post($user_post);
				update_post_meta($post_id, '_featured_job_string', 0);

				//update api key data
				if (apply_filters('workreap_filter_user_promotion', 'disable') === 'enable') {
					do_action('workreap_update_users_marketing_attributes', $user_id, 'posted_projects');
				}

				//update jobs
				$remaning_jobs	= workreap_get_subscription_metadata('wt_jobs', intval($user_id));
				$remaning_jobs	= !empty($remaning_jobs) ? intval($remaning_jobs) : 0;

				if (!empty($remaning_jobs) && $remaning_jobs >= 1) {
					$update_jobs	= intval($remaning_jobs) - 1;
					$update_jobs	= intval($update_jobs);

					$wt_subscription 	= get_user_meta(intval($user_id), 'wt_subscription', true);
					$wt_subscription	= !empty($wt_subscription) ?  $wt_subscription : array();

					$wt_subscription['wt_jobs'] = $update_jobs;

					update_user_meta(intval($user_id), 'wt_subscription', $wt_subscription);
				}

				$expiry_string		= workreap_get_subscription_metadata('subscription_featured_string', $user_id);

				if (!empty($expiry_string)) {
					update_post_meta($post_id, '_expiry_string', $expiry_string);
				}
			}

			if ($post_id) {

				/* Upload files from temp folder to uploads */
				$fw_options = array();
				if (!empty($_FILES) && $total_attachments > 0) {

					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					/* how many new files send from api's */
					$total_new_attachments = $total_attachments;

					/* already attached doc array from api's */
					$old_attachments = !empty($request['old_attachments_project']) ? json_decode(stripslashes($request['old_attachments_project']), true) : array();

					/* attached doc from DB */
					$db_project_attachment_arr       = fw_get_db_post_option($post_id, 'project_documents');
					$db_project_attachment_arr		= !empty($db_project_attachment_arr) ? $db_project_attachment_arr : array();

					/* create array of attachment id's that srote in DB */
					$db_project_attachment = array();
					if (!empty($db_project_attachment_arr)) {
						$db_project_attachment 	= wp_list_pluck($db_project_attachment_arr, 'attachment_id');
					}

					/* delete all images if empty array received from api's */
					if (empty($old_attachments) && !empty($db_project_attachment)) {
						foreach ($db_project_attachment as $delete_media) {
							if (!empty($delete_media)) {
								wp_delete_attachment($post_id, $delete_media, true);
							}
						}
						$fw_options['project_documents']	= array();
					}

					/* upload new docs if exist */
					$newyUploadDoc = array();
					if (!empty($total_new_attachments) && $total_new_attachments > 0) {
						/* count saved data form db for indexing */
						$new_index	= !empty($db_project_attachment_arr) ?  max(array_keys($db_project_attachment_arr)) : 0;
						for ($x = 0; $x < $total_new_attachments; $x++) {
							$new_index 				= $new_index + 1;
							$project_image_files 	= $_FILES['project_documents' . $x];
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

							$docs_attached['attachment_id']  				= (int)$attach_id;
							$docs_attached['url']            				= wp_get_attachment_url($attach_id);
							$fw_options['project_documents'][$new_index]	= $docs_attached;
							$newyUploadDoc[] = $docs_attached;
						}
					}

					/* delete some images that not send in request */
					if (!empty($old_attachments) && !empty($db_project_attachment)) {
						$newArr = array();
						$db_saved_documents = !empty($db_project_attachment_arr) ? $db_project_attachment_arr : array();

						if (!empty($db_saved_documents) && !empty($old_attachments)) {
							foreach ($db_saved_documents as $documentVal) {

								foreach ($old_attachments as $oldAttachmentVal) {
									if ($documentVal['attachment_id'] == $oldAttachmentVal['attachment_id']) {
										$newArr[] = array(
											'attachment_id' => (int)$documentVal['attachment_id'],
											'url' 			=> $documentVal['url']
										);
									}
								}
							}

							$docNew_arr = array_merge($newyUploadDoc, $newArr);
							$fw_options['project_documents'] = $docNew_arr;
						}
					}
				} else {
					/* already attached images array from api's */
					$old_attachments = !empty($request['old_attachments_project']) ? json_decode(stripslashes($request['old_attachments_project']), true) : array();

					/* old data updated */
					$db_project_old_attachment 			= fw_get_db_post_option($post_id, 'project_documents');
					$db_project_old_attachment			= !empty($db_project_old_attachment) ? $db_project_old_attachment : array();

					/* create array of attachment id's that srote in DB */
					$job_old_attachment = array();
					if (!empty($db_project_old_attachment)) {
						$job_old_attachment 	= wp_list_pluck($db_project_old_attachment, 'attachment_id');
					}

					/* delete all images if empty array received from api's */
					if (empty($old_attachments) && !empty($job_old_attachment)) {
						foreach ($job_old_attachment as $delete_media) {
							if (!empty($delete_media)) {
								wp_delete_attachment($post_id, $delete_media, true);
							}
						}
						$fw_options['project_documents']	= array();
					} else {
						$newDocsArr = array();
						/* delete some attachments that not send in request */
						if (!empty($old_attachments) && !empty($job_old_attachment)) {
							$db_saved_docs = !empty($db_project_old_attachment) ? $db_project_old_attachment : array();
							if (!empty($db_saved_docs)) {
								foreach ($db_saved_docs as $docsVal) {
									foreach ($old_attachments as $oldVal) {
										if ($docsVal['attachment_id'] == $oldVal['attachment_id']) {
											$newDocsArr[] = array(
												'attachment_id' => (int)$docsVal['attachment_id'],
												'url' 			=> $docsVal['url']
											);
										}
									}
								}
							}
						}
						$fw_options['project_documents'] = $newDocsArr;
					}
				}

				/* is featured */
				if (!empty($is_featured)) {
					if ($is_featured === 'on') {
						$is_featured_job	= get_post_meta($post_id, '_featured_job_string', true);
						if (empty($is_featured_job)) {
							$featured_jobs	= workreap_featured_job($user_id);
							if ($featured_jobs) {
								update_post_meta($post_id, '_featured_job_string', 1);
								$remaning_featured_jobs		= workreap_get_subscription_metadata('wt_featured_jobs', intval($user_id));
								$remaning_featured_jobs  	= !empty($remaning_featured_jobs) ? intval($remaning_featured_jobs) : 0;

								if (!empty($remaning_featured_jobs) && $remaning_featured_jobs >= 1) {
									$update_featured_jobs	= intval($remaning_featured_jobs) - 1;
									$update_featured_jobs	= intval($update_featured_jobs);
									$wt_subscription 	= get_user_meta(intval($user_id), 'wt_subscription', true);
									$wt_subscription	= !empty($wt_subscription) ?  $wt_subscription : array();
									$wt_subscription['wt_featured_jobs'] = $update_featured_jobs;

									update_user_meta(intval($user_id), 'wt_subscription', $wt_subscription);
								}
							} else {
								update_post_meta($post_id, '_featured_job_string', 0);
							}
						}
					} else {
						update_post_meta($post_id, '_featured_job_string', 0);
					}
				} else {
					update_post_meta($post_id, '_featured_job_string', 0);
				}

				//update langs
				wp_set_post_terms($post_id, $languages, 'languages');

				//update cats
				wp_set_post_terms($post_id, $categories, 'project_cat');

				//update skills
				wp_set_post_terms($post_id, $skills, 'skills');

				// price range
				if (!empty($job_price_option) && $job_price_option === 'enable') {
					update_post_meta($post_id, '_max_price', workreap_wmc_compatibility($max_price));
				}

				// update projec expriences
				if (!empty($job_experience_single['gadget']) && $job_experience_single['gadget'] === 'enable') {
					wp_set_post_terms($post_id, $experiences, 'project_experience');
				}

				//update
				update_post_meta($post_id, '_expiry_date', $expiry_date);
				update_post_meta($post_id, 'deadline', $deadline);
				update_post_meta($post_id, '_project_type', $project_type);
				update_post_meta($post_id, '_project_duration', $project_duration);
				update_post_meta($post_id, '_english_level', $english_level);

				update_post_meta($post_id, '_estimated_hours', $estimated_hours);
				update_post_meta($post_id, '_hourly_rate', workreap_wmc_compatibility($hourly_rate));
				update_post_meta($post_id, '_project_cost', workreap_wmc_compatibility($project_cost));

				$project_data	= array();
				$project_data['gadget']	= !empty($request['project_type']) ? $request['project_type'] : 'fixed';
				$project_data['hourly']['hourly_rate']		= !empty($request['hourly_rate']) ? $request['hourly_rate'] : '';
				$project_data['hourly']['estimated_hours']	= !empty($request['estimated_hours']) ? $request['estimated_hours'] : '';
				$project_data['fixed']['project_cost']		= !empty($request['project_cost']) ? workreap_wmc_compatibility($request['project_cost']) : '';
				$project_data['hourly']['max_price']		= !empty($request['max_price']) ? workreap_wmc_compatibility($request['max_price']) : '';
				$project_data['fixed']['max_price']			= !empty($request['max_price']) ? workreap_wmc_compatibility($request['max_price']) : '';

				//update location
				update_post_meta($post_id, '_address', $address);
				update_post_meta($post_id, '_country', $country);
				update_post_meta($post_id, '_latitude', $latitude);
				update_post_meta($post_id, '_longitude', $longitude);

				//Set country for unyson
				$locations = get_term_by('slug', $country, 'locations');
				$location = array();
				if (!empty($locations)) {
					$location[0] = $locations->term_id;

					if (!empty($location)) {
						wp_set_post_terms($post_id, $location, 'locations');
					}
				}

				//update unyson meta
				if (!empty($job_price_option) && $job_price_option === 'enable') {
					$fw_options['max_price']         	 = workreap_wmc_compatibility($max_price);
				}


				if (!empty($multiselect_freelancertype) && $multiselect_freelancertype === 'enable') {
					$fw_options['freelancer_level']      = $freelancer_level;
				} else {
					$freelancer_level					= !empty($freelancer_level) ? $freelancer_level[0] : '';
					$fw_options['freelancer_level'][0]  = $freelancer_level;
				}
				update_post_meta($post_id, '_freelancer_level', $freelancer_level);


				if (!empty($milestone) && $milestone === 'enable' && !empty($project_data['gadget']) && $project_data['gadget'] === 'fixed') {
					$is_milestone    			= !empty($request['is_milestone']) ? $request['is_milestone'] : 'off';
					$project_data['project_type']['fixed']['milestone']  	= $is_milestone;
					update_post_meta($post_id, '_milestone', $is_milestone);
				}

				// update post option
				if (!empty($job_option_setting) && $job_option_setting === 'enable') {
					$fw_options['job_option']    			= $job_option_text;
					update_post_meta($post_id, '_job_option', $job_option_text);
				}


				/* FAQ options */
				$job_faq_option		= fw_get_db_settings_option('job_faq_option');
				if (!empty($job_faq_option) && $job_faq_option == 'yes') {
					$fw_options['faq']      = $faq;
				}

				$fw_options['expiry_date']         	 = $expiry_date;
				$fw_options['deadline']         	 = $deadline;
				$fw_options['project_level']         = $project_level;
				$fw_options['project_type']          = $project_data;
				$fw_options['project_duration']      = $project_duration;
				$fw_options['english_level']         = $english_level;
				$fw_options['show_attachments']      = $show_attachments;
				$fw_options['address']            	 = $address;
				$fw_options['longitude']          	 = $longitude;
				$fw_options['latitude']           	 = $latitude;
				$fw_options['country']            	 = $location;

				/* Udate project/job data */
				fw_set_db_post_option($post_id, null, $fw_options);

				if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your job has been updated', 'workreap_api');
				} else {
					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapJobPost')) {
							$email_helper = new WorkreapJobPost();
							$emailData 	  = array();

							$employer_name 		= workreap_get_username($user_id);
							$employer_email 	= get_userdata($user_id)->user_email;
							$employer_profile 	= get_permalink($user_id);
							$job_title 			= esc_html(get_the_title($post_id));
							$job_link 			= get_permalink($post_id);

							$emailData['employer_name'] 	= esc_html($employer_name);
							$emailData['employer_email'] 	= sanitize_email($employer_email);
							$emailData['employer_link'] 	= esc_url($employer_profile);
							$emailData['status'] 			= esc_html($job_status);
							$emailData['job_link'] 			= esc_url($job_link);
							$emailData['job_title'] 		= esc_html($job_title);

							$email_helper->send_admin_job_post($emailData);
							$email_helper->send_employer_job_post($emailData);
						}
					}

					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your job has been posted.', 'workreap_api');
				}

				//add custom data
				do_action('workreap_post_job_extra_data', $request, $post_id);

				//Prepare Params
				$params_array['user_identity'] 	= $user_id;
				$params_array['user_role'] 		= apply_filters('workreap_get_user_type', $user_id);
				$params_array['type'] 			= 'project_create';

				do_action('wt_process_job_child', $params_array);
			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occur, please try again later', 'workreap_api');
			}

			return new WP_REST_Response($json, 200);
		}


		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_listing($request)
		{

			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$job_id			= !empty($request['job_id']) ? intval($request['job_id']) : '';
			$author_id		= !empty($request['company_id']) ? intval($request['company_id']) : '';
			$profile_id		= !empty($request['profile_id']) ? intval($request['profile_id']) : '';
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$listing_type	= !empty($request['listing_type']) ? esc_html($request['listing_type']) : '';
			$json 			= $items = array();
			$today 			= time();

			if (!empty($profile_id)) {
				$saved_projects	= get_post_meta($profile_id, '_saved_projects', true);
			} else {
				$saved_projects	= array();
			}
			$job_faq_option		= fw_get_db_settings_option('job_faq_option');
			$defult				= get_template_directory_uri() . '/images/featured.png';

			if ($request['listing_type'] === 'single') {
				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'projects',
					'post__in' 		 	  	=> array($job_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}

			if (!empty($listing_type) && $listing_type === 'featured') {
				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'projects',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);

				//order by pro member
				$query_args['meta_key'] = '_featured_job_string';
				$query_args['orderby']	 = array(
					'ID'      		=> 'DESC',
					'meta_value' 	=> 'DESC',
				);

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}

			if (!empty($listing_type) && $listing_type === 'single') {
				$post_id		= !empty($request['job_id']) ? $request['job_id'] : '';
				$query_args = array(
					'post_type' 	 	  	=> 'any',
					'p'						=> $post_id
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}

			if (!empty($listing_type) && !empty($author_id) && $listing_type === 'company') {
				$order		 	= 'DESC';
				$query_args 	= array(
					'posts_per_page' 	=> -1,
					'post_type' 	 	=> 'projects',
					'post_status' 	 	=> array('publish', 'pending'),
					'author' 			=> $author_id,
					'suppress_filters' 	=> false
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}

			if (!empty($listing_type) && $listing_type === 'latest') {
				$order		 	= 'DESC';
				$query_args 	= array(
					'posts_per_page' 	  	=> $limit,
					'post_type' 	 	  	=> 'projects',
					'paged' 		 	  	=> $page_number,
					'post_status' 	 	  	=> 'publish',
					'order'					=> 'ID',
					'orderby'				=> $order,
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}

			if (!empty($listing_type) && $listing_type === 'favourite') {
				$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				$wishlist 			= get_post_meta($linked_profile, '_saved_projects', true);
				$wishlist			= !empty($wishlist) ? $wishlist : array();
				if (!empty($wishlist)) {
					$order		 = 'DESC';
					$query_args = array(
						'posts_per_page' 	  	=> $limit,
						'post_type' 	 	  	=> 'projects',
						'paged' 		 	  	=> $page_number,
						'post_status' 	 	  	=> 'publish',
						'post__in'				=> $wishlist,

						'ignore_sticky_posts' 	=> 1
					);
					$query 			= new WP_Query($query_args);
					$count_post 	= $query->found_posts;
				} else {
					$json['type']		= 'error';
					$json['message']	= esc_html__('You have no project in your favourite list.', 'workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			}

			if (!empty($listing_type) && $listing_type === 'search') {
				global $wp_query;
				//Search parameters
				$keyword 		= !empty($request['keyword']) ? $request['keyword'] : '';
				$languages 		= !empty($request['language']) ? json_decode($request['language'], true) : array();
				$categories 	= !empty($request['category']) ? json_decode($request['category'], true) : array();
				$locations 	 	= !empty($request['location']) ? json_decode($request['location'], true) : array();
				$skills			= !empty($request['skills']) ? json_decode($request['skills'], true) : array();
				$duration 		= !empty($request['duration']) ? json_decode($request['duration'], true) : '';
				$type 			= !empty($request['type']) ? json_decode($request['type'],true) : array();
				$job_type 		= !empty( $request['job_type'] ) ? json_decode($request['job_type'], true) : array();
				$experiences 	= !empty($request['experience']) ? json_decode($request['experience'], true) : array();

				$project_type	= !empty($request['project_type']) ? $request['project_type'] : '';
				$english_level  = !empty($request['english_level']) ? json_decode($request['english_level'], true) : array();

				$minprice 		= !empty($request['minprice']) ? intval($request['minprice']) : 0;
				$maxprice 		= !empty($request['maxprice']) ? intval($request['maxprice']) : '';

				$tax_query_args  = array();
				$meta_query_args = array();
				$eng_lvl = array();

				//Languages
				if (!empty($languages[0]) && is_array($languages)) {
					$query_relation = array('relation' => 'OR',);
					$lang_args  	= array();

					foreach ($languages as $key => $lang) {
						$lang_args[] = array(
							'taxonomy' => 'languages',
							'field'    => 'slug',
							'terms'    => $lang,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $lang_args);
				}

				//cat
				if (is_tax('project_cat') && empty($categories)) {
					$cat = $wp_query->get_queried_object();
					if (!empty($cat->slug)) {
						$categories = array($cat->slug);
					}
				}

				//skills
				if (is_tax('skills') && empty($skills)) {
					$skill = $wp_query->get_queried_object();
					if (!empty($skill->slug)) {
						$skills = array($skill->slug);
					}
				}

				//Location
				if (is_tax('locations') && empty($locations)) {
					$location = $wp_query->get_queried_object();
					if (!empty($location->slug)) {
						$locations = array($location->slug);
					}
				}

				//Language
				if (is_tax('languages') && empty($languages)) {
					$language = $wp_query->get_queried_object();
					if (!empty($language->slug)) {
						$languages = array($language->slug);
					}
				}

				//Categories
				if (!empty($categories[0]) && is_array($categories)) {
					$query_relation = array('relation' => 'OR',);
					$category_args  = array();

					foreach ($categories as $key => $cat) {
						$category_args[] = array(
							'taxonomy' => 'project_cat',
							'field'    => 'slug',
							'terms'    => $cat,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $category_args);
				}

				//Locations
				if (!empty($locations[0]) && is_array($locations)) {
					$query_relation = array('relation' => 'OR',);
					$location_args  = array();

					foreach ($locations as $key => $loc) {
						$location_args[] = array(
							'taxonomy'	=> 'locations',
							'field'    	=> 'slug',
							'terms'    	=> $loc,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $location_args);
				}

				//skills
				if (!empty($skills[0]) && is_array($skills)) {
					$query_relation = array('relation' => 'OR',);
					$skills_args  = array();

					foreach ($skills as $key => $skill) {
						$skills_args[] = array(
							'taxonomy' => 'skills',
							'field'    => 'slug',
							'terms'    => $skill,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $skills_args);
				}

				//Experience
				if (!empty($experiences[0]) && is_array($experiences)) {
					$query_relation = array('relation' => 'OR',);
					$experiences_args  = array();

					foreach ($experiences as $key => $experience) {
						$experiences_args[] = array(
							'taxonomy' => 'project_experience',
							'field'    => 'slug',
							'terms'    => $experience,
						);
					}
					$tax_query_args[] = array_merge($query_relation, $experiences_args);
				}

				//experience
				if (!empty($experiences[0]) && is_array($experiences)) {
					$query_relation = array('relation' => 'OR',);
					$experiences_args  = array();

					foreach ($experiences as $key => $experience) {
						$experiences_args[] = array(
							'taxonomy' => 'project_experience',
							'field'    => 'slug',
							'terms'    => $experience,
						);
					}
					$tax_query_args[] = array_merge($query_relation, $experiences_args);
				}

				$freelancertype	= '';
				$optin_select = '';
				if (function_exists('fw_get_db_settings_option')) {
					$freelancertype	= fw_get_db_settings_option('job_freelancer_type', $default_value = null);
					$optin_select	= fw_get_db_settings_option('freelancertype_multiselect', $default_value = null);
					$optin_select 	= ($optin_select === 'enable') ? 'multiselect' : '';
				}

				if (!empty($freelancertype) && $freelancertype === 'enable') {
					//Freelancer type Level
					if (!empty($optin_select) && $optin_select === 'multiselect' && !empty($type)) {
						if (!empty($type) && !empty($type[0]) && is_array($type)) {

							$query_relation = array('relation' => 'OR',);
							$sub_types_args = array();
							foreach ($type as $key => $value) {
								$sub_types_args[] = array(
									'key' 		=> '_freelancer_level',
									'value' 	=> strval($value),
									'compare' 	=> 'LIKE'
								);
							}
							$meta_query_args[] = array_merge($query_relation, $sub_types_args);
						}
					} else if (!empty($type)) {
						$meta_query_args[] = array(
							'key' 		=> '_freelancer_level',
							'value' 	=> $type,
							'compare' 	=> 'IN'
						);
					}
				}

				//Job type option Level
				if (!empty($job_type)) {
					$meta_query_args[] = array(
						'key' 		=> '_job_option',
						'value' 	=> $job_type,
						'compare' 	=> 'IN'
					);
				}

				//Duration
				if (!empty($duration)) {
					$duration_args[] = array(
						'key'		=> '_project_duration',
						'value' 	=> $duration,
						'compare' 	=> 'IN'
					);

					$meta_query_args = array_merge($meta_query_args, $duration_args);
				}

				//English Level
				if (!empty($english_level)) {
					$query_relation = array('relation' => 'OR',);
					$english_level_args = array();
					foreach ($english_level as $key => $value) {
						$english_level_args[] = array(
							'key' 		=> '_english_level',
							'value' 	=> $value,
							'compare' 	=> 'LIKE'
						);
					}


					$meta_query_args[] = array_merge($query_relation, $english_level_args);
				}

				//Radius Search
				if (!empty($_GET['geo'])) {

					$Latitude 	= '';
					$Longitude 	= '';
					$prepAddr 	= '';
					$minLat 	= '';
					$maxLat 	= '';
					$minLong 	= '';
					$maxLong 	= '';

					$address = sanitize_text_field($_GET['geo']);
					$prepAddr = str_replace(' ', '+', $address);


					if (isset($_GET['geo_distance']) && !empty($_GET['geo_distance'])) {
						$radius = $_GET['geo_distance'];
					} else {
						$radius = 300;
					}

					//Distance in miles or kilometers
					if (function_exists('fw_get_db_settings_option')) {
						$dir_distance_type = fw_get_db_settings_option('dir_distance_type');
					} else {
						$dir_distance_type = 'mi';
					}

					if ($dir_distance_type === 'km') {
						$radius = $radius * 0.621371;
					}

					$Latitude	= isset($_GET['lat']) ? esc_attr($_GET['lat']) : '';
					$Longitude	= isset($_GET['long']) ? esc_attr($_GET['long']) : '';

					if (!empty($Latitude) && !empty($Longitude)) {
						$Latitude	 = $Latitude;
						$Longitude   = $Longitude;
					} else {
						$args = array(
							'timeout'     => 15,
							'headers' => array('Accept-Encoding' => ''),
							'sslverify' => false
						);

						$url	    = 'https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&key=' . $google_key;;
						$response   = wp_remote_get($url, $args);
						$geocode	= wp_remote_retrieve_body($response);

						$output	  = json_decode($geocode);

						if (isset($output->results) && !empty($output->results)) {
							$Latitude	 = $output->results[0]->geometry->location->lat;
							$Longitude   = $output->results[0]->geometry->location->lng;
						}
					}

					if (!empty($Latitude) && !empty($Longitude)) {
						$zcdRadius  = new RadiusCheck($Latitude, $Longitude, $radius);
						$minLat 	= $zcdRadius->MinLatitude();
						$maxLat 	= $zcdRadius->MaxLatitude();
						$minLong 	= $zcdRadius->MinLongitude();
						$maxLong 	= $zcdRadius->MaxLongitude();

						$meta_query_args = array(
							'relation' => 'AND',
							array(
								'key' 		=> '_latitude',
								'value' 	=> array($minLat, $maxLat),
								'compare' 	=> 'BETWEEN',
								'type' 		=> 'DECIMAL(20,10)',
							),
							array(
								'key' 		=> '_longitude',
								'value' 	=> array($minLong, $maxLong),
								'compare' 	=> 'BETWEEN',
								'type' 		=> 'DECIMAL(20,10)',
							)
						);

						if (isset($query_args['meta_query']) && !empty($query_args['meta_query'])) {
							$meta_query = array_merge($meta_query_args, $query_args['meta_query']);
						} else {
							$meta_query = $meta_query_args;
						}

						$query_args['meta_query'] = $meta_query;
					}
				}

				//Project Type
				if (!empty($project_type) && ($project_type === 'hourly' || $project_type === 'fixed')) {
					$meta_query_args[] = array(
						'key' 			=> '_project_type',
						'value' 		=> $project_type,
						'compare' 		=> '='
					);
				}

				//Hourly Rate
				if (!empty($project_type) &&  $project_type === 'hourly' && !empty($maxprice)) {
					$range_array 		= array($minprice, $maxprice);
					$price_args = array();
					if (!empty($job_price_option) && $job_price_option === 'enable') {
						$query_relation = array('relation' => 'OR',);

						$price_args[]  = array(
							'key' 			=> '_project_cost',
							'value' 		=> $range_array,
							'type'    		=> 'NUMERIC',
							'compare' 		=> 'BETWEEN'
						);

						$price_args[] = array(
							'key'     => '_max_price',
							'value'   => $range_array,
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						);
						$meta_query_args[] = array_merge($query_relation, $price_args);
					} else {
						if (!empty($range_array)) {
							$meta_query_args[] = array(
								'key'     => '_hourly_rate',
								'value'   => $range_array,
								'type'    => 'NUMERIC',
								'compare' => 'BETWEEN',
							);
						}
					}
				} else if (!empty($project_type) &&  $project_type === 'fixed' && !empty($maxprice)) {
					$price_range 		= array($minprice, $maxprice);
					$price_args 		= array();

					if (!empty($job_price_option) && $job_price_option === 'enable') {
						$query_relation = array('relation' => 'OR',);
						$price_args[]  = array(
							'key' 			=> '_project_cost',
							'value' 		=> $price_range,
							'type'    		=> 'NUMERIC',
							'compare' 		=> 'BETWEEN'
						);

						$price_args[] = array(
							'key'     => '_max_price',
							'value'   => $price_range,
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						);
						$meta_query_args[] = array_merge($query_relation, $price_args);
					} else {
						$meta_query_args[]  = array(
							'key' 			=> '_project_cost',
							'value' 		=> $price_range,
							'type'    		=> 'NUMERIC',
							'compare' 		=> 'BETWEEN'
						);
					}
				} elseif (!empty($maxprice)) {
					$price_range 		= array($minprice, $maxprice);
					$price_args 		= array();

					if (!empty($job_price_option) && $job_price_option === 'enable') {
						$query_relation = array('relation' => 'OR',);
						$price_args[]  = array(
							'key' 			=> '_project_cost',
							'value' 		=> $price_range,
							'type'    		=> 'NUMERIC',
							'compare' 		=> 'BETWEEN'
						);

						$price_args[] = array(
							'key'     => '_max_price',
							'value'   => $price_range,
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						);
						$meta_query_args[] = array_merge($query_relation, $price_args);
					} else {
						$meta_query_args[]  = array(
							'key' 			=> '_project_cost',
							'value' 		=> $price_range,
							'type'    		=> 'NUMERIC',
							'compare' 		=> 'BETWEEN'
						);
					}
				}

				$project_search_status = !empty($project_search_status) ? $project_search_status : array('publish');

				//Main Query
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'projects',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => $project_search_status,
					'ignore_sticky_posts' => 1
				);

				//order by pro 
				$query_args['meta_key'] = '_featured_job_string';
				$query_args['orderby']	 = array(
					'meta_value' 	=> 'DESC',
					'ID'      		=> 'DESC',
				);

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation 		= array('relation' => 'AND',);
					$meta_query_args 		= array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}

				//Taxonomy Query
				if (!empty($tax_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);
				}

				//keyword search
				if (!empty($keyword)) {
					$query_args['s']	=  $keyword;
				}

				$query = new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}
			if (empty($listing_type)) {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Please provide api type', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			//Start Query working.
			if ($query->have_posts()) {
				$duration_list 			= worktic_job_duration_list();
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					$project_id	 = $post->ID;

					$item['favourite']			= '';
					if (!empty($saved_projects)  &&  in_array($project_id, $saved_projects)) {
						$item['favourite']			= 'yes';
					}

					/* Featured Jobs */
					$featured_job	= get_post_meta($project_id, '_featured_job_string', true);
					$item['is_featured'] = !empty($featured_job) ? 'yes' : 'no';

					$item['location']		= workreap_get_location($project_id);
					$author_id				= get_the_author_meta('ID');
					$linked_profile			= workreap_get_linked_profile_id($author_id);
					$item['job_id']			= $project_id;
					$item['job_link']		= get_the_permalink($project_id);
					$is_verified			= get_post_meta($linked_profile, '_is_verified', true);
					$item['_is_verified'] 	= !empty($is_verified) ? $is_verified : '';
					$item['project_level']	= apply_filters('workreap_filter_project_level', $project_id);
					$english_level			= get_post_meta($project_id, '_english_level', true);
					$item['english_level'] 	= !empty($english_level) ? $english_level : '';
					$languages_arr			= wp_get_post_terms($project_id, 'languages', array('fields' => 'all'));
					$proposals  			= workreap_get_totoal_proposals($project_id, 'array', '-1');

					/* languages */
					$job_languages = array();
					if (!empty($languages_arr) && is_array($languages_arr)) {
						foreach ($languages_arr as $key => $language_obj) {
							$job_languages[] = array(
								'id' 	=> $language_obj->term_id,
								'name' 	=> $language_obj->name,
								'slug' 	=> $language_obj->slug,
							);
						}
					}
					$item['languages'] 	= $job_languages;

					/* categories */
					$job_categories 	= array();
					$job_categories_arr = wp_get_post_terms($project_id, 'project_cat', array('fields' => 'all'));
					if (!empty($job_categories_arr) && is_array($job_categories_arr)) {
						foreach ($job_categories_arr as $key => $categories_obj) {
							$job_categories[] = array(
								'id' 	=> $categories_obj->term_id,
								'name' 	=> $categories_obj->name,
								'slug' 	=> $categories_obj->slug,
							);
						}
					}
					$item['categories'] 	= $job_categories;

					$job_option	= get_post_meta($project_id, '_job_option', true);
					$job_option	= !empty($job_option) ? workreap_get_job_option($job_option) : '';

					$longitude = $latitude = '';
					if (function_exists('fw_get_db_post_option')) {
						$project_type 		= fw_get_db_post_option($project_id, 'project_type', true);
						$project_duration   = fw_get_db_post_option($project_id, 'project_duration', true);
						$project_documents  = fw_get_db_post_option($project_id, 'project_documents', true);
						$project_documents	= !empty($project_documents) ? $project_documents : array();
						$db_project_type 	= fw_get_db_post_option($project_id, 'project_type', true);
						$expiry_date 		= fw_get_db_post_option($project_id, 'expiry_date', true);
						$deadline_date   	= fw_get_db_post_option($project_id, 'deadline', true);
						$longitude   		= fw_get_db_post_option($project_id, 'longitude', true);
						$latitude   		= fw_get_db_post_option($project_id, 'latitude', true);

						$project_cost 		= !empty($db_project_type['fixed']['project_cost']) ? $db_project_type['fixed']['project_cost'] : '';
						$max_cost 			= !empty($db_project_type['fixed']['max_price']) ? $db_project_type['fixed']['max_price'] : '';
						$hourly_rate 		= !empty($db_project_type['hourly']['hourly_rate']) ? $db_project_type['hourly']['hourly_rate'] : '';
						$estimated_hours	= !empty($db_project_type['hourly']['estimated_hours']) ? $db_project_type['hourly']['estimated_hours'] : '';
					}

					$deadline_date	  = !empty($deadline_date) ? workreap_date_format_fix($deadline_date) : '';
					$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';

					$item['faq']		= array();
					if (!empty($job_faq_option) && $job_faq_option == 'yes') {
						$faqs_arr 	= array();
						$faqs 		= fw_get_db_post_option($project_id, 'faq');
						$faqs		= !empty($faqs) ? $faqs : array();
						if (!empty($faqs)) {
							foreach ($faqs as $faq_val) {
								$faqs_arr[] = array(
									'faq_question' 	=> $faq_val['faq_question'],
									'faq_answer' 	=> $faq_val['faq_answer'],
								);
							}
						}
						$item['faq']		= $faqs_arr;
					}

					/* job direction */
					$direction = '';
					if (!empty($latitude)) {
						$direction = 'http://www.google.com/maps/place/' . esc_js($latitude) . ',' . esc_js($longitude) . '/@' . esc_js($latitude) . ',' . esc_js($longitude) . ',17z';
					}

					/**
					 * employer detail
					 *  */
					$employer_data_arr = apply_filters('workreap_api_employer_details', $project_id);

					/* is proposal already submitted */
					$proposal_submitted = apply_filters('workreap_api_proposal_submitted', $user_id, $project_id);

					/* share project */
					$social_share = apply_filters('workreap_api_social_share_job', $project_id);

					$item['project_type']   		= !empty($project_type['gadget']) ? ucfirst($project_type['gadget']) : '';
					$item['project_duration']		= !empty($project_duration) ? $duration_list[$project_duration] : '';
					$item['project_cost']			= !empty($project_cost) ? apply_filters('workreap_price_format', $project_cost, 'return') : '';
					$item['max_price']				= !empty($max_cost) ? apply_filters('workreap_price_format', $max_cost, 'return') : '';
					$item['hourly_rate']			= !empty($hourly_rate) ? apply_filters('workreap_price_format', $hourly_rate, 'return') : '';
					$item['estimated_hours']		= !empty($estimated_hours) ? $estimated_hours : '';
					$item['expiry_date']			= !empty($expiry_date) ? $expiry_date : '';
					$item['deadline_date']			= !empty($deadline_date) ? $deadline_date : '';
					$item['proposal_count']			= !empty($proposals) ? count($proposals) : 0;
					$item['proposal_submitted']		= $proposal_submitted;
					$item['direction']				= $direction;
					$item['share_job']				= $social_share;
					$item['employer']   			= $employer_data_arr;

					$docs						= array();
					if (!empty($project_documents) && is_array($project_documents)) {
						$docs_count	= 0;
						foreach ($project_documents as $value) {
							if (!empty($value['attachment_id'])) {
								$docs_count++;
								$file_detail  	= Workreap_file_permission::getDecrpytFile($value);
								$name        	= $file_detail['filename'];
								$docs[$docs_count]['document_name']   	= !empty($name) ? $name : '';
								$docs[$docs_count]['file_size']			= !empty(filesize(get_attached_file($value['attachment_id']))) ? size_format(filesize(get_attached_file($value['attachment_id'])), 2) : '';
								$docs[$docs_count]['filetype']        	= wp_check_filetype($value['url']);
								$docs[$docs_count]['extension']       	= !empty($filetype['ext']) ? $filetype['ext'] : '';
								$docs[$docs_count]['url']				= workreap_add_http($value['url']);
							}
						}
					}

					$item['attachments']	= array_values($docs);

					$terms 					= wp_get_post_terms($project_id, 'skills');
					$skills					= array();
					if (!empty($terms)) {
						$sk_count	= 0;
						foreach ($terms as $term) {
							$sk_count++;
							$term_link 							= get_term_link($term->term_id, 'skills');
							$skills[$sk_count]['skill_link']	= $term_link;
							$skills[$sk_count]['skill_name']	= $term->name;
						}
					}
					$item['skills']				= array_values($skills);

					$item['employer_name']		= get_the_title($linked_profile);
					$item['employer_avatar']	= $avatar = apply_filters(
						'workreap_employer_avatar_fallback',
						workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile),
						array('width' => 100, 'height' => 100)
					);
					$item['project_title']		= get_the_title($project_id);
					$item['project_content']	= get_the_content($project_id);
					$item['job_type']       	= $job_option;
					$items[]				    = maybe_unserialize($item);
				}

				$json['type'] 				= "success";
				$json['count_totals'] 		= $count_post;
				$json['jobs'] 				= $items;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Some error occur, please try again later', 'workreap_api');
				$json['jobs']		= array();
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Cancel Milestone
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function cancel_milestone_request($request)
		{
			$json					= $items = $update_post = array();
			$user_id				= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$proposal_id			= !empty($request['proposal_id']) ? intval($request['proposal_id']) : '';
			$project_id				= get_post_meta($proposal_id, '_project_id', true);
			$cancelled_reason		= !empty($request['cancelled_reason']) ? ($request['cancelled_reason']) : '';

			if (empty($proposal_id)) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Proposal ID is required', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}

			if (empty($cancelled_reason)) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Cancelled reason is required', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}

			if (!empty($proposal_id) && !empty($cancelled_reason)) {
				update_post_meta($proposal_id, '_cancelled_reason', $cancelled_reason);
				update_post_meta($proposal_id, '_proposal_status', 'cancelled');
				update_post_meta($proposal_id, '_cancelled_user_id', $user_id);
				$update_post	= array(
					'ID'    		=>  $proposal_id,
					'post_status'   =>  'cancelled'
				);

				wp_update_post($update_post);

				$freelancer_id				= get_post_field('post_author', $proposal_id);
				$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
				$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile);
				$freelancer_link 		    = esc_url(get_the_permalink($freelancer_linked_profile));


				$employer_id				= get_post_field('post_author', $project_id);
				$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
				$employer_name 				= workreap_get_username('', $employer_linked_profile);

				$project_title				= get_the_title($project_id);
				$project_link				= get_the_permalink($project_id);

				$profile_id					= workreap_get_linked_profile_id($employer_linked_profile, 'post');
				$user_email 				= !empty($profile_id) ? get_userdata($profile_id)->user_email : '';

				//Send email to employer
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapMilestoneRequest')) {
						$email_helper = new WorkreapMilestoneRequest();
						$emailData = array();

						$emailData['freelancer_name'] 	= esc_html($hired_freelancer_title);
						$emailData['freelancer_link'] 	= esc_html($freelancer_link);
						$emailData['employer_name'] 	= esc_html($employer_name);
						$emailData['project_title'] 	= esc_html($project_title);
						$emailData['project_link'] 		= esc_html($project_link);
						$emailData['reason'] 			= esc_html($cancelled_reason);

						$emailData['email_to'] 			= esc_html($user_email);

						$email_helper->send_milestone_request_rejected_email($emailData);
					}
				}

				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Settings Updated.', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 200);
			}
		}

		/**
		 * List Milestone
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function list_milestone($request)
		{
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';

			$json				= array();
			$item				= array();
			$items				= array();

			$user_identity 		= $user_id;
			$url_identity 	 	= $user_identity;
			$linked_profile  	= workreap_get_linked_profile_id($user_identity);
			$post_id 		 	= $linked_profile;

			$date_format			= get_option('date_format');
			$proposal_id			= !empty($request['id']) ? intval($request['id']) : '';

			$project_id				= get_post_meta($proposal_id, '_project_id', true);
			$project_status			= get_post_status($project_id);

			$post_author			= get_post_field('post_author', $project_id);
			$hired_freelancer_id	= get_post_field('post_author', $proposal_id);

			$post_status			= get_post_status($proposal_id);
			$hired_freelance_id		= !empty($hired_freelancer_id) ? intval($hired_freelancer_id) : '';
			$hire_linked_profile	= workreap_get_linked_profile_id($hired_freelance_id);
			$hired_freelancer_title	= get_the_title($hire_linked_profile);
			$job_statuses			= worktic_job_statuses();
			$proposal_price			= get_post_meta($proposal_id, '_amount', true);
			$proposal_price			= !empty($proposal_price) ? $proposal_price : 0;

			$total_milestone_price			= workreap_get_milestone_statistics($proposal_id, array('pending', 'publish'));
			$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
			$meta_array	= array(
				array(
					'key'		=> '_propsal_id',
					'value'   	=> $proposal_id,
					'compare' 	=> '=',
					'type' 		=> 'NUMERIC'
				),
				array(
					'key'		=> '_status',
					'value'   	=> 'completed',
					'compare' 	=> '=',
				)
			);

			$completed	= workreap_get_post_count_by_meta('wt-milestone', 'publish', $meta_array);
			$completed	= !empty($completed) ? intval($completed) : 0;

			$remaning_price	= intval($proposal_price) > intval($total_milestone_price) ? $proposal_price - $total_milestone_price : 0;

			$hired_freelancer_avatar 	= apply_filters(
				'workreap_freelancer_avatar_fallback',
				workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $hire_linked_profile),
				array('width' => 225, 'height' => 225)
			);

			$proposal_status				= get_post_meta($proposal_id, '_proposal_status', true);
			$proposal_status				= !empty($proposal_status) ? $proposal_status : '';

			$order 			 = 'DESC';
			$sorting 		 = 'ID';
			$meta_query_args = array();

			$args 			= array(
				'posts_per_page' 	=> -1,
				'post_type' 		=> 'wt-milestone',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('pending', 'publish'),
				'suppress_filters' 	=> false
			);

			$meta_query_args[] = array(
				'key' 		=> '_propsal_id',
				'value' 	=> $proposal_id,
				'compare' 	=> '='
			);

			$query_relation 	= array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($args);

			if ($query->have_posts()) {
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$milstone_title		= get_the_title($post->ID);
					$milstone_content	= get_post_field('post_content', $post->ID);
					$milstone_price_single		= get_post_meta($post->ID, '_price', true);
					$milstone_date		= get_post_meta($post->ID, '_due_date', true);
					$milstone_due_date	= !empty($milstone_date) ? date($date_format, strtotime($milstone_date)) : '';
					$milstone_price		= !empty($milstone_price_single) ? html_entity_decode(workreap_price_format($milstone_price_single, 'return')) : '';

					$milstone_status	= get_post_status($post->ID);
					$edit_price			= $remaning_price + $milstone_price_single;
					$updated_status	= get_post_meta($post->ID, '_status', true);
					$updated_status	= !empty($updated_status) ? $updated_status : '';
					$status_class	= '';
					$status_text	= '';
					$status_option	= '';

					$order_id	= get_post_meta($post->ID, '_order_id', true);
					$order_id	= !empty($order_id) ? intval($order_id) : 0;
					$order_url	= '';

					if (!empty($order_id)) {
						if (class_exists('WooCommerce')) {
							$order		= wc_get_order($order_id);
							$order_url	= $order->get_view_order_url();
						}
					}

					if (!empty($updated_status)) {
						if (($updated_status === 'pay_now' || $updated_status === 'pending') && (!empty($proposal_status) && $proposal_status === 'approved' && empty($order_id))) {
							$status_text	= esc_html__('Pay Now', 'workreap_api');
							$status_class	= 'green';
						} else if ($updated_status === 'pending') {
							$status_text	= 'pending';
						} else if ($updated_status === 'hired') {
							$status_text	= esc_html__('Hired', 'workreap_api');
						} else if ($updated_status === 'completed') {
							$status_text	= esc_html__('Completed', 'workreap_api');
							$status_class	= '';
						}
					}

					$json['milestone_id']			= intval($post->ID);
					$json['milestone_price']		= $milstone_price;
					$json['milestone_title']		= $milstone_title;
					$json['milestone_due_date']		= $milstone_due_date;
					$json['updated_status']			= $updated_status;
					$json['status_class']			= $status_class;
					$json['order_id']				= $order_id;
					$json['milestone_content']		= $milstone_content;
					$json['milestone_date']			= $milstone_date;
					$json['milestone_price_single']	= $milstone_price_single;
					$json['proposal_id']			= $proposal_id;

					$item[]	= $json;
				endwhile;
				wp_reset_postdata();
			}

			$items		= maybe_unserialize($item);
			return new WP_REST_Response($items, 200);
		}

		/**
		 * Delete Employer job
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function delete_listing($data)
		{

			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json = $items = array();
			$project_id	= !empty($request['project_id']) ? intval($request['project_id']) : 0;
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$post_id	= workreap_get_linked_profile_id($user_id);

			if (empty($project_id)) {
				$json['type']       = 'error';
				$json['message']    = esc_html__('Project ID is required', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}

			if (empty($user_id)) {
				$json['type']           = 'error';
				$json['message']        = esc_html__('User ID is required', 'workreap_api');
				$items[] 			    = $json;
				return new WP_REST_Response($items, 203);
			}

			/* check current user is author/owner of this project */
			$post_author_id = get_post_field('post_author', $project_id);
			if ($post_author_id != $user_id) {
				$json['type']           = 'error';
				$json['message']        = esc_html__('You are not authorized', 'workreap_api');
				$items[] 			    = $json;
				return new WP_REST_Response($items, 203);
			}

			if (!empty($project_id) && !empty($user_id)) {
				$output = wp_delete_post($project_id, true);
				if ($output != false) {
					//Send email to user on project delete
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapJobPost')) {
							$email_helper 		= new WorkreapJobPost();
							$emailData 			= array();
							$meta_query_args 	= array();

							$query_args = array(
								'posts_per_page' => -1,
								'post_type' 		=> 'proposals',
								'suppress_filters' 	=> false,
							);

							$meta_query_args[] = array(
								'key' 			=> '_project_id',
								'value' 		=> $project_id,
								'compare' 		=> '='
							);
							$query_relation = array('relation' => 'AND',);
							$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);

							$proposals = get_posts($query_args);
							foreach ($proposals as $key => $proposal) {
								$freelance_id			= get_post_field('post_author', $proposal->ID);

								if (!empty($freelance_id)) {
									$author_data    = get_userdata($freelance_id);
									$email_to       = $author_data->data->user_email;
									$freelancer_post_id	= workreap_get_linked_profile_id($user_id);

									$emailData['email_to'] 			= esc_html($email_to);
									$emailData['project_title'] 	= esc_html(get_the_title($project_id));
									$emailData['employer_name'] 	= workreap_get_username($user_id);
									$emailData['employer_link'] 	= esc_html(get_the_permalink($post_id));
									$emailData['freelancer_name'] 	= workreap_get_username($freelance_id);
									$emailData['freelancer_link'] 	= esc_html(get_the_permalink($freelancer_post_id));

									$email_helper->send_delete_job_email($emailData);
									wp_delete_post($proposal->ID, true);
								}
							}
						}
					}
					$json['type']		= 'success';
					$json['message']	= esc_html__('Project deleted successfully!', 'workreap_api');
					$items[] = $json;
					return new WP_REST_Response($items, 200);
				} else {
					$json['type']		= 'error';
					$json['message']	= esc_html__('Project not delete, please try again later', 'workreap_api');
					$items[] = $json;
					return new WP_REST_Response($items, 203);
				}
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Some error occur, please try again later', 'workreap_api');
				$items[] = $json;
				return new WP_REST_Response($items, 203);
			}
		}

		/**
		 * Milestone price details
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function milestones_details($request)
		{
			$json				= array();
			$proposal_id		= !empty($request['proposal_id']) ? intval($request['proposal_id']) : 0;

			if (empty($proposal_id)) {
				$json = array(
					'type' 		=> 'error',
					'message' 	=> esc_html__('Proposal id is missing', 'workreap_api'),
				);
				return new WP_REST_Response($json, 203);
			}

			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}

			$project_id			= get_post_meta($proposal_id, '_project_id', true);
			$total_price		= get_post_meta($proposal_id, '_amount', true);
			$total_price		= !empty($total_price) ? html_entity_decode(workreap_price_format($total_price, 'return')) : 0;

			$completed_price	= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id, 'completed', 'amount') : '';
			$completed_price 	= !empty($completed_price) ? html_entity_decode(workreap_price_format($completed_price, 'return')) : html_entity_decode(workreap_price_format(0, 'return'));

			$hired_price		= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id, 'hired', 'amount') : 0;
			$hired_price		= !empty($hired_price) ? html_entity_decode(workreap_price_format($hired_price, 'return')) : html_entity_decode(workreap_price_format(0, 'return'));

			$pending_price		= workreap_get_milestone_statistics($proposal_id, 'pending');
			$pending_price		= !empty($pending_price) ? html_entity_decode(workreap_price_format($pending_price, 'return')) : workreap_price_format(0, 'return');

			$total_budget		= !empty($milestone['enable']['total_budget']['url']) ? $milestone['enable']['total_budget']['url'] : get_template_directory_uri() . '/images/budget.png';
			$in_escrow			= !empty($milestone['enable']['in_escrow']['url']) ? $milestone['enable']['in_escrow']['url'] : get_template_directory_uri() . '/images/escrow.png';
			$milestone_paid		= !empty($milestone['enable']['milestone_paid']['url']) ? $milestone['enable']['milestone_paid']['url'] : get_template_directory_uri() . '/images/paid.png';
			$remainings			= !empty($milestone['enable']['remainings']['url']) ? $milestone['enable']['remainings']['url'] : get_template_directory_uri() . '/images/remainings.png';

			$json = array(
				'total_price'		=> $total_price,
				'completed_price'	=> $completed_price,
				'hired_price'		=> $hired_price,
				'pending_price'		=> $pending_price,
				'total_budget'		=> $total_budget,
				'in_escrow'			=> $in_escrow,
				'milestone_paid'	=> $milestone_paid,
				'remainings'		=> $remainings
			);

			return new WP_REST_Response($json, 200);
		}

		/**
		 * Get Freelancer Posted Servies
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_freelancer_posted_services($request)
		{
			$json 		= $item = array();
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : 0;

			if (empty($user_id)) {
				$json = array(
					'type' 		=> 'error',
					'message' 	=> esc_html__('User id is missing!', 'workreap_api'),
				);
				return new WP_REST_Response($json, 203);
			}

			$args_services = array(
				'author'        =>  $user_id,
				'post_type'		=> 	'micro-services',
				'post_status'	=>  'publish',
				'orderby'       =>  'post_date',
				'order'         =>  'ASC',
				'posts_per_page' => -1
			);
			$listings		= get_posts($args_services);

			if (!empty($listings)) {
				foreach ($listings as $service) {
					$serviceID		= $service->ID;
					$serviceTitle	= $service->post_title;
					$item[]	= array(
						'serviceID'		=> $serviceID,
						'serviceTitle'	=> $serviceTitle
					);
				}
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Qoutes listing', 'workreap_api');
				$json['listing'] 	= $item;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['title'] 		= esc_html__('Oopss!', 'workreap_api');
				$json['message'] 	= esc_html__('No services added.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get USer by chat for Qoute
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_user_by_chat_qoutes($request)
		{
			$json = $employers_list = $employer_ = array();
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : 0;

			if (!empty($user_id)) {
				if (function_exists('fw_get_db_settings_option')) {
					$chat_api = fw_get_db_settings_option('chat');
					if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
						$employers_list	= apply_filters('wpguppy_get_users_by_chat', $user_id);
					} else {
						$employers_list = ChatSystem::getUsersThreadListData($user_id, '', 'list_receivers', array(), '');
						if (!empty($employers_list) && is_array($employers_list)) {
							$employers_list = wp_list_pluck($employers_list, 'userId');
						}
					}
					if (!empty($employers_list)) {
						foreach ($employers_list as $key => $employer) {
							$username		= workreap_get_username(intval($employer));
							$get_user_type	= apply_filters('workreap_get_user_type', $employer);
							$employer_[]	= array(
								'id'			=> $employer,
								'username'		=> $username,
								'get_user_type'	=> $get_user_type,
							);
						}

						$json = array(
							'type' 				=> 'success',
							'message'			=> esc_html__('Employer listing', 'workreap_api'),
							'employers_list' 	=> $employer_
						);
						return new WP_REST_Response($json, 200);
					} else {
						$json = array(
							'type' 				=> 'error',
							'title'				=> esc_html__('Failed!', 'workreap_api'),
							'message' 			=> esc_html__('No record found!', 'workreap_api'),
							'employers_list'	=> array()
						);
						return new WP_REST_Response($json, 203);
					}
				} else {
					$json = array(
						'type' 		=> 'error',
						'title'		=> esc_html__('Failed!', 'workreap_api'),
						'message'	=> esc_html__('Something went wrong.', 'workreap_api')
					);
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json = array(
					'type' 				=> 'error',
					'title'				=> esc_html__('Failed!', 'workreap_api'),
					'message' 			=> esc_html__('No record found!', 'workreap_api'),
					'employers_list'	=> array()
				);
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Qoutes Listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_qoutes_listing($data)
		{
			$qoute_data	= $reciever_data = $item = $json = array();
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);

			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$keyword	= !empty($request['keyword']) ? esc_html($request['keyword']) : '';
			$show_posts	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
			$pg_page 	= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
			$pg_paged 	= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;


			$user_identity 	= $user_id;
			$user_type		= apply_filters('workreap_get_user_type', $user_id);
			$linked_profile = workreap_get_linked_profile_id($user_identity);
			$paged 			= max($pg_page, $pg_paged);
			$order 			= 'DESC';
			$sorting 		= 'ID';

			if (!empty($user_type) && $user_type == 'freelancer') {
				$query_args	= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'send-quote',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> 'any',
					'paged' 			=> $page_number,
					'suppress_filters' 	=> false,
					'author'			=> $user_identity,

				);
				//keyword search
				if (!empty($keyword)) {
					$query_args['s']	=  $keyword;
				}
				$meta_query_args = array();
				$meta_query_args[]  = array(
					'key' 			=> 'hiring_status',
					'value' 		=> 'pending',
				);
				if (!empty($meta_query_args)) {
					$query_relation 		= array('relation' => 'AND',);
					$meta_query_args 		= array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
			} else {
				$query_args	= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'send-quote',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> 'publish',
					'paged' 			=> $page_number,
					'suppress_filters' 	=> false,
					's'                 => $keyword
				);
				$meta_query_args = array();
				$meta_query_args[]  = array(
					'key' 			=> 'hiring_status',
					'value' 		=> 'pending',
				);
				$meta_query_args[]  = array(
					'key' 			=> 'employer',
					'value' 		=> $user_identity,
				);
				if (!empty($meta_query_args)) {
					$query_relation 		= array('relation' => 'AND',);
					$meta_query_args 		= array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
			}
			$query 		= new WP_Query($query_args);
			$count_post = $query->found_posts;
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					$author_id 			= $post->post_author;
					$service_id			= get_post_meta($post->ID, 'service', true);
					$declined			= get_post_meta($post->ID, 'declined', true);
					$employer_id		= get_post_meta($post->ID, 'employer', true);
					$service_title		= get_the_title($service_id);
					$reason				= get_post_meta($post->ID, 'reason', true);
					$service_link		= get_the_permalink($service_id);
					$featured_img		= get_the_post_thumbnail_url($service_id, array(100, 100));
					$featured_img		= !empty($featured_img) ? esc_url($featured_img) : '';
					$user_price 		= get_post_meta($post->ID, 'price', true);
					$date 				= get_post_meta($post->ID, 'date', true);
					$content 			= get_post_field('post_content', $post->ID);

					$qoute_data = array(
						'id' 				=> $post->ID,
						'service_title' 	=> $service_title,
						'service_link' 		=> $service_link,
						'service_id' 		=> $service_id,
						'featured_img' 		=> $featured_img,
						'user_price' 		=> $user_price,
						'formated_price' 	=> !empty($user_price) ? workreap_price_format($user_price, 'return') : '',
						'date' 				=> !empty($date) ? $date : '',
						'content' 			=> !empty($content) ? $content : '',
					);
					$qoute_decline_msg = $qoute_reason = '';
					if (!empty($reason)) {
						$qoute_decline_msg	= esc_html__('Decline Qoute', 'workreap_api');
						$qoute_reason	= $reason;
					}

					if (!empty($user_type) && $user_type == 'employer') {
						if (!empty($author_id)) {
							$profile_id			= workreap_get_linked_profile_id($author_id);
							$freelancer_title	= get_the_title($profile_id);
							$tagline			= workreap_get_tagline($profile_id);
							$freelancer_avatar 	= apply_filters(
								'workreap_employer_avatar_fallback',
								workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id),
								array('width' => 100, 'height' => 100)
							);

							$reciever_data = array(
								'freelancer_title'	=> $freelancer_title,
								'tagline' 		  	=> $tagline,
								'freelancer_avatar' => $freelancer_avatar,
								'frelancer_id' 		=> $author_id,
							);

							$item[]	= array(
								'qoute_decline_msg' 	=> $qoute_decline_msg,
								'reason' 				=> $qoute_reason,
								'quote_listing_basic' 	=> $qoute_data,
								'freelancer_detail'		=> $reciever_data,
							);
						}
					} else {
						if (!empty($employer_id)) {
							$profile_id			= workreap_get_linked_profile_id($employer_id);
							$employer_title		= get_the_title($profile_id);
							$tagline			= workreap_get_tagline($profile_id);
							$employer_avatar 	= apply_filters(
								'workreap_employer_avatar_fallback',
								workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id),
								array('width' => 100, 'height' => 100)
							);

							$reciever_data = array(
								'employer_title'	=> $employer_title,
								'tagline' 		  	=> $tagline,
								'employer_avatar' 	=> $employer_avatar,
								'employer_id' 		=> $employer_id,
							);

							$item[]	= array(
								'qoute_decline_msg' => $qoute_decline_msg,
								'reason' => $qoute_reason,
								'quote_listing_basic' => $qoute_data,
								'service_employer' => $reciever_data,
							);
						}
					}
				}
				wp_reset_postdata();

				/* service by user */
				$args_services = array(
					'author'        =>  $user_id,
					'post_type'		=> 	'micro-services',
					'post_status'	=>  'publish',
					'orderby'       =>  'post_date',
					'order'         =>  'ASC',
					'posts_per_page' => -1
				);
				$listings		= get_posts($args_services);

				/* Quote services */
				$service_listings = array();
				if (!empty($listings)) {
					foreach ($listings as $quote_serv) {
						$service_listings[] = array(
							'id' 			=> $quote_serv->ID,
							'post_date' 	=> $quote_serv->post_date,
							'post_title' 	=> $quote_serv->post_title,
							'post_content' 	=> $quote_serv->post_content,
						);
					}
				}
				$json['type'] 			= 'success';
				$json['message'] 		= esc_html__('Qoutes listing', 'workreap_api');
				$json['listing'] 		= $item;
				$json['total'] 			= $count_post;
				// $json['freelancer_services']	= $service_listings;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No record found!', 'workreap_api');
				$json['listing'] 	= array();
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Remove qoute Listing
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function remove_qoutes($data)
		{
			$json					= $item = array();
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$quote_id	= !empty($request['quote_id']) ? intval($request['quote_id']) : '';
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';

			$required = array(
				'quote_id'	=> esc_html__('Quote ID is required', 'workreap_api'),
				'user_id'	=> esc_html__('User ID is required', 'workreap_api')
			);

			foreach ($required as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				}
			}

			$qoute_post   	= get_post($quote_id);
			$author_id		= !empty($qoute_post) ? intval($qoute_post->post_author) : '';
			$linked_profile	= workreap_get_linked_profile_id($user_id);
			do_action('workreap_check_post_author_status', $linked_profile); //check if user is not blocked or deactive
			do_action('workreap_check_post_author_identity_status', $linked_profile); //check if user identity is verified
			if (!empty($author_id)) {
				if ($user_id == $author_id) {
					$response	= wp_delete_post($quote_id, false);
					if (!is_wp_error($response)) {
						$json['type'] 		= 'success';
						$json['title'] 		= esc_html__('Updated!', 'workreap_api');
						$json['message'] 	= esc_html__('Quotes listing update', 'workreap_api');
						return new WP_REST_Response($json, 200);
					}
				} else {

					$json['type'] 		= 'error';
					$json['title'] 		= esc_html__('Oopss!', 'workreap_api');
					$json['message'] 	= esc_html__('Action does not perform', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type'] 		= 'error';
				$json['title'] 		= esc_html__('Oopss!', 'workreap_api');
				$json['message'] 	= esc_html__('Something went wrong.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * quote_listing_basicUpdate/Add Qoutes 
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function add_qoutes($data)
		{
			$json = $qoute_data	= $reciever_data = $item = array();
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$user_id				= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$employer				= !empty($request['employer']) ? intval($request['employer']) : '';
			$service				= !empty($request['service']) ? intval($request['service']) : '';
			$service_title			= !empty($service) ? get_the_title($service) : rand(1, 999999);
			$price					= !empty($request['price']) ? intval($request['price']) : '';
			$date					= !empty($request['date']) ? $request['date'] : '';
			$description			= !empty($request['description']) ? esc_html($request['description']) : '';

			$response 	= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}
			$required = array(
				'employer'   	=> esc_html__('Please select the employer', 'workreap_api'),
				'service'  		=> esc_html__('Select service which quote you want to send', 'workreap_api'),
				'price'   		=> esc_html__('Add quote price', 'workreap_api'),
				'date'   		=> esc_html__('Add dilivery date', 'workreap_api')
			);

			foreach ($required as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] 		= 'error';
					$json['message']	= $value;
					return new WP_REST_Response($json, 203);
				}

				if ($key === 'price' && empty(floatval($request[$key]))) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				}
			}
			$profile_id	= workreap_get_linked_profile_id($user_id);
			do_action('workreap_check_post_author_status', $profile_id); //check if user is not blocked or deactive
			do_action('workreap_check_post_author_identity_status', $profile_id); //check if user identity is verified

			if (isset($request['submit_type']) && $request['submit_type'] === 'update') {

				$current = !empty($request['qoute_id']) ? esc_html($request['qoute_id']) : '';

				$post_author = get_post_field('post_author', $current);
				$post_id 	 = $current;

				if (intval($post_author) === intval($user_id)) {
					$article_post = array(
						'ID' 			=> $current,
						'post_title' 	=> $service_title,
						'post_content' 	=> $description,
						'post_status' 	=> 'publish',
					);

					wp_update_post($article_post);
				} else {
					$json['type'] = 'error';
					$json['message'] = esc_html__('You are not authorized to update this quote', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$user_post = array(
					'post_title'    => wp_strip_all_tags($service_title),
					'post_status'   => 'publish',
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'send-quote',
				);
				$post_id  = wp_insert_post($user_post);
			}
			if ($post_id) {
				update_post_meta($post_id, 'employer', $employer);
				update_post_meta($post_id, 'service', $service);
				update_post_meta($post_id, 'price', $price);
				update_post_meta($post_id, 'date', $date);
				update_post_meta($post_id, 'hiring_status', 'pending');
				update_post_meta($post_id, 'declined', 'no');

				//Email variables
				$service_id				= $service;
				$employer_id			= $employer;

				//Freelancer
				$freelancer_name 		= workreap_get_username($user_id);
				$linked_profile  		= workreap_get_linked_profile_id($user_id);
				$freelancer_link 		= get_the_permalink($linked_profile);

				//employer
				$employer_name 			= workreap_get_username($employer_id);
				$employer_linked  		= workreap_get_linked_profile_id($employer_id);
				$employer_link 			= get_the_permalink($employer_linked);
				$email_to 				= get_userdata($employer_id)->user_email;

				//service
				$service_name 			= get_the_title($service_id);
				$service_link 			= get_the_permalink($service_id);

				$emailData 	  = array();
				$emailData['freelancer_name'] 		= esc_html($freelancer_name);
				$emailData['freelancer_link'] 		= esc_url($freelancer_link);
				$emailData['service_name'] 			= esc_html($service_name);
				$emailData['service_link'] 			= esc_url($service_link);
				$emailData['employer_name'] 		= esc_html($employer_name);
				$emailData['employer_link'] 		= esc_url($employer_link);
				$emailData['email_to'] 				= esc_html($email_to);

				$push	= array();
				$push['freelancer_id']		= $user_id;
				$push['employer_id']		= $employer_id;
				$push['service_id']			= $service_id;

				$push['%freelancer_link%']	= $emailData['freelancer_link'];
				$push['%freelancer_name%']	= $emailData['freelancer_name'];
				$push['%employer_name%']	= $emailData['employer_name'];
				$push['%employer_link%']	= $emailData['employer_link'];
				$push['%service_name%']		= $emailData['service_name'];
				$push['%service_link%']		= $emailData['service_link'];

				//Send email to users
				$json['message'] 	= esc_html__('Your quote has been sent to employer.', 'workreap_api');

				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapServiceOffer')) {

						$email_helper = new WorkreapServiceOffer();
						$email_helper->send_offer($emailData);

						//Push notification
						$push['type']				= 'quote_sent';
						do_action('workreap_user_push_notify', array($employer_id), '', 'pusher_emp_noty_send_offer', $push);
					}
				}

				$json['type'] 		= 'success';
				$json['url'] 		= Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_id, true, 'quote_listing');
				wp_send_json($json);
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occur, please try again later', 'workreap_api');
				wp_send_json($json);
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Qoute decline
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function decline_qoutes($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);

			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$quote_id	= !empty($request['quote_id']) ?  intval($request['quote_id']) : '';
			$reason		= !empty($request['reason']) ?  esc_html($request['reason']) : '';
			$profile_id	= workreap_get_linked_profile_id($user_id);
			do_action('workreap_check_post_author_status', $profile_id); //check if user is not blocked or deactive
			do_action('workreap_check_post_author_identity_status', $profile_id); //check if user identity is verified

			$required = array(
				'reason'   	=> esc_html__('Please add decline reason', 'workreap_api'),
				'user_id'   => esc_html__('Something went wrong.', 'workreap_api'),
				'quote_id'  => esc_html__('Qoute Id is missing.', 'workreap_api'),
			);

			foreach ($required as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] = 'error';
					$json['message'] = $value;
					return new WP_REST_Response($json, 203);
				}
			}
			$quote_post = array(
				'ID' 			=> $quote_id,
				'post_status' 	=> 'pending',
			);
			$updated	= wp_update_post($quote_post);
			if (!is_wp_error($updated)) {

				update_post_meta($quote_id, 'reason', $reason);
				update_post_meta($quote_id, 'declined', 'yes');

				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapServiceOffer')) {
						$emailData 	  = array();
						$email_helper = new WorkreapServiceOffer();

						//Email variables
						$service_id				= get_post_meta($quote_id, 'service', true);
						$employer_id			= get_post_meta($quote_id, 'employer', true);

						//employer
						$employer_name 			= workreap_get_username($user_id);
						$employer_linked  		= workreap_get_linked_profile_id($user_id);
						$employer_link 			= get_the_permalink($employer_linked);

						//Freelancer
						$quote_post 		= get_post($quote_id);
						$freelancer_id		= $quote_post->post_author;

						$freelancer_name 		= workreap_get_username($freelancer_id);
						$freelancer_linked  	= workreap_get_linked_profile_id($freelancer_id);
						$freelancer_link 		= get_the_permalink($freelancer_linked);
						$email_to 				= get_userdata($freelancer_id)->user_email;

						//service
						$service_name 			= get_the_title($service_id);
						$service_link 			= get_the_permalink($service_id);

						$emailData 	  = array();
						$emailData['freelancer_name'] 		= esc_html($freelancer_name);
						$emailData['freelancer_link'] 		= esc_url($freelancer_link);
						$emailData['service_name'] 			= esc_html($service_name);
						$emailData['service_link'] 			= esc_url($service_link);
						$emailData['employer_name'] 		= esc_html($employer_name);
						$emailData['employer_link'] 		= esc_url($employer_link);
						$emailData['email_to'] 				= esc_html($email_to);

						$email_helper->decline_offer($emailData);

						//Push notification
						$push	= array();
						$push['freelancer_id']		= $freelancer_id;
						$push['employer_id']		= $employer_id;
						$push['service_id']			= $service_id;

						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'];
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%service_name%']		= $emailData['service_name'];
						$push['%service_link%']		= $emailData['service_link'];

						$push['type']				= 'quote_declined';
						do_action('workreap_user_push_notify', array($freelancer_id), '', 'pusher_quote_rejected_content', $push);
					}
				}
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Qoute request has been declined.', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Something went wrong.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidAppGetJobsRoutes;
		$controller->register_routes();
	}
);
