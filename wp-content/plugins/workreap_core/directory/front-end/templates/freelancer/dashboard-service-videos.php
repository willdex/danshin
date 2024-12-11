<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		= $linked_profile;
$videos 		= array();

$edit_id 		= !empty($_GET['id']) ? intval($_GET['id']) : '';
if( !empty( $edit_id ) ){
	if (function_exists('fw_get_db_post_option')) {
		$videos = fw_get_db_post_option($edit_id, 'videos', true);		
	}
}

?>
<div class="wt-videosdataholder wt-tabsinfo">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Add Your Videos', 'workreap'); ?></h2>
		<span class="wt-add-video"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add Video URL', 'workreap'); ?></a></span>
	</div>
	<ul class="wt-experienceaccordion">
		<?php 
		if( !empty( $videos ) && is_array($videos) ) {
			foreach ($videos as $key => $video) {
				$rand = rand(999999, 99999);
				?>
				<li class="wt-videos-item">
					<div class="wt-accordioninnertitle">
						<div class="form-group">
							<input type="text" value="<?php echo esc_url( $video );?>" name="service[videos][]" class="wt-input-title form-control" placeholder="<?php esc_attr_e('Video URL', 'workreap'); ?>">
						</div>
						<div class="wt-rightarea">
							<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
						</div>
					</div>
				</li>		
		<?php } } ?>													
	</ul>
</div>
<script type="text/template" id="tmpl-load-videos">
<li data-id="{{data.counter}}" class="wt-videos-item">
	<div class="wt-accordioninnertitle">
		<div class="form-group">
			<input type="text" name="service[videos][]" class="wt-input-title form-control" placeholder="<?php esc_attr_e('Video URL', 'workreap'); ?>">
		</div>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
		</div>
	</div>
</li>
</script>