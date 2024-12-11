<?php
/**
 * Single task price plan tabs
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/single-offer/
 */
global $post, $current_user, $workreap_settings;
$post_id		= $post->ID;
$plans			= !empty($workreap_plans_values) ? $workreap_plans_values : array();
$plans_count	= !empty($plans) && is_array($plans) ? count($plans) : 0;
$order_id		= get_post_meta( $post_id, 'order_id', true );
$order_id		= !empty($order_id) ? intval($order_id) : 0;
$post_author	= get_post_field( 'post_author', $post_id );
$checkout_class	= 'wr_offers_btn_checkout';

if( !empty($current_user->ID) && $post_author == $current_user->ID ){
	$checkout_class	= 'wr_btn_author';
}

$fetured_plan		= get_post_meta( $post_id, '_featured_package', true );
$fetured_plan		= !empty($fetured_plan) ? $fetured_plan : '';
$db_delivery_time   = wp_get_post_terms($post_id, 'delivery_time', array('fields' => 'ids'));
$tab_contents 	= '';

if( !empty($plans) ){
	$tab_contents	.='';
	?>
	<div class="wr-asideholder wr-sidebartabholder">
		<div class="wr-asidebox wr-sidebartabs">
			<?php
			$counter	= 0;
			foreach($plans as $key => $plan ){
				$counter ++;
				$custom_fields		= workreap_task_custom_fields($post_id,$key);
				$cart_url      		= Workreap_Profile_Menu::workreap_custom_profile_menu_link('offers-cart', $task_id, $key);
				$cart_url			= add_query_arg(array('offers_id'=>$post_id), $cart_url);
				if( isset($plan) ){
					$class			= '';
					$class_li		= '';
					$class_content	= '';

					if( !empty($fetured_plan) && $fetured_plan == $key ){
						$class_li		= 'wr-sideactive';
						$class			= 'active';
						$class_content	= 'show';
					} else if(empty($fetured_plan)){
						if( !empty($counter) && $counter == 1 ){
							$class_li		= 'wr-sideactive';
							$class			= 'active';
							$class_content	= 'show';
						}
					}

					$workreap_icon_key	= 'task_plan_icon_'.$key;
					$task_plan_icon_url	= !empty($workreap_settings[$workreap_icon_key]['url']) ? $workreap_settings[$workreap_icon_key]['url'] : '';
					$tab_contents	.='<div class="tab-pane fade '.esc_attr($class_content).' '.esc_attr($class).'" id="'.esc_attr($key).'" role="tabpanel">';
					$tab_contents	.='<div class="wr-sidebarpkg wr-custom-offers">';
					$tab_contents	.='<div class="wr-sectiontitle wr-sectiontitlev2">';

					if(!empty($task_plan_icon_url)){
						$tab_contents	.='<img src="'.esc_url($task_plan_icon_url).'" alt="'.esc_attr($key).'">';
					}

					$tab_contents	.='<div class="wr-packegeplan">';
					$tab_contents	.='<h5>'.esc_html('Custom offer','customized-task-offer').'</h5>';
					$tab_contents	.='<h3>'.workreap_price_format($price,'return').'</h3>';
					$tab_contents	.='</div>';

					if( !empty($acf_fields) || !empty($custom_fields['contents']) || !empty($db_delivery_time) ){
						$counter_checked	= 0;
						$tab_contents	.='<div class="wr-sectiontitle__list--title"><h6>'.esc_html__('Features included','customized-task-offer').'</h6><ul class="wr-sectiontitle__list wr-sectiontitle__listv2">';
						if( !empty($acf_fields) ) {
							foreach($acf_fields as $acf_field ){
								$plan_value	= !empty($acf_field['key']) && !empty($plan[$acf_field['key']]) ? $plan[$acf_field['key']] : '--';
								$counter_checked++;

								$tab_contents	.= workreap_task_package_details($acf_field,$plan_value);
							}
						}
						
						$tab_contents	.= !empty($custom_fields['contents']) ? $custom_fields['contents'] : '';
						if(function_exists('workreap_offer_delivery_time') ){
							$tab_contents	.= workreap_offer_delivery_time($post_id,'v3');
						}
						$tab_contents	.='</ul></div>';
					}				

					$tab_contents	.='';
					$tab_contents	.='</div>';
					if( empty($order_id) && $current_user->ID != $post_author ){
						$tab_contents	.='<div class="wr-sidebarpkg__btn wr-offer-actions">';
							$tab_contents	.='<a href="javascript:void(0)" data-offer-id="'.intval($post->ID).'" data-employer-id="'.intval($current_user->ID).'" data-post-author="'.intval($post_author).'" class="wr-btn workreap-decline-offer">'.esc_html__('Decline offer','customized-task-offer').'</a>';
							$tab_contents	.='<a href="javascript:void(0);" data-url="'.esc_url( $cart_url ).'" data-type="task_cart" class="wr-btn '.esc_attr($checkout_class).'">'.esc_html__('Accept offer','customized-task-offer').'<i class="wr-icon-arrow-right"></i></a>';
						$tab_contents	.='</div>';
					} 
					$tab_contents	.='</div>';
					$tab_contents	.='</div>';
					
				}
			} 
			?>
			
			<div class="tab-content" id="wr_tasktakscontents">
				<?php echo do_shortcode($tab_contents);?>
			</div>
		</div>
	</div>
	<div class="modal fade" id="wr_completeoffer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog wr-modaldialog" role="document">
            <div class="modal-content">
                <div class="wr-popuptitle">
                    <h4 id="wr_project_ratingtitle"><?php esc_html_e('Decline offer','customized-task-offer');?></h4>
                    <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                </div>
                <div class="modal-body wr-taskcomplete_popup" id="wr_offercomplete_form"></div>
            </div>
        </div>
    </div>
	<script type="text/template" id="tmpl-load-cancelled-offer-form">
		<div class="wr-completetask wr-offer-decline">
			<div class="wr-themeform">
 					<div class="wr-themeform__wrap">
						<div class="form-group">
							<textarea class="form-control" id="details" name="details" placeholder="<?php esc_attr_e('Add decline reason','customized-task-offer');?>"></textarea>
						</div>
						<div class="form-group wr-formbtn">
							<ul class="wr-formbtnlist">
								<li id="wr-decline-offer"><a href="javascript:void(0);" class="wr-btn wr_decline_offer" data-offer_id="{{data.offer_id}}" data-post_author="{{data.post_author}}" data-employer_id="{{data.employer_id}}"><?php esc_html_e('Decline offer','customized-task-offer');?></a></li>
							</ul>
						</div>
					</div>
 			</div>
		</div>
	</script>
	<?php 
}
