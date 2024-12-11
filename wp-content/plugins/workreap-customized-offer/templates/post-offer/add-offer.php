<?php
/**
 *  Add offer basic template
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $workreap_settings;

if (!class_exists('WooCommerce')) {
    return;
}


$lists      = workreap_task_offer_list();
$step       = !empty($_GET['step']) ? intval($_GET['step']) : 1;
$post_id    = !empty($_GET['post']) ? intval($_GET['post']) : 0;
$page_url   = !empty($workreap_settings['tpl_add_offer_page']) ? get_permalink($workreap_settings['tpl_add_offer_page']) : '';
$post_status= !empty($post_id) ? get_post_status( $post_id ) : '';
$offer_status   = !empty($post_id) ? get_post_status ( $post_id ) : '';
$offer_decline_reason   =  !empty($post_id) ?  get_post_meta( $post_id, 'decline_reason',true ) : '' ; 
?>
<div class="wr-postservice wr-postservicev2">
    <?php if(!empty($offer_status) && $offer_status == 'rejected'){?>
        <div class="wr-declinerequest wr-alert-information">
            <div class="wr-orderrequest">
                <div class="wr-ordertitle">
                    <h4><?php esc_html_e('Offer declined', 'customized-task-offer'); ?></h4>
                    <p><?php esc_html_e('Your customized offer has been declined by the employer. You can update the offer and send it again.', 'customized-task-offer'); ?></p>
                    <h5><?php esc_html_e('Reason:', 'customized-task-offer'); ?></h5>
                    <?php if(!empty($offer_decline_reason)){?>
                        <p><?php echo esc_html($offer_decline_reason)?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <ul class="wr-addservice-steps wr-offer-reponsice">
        <?php foreach($lists as $key => $value ){
            $title  = !empty($value['title']) ? $value['title'] : '';
            $class  = !empty($value['class']) ? $value['class'] : '';
            $active_class   = '';

            if( !empty($step) && $step > $key ){
                $active_class   = 'wr-addservice-step-complete';
            }

            if( !empty($step) && $step == $key ){
                $active_class   = 'wr-addservice-step-fill';
            }

            $step_url       = '#';
            $steps_class    = 'service-steps';

            if( !empty($post_id)){
                $steps_class    = 'service-steps service-steps-draft';
            }
            
            if( !empty($post_status) && $post_status == 'publish' ){
                $step_url   = add_query_arg( array(
                    'post' => $post_id,
                    'step' => $key,
                ), $page_url );
                $steps_class    = 'service-steps';
            }?>
            <li>
                <div class="task-step-<?php echo intval($key);?> <?php echo esc_attr($class);?> <?php echo esc_attr($active_class);?>">
                    <a href="<?php echo esc_url($step_url);?>" class="<?php echo esc_attr($steps_class);?>"><span><?php echo esc_html($title);?></span></a>
                </div>
            </li>
        <?php } ?>
    </ul>
    <?php do_action('workreap_add_offers_steps_before'); ?>
    <div class="wr-addservices-steps wr-offer-wrapper" id="wr-services-steps">
        <?php do_action('workreap_add_offer_steps', $args);?>
    </div>
    <?php do_action('workreap_add_offers_steps_after'); ?>
</div>