<?php
if (!class_exists('AndroidAppGetFreelancersRoutes')) {

	class AndroidAppGetFreelancersRoutes extends WP_REST_Controller
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
				'/' . $base . '/get_freelancers',
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
		 * Get Listings
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_listing($request)
		{
			$limit			= !empty($request['show_posts']) ? intval($request['show_posts']) : 10;
			$page_number	= !empty($request['page_number']) ? intval($request['page_number']) : 1;
			$profile_id		= !empty($request['profile_id']) ? intval($request['profile_id']) : 0;
			$listing_type	= !empty($request['listing_type']) ? $request['listing_type'] : '';
			$date_formate	= get_option('date_format');
			$json = $item 	= $items = $saved_freelancers = array();
			$protocol 		= is_ssl() ? 'https:' : 'http:';
			$today 			= time();
			$count_post 	= 0;

			if (!empty($profile_id)) {
				$saved_freelancers	= get_post_meta($profile_id, '_saved_freelancers', true);
			}

			if ($listing_type === 'single') {
				$profile_id	= workreap_get_linked_profile_id($profile_id);

				$query_args = array(
					'post_type' 	 	  	=> 'freelancers',
					'post__in' 		 	  	=> array($profile_id),
					'paged' 		 	  	=> $page_number,
					'posts_per_page' 	  	=> $limit,
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);

				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif ($listing_type === 'featured') {

				$hide_profiles  = array();
				if (function_exists('fw_get_db_settings_option')) {
					$hide_profiles = fw_get_db_settings_option('hide_profiles', $default_value = 'no');
				}

				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'freelancers',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);

				/* query for featured */
				$meta_query_args[] = array(
					'key'         => '_featured_timestamp',
					'value'       => 1,
					'compare'     => '=',
				);

				$meta_query_args[] = array(
					'relation'  => 'AND',
					array(
						'key'       => '_is_verified',
						'compare'   => 'EXISTS'
					),
					array(
						'key'       => '_is_verified',
						'value'		=> '',
						'compare'	=> '!='
					),
					array(
						'key'       => '_is_verified',
						'value'		=> 'yes',
						'compare'	=> '='
					),
				);

				/* Profile strength */
				if (!empty($hide_profiles['gadget']) && $hide_profiles['gadget'] === 'yes') {
					if (!empty($hide_profiles['yes']['define_percentage'])) {
						$meta_query_args[] = array(
							'key' 			=> '_profile_health_filter',
							'value' 		=> intval($hide_profiles['yes']['define_percentage']),
							'compare' 		=> '>=',
							'type'			=> 'NUMERIC'
						);
					}
				}

				/* order by pro member */
				$query_args['meta_key'] = '_featured_timestamp';
				$query_args['orderby']	 = array(
					'meta_value' 	=> 'DESC',
					'ID'      		=> 'DESC'
				);

				/* Meta Query */
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif ($listing_type === 'latest') {

				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  	=> $limit,
					'post_type' 	 	  	=> 'freelancers',
					'paged' 		 	  	=> $page_number,
					'post_status' 	 	  	=> 'publish',
					'order'					=> 'ID',
					'orderby'				=> $order,
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif ($listing_type === 'favorite') {

				$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				$wishlist 			= get_post_meta($linked_profile, '_saved_freelancers', true);
				$wishlist			= !empty($wishlist)  && is_array($wishlist) ? $wishlist : array();

				if (!empty($wishlist)) {
					$order		 = 'DESC';
					$query_args = array(
						'posts_per_page' 	  	=> $limit,
						'post_type' 	 	  	=> 'freelancers',
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
					$json['message']	= esc_html__('You have no freelancer in your favorite list.', 'workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			} elseif ($listing_type === 'search') {

				$freelancertype			= fw_get_db_settings_option('multiselect_freelancertype', $default_value = null);
				$keyword 				= !empty($request['keyword']) ? $request['keyword'] : '';
				$languages 				= !empty($request['language']) ? json_decode($request['language'], true) : array();
				$locations 	 			= !empty($request['location']) ? json_decode($request['location'], true) : array();
				$skills					= !empty($request['skills']) ? json_decode($request['skills'], true) : array();
				$duration 				= !empty($request['duration']) ? $request['duration'] : '';
				$type 					= !empty($request['type']) ? json_decode($request['type'], true) : array();
				$english_level  		= !empty($request['english_level']) ? json_decode($request['english_level'], true) : array();
				$hourly_rate    		= !empty($request['hourly_rate']) ? explode('-', $request['hourly_rate']) : '';
				$specialization			= !empty($request['specialization']) ? json_decode($request['specialization'], true) : array();
				$industrial_experience	= !empty($request['industrial_experience']) ? json_decode($request['industrial_experience'], true) : array();
				$hide_profiles  		= $tax_query_args  = $meta_query_args = array();

				if (function_exists('fw_get_db_settings_option')) {
					$hide_profiles = fw_get_db_settings_option('hide_profiles', $default_value = 'no');
				}

				/* hourly rate */
				$hourly_rate_start = 0;
				$hourly_rate_end   = 1000;
				if (!empty($hourly_rate)) {
					$hourly_rate_start = isset($hourly_rate[0]) ? intval($hourly_rate[0]) : 0;
					$hourly_rate_end   = !empty($hourly_rate[1]) ? intval($hourly_rate[1]) : 1000;
				}

				/* Languages */
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

				/* Profile strength */
				if (!empty($hide_profiles['gadget']) && $hide_profiles['gadget'] === 'yes') {
					if (!empty($hide_profiles['yes']['define_percentage'])) {
						$meta_query_args[] = array(
							'key' 			=> '_profile_health_filter',
							'value' 		=> intval($hide_profiles['yes']['define_percentage']),
							'compare' 		=> '>=',
							'type'			=> 'NUMERIC'
						);
					}
				}

				/* Locations */
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

				/* skills */
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

				/* industrial_experience */
				if (!empty($industrial_experience[0]) && is_array($industrial_experience)) {
					$query_relation = array('relation' => 'OR',);
					$industrial_experience_args  = array();
					foreach ($industrial_experience as $key => $experience) {
						$industrial_experience_args[] = array(
							'taxonomy' => 'wt-industrial-experience',
							'field'    => 'slug',
							'terms'    => $experience,
						);
					}
					$tax_query_args[] = array_merge($query_relation, $industrial_experience_args);
				}

				/* Freelancer type */
				if (!empty($freelancertype) && $freelancertype === 'enable' && !empty($type)) {
					if (!empty($type) && !empty($type[0]) && is_array($type)) {

						$query_relation = array('relation' => 'OR',);
						$sub_types_args = array();
						foreach ($type as $key => $value) {
							$sub_types_args[] = array(
								'key' 		=> '_freelancer_type',
								'value' 	=> strval($value),
								'compare' 	=> 'LIKE'
							);
						}
						$meta_query_args[] = array_merge($query_relation, $sub_types_args);
					}
				} else if (!empty($type)) {
					foreach ($type as $key => $item_type) {
						$meta_query_args[] = array(
							'key' 		=> '_freelancer_type',
							'value' 	=> strval($item_type),
							'compare' 	=> 'LIKE'
						);
					}
				}

				/* English Level */
				if (!empty($english_level)) {
					$meta_query_args[] = array(
						'key' 			=> '_english_level',
						'value' 		=> $english_level,
						'compare' 		=> 'IN'
					);
				}

				/* Hourly Rate */
				if (!empty($hourly_rate)) {
					$meta_query_args[] = array(
						'key' 				=> '_perhour_rate',
						'value' 			=> array($hourly_rate_start, $hourly_rate_end),
						'type' 				=> 'NUMERIC',
						'compare' 			=> 'BETWEEN'
					);
				}

				$meta_query_args[] = array(
					'key' 			=> '_profile_blocked',
					'value' 		=> 'off',
					'compare' 		=> '='
				);

				$meta_query_args[] = array(
					'relation'  => 'AND',
					array(
						'key'       => '_is_verified',
						'compare'   => 'EXISTS'
					),
					array(
						'key'       => '_is_verified',
						'value'		=> array('yes'),
						'compare'	=> 'IN'
					),
				);

				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'freelancers',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'any',
					'ignore_sticky_posts' => true
				);

				/* keyword search */
				if (!empty($keyword)) {
					$query_args['s']	=  $keyword;
				}

				/* order by pro member */
				$query_args['meta_key'] = '_featured_timestamp';
				$query_args['orderby']	 = array(
					'meta_value' 	=> 'DESC',
					'ID'      		=> 'DESC'
				);

				/* Taxonomy Query */
				if (!empty($tax_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);
				}

				/* Meta Query */
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}

				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Please provide api type', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}


			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$freelancer_id = get_the_ID();

					$favorit_freelancer			= 'no';
					if (!empty($saved_freelancers)  &&  in_array($freelancer_id, $saved_freelancers)) {
						$favorit_freelancer			= 'yes';
					}

					$is_featured = get_post_meta($freelancer_id, '_featured_timestamp', true);
					$is_featured = !empty($is_featured) ? 'yes' : 'no';
					if (function_exists('workreap_get_linked_profile_id')) {
						$user_id	= workreap_get_linked_profile_id($freelancer_id, 'post');
					} else {
						$user_id	= get_post_field('post_author', $freelancer_id);
					}

					$user_id						= !empty($user_id) ? intval($user_id) : 0;
					$url							= !empty(get_the_permalink($freelancer_id)) ? esc_url(get_the_permalink($freelancer_id)) : '';
					$name_freelancer				= !empty(get_the_title()) ? get_the_title() : '';
					$user_id_freelancer				= $user_id;
					$profile_id_freelancer			= $freelancer_id;
					$content_freelancer				= get_the_content();
					$member_since_freelancer		= get_the_date($date_formate, $freelancer_id);
					$freelancer_link_freelancer 	= $url;
					$is_featured_freelancer 		= $is_featured;

					/* profile image */
					$profile_img_freelancer 		= apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_id),
						array('width' => 100, 'height' => 100)
					);

					/* banner image */
					$banner_img_freelancer	= '';
					if(function_exists('workreap_get_freelancer_banner')){
						$banner_img_freelancer	= apply_filters(
							'workreap_freelancer_banner_fallback',
							workreap_get_freelancer_banner(array('width' => 350, 'height' => 172), $freelancer_id),
							array('width' => 350, 'height' => 172)
						);
					}

					/* featured badged */
					$featured_badged = array();
					$featured_id	= workreap_is_feature_value('wt_badget', intval($user_id));
					$featured_id	= !empty($featured_id) ? intval($featured_id) : 0;
					if (empty($featured_id)) {
						$featured_badged = array(
							'badget_url' => '',
							'badget_color' => '',
						);
					} elseif (!empty($featured_id)) {
						$term	= get_term($featured_id);
						if (!empty($term)) {
							$badge_icon  = fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_icon');
							$badge_color = fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_color');
							if (!empty($badge_icon['url'])) {
								$color = !empty($badge_color) ? $badge_color : '#ff5851';
								$featured_badged = array(
									'badget_url' => workreap_add_http($badge_icon['url']),
									'badget_color' => esc_attr($color),
								);
							} else {
								$featured_badged = array(
									'badget_url' => '',
									'badget_color' => '',
								);
							}
						} else {
							$featured_badged = array(
								'badget_url' => '',
								'badget_color' => '',
							);
						}
					}

					/* earnings */
					$earnings						= workreap_get_sum_payments_freelancer($user_id, 'completed', 'amount');
					$earnings						= !empty($earnings) ?   $earnings : 0;
					$total_earnings_freelancer		= $earnings;
					if (function_exists('workreap_price_format')) {
						$total_earnings_freelancer		= workreap_price_format($earnings, 'return');
					}

					$is_verified				= get_post_meta($freelancer_id, '_is_verified', true);
					$_is_verified 				= !empty($is_verified) ? $is_verified : '';
					$featured_timestamp			= get_post_meta($freelancer_id, '_featured_timestamp', true);
					$featured_expiry         	= get_post_meta($freelancer_id, '_expiry_string', true);
					$featured_expiry       		= !empty($featured_expiry) ? $featured_expiry : 0;
					$_featured_timestamp 		= !empty($featured_timestamp) && $featured_expiry > $today ? 'wt-featured' : array();
					$rating_filter				= get_post_meta($freelancer_id, 'rating_filter', true);
					$rating_filter_freelancer	= !empty($rating_filter) ? $rating_filter : '';
					$review_data				= get_post_meta($freelancer_id, 'review_data', true);
					$review_data 				= !empty($review_data) ? $review_data : array();

					$wt_average_rating		= $wt_total_rating		= $wt_total_percentage	= 0;
					if (!empty($review_data)) {
						$wt_average_rating		= !empty($review_data['wt_average_rating']) ? $review_data['wt_average_rating'] : 0;
						$wt_total_rating		= !empty($review_data['wt_total_rating']) ? $review_data['wt_total_rating'] : 0;
						$wt_total_percentage	= !empty($review_data['wt_total_percentage']) ? $review_data['wt_total_percentage'] : 0;
					}

					$_longitude 	= $_latitude = $_address = $_tag_line = $_gender = $_perhour_rate = $_english_level = '';
					$_educations 	= $_experience = $_awards = $_projects = $location_freelancer = array();
					if (function_exists('fw_get_db_term_option')) {
						$education 	= fw_get_db_post_option($freelancer_id, 'education', true);
						$experience = fw_get_db_post_option($freelancer_id, 'experience', true);
						$awards		= fw_get_db_post_option($freelancer_id, 'awards', true);
						$projects	= fw_get_db_post_option($freelancer_id, 'projects', true);
						$specialization = fw_get_db_post_option($freelancer_id, 'specialization', true);

						$address	= fw_get_db_post_option($freelancer_id, 'address');
						$longitude	= fw_get_db_post_option($freelancer_id, 'longitude');
						$latitude	= fw_get_db_post_option($freelancer_id, 'latitude');
						$tag_line	= fw_get_db_post_option($freelancer_id, 'tag_line');
						$gender		= fw_get_db_post_option($freelancer_id, 'gender');
						$rates		= fw_get_db_post_option($freelancer_id, '_perhour_rate');
						$eng_level	= fw_get_db_post_option($freelancer_id, '_english_level');

						$_longitude 	= !empty($longitude) ? $longitude : '';
						$_latitude 		= !empty($latitude) ? $latitude : '';
						$_address 		= !empty($address) ? $address : '';
						$_tag_line 		= !empty($tag_line) ? stripslashes($tag_line) : '';
						$_gender 		= !empty($gender) ? $gender : '';
						$_perhour_rate 	= !empty($rates) ? html_entity_decode(workreap_price_format($rates, 'return')) : '';
						$_english_level = !empty($eng_level) ? $eng_level : '';

						/* education */
						$edu	= array();
						$awd	= array();
						$proj	= array();
						if (!empty($education) && is_array($education)) {
							foreach ($education as $keys => $values) {
								foreach ($values as $key_main => $val) {
									if ($key_main === 'startdate' || $key_main === 'enddate' || $key_main === 'date') {
										$edu[$keys][$key_main]	= date_i18n($date_formate, strtotime($val));
									} else {
										$edu[$keys][$key_main]	= stripslashes($val);
									}
								}
							}
							$_educations	= $edu;
						} else {
							$_educations	= $edu;
						}

						/* experience */
						$exp	= array();
						if (!empty($experience) && is_array($experience)) {
							foreach ($experience as $keys => $values) {
								foreach ($values as $key_main => $val) {
									if ($key_main === 'startdate' || $key_main === 'enddate' || $key_main === 'date') {
										$exp[$keys][$key_main]	= date_i18n($date_formate, strtotime($val));
									} else {
										$exp[$keys][$key_main]	= $val;
									}
								}
							}
							$_experience	= $exp;
						} else {
							$_experience	= $exp;
						}

						/* awards */
						if (!empty($awards) && is_array($awards)) {
							foreach ($awards as $keys => $values) {
								if (empty($values['image']['url'])) {
									$values['image']	= array();
									$values['image']['url'] 			= '';
									$values['image']['attachment_id'] 	= '';
								}

								foreach ($values as $key_main => $val) {
									if ($key_main === 'startdate' || $key_main === 'enddate' || $key_main === 'date') {
										$awd[$keys][$key_main]	= date_i18n($date_formate, strtotime($val));
									} elseif ($key_main === 'image') {
										$awd[$keys][$key_main]['url']			=  !empty($val['url']) ? workreap_add_http($val['url']) : '';
										$awd[$keys][$key_main]['attachment_id']	= $val['attachment_id'];
									} else {
										$awd[$keys][$key_main]	= $val;
									}
								}
							}
							$_awards	= $awd;
						} else {
							$_awards	= $awd;
						}

						/* Projects */
						if (!empty($projects) && is_array($projects)) {
							foreach ($projects as $keys => $values) {
								if (empty($values['image'])) {
									$values['image']	= array();
									$values['image']['url'] 			= '';
									$values['image']['attachment_id'] 	= '';
								}
								foreach ($values as $key_main => $val) {

									if ($key_main === 'image') {
										$pro[$keys][$key_main]['url']			= !empty($val['url']) ? workreap_add_http($val['url']) : '';
										$pro[$keys][$key_main]['attachment_id']	= $val['attachment_id'];
									} else {
										$pro[$keys][$key_main]	= $val;
									}
								}
							}
							$_projects	= $pro;
						} else {
							$_projects	= $proj;
						}


						$args = array();
						$terms 						= wp_get_post_terms($freelancer_id, 'locations', $args);
						$countries					= !empty($terms[0]->term_id) ? intval($terms[0]->term_id) : '';
						$locations_name				= !empty($terms[0]->name) ?  $terms[0]->name  : '';
						if (!empty($locations_name)) {
							$location_freelancer['_country']			= $locations_name;
						} else {
							$location_freelancer['_country']			= '';
						}
						$icon          				= !empty($countries) ? fw_get_db_term_option($countries, 'locations', 'image') : '';
						$location_freelancer['flag'] 	= !empty($icon['url']) ? $protocol . ($icon['url']) : '';
					}

					/*  freelancer Skills */
					$skills_freelancer = $freela_skills	= array();
					if (function_exists('fw_get_db_post_option')) {
						$freela_skills  = fw_get_db_post_option($freelancer_id, 'skills', true);
					}

					$display_type	    = '';
					if (function_exists('fw_get_db_settings_option')) {
						$display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
					}

					$field_type		    = !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Years', 'workreap_api');
					if (!empty($freela_skills)) {
						$fre_skills = $freelancer_skils = array();
						foreach ($freela_skills as $key => $item_) {
							if (!empty($item_['skill'][0])) {
								$freelan_skill	= get_term_by('id', $item_['skill'][0], 'skills');
								$item_val	    = !empty($item_['value']) ? intval($item_['value']) : 0;
								if (!empty($display_type) && $display_type === 'number') {
									$percentage	= $item_val;
								} else {
									if ($item_val > 10) {
										$item_val	= 10;
									}
									$percentage	= $item_val * 10;
								}
								if (!empty($freelan_skill->name)) {
									$fre_skills['skill_val']        = $item_val;
									$fre_skills['skill_name']       = $freelan_skill->name;
									$fre_skills['skill_percent']    = $percentage;
								}
							}
							$freelancer_skils[] = $fre_skills;
						}
						$skills_freelancer         = $freelancer_skils;
					}

					/*  industrial experience */
					$industrial_exper_freelancer = $industrial_experiences_ = array();
					if (function_exists('fw_get_db_post_option')) {
						$industrial_experiences_    = fw_get_db_post_option($freelancer_id, 'industrial_experiences');
					}

					$display_type	= '';
					if (function_exists('fw_get_db_settings_option')) {
						$display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
					}

					$field_type		    = !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Years', 'workreap_api');
					if (!empty($industrial_experiences_) && is_array($industrial_experiences_)) {
						$exper = $exper_ = array();
						foreach ($industrial_experiences_ as $key => $item_) {
							if (!empty($item_['exp'][0])) {
								$industrial_experi		= get_term_by('id', $item_['exp'][0], 'wt-industrial-experience');
								$item_val	= !empty($item_['value']) ? intval($item_['value']) : 0;
								if (!empty($display_type) && $display_type === 'number') {
									$percentage	    = $item_val;
								} else {
									if ($item_val > 10) {
										$item_val	= 10;
									}
									$percentage	= $item_val * 10;
								}
								if (!empty($industrial_experi->name)) {
									$exper['name']          = $industrial_experi->name;
									$exper['percent']       = intval($percentage);
									$exper['item_val']      = $item_val;
									$exper['field_type']    = $field_type;
								}
							}
							$exper_[]                       = $exper;
						}
						$industrial_exper_freelancer           = $exper_;
					}

					/* languages */
					$languages_freelancer 		= array();
					$remove_languages		    = fw_get_db_settings_option('frc_remove_languages', $default_value = 'no');
					$db_languages				= wp_get_post_terms($freelancer_id, 'languages');
					if (!empty($db_languages) && (!empty($remove_languages) && $remove_languages === 'no')) {
						$languages_freelancer = $db_languages;
					}

					/* english level */
					$english_level_freelancer     	= '';
					$english_level_list             = worktic_english_level_list();
					$english_level	                = fw_get_db_post_option($freelancer_id, 'english_level');
					$frc_english_level			    = fw_get_db_settings_option('frc_english_level', $default_value = 'no');
					if (!empty($english_level_list[$english_level]) &&  (!empty($frc_english_level) && $frc_english_level === 'no')) {
						$english_level_freelancer = $english_level_list[$english_level];
					}

					/* specialization */
					$specializaton_freelancer 	= array();
					$experience_type	    	= fw_get_db_settings_option('display_type', $default_value = 'number');
					$field_type		        	= !empty($experience_type) && ($experience_type === 'number') ? '%' : esc_html__('Years', 'workreap_api');
					if (!empty($specialization) && is_array($specialization)) {
						$specilize		= $specilize_ =	 array();
						foreach ($specialization as $spec_item) {
							if (!empty($spec_item['spec'][0])) {
								$skill		= get_term_by('id', $spec_item['spec'][0], 'wt-specialization');
								$item_val	= !empty($spec_item['value']) ? intval($spec_item['value']) : 0;
								if (!empty($experience_type) && $experience_type === 'number') {
									$percentage	= $item_val;
								} else {
									if ($item_val > 10) {
										$item_val	= 10;
									}
									$percentage	= $item_val * 10;
								}
								if (!empty($skill->name)) {
									$specilize['specilize_name']        = $skill->name;
									$specilize['specilize_percent_val'] = $percentage;
									$specilize['specilize_type_']       = $field_type;
									$specilize['tem_val']               = $item_val;
								}
							}
							$specilize_[]       =  $specilize;
						}
						$specializaton_freelancer  = $specilize_;
					}

					/* Social profiles */
					$socialmediaurls   = $socials_profile_freelancer	= array();
					if (function_exists('fw_get_db_settings_option')) {
						$post_type			    = get_post_type($freelancer_id);
						if (!empty($post_type) && $post_type === 'employers') {
							$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
						} else {
							$socialmediaurls	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
						}
					}
					$socialmediaurl 		    = !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';
					$social_settings	        = array();
					if (function_exists('workreap_get_social_media_icons_list')) {
						$social_settings	    = workreap_get_social_media_icons_list('no');
					}

					$social_available = 'no';
					if (!empty($social_settings) && is_array($social_settings)) {
						foreach ($social_settings as $key => $val) {
							$social_url	= '';
							if (function_exists('fw_get_db_post_option')) {
								$social_url	= fw_get_db_post_option($freelancer_id, $key, null);
							}
							if (!empty($social_url)) {
								$social_available = 'yes';
								break;
							}
						}
					}

					$profil_social_ = array();
					if (!empty($socialmediaurl) && $socialmediaurl === 'enable' && $social_available === 'yes') {
						foreach ($social_settings as $key => $val) {
							$icon		    = !empty($val['icon']) ? $val['icon'] : '';
							$color		    = !empty($val['color']) ? $val['color'] : '#484848';
							$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
							if (!empty($enable_value) && $enable_value === 'enable') {
								$social_url	= '';
								if (function_exists('fw_get_db_post_option')) {
									$social_url	= fw_get_db_post_option($freelancer_id, $key, null);
								}
								$social_url	= !empty($social_url) ? $social_url : '';
								$social = array();
								if ($social_url != '') {
									if ($key === 'whatsapp') {
										if (!empty($social_url)) {
											$social_url = 'https://api.whatsapp.com/send?phone=' . $social_url;
											$social['social_profile_type']      = $key;
											$social['social_profile_url']       = esc_attr($social_url);
											$social['social_profile_icon']      = esc_attr($icon);
										}
									} else if ($key === 'skype') {
										if (!empty($social_url)) {
											$social_url = 'skype:' . $social_url . '?call';
											$social['social_profile_type']      = $key;
											$social['social_profile_url']       = esc_attr($social_url);
											$social['social_profile_icon']      = esc_attr($icon);
										}
									} else {
										$social_url = esc_url($social_url);
										$social['social_profile_type']      = $key;
										$social['social_profile_url']       = esc_attr($social_url);
										$social['social_profile_icon']      = esc_attr($icon);
									}
									$profil_social_[] = $social;
								}
							}
						}

						$socials_profile_freelancer = $profil_social_;
					}

					/* Social shares */
					$social_shares_freelancer  = array();
					if (function_exists('fw_get_db_post_option')) {
						$social_facebook    = fw_get_db_settings_option('social_facebook');
						$social_twitter     = fw_get_db_settings_option('social_twitter');
						$social_pinterest   = fw_get_db_settings_option('social_pinterest');
						$social_linkedin    = fw_get_db_settings_option('social_linkedin');
						$twitter_username   = !empty($social_twitter['enable']['twitter_username']) ? $social_twitter['enable']['twitter_username'] : '';
					} else {
						$social_facebook 	= 'enable';
						$social_twitter 	= 'enable';
						$social_pinterest 	= 'enable';
						$social_linkedin 	= 'enable';
						$twitter_username 	= '';
					}
					if (function_exists('fw_get_db_post_option')) {
						$twitter_username   = !empty($social_twitter['enable']['twitter_username']) ? $social_twitter['enable']['twitter_username'] : '';
						$hide_hideshares    = fw_get_db_settings_option('hide_hideshares');
					} else {
						$twitter_username   = 'twitter';
					}
					if (!empty($hide_hideshares) && $hide_hideshares === 'no') {
						$permalink          = get_the_permalink();
						$title              = get_the_title();
						$social_share = $social = array();
						if (isset($social_linkedin) && $social_linkedin == 'enable') {
							$social_share['social_type']    = 'linkedin';
							$social_share['social_color']   = '#0077b5';
							$social_share['social_icon']    = 'fa fa-linkedin';
							$social_share['social_url']     = 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode(esc_url($permalink));
							$social_share['social_title']   = esc_html__("Share on linkedin", "workreap_api");
							$social[] = $social_share;
						}
						if (isset($social_facebook) && $social_facebook == 'enable') {
							$social_share['social_type']    = 'facebook';
							$social_share['social_color']   = '#3b5999';
							$social_share['social_icon']    = 'fa fa-facebook';
							$social_share['social_url']     = '//www.facebook.com/sharer.php?u=' . urlencode(esc_url($permalink));
							$social_share['social_title']   = esc_html__("Share on Facebook", "workreap_api");
							$social[] = $social_share;
						}
						if (isset($social_twitter['gadget']) && $social_twitter['gadget'] == 'enable') {
							$social_share['social_type']    = 'twitter';
							$social_share['social_color']   = '#55acee';
							$social_share['social_icon']    = 'fa fa-twitter';
							$social_share['social_url']     = '//twitter.com/intent/tweet?text=' . urlencode(esc_url($permalink));
							$social_share['social_title']   = esc_html__("Share on Twitter", "workreap_api");
							$social[] = $social_share;
						}
						if (isset($social_pinterest) && $social_pinterest == 'enable') {
							$social_share['social_type']    = 'pinterest';
							$social_share['social_color']   = '#bd081c';
							$social_share['social_icon']    = 'fa fa-pinterest-p';
							$social_share['social_url']     = '//pinterest.com/pin/create/button/?url=' . urlencode(esc_url($permalink));
							$social_share['social_title']   = esc_html__("Share on Pinterest", "workreap_api");
							$social[] = $social_share;
						}
						$social_shares_freelancer              = $social;
					}

					/* FAQ's */
					$faqs_freelancer = $faqs = array();
					if (function_exists('fw_get_db_post_option') && !empty($freelancer_id)) {
						$faqs = fw_get_db_post_option($freelancer_id, 'faq', true);
					}
					if (!empty($faqs)) {
						foreach ($faqs as $faq) {
							if (!empty($faq['faq_question'])) {
								$single_faq['faq_question']     = esc_html($faq['faq_question']);
								$single_faq['faq_answer']       = esc_html($faq['faq_answer']);
							}
							$single_faq_[] = $single_faq;
						}
						$faqs_freelancer = $single_faq_;
					}

					/* profile health */
					$profile_health_percent     = '';
					$get_profile_data	      	= get_post_meta($freelancer_id, 'profile_strength', true);
					if (isset($get_profile_data) && $get_profile_data != '') {
						$total_percentage	      	= !empty($get_profile_data['data']) ? array_sum($get_profile_data['data']) : 0;
						$profile_health_percent 	= $total_percentage;
					}

					/* freelancer report */
					$report_reason['report_title']  = esc_html__('Report this freelancer', 'workreap_api');
					$report_reason['reasons']     = workreap_get_report_reasons();
					$report_freelancer      = $report_reason;


					/* completed projects/jobs */
					$completed_jobs					= workreap_count_posts_by_meta('projects', '', '_freelancer_id', $freelancer_id, 'completed');
					$completed_jobs			= !empty($completed_jobs) ? $completed_jobs : 0;

					/* ongoing projects/jobs */
					$ongoning_jobs					= workreap_count_posts_by_meta('projects', '', '_freelancer_id', $freelancer_id, 'hired');
					$ongoning_jobs			= !empty($ongoning_jobs) ? $ongoning_jobs : 0;

					/* cancelled projects/jobs */
					$cancelled_jobs					= workreap_count_posts_by_meta('proposals', $user_id, '', '', 'cancelled');
					$cancelled_jobs			= !empty($cancelled_jobs) ? $cancelled_jobs : 0;

					/* Completed services */
					$completed_services				= workreap_count_posts_by_meta('services-orders', '', '_service_author', $user_id, 'completed');
					$completed_services		= !empty($completed_services) ? $completed_services : 0;

					/* Ongoing services */
					$ongoing_services				= workreap_count_posts_by_meta('services-orders', '', '_service_author', $user_id, 'hired');
					$ongoing_services		= !empty($ongoing_services) ? $ongoing_services : 0;

					/* Cancelled services */
					$cancelled_services				= workreap_count_posts_by_meta('services-orders', 0, '_service_author', $user_id, 'cancelled');
					$cancelled_services		= !empty($cancelled_services) ? $cancelled_services : 0;

					/**
					 * Services 
					 * */
					if (!empty($profile_id)) {
						$saved_services	= get_post_meta($profile_id, '_saved_services', true);
					} else {
						$saved_services	= array();
					}

					$services_arr = array();
					$service_args 			= array(
						'post_type' 		=> 'micro-services',
						'posts_per_page' 	=> -1,
						'orderby' 			=> 'ID',
						'order' 			=> 'DESC',
						'author' 			=> $user_id,
					);
					$service_query 			= new WP_Query($service_args);
					$service_count			= $service_query->found_posts;
					if ($service_query->have_posts()) {
						$english_level      	= worktic_english_level_list();

						while ($service_query->have_posts()) {
							$service_query->the_post();
							$service_id 	= get_the_ID();
							$args = array();
							$service_location_array= array();
 							$service_location_array = wp_get_post_terms( $service_id, 'locations', $args );

							$service_location		= array();
							$english_level_	= array();
							foreach ( $service_location_array as $key => $term ) {    
								$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
								$service_location[]	= array(
									'flag'	=> $country['url'],
									'name'	=> $term->name,
								);
							}

							$service_english_level	= array();
							$db_english_level		= fw_get_db_post_option($service_id,'english_level');
 
							if(is_array($db_english_level)){
								foreach($db_english_level as $level){
									$service_english_level[]	= array(
										'name'	=> $english_level[$level]
									);
								}

							} else{
								$service_english_level[]	=  array(
									'name'	=> $english_level[$db_english_level],
								);;
							}

							$service_downloadable = $service_faq = $service_price = $service_order_details = $service_docs = '';
							$service_price = 0;
							if (function_exists('fw_get_db_post_option')) {
								$service_faq 				= fw_get_db_post_option($service_id, 'faq');
								$service_docs   			= fw_get_db_post_option($service_id, 'docs');
								$service_order_details   	= fw_get_db_post_option($service_id, 'order_details');
								$service_price   			= fw_get_db_post_option($service_id, 'price');
								$service_downloadable   	= fw_get_db_post_option($service_id, 'downloadable');
							}

							/* favourite */
							if (!empty($saved_services)  &&  in_array($service_id, $saved_services)) {
								$favorit	= 'yes';
							} else {
								$favorit	= '';
							}

							/* add-ons */
							$db_addons		= get_post_meta($service_id, '_addons', true);
							$db_addons		= !empty($db_addons) ? $db_addons : array();
							$addons_items 	= array();
							if (!empty($db_addons)) {
								foreach ($db_addons as $addon) {
									$service_title		= get_the_title($addon);
									$service_title		= !empty($service_title) ? $service_title : '';
									$db_price			= 0;
									if (function_exists('fw_get_db_post_option')) {
										$db_price   = fw_get_db_post_option($addon, 'price');
									}

									$db_price		= !empty($db_price) ?  html_entity_decode(workreap_price_format($db_price, 'return')) : '';
									$post_status		= get_post_status($addon);
									$post_status		= !empty($post_status) ? $post_status : '';
									$addon_excerpt		= get_the_excerpt($addon);
									$addon_excerpt	= !empty($addon_excerpt) ? $addon_excerpt : '';
									$addon_id			= !empty($addon) ? $addon : '';

									$addons_items[] = array(
										'ID' 			=> $addon_id,
										'title' 		=> $service_title,
										'price' 		=> $db_price,
										'status' 		=> $post_status,
										'description' 	=> $addon_excerpt,
									);
								}
							}

							$auther_id				= get_post_field('post_author', $service_id);
							$auther_profile_id		= !empty($auther_id) ? workreap_get_linked_profile_id($auther_id) : '';

							/* author name */
							$auther_title			= get_the_title($auther_profile_id);
							$auther_title			= !empty($auther_title) ? $auther_title : '';

							/* author avatar */
							$freelancer_avatar = apply_filters(
								'workreap_freelancer_avatar_fallback',
								workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $auther_profile_id),
								array('width' => 100, 'height' => 100)
							);
							$service_auther_image	= !empty($freelancer_avatar) ? esc_url($freelancer_avatar) : '';

							/* service author verified */
							$auther_verivifed			= get_post_meta($auther_profile_id, "_is_verified", true);
							$service_auther_verified	= !empty($auther_verivifed) ? esc_attr($auther_verivifed) : '';

							/* service views */
							$services_views_count   = get_post_meta($service_id, 'services_views', true);
							$service_views			= !empty($services_views_count) ? intval($services_views_count) : 0;

							/* featured service text */
							$featured_service		= get_post_meta($service_id, '_featured_service_string', true);
							$service_featured_text	= !empty($featured_service) ? esc_html__('Featured', 'workreap_api') : '';

							/* service categories */
							$service_cats_array = array();
							$db_project_cat 		= wp_get_post_terms($service_id, 'project_cat', array('fields' => 'all'));
							$service_categories				= !empty($db_project_cat) ? $db_project_cat : array();
							if (!empty($service_categories)) {
								$serv_count	= 0;
								foreach ($service_categories as $cat) {
									$serv_count++;

									$service_cats_array[] = array(
										'id' 				=> $cat->term_id,
										'category_name' 	=> esc_html($cat->name),
										'category_slug' 	=> $cat->slug,
									);
								}
							}

							/* service content */
							$service_content		= get_the_content($service_id);
							$service_content		= !empty($service_content) ?  $service_content : '';

							/* service faq */
							$service_faq_option		= fw_get_db_settings_option('service_faq_option');
							$service_faq			= array();
							if (!empty($service_faq_option) && $service_faq_option == 'yes') {
								$service_faq			= !empty($service_faq) ? $service_faq : array();
							}

							/* download able */
							$service_downloadable		= !empty($service_downloadable) ? $service_downloadable : 'no';

							/* images/docs */
							$service_docs				= !empty($service_docs) ? $service_docs : array();
							$service_images	= array();
							if (!empty($service_docs)) {
								$docs_count	= 0;
								foreach ($service_docs as $key => $doc) {
									$docs_count++;
									$attachment_id				= !empty($doc['attachment_id']) ? $doc['attachment_id'] : '';
									$image_url					= workreap_prepare_image_source($attachment_id, 355, 352);
									$service_images[]['url'] 	= !empty($image_url) ? esc_url($image_url) : '';
								}
							}

							/* delivery and response time */
							$response_time = '';
							$service_delivery_time 		= wp_get_post_terms($service_id, 'delivery');
							if (!is_wp_error($service_delivery_time)) {
								$service_delivery_time		= !empty($service_delivery_time[0]) ? $service_delivery_time[0]->name : '';
								$response_time 				= wp_get_post_terms($service_id, 'response_time');
								$response_time				= !empty($response_time[0]) ? $response_time[0]->name : '';
							}

							/* sold out */
							// $completed_services		= workreap_get_services_count('services-orders', array('completed'), $service_id);
							$service_sold			= !empty($completed_services) ? $completed_services : 0;

							/* rating & reviews */
							$serviceRating		= get_post_meta($service_id, '_service_total_rating', true);
							$serviceRating		= !empty($serviceRating) ? $serviceRating : 0;
							$serviceFeedbacks		= get_post_meta($service_id, '_service_feedbacks', true);
							$serviceFeedbacks		= !empty($serviceFeedbacks) ? intval($serviceFeedbacks) : 0;
							if (!empty($serviceRating) || !empty($serviceFeedbacks)) {
								$serviceTotalRating	= $serviceRating / $serviceFeedbacks;
							} else {
								$serviceTotalRating	= 0;
							}
							$serviceTotalRating 		= number_format((float) $serviceTotalRating, 1);
							$queu_services				= workreap_get_services_count('services-orders', array('hired'), $service_id);

							/* reviews on services */
							$service_reviews		= array();
							$service_review_query	= array(
								'posts_per_page' 	=> -1,
								'post_type' 		=> 'services-orders',
								'post_status' 		=> array('completed'),
								'suppress_filters' 	=> false
							);
							$meta_query_service_reviews[] = array(
								'key' 		=> '_service_id',
								'value' 	=> $service_id,
								'compare' 	=> '='
							);
							$service_reviews_relation 	= array('relation' => 'AND',);
							// $service_review_query['meta_query'] = array_merge($service_reviews_relation, $meta_query_service_reviews);
							$service_reviews_args 				= new WP_Query($service_review_query);
							$review_count	= $service_reviews_args->found_posts;
							$review_count	= !empty($review_count) ? $review_count : 0;

							if ($service_reviews_args->have_posts()) {

								while ($service_reviews_args->have_posts()) {
									$service_reviews_args->the_post();
									$service_review_id = get_the_ID();
									$author_id 		= get_the_author_meta('ID', $service_review_id);
									$linked_profile = workreap_get_linked_profile_id($author_id);
									$tagline		= workreap_get_tagline($linked_profile);
									$employer_title = get_the_title($linked_profile);
									$employer_avatar = apply_filters(
										'workreap_employer_avatar_fallback',
										workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile),
										array('width' => 100, 'height' => 100)
									);
									$service_ratings	= get_post_meta($service_review_id, '_hired_service_rating', true);
									if (function_exists('fw_get_db_post_option')) {
										$feedback	 		= fw_get_db_post_option($service_review_id, 'feedback');
									}
									$feedback				= !empty($feedback) ? $feedback : '';
									$employer_title			= !empty($employer_title) ? $employer_title : '';
									$employer_avatar		= !empty($employer_avatar) ? esc_url($employer_avatar) : '';
									$verified				= get_post_meta($linked_profile, "_is_verified", true);
									$_is_verified			= !empty($verified) ? $verified : '';
									$service_loaction		= workreap_get_location($linked_profile);
									$location				= !empty($service_loaction) ? $service_loaction : array();
									$service_rating			= !empty($service_ratings) ? $service_ratings : '';
									$service_id				= !empty($service_id) ? $service_id : '';

									$service_reviews[] = array(
										'feedback' 				=> $feedback,
										'employer_title' 		=> $employer_title,
										'employer_avatar' 		=> $employer_avatar,
										'_is_verified' 			=> $_is_verified,
										'location' 				=> $location,
										'service_rating' 		=> $service_rating,
										'service_id' 			=> $service_id,
									);
								}
								wp_reset_postdata();
							}

							/* services array */
							$services_arr[] = array(
								'favorit' 				=> $favorit,
								'service_id' 			=> $service_id,
								'service_location' 		=> $service_location,
								'english_level' 		=> $service_english_level,
								'service_url' 			=> get_the_permalink(),
								'addons'				=> $addons_items,
								'auther_title'			=> $auther_title,
								'user_id'				=> (int)$auther_id,
								'profile_id'			=> $auther_profile_id,
								'auther_image'			=> $service_auther_image,
								'service_views'			=> $service_views,
								'featured_text'			=> $service_featured_text,
								'categories'			=> $service_cats_array,
								'title' 				=> get_the_title($service_id),
								'content' 				=> $service_content,
								'rating' 				=> $serviceRating,
								'feedback' 				=> $serviceFeedbacks,
								'total_rating' 			=> $serviceTotalRating,
								'faq' 					=> $service_faq,
								'downloadable' 			=> $service_downloadable,
								'price' 				=> $service_price,
								'formated_price' 		=> workreap_price_format($service_price, 'return'),
								'delivery_time' 		=> $service_delivery_time,
								'response_time' 		=> $response_time,
								'queue' 				=> $queu_services,
								'sold' 					=> $service_sold,
								'images' 				=> $service_images,
								'reviews' 				=> $service_reviews,
								'reviews_count' 		=> $review_count,
							);
						}
						wp_reset_postdata();
					}

					$services 		= $services_arr;
					$services_count = !empty($service_count) ? (int)$service_count : 0;

					/**
					 * portfolios 
					 * */
					$portfolios_arr = array();
					$portfolio_args 			= array(
						'posts_per_page' 	=> -1,
						'post_type' 		=> 'wt_portfolio',
						'orderby' 			=> 'ID',
						'order' 			=> 'DESC',
						'author' 			=> $user_id,
					);

					$portfolios_data 	= get_posts($portfolio_args);
					$portfolio_counts	=  count($portfolios_data);

					if (!empty($portfolios_data) && $portfolio_counts > 0) {
						foreach ($portfolios_data as $portfolio_obj) {
							$portfolio_id 			= $portfolio_obj->ID;
							$author_id 				= get_the_author_meta('ID', $user_id);
							$linked_profile 		= workreap_get_linked_profile_id($author_id);

							/* portfolio gallery */
							$gallery_imgs			= array();
							if (function_exists('fw_get_db_post_option')) {
								$gallery_imgs   	= fw_get_db_post_option($portfolio_id, 'gallery_imgs', true);
							}

							$portfolio_images_arr = array();
							if (!empty($gallery_imgs) && is_array($gallery_imgs)) {
								foreach ($gallery_imgs as $key => $gallery_item) {
									$portfolio_attachment_id	= !empty($gallery_item['attachment_id']) ? $gallery_item['attachment_id'] : '';
									$thumbnail      = workreap_prepare_image_source($portfolio_attachment_id, 352, 200);
									if (strpos($thumbnail, 'media/default.png') === false) {
										$portfolio_images_arr[] = esc_url($thumbnail);
									}
								}
							}

							/* portfolio videos */
							$portfolio_videos = $video_links = array();
							if (function_exists('fw_get_db_post_option')) {
								$portfolio_videos 			= fw_get_db_post_option($portfolio_id, 'videos');
							}

							if (!empty($portfolio_videos) && is_array($portfolio_videos)) {
								foreach ($portfolio_videos as $key => $vid) {
									$video_links[] = $portfolio_videos[$key];
								}
							}

							$portfolios_arr[] = array(
								'portfolio_id' 			=> $portfolio_id,
								'portfolio_title' 		=> get_the_title($portfolio_id),
								'freelancer_name' 		=> get_the_title($linked_profile),
								'portfolio_gallery' 	=> $portfolio_images_arr,
								'portfolio_videos' 		=> $video_links,
							);
						}
						wp_reset_postdata();
					}

					$portfolios 		= $portfolios_arr;
					$portfolio_count = !empty($portfolio_counts) ? (int)$portfolio_counts : 0;

					/* Project Reviews */
					$project_reviews		= array();
					$project_reviews_query	= array(
						'posts_per_page' 	=> -1,
						'post_type' 		=> 'reviews',
						'order' 			=> 'ID',
						'author' 			=> $user_id,
						'suppress_filters' 	=> false
					);
					$project_query_reviews 			= new WP_Query($project_reviews_query);
					$project_count_reviews 			= $project_query_reviews->found_posts;
					$project_reviews_count  		= !empty($project_count_reviews) ? intval($project_count_reviews) : 0;

					if ($project_query_reviews->have_posts()) {
						while ($project_query_reviews->have_posts()) {
							$project_query_reviews->the_post();
							$project_review_id = get_the_ID();
							$project_id			= get_post_meta($project_review_id, '_project_id', true);
							$project_rating		= get_post_meta($project_review_id, 'user_rating', true);
							$employer_id		= get_post_field('post_author', $project_id);
							$company_profile 	= workreap_get_linked_profile_id($employer_id);
							$employer_title 	= get_the_title($company_profile);
							$project_title		= get_the_title($project_id);
							$company_avatar 	= apply_filters(
								'workreap_employer_avatar_fallback',
								workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $company_profile),
								array('width' => 225, 'height' => 225)
							);

							$project_title_review		= $project_title;
							$post_date_review			= get_the_date($date_formate, $project_id);
							$employer_image_review		= $company_avatar;
							$_is_verified_review		= get_post_meta($company_profile, "_is_verified", true);
							$employer_name_review		= $employer_title;

							$level_title_review		= '';
							$level_sign_review		= 0;
							if (function_exists('fw_get_db_post_option')) {
								$project_level       	= fw_get_db_post_option($project_id, 'project_level', true);
								$project_level			= !empty($project_level) ? esc_attr($project_level) : '';
								if (!empty($project_level)) {
									$level_title_review		= workreap_get_project_level($project_level);
									if ($project_level === 'basic') {
										$level_sign_review	= 1;
									} elseif ($project_level === 'medium') {
										$level_sign_review	= 2;
									} elseif ($project_level === 'expensive') {
										$level_sign_review	= 3;
									}
								}
							}

							$project_location_review	= get_post_meta($project_id, '_country', true);
							$project_rating_review		= $project_rating;
							$review_content_review		= get_the_content($project_review_id);

							$project_reviews[] = array(
								'project_title'   	=> $project_title_review,
								'post_date'   		=> $post_date_review,
								'employer_image'   	=> $employer_image_review,
								'_is_verified'   	=> $_is_verified_review,
								'employer_name'   	=> $employer_name_review,
								'level_title'   	=> $level_title_review,
								'level_sign'   		=> $level_sign_review,
								'project_location'	=> $project_location_review,
								'project_rating'   	=> $project_rating_review,
								'review_content'   	=> $review_content_review,
							);
						}
						wp_reset_postdata();
					}

					$item[] = array(
						'favorit' 					=> $favorit_freelancer,
						'name'    					=> $name_freelancer,
						'user_id'    				=> $user_id_freelancer,
						'profile_id'    			=> $profile_id_freelancer,
						'content'    				=> $content_freelancer,
						'member_since'    			=> $member_since_freelancer,
						'freelancer_link' 			=> $freelancer_link_freelancer,
						'is_featured'    			=> $is_featured_freelancer,
						'profile_img'    			=> $profile_img_freelancer,
						'banner_img'    			=> $banner_img_freelancer,
						'badge'    					=> $featured_badged,
						'total_earnings'    		=> $total_earnings_freelancer,
						'_is_verified'    			=> $_is_verified,
						'_featured_timestamp'    	=> $_featured_timestamp,
						'rating_filter'    			=> $rating_filter_freelancer,
						'wt_average_rating'    		=> $wt_average_rating,
						'wt_total_rating'    		=> $wt_total_rating,
						'wt_total_percentage'    	=> $wt_total_percentage,
						'_longitude' 				=> $_longitude,
						'_latitude' 				=> $_latitude,
						'_address' 					=> $_address,
						'_tag_line' 				=> $_tag_line,
						'_gender' 					=> $_gender,
						'_perhour_rate' 			=> $_perhour_rate,
						'_english_level' 			=> $_english_level,
						'_educations' 				=> $_educations,
						'_experience' 				=> $_experience,
						'_awards' 					=> $_awards,
						'_projects' 				=> $_projects,
						'location' 					=> $location_freelancer,
						'skills'    				=> $skills_freelancer,
						'industrial_exper'    		=> $industrial_exper_freelancer,
						'languages'    				=> $languages_freelancer,
						'english_level'    			=> $english_level_freelancer,
						'specializaton'    			=> $specializaton_freelancer,
						'socials_profile'    		=> $socials_profile_freelancer,
						'social_shares'    			=> $social_shares_freelancer,
						'faqs'    					=> $faqs_freelancer,
						'profile_health_percent'  	=> $profile_health_percent,
						'report_freelancer' 		=> $report_freelancer,
						'completed_jobs' 			=> $completed_jobs,
						'ongoning_jobs' 			=> $ongoning_jobs,
						'cancelled_jobs' 			=> $cancelled_jobs,
						'completed_services' 		=> $completed_services,
						'ongoing_services' 			=> $ongoing_services,
						'cancelled_services' 		=> $cancelled_services,
						'services'    				=> $services,
						'services_count'    		=> $services_count,
						'portfolios'    			=> $portfolios,
						'portfolio_count'    		=> $portfolio_count,
						'reviews'    				=> $project_reviews,
						'reviews_count'    			=> $project_reviews_count,
					);
				}
				wp_reset_postdata();

				$items['freelancers_count'] 	= $count_post;
				$items['freelancers_data'] 		= $item;
				$json['type']					= 'success';
				$json['freelancers'] 			= $items;
				return new WP_REST_Response($json, 200);
			} else {
				$json['type']			= 'error';
				$json['message']		= esc_html__('No listing found', 'workreap_api');
				$json['freelancers']	= array();
				return new WP_REST_Response($json, 203);
			}
		}
	}
}

add_action(
	'rest_api_init',
	function () {
		$controller = new AndroidAppGetFreelancersRoutes;
		$controller->register_routes();
	}
);
