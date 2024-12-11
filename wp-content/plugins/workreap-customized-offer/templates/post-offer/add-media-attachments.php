<?php
/**
 *  Add offer media attachments
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $workreap_settings;
$task_downloadable      =  !empty($workreap_settings['task_downloadable']) ? $workreap_settings['task_downloadable'] : '';
$task_max_images        =  !empty($workreap_settings['task_max_images']) ? intval($workreap_settings['task_max_images']) : 3;
if(isset($_GET['postid']) && !empty($_GET['postid'])){
    $post_id = intval($_GET['postid']);
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

$services_upload_area   = '';
if(!empty($offer_gallery) && is_array($offer_gallery) && count($offer_gallery) >= $task_max_images ){
    $services_upload_area   = 'style="display: none;"';
}

?>
<div id="service-media-attachments-wrapper">
    <form id="offer-media-attachments-form" name="service-media-attachments-form" class="wr-themeform service-media-attachments-form" action="<?php echo esc_url($current_page_url);?>" method="post" novalidate enctype="multipart/form-data">
        <fieldset>
            <div class="form-group">
                <div class="wr-postserviceholder">
                    <div class="wr-postservicetitle">
                        <h4><?php esc_html_e('Attachments', 'customized-task-offer');?> <span>(<?php echo sprintf(esc_html__('Add up to %s', 'customized-task-offer'),$task_max_images);?>)</span></h4>
                    </div>
                    <div id="workreap-offer-upload-attachment" class="workreap-fileuploader wr-uploadarea">
                        <div class="wr-uploadbox workreap-dragdroparea" id="workreap-droparea" <?php echo do_shortcode($services_upload_area);?>>
                            <svg>
                                <rect width="100%" height="100%"/>
                            </svg>
                            <i class="wr-icon-upload"></i>
                            <em>
                                <?php echo wp_sprintf( '%1$s <br/> %2$s', esc_html__( 'You can upload media file format only.', 'customized-task-offer'), esc_html__( 'make sure your file size does not exceed 15mb.', 'customized-task-offer') );?>
                                <label for="file1">
                                    <span id="workreap-offer-attachment-btn">
                                        <input id="file1" type="file" name="file">
                                        <?php esc_html_e('Click here to upload', 'customized-task-offer');?>
                                    </span>
                                </label>
                            </em>
                        </div>
                        <ul class="wr-uploadbar wr-bars workreap-fileprocessing" id="workreap-fileprocessing">
						  <?php if(!empty($offer_gallery) ){
							foreach( $offer_gallery as $gallery_image ){

								if(!empty($gallery_image)){
									$url    = $name = '';
                                    $name   = !empty($gallery_image['name']) ? $gallery_image['name'] : '';

									if(!empty($gallery_image['url'])) {
										$file_detail    = Workreap_file_permission::getDecrpytFile($gallery_image);
										$url            = $file_detail['dirname'].'/'.$name;
									}

									$attachment_id  = !empty($gallery_image['attachment_id']) ? $gallery_image['attachment_id'] : '';
									$url            = wp_get_attachment_url( $attachment_id );
									$file_size      = !empty($gallery_image['size']) ? $gallery_image['size'] : '';

									if(empty($name)){
										$name   = get_the_title($attachment_id);
									}
									?>
									<li class="workreap-file-uploaded">
										<div class="wr-filedesciption">
											<span><?php echo esc_html($name);?></span>
											<input type="hidden" class="attachment_url" name="attachments[<?php echo intval($attachment_id);?>][url]" value="<?php echo esc_url($url);?>">
											<input type="hidden" name="attachments[<?php echo intval($attachment_id);?>][attachment_id]" value="<?php echo intval($attachment_id);?>">
											<input type="hidden" name="attachments[<?php echo intval($attachment_id);?>][name]" value="<?php echo esc_attr($name);?>">
                                            <span class="wr-preview">
                                                <a href="<?php echo esc_url($url);?>" download><?php esc_html_e('View', 'customized-task-offer');?></a>
                                            </span>
											<em class="wr-remove"><a href="javascript:void(0)"  class="workreap-remove-attachment wr-gallery-attachment" data-attachment_id="<?php echo intval($attachment_id);?>"><?php esc_html_e('Remove', 'customized-task-offer');?></a></em>
										</div>
									</li>
						   <?php }}}?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php do_action('workreap_offer_media_fields_after', $args);?>
            <div class="form-group wr-postserviceformbtn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save &amp; Update” to update your media/attachments.', 'customized-task-offer');?></span>
                    <button type="submit" class="wr-btn wr-upload-media-attachments"><?php esc_html_e('Save &amp; Update', 'customized-task-offer');?></button>
                    <input type="hidden" name="post_id" value="<?php echo (int)$post_id;?>">
                </div>
            </div>
        </fieldset>
        <script type="text/template" id="tmpl-load-service-media-attachments">
            <li id="thumb-{{data.id}}" class="workreap-list">
                <div class="wr-filedesciption">
                    <span>{{data.name}}</span>
                    <input type="hidden" class="attachment_url" name="attachments[{{data.attachment_id}}]" value="{{data.url}}">
                    <span class="wr-preview">
                        <a href="{{data.url}}" class="wr-downalod-file" download><?php esc_html_e('View', 'customized-task-offer');?></a>
                    </span>
                    <em class="wr-remove"><a href="javascript:void(0)" class="workreap-remove-attachment wr-gallery-attachment"><?php esc_html_e('Remove', 'customized-task-offer');?></a></em>
                </div>
                <div class="progress">
                    <div class="progress-bar uploadprogressbar" style="width:0%"></div>
                </div>
            </li>
        </script>
    </form>
</div>
<?php
$script_video = '
jQuery(document).ready(function () {
    let venobox = document.querySelector(".venobox-gallery");
    if (venobox !== null) {
        jQuery(".venobox-gallery").venobox({
            spinner : "cube-grid",
        });
    }
})
';
wp_add_inline_script('venobox', $script_video, 'after');