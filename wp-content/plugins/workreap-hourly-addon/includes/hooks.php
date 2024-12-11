<?php

/**
 * Proposal basic details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_employer_project_basic_details')){
	function workreap_employer_project_basic_details($proposal_id=0) {
        global $current_user;
        $proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
        $proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array();
        $project_id         = get_post_meta( $proposal_id, 'project_id', true );
        $project_id         = !empty($project_id) ? intval($project_id) : 0;
        $max_hours          = get_post_meta( $project_id, 'max_hours', true );
        $max_hours          = isset($max_hours) ? $max_hours : 0;
        $project_meta       = get_post_meta( $project_id, 'wr_project_meta', true );
        $project_meta       = !empty($project_meta) ? $project_meta : array();
        $time_slots         = get_post_meta( $proposal_id, 'wr_timetracking',true );
        $time_slots         = !empty($time_slots) ? $time_slots : array();
        $post_status        = get_post_status( $proposal_id );
        $payment_mode       = !empty($project_meta['payment_mode']) ? workreap_payment_mode('key',$project_meta['payment_mode']) : "";
        $transaction_id     = !empty($_GET['transaction_id']) ? intval($_GET['transaction_id']) : '';
        $ul_classs          = 'wr-timeslot_list';

        if( !empty($payment_mode) && $payment_mode === 'month'){
            $ul_classs              = 'wr-addtime-slot';
            if( empty($transaction_id) ){
                $formated_current_date  = date('Y-m-01');
                $transaction_id         = !empty($formated_current_date) ? strtotime($formated_current_date) : 0;
            }

            $mode_title = esc_html__('Month','workreap-hourly-addon');
        } else if( !empty($payment_mode) && $payment_mode === 'week' ){
            $ul_classs          = 'wr-timeslot_list';
            if( empty($transaction_id) ){
                $interval           = workreap_get_weekrang(date('Y-m-d'));
                $transaction_id     = !empty($interval['start_time']) ? strtotime($interval['start_time']) : 0;
            }

            $mode_title = esc_html__('Week','workreap-hourly-addon');
        } else if( !empty($payment_mode) && $payment_mode === 'day' ){
            $ul_classs          = 'wr-today-timeslot';
            if( empty($transaction_id) ){
                $transaction_id     = strtotime(date('Y-m-d'));
            }

            $mode_title = esc_html__('Day','workreap-hourly-addon');
        }

        $mode_title     = !empty($mode_title) ? $mode_title : $payment_mode;

        $transaction_date   = !empty($transaction_id) ? date('Y-m-d',$transaction_id) : date('Y-m-d');
        $time_slot_status   = !empty($time_slots[$transaction_id]['status']) ? $time_slots[$transaction_id]['status'] : '';
        $total_hours        = !empty($time_slots[$transaction_id]['total_time']) ? $time_slots[$transaction_id]['total_time'] : 0;
        $price              = !empty($proposal_meta['price']) ? $proposal_meta['price'] : 0;
        $intervals          = array();
        
        $user_balance       = get_user_meta( $current_user->ID, '_employer_balance', true );
        $user_balance       = !empty($user_balance) ? $user_balance : 0;

        if( !empty($user_balance) ){
            $scrow_class    = 'wr_hourly_proposal_hiring';
        } else {
            $scrow_class    = 'wr_hourly_slot_payment';
        }

        $checkout_class     = 'wr_approve_hours';
        $hourly_image	    = workreap_add_http_protcol(WORKREAP_HOURLY_ADDON_URI . 'public/images/hourly-escrow.png');
        $carried_text       = sprintf(__('Your remaining total excessive amount will be carried forward to the next %s for your use.','workreap-hourly-addon'),$mode_title);
        ob_start();
        ?>
        <div class="wr-project-wrapper wr-timecardwraper">
            <?php do_action( 'workreap_hourly_stats_status', $time_slots,$transaction_id,$price,$payment_mode,$proposal_id );?>
            <div class="wr-timecards">
                <div class="wr-timecards_head">
                    <h5>
                        <?php esc_html_e('Timecard activities','workreap-hourly-addon');?>
                        <?php
                            if( !empty($time_slot_status) && !empty($time_slot_status) && in_array($time_slot_status,array('completed','pending','decline')) ) {
                                do_action( 'workreap_hourly_status', $time_slot_status );
                            } 
                        ?>
                    </h5>
                    <div class="wr-actionselect">
                        <?php if( !empty($time_slots[$transaction_id]) && isset($total_hours) && !empty($time_slot_status) && in_array($time_slot_status,array('completed','pending','decline'))) {?>
                            <div class="wr-timecards_total">
                                <h6><?php esc_html_e('Total hours:','workreap-hourly-addon');?></h6>
                                <span>
                                <?php echo esc_html($total_hours);?>/
                                    <em><?php echo esc_html($max_hours);?><?php do_action('workreap_tooltip', '<i class="wr-icon-alert-circle"></i>', '',$carried_text);?></em>
                                </span>
                            </div>
                        <?php } else { ?>
                            <span><?php esc_html_e('Filter by','workreap-hourly-addon');?> </span>
                        <?php } ?>
                        <?php 
                            if( !empty($payment_mode) && $payment_mode === 'week' ){
                                do_action( 'workreap_hourly_week_intervals', $proposal_id,$transaction_id );
                            } else if( !empty($payment_mode) && $payment_mode === 'day' ){
                                do_action( 'workreap_hourly_daily_intervals', $proposal_id,$transaction_id );
                            } else if( !empty($payment_mode) && $payment_mode === 'month' ){
                                $intervals  = workreap_monthly_intervals($proposal_id);
                                do_action( 'workreap_hourly_monthly_intervals', $proposal_id,$transaction_id,$intervals );
                            }
                        ?>
                    </div>
                </div>
                <?php if( !empty($time_slots[$transaction_id]['decline_detail'])){?>
                    <div class="wr-statusview_alert">
                        <span><i class="wr-icon-info"></i><?php esc_html_e('The employer declined this milestone invoice. Read the comment below and try again','workreap-hourly-addon');?></span>
                        <p><?php echo esc_html($time_slots[$transaction_id]['decline_detail']);?></p>
                    </div>
                <?php } ?>
                <?php  
                    if(!empty($time_slot_status) && in_array($time_slot_status,array('completed','pending','decline')) ){
                        do_action( 'workreap_hourly_list', $proposal_id,$transaction_id,$time_slots );
                    }  else if( empty($time_slot_status) ) { ?>
                        <div class="wr-betaversion-wrap wr-betaversion-wrap-two">
                            <img src="<?php echo esc_url($hourly_image);?>" alt="<?php esc_attr_e('Escrow','workreap-hourly-addon');?> ">
                            <div class="wr-unlock-week">
                                <h5><?php echo sprintf(__('Escrow payment and enable this %s','workreap-hourly-addon'),$mode_title);?></h5>
                                <p><?php echo sprintf(__('Have more work to do with freelancer? In order to unlock this %s, you need to escrow this %s payment. Hit the “Escrow & unlock %s” button to get started.','workreap-hourly-addon'),$mode_title,$mode_title,$mode_title);?></p>
                                <?php if( !empty($post_status) && $post_status === 'hired' ){?>
                                    <button data-id="<?php echo intval($proposal_id);?>" data-key="<?php echo intval($transaction_id);?>" class="wr-btn-line-lefticon wr-success-tag <?php echo esc_attr($scrow_class);?>"> <i class="wr-icon-unlock"></i> <?php echo sprintf(__('Escrow & unlock %s','workreap-hourly-addon'),$mode_title);?></button>
                                <?php } ?>
                            </div>
                        </div>
                        <?php 
                    } else {
                        do_action( 'workreap_empty_listing', esc_html__('Seller is not submit any hours for this time interval','workreap-hourly-addon'));
                    }
                ?>
                <?php if( !empty($time_slot_status) && !empty($time_slot_status) && in_array($time_slot_status,array('pending')) && !empty($post_status) && $post_status === 'hired' ) {?>
                    <div class="wr-statusview_btns">
                        <a href="javascript:void(0);" class="wr-btn_approve <?php echo esc_attr($checkout_class);?>" data-key="<?php echo esc_attr($transaction_id);?>" data-id="<?php echo intval($proposal_id);?>" id="wr-toaster-notification"><?php esc_html_e('Approve','workreap-hourly-addon');?></a>
                        <a href="javascript:void(0);" data-bs-target="#wr_hourly_decline" data-bs-toggle="modal" class="wr-btn_decline"><?php esc_html_e('Decline','workreap-hourly-addon');?></a>
                        <p><?php esc_html_e('Read above details before doing any action','workreap-hourly-addon');?></p>
                    </div>
                    <div class="modal fade wr-declinereason" id="wr_hourly_decline" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="wr-popup_title">
                                    <h5><?php esc_html_e('Add decline reason below','workreap-hourly-addon');?></h5>
                                    <a href="javascrcript:void(0)" data-bs-dismiss="modal">
                                        <i class="wr-icon-x"></i>
                                    </a>
                                </div>
                                <div class="modal-body wr-popup-content">
                                    <form class="wr-themeform">
                                        <fieldset>
                                            <div class="wr-themeform__wrap">
                                                <div class="form-group">
                                                    <div class="wr-placeholderholder">
                                                        <textarea name="description" id="wr_decline_detail" class="form-control wr-themeinput"></textarea>
                                                    </div>
                                                </div>
                                                <div class="wr-popup-terms form-group">
                                                    <button type="button" data-transaction_id="<?php echo intval($transaction_id);?>" data-proposal_id="<?php echo intval($proposal_id);?>" class="wr-btn-solid-lg wr_decline_hourly"><?php esc_html_e('Submit question now','workreap-hourly-addon');?><i class="wr-icon-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $script	= "
        ( function ( $ ) {
            'use strict'; 
            jQuery(document).ready(function($){
                jQuery(document).on('change', '#wr_order_type', function (e) {
                    let _this       = jQuery(this);
                    let page_url = _this.find(':selected').data('url');
                    alert(page_url);
                    window.location.replace(page_url);
                });
                let classes = [
                    '.wr-timecards',
                    '.wr-timecards',
                    '.wr-timecards',
                    '.wr-timecards'
                ];
                for ( let i = 0; i < classes.length; ++i) {
                    if (classes[i].length <= 3) {
                      jQuery('.wr-show_more').hide();
                    } 
                    else if (classes[i].length >= 3) {
                      jQuery('.wr-show_more').show();
                      jQuery('.wr-timecard-table tbody tr:nth-child(n+4)').hide();
                      jQuery('#wr-timealert-table table tbody tr:nth-child(n+4)').hide();
                    }
                  }
                
                  jQuery('.wr-show_more').on('click', function() {
                    jQuery(this).text($(this).text() === scripts_vars.show_less ? scripts_vars.show_all : scripts_vars.show_less);
                    jQuery(this).closest('.wr-timecards').find('.wr-timecard-table tbody tr:nth-child(n+4)').slideToggle('500','linear');
                    jQuery(this).closest('.wr-timecards').find('#wr-timealert-table tbody tr:nth-child(n+4)').slideToggle('500','linear');
                  });
            });
        } ( jQuery ) );
        ";
        wp_add_inline_script( 'workreap-hourly-project', $script, 'after' );
        echo ob_get_clean();
	}
    add_action('workreap_employer_project_basic_details', 'workreap_employer_project_basic_details');
}

/**
 * Proposal basic details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_basic_details')){
	function workreap_project_basic_details($proposal_id=0) {
        $proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
        $proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array();
        $project_id         = get_post_meta( $proposal_id, 'project_id', true );
        $project_id         = !empty($project_id) ? intval($project_id) : 0;
        $project_meta       = get_post_meta( $project_id, 'wr_project_meta', true );
        $project_meta       = !empty($project_meta) ? $project_meta : array();
        $time_slots         = get_post_meta( $proposal_id, 'wr_timetracking',true );
        $hiring_date        = get_post_meta( $proposal_id, 'hiring_date',true );
        $time_slots         = !empty($time_slots) ? $time_slots : array();
        $hiring_date        = date('Y-m-d',strtotime($hiring_date));
        
        $max_hours          = get_post_meta( $project_id, 'max_hours', true );
        $max_hours          = isset($max_hours) ? $max_hours : 0;
        
        $payment_mode       = !empty($project_meta['payment_mode']) ? workreap_payment_mode('key',$project_meta['payment_mode']) : "";
        $transaction_id     = !empty($_GET['transaction_id']) ? intval($_GET['transaction_id']) : 0;
        $ul_classs          = 'wr-timeslot_list';

        if( !empty($payment_mode) && $payment_mode === 'month'){
            $ul_classs              = 'wr-addtime-slot';
            if( empty($transaction_id) ){
                $formated_current_date  = date('Y-m-01');
                $transaction_id         = !empty($formated_current_date) ? strtotime($formated_current_date) : 0;
            }

            $mode_title = esc_html__('Month','workreap-hourly-addon');
        } else if( !empty($payment_mode) && $payment_mode === 'week' ){
            $ul_classs          = 'wr-timeslot_list';
            if( empty($transaction_id) ){
                $interval           = workreap_get_weekrang($hiring_date);
                $transaction_id     = !empty($interval['start_time']) ? strtotime($interval['start_time']) : 0;
                $week_range         = $interval;
                //$transaction_id           = strtotime($hiring_date);
            }

            $mode_title = esc_html__('Week','workreap-hourly-addon');
        } else if( !empty($payment_mode) && $payment_mode === 'day' ){
            $ul_classs          = 'wr-today-timeslot';
            if( empty($transaction_id) ){
                $formated_current_date  = date('Y-m-d');
                $transaction_id         = !empty($formated_current_date) ? strtotime($formated_current_date) : 0;
            }

            $mode_title = esc_html__('Day','workreap-hourly-addon');
        }

        $mode_title     = !empty($mode_title) ? $mode_title : $payment_mode;

        $transaction_date   = !empty($transaction_id) ? date('Y-m-d',$transaction_id) : date('Y-m-d');
        $time_slot_status   = !empty($time_slots[$transaction_id]['status']) ? $time_slots[$transaction_id]['status'] : '';
        $total_hours        = !empty($time_slots[$transaction_id]['total_time']) ? $time_slots[$transaction_id]['total_time'] : 0;
        $price              = !empty($proposal_meta['price']) ? $proposal_meta['price'] : 0;
        $intervals          = array();
        $hourly_image	    = workreap_add_http_protcol(WORKREAP_HOURLY_ADDON_URI . 'public/images/hourly-pending-escrow.png');
        ob_start();
        ?>
        <div class="wr-project-wrapper wr-timecardwraper">
            <?php do_action( 'workreap_hourly_stats_status', $time_slots,$transaction_id,$price,$payment_mode,$proposal_id);?>
            <div class="wr-timecards">
                <div class="wr-timecards_head">
                    <h5>
                    <?php 
                        if( !empty($payment_mode) && $payment_mode === 'week' ){
                            esc_html_e('Add this week timecard','workreap-hourly-addon');
                        } else if( !empty($payment_mode) && $payment_mode === 'day' ){
                            esc_html_e('Add this day timecard','workreap-hourly-addon');
                        } else if( !empty($payment_mode) && $payment_mode === 'month' ){
                            esc_html_e('Add this month timecard','workreap-hourly-addon');
                        } 
                    ?>
                    </h5>
                    <div class="wr-actionselect">
                        <?php if( !empty($time_slots[$transaction_id]) && isset($total_hours) && !empty($time_slot_status) && in_array($time_slot_status,array('completed','pending','decline'))) {?>
                                <div class="wr-timecards_total">
                                    <h6><?php esc_html_e('Total hours:','workreap-hourly-addon');?></h6>
                                    <span>
                                        <?php echo esc_html($total_hours);?>
                                    </span>
                                </div>
                            <?php } else { ?>
                                <span><?php esc_html_e('Filter by','workreap-hourly-addon');?> </span>
                            <?php } ?>
                        <?php 
                            if( !empty($payment_mode) && $payment_mode === 'week' ){
                                do_action( 'workreap_hourly_week_intervals', $proposal_id,$transaction_id );
                            } else if( !empty($payment_mode) && $payment_mode === 'day' ){
                                do_action( 'workreap_hourly_daily_intervals', $proposal_id,$transaction_id );
                            } else if( !empty($payment_mode) && $payment_mode === 'month' ){
                                $intervals  = workreap_monthly_intervals($proposal_id);
                                do_action( 'workreap_hourly_monthly_intervals', $proposal_id,$transaction_id,$intervals );
                            }
                        ?>
                    </div>
                </div>
                <?php if( !empty($time_slots[$transaction_id]['decline_detail'])){?>
                    <div class="wr-statusview_alert">
                        <span><i class="wr-icon-info"></i><?php esc_html_e('The employer declined this milestone invoice. Read the comment below and try again','workreap-hourly-addon');?></span>
                        <p><?php echo esc_html($time_slots[$transaction_id]['decline_detail']);?></p>
                    </div>
                <?php } ?>
                <?php if( !empty($time_slot_status)){?>
                    <div class="wr-timeslot">
                        <ul class="<?php echo esc_attr($ul_classs);?>">
                            <?php
                                if( !empty($payment_mode) && $payment_mode === 'week' ){
                                    $week_range = workreap_get_weekrang($transaction_date);
                                    do_action( 'workreap_hourly_time_slots', $proposal_id,$week_range,$transaction_id,$time_slots );
                                } else if( !empty($payment_mode) && $payment_mode === 'day' ){
                                    do_action( 'workreap_hourly_day_time_slots', $proposal_id,$transaction_id,$time_slots );
                                } else if( !empty($payment_mode) && $payment_mode === 'month' ){
                                    do_action( 'workreap_hourly_month_time_slots', $proposal_id,$transaction_id,$time_slots );
                                }
                            ?>
                        </ul>
                        <?php if( !empty($time_slot_status) && $time_slot_status === 'draft' ){?>
                            <div class="wr-timeslot_save">
                                <span><?php esc_html_e('Submit week activity to the employer and wait for approval','workreap-hourly-addon');?></span>
                                <span data-transaction_id="<?php echo intval($transaction_id);?>" data-id="<?php echo intval($proposal_id);?>" class="wr-btn-solid wr_send_timeslot"><?php esc_html_e('Submit week activity','workreap-hourly-addon');?></span>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="wr-betaversion-wrap wr-firstweek-payment">
                        <img src="<?php echo esc_url($hourly_image);?>" alt="<?php esc_attr_e('Escrow','workreap-hourly-addon');?>">
                        <div class="wr-unlock-week">
                            <h5>
                            <?php    
                                if( !empty($payment_mode) && $payment_mode === 'week' ){
                                    esc_html_e('This week is locked till employer approval','workreap-hourly-addon');
                                } else if( !empty($payment_mode) && $payment_mode === 'day' ){
                                    esc_html_e('This day is locked till employer approval','workreap-hourly-addon');
                                } else if( !empty($payment_mode) && $payment_mode === 'month' ){
                                    esc_html_e('This month is locked till employer approval','workreap-hourly-addon');
                                } 
                            ?></h5>
                            <p>
                            <?php 
                                if( !empty($payment_mode) && $payment_mode === 'week' ){
                                    esc_html_e('Wait for the employer to activate this week, If your services further required for this project.','workreap-hourly-addon');
                                } else if( !empty($payment_mode) && $payment_mode === 'day' ){
                                    esc_html_e('Wait for the employer to activate this day, If your services further required for this project.','workreap-hourly-addon');
                                } else if( !empty($payment_mode) && $payment_mode === 'month' ){
                                    esc_html_e('Wait for the employer to activate this month, If your services further required for this project.','workreap-hourly-addon');
                                } 
                            ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="wr-timecards">
                <div class="wr-timecards_head">
                    <h5>
                        <?php esc_html_e('Timecard activities','workreap-hourly-addon');?>
                        <?php 
                            if( !empty($time_slot_status) ) {
                                do_action( 'workreap_hourly_status', $time_slot_status );
                            } 
                        ?>
                    </h5>
                    <?php if( !empty($time_slots[$transaction_id]) && isset($total_hours)) {?>
                        <div class="wr-timecards_total">
                            <h6><?php esc_html_e('Total hours:','workreap-hourly-addon');?></h6>
                            <span><?php echo esc_html($total_hours);?></span>
                        </div>
                    <?php } ?>
                </div>
                <?php  do_action( 'workreap_hourly_list', $proposal_id,$transaction_id,$time_slots );?>
            </div>
        </div>
        <div class="modal fade wr-workinghours-popup" id="wr_workinghours" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="wr-popup_title">
                        <h5><?php esc_html_e('Submit your working hours','workreap-hourly-addon');?></h5>
                        <a href="javascrcript:void(0)" data-bs-dismiss="modal">
                            <i class="wr-icon-x"></i>
                        </a>
                    </div>
                    <div class="modal-body wr-popup-content">
                        <form class="wr-themeform" id="wr_timetracking_form">
                            <fieldset>
                                <div class="wr-themeform__wrap">
                                    <div class="form-group at-slots-timedate">
                                        <h4 id="wr_timeslot_date_format"></h4>
                                    </div>
                                    <div class="form-group">
                                        <label class="wr-label"><?php esc_html_e('Add your working hours','workreap-hourly-addon');?></label>
                                        <div class="wr-placeholderholder">
                                            <input type="text" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('00H : 00M','workreap-hourly-addon');?>" name="working_time" id="wr-working-time" value="">
                                        </div> 
                                    </div>
                                    <div class="form-group">
                                        <div class="wr-placeholderholder">
                                            <textarea id="wr_timeslot_details" class="form-control wr-themeinput" name="details" placeholder="<?php esc_attr_e('Enter description','workreap-hourly-addon');?>" class="form-control wr-themeinput" required="required"></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" id="wr_form_time_id" value="" name="time_id">
                                    <input type="hidden" id="wr_form_date" value="" name="time_date">
                                    <input type="hidden" id="proposal_id" value="<?php echo intval($proposal_id);?>" name="proposal_id">
                                    <div class="form-group wr-btnarea">
                                        <button type="button" class="wr_timetracking_btn wr-btn-solid-lg"><?php esc_html_e('Save activity','workreap-hourly-addon');?></button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $script	= "
        ( function ( $ ) {
            'use strict'; 
            jQuery(document).ready(function(){
                
                jQuery(document).on('change', '#wr_order_type', function (e) {
                    let _this       = jQuery(this);
                    let page_url = _this.find(':selected').data('url');
                    window.location.replace(page_url);
                });
                jQuery('#wr-working-time').inputmask(
                    'datetime',{
                    mask: 'hH : sM',
                    placeholder: '".esc_attr__('00H : 00M','workreap-hourly-addon')."',
                    greedy: false,
                    insertMode: false,
                    showMaskOnHover: false,
                });
                let classes = [
                    '.wr-timecards',
                    '.wr-timecards',
                    '.wr-timecards',
                    '.wr-timecards'
                ];
                for ( let i = 0; i < classes.length; ++i) {
                    if (classes[i].length <= 3) {
                      jQuery('.wr-show_more').hide();
                    } 
                    else if (classes[i].length >= 3) {
                      jQuery('.wr-show_more').show();
                      jQuery('.wr-timecard-table tbody tr:nth-child(n+4)').hide();
                      jQuery('#wr-timealert-table table tbody tr:nth-child(n+4)').hide();
                    }
                  }
                
                  jQuery('.wr-show_more').on('click', function() {
                    jQuery(this).text($(this).text() === scripts_vars.show_less ? scripts_vars.load_more : scripts_vars.show_less);
                    jQuery(this).closest('.wr-timecards').find('.wr-timecard-table tbody tr:nth-child(n+4)').slideToggle('500','linear');
                    jQuery(this).closest('.wr-timecards').find('#wr-timealert-table tbody tr:nth-child(n+4)').slideToggle('500','linear');
                  });
            });
        } ( jQuery ) );
        ";
        wp_add_inline_script( 'workreap-hourly-project', $script, 'after' );
        echo ob_get_clean();
	}
    add_action('workreap_project_basic_details', 'workreap_project_basic_details');
}

/**
 * hourly week intervals
 *
 * @throws error
 * @return
 */
if (!function_exists('workreap_get_hourly_week_intervals')) {
    function workreap_get_hourly_week_intervals($start_week_date='' ) {
        date_default_timezone_set (date_default_timezone_get());
        $week_array		    = workreap_get_weekrang($start_week_date);
        $start_week         = !empty($week_array['start_time']) ? strtotime($week_array['start_time']) : 0;
        $end_week           = !empty($week_array['end_time']) ? strtotime($week_array['end_time']) : 0;

        $start_week_date    = !empty($start_week) ? date_i18n('Y-m-d',$start_week ) : 0;
        $end_week_date      = !empty($end_week) ? date_i18n('Y-m-d',$end_week) : 0;
        $working_date       = '';

        $start_month        = !empty($start_week) ? date('M',$start_week) : 0;
        $end_month          = !empty($end_week) ? date('M',$end_week) : 0;

        $start_year        = !empty($start_week) ? date('Y',$start_week) : 0;
        $end_year          = !empty($end_week) ? date('Y',$end_week) : 0;

        
        if($start_month == $end_month){
            $working_date   = date('d',$start_week).'-'.date('d',$end_week).' '.$start_month.', '.$end_year;
        } else {
            if( $start_year == $end_year ){
                $working_date   = date('d',$start_week).' '.$start_month.' - '.date('d',$end_week).' '.$end_month.', '.$end_year;
            } else {
                $working_date   = date('d',$start_week).' '.$start_month.','.$start_year.' - '.date('d',$end_week).' '.$end_month.', '.$end_year;
            }
        }
        return $working_date;
    }
}
/**
 * hourly week intervals
 *
 * @throws error
 * @return
 */
if (!function_exists('workreap_hourly_week_intervals')) {
    function workreap_hourly_week_intervals($proposal_id,$current_week ) {
        date_default_timezone_set (date_default_timezone_get());
        global $current_user;
        $hiring_string          = !empty($proposal_id) ? get_post_meta( $proposal_id, 'hiring_date',true ) : 0;
        $proposal_date          = !empty($hiring_string) ? date('Y-m-d',strtotime($hiring_string)) : 0;
        $start_week_date        = date('Y-m-d');
        if( !empty($proposal_date) ){
            ob_start();
            ?>
            <div class="wr-calendar">
                <select class="form-control wr-selectprice" id="wr_order_type">
                    <?php
                        do {
                            $week_array		    = workreap_get_weekrang($start_week_date);
                            $start_week         = !empty($week_array['start_time']) ? strtotime($week_array['start_time']) : 0;
                            $end_week           = !empty($week_array['end_time']) ? strtotime($week_array['end_time']) : 0;

                            $start_week_date    = !empty($start_week) ? date_i18n('Y-m-d',$start_week ) : 0;
                            $end_week_date      = !empty($end_week) ? date_i18n('Y-m-d',$end_week) : 0;
                            $working_date       = '';
                            $activ_class        = "";
                            if( !empty($current_week) && $current_week == $start_week ){
                                $activ_class    = "selected";
                            }

                            $start_month        = !empty($start_week) ? date('M',$start_week) : 0;
                            $end_month          = !empty($end_week) ? date('M',$end_week) : 0;

                            $start_year        = !empty($start_week) ? date('Y',$start_week) : 0;
                            $end_year          = !empty($end_week) ? date('Y',$end_week) : 0;

                            
                            if($start_month == $end_month){
                                $working_date   = date('d',$start_week).'-'.date('d',$end_week).' '.$start_month.', '.$end_year;
                            } else {
                                if( $start_year == $end_year ){
                                    $working_date   = date('d',$start_week).' '.$start_month.'-'.date('d',$end_week).' '.$end_month.', '.$end_year;
                                } else {
                                    $working_date   = date('d',$start_week).' '.$start_month.','.$start_year.'-'.date('d',$end_week).' '.$end_month.', '.$end_year;
                                }
                            }
                            ?> 
                                <option <?php echo esc_attr($activ_class);?> data-url="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $current_user->ID, '', 'activity',$proposal_id)?>&transaction_id=<?php echo intval($start_week);?>"><?php echo do_shortcode($working_date);?></option>
                                
                            <?php
                        
                            if (($proposal_date >= $start_week_date) && ($proposal_date <= $end_week_date)){
                                $start_week_date   = 0;
                            } else {
                                $start_week_date    = date('Y-m-d', strtotime($start_week_date .' -1 day'));
                            }
                        } while ($start_week_date != 0);
                    ?>
                </select>
            </div>
            <?php
                echo ob_get_clean();
        }
    }
    add_action('workreap_hourly_week_intervals', 'workreap_hourly_week_intervals', 10, 2);
}

/**
 * hourly week intervals
 *
 * @throws error
 * @return
 */
if (!function_exists('workreap_monthly_intervals')) {
    function workreap_monthly_intervals($proposal_id ) {
        date_default_timezone_set (date_default_timezone_get());
        global $current_user;
        $hiring_string          = !empty($proposal_id) ? get_post_meta( $proposal_id, 'hiring_date',true ) : 0;
        $hiring_date            = !empty($hiring_string) ? strtotime($hiring_string) : 0;
        $current_month          = date('m');
        $current_year           = date('Y');
        $hiring_month           = date('m',$hiring_date);
        $hiring_year            = date('Y',$hiring_date);
        $hiring_day             = date('d',$hiring_date);
        $hiring_date            = sprintf("%04d-%02d-%02d", $hiring_year, $hiring_month, '01');
        $hiring_date            = !empty($hiring_date) ? strtotime($hiring_date) : 0;
        $month_array            = array();
        $day_num                = 01;

        for ($year = $hiring_year; $year <= $current_year; $year++) {
            $loop_month = $year < $current_year ? $hiring_month : 01;
            $end_month  = $year < $current_year ? 12 : $current_month;
            for ($month = $loop_month; $month <= $end_month; $month++) {
                $isodate    = sprintf("%04d-%02d-%02d", $year, $month, $day_num);
                $start_date = strtotime($isodate);
                if( !empty($start_date) && $start_date >= $hiring_date ){
                    $month_array[intval($start_date)]   = $month;
                }
            }   
        }
        return $month_array;
    }
}

/**
 * Project creation step 2
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_creation_step2')){
	function workreap_project_creation_step2($project_id=0) {
        $project_meta       = !empty($project_id) ? get_post_meta( $project_id, 'wr_project_meta', true ) : array();
        $project_meta       = !empty($project_meta) ? $project_meta : array();
        $selected_job_type  = !empty($project_meta['project_type']) ? $project_meta['project_type'] : "fixed";
        $hourly_payment_mode= workreap_payment_mode('title');
        $min_hourly_price   = !empty($project_meta['min_price']) ? $project_meta['min_price'] : "";
        $max_hourly_price   = !empty($project_meta['max_price']) ? $project_meta['max_price'] : "";
        $max_hours          = !empty($project_meta['max_hours']) ? $project_meta['max_hours'] : "";
        $payment_mode       = !empty($project_meta['payment_mode']) ? $project_meta['payment_mode'] : "";
        $hourly_class       = !empty($selected_job_type) && $selected_job_type === 'hourly' ? "wr-hourly-type" : "wr-hourly-type d-none";
        ob_start();
        ?>
        <div class="form-group form-group-half <?php echo esc_attr($hourly_class);?>">
            <label class="wr-label">
                <?php esc_html_e('Select payment mode','workreap-hourly-addon');?>
            </label>
            <div class="wr-select">
                <?php do_action( 'workreap_custom_dropdown_html', $hourly_payment_mode,'payment_mode','wr-payment-mode',$payment_mode,esc_html__('Select payment mode','workreap-hourly-addon'));?>
            </div>
        </div>
        <div class="form-group form-group-half <?php echo esc_attr($hourly_class);?>">
            <label class="wr-label">
                <?php esc_html_e('Add maximum hours','workreap-hourly-addon');?>
                <?php do_action('workreap_tooltip', '<i class="wr-icon-alert-circle"></i>', 'add_max_hours');?>
            </label>
            <div class="wr-placeholderholder">
                <input type="text" name="max_hours" value="<?php echo esc_attr($max_hours);?>" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter maximum hours','workreap-hourly-addon');?>">
            </div>
        </div>
        <div class="form-group form-group-half <?php echo esc_attr($hourly_class);?>">
            <label class="wr-label"><?php esc_html_e('Add minimum hourly rate','workreap-hourly-addon');?></label>
            <div class="wr-placeholderholder">
                <input type="text" name="min_hourly_price" value="<?php echo esc_attr($min_hourly_price);?>" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter minimum hourly rate','workreap-hourly-addon');?>">
            </div>
        </div>
        <div class="form-group form-group-half <?php echo esc_attr($hourly_class);?>">
            <label class="wr-label"><?php esc_html_e('Add maximum hourly rate','workreap-hourly-addon');?></label>
            <div class="wr-placeholderholder">
                <input type="text" name="max_hourly_price" value="<?php echo esc_attr($max_hourly_price);?>" class="form-control wr-themeinput" placeholder="<?php esc_attr_e('Enter maximum hourly rate','workreap-hourly-addon');?>">
            </div>
        </div>
        <?php
        echo ob_get_clean();
	}
    add_action('workreap_project_creation_step2', 'workreap_project_creation_step2');
}

/**
 * Project estimation 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_estimation_html')){
	function workreap_project_estimation_html($project_id=0) {
        $project_meta	= get_post_meta( $project_id, 'wr_project_meta',true);
	    $project_type	= !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';

        if( !empty($project_type) && $project_type === 'hourly' ){
            $max_hours          = !empty($project_meta['max_hours']) ? $project_meta['max_hours'] : "";
            $payment_mode       = !empty($project_meta['payment_mode']) ? workreap_payment_mode('name',$project_meta['payment_mode']) : "";
            ob_start();
            ?>
                <em><?php echo sprintf( __('%s estimated maximum hours per %s','workreap-hourly-addon'),$max_hours,$payment_mode);?></em>
            <?php
            echo ob_get_clean();
        }
	}
    add_action('workreap_project_estimation_html', 'workreap_project_estimation_html');
}

/**
 * Submit proposal form
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_submit_proposal_form')){
	function workreap_submit_proposal_form($project_id=0,$proposal_id=0,$project_price=0,$price_options=0,$commission=0) {
        $project_meta	= get_post_meta( $project_id, 'wr_project_meta',true);
	    $project_type	= !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';

        if( !empty($project_type) && $project_type === 'hourly' ){
            $proposal_meta	= !empty($proposal_id) ? get_post_meta( $proposal_id, 'proposal_meta',true ) : array();
            $proposal_price	= isset($proposal_meta['price']) ? $proposal_meta['price'] : "";
            ob_start();
            ?>
            <div class="form-group wr-input-price">
                <label class="wr-label"><?php esc_html_e('Your budget working hourly rate','workreap-hourly-addon');?></label>
                <div class="wr-placeholderholder">
                    <input type="text" value="<?php echo esc_attr($proposal_price);?>" name="price" data-post_id="<?php echo intval($project_id);?>" class="form-control wr-themeinput wr_proposal_price" placeholder="<?php esc_attr_e('Enter your hourly rate','workreap-hourly-addon');?>">
                </div>
            </div>
            <div class="form-group">
                <ul class="wr-budgetlist">
                    <li>
                        <span><?php esc_html_e('Client per hour rate','workreap-hourly-addon');?></span>
                        <h6><?php echo do_shortcode($project_price);?></h6> 
                    </li>
                    <li>
                        <span><?php esc_html_e('Your per hour rate','workreap-hourly-addon');?></span>
                        <h6 id="wr_total_rate"><?php if( isset($proposal_price) ){workreap_price_format($proposal_price);};?></h6>
                    </li>
                    <li>
                        <span><?php echo sprintf( __('Admin commision fee (%s)','workreap-hourly-addon'),$commission);?></span>
                        <h6 id="wr_service_fee"><?php if( isset($price_options['admin_shares']) ){workreap_price_format($price_options['admin_shares']);}?></h6>
                    </li>
                </ul>
            </div>
            <div class="form-group">
                <div class="wr-totalamout">
                    <span><?php esc_html_e("Total amount you'll get per hour","workreap");?></span>
                    <h5 id="wr_user_share"><?php if( isset($price_options['freelancer_shares']) ){workreap_price_format($price_options['freelancer_shares']);}?></h5>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
	}
    add_action('workreap_submit_proposal_form', 'workreap_submit_proposal_form',10,5);
}

/**
 * App wallet amount
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_add_apply_wallet_amount')){
	function workreap_add_apply_wallet_amount($value) {
        if ( class_exists('WooCommerce') ) {
            if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'hourly' ){
                if( isset( $value['cart_data']['wallet_price'] ) ){
                    WC()->cart->add_fee( esc_html__('Wallet amount','workreap-hourly-addon'), -($value['cart_data']['wallet_price']) );
                }
            }
        }
	}
    add_action('workreap_add_apply_wallet_amount', 'workreap_add_apply_wallet_amount');
}

/**
 * change price on cart
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_add_apply_custom_price_to_cart_item')){
	function workreap_add_apply_custom_price_to_cart_item($value) {
        if ( class_exists('WooCommerce') ) {
            if( isset( $value['cart_data']['price'] ) && !empty( $value['payment_type'] ) && in_array($value['payment_type'],array('hourly')) ){
                $bk_price = floatval( $value['cart_data']['price'] );
                $value['data']->set_price($bk_price);

                if( !empty( $value['payment_type']) && $value['payment_type'] === 'hourly' && isset($value['cart_data']['product_name']) ){
                    $value['data']->set_name( $value['cart_data']['product_name'] );
                }
            }
        }
	}
    add_action('workreap_add_apply_custom_price_to_cart_item', 'workreap_add_apply_custom_price_to_cart_item');
}

/**
 * Checkout details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_after_cart_details')){
	function workreap_after_cart_details($cart_items) {
        if ( class_exists('WooCommerce') ) {
            if( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'hourly' ) {
                $cart_data	= !empty($cart_items['cart_data']) ? $cart_items['cart_data'] : array();
                ?>
                <div class="wr-haslayout wr-project-checkout">
                    <div class="cart-data-wrap">
                        <h3><?php esc_html_e('Summary','workreap-hourly-addon');?></h3>
                        <div class="selection-wrap">
                            <?php do_action('workreap_cart_hourly_project_details', $cart_data );?>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
	}
    add_action('workreap_after_cart_details', 'workreap_after_cart_details');
}

/**
 * Checkout cart project details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_cart_hourly_project_details')) {
    add_action('workreap_cart_hourly_project_details', 'workreap_cart_hourly_project_details',10,1);
    function workreap_cart_hourly_project_details($cart_data = array()) {
        $project_type       = !empty($cart_data['project_type']) ? $cart_data['project_type'] : '';
        $project_id         = !empty($cart_data['project_id']) ? intval($cart_data['project_id']) : '';
        $hourly_rate        = !empty($cart_data['hourly_rate']) ? $cart_data['hourly_rate'] : '';
        $proposal_meta      = !empty($cart_data['proposal_meta']) ? $cart_data['proposal_meta'] : array();
        ob_start();
        ?>
        <div class="wr-pricing__content">
            <?php if( !empty($project_id) ){?>
                <h4><?php echo get_the_title( $project_id );?></h4>
            <?php } ?>
            <ul class="wr-pricinglist">
                <?php if( !empty($cart_data['interval_name']) ){?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Time interval', 'workreap-hourly-addon'); ?></span>
                            <span> <?php echo esc_html( $cart_data['interval_name'] );?></span>
                        </div>
                    </li>
                <?php } ?>
                <?php if( !empty($cart_data['time_slots']['total_time']) ){?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Total Time interval', 'workreap-hourly-addon'); ?></span>
                            <span> <?php echo sprintf( __( '%s hours ','workreap-hourly-addon'),$cart_data['time_slots']['total_time'] );?></span>
                        </div>
                    </li>
                <?php } ?>
                <?php if( !empty($cart_data['max_hours']) ){?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Maximum estimated hours', 'workreap-hourly-addon'); ?></span>
                            <span> <?php echo esc_html( $cart_data['max_hours'] );?></span>
                        </div>
                    </li>
                <?php } ?>
                <?php if( !empty($hourly_rate) ){?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Hourly rate', 'workreap-hourly-addon'); ?></span>
                            <span> <?php workreap_price_format( $hourly_rate );?></span>
                        </div>
                    </li>
                <?php } ?>
                <?php if( !empty($cart_data['remaining_price']) ){?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Remaining price for old interval', 'workreap-hourly-addon'); ?></span>
                            <span>-<?php workreap_price_format( $cart_data['remaining_price'] );?></span>
                        </div>
                    </li>
                <?php } ?>
                <?php if( !empty($cart_data['price']) ){?>
                    <li>
                        <div class="wr-pricinglist__content">
                            <span><?php esc_html_e('Total amount', 'workreap-hourly-addon'); ?></span>
                            <span> <?php workreap_price_format( $cart_data['price'] );?></span>
                        </div>
                    </li>
                <?php } ?>
                
            </ul>
        </div>
        <?php
        echo ob_get_clean();
    }
}

/**
 * Checkout cart project details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_add_display_order_data_success')) {
    add_action('workreap_add_display_order_data_success', 'workreap_add_display_order_data_success');
    function workreap_add_display_order_data_success($order_detail = array()) {
        global $current_user;
        ob_start();
        if( !empty($order_detail) && !empty( $order_detail['payment_type'] )  && $order_detail['payment_type'] == 'hourly' ) {
            $dashboard_url	= !empty($current_user->ID) ? Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $current_user->ID, true, 'insights') : '';
			$project_url	= !empty($order_detail['proposal_id']) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $current_user->ID, true, 'activity',$order_detail['proposal_id']).'&transaction_id='.intval($order_detail['transaction_id']) : '';
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="cart-data-wrap">
						<div class="selection-wrap">
							<div class="wr-haslayout">
								<div class="cart-data-wrap">
									<h3><?php esc_html_e('Summary','workreap-hourly-addon');?></h3>
									<div class="selection-wrap">
										<?php do_action('workreap_cart_hourly_project_details', $order_detail );?>
									</div>
								</div>
							   <div class="wr-go-dbbtn">
									<a href="<?php echo esc_url_raw($project_url);?>" class="button"><?php esc_html_e('Go to project','workreap-hourly-addon');?></a>
									<a href="<?php echo esc_url_raw($dashboard_url);?>" class="button"><?php esc_html_e('Go to dashboard','workreap-hourly-addon');?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
        echo ob_get_clean();
    }
}

/**
 * Invoice detail for employer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_add_employer_invoice_details')) {
    add_action('workreap_add_employer_invoice_details', 'workreap_add_employer_invoice_details',10,2);
    function workreap_add_employer_invoice_details($order_data = array(),$order_id=0) {
        $order_type = get_post_meta( $order_id, 'project_type',true );
        if( !empty($order_type) && $order_type === 'hourly' ){
            ob_start();
            $total_time = !empty($order_data['time_slots']['total_time']) ? $order_data['time_slots']['total_time'] : ""
            ?>
            <tr>
                <td data-label="<?php esc_attr_e('#', 'workreap-hourly-addon');?>"><?php echo intval(1);?></td>
                <td data-label="<?php esc_attr_e('Description', 'workreap-hourly-addon');?>">
                    <?php 
                        if( !empty($order_data['project_type']) && $order_data['project_type'] === 'hourly' && !empty($order_data['interval_name']) ){
                            echo esc_html($order_data['interval_name']);
                        }
                    ?>
                </td>
                <td data-label="<?php esc_attr_e('Rate per hour', 'workreap-hourly-addon'); ?>"><?php workreap_price_format($order_data['hourly_rate']);?></td>
                <td data-label="<?php esc_attr_e('Total hours', 'workreap-hourly-addon'); ?>"><?php echo esc_html($order_data['time_slots']['total_time']);?></td>
                <td data-label="<?php esc_attr_e('Cost', 'workreap-hourly-addon');?>"><?php echo sprintf(__('%s * %s','workreap-hourly-addon'),workreap_price_format($order_data['hourly_rate'],'return'),$order_data['time_slots']['total_time']);?></td>
                <td data-label="<?php esc_attr_e('Amount', 'workreap-hourly-addon');?>"><?php workreap_price_format($order_data['price']);?></td>
            </tr>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * Invoice heading for employer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_employer_invoice_heading')) {
    add_action('workreap_employer_invoice_heading', 'workreap_employer_invoice_heading');
    function workreap_employer_invoice_heading($payment_type) {
        if( !empty($payment_type) && $payment_type === 'hourly'){
            ob_start();
            ?>
                <th><?php esc_html_e('Rate per hour', 'workreap-hourly-addon'); ?></th>
                <th><?php esc_html_e('Total hours', 'workreap-hourly-addon'); ?></th>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * Invoice heading for employer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_hire_proposal_button')) {
    add_action('workreap_hire_proposal_button', 'workreap_hire_proposal_button');
    function workreap_hire_proposal_button($proposal_id=0) {
        global $current_user;
        $proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
        $proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array();
        
        $project_type       = get_post_meta( $proposal_id, 'proposal_type',true );
        $project_type       = !empty($project_type) ? $project_type : '';

        $product_author_id  = get_post_field( 'post_author', $proposal_id );
        $linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','freelancers');
        $user_name          = workreap_get_username($linked_profile_id);

        $user_balance       = get_user_meta( $current_user->ID, '_employer_balance', true );
        $user_balance       = !empty($user_balance) ? $user_balance : 0;
        if( !empty($project_type) && $project_type === 'hourly') {
            if( !empty($user_balance) ){
                $checkout_class    = 'wr_hourly_proposal_hiring';
            } else {
                $checkout_class    = 'wr_hourly_slot_payment';
            }
        }

        $project_id	        = get_post_meta( $proposal_id, 'project_id',true);
        $project_status     = get_post_status( $project_id );
        $proposal_status    = !empty($proposal_id) ? get_post_status( $proposal_id ) : '';
        if( !empty($project_type) && $project_type === 'hourly' && !empty($project_status) && $project_status === 'publish' && !empty($proposal_status) && $proposal_status === 'publish'){
            ob_start();
            ?>
                <button class="wr-btn-solid-lg-lefticon <?php echo esc_attr($checkout_class);?>" data-key="" data-id="<?php echo intval($proposal_id);?>"><?php echo sprintf(__('Hire “%s”','workreap-hourly-addon'),$user_name);?></button>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * Project menu
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_history_menu')){
	function workreap_project_history_menu($proposal_id=0,$project_type='') {
        $time_slots         = get_post_meta( $proposal_id, 'wr_timetracking',true );
        $time_slots         = !empty($time_slots) ? $time_slots : array();
        $remove_option      = true;

        foreach($time_slots as $key => $val ){
            $status = !empty($val['status']) ? $val['status'] : '';
            if(!empty($status) && in_array($status,array('draft','pending','decline'))){
                if(!empty($status) && $status === 'draft' && !empty($val['slots'])){
                    $remove_option      = false;
                    break;
                } else {
                    $remove_option      = false;
                    break;
                }
            }
        }

        $project_id     = get_post_meta( $proposal_id, 'project_id', true );
        $project_title  = get_the_title( $project_id );
        $post_status    = get_post_status( $proposal_id );
        if( !empty($project_type) && $project_type === 'hourly' && !empty($remove_option) && !empty($post_status) && $post_status === 'hired' ){
            
            ob_start();
            ?>
                <li>
                    <span class="wr_project_completed" data-proposal_id="<?php echo intval($proposal_id);?>" data-title="<?php echo esc_attr($project_title);?>"><?php esc_html_e('Complete project','workreap-hourly-addon');?></span>
                </li>
            <?php
            echo ob_get_clean();
        }
	}
    add_action('workreap_project_history_menu','workreap_project_history_menu',10,2);
}

/**
 * Hiring text on proposal
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_before_hire_proposal_button')) {
    add_action('workreap_before_hire_proposal_button', 'workreap_before_hire_proposal_button');
    function workreap_before_hire_proposal_button($proposal_id=0) {
        $proposal_type      = get_post_meta( $proposal_id, 'proposal_type',true );
        $proposal_type      = !empty($proposal_type) ? $proposal_type : '';
        $status             = get_post_status( $proposal_id );

        if( !empty($proposal_type) && $proposal_type === 'hourly' && !empty($status) && $status === 'publish' ){
            $hourly_image	    = workreap_add_http_protcol(WORKREAP_HOURLY_ADDON_URI . 'public/images/hourly-proposal-escrow.png');
            $project_id         = get_post_meta( $proposal_id, 'project_id',true );
            $payment_mode       = get_post_meta( $project_id, 'payment_mode',true );
            $project_id         = !empty($project_id) ? intval($project_id) : 0;
            $payment_mode       = !empty($payment_mode) ? workreap_payment_mode('name',$payment_mode) : '';
            ob_start();
            ?>
            <div class="wr-betaversion-wrap wr-firstweek-payment">
                <img src="<?php echo esc_url($hourly_image);?>" alt="<?php esc_attr_e('escrow','workreap-hourly-addon');?>">
                <div class="wr-unlock-week">
                    <h5><?php echo sprintf(__("Like this freelancer? Let’s escrow first %s payment to get started","workreap-hourly-addon"),$payment_mode);?></h5>
                    <p><?php echo sprintf(__("In order to start this project, you need to escrow the first %s payment. Hit the “Hire” button to get started.","workreap-hourly-addon"),$payment_mode);?></p>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * @Dispute budget details
 * @return
 */
if (!function_exists('workreap_hourly_proposal_order_budget_details')) {
    add_action( 'workreap_hourly_proposal_order_budget_details', 'workreap_hourly_proposal_order_budget_details', 10, 2);
    function workreap_hourly_proposal_order_budget_details($proposal_id =0, $user_type = 'freelancers') {
		if ( !class_exists('WooCommerce') ) {
			return;
		}

        $proposal_type      = get_post_meta( $proposal_id, 'proposal_type',true );
        if( !empty($proposal_type) && $proposal_type === 'hourly' ){
            $project_id             = get_post_meta( $proposal_id, 'project_id',true );
            $project_id             = !empty($project_id) ? intval($project_id) : 0;
            $payment_mode           = get_post_meta( $project_id, 'payment_mode',true );
            $payment_mode           = !empty($payment_mode) ? $payment_mode : '';

            $proposal_meta          = get_post_meta( $proposal_id, 'proposal_meta',true );
            $proposal_meta          = !empty($proposal_meta) ? $proposal_meta : array();
            $hourly_rate            = !empty($proposal_meta['price']) ? $proposal_meta['price'] : 0;
            $wr_timetracking        = get_post_meta( $proposal_id, 'wr_timetracking',true );
            $wr_timetracking        = !empty($wr_timetracking) ? $wr_timetracking : array();
            
            $total_amount           = 0;  
            $employer_amount           = 0;
            $houly_array            = array(); 

            if( !empty($wr_timetracking) ){
                foreach($wr_timetracking as $key => $val ){
                    $status         = !empty($val['status']) ? $val['status'] : '';
                    $total_time     = isset($val['total_time']) ? $val['total_time'] : 0;
                    $order_id       = isset($val['order_id']) ? $val['order_id'] : 0;
                    
                    if( in_array($status,array('pending','draft','decline'))){
                        $interval_name  = '';
                        if( !empty($payment_mode) && $payment_mode === 'weekly' ){
                            $interval_name		    = workreap_get_hourly_week_intervals($key);
                        } else if( !empty($payment_mode) && $payment_mode === 'daily' ){
                            $interval_name		    = date_i18n(get_option('date_format'),$key );
                        } else if( !empty($payment_mode) && $payment_mode === 'monthly' ){
                            $interval_name		    = date_i18n('F Y',$key );
                        }

                        if( !empty($user_type) && $user_type === 'freelancers' ){
                            $current_amount =  $total_time*$hourly_rate;  
                            $total_amount   = $total_amount + $current_amount;
                        } elseif( !empty($user_type) && $user_type === 'employers' ){
                            $order_data     = get_post_meta( $order_id, 'cus_woo_product_data',true );
                            $order_data     = !empty($order_data) ? $order_data : array();
                            $current_amount =  isset($order_data['price']) ? $order_data['price'] : 0;  
                            $current_amount = isset($order_data['remaining_price']) ? $order_data['price'] + $order_data['remaining_price']: 0;
                            $total_amount   = isset($current_amount) ? $total_amount + $current_amount : $total_amount;
                        } else {
                            $current_amount =  $total_time*$hourly_rate;  
                            $total_amount   = $total_amount + $current_amount;

                            $order_data             = get_post_meta( $order_id, 'cus_woo_product_data',true );
                            $order_data             = !empty($order_data) ? $order_data : array();
                            $employer_current_amount   =  isset($order_data['price']) ? $order_data['price'] : 0;
                            $employer_current_amount   = isset($order_data['remaining_price']) ? $order_data['price'] + $order_data['remaining_price']: 0;
                            $employer_amount           = isset($employer_current_amount) ? $employer_amount + $employer_current_amount : $employer_amount;
                            $houly_array[$key]['employer_amount']             = $employer_current_amount;
                            $houly_array[$key]['total_employer_amount']       = $employer_amount;
                        }
                        
                        
                        $houly_array[$key]['title']             = $interval_name;
                        $houly_array[$key]['hourly_rate']       = $hourly_rate;
                        $houly_array[$key]['total_time']        = $total_time;
                        $houly_array[$key]['amount']            = $current_amount;
                    }
                }
            }
            
            ob_start();?>
            <div class="wr-asideholder wr-taskdeadline">
                <?php if(isset($total_amount)){?>
                <div class="wr-asidebox wr-additonoltitleholder">
                    <div data-bs-toggle="collapse" data-bs-target="#wr-additionolinfov2" aria-expanded="true" role="button">
                        <div class="wr-additonoltitle">
                            <div class="wr-startingprice">
                                <i><?php esc_html_e('Total project budget', 'workreap-hourly-addon');?></i>
                                <span><?php workreap_price_format($total_amount);  ?></span>
                            </div>
                            <i class="wr-icon-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <?php }?>
                <div id="wr-additionolinfov2" class="show">
                    <div class="wr-budgetlist">
                        <?php if(!empty($houly_array)){?>
                            <ul class="wr-planslist">
                                <?php
                                // Get and Loop Over Order Items
                                foreach ($houly_array as $h_key => $h_val) {
                                    if( isset($h_val['amount']) && isset($h_val['title'])){ ?>
                                        <li>
                                            <?php if( !empty($user_type) && $user_type != 'administrator'){ ?>
                                                <h6>
                                                    <?php echo esc_html($h_val['title']);?>
                                                    <span>(<?php workreap_price_format($h_val['amount']); ?>) </span>
                                                </h6>
                                            <?php }?>
                                            <?php if( !empty($user_type) && $user_type === 'administrator'){ ?>
                                                <h3>
                                                    <?php echo esc_html($h_val['title']);?>
                                                </h3>
                                                <h6>
                                                    <?php esc_html_e('Buyer amount','workreap-hourly-addon');?>
                                                    <span>(<?php workreap_price_format($h_val['employer_amount']); ?>) </span>
                                                </h6>
                                                <h6>
                                                    <?php esc_html_e('Seller amount','workreap-hourly-addon');?>
                                                    <span>(<?php workreap_price_format($h_val['amount']); ?>) </span>
                                                </h6>
                                            <?php } ?>
                                        </li>
                                <?php }
                                }?>
                            </ul>
                        <?php }?>
                        <ul class="wr-planslist wr-totalfee">
                            <?php if( !empty($user_type) && $user_type == 'administrator'){ ?>
                                <li>
                                    <a href="javascript:void(0);">
                                        <h6>
                                            <?php esc_html_e('Total employer amount', 'workreap-hourly-addon');?>:&nbsp;
                                            <span>(<?php workreap_price_format($employer_amount);?>) </span>
                                        </h6>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <h6>
                                            <?php esc_html_e('Total freelancer amount', 'workreap-hourly-addon');?>:&nbsp;
                                            <span>(<?php workreap_price_format($total_amount);?>) </span>
                                        </h6>
                                    </a>
                                </li>
                            <?php } else {?>
                                <li>
                                    <a href="javascript:void(0);">
                                        <h6>
                                            <?php esc_html_e('Total project budget', 'workreap-hourly-addon');?>:&nbsp;
                                            <span>(<?php workreap_price_format($total_amount);?>) </span>
                                        </h6>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
}

 /**
 * @Init Dispute Refuded 
 * @return
 */
if (!function_exists('workreap_after_refund_dispute')) {
    add_action( 'workreap_after_refund_dispute', 'workreap_after_refund_dispute', 10, 3);
    function workreap_after_refund_dispute($dispute_id =0, $resolve_type='employers',$type = '') {

        $freelancer_id	        = get_post_meta( $dispute_id, '_freelancer_id', true );
        $employer_id	        = get_post_meta( $dispute_id, '_employer_id', true );
        $proposal_id	    = get_post_meta( $dispute_id, '_proposal_id', true );
        $project_id         = get_post_meta( $dispute_id, '_project_id',true );

        $project_type	= get_post_meta( $project_id, 'project_type', true );
        $project_type   = !empty($project_type) ? $project_type : '';

        if( !empty($project_type) && $project_type === 'hourly'){
            $payment_mode           = get_post_meta( $project_id, 'payment_mode',true );
            $payment_mode           = !empty($payment_mode) ? $payment_mode : '';

            $proposal_meta          = get_post_meta( $proposal_id, 'proposal_meta',true );
            $proposal_meta          = !empty($proposal_meta) ? $proposal_meta : array();
            $hourly_rate            = !empty($proposal_meta['price']) ? $proposal_meta['price'] : 0;
            $wr_timetracking        = get_post_meta( $proposal_id, 'wr_timetracking',true );
            $wr_timetracking        = !empty($wr_timetracking) ? $wr_timetracking : array();
            $dispute_order	        = get_post_meta( $dispute_id, '_dispute_order', true );
            $dispute_order          = !empty($dispute_order) ? intval($dispute_order) : 0;

            $remaining_amount       = get_post_meta( $proposal_id, 'remaining_amount',true );
            $remaining_amount       = !empty($remaining_amount) ? $remaining_amount : 0;

            $total_amount           = 0;   
            if( !empty($resolve_type) && $resolve_type === 'employers'){
                if( !empty($wr_timetracking) ){
                    foreach($wr_timetracking as $key => $val ){
                        $status         = !empty($val['status']) ? $val['status'] : '';
                        $order_id       = isset($val['order_id']) ? $val['order_id'] : 0;
                        $total_time     = isset($val['total_time']) ? $val['total_time'] : 0;
                        if( !empty($order_id) && in_array($status,array('pending','draft','decline'))){
                            $order_data     = get_post_meta( $order_id, 'cus_woo_product_data',true );
                            $order_data     = !empty($order_data) ? $order_data : array();
                            $buer_price     = isset($order_data['price']) ? ($order_data['price']) : 0;
                            $buer_price     = isset($order_data['remaining_price']) ? $order_data['price'] + $order_data['remaining_price']: 0;
                            
                            $total_amount   = isset($buer_price) ? $total_amount + $buer_price : $total_amount;
                            $order          = wc_get_order($order_id);
                            $order->set_status('refunded');
                            $order->save();
                            update_post_meta($order->get_id(), '_task_status', 'refunded');
                        }
                    }
                }

                $total_amount   = $total_amount + $remaining_amount;
                update_post_meta($dispute_order, '_task_status', 'cancelled');

                if ( class_exists('WooCommerce') ) {
                    global $woocommerce;
                    if( !empty($type) && $type === 'mobile' ){
                        check_prerequisites($user_id);
                    }
                    
                    $woocommerce->cart->empty_cart();
                    $wallet_amount              = $total_amount;
                    $product_id                 = workreap_employer_wallet_create();
                    $cart_meta                  = array();
                    $cart_meta['wallet_id']     = $product_id;
                    $cart_meta['product_name']  = get_the_title($product_id);
                    $cart_meta['price']         = $wallet_amount;
                    $cart_meta['project_id']    = $project_id;
                    $cart_meta['proposal_id']   = $proposal_id;
                    $cart_meta['payment_type']  = 'wallet';

                    $cart_data = array(
                        'wallet_id' 		=> $product_id,
                        'cart_data'     	=> $cart_meta,
                        'price'				=> $wallet_amount,
                        'payment_type'     	=> 'wallet'
                    );
                    $woocommerce->cart->empty_cart();
                    $cart_item_data = $cart_data;
                    WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                    $new_order_id	= workreap_place_order($employer_id,'wallet',$dispute_id);
                    update_post_meta($new_order_id, '_fund_type', 'freelancer');
                    update_post_meta($new_order_id, '_task_dispute_type', 'project');
                    update_post_meta($new_order_id, '_task_dispute_order', $dispute_order);

                    update_post_meta($dispute_id, 'dispute_status', 'resolved');
                    update_post_meta($dispute_id, 'winning_party', $employer_id);
                    update_post_meta($dispute_id, 'resolved_by', 'freelancers');
                    
                }
            } else {
                $employer_amount   = 0;
                if( !empty($wr_timetracking) ){
                    $time_slots = $wr_timetracking;
                    foreach($wr_timetracking as $transaction_id => $val ){
                        $status         = !empty($val['status']) ? $val['status'] : '';
                        $order_id       = isset($val['order_id']) ? $val['order_id'] : 0;
                        $total_time     = isset($val['total_time']) ? $val['total_time'] : 0;
                        if( !empty($order_id) && in_array($status,array('pending','draft','decline'))){
                            $order_data     = get_post_meta( $order_id, 'cus_woo_product_data',true );
                            $order_data     = !empty($order_data) ? $order_data : array();
                            
                            $hourly_rate    = isset($order_data['hourly_rate']) ? $order_data['hourly_rate'] : 0;
                            $price          = isset($order_data['price']) ? ($order_data['price']) : 0;
                            $price          = isset($order_data['remaining_price']) ? $order_data['price'] + $order_data['remaining_price']: 0;
                            $approved_amount= $total_time*$hourly_rate;
                            $remaining_price= $price - $approved_amount;
                            $employer_amount   = $employer_amount +$remaining_price;

                            $service_fee    = workreap_commission_fee($approved_amount);
                            $admin_shares   = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
                            $freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $approved_amount;
                            $order_data['approved_amount']                                      = $approved_amount;
                            $order_data['admin_shares']                                         = $admin_shares;
                            $order_data['freelancer_shares']                                        = $freelancer_shares;
                            $order_data['remaining_price']                                      = $remaining_price;
                            $order_data['time_slots'][$transaction_id]['approved_amount']       = $approved_amount;
                            $order_data['time_slots'][$transaction_id]['remaining_price']       = $remaining_price;
                            $order_data['time_slots'][$transaction_id]['completed_time']        = $gmt_time;
                            $order_data['time_slots'][$transaction_id]['completed_by']          = 'admin';

                            $time_slots[$transaction_id]['approved_amount']     = $approved_amount;
                            $time_slots[$transaction_id]['remaining_price']     = $remaining_price;
                            $time_slots[$transaction_id]['completed_time']      = $gmt_time;
                            $time_slots[$transaction_id]['completed_by']        = 'admin';
                            
                            update_post_meta( $order_id, 'admin_shares', $admin_shares );
                            update_post_meta( $order_id, 'freelancer_shares', $freelancer_shares );
                            update_post_meta( $order_id, 'cus_woo_product_data', $order_data );
                            update_post_meta( $order_id, '_post_project_status', 'completed' );
                            update_post_meta( $order_id, '_task_status', 'completed' );
                            update_post_meta( $order_id, 'freelancer_id', $freelancer_id );
                            
                            $order          = wc_get_order($order_id);
                            $order->set_status('completed');
                            $order->save();
                            update_post_meta($order->get_id(), '_task_status', 'completed');
                        }
                    }
                    update_post_meta( $proposal_id, 'wr_timetracking', $time_slots );
                }

                $total_amount   = $employer_amount + $remaining_amount;
                update_post_meta($dispute_order, '_task_status', 'cancelled');

                if ( class_exists('WooCommerce') && $total_amount > 0  ) {
                    global $woocommerce;
                    if( !empty($type) && $type === 'mobile' ){
                        check_prerequisites($user_id);
                    }
                    
                    $woocommerce->cart->empty_cart();
                    $wallet_amount              = $total_amount;
                    $product_id                 = workreap_employer_wallet_create();
                    $cart_meta                  = array();
                    $cart_meta['wallet_id']     = $product_id;
                    $cart_meta['product_name']  = get_the_title($product_id);
                    $cart_meta['price']         = $wallet_amount;
                    $cart_meta['project_id']    = $project_id;
                    $cart_meta['proposal_id']   = $proposal_id;
                    $cart_meta['payment_type']  = 'wallet';

                    $cart_data = array(
                        'wallet_id' 		=> $product_id,
                        'cart_data'     	=> $cart_meta,
                        'price'				=> $wallet_amount,
                        'payment_type'     	=> 'wallet'
                    );

                    $woocommerce->cart->empty_cart();
                    $cart_item_data = $cart_data;
                    WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                    $new_order_id	= workreap_place_order($employer_id,'wallet',$dispute_id);
                    update_post_meta($new_order_id, '_fund_type', 'freelancer');
                    update_post_meta($new_order_id, '_task_dispute_type', 'project');
                    update_post_meta($new_order_id, '_task_dispute_order', $dispute_order);

                    update_post_meta($dispute_id, 'dispute_status', 'resolved');
                    update_post_meta($dispute_id, 'winning_party', $employer_id);
                    update_post_meta($dispute_id, 'resolved_by', 'freelancers');
                    
                } 
            }
        }
    }
}

/**
 * Invoice detail for employer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_employer_invoice_details')) {
    add_action('workreap_employer_invoice_details', 'workreap_employer_invoice_details');
    function workreap_employer_invoice_details($args=array()) {
        global $workreap_settings;
        $admin_commision_employers     =  !empty($workreap_settings['admin_commision_employers']) ? $workreap_settings['admin_commision_employers'] : 0;
        $commission_text            =  !empty($workreap_settings['commission_text']) ? $workreap_settings['commission_text'] : esc_html__('Processing fee', 'workreap');
		
        $identity   = !empty($args['identity']) ? intval($args['identity']) : "";
        $order_id   = !empty($args['order_id']) ? intval($args['order_id']) : "";
        $site_logo  = !empty($workreap_settings['defaul_site_logo']['url']) ? $workreap_settings['defaul_site_logo']['url'] : '';
        $order_type = get_post_meta( $order_id, 'project_type',true );
        if( !empty($order_type) && $order_type === 'hourly' ){
            global $workreap_settings;
            /* if order Id empty */
            if (empty($order_id)) {
                return;
            }

            if (!class_exists('WooCommerce')) {
                return;
            }

            $date_format    = get_option( 'date_format' );
            $gmt_time		= strtotime(current_time( 'mysql', 1 ));
            $order          = wc_get_order($order_id);
            $data_created   = $order->get_date_created();
            $order_status   = $order->get_status();
            $invoice_status = get_post_meta( $order_id,'_task_status', true );
            $invoice_status = !empty($invoice_status) ? $invoice_status : '';

            $data_created   = date_i18n($date_format, strtotime($data_created));
            $get_total      = $order->get_total();

            if(function_exists('wmc_revert_price')){
                $get_total =  wmc_revert_price($order->get_total(),$order->get_currency());
            }

            $get_taxes      = $order->get_taxes();
            if(function_exists('wmc_revert_price')){
                $get_subtotal =  wmc_revert_price($order->get_subtotal(),$order->get_currency());
            } else {
                $get_subtotal   = $order->get_subtotal(); 
            }

            $billing_address        = $order->get_formatted_billing_address();
            $order_meta             = get_post_meta( $order_id, 'cus_woo_product_data', true );
            $order_meta             = !empty($order_meta) ? $order_meta : array();
            $processing_fee		    = !empty($order_meta['processing_fee']) ? $order_meta['processing_fee'] : 0.0;

            $payment_type           = get_post_meta( $order_id, 'payment_type',true );
            $payment_type           = !empty($payment_type) ? $payment_type : '';

            $wallet_amount          = 0;
            $wallet_amount          = get_post_meta( $order_id, '_wallet_amount', true );
            $wallet_amount          = !empty($wallet_amount) ? $wallet_amount : 0;
            
            $from_billing_address   = !empty($identity) ? workreap_user_billing_address($identity) : '';
            $task_title             = "";

            $project_id     = !empty($order_meta['project_id']) ? $order_meta['project_id'] : '';
            $project_title  = !empty($project_id) ? get_the_title( $project_id ) : '';
            $task_title     = !empty($order_meta['interval_name']) && !empty($project_title) ? $project_title . ' ('. $order_meta['interval_name'].')' : '';
            $total_tax 	    = $order->get_total_tax();
            $total_tax      = !empty($total_tax) ? $total_tax : 0;
            $invoice_terms  = !empty($workreap_settings['invoice_terms']) ? $workreap_settings['invoice_terms'] : '';

            $remaining_price    = isset($order_meta['remaining_price']) ? $order_meta['remaining_price'] : 0;
            $total_price        = isset($order_meta['price']) ?  $order_meta['price'] : 0;
            //$total_price        = isset($remaining_price) && isset($order_meta['price']) ? $remaining_price + $order_meta['price'] : $order_meta['price'];
            $invoice_billing_to = !empty($workreap_settings['invoice_billing_to']) ? $workreap_settings['invoice_billing_to'] : '';
            $billing_address    = !empty($invoice_billing_to) && !empty($workreap_settings['invoice_billing_address']) ? $workreap_settings['invoice_billing_address'] : $billing_address;
            ?>
            <div class="wr-main-section wr-hourly-invoice">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="wr-invoicedetal">
                                <div class="wr-printable">
                                    <div class="wr-invoicebill">
                                        <?php if( !empty($site_logo) ){
                                            if( !empty($args['option']) && $args['option'] === 'pdf'){
                                                $type           = pathinfo($site_logo, PATHINFO_EXTENSION);
                                                $data           = file_get_contents($site_logo);
                                                $base64_logo    = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                                echo do_shortcode( '<figure><img src="'.($base64_logo).'" alt="'.esc_attr__('invoice detail','workreap-hourly-addon').'"></figure>' );
                                            } else { ?>
                                                <figure>
                                                    <img src="<?php echo esc_url($site_logo);?>" alt="<?php esc_attr_e('invoice detail','workreap-hourly-addon');?>">
                                                </figure>
                                        <?php } } ?>
                                        <div class="wr-billno">
                                            
                                            <h3><?php esc_html_e('Invoice', 'workreap-hourly-addon'); ?></h3>
                                            <span># <?php echo intval($order_id); ?></span>
                                        </div>
                                    </div>
                                    <div class="wr-tasksinfos">
                                        <?php if( !empty($task_title) ){?>
                                            <div class="wr-invoicetasks">
                                                <h5><?php esc_html_e('Title','workreap-hourly-addon');?>:</h5>
                                                <h3><?php echo esc_html($task_title); ?></h3>
                                            </div>
                                        <?php } ?>
                                        <div class="wr-tasksdates">
                                            <div class="wr-tags"><?php do_action( 'workreap_proposal_invoice_status_tag', $invoice_status );?></div>
                                            <span> <em><?php esc_html_e('Issue date:', 'workreap-hourly-addon') ?>&nbsp;</em><?php echo esc_html($data_created); ?></span>
                                        </div>
                                    </div>
                                    <div class="wr-invoicefromto">
                                        <?php if (!empty($from_billing_address)){ ?>
                                            <div class="wr-fromreceiver">
                                                <h5><?php esc_html_e('From:', 'workreap-hourly-addon'); ?></h5>
                                                <span><?php echo do_shortcode(nl2br($from_billing_address)); ?></span>
                                            </div>
                                        <?php } ?>

                                        <?php if( !empty($billing_address) ){?>
                                            <div class="wr-fromreceiver">
                                                <h5><?php esc_html_e('To:', 'workreap-hourly-addon'); ?></h5>
                                                <span><?php echo do_shortcode(nl2br($billing_address)); ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <table class="wr-table wr-invoice-table">
                                        <thead>
                                        <tr>
                                            <th><?php esc_html_e('#','workreap-hourly-addon');?></th>
                                            <th><?php esc_html_e('Description', 'workreap-hourly-addon'); ?></th>
                                            <th><?php esc_html_e('Rate per hour', 'workreap-hourly-addon'); ?></th>
                                            <th><?php esc_html_e('Total hours', 'workreap-hourly-addon'); ?></th>
                                            <th><?php esc_html_e('Amount', 'workreap-hourly-addon'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-label="<?php esc_attr_e('#', 'workreap-hourly-addon');?>"><?php echo intval(1);?></td>
                                                <td data-label="<?php esc_attr_e('Description', 'workreap-hourly-addon');?>">
                                                    <?php 
                                                        if( !empty($order_meta['project_type']) && $order_meta['project_type'] === 'hourly' && !empty($order_meta['interval_name']) ){
                                                            echo esc_html($order_meta['interval_name']);
                                                        }
                                                    ?>
                                                </td>
                                                <td data-label="<?php esc_attr_e('Rate per hour', 'workreap-hourly-addon'); ?>"><?php workreap_price_format($order_meta['hourly_rate']);?></td>
                                                <td data-label="<?php esc_attr_e('Total hours', 'workreap-hourly-addon'); ?>"><?php echo esc_html($order_meta['max_hours']);?></td>
                                                <td data-label="<?php esc_attr_e('Amount', 'workreap-hourly-addon');?>"><?php workreap_price_format($total_price);?></td>
                                            </tr>
                                            <?php if( isset($order_meta['approved_total_time']) && isset($order_meta['approved_amount']) ){?>
                                                <tr>
                                                    <td data-label="<?php esc_attr_e('#', 'workreap-hourly-addon');?>"><?php echo intval(2);?></td>
                                                    <td data-label="<?php esc_attr_e('Description', 'workreap-hourly-addon');?>"><?php esc_html_e('Freelancer work','workreap-hourly-addon');?></td>
                                                    <td data-label="<?php esc_attr_e('Rate per hour', 'workreap-hourly-addon'); ?>"><?php workreap_price_format($order_meta['hourly_rate']);?></td>
                                                    <td data-label="<?php esc_attr_e('Total hours', 'workreap-hourly-addon'); ?>"><?php echo esc_html($order_meta['approved_total_time']);?></td>
                                                    <td data-label="<?php esc_attr_e('Amount', 'workreap-hourly-addon');?>"><?php workreap_price_format($order_meta['approved_amount']);?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="wr-subtotal">
                                        <ul class="wr-subtotalbill">
                                            <?php if(isset($remaining_price) && $remaining_price > 0){?>
                                                <li><?php esc_html_e('Previous C/F balance','workreap-hourly-addon'); ?> : <h6>-<?php workreap_price_format($remaining_price); ?></h6></li>
                                            <?php } ?>
                                            <li><?php esc_html_e('Amount you paid:','workreap-hourly-addon'); ?> <h6><?php workreap_price_format($get_total); ?></h6></li>
                                            <?php if(!empty($total_tax)){?>
                                                <li><?php esc_html_e('Taxes & fees:','workreap-hourly-addon'); ?> <h6><?php workreap_price_format($total_tax); ?></h6></li><?php } ?>
                                            <?php if(!empty($processing_fee)){?>
                                                <li><?php echo esc_html($commission_text);?>: <h6><?php workreap_price_format($processing_fee); ?></h6></li>
                                            <?php } ?>  
                                            <?php if( !empty($wallet_amount)){?>
                                                <li><?php esc_html_e('Wallet amount used','workreap-hourly-addon'); ?> : <h6><?php workreap_price_format($wallet_amount); ?></h6></li>
                                            <?php } ?>                            
                                        </ul>
                                        <div class="wr-sumtotal"><?php esc_html_e('Total','workreap-hourly-addon'); ?> : <h6><?php workreap_price_format($get_total); ?></h6></div>
                                        <?php if( isset($order_meta['remaining_price']) ){?>
                                            <div class="wr-ad-balance"><?php esc_html_e('Additional C/F balance','workreap-hourly-addon'); ?> : <span><?php workreap_price_format($order_meta['remaining_price']); ?></span></div>
                                        <?php } ?>
                                    </div>
                                    <?php if( !empty($invoice_terms) ){?>
                                        <div class="wr-anoverview">
                                            <div class="wr-description">
                                                <?php echo do_shortcode( $invoice_terms );?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * Invoice detail for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_freelancer_invoice_details')) {
    add_action('workreap_freelancer_invoice_details', 'workreap_freelancer_invoice_details');
    function workreap_freelancer_invoice_details($args=array()) {
        global $workreap_settings;
        $identity   = !empty($args['identity']) ? intval($args['identity']) : "";
        $order_id   = !empty($args['order_id']) ? intval($args['order_id']) : "";
        $site_logo  = !empty($workreap_settings['defaul_site_logo']['url']) ? $workreap_settings['defaul_site_logo']['url'] : '';
        $order_type = get_post_meta( $order_id, 'project_type',true );
        if( !empty($order_type) && $order_type === 'hourly' ){
            global $workreap_settings;
            /* if order Id empty */
            if (empty($order_id)) {
                return;
            }

            if (!class_exists('WooCommerce')) {
                return;
            }

            $date_format    = get_option( 'date_format' );
            $gmt_time		= strtotime(current_time( 'mysql', 1 ));
            $order          = wc_get_order($order_id);
            $data_created   = $order->get_date_created();
            $order_status  = $order->get_status();
            if(!empty($order_status) && $order_status === 'refunded'){
                $order_status_text  = esc_html__('Refunded','workreap-hourly-addon');
            }else if(!empty($order_status) && $order_status === 'completed'){
                $order_status_text  = esc_html__('Completed','workreap-hourly-addon');
            }else {
                $order_status_text  = $order_statu; 
            }

            $data_created   = date_i18n($date_format, strtotime($data_created));
            $processing_fee = 0;

            $processing_fee   = get_post_meta($order_id, 'admin_shares', true);
            $processing_fee   = isset($processing_fee) ? $processing_fee : 0;
            $get_total              = get_post_meta($order_id, 'freelancer_shares', true);
            $get_total              = !empty($get_total) ? $get_total : 0;
            $billing_address        = $order->get_formatted_billing_address();
            $order_meta             = get_post_meta( $order_id, 'cus_woo_product_data', true );
            $order_meta             = !empty($order_meta) ? $order_meta : array();
            $payment_type           = get_post_meta( $order_id, 'payment_type',true );
            $payment_type           = !empty($payment_type) ? $payment_type : '';
            $wallet_amount          = 0;
            $wallet_amount          = get_post_meta( $order_id, '_wallet_amount', true );
            $wallet_amount          = !empty($wallet_amount) ? $wallet_amount : 0;
            $from_billing_address   = !empty($identity) ? workreap_user_billing_address($identity) : '';
            $task_title             = "";

            $project_id     = !empty($order_meta['project_id']) ? $order_meta['project_id'] : '';
            $project_title  = !empty($project_id) ? get_the_title( $project_id ) : '';
            $task_title     = !empty($order_meta['interval_name']) && !empty($project_title) ? $project_title . ' ('. $order_meta['interval_name'].')' : '';
            $total_tax 	    = $order->get_total_tax();
            $total_tax      = !empty($total_tax) ? $total_tax : 0;
            $invoice_terms  = !empty($workreap_settings['invoice_terms']) ? $workreap_settings['invoice_terms'] : '';
            $invoice_status = get_post_meta( $order_id,'_task_status', true );
            $invoice_status = !empty($invoice_status) ? $invoice_status : '';

            $remaining_price    = isset($order_meta['remaining_price']) ? $order_meta['remaining_price'] : 0;
            $total_price        = isset($remaining_price) && isset($order_meta['price']) ? $remaining_price + $order_meta['price'] : $order_meta['price'];
            $invoice_billing_to = !empty($workreap_settings['invoice_billing_to']) ? $workreap_settings['invoice_billing_to'] : '';
            $billing_address    = !empty($invoice_billing_to) && !empty($workreap_settings['invoice_billing_address']) ? $workreap_settings['invoice_billing_address'] : $billing_address;
            ?>
            <div class="wr-main-section wr-hourly-invoice">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="wr-invoicedetal">
                                <div class="wr-printable">
                                    <div class="wr-invoicebill">
                                        <?php if( !empty($site_logo) ){
                                            if( !empty($args['option']) && $args['option'] === 'pdf'){
                                                $type           = pathinfo($site_logo, PATHINFO_EXTENSION);
                                                $data           = file_get_contents($site_logo);
                                                $base64_logo    = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                                echo do_shortcode( '<figure><img src="'.($base64_logo).'" alt="'.esc_attr__('invoice detail','workreap-hourly-addon').'"></figure>' );
                                            } else { ?>
                                                <figure>
                                                    <img src="<?php echo esc_url($site_logo);?>" alt="<?php esc_attr_e('invoice detail','workreap-hourly-addon');?>">
                                                </figure>
                                        <?php } } ?>
                                        <div class="wr-billno">
                                            
                                            <h3><?php esc_html_e('Invoice', 'workreap-hourly-addon'); ?></h3>
                                            <span># <?php echo intval($order_id); ?></span>
                                        </div>
                                    </div>
                                    <div class="wr-tasksinfos">
                                        <?php if( !empty($task_title) ){?>
                                            <div class="wr-invoicetasks">
                                                <h5><?php esc_html_e('Title','workreap-hourly-addon');?>:</h5>
                                                <h3><?php echo esc_html($task_title); ?></h3>
                                            </div>
                                        <?php } ?>
                                        <div class="wr-tasksdates">
                                            <div class="wr-tags"><span class="wr-tag-ongoing order-status-<?php echo esc_attr($order_status);?>"><?php echo esc_html($order_status_text);?></span></div>
                                            <span> <em><?php esc_html_e('Issue date:', 'workreap-hourly-addon') ?>&nbsp;</em><?php echo esc_html($data_created); ?></span>
                                        </div>
                                    </div>
                                    <div class="wr-invoicefromto">
                                        <?php if (!empty($from_billing_address)){ ?>
                                            <div class="wr-fromreceiver">
                                                <h5><?php esc_html_e('From:', 'workreap-hourly-addon'); ?></h5>
                                                <span><?php echo do_shortcode(nl2br($from_billing_address)); ?></span>
                                            </div>
                                        <?php } ?>

                                        <?php if( !empty($billing_address) ){?>
                                            <div class="wr-fromreceiver">
                                                <h5><?php esc_html_e('To:', 'workreap-hourly-addon'); ?></h5>
                                                <span><?php echo do_shortcode(nl2br($billing_address)); ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php if( !empty($invoice_status) && $invoice_status === 'pending'){?>
                                        <div class="wr-freelancer-empty-hourlyinvoice">
                                            <div class="wr-orderrequest wr-alert-success">
                                                <p><?php esc_html_e('Buyer has not released the payment against the project for which you are hired. Once you will submit the hours for approval then employer will review and release the payment','workreap-hourly-addon') ?></p>
                                            </div>
                                        </div>
                                    <?php } else {?>
                                        <table class="wr-table wr-invoice-table">
                                            <thead>
                                            <tr>
                                                <th><?php esc_html_e('#','workreap-hourly-addon');?></th>
                                                <th><?php esc_html_e('Description', 'workreap-hourly-addon'); ?></th>
                                                <th><?php esc_html_e('Rate per hour', 'workreap-hourly-addon'); ?></th>
                                                <th><?php esc_html_e('Total hours', 'workreap-hourly-addon'); ?></th>
                                                <th><?php esc_html_e('Amount', 'workreap-hourly-addon'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php if( isset($order_meta['approved_total_time']) && isset($order_meta['approved_amount']) ){?>
                                                    <tr>
                                                        <td data-label="<?php esc_attr_e('#', 'workreap-hourly-addon');?>"><?php echo intval(1);?></td>
                                                        <td data-label="<?php esc_attr_e('Description', 'workreap-hourly-addon');?>">
                                                            <?php 
                                                                if( !empty($order_meta['interval_name']) ){
                                                                    echo esc_html($order_meta['interval_name']);
                                                                } 
                                                            ?>
                                                        </td>
                                                        <td data-label="<?php esc_attr_e('Rate per hour', 'workreap-hourly-addon'); ?>"><?php workreap_price_format($order_meta['hourly_rate']);?></td>
                                                        <td data-label="<?php esc_attr_e('Total hours', 'workreap-hourly-addon'); ?>"><?php echo esc_html($order_meta['approved_total_time']);?></td>
                                                        <td data-label="<?php esc_attr_e('Amount', 'workreap-hourly-addon');?>"><?php workreap_price_format($order_meta['approved_amount']);?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="wr-subtotal">
                                            <ul class="wr-subtotalbill">
                                                <?php if( isset($order_meta['approved_amount']) ){?>
                                                    <li><?php esc_html_e('Sub totals:', 'workreap-hourly-addon'); ?> <h6><?php workreap_price_format($order_meta['approved_amount']); ?></h6> </li>
                                                <?php } ?>
                                                <li><?php esc_html_e('Admin commission:','workreap-hourly-addon'); ?> <h6><?php workreap_price_format($processing_fee); ?></h6></li>
                                            </ul>
                                            <div class="wr-sumtotal"><?php esc_html_e('Totals:','workreap-hourly-addon'); ?> <h6><?php workreap_price_format($get_total); ?></h6></div>
                                            
                                        </div>  
                                    <?php } ?>
                                    <?php if( !empty($invoice_terms) ){?>
                                        <div class="wr-anoverview">
                                            <div class="wr-description">
                                                <?php echo do_shortcode( $invoice_terms );?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * Invoice listing
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_hourly_proposal_invoice_listing')) {
    add_action('workreap_hourly_proposal_invoice_listing', 'workreap_hourly_proposal_invoice_listing');
    function workreap_hourly_proposal_invoice_listing($args=array()) {
        global $current_user;
        $proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
        $project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
        $freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
        $employer_id       = !empty($args['employer_id']) ? intval($args['employer_id']) : 0;
        $proposal_meta  = !empty($args['proposal_meta']) ? ($args['proposal_meta']) : array();
        $user_identity  = !empty($args['user_identity']) ? intval($args['user_identity']) : 0;
        $user_type      = !empty($args['user_type']) ? esc_attr($args['user_type']) : '';

        $date_format    = get_option( 'date_format' );
        $time_format    = get_option( 'time_format' );
        $pg_page        = get_query_var('page') ? get_query_var('page') : 1;
        $pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1;
        $per_page_itme  = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
        $paged          = max($pg_page, $pg_paged);
        $current_page   = $paged;
        $order_arg  = array(
            'page'          => $current_page,
            'paginate'      => true,
            'limit'         => $per_page_itme,
            'proposal_id'   => $proposal_id
        );
        
        if (!empty($user_type) && $user_type === 'freelancers') {
            $order_arg['freelancer_id']    = $freelancer_id;
        } else if (!empty($user_type) && $user_type === 'employers') {
            $order_arg['employer_id']    = $employer_id;
        }
        $customer_orders = wc_get_orders( $order_arg );
        ?>
        <div class="tab-pane fade" id="proposal-invoices" role="tabpanel" aria-labelledby="proposal-invoices-tab">
            <div class="wr-proinvoices">
                <div class="wr-proinvoices_title">
                    <h5><?php esc_html_e('Invoices','workreap-hourly-addon');?></h5>
                </div>
                <table class="table wr-proinvoices_table wr-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Date','workreap-hourly-addon');?></th>
                            <th><?php esc_html_e('Title','workreap-hourly-addon');?></th>
                            <th><?php esc_html_e('Status','workreap-hourly-addon');?></th>
                            <th><?php esc_html_e('Hours','workreap-hourly-addon');?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($customer_orders->orders)) {
                            $count_post = count($customer_orders->orders);
                            foreach ($customer_orders->orders as $order) {
                                $data_created	= wc_format_datetime( $order->get_date_created(), $date_format . ', ' . $time_format );
                                $invoice_status = get_post_meta( $order->get_id(),'_task_status', true );
                                $invoice_status = !empty($invoice_status) ? $invoice_status : '';
                                $order_detail   = get_post_meta( $order->get_id(),'cus_woo_product_data', true );
                                $project_type   = !empty($order_detail['project_type']) ? $order_detail['project_type'] : '';
                                $invoice_title  = !empty($order_detail['interval_name']) ? $order_detail['interval_name'] : '';
                                $invoice_price  = 0;
                                if( !empty($user_type) && $user_type === 'freelancers' ){
                                    $invoice_price  = !empty($order_detail['freelancer_shares']) ? $order_detail['freelancer_shares'] : "";
                                } else if( !empty($user_type) && $user_type === 'employers' ){
                                    $invoice_price      = $order->get_total();
                                    if(function_exists('wmc_revert_price')){
                                        $invoice_price =  wmc_revert_price($order->get_total(),$order->get_currency());
                                    }
                                }
                                $invoice_url  = !empty($order->get_id()) && $current_user->ID ? Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $current_user->ID, true, 'hourly-detail', intval($order->get_id())) : '';
                                if( !empty($user_type) && $user_type === 'freelancers' && $invoice_status === 'pending' ){
                                    //do something
                                }
                        ?>
                        <tr>
                            <td data-label="<?php esc_attr_e('Date','workreap-hourly-addon');?>"><?php echo esc_html($data_created); ?></td>
                            <td data-label="<?php esc_attr_e('Title','workreap-hourly-addon');?>">
                                <p><?php echo esc_html($invoice_title);?></p>
                            </td>
                            <td data-label="<?php esc_attr_e('Status','workreap-hourly-addon');?>">
                                <?php do_action( 'workreap_proposal_invoice_status_tag', $invoice_status );?>
                            </td>
                            <td data-label="<?php esc_attr_e('Hours','workreap-hourly-addon');?>">
                                <?php 
                                    if( isset($order_detail['approved_total_time']) ){
                                        echo esc_html($order_detail['approved_total_time']);
                                    } else if( isset($order_detail['max_hours']) ){
                                        echo esc_html($order_detail['max_hours']);
                                    }
                                ?>
                            </td>
                            <td data-label="<?php esc_attr_e('Action','workreap-hourly-addon');?>">
                                <a href="<?php echo esc_url($invoice_url);?>"><?php esc_html_e('View invoice','workreap-hourly-addon');?></a>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
                <?php 
                    workreap_paginate($customer_orders);
                    if (empty($customer_orders->orders)) {
                        do_action( 'workreap_empty_listing', esc_html__('No invoices & bills found', 'workreap-hourly-addon'));
                    }
                ?>
            </div>
        </div>
    <?php
    }
}

/**
 * Project search filter option 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_search_option')){
	function workreap_project_search_option() {
        $project_types_array    = array();
        $project_types          = workreap_project_type();
        $selected_type          = !empty($_GET['project_type']) ? $_GET['project_type'] : 'all';
        if( !empty($project_types) ){
            $project_types_array['all'] = esc_html__('All','workreap-hourly-addon');
            foreach( $project_types as $key => $val ){
                $project_types_array[$key]  = !empty($val['title']) ? $val['title'] : "";
            }
        }
        ob_start();
        ?>
        <div class="wr-aside-holder">
            <div class="wr-asidetitle" data-bs-toggle="collapse" data-bs-target="#wr_project_type_holder" role="button" aria-expanded="true">
                <h5><?php esc_html_e('Project types','workreap-hourly-addon'); ?></h5>
            </div>
            <div id="wr_project_type_holder" class="collapse show">
                <div class="wr-aside-content">
                    <div class="wr-filterselect">
                        <div class="wr-select">
                            <?php do_action( 'workreap_custom_dropdown_html', $project_types_array,'project_type','wr-project-type',$selected_type );?>
                        </div>
                    </div>                                    
                </div>
            </div>
        </div>
        <?php
        $scripts	= "
        ( function ( $ ) {
            'use strict'; 
            jQuery(document).ready(function($){        
                jQuery('.wr-project-type').select2({
                    minimumResultsForSearch: Infinity,
                    theme: 'default wr-select2-dropdown',
                });
            
            });
        } ( jQuery ) );";

        wp_add_inline_script('workreap', $scripts, 'after');
        echo ob_get_clean();
	}
    add_action('workreap_project_search_option', 'workreap_project_search_option');
}

/**
 * Project completed model
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_completed_form')){
	function workreap_project_completed_form($args=array()) {
        global $current_user, $workreap_settings;
        $user_identity 	= intval($current_user->ID);
        $proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
        $project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
        $project_title  = !empty($args['project_title']) ? esc_attr($args['project_title']) : 0;
        $freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
        $proposal_type  = get_post_meta( $proposal_id, 'proposal_type', true );
        $profile_id     = workreap_get_linked_profile_id($freelancer_id, '','freelancers');
        $user_name      = workreap_get_username($profile_id);
        $avatar         = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $profile_id), array('width' => 50, 'height' => 50));
        $proposal_price = isset($args['proposal_meta']['price']) ? $args['proposal_meta']['price'] : 0;

        $remaning_amount    = get_post_meta( $proposal_id, 'remaining_amount',true );
        $remaning_amount    = isset($remaning_amount) ? $remaning_amount : 0;
        $hourly_image	    = workreap_add_http_protcol(WORKREAP_HOURLY_ADDON_URI . 'public/images/arrow.png');
        if( !empty($proposal_type) && $proposal_type === 'hourly'){
        ob_start();
        ?>
        <script type="text/template" id="tmpl-load-completed_project_form">
            <div class="wr-complete-process">
                <div class="wr-complete-process_head">
                    <div class="wr-complete-title">
                        <strong class="wr-counterinfo_carried"><i class="wr-icon-git-branch"></i></strong>
                        <h5><?php workreap_price_format($remaning_amount);?> <span><?php esc_html_e('C/F balance','workreap-hourly-addon');?></span> </h5>
                    </div>
                    <div><img src="<?php echo esc_url($hourly_image);?>" alt="<?php esc_attr_e('Complete project','workreap-hourly-addon');?>"></div>
                    <div class="wr-complete-title">
                        <h5 class="text-right"><?php workreap_price_format($remaning_amount);?> <span><?php esc_html_e('Wallet balance','workreap-hourly-addon');?></span> </h5>
                        <strong class="wr-counterinfo_card"><i class="wr-icon-credit-card"></i></strong>
                    </div>
                </div>
                <p><?php esc_html_e("Please note, your remaining total carried forward amount will be automatically transferred to your wallet for your further use.","workreap");?></p>
            </div>
            <div class="wr-projectsstatus_head">
                <div class="wr-projectsstatus_info">
                    <figure class="wr-projectsstatus_img">
                        <img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
                    </figure>
                    <?php if(!empty($user_name)){?>
                        <div class="wr-projectsstatus_name">
                            <h5><?php echo esc_html($user_name);?></h5>
                        </div>
                    <?php }?>
                </div>
                <div class="wr-completestatus_budget">
                    <div class="form-group">
                        <div class="wr-my-ratingholder">
                            <ul id="wr_stars-{{data.proposal_id}}" class='wr-rating-stars wr_stars'>
                                <li class='wr-star' data-value='1'  data-id="{{data.proposal_id}}">
                                    <i class='wr-icon-star fa-fw'></i>
                                </li>
                                <li class='wr-star' data-value='2'  data-id="{{data.proposal_id}}">
                                    <i class='wr-icon-star fa-fw'></i>
                                </li>
                                <li class='wr-star' data-value='3'  data-id="{{data.proposal_id}}">
                                    <i class='wr-icon-star fa-fw'></i>
                                </li>
                                <li class='wr-star' data-value='4'  data-id="{{data.proposal_id}}">
                                    <i class='wr-icon-star fa-fw'></i>
                                </li>
                                <li class='wr-star' data-value='5'  data-id="{{data.proposal_id}}">
                                    <i class='wr-icon-star fa-fw'></i>
                                </li>
                            </ul>
                            <input type="hidden" id="wr_task_rating-{{data.proposal_id}}" name="rating" value="1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="wr-themeform">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group">
                            <div class="wr-placeholderholder">
                            <input type="text" class="form-control" id="wr_rating_title-{{data.proposal_id}}" name="title" placeholder="<?php esc_attr_e('Add feedback title','workreap-hourly-addon');?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="wr_rating_details-{{data.proposal_id}}" name="details" placeholder="<?php esc_attr_e('Feedback','workreap-hourly-addon');?>"></textarea>
                        </div>
                        <div class="form-group wr-btnarea-two">
                            <ul class="wr-formbtnlist">
                                <li id="wr_without_feedback">
                                    <a href="javascript:void(0);" data-proposal_id="{{data.proposal_id}}" data-user_id="<?php echo intval($url_identity);?>" class="wr-btn wr-plainbtn wr_complete_project"><?php esc_html_e('Complete without review','workreap-hourly-addon');?></a>
                                </li>
                                <li><a href="javascript:void(0);" data-user_id="<?php echo intval($url_identity);?>" data-proposal_id="{{data.proposal_id}}" class="wr-btn wr-greenbg wr_rating_project"><?php esc_html_e('Complete contract','workreap-hourly-addon');?></a></li>
                            </ul>
                        </div>
                    </div>
                </fieldset>
            </div>
        </script>
        <?php
        echo ob_get_clean();
        }
	}
    add_action('workreap_project_completed_form','workreap_project_completed_form');
}

/**
 * Project completed model
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_completed_model')){
	function workreap_project_completed_model() {
        ob_start();
        ?>
        <div class="modal fade wr-pricerequest-popup" id="wr_project_completetask" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog wr-modaldialog" role="document">
                <div class="modal-content">
                    <div class="wr-popuptitle">
                        <h4 id="wr_ratingtitle"><?php esc_html_e('Complete task','workreap-hourly-addon');?></h4>
                        <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                    </div>
                    <div class="modal-body wr-popup-content" id="wr_projectcomplete_form"></div>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();
	}
    add_action('workreap_project_completed_model','workreap_project_completed_model');
}

/**
 * After project requirements
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_after_project_requirements')){
	function workreap_after_project_requirements($project_id=0) {
        $project_meta	= get_post_meta( $project_id, 'wr_project_meta',true);
	    $project_type	= !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
        $payment_mode   = !empty($project_meta['payment_mode']) ? $project_meta['payment_mode'] : '';
        if( !empty($project_type) && $project_type === 'hourly' && !empty($payment_mode) ){
            ob_start();
            ?>
            <li>
                <i class="wr-icon-clock wr-purple-icon"></i>
                <div class="wr-project-requirement_content">
                    <em><?php esc_html_e('Project type','workreap-hourly-addon');?></em>
                    <div class="wr-requirement-tags">
                        <span><?php echo ucfirst( esc_html($payment_mode) );?></span>
                    </div>
                </div>
            </li>
            <?php
            echo ob_get_clean();
        }
	}
    add_action('workreap_after_project_requirements','workreap_after_project_requirements');
}