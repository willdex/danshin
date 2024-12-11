<?php
/**
 *  Offer plans
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global  $workreap_settings, $current_user;
$package_option         =  !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'],array('paid','employer_free')) ? true : false;
$custom_field_option    =  !empty($workreap_settings['custom_field_option']) ? $workreap_settings['custom_field_option'] : false;
$packages_listing_page  = 'javascript:void(0);';
if (!empty($package_option)) {
    $packages_listing_page  = !empty($workreap_settings['tpl_package_page']) ? get_the_permalink($workreap_settings['tpl_package_page']) : 'javascript:void(0);';
}
$enable_ai      = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_custom_offer']) ? true : false;
$ai_classs      = !empty($enable_ai) ? 'wr-input-ai' : '';

if(!empty($_GET['postid'])){
    $post_id = $_GET['postid'];
}

if ( !class_exists('WooCommerce') ) {
	return;
}
$current_page_url   = get_the_permalink();
if(isset($_GET['post']) && !empty($_GET['post'])){
    $post_id = intval($_GET['post']);
    $current_page_url   = add_query_arg('post', $post_id, $current_page_url);
}

if(isset($_GET['step']) && !empty($_GET['step'])){
    $step = intval($_GET['step']);
    $current_page_url   = add_query_arg('step', $step, $current_page_url);
}

if( !empty($custom_field_option) ){
    $wr_custom_fields       = get_post_meta( $post_id, 'wr_custom_fields',true );
    $wr_custom_fields       = !empty($wr_custom_fields) ? $wr_custom_fields : array();
}

$workreap_plans_values   = get_post_meta($post_id, 'workreap_product_plans', TRUE);
$workreap_service_plans  = Workreap_Service_Plans::service_plans();
$workreap_service_plans  = array('basic'=>'Basic');

$workreap_service_keys   = !empty($workreap_service_plans) && is_array($workreap_service_plans) ? array_keys($workreap_service_plans) : array();
$workreap_overlay_class  = 'wr-active-pricing';
if($task_plans_allowed == 'no'){
    $workreap_overlay_class  = 'wr-overley-pricing';
}

$groups = array();
if(class_exists('ACF')) {
    $groups = acf_get_field_groups();
}

$user_id = $current_user->ID;
$args = array(
    'limit'     => -1, // All products
    'status'    => 'publish',
    'type'      => 'subtasks',
    'orderby'   => 'date',
    'order'     => 'DESC',
    'author'    => $user_id
);
$workreap_subtasks = wc_get_products( $args );

$group_locations_html = '';
if( !empty($groups) ){
    foreach($groups as $group){
        foreach( $group['location'] as $group_locations ) {
            $workreap_plan_category  = '';
            $product_plans_category = 'am-plans-category';
            $found_key              = array_search('product_plans_category', array_column($group_locations, 'param'));

            if($found_key){
                $group_location_category = $group_locations[$found_key];

                if(isset($group_location_category['param']) && $group_location_category['param'] == 'product_plans_category'
                    && !empty($group_location_category['value']) ){
                    $product_plans_category .= ' am-category-'.$group_location_category['value'];
                    $workreap_plan_category = $group_location_category['value'];
                }

            }

            $product_plans_category = apply_filters('workreap_product_plans_category', $product_plans_category);
            $found_key = '';
            if(!empty($service_categories) && is_array($service_categories) && !in_array($workreap_plan_category, $service_categories)){
                continue;
            }
            foreach( $group_locations as $rule ) {
                if( $rule['param'] == 'product_tabs' && $rule['operator'] == '==' && $rule['value'] == 'plan' ) {
                    ob_start();
                    do_action('workreap_plan_group_fields_before', $group, $workreap_service_plans, $workreap_plans_values, $product_plans_category);
                    do_action('workreap_acf_dynamically_render_fields', acf_get_fields( $group ), $workreap_service_plans, $workreap_plans_values, $product_plans_category);
                    do_action('workreap_plan_group_fields_after', $group, $workreap_service_plans, $workreap_plans_values, $product_plans_category);
                    $group_data_html   = ob_get_clean();
                    $group_locations_html   = $group_locations_html.$group_data_html;
                    break 2;
                }

            }
        }
    }
}
?>
<div id="service-pricing-wrapper">
    <form id="offer-plans-form" class="wr-themeform offer-plans-form" name="offer-plans-form" action="<?php echo esc_url($current_page_url);?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="form-group">
                <div class="form-group form-group-half form-group_vertical">
                    <label class="form-group-title"><?php esc_html_e('Offer price:', 'customized-task-offer'); ?></label>
                    <input type="text" value="<?php echo esc_attr($offer_price); ?>" class="form-control" name="workreap_offer[offer_price]" placeholder="<?php esc_attr_e('Enter your offer price', 'customized-task-offer'); ?>" autocomplete="off" required="required">
                </div>
                <div class="form-group form-group-half form-group_vertical" id="wr-task-delivery-time">
                    <label class="form-group-title"><?php esc_html_e('Delivery time:', 'customized-task-offer'); ?></label>
                    <span class="wr-select">
                        <?php 
                            $workreap_args = array(
                                'show_option_none'  => esc_html__('Choose delivery time', 'customized-task-offer'),
                                'option_none_value' => '',
                                'show_count'        => false,
                                'hide_empty'        => false,
                                'name'              => 'workreap_offer[delivery_time]',
                                'class'             => 'service-dropdwon',
                                'taxonomy'          => 'delivery_time',
                                'id'                => 'wr-delivery-time',
                                'value_field'       => 'term_id',
                                'orderby'           => 'name',
                                'selected'          => $delivery_time,
                                'hide_if_empty'     => false,
                                'echo'              => true,
                                'required'          => false,
                                'parent'            => 0,

                            );
                            do_action('workreap_taxonomy_dropdown', $workreap_args);
                        ?>
                    </span>
                </div>
                <div class="form-group wr-message-text <?php echo esc_attr($ai_classs);?>">
                    <label class="form-group-title"><?php esc_html_e('Add description', 'customized-task-offer');?></label>
                    <?php if(!empty($enable_ai)){
	                    do_action( 'workreapAIContent', 'custom-offer-description-'.$post_id,'custom_offer_content' );
                    } ?>
                    <textarea class="form-control wr-form-input workreap-offer-description-value" data-ai_content_id="custom-offer-description-<?php echo esc_attr($post_id);?>" id="workreap-offer-description" name="workreap_offer[offer_content]" required placeholder="<?php esc_attr_e('Enter description', 'customized-task-offer'); ?>"  maxlength="500"><?php echo do_shortcode($offer_content);?></textarea>
                    <div class="wr-input-counter">
                        <span><?php esc_html_e('Characters count', 'customized-task-offer');?>:</span>
                        <b class="wr_current_comment"><?php echo intval(500);?></b>
                        /
                        <em class="wr_maximum_comment"> <?php echo intval(500);?></em>
                    </div>
                </div>  

                <?php if( !empty($group_locations_html) ){?>
                    <label class="form-group-title"><?php esc_html_e('Default features of task', 'customized-task-offer');?></label>
                    <div class="wr-pricingcontainer wr-pricingcontainertwo">
                        <div class="wr-pricing-items">
                            <ul class="wr-pricingitems wr-offers-pricing <?php echo esc_attr($workreap_overlay_class);?>">
                                <?php 
                                    echo do_shortcode( $group_locations_html );
                                ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <?php do_action('workreap_offers_plans_fields_after', $workreap_service_plans, $workreap_plans_values); ?>
            </div>
            <?php if( !empty($custom_field_option) ){?>
                <div class="form-group">
                    <div class="wr-postserviceholder">
                        <div class="wr-postservicetitle">
                            <h4><label class="form-group-title"><?php esc_html_e('Add custom fields','customized-task-offer');?></label></h4>
                            <a href="javascript:void(0);" id="wr_add_customfields" data-heading="<?php esc_attr_e('Add more','customized-task-offer');?>" title="<?php esc_attr_e('Add more','customized-task-offer');?>"><?php esc_html_e('Add more','customized-task-offer');?></a>
                        </div>
                        <div class="wr-pricing-items wr-custom-pricing">
                            <ul class="wr-custom-fields wr-offers-custom-fields" id="wr-customfields-ul">
                                <?php if( !empty($wr_custom_fields) ){
                                    foreach($wr_custom_fields as $key => $wr_custom_field){
                                        $title  = !empty($wr_custom_field['title']) ? $wr_custom_field['title'] : '';
                                    ?>
                                    <li id="fields-<?php echo esc_attr($key);?>" class="am-plans-category am-category-33 content_upload_to_zip_file">
                                        <div class="wr-pricingitems__content">
                                            
                                            <div class="wr-pricingtitle form-field wr-pricing-input">
                                                <input type="text" name="custom_fields[<?php echo esc_attr($key);?>][title]" value="<?php echo esc_attr($title);?>" class="form-control wr-cf-title-input" placeholder="<?php esc_attr_e('Add title','customized-task-offer');?>" autocomplete="off">
                                            </div>
                                            <?php foreach($workreap_service_keys as $workreap_keys){
                                                $package_value  = !empty($wr_custom_field[$workreap_keys]) ? $wr_custom_field[$workreap_keys] : '';
                                                ?>
                                                <div class="form-field wr-pricing-input">
                                                    <input type="text" name="custom_fields[<?php echo esc_attr($key);?>][<?php echo esc_attr($workreap_keys);?>]" value="<?php echo esc_attr($package_value);?>" class="form-control " placeholder="<?php esc_attr_e('Add value','customized-task-offer');?>" autocomplete="off">
                                                </div>
                                            <?php } ?>
                                            <div class="form-field wr-remove-field">
                                                <div class="wr-trashlink">
                                                    <i class="wr-icon-trash-2"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }
                                }?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group wr-postserviceformbtn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save & Update” to update pricings', 'customized-task-offer'); ?></span>
                    <button type="submit" class="wr-btn"><?php esc_html_e('Save & Update', 'customized-task-offer'); ?></button>
                    <input type="hidden" id="service_id" name="post_id" value="<?php echo (int)$post_id; ?>">
                    <input type="hidden" id="add_service_step" name="step" value="<?php echo (int)$step; ?>">
                </div>
            </div>
        </fieldset>
    </form>
    <?php if( !empty($custom_field_option) ){?>
        <script type="text/template" id="tmpl-load-service-custom_fields">
            <li id="fields-{{data.id}}" class="wr-pricing-input am-plans-category am-category-33 content_upload_to_zip_file">
                <div class="wr-pricingitems__content">
                   
                    <div class="wr-pricingtitle form-field wr-pricing-input">
                            <input type="text" name="custom_fields[{{data.id}}][title]" value="" class="form-control wr-cf-title-input" placeholder="<?php esc_attr_e('Add title','customized-task-offer');?>" autocomplete="off">
                        </div>
                    <?php foreach($workreap_service_keys as $workreap_keys){?>
                        <div class="form-field wr-pricing-input">
                            <input type="text" name="custom_fields[{{data.id}}][<?php echo esc_attr($workreap_keys);?>]" value="" class="form-control " placeholder="<?php esc_attr_e('Add value','customized-task-offer');?>" autocomplete="off">
                        </div>
                    <?php } ?>
                    <div class="form-field wr-remove-field">
                        <div class="wr-trashlink">
                            <i class="wr-icon-trash-2"></i>
                        </div>
                    </div>
                </div>
            </li>
        </script>
    <?php } ?>
</div>