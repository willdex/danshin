<?php
if (!class_exists('AndroidAppGetServicesRoutes')) {

	class AndroidAppGetServicesRoutes extends WP_REST_Controller
	{

		/**
		 * Register the routes for the objects of the controller.
		 */
		public function register_routes()
		{
			$version 	= '1';
			$namespace 	= 'api/v' . $version;
			$base 		= 'services';

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
				'/' . $base . '/get_addons_services',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_addons_services'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/update_service',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_service'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/edit_service',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'edit_service'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/add_addon_service',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'add_addon_service'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/delete_addon_service',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'delete_addon_service'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/delete_service',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'delete_service'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * Edit Service
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function edit_service($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$user_profile_id		= workreap_get_linked_profile_id($user_id);
			$user_profile_id			= !empty($user_profile_id) ? intval($user_profile_id) : 0;
			$user_type			= apply_filters('workreap_get_user_type', $user_id);
			$service_id			= !empty($request['service_id']) ? intval($request['service_id']) : '';
			$json				= array();

			if (!empty($user_id) && !empty($service_id)) {
				$item = $adon_item = $service_cat_items = $addons_items = $db_videos = array();
				$db_address = $db_longitude = $db_latitude = '';
				$service_price			= 0;
				$db_downloadable	= $db_hide_map = 'no';

				if (function_exists('fw_get_db_post_option')) {
					$db_docs   			= fw_get_db_post_option($service_id, 'docs');
					$service_price   	= fw_get_db_post_option($service_id, 'price');
					$db_downloadable   	= fw_get_db_post_option($service_id, 'downloadable');
					$db_videos   		= fw_get_db_post_option($service_id, 'videos');
					$db_address   		= fw_get_db_post_option($service_id, 'address');
					$db_longitude   	= fw_get_db_post_option($service_id, 'longitude');
					$db_latitude   		= fw_get_db_post_option($service_id, 'latitude');
				}

				$service_faq_option = $services_categories = 'no';
				if (function_exists('fw_get_db_settings_option')) {
					$db_hide_map 			= fw_get_db_settings_option('hide_map');
					$service_faq_option		= fw_get_db_settings_option('service_faq_option');
					$services_categories	= fw_get_db_settings_option('services_categories');
				}

				$service_title			= get_the_title($service_id);
				$service_content		= get_post_field('post_content', $service_id);
				$service_url			= get_the_permalink($service_id);

				/* ad-ons */
				$db_addons				= get_post_meta($service_id, '_addons', true);
				$db_addons				= !empty($db_addons) ? $db_addons : array();
				$args_addons = array(
					'author'        =>  $user_id,
					'post_type'		=> 	'addons-services',
					'post_status'	=>  'publish',
					'orderby'       =>  'post_date',
					'order'         =>  'ASC',
					'posts_per_page' => -1
				);
				$addons		= get_posts($args_addons);
				if (!empty($addons)) {
					foreach ($addons as $addon) {
						$db_price = 0;
						if (function_exists('fw_get_db_post_option')) {
							$db_price   = fw_get_db_post_option($addon->ID, 'price');
						}
						$checked = '';
						if (in_array($addon->ID, $db_addons)) {
							$checked = 'checked';
						}
						$adon_item['ID']			= !empty($addon->ID) ? $addon->ID : '';
						$addon_title				= get_the_title($addon->ID);
						$adon_item['title']			= !empty($addon_title) ? $addon_title : '';
						$adon_item['description']	= !empty($addon_excerpt) ? $addon_excerpt : '';
						$adon_item['price']			= !empty($db_price) ?  html_entity_decode(workreap_price_format($db_price, 'return')) : '';
						$post_status				= get_post_status($addon->ID);
						$adon_item['status']		= !empty($post_status) ? $post_status : '';
						$addon_excerpt				= get_the_excerpt($addon->ID);
						$adon_item['is_checked']	= $checked;
						$addons_items[]				= maybe_unserialize($adon_item);
					}
				}

				$auther_id				= get_post_field('post_author', $service_id);
				$auther_profile_id		= !empty($auther_id) ? workreap_get_linked_profile_id($auther_id) : '';
				$auther_title			= get_the_title($auther_profile_id);

				/* featured service */
				$featured_service		= get_post_meta($service_id, '_featured_service_string', true);

				/* service categories */
				$service_cat_items = array();
				if (!empty($services_categories) && $services_categories === 'no') {
					$taxonomy	= 'project_cat';
				} else {
					$taxonomy	= 'service_categories';
				}

				$db_service_cat 			= wp_get_post_terms($service_id, $taxonomy, array('fields' => 'all'));
				$service_categories			= !empty($db_service_cat) ? $db_service_cat : array();
				if (!empty($service_categories)) {
					$serv_count	= 0;
					foreach ($service_categories as $cat) {
						$serv_count++;
						$service_cat_items[] = array(
							'id' => !empty($cat->term_id) ? $cat->term_id : '',
							'name' => !empty($cat->name) ? $cat->name : '',
							'slug' => !empty($cat->slug) ? $cat->slug : '',
						);
					}
				}

				/* faq */
				$faqs_arr 	= array();
				if (!empty($service_faq_option) && $service_faq_option == 'yes') {
					$db_faq		= fw_get_db_post_option($service_id, 'faq');
					$db_faq 	= !empty($db_faq) ? $db_faq : array();
					if (!empty($db_faq)) {
						foreach ($db_faq as $faq_val) {
							$faqs_arr[] = array(
								'faq_question' 	=> $faq_val['faq_question'],
								'faq_answer' 	=> $faq_val['faq_answer'],
							);
						}
					}
				}
				$faqs		= $faqs_arr;

				/* downloadable files */
				$downloadable_files_arr = array();
				$edit_id 					= !empty($service_id) ? intval($service_id) : 0;
				$downloadable_files 		= !empty($edit_id) ? get_post_meta($edit_id, '_downloadable_files', true) : '';
				$downloadable_files			= !empty($downloadable_files) ? $downloadable_files : array();
				if (!empty($downloadable_files)) {
					foreach ($downloadable_files as $downloadable_file) {
						$attachment_id	= !empty($downloadable_file['attachment_id']) ? $downloadable_file['attachment_id'] : '';
						$file_size 		= !empty($downloadable_file) ? filesize(get_attached_file($attachment_id)) : '';
						$filetype       = !empty($downloadable_file) ? wp_check_filetype($downloadable_file['url']) : '';
						$doc_url 		= !empty($downloadable_file['url']) ? esc_url($downloadable_file['url']) : '';
						$file_detail  	= Workreap_file_permission::getDecrpytFile($downloadable_file);
						$name        	= $file_detail['filename'];

						$downloadable_files_arr[] = array(
							'attachment_id' 	=> (int)$attachment_id,
							'name' 				=> $name,
							'size' 				=> $file_size,
							'url' 				=> $doc_url,
							'fileType' 			=> $filetype,
						);
					}
				}

				/* images */
				$service_imge_arr = array();
				$db_images				= !empty($db_docs) ? $db_docs : array();
				if (!empty($db_images)) {

					foreach ($db_images as $key => $img_service) {
						$attachment_id	= !empty($img_service['attachment_id']) ? $img_service['attachment_id'] : '';
						$file_size 		= !empty($img_service) ? filesize(get_attached_file($attachment_id)) : '';
						$document_name	= !empty($img_service) ? get_the_title($attachment_id) : '';
						$filetype       = !empty($img_service) ? wp_check_filetype($img_service['url']) : '';
						$doc_url 		= !empty($img_service['url']) ? $img_service['url'] : '';
						$file_detail  	= Workreap_file_permission::getDecrpytFile($img_service);
						$name        	= $file_detail['filename'];

						$service_imge_arr[] = array(
							'attachment_id' 	=> (int)$attachment_id,
							'name' 				=> $name,
							'size' 				=> $file_size,
							'url' 				=> $doc_url,
							'fileType' 			=> $filetype,
						);
					}
				}

				/* languages */
				$service_lang = array();
				$service_languages = wp_get_post_terms($service_id, 'languages');
				if (!empty($service_languages) && is_array($service_languages)) {
					foreach ($service_languages as $key => $lang) {
						$service_lang[] = array(
							'id' 		=> $lang->term_id,
							'name' 		=> $lang->name,
							'slug' 		=> $lang->slug,
						);
					}
				}

				/* country */
				$service_counry = array();
				$service_counry_slug = get_post_meta($service_id, '_country', true);
				$service_counry_slug = !empty($service_counry_slug) ? $service_counry_slug : '';
				if (!empty($service_counry_slug)) {
					$service_counry_obj = get_term_by('slug', $service_counry_slug, 'locations');
					$service_counry[] = array(
						'id' => $service_counry_obj->term_id,
						'name' => $service_counry_obj->name,
						'slug' => $service_counry_obj->slug,
					);
				}

				/* english level */
				$english_level = array();
				$english_level_slug = get_post_meta($service_id, '_english_level', true);
				$english_level_slug = !empty($english_level_slug) ? $english_level_slug : '';
				if (!empty($english_level_slug)) {
					$english_level_obj = get_term_by('slug', $english_level_slug, 'english_level');
					$english_level[] = array(
						'id' => $english_level_obj->term_id,
						'name' => $english_level_obj->name,
						'slug' => $english_level_obj->slug,
					);
				}

				/* delivery time */
				$delivery_time_arr = array();
				$db_delivery_time 		= wp_get_post_terms($service_id, 'delivery');
				$db_delivery_time 		= !empty($db_delivery_time) ? $db_delivery_time : array();
				if (!empty($db_delivery_time)) {
					foreach ($db_delivery_time as $delivery_time_term) {
						$delivery_time_arr[] = array(
							'id' => $delivery_time_term->term_id,
							'name' => $delivery_time_term->name,
							'slug' => $delivery_time_term->slug,
						);
					}
				}

				/* response time */
				$response_time_arr 		= array();
				$db_response_time 		= wp_get_post_terms($service_id, 'response_time');
				$db_response_time 		= !empty($db_response_time) ? $db_response_time : array();
				if (!empty($db_response_time)) {
					foreach ($db_response_time as $response_time_term) {
						$response_time_arr[] = array(
							'id' 		=> $response_time_term->term_id,
							'name' 		=> $response_time_term->name,
							'slug' 		=> $response_time_term->slug,
						);
					}
				}

				$item = array(
					'service_id' 			=> $service_id,
					'title' 				=> !empty($service_title) ? esc_html($service_title) : '',
					'content' 				=> !empty($service_content) ?  $service_content : '',
					'service_url' 			=> !empty($service_url) ? esc_url($service_url) : '',
					'addons' 				=> $addons_items,
					'auther_title' 			=> !empty($auther_title) ? $auther_title : '',
					'user_id' 				=> (int)$auther_id,
					'profile_id' 			=> $auther_profile_id,
					'featured_text' 		=> !empty($featured_service) ? esc_html__('Featured', 'workreap_api') : '',
					'categories' 			=> $service_cat_items,
					'faq' 					=> $faqs,
					'downloadable' 			=> $db_downloadable,
					'downloadable_files' 	=> $downloadable_files_arr,
					'delivery_time' 		=> $delivery_time_arr,
					'response_time' 		=> $response_time_arr,
					'price' 				=> !empty($service_price) ? $service_price : '',
					'languages' 			=> $service_lang,
					'english_level' 		=> $english_level,
					'images'				=> $service_imge_arr,
					'videos' 				=> $db_videos,
					'country' 				=> $service_counry,
					'address' 				=> $db_address,
					'longitude' 			=> $db_longitude,
					'latitude' 				=> $db_latitude,
					'hide_map' 				=> $db_hide_map,
				);
				return new WP_REST_Response($item, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Something is missing!', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Add Service
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_service($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$total_attachments 	= !empty($request['size']) ? $request['size'] : 0;
			$total_downloads 	= !empty($request['donwload_size']) ? $request['donwload_size'] : 0;

			$json = $items = array();

			$hide_map 			= 'show';
			$system_access = $service_faq_option = '';

			if (function_exists('fw_get_db_post_option')) {
				$hide_map		= fw_get_db_settings_option('hide_map');
				$job_status		= fw_get_db_settings_option('job_status');
				$system_access	= fw_get_db_settings_option('system_access');
				$total_limit	= fw_get_db_settings_option('default_service_images');

				$minimum_service_price	= fw_get_db_settings_option('minimum_service_price');
				$service_faq_option		= fw_get_db_settings_option('service_faq_option');
			}

			$total_limit		= !empty($total_limit) ? intval($total_limit) : 100;
			$job_status			= !empty($job_status) ? $job_status : 'publish';

			$minimum_service_price	= !empty($minimum_service_price) ? intval($minimum_service_price) : 1;
			$linked_profile	= workreap_get_linked_profile_id($user_id);
			do_action('workreap_check_post_author_status', $linked_profile); //check if user is not blocked or deactive
			do_action('workreap_check_post_author_identity_status', $linked_profile); //check if user identity is verified

			$json 		= array();
			$current 	= !empty($request['id']) ? esc_attr($request['id']) : '';

			if (apply_filters('workreap_is_service_posting_allowed', 'wt_services', $user_id) === false && empty($current)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You’ve consumed all you points or your package has get expired. Please upgrade your package', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$is_featured              = !empty($request['is_featured']) ? $request['is_featured'] : '';

			$required = array(
				'title'   			=> esc_html__('Service title is required', 'workreap_api'),
				'price'  			=> esc_html__('Service price is required', 'workreap_api'),
				'categories'   		=> esc_html__('Category is required', 'workreap_api')
			);


			$required	= apply_filters('workreap_filter_service_required_fields', $required);

			foreach ($required as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] = 'error';
					$json['message'] = $value;
					return new WP_REST_Response($json, 203);
				}
				if ($key === 'price' && empty(floatval($request[$key]))) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				} else if ($key === 'price' &&  $request[$key] < $minimum_service_price) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Minimum service price should be ', 'workreap_api') . $minimum_service_price;
					return new WP_REST_Response($json, 203);
				}
			}

			//Addon check
			if (!empty($request['addons_service'])) {
				$required = array(
					'title'   			=> esc_html__('Addons Service title is required', 'workreap_api'),
					'price'  			=> esc_html__('Addons Service price is required', 'workreap_api'),
				);
				$addons_services_	= json_decode(stripslashes($request['addons_service']));
				foreach ($addons_services_ as $key => $value) {
					if (empty($addons_services_[$key])) {
						$json['type'] 		= 'error';
						$json['message'] 	=  $item_check;
						return new WP_REST_Response($json, 203);
					}
				}
			}

			//extract the job variables
			$title				= !empty($request['title']) ? esc_attr($request['title']) : rand(1, 999999);
			$description		= !empty($request['description']) ? $request['description'] : '';

			if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
				$current = !empty($request['id']) ? esc_attr($request['id']) : '';
				$post_author = get_post_field('post_author', $current);
				$post_id 	 = $current;
				$status 	 = get_post_status($post_id);

				if (intval($post_author) === intval($user_id)) {
					$article_post = array(
						'ID' 			=> $current,
						'post_title' 	=> $title,
						'post_content' 	=> $description,
						'post_status' 	=> $status,
					);

					wp_update_post($article_post);
				} else {
					$json['type'] = 'error';
					$json['message'] = esc_html__('Some error occur, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				//change status on update
				do_action('workreap_update_post_status_action', $post_id, 'service'); //Admin will get an email to publish it
			} else {
				//Create Post
				$user_post = array(
					'post_title'    => wp_strip_all_tags($title),
					'post_status'   => $job_status,
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'micro-services',
				);

				$post_id    		= wp_insert_post($user_post);

				//featured string
				update_post_meta($post_id, '_featured_service_string', 0);

				$remaning_services		= workreap_get_subscription_metadata('wt_services', intval($user_id));
				$remaning_services  	= !empty($remaning_services) ? intval($remaning_services) : 0;

				if (!empty($remaning_services) && $remaning_services >= 1) {
					$update_services	= intval($remaning_services) - 1;
					$update_services	= intval($update_services);

					$wt_subscription 	= get_user_meta(intval($user_id), 'wt_subscription', true);
					$wt_subscription	= !empty($wt_subscription) ?  $wt_subscription : array();

					$wt_subscription['wt_services'] = $update_services;
					update_user_meta(intval($user_id), 'wt_subscription', $wt_subscription);
				}

				$expiry_string		= workreap_get_subscription_metadata('subscription_featured_string', $user_id);

				if (!empty($expiry_string)) {
					update_post_meta($post_id, '_expiry_string', $expiry_string);
				}
			}

			if (!empty($post_id)) {
				$fw_options = array();
				/**
				 * work with Images
				 *  */
				if (!empty($_FILES) && $total_attachments != 0) {
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$total_service_imgs = $total_attachments;

					/* already attached images array from api's */
					$old_images = !empty($request['old_images_service']) ? json_decode(stripslashes($request['old_images_service']), true) : '';

					/* already attached images */
					$service_old_attachment_arr	= fw_get_db_post_option($post_id, 'docs', true);

					/* create array of attachment id's that srote in DB */
					$service_old_attachment = array();
					if (!empty($service_old_attachment_arr)) {
						$service_old_attachment 	= wp_list_pluck($service_old_attachment_arr, 'attachment_id');
					}

					/* delete all images if sending null/empty */
					if (empty($old_images) && !empty($service_old_attachment)) {
						foreach ($service_old_attachment as $delete_media) {
							if (!empty($delete_media)) {
								wp_delete_attachment($post_id, $delete_media, true);
							}
						}
						$fw_options['docs']	= array();
					}

					/* upload new images if exist */
					$newyUploadImage = array();
					if (!empty($_FILES) && $total_service_imgs != 0) {
						$new_index	= !empty($service_old_attachment) ?  max(array_keys($service_old_attachment_arr)) : 0;
						for ($x = 0; $x < $total_service_imgs; $x++) {
							$new_index = $new_index + 1;
							$service_image_files 	= $_FILES['service_images' . $x];
							$uploaded_image  		= wp_handle_upload($service_image_files, array('test_form' => false));
							$file_name			 	= basename($service_image_files['name']);
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
							$fw_options['docs'][$new_index]	= $gallery;
							$newyUploadImage[] = $gallery;
						}
					}

					/* delete some images that not send in request */
					if (!empty($old_images) && !empty($service_old_attachment)) {
						$newImagesArr = array();
						$db_saved_images = !empty($service_old_attachment_arr) ? $service_old_attachment_arr : array();
						if (!empty($db_saved_images)) {
							foreach ($db_saved_images as $imgsVal) {
								foreach ($old_images as $oldVal) {
									if ($imgsVal['attachment_id'] == $oldVal['attachment_id']) {
										$newImagesArr[] = array(
											'attachment_id' => (int)$imgsVal['attachment_id'],
											'url' 			=> $imgsVal['url']
										);
									}
								}
							}

							$newyUploadImage_arr = array_merge($newyUploadImage, $newImagesArr);
							$fw_options['docs'] = $newyUploadImage_arr;
						}
					}

					if (!empty($newyUploadImage[0]['attachment_id'])) {
						set_post_thumbnail($post_id, $newyUploadImage[0]['attachment_id']);
					}
				} else {
					/* already attached images array from api's */
					$old_images = !empty($request['old_images_service']) ? json_decode(stripslashes($request['old_images_service']), true) : '';
					/* old data updated */
					$db_service_old_attachment 	= fw_get_db_post_option($post_id, 'docs', true);

					/* create array of attachment id's that srote in DB */
					$service_old_attachment = array();
					if (!empty($db_service_old_attachment)) {
						$service_old_attachment 	= wp_list_pluck($db_service_old_attachment, 'attachment_id');
					}

					/* delete all images if sending null/empty */
					if (empty($old_images) && !empty($service_old_attachment)) {
						foreach ($service_old_attachment as $delete_media) {
							if (!empty($delete_media)) {
								wp_delete_attachment($post_id, $delete_media, true);
							}
						}
						$fw_options['docs']	= array();
					} else {
						$newImagesArr = array();
						/* delete some images that not send in request */
						if (!empty($old_images) && !empty($service_old_attachment)) {
							$db_saved_images = !empty($db_service_old_attachment) ? $db_service_old_attachment : array();
							if (!empty($db_saved_images)) {
								foreach ($db_saved_images as $imgsVal) {
									foreach ($old_images as $oldVal) {
										if ($imgsVal['attachment_id'] == $oldVal['attachment_id']) {
											$newImagesArr[] = array(
												'attachment_id' => (int)$imgsVal['attachment_id'],
												'url' 			=> $imgsVal['url']
											);
										}
									}
								}
							}
						}

						if (!empty($newImagesArr[0]['attachment_id'])) {
							set_post_thumbnail($post_id, $newImagesArr[0]['attachment_id']);
						}
						$fw_options['docs'] = $newImagesArr;
					}
				}

				/**
				 * work with downloadable files
				 *  */
				$downloads_files	= array();
				if (!empty($_FILES) && $total_downloads != 0) {
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					/* how many new files send from api's */
					$total_new_download_attachments = $total_downloads;

					/* already attached doc array from api's */
					$old_doc_attachments = !empty($request['old_download_attachments_service']) ? json_decode(stripslashes($request['old_download_attachments_service']), true) : array();

					/* attached doc from DB */
					$db_servide_attachment_arr       = get_post_meta($post_id, '_downloadable_files');
					$db_servide_attachment_arr		= !empty($db_servide_attachment_arr) ? $db_servide_attachment_arr : array();

					/* create array of attachment id's that store in DB */
					$db_service_download_attachment = array();
					if (!empty($db_servide_attachment_arr)) {
						$db_service_download_attachment 	= wp_list_pluck($db_servide_attachment_arr, 'attachment_id');
					}

					/* delete all docs if empty array received from api's */
					if (empty($old_doc_attachments) && !empty($db_service_download_attachment)) {
						foreach ($db_service_download_attachment as $delete_media) {
							if (!empty($delete_media)) {
								wp_delete_attachment($post_id, $delete_media, true);
							}
						}
						update_post_meta($post_id, '_downloadable_files', array());
					}

					/* upload new docs if exist */
					if (!empty($_FILES) && $total_new_download_attachments != 0) {
						$new_index	= !empty($db_servide_attachment_arr) ?  max(array_keys($db_servide_attachment_arr)) : 0;

						for ($x = 0; $x < $total_new_download_attachments; $x++) {
							$new_index 				= $new_index + 1;
							$service_docs_files 	= $_FILES['downloads_documents' . $x];
							$uploaded_doc  		= wp_handle_upload($service_docs_files, array('test_form' => false));
							$file_name			 	= basename($service_docs_files['name']);
							$file_type 		 		= wp_check_filetype($uploaded_doc['file']);

							/* Prepare an array of post data for the attachment. */
							$doc_attachment_details = array(
								'guid' 				=> $uploaded_doc['url'],
								'post_mime_type' 	=> $file_type['type'],
								'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
								'post_content' 		=> '',
								'post_status' 		=> 'inherit'
							);

							$attach_doc_id 		= wp_insert_attachment($doc_attachment_details, $uploaded_doc['file']);
							$attach_data 	= wp_generate_attachment_metadata($attach_doc_id, $uploaded_doc['file']);
							wp_update_attachment_metadata($attach_doc_id, $attach_data);

							$docs_attached['attachment_id']  				= (int)$attach_doc_id;
							$docs_attached['url']            				= wp_get_attachment_url($attach_doc_id);
							$downloads_files[$new_index]	= $docs_attached;
						}
					}

					/* delete some docs that not send in request */
					if (!empty($old_doc_attachments) && !empty($db_service_download_attachment)) {
						$newDocsArr = array();
						$db_saved_documents = !empty($db_servide_attachment_arr) ? $db_servide_attachment_arr : array();

						if (!empty($db_saved_documents) && !empty($old_doc_attachments)) {
							foreach ($db_saved_documents as $documentVal) {

								foreach ($old_doc_attachments as $oldAttachmentVal) {
									if ($documentVal['attachment_id'] == $oldAttachmentVal['attachment_id']) {
										$newDocsArr[] = array(
											'attachment_id' => (int)$documentVal['attachment_id'],
											'url' 			=> $documentVal['url']
										);
									}
								}
							}

							$docNew_arr = array_merge($downloads_files, $newDocsArr);

							$downloads_files = $docNew_arr;
						}
					}

					$is_downloable	= !empty($request['downloadable']) ? $request['downloadable'] : '';
					if (!empty($is_downloable) && $is_downloable === 'yes' && !empty($downloads_files)) {
						update_post_meta($post_id, '_downloadable_files', $downloads_files);
					}
					update_post_meta($post_id, '_downloadable', $is_downloable);
				} else {
					/* already attached doc array from api's */
					$old_doc_attachments = !empty($request['old_download_attachments_service']) ? json_decode(stripslashes($request['old_download_attachments_service']), true) : array();

					/* attached doc from DB */
					$db_servide_attachment_arr       = get_post_meta($post_id, '_downloadable_files');
					$db_servide_attachment_arr		= !empty($db_servide_attachment_arr) ? $db_servide_attachment_arr : array();

					/* create array of attachment id's that srote in DB */
					$db_service_download_attachment = array();
					if (!empty($db_servide_attachment_arr)) {
						$db_service_download_attachment 	= wp_list_pluck($db_servide_attachment_arr, 'attachment_id');
					}

					/* delete all docs if empty array received from api's */
					if (empty($old_doc_attachments) && !empty($db_service_download_attachment)) {
						foreach ($db_service_download_attachment as $delete_media) {
							if (!empty($delete_media)) {
								wp_delete_attachment($post_id, $delete_media, true);
							}
						}
						update_post_meta($post_id, '_downloadable_files', array());
					} else {
						/* delete some docs that not send in request */
						$downloads_files = array();
						if (!empty($old_doc_attachments) && !empty($db_service_download_attachment)) {
							$newDocsArr = array();
							$db_saved_documents = !empty($db_servide_attachment_arr) ? $db_servide_attachment_arr : array();
							if (!empty($db_saved_documents) && !empty($old_doc_attachments)) {
								foreach ($db_saved_documents as $documentVal) {
									foreach ($old_doc_attachments as $oldAttachmentVal) {
										if ($documentVal['attachment_id'] == $oldAttachmentVal['attachment_id']) {
											$newDocsArr[] = array(
												'attachment_id' => (int)$documentVal['attachment_id'],
												'url' 			=> $documentVal['url']
											);
										}
									}
								}
								$downloads_files = $newDocsArr;
							}
						}
						update_post_meta($post_id, '_downloadable_files', $downloads_files);
					}
				}

				$categories		= !empty($request['categories']) ? json_decode($request['categories'], true) : array();
				$languages		= !empty($request['languages']) ? json_decode($request['languages'], true) : array();
				$price	        = !empty($request['price']) ? workreap_wmc_compatibility($request['price']) : '';
				$delivery_time  = !empty($request['delivery_time']) ? array($request['delivery_time']) : array();
				$response_time  = !empty($request['response_time']) ? array($request['response_time']) : array();
				$english_level	= !empty($request['english_level']) ? $request['english_level'] : '';
				$addons	        = !empty($request['addons']) ? json_decode($request['addons'], true) : array();
				$addons_service	= !empty($request['addons_service']) ? json_decode($request['addons_service'], true) : array();

				if (!empty($addons_service)) {
					foreach ($addons_service as $key => $item) {
						$user_post = array(
							'post_title'    => wp_strip_all_tags($item['title']),
							'post_excerpt'  => $item['description'],
							'post_author'   => $user_id,
							'post_type'     => 'addons-services',
							'post_status'	=> 'publish'
						);

						$addon_post_id    		= wp_insert_post($user_post);
						$addons[]				= $addon_post_id;
						$addon_price	        = !empty($item['price']) ? $item['price'] : '';

						//update
						update_post_meta($addon_post_id, '_price', workreap_wmc_compatibility($addon_price));
						//update unyson meta
						$fw_options_['price']	= workreap_wmc_compatibility($addon_price);

						fw_set_db_post_option($addon_post_id, null, $fw_options_);
					}
				}

				update_post_meta($post_id, '_addons', $addons);

				if (!empty($is_featured) && $is_featured === 'on') {
					$is_featured_service	= get_post_meta($post_id, '_featured_service_string', true);

					if (empty($is_featured_service)) {
						$featured_services	= workreap_featured_service($user_id);
						if ($featured_services || $system_access == 'both') {
							$featured_string	= workreap_is_feature_value('subscription_featured_string', $user_id);
							update_post_meta($post_id, '_featured_service_string', 1);
						}

						$remaning_featured_services		= workreap_get_subscription_metadata('wt_featured_services', intval($user_id));
						$remaning_featured_services  	= !empty($remaning_featured_services) ? intval($remaning_featured_services) : 0;

						if (!empty($remaning_featured_services) && $remaning_featured_services >= 1) {
							$update_featured_services	= intval($remaning_featured_services) - 1;
							$update_featured_services	= intval($update_featured_services);

							$wt_subscription 	= get_user_meta(intval($user_id), 'wt_subscription', true);
							$wt_subscription	= !empty($wt_subscription) ?  $wt_subscription : array();

							$wt_subscription['wt_featured_services'] = $update_featured_services;
							update_user_meta(intval($user_id), 'wt_subscription', $wt_subscription);
						}
					}
				} else {
					update_post_meta($post_id, '_featured_service_string', 0);
				}

				if (!empty($categories)) {
					if (function_exists('fw_get_db_post_option')) {
						$services_categories	= fw_get_db_settings_option('services_categories');
					}

					$services_categories	= !empty($services_categories) ? $services_categories : 'no';
					if (!empty($services_categories) && $services_categories === 'no') {
						$taxonomy_type	= 'project_cat';
					} else {
						$taxonomy_type	= 'service_categories';
					}

					wp_set_post_terms($post_id, $categories, $taxonomy_type);
				}

				if (!empty($languages)) {
					wp_set_post_terms($post_id, $languages, 'languages');
				}

				if (!empty($delivery_time)) {
					wp_set_post_terms($post_id, $delivery_time, 'delivery');
				}

				if (!empty($response_time)) {
					wp_set_post_terms($post_id, $response_time, 'response_time');
				}

				//update location
				$address    	= !empty($request['address']) ? esc_attr($request['address']) : '';
				$country    	= !empty($request['country']) ? $request['country'] : '';
				$latitude   	= !empty($request['latitude']) ? esc_attr($request['latitude']) : '';
				$longitude  	= !empty($request['longitude']) ? esc_attr($request['longitude']) : '';
				$new_videos 	= !empty($request['videos']) ? json_decode($request['videos'], true) : array();

				/* update country */
				update_post_meta($post_id, '_country', $country);

				/* reset comming video data */
				$videos = array();
				if (!empty($new_videos) && is_array($new_videos)) {
					foreach ($new_videos as $key => $video) {
						$videos[] = $video['videotitle'];
					}
				}

				//Set country for unyson
				$locations = get_term_by('slug', $country, 'locations');
				$location = array();
				if (!empty($locations)) {
					$location[0] = $locations->term_id;
					if (!empty($location)) {
						wp_set_post_terms($post_id, $location, 'locations');
					}
				}

				//update
				update_post_meta($post_id, '_price', $price);
				update_post_meta($post_id, '_english_level', $english_level);

				//update unyson meta
				if (!empty($service_faq_option) && $service_faq_option == 'yes') {
					$faq 					= !empty($request['faq']) ? json_decode($request['faq'], true) : array();
					$fw_options['faq']      = $faq;
				}

				$fw_options['price']         	= $price;
				$fw_options['downloadable']     = $is_downloable;
				$fw_options['english_level']    = $english_level;
				$fw_options['address']          = $address;
				$fw_options['longitude']        = $longitude;
				$fw_options['latitude']         = $latitude;
				$fw_options['country']          = $location;
				$fw_options['videos']           = $videos;

				//Update User Profile
				fw_set_db_post_option($post_id, null, $fw_options);

				if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your service has been updated', 'workreap_api');
				} else {
					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapServicePost')) {
							$email_helper = new WorkreapServicePost();
							$emailData 	  = array();

							$freelancer_name 		= workreap_get_username($user_id);
							$freelancer_email 		= get_userdata($user_id)->user_email;
							$freelancer_profile 	= get_permalink($user_id);
							$service_title 			= get_the_title($post_id);
							$service_link 			= get_permalink($post_id);

							$emailData['freelancer_name'] 	= esc_html($freelancer_name);
							$emailData['freelancer_email'] 	= esc_html($freelancer_email);
							$emailData['freelancer_link'] 	= esc_url($freelancer_profile);
							$emailData['status'] 			= esc_html($job_status);
							$emailData['service_title'] 	= esc_html($service_title);
							$emailData['service_link'] 		= esc_url($service_link);

							$email_helper->send_admin_service_post($emailData);
							$email_helper->send_freelancer_service_post($emailData);
						}
					}
					//Push notification
					$push	= array();
					$push['freelancer_id']		= $user_id;
					$push['service_id']			= $post_id;
					$push['%freelancer_name%']	= $freelancer_name;
					$push['%freelancer_link%']	= $freelancer_profile;
					$push['%service_title%']	= $service_title;
					$push['%service_link%']		= $service_link;
					$push['type']				= 'post_service';

					do_action('workreap_user_push_notify', array($user_id), '', 'pusher_freelancer_service_post_content', $push);
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your service has been posted.', 'workreap_api');
				}

				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occur, please try again later', 'workreap_api');
				return new WP_REST_Response($items, 203);
			}
		}

		/**
		 * Add addon-Service
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function add_addon_service($data)
		{
			$json					= array();
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);

			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id = !empty($request['user_id']) ? intval($request['user_id']) : 0;
			if (!empty($user_id)) {
				$submit_type = !empty($request['submit_type']) ? esc_html($request['submit_type']) : '';
				/* required fields */
				$required = array(
					'title'   			=> esc_html__('Addon Service title is required', 'workreap_api'),
					'user_id'  			=> esc_html__('User ID is required', 'workreap_api'),
					'price'  			=> esc_html__('Addon Service Service price is required', 'workreap_api')
				);
				foreach ($required as $key => $value) {
					if (empty($request[$key])) {
						$json['type'] 		= 'error';
						$json['message'] 	= $value;
						return new WP_REST_Response($json, 203);
					}
				}

				$title				= !empty($request['title']) ? sanitize_text_field($request['title']) : '';
				$description		= !empty($request['description']) ?  sanitize_textarea_field($request['description']) : '';
				$price				= !empty($request['price']) ?  esc_html($request['price']) : '';

				if (isset($submit_type) && $submit_type === 'update') {
					$current_post_id = !empty($request['id']) ? intval($request['id']) : '';
					$post_author = get_post_field('post_author', $current_post_id);
					$post_id 	 = $current_post_id;

					if (intval($post_author) === intval($user_id)) {
						$article_post = array(
							'ID' 			=> $current_post_id,
							'post_title' 	=> $title,
							'post_excerpt' 	=> $description,
						);
						wp_update_post($article_post);
					} else {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				} else {
					//Create Post
					$user_post = array(
						'post_title'    => wp_strip_all_tags($title),
						'post_excerpt'  => $description,
						'post_author'   => $user_id,
						'post_type'     => 'addons-services',
						'post_status'	=> 'publish'
					);
					$post_id    		= wp_insert_post($user_post);
				}

				if (!empty($post_id)) {
					//update
					update_post_meta($post_id, '_price', $price);
					//update unyson meta
					$fw_options 					= array();
					$fw_options['price']         	= $price;
					//Update User Profile
					fw_set_db_post_option($post_id, null, $fw_options);

					if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
						$json['type'] 		= 'success';
						$json['message'] 	= esc_html__('Your addons service has been updated', 'workreap_api');
					} else {
						$json['type'] 		= 'success';
						$json['message'] 	= esc_html__('Your addons service has been added', 'workreap_api');
					}
					return new WP_REST_Response($json, 200);
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('User id is required!', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Listings aadons
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_addons_services($request)
		{
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$post_id		= !empty($request['post_id']) ?  $request['post_id'] : '';
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$type_search	= !empty($request['type']) ? esc_html($request['type']) : '';
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$keyword 		= !empty($request['keyword']) ? $request['keyword'] : '';
			$items			= array();
			if (!empty($user_id)) {
				$args = array(
					'posts_per_page' 	=> $limit,
					'post_type' 		=> 'addons-services',
					'post_status' 		=> array('publish'),
					'author' 			=> $user_id,
					'paged' 			=> $page_number,
					'suppress_filters'  => false
				);

				if ((!empty($type_search) && $type_search === 'search') && !empty($keyword)) {
					$args['s'] = $keyword;
				}

				if (!empty($post_id)) {
					$args['post__in'] = array($post_id);
				}

				$query 			= new WP_Query($args);
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
						global $post;

						$service_title		= get_the_title($post->ID);
						$db_price			= 0;
						if (function_exists('fw_get_db_post_option')) {
							$db_price   = fw_get_db_post_option($post->ID, 'price');
						}
						$perma_link			= get_the_permalink($post->ID);
						$post_status		= get_post_status($post->ID);
						$addon_excerpt		= get_the_excerpt($post->ID);

						$items[] = array(
							'ID' 				=> !empty($post->ID) ? $post->ID : '',
							'link' 				=> !empty($perma_link) ?  $perma_link : '',
							'title' 			=> !empty($service_title) ? $service_title : '',
							'price' 			=> !empty($db_price) ?  $db_price : '',
							'status' 			=> !empty($post_status) ? $post_status : '',
							'description' 		=> !empty($addon_excerpt) ? $addon_excerpt : '',
							'price_formated' 	=> !empty($db_price) ?  workreap_price_format($db_price, 'return') : '',
						);
					}

					$json['type'] 		= 'success';
					$json['items'] 		= maybe_unserialize($items);
					return new WP_REST_Response($json, 200);
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Empty Service Addon.', 'workreap_api');
					$json['items'] 		= array();
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('User Id is required', 'workreap_api');
				$json['items'] 			= array();
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Delete addon service
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function delete_addon_service($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			if (!empty($user_id)) {
				$service_id		= !empty($request['id']) ?  $request['id'] : 0;
				if (empty($service_id)) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Addons service ID is required', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				if (!empty($service_id)) {
					wp_delete_post($service_id);
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Successfully!  removed this addon service.', 'workreap_api');
					return new WP_REST_Response($json, 200);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('User id is missing', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Delete service
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function delete_service($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			if (!empty($user_id)) {
				$service_id		= !empty($request['id']) ?  $request['id'] : 0;
				$items			= array();

				if (empty($service_id)) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Service ID is required', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				if (!empty($service_id)) {
					$queu_services		= workreap_get_services_count('services-orders', array('hired'), $service_id);
					if ($queu_services === 0) {
						$update				= workreap_save_service_status($service_id, 'deleted');
						$json['type'] 		= 'success';
						$json['message'] 	= esc_html__('Successfully!  removed this service.', 'workreap_api');
						return new WP_REST_Response($json, 200);
					} else {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('You cannot do this because your orders in queue.', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('User id is required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_services($request)
		{
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$service_id		= !empty($request['service_id']) ? intval($request['service_id']) : '';
			$profile_id		= !empty($request['profile_id']) ? intval($request['profile_id']) : '';
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : 0;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$listing_type	= !empty($request['listing_type']) ? esc_attr($request['listing_type']) : '';

			$system_access	= workreap_return_system_access();
			if (isset($system_access) && $system_access == 'job') {
				$json['type']		= 'error';
				$json['message']	= esc_html__('You are restricted to access this page', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$english_level      	= worktic_english_level_list();
			$remove_service_languages = $service_faq_option = 'no';
			if (function_exists('fw_get_db_settings_option')) {
				$service_faq_option			= fw_get_db_settings_option('service_faq_option');
				$remove_service_languages	= fw_get_db_settings_option('remove_service_languages');
			}
			$offset 				= ($page_number - 1) * $limit;

			$json			= array();
			$items			= array();
			$today 			= time();

			if (!empty($profile_id)) {
				$saved_services	= get_post_meta($profile_id, '_saved_services', true);
			} else {
				$saved_services	= array();
			}

			$defult			= get_template_directory_uri() . '/images/featured.png';
			$count_post		= 0;
			if ($listing_type === 'user') {

				$query_args = array(
					'posts_per_page' 	  	=> $limit,
					'post_type' 	 	  	=> 'micro-services',
					'paged' 		 	  	=> $page_number,
					'post_status'			=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				if (!empty($user_id)) {
					$query_args['author']  = $user_id;
				} 
				//order by pro member
				$query_args['meta_key'] = '_featured_service_string';
				$query_args['orderby']	 = array(
					'meta_value' 	=> 'DESC',
					'ID'      		=> 'DESC'
				);
				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} else if ($request['listing_type'] === 'single') {

				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'micro-services',
					'post__in' 		 	  	=> array($service_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);

				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} else if (!empty($listing_type) && $listing_type === 'featured') {
				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'micro-services',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);
				//order by pro member
				$query_args['meta_key'] = '_featured_service_string';
				$query_args['orderby']	 = array(
					'meta_value' 	=> 'DESC',
					'ID'      		=> 'DESC'
				);

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif (!empty($listing_type) && $listing_type === 'single') {
				$post_id		= !empty($service_id) ? $service_id : '';
				$query_args = array(
					'post_type' 	 	  	=> 'any',
					'p'						=> $post_id
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif (!empty($listing_type) && $listing_type === 'latest') {
				$order		 	= 'DESC';
				$query_args 	= array(
					'posts_per_page' 	  	=> $limit,
					'post_type' 	 	  	=> 'micro-services',
					'paged' 		 	  	=> $page_number,
					'post_status' 	 	  	=> 'publish',
					'order'					=> 'ID',
					'orderby'				=> $order,
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif (!empty($listing_type) && $listing_type === 'favorite') {
				$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				$wishlist 			= get_post_meta($linked_profile, '_saved_services', true);
				$wishlist			= !empty($wishlist) ? $wishlist : array();
				if (!empty($wishlist)) {
					$order		 = 'DESC';
					$query_args = array(
						'posts_per_page' 	  	=> $limit,
						'post_type' 	 	  	=> 'micro-services',
						'post__in'				=> $wishlist,
						'paged' 		 	  	=> $page_number,
						'post_status' 	 	  	=> 'publish',
						'order'					=> 'ID',
						'orderby'				=> $order,
						'ignore_sticky_posts' 	=> 1
					);
					$query 			= new WP_Query($query_args);
					$count_post 	= $query->found_posts;
				} else {
					$json['type']		= 'error';
					$json['message']	= esc_html__('You have no services in your favorite list.', 'workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			} elseif (!empty($listing_type) && $listing_type === 'search') {
				//Search parameters
				$keyword 		= !empty($request['keyword']) ? $request['keyword'] : '';
				$categories 	= !empty($request['category']) ? json_decode($request['category'], true) : array();
				$locations 	 	= !empty($request['location']) ? json_decode($request['location'], true) : array();
				$delivery 		= !empty($request['service_duration']) ? json_decode($request['service_duration'], true) : array();
				$response_time	= !empty($request['response_time']) ? json_decode($request['response_time'], true)  : array();
				$languages 		= !empty($request['language']) ? json_decode($request['language'], true)  : array();
				$english_level  = !empty($request['english_level']) ? json_decode($request['english_level'], true)  : array();
				$minprice 		= !empty($request['minprice']) ? intval($request['minprice']) : 0;
				$maxprice 		= !empty($request['maxprice']) ? intval($request['maxprice']) : '';

				$services_categories	= 'service_categories';
				if(function_exists('fw_get_db_settings_option')){
					$services_categories	= fw_get_db_settings_option('services_categories');
				}

				if( !empty($services_categories) && $services_categories === 'no' ) {
					$taxonomy_type	= 'project_cat';
				}else{
					$taxonomy_type	= 'service_categories';
				}

				$tax_query_args  = array();
				$meta_query_args = array();

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
				if (!empty($maxprice)) {
					$range_array 		= array($minprice, $maxprice);
					$price_args = array();

					$query_relation = array('relation' => 'OR',);

					$price_args[]  = array(
						'key' 		=> '_price',
						'value' 	=> $range_array,
						'type'    	=> 'NUMERIC',
						'compare' 	=> 'BETWEEN'
					);
					$meta_query_args[] = array_merge($query_relation, $price_args);
				}
				//Delivery
				if (!empty($delivery[0]) && is_array($delivery)) {
					$query_relation = array('relation' => 'OR',);
					$delv_args  	= array();

					foreach ($delivery as $key => $del) {
						$delv_args[] = array(
							'taxonomy' => 'delivery',
							'field'    => 'slug',
							'terms'    => $del,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $delv_args);
				}

				//Delivery
				if (!empty($response_time[0]) && is_array($response_time)) {
					$query_relation = array('relation' => 'OR',);
					$reponse_args  	= array();

					foreach ($response_time as $key => $res) {
						$reponse_args[] = array(
							'taxonomy' => 'response_time',
							'field'    => 'slug',
							'terms'    => $res,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $reponse_args);
				}

				//Categories
				if (!empty($categories[0]) && is_array($categories)) {
					$query_relation = array('relation' => 'OR',);
					$category_args  = array();

					foreach ($categories as $key => $cat) {
						$category_args[] = array(
							'taxonomy' => $taxonomy_type,
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
							'taxonomy' => 'locations',
							'field'    => 'slug',
							'terms'    => $loc,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $location_args);
				}

				//Main Query
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'micro-services',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => array('publish'),
					'ignore_sticky_posts' => 1
				);

				//keyword search
				if (!empty($keyword)) {
					$query_args['s']	=  $keyword;
				}

				//order by pro member
				$query_args['meta_key'] = '_featured_service_string';
				$query_args['orderby']	= array(
					'meta_value' 	=> 'DESC',
					'ID'      		=> 'DESC'
				);

				//Taxonomy Query
				if (!empty($tax_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);
				}

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation 			= array('relation' => 'AND',);
					$meta_query_args 			= array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] 	= $meta_query_args;
				}

				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} else {
				if (!empty($count_post) && $count_post) {
					$json['type']		= 'error';
					$json['message']	= esc_html__('Please provide api type', 'workreap_api');
					return new WP_REST_Response($json, 203);
				} else {
					$json['type']		= 'error';
					$json['message']	= esc_html__('Please provide api type', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			}

			/* Start Query working. */
			if ($query->have_posts()) {
				$width			= 355;
				$height			= 352;
				$formate_date	= get_option('date_format');
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					do_action('workreap_post_views', $post->ID, 'services_views');

					$item['service_id']		= $post->ID;
					$service_title			= get_the_title($post->ID);
					$item['title']			= !empty($service_title) ? esc_html($service_title) : '';
					$service_url			= get_the_permalink($post->ID);
					$item['service_url']	= !empty($service_url) ? esc_url($service_url) : '';

					$item['favorit']			= 'no';
					if (!empty($saved_services)  &&  in_array($post->ID, $saved_services)) {
						$item['favorit']			= 'yes';
					}

					$auther_id				= get_post_field('post_author', $post->ID);
					$auther_profile_id		= !empty($auther_id) ? workreap_get_linked_profile_id($auther_id) : '';
					$item['user_id']		= (int)$auther_id;
					$item['profile_id']		= $auther_profile_id;
					$auther_title			= get_the_title($auther_profile_id);
					$item['auther_title']	= !empty($auther_title) ? $auther_title : '';

					$db_addons				= get_post_meta($post->ID, '_addons', true);
					$db_addons				= !empty($db_addons) ? $db_addons : array();
					$itm = $addons_items = array();

					if (!empty($db_addons)) {
						foreach ($db_addons as $addon) {
							if (!empty($addon)) {
								$service_title		= get_the_title($addon);
								$itm['title']		= !empty($service_title) ? $service_title : '';
								$db_price			= 0;
								if (function_exists('fw_get_db_post_option')) {
									$db_price   = fw_get_db_post_option($addon, 'price');
								}

								$itm['price']		= !empty($db_price) ?  html_entity_decode(workreap_price_format($db_price, 'return')) : '';
								$post_status		= get_post_status($addon);
								$itm['status']		= !empty($post_status) ? $post_status : '';
								$addon_excerpt		= get_the_excerpt($addon);
								$itm['description']	= !empty($addon_excerpt) ? $addon_excerpt : '';
								$itm['ID']			= !empty($addon) ? $addon : '';
								$addons_items[]		= maybe_unserialize($itm);
							}
						}
					}

					$item['addons']	= $addons_items;

					/* Service location */
					$service_location_arr 		= array();
					$service_location 			= wp_get_post_terms($post->ID, 'locations', true);
					$service_location 			= !empty($service_location) ? $service_location : array();
					if (!empty($service_location)) {
						foreach ($service_location as $location_val) {
							$service_location_arr[] = array(
								'id' 	=> $location_val->term_id,
								'name'	=> $location_val->name,
								'slug'	=> $location_val->slug
							);
						}
					}
					$item['service_location'] 	= $service_location_arr;

					/* Avatar */
					$freelancer_avatar = apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $auther_profile_id),
						array('width' => 100, 'height' => 100)
					);
					$item['auther_image']	= !empty($freelancer_avatar) ? esc_url($freelancer_avatar) : '';

					$auther_verivifed			= get_post_meta($auther_profile_id, "_is_verified", true);
					$item['auther_verified']	= !empty($auther_verivifed) ? esc_attr($auther_verivifed) : '';

					$created_date			= get_the_date($formate_date, $auther_profile_id);
					$item['auther_date']	= !empty($created_date) ? $created_date : '';

					$post_name				= workreap_get_slug($auther_profile_id);
					$item['auther_slug']	= !empty($post_name) ? esc_attr($post_name) : '';

					$services_views_count   = get_post_meta($post->ID, 'services_views', true);
					$item['service_views']	= !empty($services_views_count) ? intval($services_views_count) : 0;

					//Featured Service
					$featured_service		= get_post_meta($post->ID, '_featured_service_string', true);
					$item['featured_text']	= !empty($featured_service) ? esc_html__('Featured', 'workreap_api') : '';

					$db_project_cat 		= wp_get_post_terms($post->ID, 'project_cat', array('fields' => 'all'));
					$categories				= !empty($db_project_cat) ? $db_project_cat : array();
					$item['categories']		= array();
					if (!empty($categories)) {
						foreach ($categories as $cat) {
							$item['categories'][] = array(
								'id' 				=> $cat->term_id,
								'category_name' 	=> $cat->name,
								'slug' 				=> $cat->slug,
							);
						}
					}

					$service_content		= get_the_content($post->ID);
					$item['content']		= !empty($service_content) ?  $service_content : '';

					$serviceTotalRating		= get_post_meta($post->ID, '_service_total_rating', true);
					$serviceFeedbacks		= get_post_meta($post->ID, '_service_feedbacks', true);
					$queu_services			= workreap_get_services_count('services-orders', array('hired'), $post->ID);
					$item['rating']			= !empty($serviceTotalRating) ? $serviceTotalRating : 0;
					$item['feedback']		= !empty($serviceFeedbacks) ? intval($serviceFeedbacks) : 0;

					if (!empty($serviceTotalRating) || !empty($serviceFeedbacks)) {
						$serviceTotalRating	= $serviceTotalRating / $serviceFeedbacks;
					} else {
						$serviceTotalRating	= 0;
					}
					$item['total_rating'] 		= number_format((float) $serviceTotalRating, 1);

					$db_english_level = '';
					if (function_exists('fw_get_db_post_option')) {
						$db_docs   			= fw_get_db_post_option($post->ID, 'docs');
						$order_details   	= fw_get_db_post_option($post->ID, 'order_details');
						$db_price   		= fw_get_db_post_option($post->ID, 'price');
						$db_downloadable   	= fw_get_db_post_option($post->ID, 'downloadable');
						$db_english_level   = fw_get_db_post_option($post->ID, 'english_level');
					}

					$item['faq']			= array();
					if (!empty($service_faq_option) && $service_faq_option == 'yes') {
						$faq 					= fw_get_db_post_option($post->ID, 'faq');
						$item['faq']			= !empty($faq) ? array_values($faq) : array();
					}

					$item['downloadable']	= !empty($db_downloadable) ? $db_downloadable : 'no';
					$db_docs				= !empty($db_docs) ? $db_docs : array();
					$item['price']			= !empty($db_price) ? $db_price : '';
					$item['formated_price']			= !empty($db_price) ? html_entity_decode(workreap_price_format($db_price, 'return')) : '';

					$db_delivery_time 		= wp_get_post_terms($post->ID, 'delivery');
					$db_response_time 		= wp_get_post_terms($post->ID, 'response_time');
					$item['delivery_time']	= !empty($db_delivery_time[0]) ? $db_delivery_time[0]->name : '';
					$item['response_time']	= !empty($db_response_time[0]) ? $db_response_time[0]->name : '';
					$db_response_time 		= wp_get_post_terms($post->ID, 'response_time');
					$queu_services			= workreap_get_services_count('services-orders', array('hired'), $post->ID);
					$item['queue']			= !empty($queu_services) ? $queu_services : 0;

					$completed_services		= workreap_get_services_count('services-orders', array('completed'), $post->ID);
					$item['sold']			= !empty($completed_services) ? $completed_services : 0;

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
					$item['english_level']	= $english_level;

					/* is featured */
					$is_featured	= apply_filters('workreap_service_print_featured', $post->ID, 'yes');
					$item['is_featured']	= !empty($is_featured) ? 'yes' : 'no';

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
					$item['speak_languages']	= $lang_speak_arr;


					$item['images']	= array();
					if (!empty($db_docs)) {
						$docs_count	= 0;
						foreach ($db_docs as $key => $doc) {
							$docs_count++;
							$attachment_id				= !empty($doc['attachment_id']) ? $doc['attachment_id'] : '';
							$image_url					= workreap_prepare_image_source($attachment_id, $width, $height);
							$item['images'][]['url'] 	= !empty($image_url) ? esc_url($image_url) : '';
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

							$author_id 		= get_the_author_meta('ID');
							$linked_profile = workreap_get_linked_profile_id($author_id);
							$tagline		= workreap_get_tagline($linked_profile);
							$employer_title = get_the_title($linked_profile);
							$employer_avatar = apply_filters(
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

					$item['reviews']			= array_values($reviews);
					$item['count_totals']       = !empty($count_post) ? intval($count_post) : 0;
					$items[]					= maybe_unserialize($item);
				}

				$json['type']		= 'success';
				$json['services'] 	= $items;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']		= 'success';
				$json['message']	= esc_html__('Sorry no record found!', 'workreap_api');
				$json['services'] 	= array();
				return new WP_REST_Response($json, 200);
			}
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidAppGetServicesRoutes;
		$controller->register_routes();
	}
);
