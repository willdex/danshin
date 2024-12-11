<?php
/**
 * Single offer author details
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/single-offer/
 */
global $post;
$post_author				= get_post_field( 'post_author', $post);
$workreap_args               = array();
$workreap_args['post_id']    = !empty($post_author) ? workreap_get_linked_profile_id($post_author,'','freelancers') :'';
workreap_get_template( 'single-freelancer/profile-basic.php',$workreap_args);
