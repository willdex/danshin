<?php

/**
 *
 * The template used for displaying offer detail
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $post, $thumbnail,$current_user;
do_action('workreap_post_views', $post->ID,'workreap_service_views');
get_header();
while (have_posts()) : the_post();
	$product 		= array();
	$employer_id 		= get_post_meta($post->ID, 'employer_id', true);
	$task_id 		= get_post_meta($post->ID, 'task_id', true);
	$order_id 		= get_post_meta($post->ID, 'order_id', true);
	$price    		= get_post_meta($post->ID, 'offer_price', true);
	$delivery_time  = get_post_meta($post->ID, 'delivery_time', true);
	$attachments 	= get_post_meta($post->ID, '_offer_attachments', true);
	$is_downalodable= !empty($attachments) && is_array($attachments) ? count($attachments) : 0;
	$workreap_plans_values 	= get_post_meta($post->ID, 'workreap_product_plans', true);
	
	$workreap_plans_values	= !empty($workreap_plans_values) ? $workreap_plans_values : array();
	$product_cat 			= wp_get_post_terms( $task_id, 'product_cat', array( 'fields' => 'ids' ) );
	$workreap_subtask 		= get_post_meta($post->ID, 'workreap_product_subtasks', true);
	$post_status			= get_post_status( $post->ID );
	$post_author			= get_post_field( 'post_author', $post->ID );
	$allow_user				= true;
	$employer_user_id      	= workreap_get_linked_profile_id($current_user->ID, 'users', 'employers');

	if( !is_user_logged_in( ) ){
		$allow_user			= false;
	} else {
		
		if( is_user_logged_in() && (current_user_can('administrator') || ($current_user->ID == $post_author) ||  ($employer_id == $employer_user_id && in_array($post_status,array('publish','hired','cancelled','completed','disputed')) )) ){
			$allow_user		= true;
		} else {
			$allow_user		= false;
		}
	}
	
	$plan_array	= array(
		'product_tabs' 			=> array('plan'),
		'product_plans_category'=> $product_cat
	);
	$acf_fields		= workreap_acf_groups($plan_array);
	$workreap_attr	= array(
		'post_id'   	=> $post->ID,
		'task_id'   	=> $task_id,
		'employer_id'   	=> $employer_id,
		'price'   		=> $price,
		'delivery_time'	=> $delivery_time,
		'title'			=> get_the_title(),
		'product'   	=> $post,
		'plan_array'	=> $plan_array,
		'is_downalodable'		=> $is_downalodable,
		'acf_fields'			=> $acf_fields,
		'workreap_plans_values'	=> $workreap_plans_values,
	);
	?>
	<section class="wr-main-section wr-customoffes-wrapper wr-main-bg">
		<div class="container">
			<?php
				if( empty($allow_user) ){
					do_action( 'workreap_notification', esc_html__('Restricted access','customized-task-offer'), esc_html__('Oops! you are not allowed to access this page','customized-task-offer') );
				} else {?>
				<div class="row gy-4">
					<div class="col-lg-7 col-xl-8">
						<div class="wr-servicewrap">
							<div class="wr-tehelpop wr-servicedetailtitle">
								<?php echo do_action('workreap_task_categories', $task_id, 'product_cat'); ?>
								<h3><?php the_title();?></h3>
							 </div>
							<div class="wr-singleservice-tile">
								<div class="wr-text-wrapper">
									<?php
										echo wpautop(get_the_content());
										wp_link_pages( array(
											'before'	=> '<div class="wr-paginationvtwo"><nav class="wr-pagination"><ul>',
											'after'		=> '</ul></nav></div>',
										) );
									?>
								</div>
							</div>
						</div>
						<?php workreap_custom_task_offer_get_template( 'single-offer/price-plan-tabs.php',$workreap_attr);?>
					</div>
					<div class="col-lg-5 col-xl-4">
						<aside class="wr-tabasidebar wr-custom-sidebar">
							<?php
 								workreap_custom_task_offer_get_template( 'single-offer/author-box.php',$workreap_attr);
								 workreap_custom_task_offer_get_template( 'single-offer/attachments.php',$workreap_attr);
							?>
						</aside>						
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
<?php
endwhile;
get_footer();
