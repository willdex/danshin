<?php

/**
 *
 * @package   WorkreapAppApi
 * @author    amentotech
 * @link      https://codecanyon.net/user/amentotech/portfolio
 * @since 1.0
 */

function android_get_video_data($video_url)
{
	if (!empty($video_url)) {
		$height = 300;
		$width  = 450;
		$post_video = $video_url;
		$url = parse_url($post_video);
		$videodata	= '';
		if (isset($url['host']) && ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com')) {
			$content_exp = explode("/", $post_video);
			$content_vimo = array_pop($content_exp);
			$videodata .= '<iframe width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
></iframe>';
		} elseif (isset($url['host']) && $url['host'] == 'soundcloud.com') {
			$video = wp_oembed_get($post_video, array('height' => $height));
			$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
			$video = str_replace($search, '', $video);
			$videodata .= str_replace('&', '&amp;', $video);
		} else {
			$content = str_replace(array('watch?v=', 'http://www.dailymotion.com/'), array('embed/', '//www.dailymotion.com/embed/'), $post_video);
			$videodata .= '<iframe width="' . $width . '" height="' . $height . '" src="' . $content . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}

		return $videodata;
	}
}

/**
 * Proposla already submitted
 */
if (!function_exists('workreap_api_proposal_submitted')) {
	function workreap_api_proposal_submitted($user_id = 0, $project_id = 0)
	{
		$proposal_submitted = 'no';
		if (!empty($user_id)) {
			$proposals_sent = 0;
			$args = array(
				'post_type' 	=> 'proposals',
				'author'    	=>  $user_id,
				'meta_query' 	=> array(
					array(
						'key'     => '_project_id',
						'value'   => intval($project_id),
						'compare' => '=',
					),
				),
			);
			$query = new WP_Query($args);

			if (!empty($query)) {
				$proposals_sent =  $query->found_posts;
			}

			if ($proposals_sent > 0) {
				$proposal_submitted = 'yes';
			}
		}
		return $proposal_submitted;
	}
	add_filter('workreap_api_proposal_submitted', 'workreap_api_proposal_submitted', 10, 2);
}

/**
 * Social share project/jop
 */
if (!function_exists('workreap_api_social_share_job')) {
	function workreap_api_social_share_job($project_id = 0)
	{
		$social_shoare = array();
		if (!empty($project_id)) {
			$social_facebook 	= $social_twitter 	= $social_pinterest 	= $social_linkedin 	= 'enable';
			$hide_hideshares 	= 'no';
			if (function_exists('fw_get_db_settings_option')) {
				$social_facebook = fw_get_db_settings_option('social_facebook');
				$social_twitter = fw_get_db_settings_option('social_twitter');
				$social_pinterest = fw_get_db_settings_option('social_pinterest');
				$social_linkedin = fw_get_db_settings_option('social_linkedin');
				$hide_hideshares = fw_get_db_settings_option('hide_hideshares');
			}
			if (!empty($hide_hideshares) && $hide_hideshares === 'no') {
				if (isset($social_pinterest) && $social_pinterest == 'enable') {
					$social_shoare['pinterest'] = 'https://pinterest.com/pin/create/button/?url=' . urlencode(esc_url(get_the_permalink($project_id)));
				}
				if (isset($social_facebook) && $social_facebook == 'enable') {
					$social_shoare['facebook'] = 'https://www.facebook.com/sharer.php?u=' . urlencode(esc_url(get_the_permalink($project_id)));
				}
				if (isset($social_linkedin) && $social_linkedin == 'enable') {
					$social_shoare['linkedin'] = 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode(esc_url(get_the_permalink($project_id)));
				}
				if (isset($social_twitter) && $social_twitter == 'enable') {
					$social_shoare['twitter'] = 'https://twitter.com/intent/tweet?text=' . urlencode(esc_url(get_the_permalink($project_id)));
				}
			}
		}
		return $social_shoare;
	}
	add_filter('workreap_api_social_share_job', 'workreap_api_social_share_job', 10, 1);
}

/**
 * Employer details by
 * project id
 */
if (!function_exists('workreap_api_employer_details')) {
	function workreap_api_employer_details($project_id = 0)
	{
		$employer_data_arr 	= array();
		if (!empty($project_id)) {
			$project_author_id 				= get_post_field('post_author', $project_id);
			$project_author_profile_id		= workreap_get_linked_profile_id($project_author_id);
			$followers_count				= apply_filters('workreap_api_get_followers', $project_author_profile_id, 'followers_count');
			$followers_count				= !empty($followers_count) ? $followers_count : 0;
			$followers						= apply_filters('workreap_api_get_followers', $project_author_profile_id, 'followers');
			$followers						= !empty($followers) ? $followers : array();
			$employer 						= get_post($project_author_profile_id);
			if (!empty($employer)) {
				$employer_avatar 	= apply_filters(
					'workreap_employer_avatar_fallback',
					workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $project_author_id),
					array('width' => 100, 'height' => 100)
				);
				$profile_image 	= !empty($employer_avatar) ? esc_url($employer_avatar) : '';
				$profile_image 	= !empty($profile_image) ? $profile_image : '';
				$tagline = '';
				if (function_exists('fw_get_db_post_option')) {
					$tagline		= fw_get_db_post_option($employer->ID, 'tag_line', true);
				}

				$employer_data_arr[] = array(
					'id' 				=> $employer->ID,
					'user_id' 			=> (int)$employer->post_author,
					'name' 				=> $employer->post_title,
					'slug' 				=> $employer->post_name,
					'tag_line' 			=> $tagline,
					'profile_image' 	=> $profile_image,
					'post_type' 		=> $employer->post_type,
					'post_content' 		=> $employer->post_content,
					'guid' 				=> $employer->guid,
					'followers_count' 	=> $followers_count,
					'followers' 		=> $followers,
				);
				wp_reset_postdata();
			}
		}
		return $employer_data_arr;
	}
	add_filter('workreap_api_employer_details', 'workreap_api_employer_details');
}

/**
 * Employer followers count
 */
if (!function_exists('workreap_api_get_followers')) {
	function workreap_api_get_followers($project_author_profile_id = 0, $follower_key = '')
	{
		$followers_count = 0;
		$followers = array();
		if (!empty($project_author_profile_id)) {
			$emp_followers 		= get_post_meta($project_author_profile_id, '_followers', true);
			$emp_followers		= !empty($emp_followers) ? $emp_followers : array();
			if (!empty($emp_followers)) {
				$args	= array(
					'post_type' 			=> array('freelancers', 'employers'),
					'posts_per_page'      	=> -1,
					'post_status' 			=> 'publish',
					'suppress_filters' 		=> false,
					'ignore_sticky_posts' 	=> 1,
					'post__in'				=> $emp_followers
				);
				$emp_followers = get_posts($args);
			}

			if ($follower_key === 'followers_count') {
				if (!empty($emp_followers) &&  !empty($emp_followers)) {
					$followers_count = count($emp_followers);
				}
				return $followers_count;
			} elseif ($follower_key === 'followers') {
				if (!empty($emp_followers) && !empty($emp_followers)) {
					foreach ($emp_followers as $follower) {
						$follower_avatar 	= apply_filters(
							'workreap_employer_avatar_fallback',
							workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $follower->ID),
							array('width' => 100, 'height' => 100)
						);
						$profile_image 	= !empty($follower_avatar) ? esc_url($follower_avatar) : '';
						$profile_image 	= !empty($profile_image) ? $profile_image : '';


						$followers[] = array(
							'id' 			=> $follower->ID,
							'user_id' 		=> (int)$follower->post_author,
							'post_title' 	=> $follower->post_title,
							'post_name' 	=> $follower->post_name,
							'profile_image' => $profile_image,
							'post_type' 	=> $follower->post_type,
							'post_content' 	=> $follower->post_content,
							'guid' 			=> $follower->guid,
						);
					}
					wp_reset_postdata();
				}
				return $followers;
			}
		}
	}
	add_filter('workreap_api_get_followers', 'workreap_api_get_followers', 10, 2);
}

/**
 * Get employer jobs/projects
 */
if (!function_exists('workreap_api_get_employer_projects')) {
	function workreap_api_get_employer_projects($employer_id = 0)
	{
		$jobs = $jobs_data = array();
		if (!empty($employer_id)) {
			$query_args = array(
				'posts_per_page' 	  	=> -1,
				'post_type' 	 	  	=> 'projects',
				'post_status' 	 	  	=> 'publish',
				'author' 				=> $employer_id
			);

			$query_result 	= new WP_Query($query_args);
			$count_post 	= $query_result->found_posts;

			if ($query_result->have_posts()) {
				$duration_list 			= worktic_job_duration_list();
				while ($query_result->have_posts()) {
					$query_result->the_post();
					global $post;
					$project_id	 			= $post->ID;
					$project_title			= get_the_title($project_id);
					$project_content		= get_the_content($project_id);
					$project_url 			= get_permalink($project_id);
					$featured_job			= get_post_meta($project_id, '_featured_job_string', true);
					$linked_profile			= workreap_get_linked_profile_id($employer_id);
					$english_level			= get_post_meta($project_id, '_english_level', true);
					$languages_arr			= wp_get_post_terms($project_id, 'languages', array('fields' => 'all'));
					$proposals  			= workreap_get_totoal_proposals($project_id, 'array', '-1');
					$job_option				= get_post_meta($project_id, '_job_option', true);
					$job_option				= !empty($job_option) ? workreap_get_job_option($job_option) : '';
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

					/* job direction */
					$direction = '';
					if (!empty($latitude)) {
						$direction = 'http://www.google.com/maps/place/' . esc_js($latitude) . ',' . esc_js($longitude) . '/@' . esc_js($latitude) . ',' . esc_js($longitude) . ',17z';
					}

					/* is proposal already submitted */
					$proposal_submitted = apply_filters('workreap_api_proposal_submitted', $employer_id, $project_id);

					/* share project */
					$social_share = apply_filters('workreap_api_social_share_job', $project_id);

					$docs						= array();
					if (!empty($project_documents) && is_array($project_documents)) {
						$docs_count	= 0;
						foreach ($project_documents as $value) {
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

					/* skills */
					$skills					= array();
					$silees_terms 			= wp_get_post_terms($project_id, 'skills');
					if (!empty($silees_terms)) {
						$sk_count	= 0;
						foreach ($silees_terms as $term) {
							$sk_count++;
							$term_link 							= get_term_link($term->term_id, 'skills');
							$skills[$sk_count]['skill_link']	= $term_link;
							$skills[$sk_count]['skill_name']	= $term->name;
						}
					}

					/* faq */
					$job_faq_option		= fw_get_db_settings_option('job_faq_option');
					$faq_items		= array();
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
						$faq_items		= $faqs_arr;
					}

					/**
					 * employer detail
					 *  */
					$employer_data_arr = apply_filters('workreap_api_employer_details', $project_id);

					$jobs[] = array(
						'id' 					=> $project_id,
						'project_title' 		=> $project_title,
						'project_content' 		=> $project_content,
						'link' 					=> esc_url($project_url),
						'is_featured' 			=> !empty($featured_job) ? 'yes' : 'no',
						'location' 				=> workreap_get_location($project_id),
						'author_id' 			=> $employer_id,
						'profile_id' 			=> $linked_profile,
						'project_level' 		=> apply_filters('workreap_filter_project_level', $project_id),
						'english_level' 		=> !empty($english_level) ? $english_level : '',
						'languages' 			=> $job_languages,
						'proposals' 			=> !empty($proposals) ? count($proposals) : 0,
						'categories' 			=> $job_categories,
						'job_type' 				=> $job_option,
						'project_type' 			=> !empty($project_type['gadget']) ? ucfirst($project_type['gadget']) : '',
						'project_duration' 		=> !empty($project_duration) ? $duration_list[$project_duration] : '',
						'project_cost' 			=> !empty($project_cost) ? apply_filters('workreap_price_format', $project_cost, 'return') : '',
						'max_price' 			=> !empty($max_cost) ? apply_filters('workreap_price_format', $max_cost, 'return') : '',
						'hourly_rate' 			=> !empty($hourly_rate) ? apply_filters('workreap_price_format', $hourly_rate, 'return') : '',
						'estimated_hours' 		=> !empty($estimated_hours) ? $estimated_hours : '',
						'expiry_date' 			=> !empty($expiry_date) ? $expiry_date : '',
						'deadline_date' 		=> !empty($deadline_date) ? $deadline_date : '',
						'proposal_count' 		=> !empty($proposals) ? count($proposals) : 0,
						'proposal_submitted' 	=> $proposal_submitted,
						'direction' 			=> $direction,
						'share_job' 			=> $social_share,
						'attachments' 			=> array_values($docs),
						'skills' 				=> array_values($skills),
						'faq' 					=> array_values($faq_items),
						'employer' 				=> array_values($employer_data_arr),
					);
				}
				$jobs_data['count'] = $count_post;
				$jobs_data['jobs'] = array_values($jobs);
			}
			return $jobs_data;
		}
	}
	add_filter('workreap_api_get_employer_projects', 'workreap_api_get_employer_projects');
}


/**
 * invoice pdf download
 */
if (!function_exists('workreap_api_pdf_download')) {
	add_action('workreap_api_pdf_download', 'workreap_api_pdf_download');
	function workreap_api_pdf_download($args = array())
	{
		$order_id 		= !empty($args['id']) ? $args['id'] : 0;
		$order      	= wc_get_order($order_id);
		$data_created	= wc_format_datetime($order->get_date_created(), get_option('date_format') . ', ' . get_option('time_format'));
		//$get_date_paid	= $order->get_date_paid();
		$get_total		= $order->get_total();
		//$get_taxes		= $order->get_taxes();
		//$get_subtotal	= $order->get_subtotal();
		$billing_address	= $order->get_formatted_billing_address();

		//$date_format	= get_option('date_format');
		//$time_format	= get_option('time_format');

		if (function_exists('fw_get_db_settings_option')) {
			$invoice_address 	= fw_get_db_settings_option('invoice_address');
			$invoice_text 		= fw_get_db_settings_option('invoice_text');
			$billing_address	= !empty($invoice_address) ? $invoice_address : $billing_address;
		}

		//Get sub totals
		if (function_exists('wmc_revert_price')) {
			$get_total	= workreap_price_format(wmc_revert_price($get_total, $order->get_currency()), 'return');
		} else {
			$get_total	= workreap_price_format($get_total, 'return');
		}

		$html			= '';
		$project_title	= '';
		$counter		= 0;
		$payment_type_title	= esc_html__('Project title:', 'workreap_api');
		if (!empty($order->get_items())) {
			foreach ($order->get_items() as $item_id => $item) {
				$counter++;
				$total 				= $item->get_total();
				$tax 				= $item->get_subtotal_tax();
				$admin_shares		= $item->get_meta('admin_shares', true);
				$freelancer_shares	= $item->get_meta('freelancer_shares', true);
				$woo_product_data	= $item->get_meta('cus_woo_product_data', true);
				$payment_type		= $item->get_meta('payment_type', true);
				$project_title		= $item->get_name();
				$employer_id		= $item->get_meta('employer_id', true);
				$freelancer_id		= $item->get_meta('freelancer_id', true);
				$current_project	= $item->get_meta('current_project', true);
				$addons				= '';

				if (!empty($current_project)) {
					$project_title	= get_the_title($current_project);
					if (!empty($woo_product_data['addons'])) {
						foreach ($woo_product_data['addons'] as $key => $service_item) {
							$addons	.= '<p>' . get_the_title($key) . '</p>';
						}
					}

					if (!empty($woo_product_data['milestone_id'])) {
						$addons	.= '<p>' . get_the_title($woo_product_data['milestone_id']) . '</p>';
					}
				} elseif (!empty($woo_product_data['project_id'])) {
					$project_title	= get_the_title($woo_product_data['project_id']);

					if (!empty($woo_product_data['milestone_id'])) {
						$addons	.= '<p>' . get_the_title($woo_product_data['milestone_id']) . '</p>';
					}
				} elseif (!empty($woo_product_data['service_id'])) {
					$project_title	= get_the_title($woo_product_data['service_id']);
					if (!empty($woo_product_data['addons'])) {
						foreach ($woo_product_data['addons'] as $key => $service_item) {
							$addons	.= '<p>' . get_the_title($key) . '</p>';
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

				//total with wmc
				if (function_exists('wmc_revert_price')) {
					$total	= workreap_price_format(wmc_revert_price($total, $order->get_currency()), 'return');
				} else {
					$total	= workreap_price_format($total, 'return');
				}

				$html	.= '<tr>
						<td data-label="' . esc_html__('#', 'workreap_api') . '"><span>' . intval($counter) . '</span></td>
						<td data-label="' . _x('Description', 'Description for invoice detail', 'workreap_api') . '"><span>' . $project_title . '</span>' . $addons . '</td>
						<td data-label="' . esc_html__('Cost', 'workreap_api') . '"><span>' . $total . '</span></td>
						<td data-label="' . esc_html__('Transaction fee', 'workreap_api') . '"><span>' . workreap_price_format($admin_shares, 'return') . '</span></td>
						<td data-label="' . esc_html__('Amount', 'workreap_api') . '"><span>' . $total . '</span></td>
					</tr>';
			}
		}

		$from_billing_address		= !empty($freelancer_id) ? workreap_user_billing_address($freelancer_id) : '';

		$main_logo = array();
		if (function_exists('fw_get_db_settings_option')) {
			$main_logo = fw_get_db_settings_option('main_logo');
		}

		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		if (!empty($main_logo['url'])) {
			$logo = $main_logo['url'];
		} else {
			$logo = get_template_directory_uri() . '/images/logo.png';
		}
?>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg- col-xl-8 float-right">
			<div class="wt-dashboardbox wt-dashboardinvocies">
				<div class="wt-printable">
					<div class="wt-invoicebill">
						<?php if (!empty($logo)) { ?>
							<figure><img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($blogname); ?>"></figure>
						<?php } ?>
						<div class="wt-billno">
							<h3><?php esc_html_e('Invoice', 'workreap_api'); ?></h3>
							<span>#<?php echo intval($order_id); ?></span>
						</div>
					</div>
					<div class="wt-tasksinfos">
						<?php if (!empty($project_title)) { ?>
							<div class="wt-invoicetasks">
								<h6><?php echo esc_html($payment_type_title); ?></h6>
								<h3><?php echo do_shortcode($project_title); ?></h3>
							</div>
						<?php } ?>
						<div class="wt-tasksdates">
							<span><em><?php esc_html_e('Issue date:', 'workreap_api'); ?></em>&nbsp;<?php echo esc_html($data_created); ?></span>
						</div>
					</div>
					<div class="wt-invoicefromto">
						<?php if (!empty($billing_address)) { ?>
							<div class="wt-fromreceiver">
								<span><strong><?php esc_html_e('To:', 'workreap_api'); ?></strong></span>
								<div class="billing-area"><?php echo do_shortcode(nl2br($billing_address)); ?></div>
							</div>
						<?php } ?>
						<?php if (!empty($from_billing_address)) { ?>
							<div class="wt-fromreceiver">
								<span><strong><?php esc_html_e('From:', 'workreap_api'); ?></strong></span>
								<div class="billing-area"><?php echo do_shortcode(nl2br($from_billing_address)); ?></div>
							</div>
						<?php } ?>

					</div>
					<table class="wt-table wt-invoice-table">
						<thead>
							<tr>
								<th><?php esc_html_e('Item', 'workreap_api'); ?>#</th>
								<th><?php echo _x('Description', 'Description for invoice detail', 'workreap_api'); ?></th>
								<th><?php esc_html_e('Cost', 'workreap_api'); ?></th>
								<th><?php esc_html_e('Transaction fee', 'workreap_api'); ?></th>
								<th><?php esc_html_e('Amount', 'workreap_api'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php echo do_shortcode($html); ?>
						</tbody>
					</table>
					<div class="wt-subtotal">
						<ul class="wt-subtotalbill">
							<?php if (!empty($total)) { ?><li><?php esc_html_e('Subtotal :', 'workreap_api'); ?><h6><?php echo esc_attr($total); ?></h6>
								</li><?php } ?>

							<?php if (!empty($admin_shares)) { ?>
								<li><?php esc_html_e('Transaction fee', 'workreap_api'); ?>&nbsp;<h6>-<?php workreap_price_format($admin_shares); ?></h6>
								</li>
							<?php } ?>
						</ul>
						<?php if (!empty($freelancer_shares)) { ?>
							<div class="wt-sumtotal"><?php esc_html_e('Total :', 'workreap_api'); ?>
								<h6><?php echo workreap_price_format($freelancer_shares); ?></h6>
							</div>
						<?php } else { ?>
							<div class="wt-sumtotal"><?php esc_html_e('Total :', 'workreap_api'); ?>
								<h6><?php echo esc_attr($get_total); ?></h6>
							</div>
						<?php } ?>
					</div>
					<?php if (!empty($invoice_text)) { ?><div class="wt-disclaimer">
							<p><?php echo esc_html($invoice_text); ?></p>
						</div><?php } ?>
				</div>
			</div>
		</div>
<?php
	}
}

/**
 * Get milestones by proposals
 */
if (!function_exists('workreap_api_get_milestons_by_proposal')) {

	function workreap_api_get_milestons_by_proposal(int $proposal_id = 0, int $user_id = 0)
	{
		$proposal_milestone = array();
		if (!empty($proposal_id)) {
			$meta_query_args	= array();
			$order 	 = 'ASC';
			$sorting = 'ID';
			$args 	= array(
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
			$query_relation 		= array('relation' => 'AND',);
			$args['meta_query'] 	= array_merge($query_relation, $meta_query_args);

			$query 					= new WP_Query($args);
			$count_post 			= $query->found_posts;
			$date_format			= get_option('date_format');

			while ($query->have_posts()) {
				$query->the_post();
				global $post;
				$milstone_title			= get_the_title($post->ID);
				$milstone_content		= get_post_field('post_content', $post->ID);
				$milstone_price_single	= get_post_meta($post->ID, '_price', true);
				$milstone_date			= get_post_meta($post->ID, '_due_date', true);
				$milstone_date			= str_replace('/', '-', $milstone_date);
				$milstone_due_date		= !empty($milstone_date) ? date_i18n($date_format, strtotime($milstone_date)) : '';
				$price_formate			= !empty($milstone_price_single) ? workreap_price_format($milstone_price_single, 'return') : '';
				$milstone_price			= !empty($milstone_price_single) ? number_format((float)$milstone_price_single, 2, '.', '') : '';
				$milstone_status		= get_post_status($post->ID);
				$updated_status			= get_post_meta($post->ID, '_status', true);
				$updated_status			= !empty($updated_status) ? $updated_status : '';

				$order_id	= get_post_meta($post->ID, '_order_id', true);
				$order_id	= !empty($order_id) ? intval($order_id) : '';
				$order_url	= '';
				if (!empty($order_id)) {
					if (class_exists('WooCommerce')) {
						$order		= wc_get_order($order_id);
						$order_url	= Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $user_id, true, 'invoice', intval($order_id));;
					}
				}

				/* is completed milestone */
				$is_completed = 'no';
				if (!empty($updated_status)) {
					if (($updated_status === 'pay_now' || $updated_status === 'pending') && (!empty($proposal_status) && $proposal_status === 'approved' && empty($order_id))) {
						$is_completed	= 'no';
					} else if ($updated_status === 'pending') {
						$is_completed	= 'no';
					} else if ($updated_status === 'hired') {
						$is_completed	= 'no';
					} else if ($updated_status === 'completed') {
						$is_completed	= 'yes';
					}
				}

				$proposal_milestone[]	= array(
					'milstone_id'			=> $post->ID,
					'milstone_title'		=> $milstone_title,
					'milstone_content'		=> $milstone_content,
					'price_formate'			=> $price_formate,
					'price'					=> $milstone_price,
					'milstone_date'			=> $milstone_date,
					'milstone_date_formate'	=> $milstone_due_date,
					'milstone_status'		=> $milstone_status,
					'order_url'				=> $order_url,
					'updated_status'		=> $updated_status,
					'is_completed'			=> $is_completed,
					'invoice_id'			=> $order_id,
				);
			}
			wp_reset_postdata();
		}
		return $proposal_milestone;
	}
	add_filter('workreap_api_get_milestons_by_proposal', 'workreap_api_get_milestons_by_proposal', 10, 2);
}


/**
 * Project hisotry
 */
if (!function_exists('workreap_api_project_history')) {
	function workreap_api_project_history(int $proposal_id = 0)
	{
		$history = $comment_history = array();
		$post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
		if (!empty($post_comment_id)) {
			$counter = 0;
			$args 		= array('post_id' => $post_comment_id);
			$comments	= get_comments($args);
			foreach ($comments as $key => $value) {
				$counter++;
				$comment_history	= array();
				$date 				= !empty($value->comment_date) ? $value->comment_date : '';
				$user_id 			= !empty($value->user_id) ? $value->user_id : '';
				$comments_ID 		= !empty($value->comment_ID) ? $value->comment_ID : '';
				$message 			= $value->comment_content;
				$date 				= !empty($date) ? date('F j, Y', strtotime($date)) : '';

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
						$filename   = 'comment_attachment-' . $comments_ID . '-' . round(microtime(true)) . '.zip';
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
						$comment_history['download_url'] = $download_url;
					} else {
						$json['type']           = 'error';
						$json['message']        = esc_html__('Oops', 'workreap_api');
						$json['message_desc']   = esc_html__('Zip library is not installed on the server, please contact to hosting provider', 'workreap_api');
						return new WP_REST_Response($json, 203);
					}
				}
				$history[]			= $comment_history;
			}
		}
		return $history;
	}
	add_filter('workreap_api_project_history', 'workreap_api_project_history', 10);
}
