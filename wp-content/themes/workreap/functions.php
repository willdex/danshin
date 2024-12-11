<?php
/**
 * workreap functions and definitions
 *
 * @link https://themeforest.net/user/amentotech/portfolio
 *
 * @package workreap
 */

if (!function_exists('workreap_prepare_thumbnail')) {
	function workreap_prepare_thumbnail($post_id, $width = '300', $height = '300') {
		global $post;
		if (has_post_thumbnail()) {
			get_the_post_thumbnail();
			$thumb_id = get_post_thumbnail_id($post_id);
			$thumb_url = wp_get_attachment_image_src($thumb_id, array(
				$width,
				$height
			), true);
			if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
				return !empty($thumb_url[0]) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
				return !empty($thumb_url[0]) ? $thumb_url[0] : '';
			}
		} else {
			return false;
		}
	}
}

if(!function_exists('workreap_new_theme_active')){
	function workreap_new_theme_active(){
		$unyson_option = get_option('fw_theme_settings_options:workreap');
		if( (!empty($unyson_option) && !defined('WORKREAP_DIRECTORY_URI')) || defined('Workreap_Basename') ){
			return false;
		}
		return true;
	}
}

//Include theme setup
require get_template_directory() . '/inc/theme-setup.php';

$theme_check = workreap_new_theme_active();
if(!$theme_check){
	require_once ( get_template_directory() . '/demo-content/data-importer/importer.php');
}