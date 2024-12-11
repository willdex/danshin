<?php
/**
 * Template Name: Add new offer
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/single-offer/
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $post, $thumbnail,$workreap_settings;
global $current_user;
$user_type			= apply_filters('workreap_get_user_type', $current_user->ID );
$post_id        	= (isset($_GET['post'])) ? intval($_GET['post']) : '';
$step           	= (isset($_GET['step'])) ? intval($_GET['step']) : '1';
$workreap_args   	= array( 'post_id'=>$post_id, 'step' => $step );
$task_allowed    	= workreap_task_create_allowed($current_user->ID);
$package_detail  	= workreap_get_package($current_user->ID);
$task_plans_allowed	= 'yes';
$package_type    	=  !empty($package_detail['type']) ? $package_detail['type'] : '';

if($package_type == 'paid'){
	$task_plans_allowed    =  !empty($package_detail['package']['task_plans_allowed']) ? $package_detail['package']['task_plans_allowed'] : 'no';
	$number_tasks_allowed  =  !empty($package_detail['package']['number_tasks_allowed']) ? $package_detail['package']['number_tasks_allowed'] : 0;
}

if((!empty($post_id) && $user_type == 'freelancers') ||  (!empty($user_type) && $user_type == 'freelancers' && $task_allowed)){
	$task_allowed_	= true;
} else {
	$workreap_packages_page_url = !empty($workreap_settings['tpl_package_page']) ? get_permalink($workreap_settings['tpl_package_page']) : get_home_url();
	wp_redirect( $workreap_packages_page_url );
    exit;
}

get_header();
?>
<div class="wr-main-section">
	<div class="container">
		<div class="row wr-blogs-bottom">
			<div class="col-xl-12">
				<?php 
				 if((!empty($post_id) && $user_type == 'freelancers') ||  (!empty($user_type) && $user_type == 'freelancers' && $task_allowed)){
					 workreap_custom_task_offer_get_template( 'post-offer/add-offer.php', $workreap_args);
				 } else {					
					 do_action('workreap_user_not_authorized');
				 } 
				?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
