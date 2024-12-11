<?php
if (!class_exists('AndroidAppGetEmployersRoutes')) {

	class AndroidAppGetEmployersRoutes extends WP_REST_Controller
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
				'/' . $base . '/get_employers',
				array(
					array(
						'methods' => WP_REST_Server::READABLE,
						'callback' => array(&$this, 'get_listing'),
						'args' => array(),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * Get Listings employers
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_listing($request)
		{
			$json = $items = $item = array();
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$profile_id		= !empty($request['profile_id']) ? intval($request['profile_id']) : '';
			$today 			= time();

			$following_employers	= array();
			if (!empty($profile_id)) {
				$following_employers	= get_post_meta($profile_id, '_following_employers', true);
			}

			if ($request['listing_type'] === 'single') {

				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'employers',
					'post__in' 		 	  	=> array($profile_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}  elseif ($request['listing_type'] === 'featured') {

				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'employers',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);

				//order by pro member
				$query_args['orderby']  	= 'meta_value_num';
				$query_args['order'] 		= 'DESC';
				$query_args['meta_key'] 	= '_featured_timestamp';
				$meta_query_args[] = array(
					'key' 		=> '_featured_timestamp',
					'compare' 	=> '>=',
					'value'		=> $today
				);

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}  elseif ($request['listing_type'] === 'latest') {
				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  	=> $limit,
					'post_type' 	 	  	=> 'employers',
					'paged' 		 	  	=> $page_number,
					'post_status' 	 	  	=> 'publish',
					'order'					=> 'ID',
					'orderby'				=> $order,
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}  elseif ($request['listing_type'] === 'favorite') {
				$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				$wishlist 			= get_post_meta($linked_profile, '_following_employers', true);
				$wishlist			= !empty($wishlist) ? $wishlist : array();
				if (!empty($wishlist)) {
					$order		 = 'DESC';
					$query_args = array(
						'posts_per_page' 	  	=> $limit,
						'post_type' 	 	  	=> 'employers',
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
					$json['message']	= esc_html__('You have no employers in your favorite list.', 'workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			}  elseif ($request['listing_type'] === 'search') {

				//Search parameters
				$keyword 		= !empty($request['keyword']) ? $request['keyword'] : '';
				$employees 		= !empty($request['employees']) ? json_decode($request['employees'], true) : '';
				$departments 	= !empty($request['department']) ? json_decode($request['department'], true) : array();
				$locations 	 	= !empty($request['location']) ? json_decode($request['location'], true) : array();

				$tax_query_args  = array();
				$meta_query_args = array();

				if (is_tax('department') && empty($departments)) {
					$dept = $wp_query->get_queried_object();
					if (!empty($dept->slug)) {
						$departments = array($dept->slug);
					}
				}
				//departments
				if (!empty($departments[0]) && is_array($departments)) {
					$query_relation = array('relation' => 'OR',);
					$department_args  = array();

					foreach ($departments as $key => $department) {
						$department_args[] = array(
							'taxonomy' => 'department',
							'field'    => 'slug',
							'terms'    => $department,
						);
					}

					$tax_query_args[] = array_merge($query_relation, $department_args);
				}

				if (is_tax('locations') && empty($locations)) {
					$location = $wp_query->get_queried_object();
					if (!empty($location->slug)) {
						$locations = array($location->slug);
					}
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

				//no of employees
				if (!empty($employees)) {
					$meta_query_args[] = array(
						'key'		=> '_employees',
						'value' 	=> $employees,
						'type' 		=> 'NUMERIC',
						'compare' 	=> '='
					);
				}

				//default
				$meta_query_args[] = array(
					'key' 		=> '_profile_blocked',
					'value' 	=> 'off',
					'compare'	=> '='
				);

				$meta_query_args[] = array(
					'key' 		=> '_is_verified',
					'value'		=> 'yes',
					'compare'	=> '='
				); 

				$query_args = array(
					'posts_per_page'      => $limit,
					'paged'			      => $page_number,
					'post_type' 	      => 'employers',
					'post_status'	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);

				//keyword search
				if (!empty($keyword)) {
					$query_args['s']	=  $keyword;
				}

				//Taxonomy Query
				if (!empty($tax_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);
				}

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}

				$query		= new WP_Query($query_args);
				$count_post	= $query->found_posts;
 
 			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Please provide api type', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			/* Start Query working */
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$employer_profile_id = get_the_ID();

					$item['favorit']			= 'no';
					if (!empty($following_employers)  &&  in_array($employer_profile_id, $following_employers)) {
						$item['favorit']			= 'yes';
					}

					if (function_exists('workreap_get_linked_profile_id')) {
						$user_id	= workreap_get_linked_profile_id($employer_profile_id, 'post');
					} else {
						$user_id	= get_post_field('post_author', $employer_profile_id);
					}
					$user_id						= !empty($user_id) ?  $user_id  : 0;
					$url							= !empty(get_the_permalink($employer_profile_id)) ? esc_url(get_the_permalink($employer_profile_id)) : '';
					$item['name']					= !empty(get_the_title()) ? get_the_title() : '';
					$item['user_id']				= $user_id;
					$item['employ_id']				= $user_id;
					$item['profile_id']				= $employer_profile_id;
					$item['company_link'] 			= $url;
					$item['followers_count'] 		= apply_filters('workreap_api_get_followers', $employer_profile_id, 'followers_count');
					$item['followers'] 				= apply_filters('workreap_api_get_followers', $employer_profile_id, 'followers');
					$projects 						= apply_filters('workreap_api_get_employer_projects', $user_id);
					$item['jobs_count']				= !empty($projects['count']) ? $projects['count'] : 0;
					$item['jobs']					= !empty($projects['jobs']) ? $projects['jobs'] : array();

					$employer_banner	= apply_filters(
						'workreap_employer_banner_fallback',
						workreap_get_employer_banner(array('width' => 352, 'height' => 200), $employer_profile_id),
						array('width' => 352, 'height' => 200)
					);

					$employer_avatar 	= apply_filters(
						'workreap_employer_avatar_fallback',
						workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_profile_id),
						array('width' => 100, 'height' => 100)
					);

					$item['profile_img'] 	= !empty($employer_avatar) ? esc_url($employer_avatar) : '';
					$item['banner_img'] 	= !empty($employer_banner) ? esc_url($employer_banner) : '';
					$item['User_profileID'] = $employer_profile_id;
					$item['employer_des'] 	= get_the_content();
					$item['link']			= esc_url(get_the_permalink());

					if (function_exists('fw_get_db_post_option')) {
						$address	= fw_get_db_post_option($employer_profile_id, 'address', true);
						$longitude	= fw_get_db_post_option($employer_profile_id, 'longitude', true);
						$latitude	= fw_get_db_post_option($employer_profile_id, 'latitude', true);
						$tag_line	= fw_get_db_post_option($employer_profile_id, 'tag_line', true);
					}

					$item['_longitude'] 	= !empty($longitude) ? $longitude : '';
					$item['_latitude'] 		= !empty($latitude) ? $latitude : '';
					$item['_address'] 		= !empty($address) ? $address : '';
					$item['_tag_line'] 		= !empty($tag_line) ? $tag_line : '';
					$is_verified			= get_post_meta($employer_profile_id, '_is_verified', true);
					$item['_is_verified'] 	= !empty($is_verified) ? $is_verified : '';
					$_featured_timestamp	= get_post_meta($employer_profile_id, '_featured_timestamp', true);

					if (intval($_featured_timestamp) >= $today) {
						$item['_featured_timestamp']['class']	= 'wt-featured';
					}
					$item['_featured_timestamp']['class']	= !empty($item['_featured_timestamp']['class']) ? $item['_featured_timestamp']['class'] : '';

					if (function_exists('workreap_get_location')) {
						$country	= workreap_get_location($employer_profile_id);
					}

					$item['location']['flag']		= !empty($country['flag']) ? $country['flag']	: '';
					$item['location']['_country']	= !empty($country['_country']) ? $country['_country']	: '';

					$item['count_totals']   = !empty($count_post) ? intval($count_post) : 0;
					$items[]				= maybe_unserialize($item);
				}
				wp_reset_postdata();

				$json['type'] 			= "success";
				$json['employers'] 		= $items;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']			= 'success';
				$json['message']		= esc_html__('Employers are not found.', 'workreap_api');
				$json['employers']		= array();
				return new WP_REST_Response($json, 200);
			}
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidAppGetEmployersRoutes;
		$controller->register_routes();
	}
);
