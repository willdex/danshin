<?php
/**
 * Single offer attachments
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/single-offer/
 */

$is_downalodable    = !empty($is_downalodable) ? $is_downalodable : 0;
$post_id            = !empty($post_id) ? $post_id : 0;
 if( !empty($is_downalodable) && !empty($post_id) ){?>
    <div class="wr-betaversion-wrap wr-offer-attachments">
        <div class="wr-betaversion-info">
            <h5><?php esc_html_e('Attachments available to download','customized-task-offer');?></h5>
        </div>
        <div class="wr-downloadbtn">
            <span class="wr-btn-solid-lefticon wr_download_offer_files" data-id="<?php echo intval($post_id);?>"><?php esc_html_e('Download files','customized-task-offer');?> <i class="wr-icon-download"></i></span>
        </div>
    </div>
<?php } 