<?php
if (!class_exists('AndroidAppGetFreelancersDashbord')) {

	class AndroidAppGetFreelancersDashbord extends WP_REST_Controller
	{

		/**
		 * Register the routes for the objects of the controller.
		 */
		public function register_routes()
		{
			$version 	= '1';
			$namespace 	= 'api/v' . $version;
			$base 		= 'dashboard';

			/* get proposals list */
			register_rest_route(
				$namespace,
				'/' . $base . '/get_my_proposals',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_my_proposals'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			// get earnings
			register_rest_route(
				$namespace,
				'/' . $base . '/get_my_earnings',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_my_earnings'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//Download attachment
			register_rest_route(
				$namespace,
				'/' . $base . '/get_attachments',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'download_attachments'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//cancelled jobs
			register_rest_route(
				$namespace,
				'/' . $base . '/get_freelancer_cancelled_jobs',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_freelancer_cancelled_jobs'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//completed jobs
			register_rest_route(
				$namespace,
				'/' . $base . '/get_completed_jobs',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_completed_jobs'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//manage proposals
			register_rest_route(
				$namespace,
				'/' . $base . '/manage_proposals',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'manage_proposals'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//manage proposals
			register_rest_route(
				$namespace,
				'/' . $base . '/project_shares',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'project_shares'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//manage ongoing jobs
			register_rest_route(
				$namespace,
				'/' . $base . '/get_ongoing_jobs',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_ongoing_jobs'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//manage ongoing job detail
			register_rest_route(
				$namespace,
				'/' . $base . '/get_ongoing_job_detail',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_ongoing_job_detail'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//manage ongoing job chat
			register_rest_route(
				$namespace,
				'/' . $base . '/get_ongoing_job_chat',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_ongoing_job_chat'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/get_download_chat_attachments',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_download_chat_attachments'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/get_services',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_services'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/update_service_status',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_service_status'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/get_services_by_type',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_services_by_type'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * calculate proposal commission
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function project_shares($request)
		{
			$project_id 		= !empty($request['project_id']) ? $request['project_id'] : 0;
			$proposed_amount 	= !empty($request['proposed_amount']) ? $request['proposed_amount'] : 0;
			$hourly_rate 		= !empty($request['hourly_rate']) ? $request['hourly_rate'] : '';
			$hours 				= !empty($request['hours']) ? $request['hours'] : '';

			if (!empty($project_id)) {
				if (!empty($proposed_amount && $proposed_amount > 0)) {
					$settings	= array();
					if (empty($project_id) || empty($proposed_amount)) {
						$settings['admin_shares'] 		= 0.0;
						$settings['freelancer_shares'] 	= 0.0;
					} else {
						$settings	= workreap_commission_fee($proposed_amount, 'projects', $project_id);
					}
					$items['project_shares'] = maybe_unserialize($settings);
					return new WP_REST_Response($items, 200);
				} elseif (!empty($hourly_rate && !empty($hours))) {

					$settings	= array();
					if (empty($project_id) || empty($hourly_rate)) {
						$settings['admin_shares'] 		= 0.0;
						$settings['freelancer_shares'] 	= 0.0;
					} else {
						$settings	= workreap_commission_fee($hourly_rate, 'projects', $project_id);
					}
					$items['project_shares'] = maybe_unserialize($settings);
					return new WP_REST_Response($items, 200);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Project id is missing', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_my_earnings($request)
		{
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 6;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$earnings		= workreap_get_earning_freelancer($user_id, $limit);

			$date_formate		= get_option('date_format');
			$items	= array();
			if ($earnings) {
				$earning_data	= array();
				foreach ($earnings as $earning) {

					$earning_data['project_title']	= !empty($earning->project_id) ? esc_html(get_the_title($earning->project_id)) : "";
					$earning_data['amount']			= !empty($earning->freelancer_amount) ? workreap_price_format($earning->freelancer_amount, 'return') : 0;
					$earning_data['timestamp']		= !empty($earning->process_date) ? date($date_formate, strtotime($earning->process_date)) : '';
					$items[]	= $earning_data;
				}
			}
			$items			    = maybe_unserialize($items);

			return new WP_REST_Response($items, 200);
		}

		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_services($request)
		{
			$limit				= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$page_number		= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$type_search		= !empty($request['type']) ? $request['type'] : '';
			$user_profile_id	= workreap_get_linked_profile_id($user_id);
			$profile_id			= !empty($user_profile_id) ? intval($user_profile_id) : 0;

			/* saved services */
			$saved_services = array();
			if (!empty($profile_id)) {
				$saved_services	= get_post_meta($profile_id, '_saved_services', true);
			}

			$args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'micro-services',
				'orderby' 			=> 'ID',
				'order' 			=> 'DESC',
				'paged' 			=> $page_number,
				'suppress_filters'  => false
			);

			if (!empty($type_search) && $type_search === 'search') {
				$keyword 		= !empty($request['keyword']) ? $request['keyword'] : '';
				$args['s'] = $keyword;
			}

			if (!empty($user_id)) {
				$args['author']  = $user_id;
				$args['post_status']  = array('publish','draft','pending');
			} else {
				$args['post_status']  = array('publish');
			}

			$query 			= new WP_Query($args);
			$result_count	= $query->count();

			$items	= array();
			if ($query->have_posts()) {
				$formate_date	= get_option('date_format');
				$width			= 355;
				$height			= 352;
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					$queu_services		= workreap_get_services_count('services-orders', array('hired'), $post->ID);
					$queu_services		= !empty($queu_services) ? $queu_services : 0;
					$db_videos =  $addons_items = array();

					$db_english_level = $db_downloadable = $db_service_price = $db_address = $db_longitude = $db_latitude = '';
					$service_map = 'off';
					if (function_exists('fw_get_db_post_option')) {
						$db_docs   			= fw_get_db_post_option($post->ID, 'docs');
						$order_details   	= fw_get_db_post_option($post->ID, 'order_details');
						$db_service_price 	= fw_get_db_post_option($post->ID, 'price');
						$db_downloadable   	= fw_get_db_post_option($post->ID, 'downloadable');
						$db_english_level   = fw_get_db_post_option($post->ID, 'english_level');
						$db_address   		= fw_get_db_post_option($post->ID, 'address');
						$db_videos   		= fw_get_db_post_option($post->ID, 'videos');
						$service_map 		= fw_get_db_post_option($post->ID, 'service_map', true);
						$db_longitude   	= fw_get_db_post_option($post->ID, 'longitude');
						$db_latitude   		= fw_get_db_post_option($post->ID, 'latitude');
					}

					$service_faq_option = $services_categories = $remove_service_languages = 'no';

					if (function_exists('fw_get_db_settings_option')) {
						$service_faq_option			= fw_get_db_settings_option('service_faq_option');
						$services_categories		= fw_get_db_settings_option('services_categories');
						$remove_service_languages	= fw_get_db_settings_option('remove_service_languages');
					}

					/* saved services */
					$favorit = 'no';
					if (!empty($saved_services)  &&  in_array($post->ID, $saved_services)) {
						$favorit = 'yes';
					}

					$service_url		= get_the_permalink($post->ID);
					$service_url		= !empty($service_url) ? esc_url($service_url) : '';
					$db_addons			= get_post_meta($post->ID, '_addons', true);
					$db_addons			= !empty($db_addons) ? $db_addons : array();

					if (!empty($db_addons)) {
						foreach ($db_addons as $addon) {
							$service_title		= get_the_title($addon);
							$service_title		= !empty($service_title) ? $service_title : '';
							$db_price			= 0;
							if (function_exists('fw_get_db_post_option')) {
								$db_price   = fw_get_db_post_option($addon, 'price');
							}

							$db_price			= !empty($db_price) ?  html_entity_decode(workreap_price_format($db_price, 'return')) : '';
							$post_status		= get_post_status($addon);
							$post_status		= !empty($post_status) ? $post_status : '';
							$addon_excerpt		= get_the_excerpt($addon);
							$addon_excerpt		= !empty($addon_excerpt) ? $addon_excerpt : '';
							$addon_ID			= !empty($addon) ? $addon : '';
							$item[] = array(
								'ID' => $addon_ID,
								'title' 		=> $service_title,
								'price' 		=> $db_price,
								'status' 		=> $post_status,
								'description' 	=> $addon_excerpt,
							);
						}
						$addons_items		= maybe_unserialize($item);
					}

					$auther_id				= get_post_field('post_author', $post->ID);
					$auther_profile_id		= !empty($auther_id) ? workreap_get_linked_profile_id($auther_id) : '';
					$auther_title			= get_the_title($auther_profile_id);
					$auther_title			= !empty($auther_title) ? $auther_title : '';

					/* author avatar */
					$freelancer_avatar = apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $auther_profile_id),
						array('width' => 100, 'height' => 100)
					);
					$auther_image	= !empty($freelancer_avatar) ? esc_url($freelancer_avatar) : '';

					/* is author verified */
					$auther_verivifed		= get_post_meta($auther_profile_id, "_is_verified", true);
					$auther_verivifed		= !empty($auther_verivifed) ? esc_attr($auther_verivifed) : '';

					/* created date */
					$created_date		= get_the_date($formate_date, $auther_profile_id);
					$created_date		= !empty($created_date) ? $created_date : '';

					/* post name */
					$post_name		= workreap_get_slug($auther_profile_id);
					$post_name		= !empty($post_name) ? esc_attr($post_name) : '';

					/* service view count */
					$services_views_count   = get_post_meta($post->ID, 'services_views', true);
					$services_views_count	= !empty($services_views_count) ? intval($services_views_count) : 0;

					/* Featured Service */
					$featured_service		= get_post_meta($post->ID, '_featured_service_string', true);
					$featured_service		= !empty($featured_service) ? esc_html__('Featured', 'workreap_api') : '';

					/* project categories */
					$db_project_cat 		= wp_get_post_terms($post->ID, 'service_categories');
					$categories				= !empty($db_project_cat) ? $db_project_cat : array();
					$service_categ			= array();
					if (!empty($categories)) {
						foreach ($categories as $cat) {
							if (!empty($cat->term_id)) {
								$service_categ[]	= array(
									'id' 				=> $cat->term_id,
									'slug' 				=> $cat->slug,
									'category_name' 	=> $cat->name,
								);
							}
						}
					}

					$service_title			= get_the_title($post->ID);
					$service_title			= !empty($service_title) ? esc_html($service_title) : '';
					$service_content		= get_the_content($post->ID);
					$service_content		= !empty($service_content) ?  $service_content : '';
					$serviceTotalRating		= get_post_meta($post->ID, '_service_total_rating', true);
					$serviceFeedbacks		= get_post_meta($post->ID, '_service_feedbacks', true);
					$queu_services			= workreap_get_services_count('services-orders', array('hired'), $post->ID);
					$service_rating			= !empty($serviceTotalRating) ? $serviceTotalRating : 0;
					$service_feedback		= !empty($serviceFeedbacks) ? intval($serviceFeedbacks) : 0;

					if (!empty($serviceTotalRating) || !empty($serviceFeedbacks)) {
						$serviceTotalRating	= $serviceTotalRating / $serviceFeedbacks;
					} else {
						$serviceTotalRating	= 0;
					}
					$service_total_rating 		= number_format((float) $serviceTotalRating, 1);

					$service_faq			= array();
					if (!empty($service_faq_option) && $service_faq_option == 'yes') {
						$faq 					= fw_get_db_post_option($post->ID, 'faq');
						$service_faq			= !empty($faq) ? $faq : array();
					}

					$db_docs						= !empty($db_docs) ? $db_docs : array();
					$service_price					= !empty($db_service_price) ? $db_service_price : '';
					$service_downloadable			= !empty($db_downloadable) ? $db_downloadable : 'no';
					$service_formated_price			= !empty($db_service_price) ? html_entity_decode(workreap_price_format($db_service_price, 'return')) : '';

					/* delivery & response time */
					$db_delivery_time 		= wp_get_post_terms($post->ID, 'delivery');
					$service_delivery_time = array();
					if (!empty($db_delivery_time)) {
						foreach ($db_delivery_time as $key => $delivery_time_obj) {
							$service_delivery_time[] = array(
								'id' 		=> $delivery_time_obj->term_id,
								'name' 		=> $delivery_time_obj->name,
								'slug' 		=> $delivery_time_obj->slug,
							);
						}
					}

					/* response time */
					$db_response_time 		= wp_get_post_terms($post->ID, 'response_time');
					$service_response_time = array();
					if (!empty($db_response_time)) {
						foreach ($db_response_time as $key => $response_time_obj) {
							$service_response_time[] = array(
								'id' 		=> $response_time_obj->term_id,
								'name' 		=> $response_time_obj->name,
								'slug' 		=> $response_time_obj->slug,
							);
						}
					}

					/* Qued services */
					$queu_services			= workreap_get_services_count('services-orders', array('hired'), $post->ID);
					$service_queue			= !empty($queu_services) ? $queu_services : 0;

					/* is completed service */
					$completed_services		= workreap_get_services_count('services-orders', array('completed'), $post->ID);
					$service_sold			= !empty($completed_services) ? $completed_services : 0;

					/* english level */
					$english_level = array();
					if (!empty($remove_service_languages) && $remove_service_languages === 'no') {
						if (!empty($db_english_level)) {
							$english_obj = get_term_by('slug', $db_english_level, 'english_level');
							$english_level[] = array(
								'id' 		=> $english_obj->term_id,
								'name' 		=> $english_obj->name,
								'slug' 		=> $english_obj->slug,
							);
						}
					}

					/* languages speak */
					$lang_speak_arr = array();
					$lang_speak 	= wp_get_post_terms($post->ID, 'languages', array());
					if (!empty($lang_speak)) {
						foreach ($lang_speak as $speak_val) {
							$lang_speak_arr[] = array(
								'id' => $speak_val->term_id,
								'name' => $speak_val->name,
								'slug' => $speak_val->slug,
							);
						}
					}

					/* service images */
					$service_images	= array();
					if (!empty($db_docs)) {
						$docs_count	= 0;
						foreach ($db_docs as $key => $doc) {
							$docs_count++;
							$attachment_id		= !empty($doc['attachment_id']) ? $doc['attachment_id'] : '';
							$image_url			= workreap_prepare_image_source($attachment_id, $width, $height);
							// $file_detail  		= Workreap_file_permission::getDecrpytFile($doc);
							// $name        		= $file_detail['filename'];
							$name        		= !empty($doc['name']) ? $doc['name'] : get_the_title($doc['attachment_id']);


							$service_images[] = array(
								'attachment_id' => (int)$attachment_id,
								'url' 			=> !empty($image_url) ? esc_url($image_url) : '',
								'name' 			=> $name
							);
						}
					}

					/* downloadable files */
					$dwonload_files = get_post_meta($post->ID, '_downloadable_files', true);
					$dwonload_files = !empty($dwonload_files) ? $dwonload_files : array();
					$download_files_arr = array();
					if (!empty($dwonload_files)) {
						foreach ($dwonload_files as $key => $file_obj) {
							$files_detail  		= Workreap_file_permission::getDecrpytFile($file_obj);
							$file_name        		= $files_detail['filename'];
							$download_files_arr[] = array(
								'attachment_id' 		=> (int)$file_obj['attachment_id'],
								'url' 					=> esc_url($file_obj['url']),
								'name' 					=> $file_name,
							);
						}
					}

					//Services Reviews
					$service_id		= $post->ID;
					$reviews		= array();
					$args_reviews	= array(
						'posts_per_page' 	=> -1,
						'post_type' 		=> 'services-orders',
						'post_status' 		=> array('completed'),
						'suppress_filters' 	=> false
					);
					$meta_query_args_reviews[] = array(
						'key' 		=> '_service_id',
						'value' 	=> $service_id,
						'compare' 	=> '='
					);
					$query_relation 			= array('relation' => 'AND',);
					$args_reviews['meta_query'] = array_merge($query_relation, $meta_query_args_reviews);
					$query_reviews 	= new WP_Query($args_reviews);

					$count	= 0;

					if ($query_reviews->have_posts()) {
						while ($query_reviews->have_posts()) : $query_reviews->the_post();
							global $post;
							$count++;

							$author_id 			= get_the_author_meta('ID');
							$linked_profile 	= workreap_get_linked_profile_id($author_id);
							$tagline			= workreap_get_tagline($linked_profile);
							$employer_title 	= get_the_title($linked_profile);
							$employer_avatar 	= apply_filters(
								'workreap_employer_avatar_fallback',
								workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile),
								array('width' => 100, 'height' => 100)
							);
							$service_ratings	= get_post_meta($post->ID, '_hired_service_rating', true);
							if (function_exists('fw_get_db_post_option')) {
								$feedback	 		= fw_get_db_post_option($post->ID, 'feedback');
							}
							$reviews[$count]['feedback']		= !empty($feedback) ? $feedback : '';
							$reviews[$count]['employer_title']	= !empty($employer_title) ? $employer_title : '';
							$reviews[$count]['employer_avatar']	= !empty($employer_avatar) ? esc_url($employer_avatar) : '';

							$verivifed							= get_post_meta($linked_profile, "_is_verified", true);
							$reviews[$count]['_is_verified']	= !empty($verivifed) ? $verivifed : '';

							$service_loaction					= workreap_get_location($linked_profile);
							$reviews[$count]['location']		= !empty($service_loaction) ? $service_loaction : array();

							$reviews[$count]['service_rating']	= !empty($service_ratings) ? $service_ratings : '';
						endwhile;
						wp_reset_postdata();
					}
					$service_count_totals	= !empty($count_post) ? intval($count_post) : 0;

					/* feature image */
					$feature_image	= get_the_post_thumbnail_url($post->ID, 'workreap_service_thumnail');
					$feature_image	= !empty($feature_image) ? esc_url($feature_image) : '';

					/* is featured */
					$is_featured			= apply_filters('workreap_service_print_featured', $post->ID, 'yes');
					$service_is_featured	= !empty($is_featured) ? 'yes' : 'no';

					/* download-able */
					$service_downloadable	= get_post_meta($post->ID, '_downloadable', true);
					$service_downloadable	= 	(!empty($service_downloadable) && $service_downloadable == 'yes') ? 'yes' : 'no';

					/* country */
					$service_counry = array();
					$service_counry_slug = get_post_meta($post->ID, '_country', true);
					$service_counry_slug = !empty($service_counry_slug) ? $service_counry_slug : '';
					if (!empty($service_counry_slug)) {
						$service_counry_obj = get_term_by('slug', $service_counry_slug, 'locations');
						$service_counry[] = array(
							'id' => $service_counry_obj->term_id,
							'name' => $service_counry_obj->name,
							'slug' => $service_counry_obj->slug,
						);
					}

					$items[]	= array(
						'ID' 				=> $post->ID,
						'service_id' 		=> $post->ID,
						'title' 			=> $service_title,
						'content' 			=> $service_content,
						'user_id' 			=> (int)$auther_id,
						'queu_services' 	=> $queu_services,
						'post_status' 		=> get_post_status($post->ID),
						'favorit' 			=> $favorit,
						'service_url' 		=> $service_url,
						'addons' 			=> $addons_items,
						'auther_title' 		=> $auther_title,
						'profile_id' 		=> $auther_profile_id,
						'auther_image' 		=> $auther_image,
						'auther_verified' 	=> $auther_verivifed,
						'auther_date' 		=> $created_date,
						'auther_slug' 		=> $post_name,
						'service_views' 	=> $services_views_count,
						'categories' 		=> $service_categ,
						'rating' 			=> $service_rating,
						'feedback' 			=> $service_feedback,
						'total_rating' 		=> $service_total_rating,
						'faq' 				=> $service_faq,
						'downloadable' 		=> $service_downloadable,
						'price' 			=> $service_price,
						'formated_price' 	=> $service_formated_price,
						'delivery_time' 	=> $service_delivery_time,
						'response_time' 	=> $service_response_time,
						'queue' 			=> $service_queue,
						'sold' 				=> $service_sold,
						'english_level' 	=> $english_level,
						'speak_languages' 	=> $lang_speak_arr,
						'images' 			=> $service_images,
						'files' 			=> $download_files_arr,
						'reviews' 			=> array_values($reviews),
						'count_totals' 		=> $service_count_totals,
						'country' 			=> $service_counry,
						'address' 			=> $db_address,
						'videos' 			=> $db_videos,
						'show_map' 			=> $service_map,
						'featured_img' 		=> $feature_image,
						'is_featured' 		=> $service_is_featured,
						'featured_text' 	=> $featured_service,
						'longitude' 		=> $db_longitude,
						'latitude' 			=> $db_latitude,
					);
				}

				wp_reset_postdata();
				$json['type']		= 'success';
				$json['count']		= $result_count;
				$json['services'] 	= $items;
				return new WP_REST_Response(maybe_unserialize($json), 200);
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Sorry no record found!', 'workreap_api');
				$json['services'] 	= array();
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_service_status($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$service_id	= !empty($request['post_id']) ? intval($request['post_id']) : '';
			$status		= !empty($request['status']) ? esc_html($request['status']) : '';
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';

			$json = array();

			$required = array(
				'post_id'	=> esc_html__('Post ID is required', 'workreap_api'),
				'status'  	=> esc_html__('Post status is required', 'workreap_api'),
				'user_id'  	=> esc_html__('User ID is required', 'workreap_api')
			);

			foreach ($required as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				}
			}

			$update_post	= array();
			$update			= workreap_save_service_status($service_id, $status);
			if ($update) {
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Successfully! updated the post status.', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Service status is not updated.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_services_by_type($data)
		{
			$json					= array();
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);

			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 6;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$type			= !empty($request['type']) ? ($request['type']) : '';
			$keyword		= !empty($request['keyword']) ? $request['keyword'] : '';
			$user_type		= apply_filters('workreap_get_user_type', $user_id);
			if (function_exists('fw_get_db_settings_option')) {
				$default_service_banner = fw_get_db_settings_option('default_service_banner');
			}
			if ($type === 'completed') {
				$post_status	= array('completed');
			} else if ($type === 'hired') {
				$post_status	= array('hired');
			} else if ($type === 'cancelled') {
				$post_status	= array('cancelled');
			}

			$order 		= 'DESC';
			$sorting 	= 'ID';

			$meta_query_args =  array();

			if (!empty($user_type) && $user_type == 'freelancer') {
				$args = array(
					'posts_per_page' 	=> $limit,
					'post_type' 		=> 'services-orders',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> $post_status,
					'paged' 			=> $page_number,
					'suppress_filters' 	=> false,
					's'					=> $keyword
				);

				$meta_query_args[] = array(
					'key' 		=> '_service_author',
					'value' 	=> $user_id,
					'compare' 	=> '='
				);

				$query_relation 	= array('relation' => 'AND',);
				$args['meta_query'] = array_merge($query_relation, $meta_query_args);
			} else {
				$args	= array(
					'posts_per_page' 	=> $limit,
					'post_type' 		=> 'services-orders',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> $post_status,
					'author' 			=> $user_id,
					'paged' 			=> $page_number,
					'suppress_filters' 	=> false,
					's'                 => $keyword
				);
			}
			$query 	= new WP_Query($args);
			$total 	= $query->found_posts;
			$items	= array();
			if ($query->have_posts()) {
				$service_data	= array();
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$db_docs				= array();
					$service_id				= get_post_meta($post->ID, '_service_id', true);
					$db_docs   				= fw_get_db_post_option($service_id, 'docs');
					$employer_id			= get_post_field('post_author', $post->ID);
					$service_addons			= get_post_meta($post->ID, '_addons', true);
					$service_downloadable	= get_post_meta($service_id, '_downloadable', true);

					$db_price		= 0;
					$addon_total	= 0;
					$width			= 100;
					$height			= 100;

					$full_width			= 714;
					$full_height		= 410;

					if (empty($db_docs) && !empty($default_service_banner)) {
						$db_docs[0]	= $default_service_banner;
					}

					if (function_exists('fw_get_db_post_option')) {
						$db_price   = fw_get_db_post_option($service_id, 'price');
					}

					$addon_array		= array();
					if (!empty($service_addons)) {
						$service_addons_array	= array();
						foreach ($service_addons as $key => $addon) {
							$db_addon_price			= 0;
							if (!empty($addon['id']) && is_array($addon)) {
								$db_addon_price	= $addon['price'];
								$addon			= $addon['id'];
							} else {
								if (function_exists('fw_get_db_post_option')) {
									$db_addon_price   = fw_get_db_post_option($addon, 'price');
								}
							}
							$addon_total	= $db_addon_price + $addon_total;
							$service_addons_array['title']	= get_the_title($addon);
							$service_addons_array['detail']	= get_the_excerpt($addon);
							$service_addons_array['price']	= html_entity_decode(workreap_price_format($db_addon_price, 'return'));
							$addon_array[]					= $service_addons_array;
						}
					}
					//images slider
					$images_slider	= array();
					if (!empty($db_docs)) {
						$total_count = 0;
						foreach ($db_docs as $key => $doc) {
							$total_count++;
							$attachment_id	= !empty($doc['attachment_id']) ? $doc['attachment_id'] : '';
							$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
							$full_pic 		= workreap_prepare_image_source($attachment_id, 'full', 'full');
							if (strpos($thumbnail, 'media/default.png') === false) {
								if (!empty($full_pic) && !empty($thumbnail)) {
									$images_slider[$total_count]['thumbnail'] = $thumbnail;
									$images_slider[$total_count]['full'] 	  = $full_pic;
								}
							}
						}
					}

					$post_comment_id	= $post->ID;
					$args 		= array('post_id' => $post_comment_id);
					$comments	= get_comments($args);

					if (!empty($post_comment_id) && !empty($comments)) {
						$counter = 0;
						$comment_history	= $history = array();
						foreach ($comments as $key => $value) {
							$counter++;
							$date 			= !empty($value->comment_date) ? $value->comment_date : '';
							$user_id 		= !empty($value->user_id) ? $value->user_id : '';
							$comments_ID 	= !empty($value->comment_ID) ? $value->comment_ID : '';
							$message 		= $value->comment_content;
							$date 			= !empty($date) ? date('F j, Y', strtotime($date)) : '';

							if (apply_filters('workreap_get_user_type', $user_id) === 'employer') {
								$employer_post_id   		= workreap_get_linked_profile_id($user_id);
								$avatar = apply_filters(
									'workreap_employer_avatar_fallback',
									workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_post_id),
									array('width' => 100, 'height' => 100)
								);
							} else {
								$freelancer_post_id   		= workreap_get_linked_profile_id($user_id);
								$avatar = apply_filters(
									'workreap_freelancer_avatar_fallback',
									workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_post_id),
									array('width' => 100, 'height' => 100)
								);
							}

							$username 		= workreap_get_username($user_id);
							$project_files  = get_comment_meta($value->comment_ID, 'message_files', true);

							$comment_history['ID'] 			    = $comments_ID;
							$comment_history['sender_image'] 	= $avatar;
							$comment_history['date_sent'] 		= $date;
							$comment_history['message'] 		= $message;

							$data	= !empty($project_files) ? array_values($project_files) : array();
							if (!empty($data)) {
								if (class_exists('ZipArchive')) {
									$zip                = new ZipArchive();
									$uploadspath        = wp_upload_dir();
									$folderRalativePath = $uploadspath['baseurl'] . "/downloads";
									$folderAbsolutePath = $uploadspath['basedir'] . "/downloads";
									wp_mkdir_p($folderAbsolutePath);
									$filename    = 'comment_attachment-' . $comments_ID . '-' . round(microtime(true)) . '.zip';
									$zip_name   = $folderAbsolutePath . '/' . $filename;
									$zip->open($zip_name,  ZipArchive::CREATE);
									$download_url    = $folderRalativePath . '/' . $filename;

									foreach ($data as $key => $value) {
										$file_url   = $value['url'];
										$response   = wp_remote_get($file_url);
										$filedata   = wp_remote_retrieve_body($response);
										$zip->addFromString(basename($file_url), $filedata);
									}
									$zip->close();
									$comment_history['download_url'] 			= $download_url;
								} else {
									$json['type']           = 'error';
									$json['message']        = esc_html__('Oops', 'peer-review-system');
									$json['message_desc']   = esc_html__('Zip library is not installed on the server, please contact to hosting provider', 'peer-review-system');
									return new WP_REST_Response($json, 203);
								}
							}
							$history[]		 = $comment_history;
							$comment_history = array();
						}
						$service_data['history']	= $history;
					}

					$order_total						= $db_price + $addon_total;
					$service_price						= workreap_price_format($db_price, 'return');
					$service_data['addons']				= $addon_array;
					$service_data['service_assets']		= !empty($images_slider) ? array_values($images_slider) : array();
					$service_data['order_id']			= $post->ID;
					$profile_id							= workreap_get_linked_profile_id($employer_id);
					$service_data['employer']['employer_title']		= get_the_title($profile_id);
					$service_data['employer']['employertagline']	= workreap_get_tagline($profile_id);
					$service_data['employer']['employer_avatar'] 	= apply_filters(
						'workreap_employer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $profile_id),
						array('width' => 100, 'height' => 100)
					);
					$service_data['employer']['employer_verified'] 	= get_post_meta($profile_id, '_is_verified', true);
					$service_data['ID']					= $service_id;
					if (function_exists('fw_get_db_post_option')) {
						$db_docs   	= fw_get_db_post_option($service_id, 'docs');
					}
					// freelancer_detail
					$employer_id		= get_post_field('post_author', $service_id);
					$freelancer_id		= workreap_get_linked_profile_id($employer_id);

					$reviews_rate = $total_rating = 0;
					if (!empty($freelancer_id)) {
						$reviews_data 	= get_post_meta($freelancer_id, 'review_data');
						$reviews_rate	= !empty($reviews_data[0]['wt_average_rating']) ? floatval($reviews_data[0]['wt_average_rating']) : 0;
						$total_rating	= !empty($reviews_data[0]['wt_total_rating']) ? intval($reviews_data[0]['wt_total_rating']) : 0;
					}

					$round_rate 		= $reviews_rate;
					$rating_average		= ($round_rate / 5) * 100;

					$service_data['service']['freelancer_title']	= get_the_title($freelancer_id);
					$service_data['service']['freelancertagline']	= workreap_get_tagline($freelancer_id);
					$service_data['service']['freelancer_avatar'] 	= apply_filters(
						'workreap_employer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_id),
						array('width' => 100, 'height' => 100)
					);
					$service_data['service']['freelancer_verified'] 	= get_post_meta($freelancer_id, '_is_verified', true);

					$service_data['service']['rating_average']	= $rating_average;
					$service_data['service']['reviews_rate']	= $reviews_rate;
					$service_data['service']['total_rating']	= $total_rating;

					$service_data['service_title']	= get_the_title($service_id);
					$service_data['service_downloadable']	= $service_downloadable;
					$service_data['featured_img']	= get_the_post_thumbnail_url($service_id, 'workreap_service_thumnail');
					$is_featured					= apply_filters('workreap_service_print_featured', $service_id, 'yes');

					$is_featured = 'no';
					if (!empty($is_featured) && $is_featured === 'wt-featured') {
						$is_featured = 'yes';
					}

					$service_data['is_featured']		= $is_featured;
					$avg_rating		= array();

					if ($type === 'completed') {
						$feedback	 		= '';
						if (function_exists('fw_get_db_post_option')) {
							$feedback   = fw_get_db_post_option($post->ID, 'feedback');
						}

						$service_data['feedback']	= $feedback;

						$service_ratings	= get_post_meta($post->ID, '_hired_service_rating', true);
						$service_ratings	= !empty($service_ratings) ? $service_ratings : 0;
						$service_data['service_ratings']	= $service_ratings;
						$rating_headings 	= workreap_project_ratings('services_ratings');

						if (!empty($rating_headings)) {
							$rating_array		= array();
							foreach ($rating_headings  as $key => $item) {
								$saved_projects     = get_post_meta($post->ID, $key, true);
								if (!empty($saved_projects)) {
									$percentage				= $saved_projects;
									$rating_array['title']	= $item;
									$rating_array['score']	= $percentage;
									$avg_rating[]			= $rating_array;
								}
							}
						}
					} else if ($type === 'cancelled') {
						$feedback	 				= fw_get_db_post_option($post->ID, 'feedback');
						$service_data['feedback']	= !empty($feedback) ? $feedback : '';
					}

					$service_data['rating_data']		= $avg_rating;
					$service_data['price']				= html_entity_decode(workreap_price_format($db_price, 'return'));
					$service_data['order_total']		= html_entity_decode(workreap_price_format($order_total, 'return'));
					$items[]	= $service_data;

				endwhile;
				wp_reset_postdata();
				$items			    = maybe_unserialize($items);
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Services list', 'workreap_api');
				$json['listing'] 	= $items;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No record found!', 'workreap_api');
				$json['listing'] 	= array();
				return new WP_REST_Response($json, 203);
			}
		}


		/**
		 * Get Ongoing jobs
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_ongoing_jobs($request)
		{
			$limit			= !empty($request['limit']) ? intval($request['limit']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$keyword		= !empty($request['keyword']) ? esc_html($request['keyword']) : '';

			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}

			$milestone	= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
			$meta_query_args = array();
			$order 	 = 'DESC';
			$sorting = 'ID';
			$query_args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'projects',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('hired'),
				'paged' 			=> $page_number,
				'suppress_filters' 	=> false,
				's'					=> $keyword
			);

			$post_id = workreap_get_linked_profile_id($user_id);
			$meta_query_args[] = array(
				'key' 		=> '_freelancer_id',
				'value' 	=> $post_id,
				'compare' 	=> '='
			);

			$query_relation 	= array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($query_args);
			$count_post 		= $query->found_posts;
			$item		= array();

			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					$author_id 		= get_the_author_meta('ID');
					$linked_profile = workreap_get_linked_profile_id($author_id);
					$employer_title = esc_html(get_the_title($linked_profile));
					$milestone_option	= 'off';

					if (!empty($milestone) && $milestone === 'enable') {
						$milestone_option	= get_post_meta($post->ID, '_milestone', true);
					}

					$proposal_id		= get_post_meta($post->ID, '_proposal_id', true);
					$post_status		= get_post_field('post_status', $post->ID);
					$post_status		= !empty($post_status) ? esc_html($post_status) : '';

					$project_type   	= fw_get_db_post_option($post->ID, 'project_type', true);
					$project_type   	= !empty($project_type['gadget']) ? $project_type['gadget'] : '';
					$job_type			= $project_type;
					$project_type		= (isset($project_type) && $project_type == 'hourly') ?  esc_html__('Hourly', 'workreap_api') : esc_html__('Fixed Price', 'workreap_api');


					$is_verified 	= get_post_meta($linked_profile, '_is_verified', true);
					$title			= $employer_title;
					if (function_exists('workreap_get_username')) {
						$title	= workreap_get_username('', $linked_profile);
					}

					$employer_avatar 				= apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile),
						array('width' => 100, 'height' => 100)
					);

					$employer_verified		= 'no';
					if (!empty($is_verified) && $is_verified === 'yes') {
						$employer_verified		= 'yes';
					}


					//project level
					$project_level = '';
					if (function_exists('fw_get_db_post_option')) {
						$project_level          = fw_get_db_post_option($post->ID, 'project_level', true);
					}

					$project_level	= workreap_get_project_level($project_level);
					//$item['type']	   		= $project_type;

					/* Location */
					$location_arr = array();
					if (!empty($post->ID)) {
						$args = array();
						if (taxonomy_exists('locations')) {
							$terms = wp_get_post_terms($post->ID, 'locations', $args);
							if (!empty($terms)) {
								foreach ($terms as $key => $term) {
									$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
									$location_arr[] = array(
										'location_name' => !empty($term->name) ? $term->name : '',
										'location_flag' => !empty($country['url']) ? workreap_add_http($country['url']) : '',
									);
								}
							}
						}
					}

					/* proposal documents */
					$proposal_docs	= $attachments = array();
					$allow_proposal_edit	= 'no';
					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs 			= fw_get_db_post_option($proposal_id, 'proposal_docs');
						$allow_proposal_edit    = fw_get_db_settings_option('allow_proposal_edit');
					}

					if (!empty($proposal_docs) && is_array($proposal_docs)) {
						foreach ($proposal_docs as $key => $attachment) {
							$file_detail    = Workreap_file_permission::getDecrpytFile($attachment);
							$name           = $file_detail['filename'];
							$attachments[] = array(
								'attachment_id' => $attachment['attachment_id'],
								'name' 			=> $name,
								'url' 			=> $attachment['url'],
								'size' 			=> filesize(get_attached_file($attachment['attachment_id'])),
							);
						}
					}

					$proposed_amount  	= get_post_meta($proposal_id, '_amount', true);
					$proposed_amount  	= !empty($proposed_amount) ? workreap_price_format($proposed_amount, 'return') : 0;

					/* proposal duration */
					$duration_list		= worktic_job_duration_list();
					$proposed_duration  = get_post_meta($proposal_id, '_proposed_duration', true);
					$duration			= !empty($duration_list[$proposed_duration]) ? $duration_list[$proposed_duration] : array();
					if (!empty($duration)) {
						$duration	= array(
							'key' 		=> $proposed_duration,
							'value' 	=> $duration
						);
					}

					/* Project History */
					$history = apply_filters('workreap_api_project_history', $proposal_id);

					$item[] = array(
						'ID' 						=> $post->ID,
						'proposal_id' 				=> intval($proposal_id),
						'title' 					=> get_the_title($post->ID),
						'milestone_option' 			=> $milestone_option,
						'project_type' 				=> fw_get_db_post_option($post->ID, 'project_type', true),
						'job_type' 					=> $job_type,
						'project_type' 				=> $project_type,
						'employer_avatar' 			=> $employer_avatar,
						'employer_verified' 		=> $employer_verified,
						'employer_name' 			=> $title,
						'project_level' 			=> $project_level,
						'project_history' 			=> $history,
						'location' 					=> $location_arr,
						'duration' 					=> $duration,
						'budget_dollar' 			=> $proposed_amount,
						'proposal_documents_urls' 	=> $attachments,
						'proposal_documents_count' 	=> !empty($proposal_docs) && is_array($proposal_docs) ?  count($proposal_docs) : 0,
					);
				}
				wp_reset_postdata();
			}

			return new WP_REST_Response($item, 200);
		}

		/**
		 * Get ongoing job detail
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_ongoing_job_detail($request)
		{
			$limit			= !empty($request['limit']) ? intval($request['limit']) : 10;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$project_id		= !empty($request['project_id']) ? intval($request['project_id']) : '';
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$offset 		= ($page_number - 1) * $limit;

			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone         = fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}

			$json = $items = $item		= array();
			$milestone			= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
			$author_id  		= get_post_field('post_author', $project_id);
			$linked_profile 	= workreap_get_linked_profile_id($author_id);
			$employer_title 	= esc_html(get_the_title($linked_profile));

			$proposal_id	= get_post_meta($project_id, '_proposal_id', true);

			$item['ID']	    		= $project_id;
			$item['proposal_id']	= $proposal_id;
			$item['freelance_id']	= $user_id;
			$item['title']		= get_the_title($project_id);

			$is_verified 	= get_post_meta($linked_profile, '_is_verified', true);
			$title			= $employer_title;
			if (function_exists('workreap_get_username')) {
				$title	= workreap_get_username('', $linked_profile);
			}

			$item['employer_avatar'] 				= apply_filters(
				'workreap_freelancer_avatar_fallback',
				workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile),
				array('width' => 100, 'height' => 100)
			);


			$item['employer_verified']		= 'no';
			if (!empty($is_verified) && $is_verified === 'yes') {
				$item['employer_verified']		= 'yes';
			}

			$item['employer_name']		= $title;

			//project level
			$project_level = '';
			if (function_exists('fw_get_db_post_option')) {
				$project_level          = fw_get_db_post_option($project_id, 'project_level', true);
			}

			$item['project_level']		= workreap_get_project_level($project_level);

			//Location
			$item['location_name']		= '';
			$item['location_flag']		= '';
			if (!empty($project_id)) {
				$args = array();
				if (taxonomy_exists('locations')) {
					$terms = wp_get_post_terms($project_id, 'locations', $args);
					if (!empty($terms)) {
						foreach ($terms as $key => $term) {
							$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
							$item['location_name']		= !empty($term->name) ? $term->name : '';;
							$item['location_flag']		= !empty($country['url']) ? workreap_add_http($country['url']) : '';;
						}
					}
				}
			}

			$items[]	= $item;

			$items			    = maybe_unserialize($items);

			return new WP_REST_Response($items, 200);
		}


		/**
		 * Get ongoing job chat history
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_download_chat_attachments($request)
		{
			$attachment_id		= !empty($request['comment_id']) ? intval($request['comment_id']) : 10;

			$item		= $items = array();
			$item['attachment'] = '';

			if (!empty($attachment_id)) {

				$project_files = get_comment_meta($attachment_id, 'message_files', true);
				if (!empty($project_files)) {
					if (class_exists('ZipArchive')) {
						$zip = new ZipArchive();
						$uploadspath	= wp_upload_dir();
						$folderRalativePath = $uploadspath['baseurl'] . "/downloades";
						$folderAbsolutePath = $uploadspath['basedir'] . "/downloades";
						wp_mkdir_p($folderAbsolutePath);
						$filename	= round(microtime(true)) . '.zip';
						$zip_name = $folderAbsolutePath . '/' . $filename;
						$zip->open($zip_name,  ZipArchive::CREATE);
						$download_url	= $folderRalativePath . '/' . $filename;

						foreach ($project_files as $key => $value) {
							$file_url	= $value['url'];
							$response	= wp_remote_get($file_url);
							$filedata   = wp_remote_retrieve_body($response);
							$zip->addFromString(basename($file_url), $filedata);
						}
						$zip->close();

						$item['attachment'] = $download_url;
					}
				}
			}

			$items[]			    = maybe_unserialize($item);

			return new WP_REST_Response($items, 200);
		}

		/**
		 * Get ongoing job chat history
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_ongoing_job_chat($request)
		{
			$user_identity 	 	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$linked_profile  	= workreap_get_linked_profile_id($user_identity);
			$edit_id			= !empty($request['id']) ? intval($request['id']) : 1;
			$post_type			= get_post_type($edit_id);

			$user_type		= apply_filters('workreap_get_user_type', $user_identity);

			if (!empty($post_type) && $post_type === 'services-orders') {
				$employeer_id				= get_post_field('post_author', $edit_id);
				$freelancer_id				= get_post_meta($edit_id, '_service_author', true);

				$service_id					= get_post_meta($edit_id, '_service_id', true);
				$hire_linked_profile		= workreap_get_linked_profile_id($freelancer_id);
				$hired_freelancer_title 	= get_the_title($hire_linked_profile);
				$title						= esc_html__('Service History', 'workreap_api');
				$post_status				= get_post_field('post_status', $edit_id);
				$post_comment_id			= $edit_id;
			} else if (!empty($post_type) && $post_type === 'proposals') {
				$proposal_id		= $edit_id;
				$title				= esc_html__('Project History', 'workreap_api');
				$post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
				$project_id			= get_post_meta($proposal_id, '_project_id', true);
				$post_status		= get_post_field('post_status', $project_id);
			} else {
				$proposal_id		= get_post_meta($edit_id, '_proposal_id', true);
				$title				= esc_html__('Project History', 'workreap_api');
				$post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
				$post_status		= get_post_field('post_status', $edit_id);
			}

			$item		= $items = array();
			$item['ID'] 			= '';
			$item['sender_image'] 	= '';
			$item['date_sent'] 		= '';
			$item['message'] 		= '';
			$item['ID'] 			= '';

			$args 				= array('post_id' => $post_comment_id);
			$comments 			= get_comments($args);

			if (!empty($post_comment_id)) {
				$counter = 0;
				foreach ($comments as $key => $value) {
					$counter++;
					$date 			= !empty($value->comment_date) ? $value->comment_date : '';
					$user_id 		= !empty($value->user_id) ? $value->user_id : '';
					$comments_ID 	= !empty($value->comment_ID) ? $value->comment_ID : '';
					$message 		= $value->comment_content;
					$date 			= !empty($date) ? date('F j, Y', strtotime($date)) : '';

					if (apply_filters('workreap_get_user_type', $user_id) === 'employer') {
						$employer_post_id   		= workreap_get_linked_profile_id($user_id);
						$avatar = apply_filters(
							'workreap_employer_avatar_fallback',
							workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_post_id),
							array('width' => 100, 'height' => 100)
						);
					} else {
						$freelancer_post_id   		= workreap_get_linked_profile_id($user_id);
						$avatar = apply_filters(
							'workreap_freelancer_avatar_fallback',
							workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_post_id),
							array('width' => 100, 'height' => 100)
						);
					}

					$username 		= workreap_get_username($user_id);
					$project_files  = get_comment_meta($value->comment_ID, 'message_files', true);

					$item['ID'] 			= $comments_ID;
					$item['sender_image'] 	= $avatar;
					$item['date_sent'] 		= $date;
					$item['message'] 		= $message;
					$item['files'] 			= !empty($project_files) ? array_values($project_files) : array();
					$items[]			    = $item;
				}
			}

			$items			    = maybe_unserialize($items);

			return new WP_REST_Response($items, 200);
		}

		/**
		 * Get completed jobs
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_completed_jobs($request)
		{
			$limit			= !empty($request['limit']) ? intval($request['limit']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$keyword		= !empty($request['keyword']) ? $request['keyword'] : '';

			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}

			$milestone	= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

			$meta_query_args = $items = array();
			$order 	 = 'DESC';
			$sorting = 'ID';
			$query_args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 	 	=> 'projects',
				'orderby' 		 	=> $sorting,
				'order' 		 	=> $order,
				'post_status' 	 	=> array('completed'),
				'paged'			 	=> $page_number,
				'suppress_filters'  => false,
				's'					=> $keyword
			);

			$post_id	= workreap_get_linked_profile_id($user_id);

			$meta_query_args[] = array(
				'key' 		=> '_freelancer_id',
				'value' 	=> $post_id,
				'compare' 	=> '='
			);

			$query_relation 	= array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($query_args);
			$count_post 		= $query->found_posts;

			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					$author_id 				= get_the_author_meta('ID');
					$linked_profile 		= workreap_get_linked_profile_id($author_id);
					$employer_title 		= esc_html(get_the_title($linked_profile));
					$milestone_option		= 'off';
					$proposal_id			= get_post_meta($post->ID, '_proposal_id', true);

					$project_type    		= fw_get_db_post_option($post->ID, 'project_type');
					$project_type   	= !empty($project_type['gadget']) ? $project_type['gadget'] : '';
					$job_type			= $project_type;
					$project_type		= (isset($project_type) && $project_type == 'hourly') ?  esc_html__('Hourly', 'workreap_api') : esc_html__('Fixed Price', 'workreap_api');

					if (!empty($milestone) && $milestone === 'enable') {
						$milestone_option	= get_post_meta($post->ID, '_milestone', true);
					}

					$project_duration_value   	= '';
					$project_duration   		= fw_get_db_post_option($post->ID, 'project_duration', true);
					$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');

					if (!empty($remove_project_duration) && $remove_project_duration === 'no') {
						$duration_list 			= worktic_job_duration_list();
						$project_duration_value = !empty($duration_list[$project_duration]) ? $duration_list[$project_duration] : '';
					}

					$project_duration	= $project_duration_value;
					$proposal_docs 				= fw_get_db_post_option($post->ID, 'project_documents');
					$attachments				= array();
					if (!empty($proposal_docs)) {
						foreach ($proposal_docs as $file) {
							$attachment_id	= !empty($file['attachment_id']) ? $file['attachment_id'] : '';
							$file_size 		= !empty($file) ? filesize(get_attached_file($attachment_id)) : '';
							$filetype       = !empty($file) ? wp_check_filetype($file['url']) : '';
							$doc_url 		= !empty($file['url']) ? esc_url($file['url']) : '';
							$file_detail  	= Workreap_file_permission::getDecrpytFile($file);
							$name        	= $file_detail['filename'];

							$attachments[] = array(
								'attachment_id' 	=> (int)$attachment_id,
								'name' 				=> $name,
								'size' 				=> $file_size,
								'url' 				=> $doc_url,
								'fileType' 			=> $filetype,
							);
						}
					}

					/* user Name */
					$title	= $employer_title;
					if (function_exists('workreap_get_username')) {
						$title	= workreap_get_username('', $linked_profile);
					}

					/* avatar */
					$employer_avatar = apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile),
						array('width' => 100, 'height' => 100)
					);

					/* is employer verified */
					$is_verified 				= get_post_meta($linked_profile, '_is_verified', true);
					$employer_verified	= 'no';
					if (!empty($is_verified) && $is_verified === 'yes') {
						$employer_verified	= 'yes';
					}

					/* project level */
					$project_level = '';
					if (function_exists('fw_get_db_post_option')) {
						$project_level	= fw_get_db_post_option($post->ID, 'project_level', true);
					}
					$project_level	= workreap_get_project_level($project_level);

					/* Location */
					$location_arr = array();
					if (!empty($post->ID)) {
						$args = array();
						if (taxonomy_exists('locations')) {
							$terms = wp_get_post_terms($post->ID, 'locations', $args);
							if (!empty($terms)) {
								foreach ($terms as $key => $term) {
									$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
									$location_arr[] = array(
										'location_name' => !empty($term->name) ? $term->name : '',
										'location_flag' => !empty($country['url']) ? workreap_add_http($country['url']) : '',
									);
								}
							}
						}
					}

					$proposed_amount  	= get_post_meta($proposal_id, '_amount', true);
					$proposed_amount  	= !empty($proposed_amount) ? workreap_price_format($proposed_amount, 'return') : 0;

					/* proposal duration */
					$duration_list		= worktic_job_duration_list();
					$proposed_duration  = get_post_meta($proposal_id, '_proposed_duration', true);
					$duration			= !empty($duration_list[$proposed_duration]) ? $duration_list[$proposed_duration] : array();
					if (!empty($duration)) {
						$duration	= array(
							'key' 		=> $proposed_duration,
							'value' 	=> $duration
						);
					}

					/* Project History */
					$history = apply_filters('workreap_api_project_history', $proposal_id);

					$items[] = array(
						'ID' 						=> $post->ID,
						'project_type' 				=> $project_type,
						'job_type' 					=> $job_type,
						'project_duration' 			=> $project_duration,
						'proposal_id' 				=> $proposal_id,
						'budget_dollar'				=> $proposed_amount,
						'proposal_documents_urls'	=> $attachments,
						'proposal_documents_count'	=> !empty($proposal_docs) && is_array($proposal_docs) ?  count($proposal_docs) : 0,
						'freelance_id' 				=> $user_id,
						'title' 					=> get_the_title($post->ID),
						'milestone_option' 			=> $milestone_option,
						'employer_avatar' 			=> $employer_avatar,
						'employer_verified' 		=> $employer_verified,
						'employer_name' 			=> $title,
						'project_level' 			=> $project_level,
						'location' 					=> $location_arr,
						'project_history' 			=> $history,
						'duration'					=> $duration
					);
				}
				wp_reset_postdata();
			}

			return new WP_REST_Response($items, 200);
		}

		/**
		 * Get cancelled jobs
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_freelancer_cancelled_jobs($request)
		{
			$limit			= !empty($request['limit']) ? intval($request['limit']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$keyword		= !empty($request['keyword']) ? esc_html($request['keyword']) : '';
			$item			= array();

			$order 			= 'DESC';
			$sorting 		= 'ID';

			$query_args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'proposals',
				'author' 			=> $user_id,
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('cancelled'),
				'paged' 			=> $page_number,
				'suppress_filters' 	=> false,
				's'					=> $keyword
			);

			$pquery = new WP_Query($query_args);
			$count_post = $pquery->found_posts;

			if ($pquery->have_posts()) {
				while ($pquery->have_posts()) {
					$pquery->the_post();
					global $post;
					$project_id 	= get_post_meta($post->ID, '_project_id', true);
					$author_id 		= get_post_field('post_author', $project_id);
					$linked_profile = workreap_get_linked_profile_id($author_id);

					$project_type   = fw_get_db_post_option($project_id, 'project_type', true);
					$project_type   = !empty($project_type['gadget']) ? $project_type['gadget'] : '';
					$job_type		= $project_type;
					$project_type	= isset($project_type) && $project_type == 'hourly' ?  esc_html__('Hourly', 'workreap_api') : esc_html__('Fixed Price', 'workreap_api');

					$milestone_option	= 'off';
					if (!empty($milestone) && $milestone === 'enable') {
						$milestone_option	= get_post_meta($project_id, '_milestone', true);
					}


					$employer_title = esc_html(get_the_title($linked_profile));
					$title			= $employer_title;
					if (function_exists('workreap_get_username')) {
						$title	= workreap_get_username('', $linked_profile);
					}

					/* avatar */
					$employer_avatar 				= apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile),
						array('width' => 100, 'height' => 100)
					);

					/* is verified */
					$is_verified 	= get_post_meta($linked_profile, '_is_verified', true);
					$employer_verified	= 'no';
					if (!empty($is_verified) && $is_verified === 'yes') {
						$employer_verified	= 'yes';
					}

					/* project level */
					$project_level = '';
					if (function_exists('fw_get_db_post_option')) {
						$project_level          = fw_get_db_post_option($project_id, 'project_level', true);
					}
					$project_level		= workreap_get_project_level($project_level);

					/* Location */
					$location_arr = array();
					if (!empty($project_id)) {
						$args = array();
						if (taxonomy_exists('locations')) {
							$terms = wp_get_post_terms($project_id, 'locations', $args);
							if (!empty($terms)) {
								foreach ($terms as $key => $term) {
									$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
									$location_arr[] = array(
										'location_name' => !empty($term->name) ? $term->name : '',
										'location_flag' => !empty($country['url']) ? workreap_add_http($country['url']) : '',
									);
								}
							}
						}
					}

					/* budget */
					$proposed_amount  	= get_post_meta($post->ID, '_amount', true);
					$proposed_amount  	= !empty($proposed_amount) ? workreap_price_format($proposed_amount, 'return') : 0;

					/* proposal duration */
					$duration_list		= worktic_job_duration_list();
					$proposed_duration  = get_post_meta($post->ID, '_proposed_duration', true);
					$duration			= !empty($duration_list[$proposed_duration]) ? $duration_list[$proposed_duration] : array();
					if (!empty($duration)) {
						$duration	= array(
							'key' 		=> $proposed_duration,
							'value' 	=> $duration
						);
					}

					/* Project History */
					$history = apply_filters('workreap_api_project_history', $post->ID);

					$item[] = array(
						'ID' 						=> $project_id,
						'proposal_id' 				=> $post->ID,
						'freelance_id' 				=> $user_id,
						'title' 					=> get_the_title($project_id),
						'milestone_option' 			=> $milestone_option,
						'proposal_id' 				=> $post->ID,
						'employer_avatar' 			=> $employer_avatar,
						'employer_verified' 		=> $employer_verified,
						'employer_name' 			=> $title,
						'job_type' 					=> $job_type,
						'project_type' 				=> $project_type,
						'project_level' 			=> $project_level,
						'location' 					=> $location_arr,
						'project_history' 			=> $history,
						'duration'					=> $duration,
						'budget_dollar'				=> $proposed_amount
					);
				}
				wp_reset_postdata();
			}
			return new WP_REST_Response($item, 200);
		}

		/**
		 * Get my proposals
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_my_proposals($request)
		{
			$limit				= !empty($request['limit']) ? intval($request['limit']) : 10;
			$page_number		= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$search_keyword		= !empty($request['keyword']) ? esc_html($request['keyword']) : '';
			$listing_type		= !empty($request['proposal_type']) ? esc_html($request['proposal_type']) : '';
			$proposal_id		= !empty($request['proposal_id']) ? esc_html($request['proposal_id']) : '';
			$offset 			= ($page_number - 1) * $limit;

			$json = $proposals	= array();
			if ($listing_type === 'single') {
				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'proposals',
					'post__in' 		 	  	=> array($proposal_id),
					'author' 				=> $user_id,
					'post_status' 	 	  	=> array('publish'),
					'ignore_sticky_posts' 	=> 1
				);
				$pquery 			= new WP_Query($query_args);
				$count_post 		= $pquery->found_posts;
			} else {
				$query_args = array(
					'posts_per_page' 	=> $limit,
					'post_type' 		=> 'proposals',
					'orderby' 			=> "ID",
					'order' 			=> 'DESC',
					'post_status' 		=> array('publish'),
					'author' 			=> $user_id,
					'paged' 			=> $page_number,
					'suppress_filters'  => false,
					's'                 => $search_keyword,
				);

				$pquery = new WP_Query($query_args);
				$count_post = $pquery->found_posts;
			}

			if ($pquery->have_posts()) {
				while ($pquery->have_posts()) {
					$pquery->the_post();
					global $post;
					$item		= array();
					$author_id 			= get_the_author_meta('ID');
					$project_id			= get_post_meta($post->ID, '_project_id', true);
					$_proposal_id 		= get_post_meta($project_id, '_proposal_id', true);
					$job_status			= '';
					$proposal_hiring_status	= get_post_meta($post->ID, '_proposal_status', true);
					$proposal_hiring_status	= !empty($proposal_hiring_status) ? $proposal_hiring_status : '';
					$project_status			= get_post_status($project_id);
					$project_type    	= fw_get_db_post_option($project_id, 'project_type');
					$proposed_amount  	= get_post_meta($post->ID, '_amount', true);
					$proposed_amount  	= !empty($proposed_amount) ? $proposed_amount : 0;

					if (!empty($_proposal_id) && (intval($_proposal_id) === $post->ID)) {
						$job_status		= get_post_field('post_status', $project_id);
					} else if (!empty($_proposal_id)) {
						$job_status		= 'cancelled';
					} else {
						$job_status		= 'pending';
					}

					$linked_profile 	= workreap_get_linked_profile_id($author_id);

					$freelancer_avatar = apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 225, 'height' => 225), $linked_profile),
						array('width' => 225, 'height' => 225)
					);

					$pargs	 = array('project_id' => $project_id, 'proposal_id' => $post->ID);
					$submit_proposal  = !empty($submit_proposal) ? add_query_arg($pargs, $submit_proposal) : '';

					$item['ID']	    				= $post->ID;
					$item['project_id']	    		= intval($project_id);
					$item['title']					= get_the_title($post->ID);
					$item['job_title']				= get_the_title($project_id);
					$item['proposal_edit']	    	= 'no';
					$item['proposal_milestone']	    = 'no';
					$item['budget_dollar']	    	= workreap_price_format($proposed_amount, 'return');
					$item['budget']	    			= $proposed_amount;

					/* proposal duration */
					$duration_list		= worktic_job_duration_list();
					$proposed_duration  = get_post_meta($post->ID, '_proposed_duration', true);

					$duration			= !empty($duration_list[$proposed_duration]) ? $duration_list[$proposed_duration] : '';
					$item['duration']	= array(
						'key' 		=> $proposed_duration,
						'value' 	=> $duration
					);

					/* fixed project */
					if (!empty($project_type['gadget']) && $project_type['gadget'] === 'fixed') {
						$proposed_cost			= !empty($proposed_amount) ? $proposed_amount : 0.00;
						$service_fee			= workreap_commission_fee($proposed_cost, 'projects', $project_id);
						$project_cost 			= !empty($project_type['fixed']['project_cost']) ? $project_type['fixed']['project_cost'] : '';
						$max_cost 				= !empty($project_type['fixed']['max_price']) ? $project_type['fixed']['max_price'] : '';
						$item['job_type']		= 'fixed';
						$item['cost']			= $project_cost;
						$item['max_price']		= $max_cost;
					}

					/* hourly project */
					$per_hour_amount 	= 0;
					if (!empty($project_type['gadget']) && $project_type['gadget'] === 'hourly') {
						$proposed_cost		= !empty($per_hour_amount) ? $per_hour_amount : 0.00;
						$per_hour_amount	= get_post_meta($post->ID, '_per_hour_amount', true);
						$service_fee		= workreap_commission_fee($per_hour_amount, 'projects', $project_id);
						$estimeted_time		= get_post_meta($post->ID, '_estimeted_time', true);
						$estimeted_time		= !empty($estimeted_time) ? $estimeted_time : 0;
						$total_amount		= apply_filters('workreap_price_format', $per_hour_amount, 'return');
						$total_amount		= !empty($total_amount) ? $total_amount : 0;
						$item['job_type']	    	= 'hourly';
						$item['per_hour_price']	    = $per_hour_amount;
						$item['estimated_hours']	= $estimeted_time;
					}

					$project_price		= workreap_project_price($project_id);
					if (isset($project_price['type']) && $project_price['type'] === 'hourly') {
						$project_price_val	= !empty($project_price['cost']) ? $project_price['cost'] : 0.0;
						$project_cost	= wp_sprintf(__('%s Per hour rate (for %s hours)', 'workreap_api'), $project_price_val, $estimeted_time);
					} else {
						$project_cost	= !empty($project_price['cost']) ? $project_price['cost'] : 0;
					}
					$item['project_cost'] = $project_cost;

					$service_count			= !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.00;
					$remaining_cost			= !empty($proposed_cost) && !empty($service_count) ? $proposed_cost - $service_count : 0.00;
					$item['service_cost']	= $service_count;
					$item['remaining_cost']	= $remaining_cost;

					if (empty($project_id) || empty($proposed_amount)) {
						$item['shares'] = array(
							'admin_shares' => 0.0,
							'freelancer_shares' => 0.0,
						);
					} else {
						$item['shares']	= workreap_commission_fee($proposed_amount, 'projects', $project_id);
					}

					//cover
					$item['cover']	= '';
					if (!empty($post->ID)) {
						$contents			= nl2br(stripslashes(get_the_content('', true, $post->ID)));
						$item['cover']	    = $contents;
					}

					/* show button for accept milestone and start project (if project is milestone based) */
					$accept_milestone 		= 'hide';
					$project_status			= get_post_status($project_id);
					$proposal_hiring_status	= get_post_meta($post->ID, '_proposal_status', true);
					$proposal_hiring_status	= !empty($proposal_hiring_status) ? $proposal_hiring_status : '';
					if (!empty($proposal_hiring_status)  && $proposal_hiring_status === 'pending' && $project_status === 'publish') {
						$accept_milestone = 'show';
					}
					$item['accept_milestone_btn']	= $accept_milestone;

					/* proposal documents */
					$proposal_docs	= $attachments = array();
					$allow_proposal_edit	= 'no';
					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs 			= fw_get_db_post_option($post->ID, 'proposal_docs');
						$allow_proposal_edit    = fw_get_db_settings_option('allow_proposal_edit');
					}

					if (!empty($proposal_docs) && is_array($proposal_docs)) {
						foreach ($proposal_docs as $key => $attachment) {
							$attachments[] = array(
								'attachment_id' => $attachment['attachment_id'],
								'name' 			=> get_the_title($attachment['attachment_id']),
								'url' 			=> $attachment['url'],
								'size' 			=> filesize(get_attached_file($attachment['attachment_id'])),
							);
						}
					}

					$item['proposal_documents_urls']		= $attachments;
					$item['proposal_documents_count']	  	= !empty($proposal_docs) && is_array($proposal_docs) ?  count($proposal_docs) : 0;

					/* Button status according to job status */
					if ($job_status === 'hired') {
						$item['status']	    = esc_html__('Hired', 'workreap_api');
						$item['status_key']	    = 'hired';
					} elseif ($job_status === 'completed') {
						$item['status']	    = esc_html__('Completed', 'workreap_api');
						$item['status_key']	    = 'completed';
					} else if ($job_status !== 'hired') {
						$item['status']	    = esc_html__('Pending', 'workreap_api');
						$item['status_key']	    = 'pending';
						if (!empty($allow_proposal_edit) && $allow_proposal_edit == 'yes') {
							$item['proposal_edit']	    = 'yes';
						}
						if (!empty($proposal_hiring_status)  && $proposal_hiring_status === 'pending' && $project_status === 'publish') {
							$item['proposal_milestone']	    = 'yes';
						}
					}
					$proposals[]	= $item;
				}
				wp_reset_postdata();

				$json['type']			= 'success';
				$json['count']			= $count_post;
				$json['proposals']		= maybe_unserialize($proposals);
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']			= 'error';
				$json['message']		= esc_html__('No Proposal found', 'workreap_api');
				$json['proposals']		= array();
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get proposal attachment
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function download_attachments($request)
		{
			$job_id	= !empty($request['id']) ? intval($request['id']) : 0;
			$type	= !empty($request['type']) ? $request['type'] : 0;

			$item = $items = array();
			$item['attachment'] = '';

			if (!empty($job_id)) {
				if (function_exists('fw_get_db_post_option')) {
					if (!empty($type) && $type === 'project') {
						$proposal_docs 			= fw_get_db_post_option($job_id, 'project_documents');
					} else {
						$proposal_docs 			= fw_get_db_post_option($job_id, 'proposal_docs');
					}

					if (!empty($proposal_docs)) {
						$zip = new ZipArchive();
						$uploadspath			= wp_upload_dir();
						$folderRalativePath 	= $uploadspath['baseurl'] . "/downloades";
						$folderAbsolutePath 	= $uploadspath['basedir'] . "/downloades";
						wp_mkdir_p($folderAbsolutePath);

						$filename				= round(microtime(true)) . '.zip';
						$zip_name 				= $folderAbsolutePath . '/' . $filename;
						$zip->open($zip_name,  ZipArchive::CREATE);
						$download_url			= $folderRalativePath . '/' . $filename;

						foreach ($proposal_docs as $file) {
							$response			= wp_remote_get($file['url']);
							$filedata   		= wp_remote_retrieve_body($response);
							$zip->addFromString(basename($file['url']), $filedata);
						}
						$zip->close();


						$item['attachment'] = $download_url;
					}
				}
			}

			$items[]	= maybe_unserialize($item);

			return new WP_REST_Response($items, 200);
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidAppGetFreelancersDashbord;
		$controller->register_routes();
	}
);
