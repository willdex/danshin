<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    WorkreapAppApi
 * @subpackage WorkreapAppApi/admin
 */
if (!class_exists('Workreap_Plugin_AutoUpdate')) {
	class Workreap_Plugin_AutoUpdate
	{
		private $current_version;
		private $update_path;
		private $plugin_slug;
		private $slug;
		private $license_user;
		private $license_key;

		public function __construct($current_version, $update_path, $plugin_slug, $license_user = '', $license_key = '')
		{
			$this->current_version 	= $current_version;
			$this->update_path 		= $update_path;

			// Set the License
			$this->license_user 	= $license_user;
			$this->license_key 		= $license_key;

			// Set the Plugin Slug	
			$this->plugin_slug 	= $plugin_slug;
			list($t1, $t2) 	= explode('/', $plugin_slug);
			$this->slug 		= str_replace('.php', '', $t1);

			// define the alternative API for updating checking
			add_filter('pre_set_site_transient_update_plugins', array(&$this, 'workreap_check_update'));
			add_filter('plugins_api', array(&$this, 'workreap_check_info'), 10, 3);
		}

		//Check if update is available
		public function workreap_check_update($transient)
		{
			if (empty($transient->checked)) {
				return $transient;
			}

			// Get the remote version
			$remote_version = $this->workreap_getRemote('version');

			// If a newer version is available, add the update
			if (!empty($this->current_version) && !empty($remote_version->new_version) && version_compare($this->current_version, $remote_version->new_version, '<')) {
				$obj 					= new stdClass();
				$obj->slug 				= $this->slug;
				$obj->new_version 		= $remote_version->new_version;
				$obj->url 				= $remote_version->url;
				$obj->plugin 			= $this->plugin_slug;
				$obj->package 			= $remote_version->package;
				$transient->response[$this->plugin_slug] = $obj;
			}
			return $transient;
		}

		//Check plugin version info
		public function workreap_check_info($obj, $action, $arg)
		{
			if (($action == 'query_plugins' || $action == 'plugin_information') &&
				isset($arg->slug) && $arg->slug === $this->slug
			) {
				return $this->workreap_getRemote('info');
			}

			return $obj;
		}

		//Get plugin remotly
		public function workreap_getRemote($action = '')
		{
			$params = array(
				'body' => array(
					'action'       => $action,
					'license_user' => $this->license_user,
					'license_key'  => $this->license_key,
				),
			);

			// Make the POST request
			$request = wp_remote_post($this->update_path, $params);

			// Check if response is valid
			if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
				return unserialize($request['body']);
			}

			return false;
		}
	}
}

/**
 * Auto update api init
 *
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0
 * @package           WorkreapAppApi
 */
if (!function_exists('workreap_api_autoupdate')) {
	add_action('init', 'workreap_api_autoupdate');
	function workreap_api_autoupdate()
	{
		$protocol = is_ssl() ? 'https' : 'http';
		$host						= $protocol . '://amentotech.com/autoupdate/workreap/';
		$plugin_current_version 	= 2.6;
		$plugin_remote_path 		= $host . 'workreap_api.php';
		$plugin_slug 				= 'workreap_api/init.php';
		$license_user 				= 'anonymous';
		$license_key 				= 'google';
		new Workreap_Plugin_AutoUpdate($plugin_current_version, $plugin_remote_path, $plugin_slug, $license_user, $license_key);
	}
}


/**
 * Get child comments
 */
if (!function_exists('workreap_api_child_comments')) {
	function workreap_api_child_comments($post_id = 0, $comment_id = 0)
	{
		$comments_arr = array();
		if (!empty($comment_id)) {

			$args = array(
				'post_id'   => $post_id,
				'status'    => 'approve',
				'order'     => 'DESC',
				'parent'    => $comment_id,
			);
			$child_comments = get_comments($args);

			if (!empty($child_comments)) {
				foreach ($child_comments as $comment) {
					$comment_duration           = sprintf(_x('%s ago', '%s = human-readable time difference', 'workreap_api'), human_time_diff(strtotime($comment->comment_date), current_time('timestamp')));
					$commentor_profile_id       = workreap_get_linked_profile_id($comment->user_id);
					$comment_date               = get_post_field('post_date', $comment->comment_ID);

					$user_avatar = apply_filters(
						'workreap_freelancer_avatar_fallback',
						workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $commentor_profile_id),
						array('width' => 100, 'height' => 100)
					);

					/* full name */
					$full_name				= get_user_meta($comment->user_id, 'full_name', true);

					$comments_arr[] = array(
						'id'                        => $comment->comment_ID,
						'post_id'                   => $comment->comment_post_ID,
						'user_id'                   => $comment->user_id,
						'comment_posted_date'       => date(get_option('date_format'), strtotime($comment_date)),
						'comment_author'            => $comment->comment_author,
						'author_name'               => !empty($full_name) ? $full_name : '',
						'comment_author_email'      => $comment->comment_author_email,
						'comment_date'              => $comment->comment_date,
						'comment_content'           => $comment->comment_content,
						'comment_approved'          => $comment->comment_approved,
						'comment_type'              => $comment->comment_type,
						'comment_parent'            => $comment->comment_parent,
						'comment_duration'          => $comment_duration,
						'avatar'                    => $user_avatar,
					);
				}
			}
		}
		return $comments_arr;
	}
}

/**
 * Get Single comment
 */
if (!function_exists('workreap_api_single_comments_detail')) {
	function workreap_api_single_comments_detail(int $comment_id = 0)
	{
		$comments_arr = array();
		if (!empty($comment_id)) {
			$single_comments = get_comment($comment_id);

			if (!empty($single_comments)) {
				$comment_duration           = sprintf(_x('%s ago', '%s = human-readable time difference', 'workreap_api'), human_time_diff(strtotime($single_comments->comment_date), current_time('timestamp')));
				$commentor_profile_id       = workreap_get_linked_profile_id($single_comments->user_id);
				$comment_date               = get_post_field('post_date', $single_comments->comment_ID);

				$user_avatar = apply_filters(
					'workreap_freelancer_avatar_fallback',
					workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $commentor_profile_id),
					array('width' => 100, 'height' => 100)
				);

				/* full name */
				$full_name				= get_user_meta($single_comments->user_id, 'full_name', true);

				$comments_arr[] = array(
					'id'                        => $single_comments->comment_ID,
					'post_id'                   => $single_comments->comment_post_ID,
					'user_id'                   => $single_comments->user_id,
					'comment_posted_date'       => date(get_option('date_format'), strtotime($comment_date)),
					'comment_author'            => $single_comments->comment_author,
					'author_name'               => !empty($full_name) ? $full_name : '',
					'comment_author_email'      => $single_comments->comment_author_email,
					'comment_date'              => $single_comments->comment_date,
					'comment_content'           => $single_comments->comment_content,
					'comment_approved'          => $single_comments->comment_approved,
					'comment_type'              => $single_comments->comment_type,
					'comment_parent'            => $single_comments->comment_parent,
					'comment_duration'          => $comment_duration,
					'avatar'                    => $user_avatar,
				);
			}
		}
		return $comments_arr;
	}
}

/**
 * Get single post comments
 */
if (!function_exists('workreap_api_single_post_comments')) {
	function workreap_api_single_post_comments(int $post_id = 0)
	{
		$comments_arr = array();
		if (!empty($post_id)) {

			$number                 = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
			$comment_data    = array(
				'post_id'           => $post_id,
				'post_type'         => 'post',
				'status'            => array('approve', 'hold'),
				'number'            => $number,
				'orderby'           => 'comment_ID',
				'type'              => 'comment',
				'hierarchical'      => 'flat',
			);

			$comments = get_comments($comment_data);

			$comments_arr = array();
			if (!empty($comments)) {
				foreach ($comments as $comment) {
					if ($comment->comment_parent == 0) {
						$comment_date               = $comment->comment_date;
						$comment_duration           = sprintf(_x('%s ago', '%s = human-readable time difference', 'workreap_api'), human_time_diff(strtotime($comment->comment_date), current_time('timestamp')));
						$commentor_profile_id       = workreap_get_linked_profile_id($comment->user_id);

						$user_avatar = apply_filters(
							'workreap_freelancer_avatar_fallback',
							workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $commentor_profile_id),
							array('width' => 100, 'height' => 100)
						);

						$child_comments = workreap_api_child_comments($comment->comment_post_ID, $comment->comment_ID);
						$child_comments = !empty($child_comments) ? $child_comments : array();
						/* full name */
						$full_name                = get_user_meta($comment->user_id, 'full_name', true);

						$comments_arr[] = array(
							'id'                        => $comment->comment_ID,
							'post_id'                   => $comment->comment_post_ID,
							'user_id'                   => $comment->user_id,
							'comment_posted_date'       => date(get_option('date_format'), strtotime($comment_date)),
							'comment_author'            => $comment->comment_author,
							'author_name'               => !empty($full_name) ? $full_name : '',
							'comment_author_email'      => $comment->comment_author_email,
							'comment_date'              => $comment->comment_date,
							'comment_content'           => $comment->comment_content,
							'comment_approved'          => $comment->comment_approved,
							'comment_type'              => $comment->comment_type,
							'comment_parent'            => $comment->comment_parent,
							'comment_duration'          => $comment_duration,
							'avatar'                    => $user_avatar,
							'child_comment'             => $child_comments,
						);
					}
				}
			}
		}
		return $comments_arr;
	}
}
