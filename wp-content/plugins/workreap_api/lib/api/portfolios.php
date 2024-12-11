<?php
if (!class_exists('AndroidAppGetPortfoliosRoutes')) {

	class AndroidAppGetPortfoliosRoutes extends WP_REST_Controller
	{
		/**
		 * Register the routes for the objects of the controller.
		 */
		public function register_routes()
		{
			$version 	= '1';
			$namespace 	= 'api/v' . $version;
			$base 		= 'portfolios';

			register_rest_route(
				$namespace,
				'/' . $base . '/get_portfolios',
				array(
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_portfolios'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/post_portfolio_comments',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'post_portfolio_comments'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);


			register_rest_route(
				$namespace,
				'/' . $base . '/update_portfolio',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_portfolio'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/delete_portfolio',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'delete_portfolio'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/' . $base . '/update_post_status',
				array(
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'update_post_status'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * Update protfolio status
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_post_status($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$json		= array();
			$user_id	= !empty($request['user_id']) ? intval($request['user_id']) : '';
			if (empty($user_id)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You must login before changing this portfolio status.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$required = array(
				'id'   			=> esc_html__('Portfolio ID is required', 'workreap_api'),
				'status'  		=> esc_html__('Portfolio status is required', 'workreap_api')
			);

			foreach ($required as $key => $value) {
				if (empty($request[$key])) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				}
			}

			$post_id	= !empty($request['id']) ? esc_attr($request['id']) : '';
			$status		= !empty($request['status']) ? esc_attr($request['status']) : '';

			$update_post			= array();
			$update					= workreap_save_service_status($post_id, $status);
			if ($update) {
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Successfully! update portfolio status', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Portfolio status is not updated.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
		 * Add/Update portfolio
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_portfolio($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			do_action('workreap_check_post_author_status', $linked_profile);
			do_action('workreap_check_post_author_identity_status', $linked_profile);

			$ppt_option = $total_limit	= '';
			$json = $params_array	= array();

			if (function_exists('fw_get_db_settings_option')) {
				$ppt_option		= fw_get_db_settings_option('ppt_template');
				$total_limit	= fw_get_db_settings_option('default_portfolio_images');
			}

			$total_limit	= !empty($total_limit) ? intval($total_limit) : 100;
			$required 		= array('title'	=> esc_html__('Portfolio title is required', 'workreap_api'));
			$required		= apply_filters('workreap_filter_portfolio_required_fields', $required);

			foreach ($required as $key => $value) {
				if (empty($required[$key])) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203);
				}
			}

			$title			= !empty($request['title']) ? $request['title'] : '';
			$description	= !empty($request['description']) ?  $request['description'] : '';
			$categories		= !empty($request['categories']) ?  json_decode($request['categories']) : array();
			$videos			= !empty($request['videos']) ? json_decode($request['videos']) : array();
			
			if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
				$current 	= !empty($request['id']) ? esc_attr($request['id']) : '';

				$post_author = get_post_field('post_author', $current);
				$post_id 	 = $current;
				$status 	 = get_post_status($post_id);

				if (intval($post_author) === intval($user_id)) {
					$portfolio_post = array(
						'ID' 			=> $current,
						'post_title' 	=> $title,
						'post_content' 	=> $description,
						'post_status' 	=> $status,
					);

					wp_update_post($portfolio_post);
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				//Create Post
				$user_post = array(
					'post_title'    => wp_strip_all_tags($title),
					'post_status'   => 'publish',
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'wt_portfolio',
				);

				$post_id    		= wp_insert_post($user_post);

				if (empty($_FILES['gallery_imgs0'])) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('At-least one portfolio image is required', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				//Prepare Params
				$params_array['user_role'] 		= apply_filters('workreap_get_user_type', $user_id);
				$params_array['type'] 			= 'portfolio_upload';
				$params_array['user_identity'] 	= $user_id;
				//child theme : update extra settings
				do_action('wt_process_portfolio_upload', $params_array);
			}

			if ($post_id) {
				if (!empty($ppt_option) && $ppt_option === 'enable') {
					$ppt_template		= !empty($request['ppt_template']) ? $request['ppt_template'] : '';
					update_post_meta($post_id, 'ppt_template', $ppt_template);
				}

				$gallery_imgs		= array();
				$documents			= array();
				$zip_files			= array();

				/* work with gallery images */
				$total_gallery 		= !empty($request['gallery_size']) ? $request['gallery_size'] : 0;
				if (!empty($_FILES) && $total_gallery != 0) {

					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$counter	= 0;
					for ($x = 0; $x < $total_gallery; $x++) {
						$submitted_files = $_FILES['gallery_imgs' . $x];
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
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']				= wp_get_attachment_url($attach_id);
						$attachments['name']			= get_the_title($attach_id);
						$gallery_imgs[]					= $attachments;
					}
					
				}

				if (!empty($gallery_imgs[0]['attachment_id'])) {
					set_post_thumbnail($post_id, $gallery_imgs[0]['attachment_id']);
				}
				/* work with document */
				$total_documents 		= !empty($request['documents_size']) ? $request['documents_size'] : 0;
				if (!empty($_FILES) && $total_documents != 0) {

					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$counter	= 0;
					for ($x = 0; $x < $total_documents; $x++) {
						$submitted_files = $_FILES['documents' . $x];
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
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']				= wp_get_attachment_url($attach_id);
						$attachments['name']			= get_the_title($attach_id);
						$documents[]					= $attachments;
 					}
				}
				/* work with zip files */
				$total_zip_files 		= !empty($request['zip_files_size']) ? $request['zip_files_size'] : 0;
				if (!empty($_FILES) && $total_zip_files != 0) {

					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$counter	= 0;
					for ($x = 0; $x < $total_zip_files; $x++) {
						$submitted_files = $_FILES['zip_files' . $x];
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
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']				= wp_get_attachment_url($attach_id);
						$zip_files[]					= $attachments;
					}
				}

				if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
					$gallery_imgs_	= array();
					$previous_gallery_images 		= !empty($request['previous_images']) ? json_decode($request['previous_images']) : array();
					$previous_previous_documents	= !empty($request['previous_documents']) ? json_decode($request['previous_documents']) : array();

					$array_one	= array(
						'previous_gallery_images'	=> $previous_gallery_images,
						'previous_previous_documents'	=> $previous_previous_documents,
					);
				 
					if(!empty($previous_gallery_images)){
						foreach($previous_gallery_images as $image){
							$gallery_imgs1['attachment_id']	=  $image->attachment_id;
							$gallery_imgs1['url']			=  $image->url;
							$gallery_imgs1['name']			=  get_the_title($image->attachment_id);
							$gallery_imgs[]					= $gallery_imgs1;
						}
						
					}
					if(!empty($previous_previous_documents)){
						foreach($previous_previous_documents as $document){
 
							$documents1['attachment_id']	=  $document->attachment_id;
							$documents1['url']				=  $document->url;
							$documents1['name']				=  get_the_title($document->attachment_id);
							$documents[]					= $documents1;
						}
					}			
				}
				$custom_link	= !empty($request['custom_link']) ? $request['custom_link'] : '';

				if (!empty($categories)) {
					wp_set_post_terms($post_id, $categories, 'portfolio_categories');
				}

				if (!empty($request['tags'])) {
					wp_set_post_terms($post_id, $request['tags'], 'portfolio_tags');
				}

				//update unyson meta
				$fw_options 					= array();
				$fw_options['custom_link']  	= $custom_link;
				$fw_options['gallery_imgs']    	= $gallery_imgs;
				$fw_options['documents']    	= $documents;
				$fw_options['zip_attachments']  = $zip_files;
				$fw_options['videos']    		= $videos;

				fw_set_db_post_option($post_id, null, $fw_options);

				if (isset($request['submit_type']) && $request['submit_type'] === 'update') {
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your portfolio has been updated', 'workreap_api');
				} else {
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your portfolio has been added.', 'workreap_api');
				}
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occur, please try again later', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		public function post_portfolio_comments($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

 			$post_id		= !empty($request['post_id']) ?  intval($request['post_id']) : '';
 			$content		= !empty($request['comment_content']) ?  esc_html($request['comment_content']) : '';
 			$comment_parent	= !empty($request['comment_parent']) ?  intval($request['comment_parent']) : '';
 			$user_id		= !empty($request['user_id']) ?  intval($request['user_id']) : '';
  
 			$validation_fields  = array(
				'user_id'  		 	=> esc_html__('User id required.','workreap_api'),
  				'post_id'          	=> esc_html__('Post id is required.','workreap_api'),
				'comment_content'   => esc_html__('Write something in comment section.','workreap_api'),
			);
	
			foreach($validation_fields as $key => $validation_field ){
				if( empty($request[$key]) ){
					$json['message_desc'] 		= $validation_field;
 					return new WP_REST_Response($json, 203);
				}
			}
			$user_info		= !empty($user_id) ? get_user_by( 'id', $user_id ) : '';  
			$comment_author	= !empty($user_info) ? $user_info->user_login : '';
			$user_email		= !empty($user_info) ? $user_info->user_email : '';

			$comment_data	= array(
				'comment_post_ID'      => $post_id,
				'comment_content'      => $content,
 				'user_id'              => $user_id,
				'comment_author'       => $comment_author,
				'comment_author_email' => $user_email,
 			);
			$comment_data['comment_author']			= $comment_author;
			$comment_data['comment_author_email']	= $user_email;
 
			if($comment_author){
				$comment_data['comment_parent']	= $comment_parent;
			}

			$comment_id = wp_insert_comment( $comment_data );
			if ( ! is_wp_error( $comment_id ) ) {
				$json['type'] 		= 'success';
				$json['title'] 		= esc_html__('success','workreap_api');
				$json['message'] 	= esc_html__("Comment has been updated.", 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['title'] 		= esc_html__('Failed.!','workreap_api');
				$json['message'] 	= esc_html__("You're not allowed to remove this portfolio", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}  
		}

		/**
		 * Delete service
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function delete_portfolio($data)
		{
			$headers    			= $data->get_headers();
			$request     			= !empty($data->get_params()) ? $data->get_params() : array();
			$request['authToken']  	= !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
			$response 				= api_authentication($request);
			if (!empty($response) && $response['type'] == 'error') {
				return new WP_REST_Response($response, 203);
			}

			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$portfolio_id	= !empty($request['id']) ?  $request['id'] : '';
			$items			= $itm			= array();

			if (empty($portfolio_id)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Portfolio ID is required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
			$post_author	= get_post_field('post_author', $portfolio_id);

			if (!empty($post_author) && $post_author != $user_id) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__("You're not allowed to remove this portfolio", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
			if (!empty($portfolio_id)) {
				wp_delete_post($portfolio_id);
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Portfolio removed successfully.', 'workreap_api');
				return new WP_REST_Response($json, 200);
			}
		}

		/**
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_portfolios($request)
		{

			$portfolio_id	= !empty($request['portfolio_id']) ? intval($request['portfolio_id']) : '';
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$listing_type	= !empty($request['listing_type']) ? esc_attr($request['listing_type']) : '';
			$keyword		= !empty($request['keyword']) ? esc_html($request['keyword']) : '';
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$json			=  $items = array();
			$count_post 	= 0;
			if ($listing_type === 'single') {

				$query_args = array(
					'post_type' 	 	  	=> 'wt_portfolio',
					'post__in' 		 	  	=> array($portfolio_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1,
					'ignore_sticky_posts' 	=> 1,
					's' 					=> $keyword,
					'paged' 				=> $page_number,
					'posts_per_page' 		=> $limit,
				);

				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif (!empty($listing_type) && $listing_type === 'latest') {
				$order		 	= 'DESC';
				$query_args 	= array(
					'posts_per_page' 	  	=> -1,
					'post_type' 	 	  	=> 'wt_portfolio',
					'post_status' 	 	  	=> 'publish',
					'author'				=> $user_id,
					's' 					=> $keyword,
					'paged' 				=> $page_number,
					'posts_per_page' 		=> $limit,

				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif (!empty($listing_type) && $listing_type === 'listing') {
				$order		 	= 'DESC';
				$query_args 	= array(
					'posts_per_page' 	  	=> -1,
					'post_type' 	 	  	=> 'wt_portfolio',
					'post_status' 	 	  	=> array('draft', 'publish'),
					'author'				=> $user_id,
					's' 					=> $keyword,
					'paged' 				=> $page_number,
					'posts_per_page' 		=> $limit
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}

			//Start Query working.
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$portfolio_id = get_the_ID();
					do_action('workreap_post_views', $portfolio_id, 'portfolio_views');
					$gallery_imgs = $documents = $db_videos = $zip_attachments = array();
					$custom_link	= '';
					if (function_exists('fw_get_db_post_option')) {
						$gallery_imgs   	= fw_get_db_post_option($portfolio_id, 'gallery_imgs');
						$zip_attachments   	= fw_get_db_post_option($portfolio_id, 'zip_attachments');
						$documents   		= fw_get_db_post_option($portfolio_id, 'documents');
						$db_videos   		= fw_get_db_post_option($portfolio_id, 'videos');
						$custom_link   		= fw_get_db_post_option($portfolio_id, 'custom_link');
					}

					/* categories */
					$categories = array();
					$db_portfolio_cats 		= wp_get_post_terms($portfolio_id, 'portfolio_categories');
					if (!empty($db_portfolio_cats)) {
						foreach ($db_portfolio_cats as $key => $catsObj) {
							$categories[] = array(
								'id' => $catsObj->term_id,
								'name' => $catsObj->name,
								'slug' => $catsObj->slug,
							);
						}
					} 
					$db_portfolio_tags	= wp_get_post_terms($portfolio_id, 'portfolio_tags');

					if (!empty($db_portfolio_tags)) {
						foreach ($db_portfolio_tags as $key => $portObj) {
							$portfolio_tags[] = array(
								'id' 	=> $portObj->term_id,
								'name'	=> $portObj->name,
								'slug' 	=> $portObj->slug,
							);
						}
					} 

					/* comments */
					$comments_arr = array();
					$coment_per_page = !empty($request['comment_per_page']) ? $request['comment_per_page'] : get_option('comments_per_page');
					$parent_comment_args = array(
						'post_id' 			=> $portfolio_id,
						'number'      		=> $coment_per_page,
						'order'       		=> 'ASC',
						'orderby' 			=> 'comment_date',
						'status'      		=> 'approve',
						'parent'      		=> 0
					);
					$comments = get_comments($parent_comment_args);
					
					//==============
					foreach ($comments as $comments_val) {
						$userId = get_comment($comments_val->comment_ID)->user_id;
						/* if child comments */
						$childcomments = get_comments(array(
							'post_id'   => $portfolio_id,
							'status'    => 'approve',
							'order'     => 'DESC',
							'parent'    => $comments_val->comment_ID,
						));

						$child_coments_arr = array();
						if (!empty($childcomments)) {
 							foreach ($childcomments as $child_comments_val) {
								$user_ID = get_comment($child_comments_val->comment_ID)->user_id;

								$child_coments_arr[] = array(
									'id' 					=> $child_comments_val->comment_ID,
									'user_id' 				=> $child_comments_val->user_id,
									'comment_parent_id' 	=> $comments_val->comment_ID,
									'comment_post_ID' 		=> $child_comments_val->comment_post_ID,
									'comment_author' 		=> $child_comments_val->comment_author,
									'comment_author_email' 	=> $child_comments_val->comment_author_email,
									'comment_date' 			=> $child_comments_val->comment_date,
									'comment_content' 		=> $child_comments_val->comment_content,
									'avatar' 				=> get_avatar_url($user_ID, ['size' => '80']),
								);
							}
						}

						/* parent comments */
						$comments_arr[] = array(
							'id' 					=> $comments_val->comment_ID,
							'user_id' 				=> $comments_val->user_id,
							'comment_post_ID' 		=> $comments_val->comment_post_ID,
							'comment_author' 		=> $comments_val->comment_author,
							'comment_author_email' 	=> $comments_val->comment_author_email,
							'comment_date' 			=> $comments_val->comment_date,
							'comment_content' 		=> $comments_val->comment_content,
							'avatar' 				=> get_avatar_url($userId, ['size' => '80']),
							'comment_child' 		=> $child_coments_arr,
						);
					}

					$portfolio_img 		= apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 225, 'height' => 225), $portfolio_id),
						array('width' => 100, 'height' => 100)
					);

					$items[]				= array(
						'ID' 				=> $portfolio_id,
						'title' 			=> get_the_title($portfolio_id),
						'post_author' 		=> get_post_field('post_author', $portfolio_id),
						'description' 		=> get_post_field('post_content', $portfolio_id),
						'portfolio_img' 	=> $portfolio_img,
						'status' 			=> get_post_status($portfolio_id),
						'categories' 		=> $categories,
						'portfolio_tags' 	=> $portfolio_tags,
						'documents' 		=> $documents,
						'db_videos' 		=> $db_videos,
						'custom_link' 		=> $custom_link,
						'gallery_imgs' 		=> $gallery_imgs,
						'zip_attachments' 	=> $zip_attachments,
						'comments' 			=> $comments_arr,
					);
				}

				$json['type']				= 'success';
				$json['portfolio_count']	= $count_post;
				$json['portfolios']			= maybe_unserialize($items);
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']			= 'error';
				$json['message']		= esc_html__('Some error occur, please try again later', 'workreap_api');
				$json['portfolios']		= array();
				return new WP_REST_Response($json, 203);
			}
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidAppGetPortfoliosRoutes;
		$controller->register_routes();
	}
);


