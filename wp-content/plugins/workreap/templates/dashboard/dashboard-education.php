<?php
/**
 * User education
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $workreap_settings, $userdata, $post;

$reference 		 = !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode 			 = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? intval($args['id']) : '';
$user_type		 = apply_filters('workreap_get_user_type', $current_user->ID );
$profile_id      = workreap_get_linked_profile_id($user_identity,'',$user_type);
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );
$date_format	 = get_option( 'date_format' );
$wr_post_meta   = get_post_meta( $profile_id,'wr_post_meta',true );
$wr_post_meta   = !empty($wr_post_meta) ? $wr_post_meta : array();

$educations     	= !empty($wr_post_meta['education']) ? $wr_post_meta['education'] : array();
$education_array	= array();
?>
<div class="wr-dhb-profile-settings wr-education-wrapper">
	<div class="wr-tabtasktitle">
		<h5><?php esc_html_e('Educational details','workreap');?></h5>
		<div class="wr-profileform__title--rightarea">
			<a href="javascript:void(0);" data-type="add" class="wr_show_education"><?php esc_html_e('Add new','workreap');?></a>
		</div>
	</div>
	<div class="wr-dhb-box-wrapper">
		<div class="wr-themeform wr-profileform">
			<fieldset>
				<div class="wr-profileform__holder">
					<?php if( !empty($educations) ){?>
						<ul class="wr-detail wr-educationdetail">
							<?php 
							foreach($educations as $key => $value ){
								$degree_title	= !empty($value['title']) ? $value['title'] : '';
								$institute		= !empty($value['institute']) ? $value['institute'] : '';
								$startdate 		= !empty( $value['start_date'] ) ? $value['start_date'] : '';
								$enddate 		= !empty( $value['end_date'] ) ? $value['end_date'] : '';
								$description 	= !empty( $value['description'] ) ? wp_kses_post( stripslashes( $value['description'] ) ) : '';
								$start_date 	= !empty( $startdate ) ? date_i18n($date_format, strtotime(apply_filters('workreap_date_format_fix',$startdate ))) : '';
								$end_date 		= !empty( $enddate ) ? date_i18n($date_format, strtotime(apply_filters('workreap_date_format_fix',$enddate ))) : '';
								
								if( empty( $end_date ) ){
									$end_date = '';
								} else {
									$end_date	= ' - '.$end_date;
								}

								if( !empty( $start_date ) ){
									$period = $start_date.$end_date;
								}

								if( !empty($period) ){
									$institute	= $institute.' - '.$period;
								}

								$education_array[$key]	= $value;
								?>
								<li>
									<div class="wr-detail__content">
										<div class="wr-detail__title">
											<?php if( !empty($institute) ){?>
												<span><?php echo esc_html($institute);?></span>
											<?php } ?>
											<?php if( !empty($degree_title) ){ ?>
												<h6><a href="javascript:void(0);"><?php echo esc_html($degree_title);?></a></h6>
											<?php } ?>
										</div>
										<div class="wr-detail__icon">
											<a href="javascript:void(0);" data-id="<?php echo intval($user_identity);?>" data-type="edit" data-key="<?php echo intval($key);?>" class="wr-edit wr_show_education"><i class="wr-icon-edit-2"></i></a>
											<a href="javascript:void(0);" data-id="<?php echo intval($user_identity);?>" data-key="<?php echo intval($key);?>" class="wr-delete wr_remove_edu"><i class="wr-icon-trash-2"></i></a>
										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>
<script>
	var profile_education = [];
	window.profile_education	= <?php echo json_encode($education_array); ?>
</script>
<script type="text/template" id="tmpl-load-education">
	<form class="wr-themeform wr-formlogin" id="wr_update_education">
		<fieldset>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Add degree title :','workreap');?></label>
				<input type="text" name="education[{{data.counter}}][title]" value="{{data.title}}" class="form-control" placeholder="<?php esc_attr_e('Add degree title','workreap');?>" autocomplete="off">
			</div>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Add institute name :','workreap');?></label>
				<input type="text" name="education[{{data.counter}}][institute]" value="{{data.institute}}" class="form-control" placeholder="<?php esc_attr_e('Add institute name','workreap');?>" autocomplete="off">
			</div>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Choose date','workreap');?></label>
				<div class="wr-themeform__wrap">
					<div class="form-group wr-combine-group">
						<div class="wr-calendar">
							<input id="edu_start_date" value="{{data.start_date}}" name="education[{{data.counter}}][start_date]" type="text" class="form-control dateinit-{{data.counter}}wr-start-pick" placeholder="<?php esc_attr_e('Date from','workreap');?>">
						</div>
						<div class="wr-calendar">
							<input id="edu_end_date" value="{{data.end_date}}" name="education[{{data.counter}}][end_date]" type="text" class="form-control dateinit-{{data.counter}}wr-end-pick" placeholder="<?php esc_attr_e('Date to','workreap');?>">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="form-group-title"><?php esc_html_e('Add description:','workreap');?></label>
				<textarea class="form-control"  name="education[{{data.counter}}][description]" placeholder="<?php esc_attr_e('Description','workreap');?>">{{{data.description}}}</textarea>
			</div>
			<div class="form-group wr-form-btn">
				<div class="wr-savebtn">
					<em><?php esc_html_e('Click “Save & Update” to update your educational details','workreap');?></em>
					<a href="javascript:void(0);" data-mode="{{data.mode}}" data-key="{{data.key}}" data-id="<?php echo intval($user_identity);?>" id="wr_add_education" class="wr-btn"><?php esc_html_e('Save & Update','workreap');?></a>
				</div>
			</div>
		</fieldset>
	</form>
</script>
<div class="modal fade wr-educationpopup" id="wr_educationaldetail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog wr-modaldialog" role="document">
		<div class="modal-content">
			<div class="wr-popuptitle">
				<h4><?php esc_html_e('Add/edit educational details','workreap');?></h4>
				<a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
			</div>
			<div class="modal-body" id="wr_add_education_frm"></div>
		</div>
	</div>
</div>
<?php
$counter = 0;
