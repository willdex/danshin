<?php
/**
 * Single task author details
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/single-offer/
 */
global $post;
$plans			= !empty($workreap_plans_values) ? $workreap_plans_values : array();
$total_price	= 0;
$select_options	= '';

if( !empty($plans) ){
	$counter	= 0;
	foreach($plans as $key => $plan ){
		$selected		= '';
		
		if( empty($counter) ){
			$total_price	= $price;
			$selected		= 'selected';
		}

		if( !empty($title)){
			$select_options .= '<option id="wr-op-'.esc_attr($key).'" '.esc_attr($selected).' value="'.esc_attr($key).'" data-price="'.esc_attr($price).'">'.esc_html($key).'</option>';
		}

		$counter++;
	}
}
?>
<div class="wr-fixsidebar" id="wr-fixsidebar" style="display:none;">
	<div class="wr-messageuser">
		<div class="wr-tasktotalbudget">
			<div class="wr-tasktotal">
				<h5><?php esc_html_e('Add task details','customized-task-offer');?></h5>
			</div>
			<a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
		</div>
	</div>
	<div class="wr-addformtask">
		<div class="wr-packages__plan">
			<h6><em data-tippy-content="<?php esc_attr_e('Attachments','customized-task-offer');?>" class="tippy icon-info"></em> <?php esc_html_e('Total task budget','customized-task-offer');?></h6>
			<h4 id="wr_total_price"><?php workreap_price_format($total_price);?></h4>
		</div>
		<form class="wr-themeform wr-sidebarform" id="wr_cart_form">
			<fieldset>
				<div class="form-group-wrap">
					<div class="form-group form-vertical">
						<label class="wr-titleinput"><?php esc_html_e('Selected service:','customized-task-offer');?></label>
						<input type="text" class="form-control disable" placeholder="<?php echo esc_attr($title);?>">
					</div>
					<div class="form-group form-vertical">
						<label class="wr-titleinput"><?php esc_html_e('Choose package plan:','customized-task-offer');?></label>
						<div class="wr-select">
							<select class="form-control wr_project_task" id="wr_task_cart" data-task_id="<?php echo intval($task_id);?>" name="product_task">
								<?php echo do_shortcode( $select_options );?>
							</select>
						</div>
					</div>
					<div class="form-group m-0">
						<div class="wr-popupbtnarea">
							<a href="javascript:void(0);" data-id="<?php echo intval($task_id);?>" class="wr-btn" id="wr_btn_cart"><?php esc_html_e('Hire now','customized-task-offer');?><span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
						</div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>