<?php



/**
 * Hire project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_hire_hourly_project')) {
    function workreap_hire_hourly_project() {
        global $current_user;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $post_id            = !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $transaction_id     = !empty($_POST['transaction_id']) ? intval($_POST['transaction_id']) : 0;
        $wallet             = !empty($_POST['wallet']) ? sanitize_text_field($_POST['wallet']) : '';
        $json               = array();

        if( !empty($post_id) ){
            workreapHiredHourlyProject($post_id,$current_user->ID,$transaction_id,$wallet);
        } else {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
			$json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap-hourly-addon');
			wp_send_json( $json );
        }
    }
    add_action('wp_ajax_workreap_hire_hourly_project', 'workreap_hire_hourly_project');
}

/**
 * Hire project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_approved_hourly_project')) {
    function workreap_approved_hourly_project() {
        global $current_user;
        
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }
        
        $post_id    = !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $json       = array();
        if( !empty($post_id) ){
            workreapApproveddHourlyProject($_POST,$current_user->ID);
        } else {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
			$json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap-hourly-addon');
			wp_send_json( $json );
        }
    }
    add_action('wp_ajax_workreap_approved_hourly_project', 'workreap_approved_hourly_project');
}
/**
 * Hire project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_update_hourly_timetracking')) {
    function workreap_update_hourly_timetracking() {
        global $current_user;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $data       = !empty($_POST['data']) ? $_POST['data'] : array();
        parse_str($data,$data);
        $json       = array();

        if( !empty($data['proposal_id']) ){
            workreapUpdatTimeTracking($data,$current_user->ID);
        } else {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
			$json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap-hourly-addon');
			wp_send_json( $json );
        }
    }
    add_action('wp_ajax_workreap_update_hourly_timetracking', 'workreap_update_hourly_timetracking');
}

/**
 * Submit hourly request
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_submite_hourly_activities')) {
    function workreap_submite_hourly_activities() {
        global $current_user;

        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $data       = !empty($_POST) ? $_POST : array();
        $json       = array();
        workreapSubmitTimeTracking($data,$current_user->ID);
    }
    add_action('wp_ajax_workreap_submite_hourly_activities', 'workreap_submite_hourly_activities');
}

/**
 * Hire project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_update_hourly_decline')) {
    function workreap_update_hourly_decline() {
        global $current_user;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        workreapDeclineTimeTracking($_POST,$current_user->ID);
    }
    add_action('wp_ajax_workreap_update_hourly_decline', 'workreap_update_hourly_decline');
}

/**
 * Add time slots function
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreapDeclineTimeTracking')) {
    function workreapDeclineTimeTracking($data=array(),$user_id=0,$option_type='') {
        $proposal_id    = !empty($data['proposal_id']) ? intval($data['proposal_id']) : 0;
        $transaction_id = !empty($data['transaction_id']) ? intval($data['transaction_id']) : 0;
        $detail         = !empty($data['detail']) ? sanitize_textarea_field($data['detail']) : 0;
        
        $json                   = array();
        $json['type']           = 'error';
	    $json['message'] 		    = esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap-hourly-addon');
        if( empty($detail) ){
            $json['message_desc']   = esc_html__('Decline detail field is required','workreap-hourly-addon');
        }

        if( !empty($transaction_id) && !empty($proposal_id) && !empty($detail)){
            $time_slots         = get_post_meta( $proposal_id, 'wr_timetracking',true );
            $time_slots         = !empty($time_slots) ? $time_slots : array();
            $time_slot_status   = !empty($time_slots[$transaction_id]['status']) ? $time_slots[$transaction_id]['status'] : '';
            if( !empty($time_slot_status) && in_array($time_slot_status,array('pending')) ){
                $time_slots[$transaction_id]['status']          = 'decline';
                $time_slots[$transaction_id]['decline_date']    = current_time( 'mysql', 1 );
                $time_slots[$transaction_id]['decline_detail']  = !empty($detail) ? $detail : '';

                $project_id     = get_post_meta( $proposal_id, 'project_id', true );
                $freelancer_id      = get_post_field( 'post_author', $proposal_id );
                $payment_mode   = get_post_meta( $project_id, 'payment_mode', true );
                $interval_name  = "";

                if( !empty($payment_mode) && $payment_mode === 'weekly' ){
                    $interval_name		    = workreap_get_hourly_week_intervals($transaction_id);
                } else if( !empty($payment_mode) && $payment_mode === 'daily' ){
                    $interval_name		    = date_i18n('Y-m-d',$transaction_id );
                } else if( !empty($payment_mode) && $payment_mode === 'monthly' ){
                    $interval_name		    = date_i18n('F Y',$transaction_id );
                }

                $employer_profile_id   = 0;
                $freelancer_profile_id  = 0;
                if( function_exists('workreap_get_linked_profile_id') ) {
                    $employer_profile_id                   = workreap_get_linked_profile_id($user_id, '', 'employers');
                    $freelancer_profile_id                  = workreap_get_linked_profile_id($freelancer_id, '', 'freelancers');
                }
                
                $notifyData						    = array();
                $notifyDetails					    = array();
                $notifyDetails['project_id']        = $project_id;
                $notifyDetails['proposal_id']  	    = $proposal_id;
                $notifyDetails['transaction_id']  	= $transaction_id;
                $notifyDetails['interval_name']	    = $interval_name;
                $notifyDetails['decline_detail']  	= $decline_detail;
                $notifyDetails['freelancer_id']         = $freelancer_profile_id;
                $notifyDetails['employer_id']          = $employer_profile_id;

                $notifyData['receiver_id']		= $freelancer_id;
                $notifyData['type']				= 'hours_decline';
                $notifyData['post_data']		= $notifyDetails;
                $notifyData['user_type']		= 'freelancers';
                $notifyData['linked_profile']	= $freelancer_profile_id;
                do_action('workreap_notification_message', $notifyData );

                // Email for decline hours 
                $hourly_request_decline_freelancer_switch   = !empty($workreap_settings['hourly_request_decline_freelancer_email_switch']) ? $workreap_settings['hourly_request_decline_freelancer_email_switch'] : true;
                if(class_exists('HourlyAddonEmails') && !empty($hourly_request_decline_freelancer_switch)){
                    $emailData                      = array();
                    $emailData['freelancer_email']      = get_userdata($freelancer_id)->user_email;
                    $emailData['employer_name']        = '';
                    $emailData['freelancer_name']       = '';
                    if( function_exists('workreap_get_username') ) {
                        $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                        $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                    }
                    $emailData['project_title']     = get_the_title($project_id);
                    $emailData['decline_detail']    = $detail;
                    $emailData['project_link']      = '';
                    if(class_exists('Workreap_Profile_Menu')){
                        $emailData['project_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'activity',$proposal_id).'&transaction_id='.intval($transaction_id);
                    }
                    $email_helper = new HourlyAddonEmails();
                    $email_helper->hourly_project_request_decline_freelancer_email($emailData);
                }

                update_post_meta( $proposal_id, 'wr_timetracking',$time_slots );
                $json['type']           = 'success';
	            $json['message']    = esc_html__('Woohoo!', 'workreap');
                $json['message_desc']   = esc_html__('You have successfully decline hourly time slots','workreap-hourly-addon');

                if( empty($option_type) ){
                    wp_send_json($json);
                } else {
                    return $json;
                }

            } else {
                $json['type']           = 'error';
	            $json['message'] 		    = esc_html__('Oops!', 'workreap');
                $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap-hourly-addon');
                if( empty($option_type) ){
                    wp_send_json($json);
                } else {
                    return $json;
                }
            }
        }

        if( empty($option_type) ){
            wp_send_json($json);
        } else {
            return $json;
        }

    }
}

/**
 * Add time slots function
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreapSubmitTimeTracking')) {
    function workreapSubmitTimeTracking($data=array(),$user_id=0,$option_type='') {
        $proposal_id    = !empty($data['id']) ? intval($data['id']) : 0;
        $transaction_id = !empty($data['transaction_id']) ? intval($data['transaction_id']) : 0;

        $json                   = array();
        $json['type']           = 'error';
	    $json['message'] 		    = esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap-hourly-addon');

        if( !empty($transaction_id) && !empty($proposal_id) ){
            $time_slots         = get_post_meta( $proposal_id, 'wr_timetracking',true );
            $time_slots         = !empty($time_slots) ? $time_slots : array();
            $time_slot_status   = !empty($time_slots[$transaction_id]['status']) ? $time_slots[$transaction_id]['status'] : '';
            
            if( !empty($time_slot_status) && in_array($time_slot_status,array('draft','decline')) ){
                $time_slots[$transaction_id]['status']          = 'pending';
                $time_slots[$transaction_id]['requested_date']  = current_time( 'mysql', 1 );
                $total_hours    = isset($time_slots[$transaction_id]['total_time']) ? $time_slots[$transaction_id]['total_time'] : 0;
                
                if( isset($total_hours) && $total_hours <= 0 ){
                    $json['message_desc']   = esc_html__('You must need to add hours for submit this activity request','workreap-hourly-addon');
                    if( empty($option_type) ){
                        wp_send_json($json);
                    } else {
                        return $json;
                    }
                }

                $interval_key   = $transaction_id;

                $project_id     = get_post_meta( $proposal_id, 'project_id', true );
                $employer_id       = get_post_field( 'post_author', $project_id );
                $payment_mode   = get_post_meta( $project_id, 'payment_mode', true );
                $interval_name  = "";

                if( !empty($payment_mode) && $payment_mode === 'weekly' ){
                    $interval_name		    = workreap_get_hourly_week_intervals($transaction_id);
                } else if( !empty($payment_mode) && $payment_mode === 'daily' ){
                    $interval_name		    = date_i18n('Y-m-d',$transaction_id );
                } else if( !empty($payment_mode) && $payment_mode === 'monthly' ){
                    $interval_name		    = date_i18n('F Y',$transaction_id );
                }

                $employer_profile_id           = 0;
                $freelancer_profile_id          = 0;
                if( function_exists('workreap_get_linked_profile_id') ) {
                    $employer_profile_id                   = workreap_get_linked_profile_id($employer_id, '', 'employers');
                    $freelancer_profile_id                  = workreap_get_linked_profile_id($user_id, '', 'freelancers');
                }
                
                $notifyData						    = array();
                $notifyDetails					    = array();
                $notifyDetails['project_id']        = $project_id;
                $notifyDetails['proposal_id']  	    = $proposal_id;
                $notifyDetails['transaction_id']  	= $transaction_id;
                $notifyDetails['interval_name']	    = $interval_name;
                $notifyDetails['total_hours']  	    = $total_hours;
                $notifyDetails['freelancer_id']         = $freelancer_profile_id;
                $notifyDetails['employer_id']          = $employer_profile_id;

                $notifyData['receiver_id']		= $employer_id;
                $notifyData['type']				= 'hours_submiation';
                $notifyData['post_data']		= $notifyDetails;
                $notifyData['user_type']		= 'employers';
                $notifyData['linked_profile']	= $employer_profile_id;
                do_action('workreap_notification_message', $notifyData );

                // Email submit hourly request
                $hourly_request_employer_switch        = !empty($workreap_settings['hourly_request_send_employer_email_switch']) ? $workreap_settings['hourly_request_send_employer_email_switch'] : true;
                if(class_exists('HourlyAddonEmails') && !empty($hourly_request_employer_switch)){
                    $emailData                      = array();
                    $emailData['employer_email']       = get_userdata($employer_id)->user_email;
                    $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                    $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                    $emailData['project_title']     = get_the_title($project_id);
                    $emailData['project_link']     = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $employer_id, true, 'activity',$proposal_id);

                    $email_helper = new HourlyAddonEmails();
		            $email_helper->hourly_project_request_employer_email($emailData);

                }

                update_post_meta( $proposal_id, 'wr_timetracking',$time_slots );
                $json['type']           = 'success';
	            $json['message']    = esc_html__('Woohoo!', 'workreap');
                $json['message_desc']   = esc_html__('You have successfully submit hourly time slots to employer','workreap-hourly-addon');
                
                if( empty($option_type) ){
                    wp_send_json($json);
                } else {
                    return $json;
                }
            } else {
                $json['type']           = 'error';
	            $json['message'] 		    = esc_html__('Oops!', 'workreap');
                $json['message_desc']   = esc_html__('You have already submit these slots to employer','workreap-hourly-addon');
                if( empty($option_type) ){
                    wp_send_json($json);
                } else {
                    return $json;
                }
            }
        }

        if( empty($option_type) ){
            wp_send_json($json);
        } else {
            return $json;
        }

    }
}
/**
 * Add time slots function
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreapUpdatTimeTracking')) {
    function workreapUpdatTimeTracking($data=array(),$user_id=0,$option_type='') {
        $proposal_id            = !empty($data['proposal_id']) ? intval($data['proposal_id']) : 0;
        $post_autor             = get_post_field( 'post_author', $proposal_id );
        $json                   = array();
        $json['type']           = 'error';
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap-hourly-addon');

        if( !empty($proposal_id) && $post_autor == $user_id){
            $validation = array(
                'working_time'  => esc_html__('Please add working time','workreap-hourly-addon'),
                'details'       => esc_html__('Working hours detail is required','workreap-hourly-addon'),
                'time_id'       => esc_html__('Something went wrong','workreap-hourly-addon'),
                'time_date'     => esc_html__('Something went wrong','workreap-hourly-addon'),
            );
        }
        
        if(!empty($validation)){
            foreach($validation as $key => $val ){
                if( empty($data[$key]) ){
                    $json['type']           = 'error';
	                $json['message'] 		    = esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = $val;
                    if( empty($option_type) ){
                        wp_send_json($json);
                    } else {
                        return $json;
                    }
                }
            }
        }

        $time_string        = !empty($data['working_time']) ? $data['working_time'] : array();
        $details            = !empty($data['details']) ? $data['details'] : '';
        $time_date          = !empty($data['time_date']) ? $data['time_date'] : '';
        $time_id            = !empty($data['time_id']) ? $data['time_id'] : '';
        $hours              = substr($time_string, 0, 2);
        $mints              = substr($time_string, 6, 2);
        $hours              = !empty($hours) ? intval($hours) : 0;
        $mints              = !empty($mints) ? intval($mints) : 0;

        $minttohours            = !empty($mints) ? number_format(($mints / 60),2) : 0;
        $slot_working_time      = !empty($mints) ? ($hours+$minttohours) : $hours;
        
        $time_slots                 = get_post_meta( $proposal_id, 'wr_timetracking',true );
        $time_slots                 = !empty($time_slots) ? $time_slots : array();
        $current_timeslots          = !empty($time_slots[$time_id]) ? $time_slots[$time_id] : array();
        $current_timeslots_status   = !empty($current_timeslots['status']) ? $current_timeslots['status'] : '';

        if( !empty($current_timeslots_status) && in_array($current_timeslots_status,array('pending','completed')) ){
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap-hourly-addon');
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        }

        $total_time                 = 0;
        if( !empty($current_timeslots['slots']) ){
            foreach($current_timeslots['slots'] as $key => $value ){
                $val_total  = !empty($value['slot_time']) ? $value['slot_time'] : 0;
                if( !empty($key) && $key != $time_date ){
                    $total_time = $total_time + $val_total;
                }
            }
        }
        
        $updated_total_time = $slot_working_time + $total_time;
        $project_id         = get_post_meta( $proposal_id, 'project_id',true );
        $max_hours          = get_post_meta( $project_id, 'max_hours',true);
        if( !empty($max_hours) && $updated_total_time > $max_hours ){
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc']   = sprintf(__('You have option to add maximum %s hours','workreap-hourly-addon'),$max_hours);
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        }

        if ( empty($hours) && empty($mints)) {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__('Please add working hours and minutes','workreap-hourly-addon');
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        }

        if( $hours < 0 ) {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__('Hours must be greater then 0','workreap-hourly-addon');
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        }

        if( $mints > 59 ) {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__('Minutes must be less the 60','workreap-hourly-addon');
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        }

        if( $mints < 0 ) {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__('Minutes must be greater then 0','workreap-hourly-addon');
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        }
        
        if (!empty($hours) && $hours > 23) {
            $json['type']           = 'error';
	        $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__('Minutes must be less the 23','workreap-hourly-addon');
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        }

        $new_time                   = array();
        $new_time['details']        = $details;
        $new_time['hours']          = $hours;
        $new_time['mints']          = $mints;
        $new_time['slot_time']      = $slot_working_time;
        $new_time['time_string']    = $time_string;
        $time_slots[$time_id]['total_time']             = $updated_total_time;
        $time_slots[$time_id]['status']                 = 'draft';
        $time_slots[$time_id]['slots'][$time_date]      = $new_time;
        update_post_meta( $proposal_id, 'wr_timetracking',$time_slots );

        $json['type']           = 'success';
	    $json['message']    = esc_html__('Woohoo!', 'workreap');
        $json['message_desc']   = esc_html__('You have successfully update time for this date','workreap-hourly-addon');
        if( empty($option_type) ){
            wp_send_json($json);
        } else {
            return $json;
        }

        if( empty($option_type) ){
            wp_send_json($json);
        } else {
            return $json;
        }
    }
}
if (!function_exists('workreapApproveddHourlyProject')) {
    function workreapApproveddHourlyProject($data=array(),$user_id=0,$transaction_id=0,$option_type='') {
        $json                   = array();
        $json['type']           = 'error';
	    $json['message'] 		    = esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap-hourly-addon');
        $proposal_id            = !empty($data['id']) ? intval($data['id']) : 0;
        $transaction_id         = !empty($data['transaction_id']) ? intval($data['transaction_id']) : 0;

        if( !empty($proposal_id) && !empty($transaction_id) ){
            $gmt_time		= current_time( 'mysql', 1 );
            $project_id     = get_post_meta( $proposal_id, 'project_id',true );
            $time_slots     = get_post_meta( $proposal_id, 'wr_timetracking',true );
            $time_slot      = !empty($time_slots[$transaction_id]) ? $time_slots[$transaction_id] : array();
            
            $order_id       = !empty($time_slot['order_id']) ? $time_slot['order_id'] : 0;
            $approved_time  = isset($time_slot['total_time']) ? $time_slot['total_time'] : 0;

            $order_data     = get_post_meta( $order_id, 'cus_woo_product_data',true );
            $order_data     = !empty($order_data) ? $order_data : array();
            $hourly_rate    = isset($order_data['hourly_rate']) ? $order_data['hourly_rate'] : 0;
            $order_remaining= isset($order_data['remaining_price']) ? $order_data['remaining_price'] : 0;
            $price          = isset($order_data['price']) ? ($order_data['price'] + $order_remaining) : 0;
            
            $approved_amount= $approved_time*$hourly_rate;
            $remaining_price= $price - $approved_amount;
            $service_fee    = workreap_commission_fee($approved_amount);
            $admin_shares   = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
            $freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $approved_amount;
            
            $order_data['approved_amount']                                      = $approved_amount;
            $order_data['approved_total_time']                                  = isset($time_slot['total_time']) ? $time_slot['total_time'] : 0;
            $order_data['admin_shares']                                         = $admin_shares;
            $order_data['freelancer_shares']                                    = $freelancer_shares;
            $order_data['remaining_price']                                      = $remaining_price;
            $order_data['time_slots'][$transaction_id]['approved_amount']       = $approved_amount;
            $order_data['time_slots'][$transaction_id]['remaining_price']       = $remaining_price;
            $order_data['time_slots'][$transaction_id]['completed_time']        = $gmt_time;

            $time_slots[$transaction_id]['approved_amount']     = $approved_amount;
            $time_slots[$transaction_id]['remaining_price']     = $remaining_price;
            $time_slots[$transaction_id]['completed_time']      = $gmt_time;
            $time_slots[$transaction_id]['status']              = 'completed';
            
            $freelancer_id              = get_post_field( 'post_author', $proposal_id );
            $employer_id               = get_post_field( 'post_author', $project_id );
            update_post_meta( $proposal_id, 'wr_timetracking', $time_slots );

            if( isset($remaining_price) ){
                update_post_meta( $proposal_id, 'remaining_amount', $remaining_price );
                update_post_meta( $proposal_id, 'remaining_order_id', $order_id );
                update_post_meta( $proposal_id, 'remaining_transaction_id', $transaction_id );
            }
            
            update_post_meta( $order_id, 'admin_shares', $admin_shares );
            update_post_meta( $order_id, 'freelancer_shares', $freelancer_shares );
            update_post_meta( $order_id, 'cus_woo_product_data', $order_data );
            update_post_meta( $order_id, '_post_project_status', 'completed' );
            update_post_meta( $order_id, '_task_status', 'completed' );
            update_post_meta( $order_id, 'freelancer_id', $freelancer_id );

            $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
            $freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
            $notifyData						    = array();
            $notifyDetails					    = array();
            $notifyDetails['project_id']        = $project_id;
            $notifyDetails['proposal_id']  	    = $proposal_id;
            $notifyDetails['transaction_id']  	= $transaction_id;
            $notifyDetails['interval_name']	    = !empty($order_detail['interval_name']) ? $order_detail['interval_name'] : '';
            $notifyDetails['total_hours']  	    = !empty($order_detail['time_slots']['total_time']) ? $order_detail['time_slots']['total_time'] : '';
            $notifyDetails['freelancer_id']         = $freelancer_profile_id;
            $notifyDetails['employer_id']          = $employer_profile_id;
            $notifyData['receiver_id']		    = $freelancer_id;
            $notifyData['type']				    = 'hours_approved';
            $notifyData['post_data']		    = $notifyDetails;
            $notifyData['user_type']		    = 'freelancers';
            $notifyData['linked_profile']	    = $freelancer_profile_id;
            do_action('workreap_notification_message', $notifyData );

            // add email for approved invoice/hourly request
            $hourly_request_approve_freelancer_switch   = !empty($workreap_settings['hourly_request_approve_freelancer_email_switch']) ? $workreap_settings['hourly_request_approve_freelancer_email_switch'] : true;
            if(class_exists('HourlyAddonEmails') && !empty($hourly_request_approve_freelancer_switch)){
                $emailData                      = array();
                $emailData['freelancer_email']      = get_userdata($freelancer_id)->user_email;
                $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                $emailData['project_title']     = get_the_title($project_id);
                $emailData['project_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'activity',$proposal_id).'&transaction_id='.intval($transaction_id);

                $email_helper = new HourlyAddonEmails();
                $email_helper->hourly_project_request_approve_freelancer_email($emailData);
            }

            $json['type']           = 'success';
	        $json['message']    = esc_html__('Woohoo!', 'workreap');
            $json['message_desc']   = esc_html__('You have successfully approved this time interval','workreap-hourly-addon');
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            }
        } else {
            if( empty($option_type) ){
                wp_send_json($json);
            } else {
                return $json;
            } 
        }
    }
}

if (!function_exists('workreapHiredHourlyProject')) {
    function workreapHiredHourlyProject($proposal_id=0,$user_id=0,$transaction_id=0,$wallet='',$option_type='') {
        global $workreap_settings;
        $admin_commision_employers     =  !empty($workreap_settings['admin_commision_employers']) ? $workreap_settings['admin_commision_employers'] : 0;
        $commission_text            =  !empty($workreap_settings['commission_text']) ? $workreap_settings['commission_text'] : esc_html__('Processing fee', 'workreap');
		
        $json                   = array();
        $json['type']           = 'error';
	    $json['message'] 		    = esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap-hourly-addon');
        $project_id             = get_post_meta( $proposal_id, 'project_id',true );
        $proposal_meta	        = get_post_meta( $proposal_id, 'proposal_meta',true);
        $proposal_meta          = !empty($proposal_meta) ? $proposal_meta : array();
        $project_id             = !empty($project_id) ? intval($project_id) : 0;
        $proposal_status        = get_post_status( $proposal_id );
        $no_of_freelancers	    = get_post_meta( $project_id, 'no_of_freelancers',true);
        $no_of_freelancers	    = !empty($no_of_freelancers) ? intval($no_of_freelancers) : 0;
        $workreap_post_count	    = workreap_post_count('proposals',array('hired','completed','refunded','disputed'),array('project_id' => $project_id));
        
        if( empty($workreap_post_count) || ($workreap_post_count < $no_of_freelancers) || !empty($proposal_status) && $proposal_status === 'hired' ){
            $max_hours              = !empty($project_id) ? get_post_meta( $project_id, 'max_hours', true ) : '';
            $hourly_rate            = isset($proposal_meta['price']) ? $proposal_meta['price'] : 0;
            if( !empty($max_hours) && !empty($hourly_rate)){
                $total_price        = $max_hours * $hourly_rate;
                $project_meta       = get_post_meta( $project_id, 'wr_project_meta',true );
                $project_meta       = !empty($project_meta) ? $project_meta : array();
                $time_slots         = get_post_meta( $proposal_id, 'wr_timetracking',true );
                $time_slots         = !empty($time_slots) ? $time_slots : array();
                $remaining_price    = get_post_meta( $proposal_id, 'remaining_amount',true );
                $remaining_price    = isset($remaining_price) && $remaining_price > 0 ? $remaining_price : 0;
                $total_price        = $total_price - $remaining_price;
                $payment_mode       = get_post_meta( $project_id, 'payment_mode', true );
                $interval_name      = "";
                
                if( empty($transaction_id) ){
                    if( !empty($payment_mode) && $payment_mode === 'weekly' ){
                        
                        $interval           = workreap_get_weekrang(date('Y-m-d'));
                       
                        $transaction_id     = !empty($interval['start_time']) ? strtotime($interval['start_time']) : 0;
                    } else if( !empty($payment_mode) && $payment_mode === 'daily' ){
                        $interval           = date('Y-m-d',time());
                        $transaction_id     = !empty($interval) ? strtotime($interval) : 0;
                    } else if( !empty($payment_mode) && $payment_mode === 'monthly' ){
                        $interval           = date('Y-m-01',time());
                        $transaction_id     = !empty($interval) ? strtotime($interval) : 0;
                    }
                }

                if( !empty($payment_mode) && $payment_mode === 'weekly' ){
                    $interval_name		= workreap_get_hourly_week_intervals(date('Y-m-d',$transaction_id));
                } else if( !empty($payment_mode) && $payment_mode === 'daily' ){
                    $interval_name		    = date_i18n(get_option('date_format'),$transaction_id );
                } else if( !empty($payment_mode) && $payment_mode === 'monthly' ){
                    $interval_name		= date_i18n('F Y',$transaction_id );
                }

                global $woocommerce;
                $woocommerce->cart->empty_cart();
                if( !empty($option_type) && $option_type === 'mobile' ){
                    check_prerequisites($user_id);
                }

                $price          = isset($total_price) && $total_price > 0 ? $total_price : 0;
                $freelancer_id      = get_post_field( 'post_author', $proposal_id );
                $user_balance   = !empty($user_id) ? get_user_meta( $user_id, '_employer_balance',true ) : '';
                $project_type   = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
                $product_id     = workreap_employer_wallet_create();
               
                $cart_meta                                              = array();
                $time_slots_array                                       = array();
                $time_slots_array[$transaction_id]['total_time']        = $max_hours;
                $time_slots_array[$transaction_id]['interval_name']     = $interval_name;
                $time_slots_array[$transaction_id]['hourly_rate']       = $hourly_rate;

                if( !empty($wallet) && !empty($user_balance) && $user_balance < $price ){
                    $cart_meta['wallet_price']		    = $user_balance;
                }

                if( !empty($remaining_price) ){
                    $remaining_transaction_id       = get_post_meta( $proposal_id, 'remaining_transaction_id',true );
                    $remaining_transaction_id       = isset($remaining_transaction_id) ? $remaining_transaction_id : 0;

                    $remaining_order_id             = get_post_meta( $proposal_id, 'remaining_order_id',true );
                    $remaining_order_id             = isset($remaining_order_id) ? $remaining_order_id : 0;

                    $cart_meta['remaining_price']               = $remaining_price;
                    $cart_meta['remaining_order_id']            = $remaining_order_id;
                    $cart_meta['remaining_transaction_id']      = $remaining_transaction_id;
                }

                if(function_exists('workreap_processing_fee_calculation')){
                    $employer_service_fee		= workreap_processing_fee_calculation('projects',$price);
                }

                $cart_meta['hiring_product_id']     = $product_id;
                $cart_meta['product_name']          = esc_html__('Hourly project hiring','workreap-hourly-addon');
                $cart_meta['price']                 = $price;
                $cart_meta['payment_type']          = 'hourly';
                $cart_meta['project_type']          = $project_type;
                $cart_meta['payment_mode']          = $payment_mode;
                $cart_meta['interval_name']         = $interval_name;
                $cart_meta['transaction_id']        = $transaction_id;
                $cart_meta['hourly_rate']           = $hourly_rate;
                $cart_meta['max_hours']             = $max_hours;
                $cart_meta['employer_id']		        = $user_id;
                $cart_meta['freelancer_id']		        = $freelancer_id;
                $cart_meta['project_id']		    = $project_id;
                $cart_meta['proposal_id']		    = $proposal_id;
                $cart_meta['proposal_meta']		    = $proposal_meta;
                $cart_meta['time_slots']            = $time_slots_array;
                $cart_meta['processing_fee']	    = !empty( $employer_service_fee['commission_amount'] ) ? $employer_service_fee['commission_amount'] : 0.0;

                $cart_data  = array(
                    'hiring_product_id'     => $product_id,
                    'cart_data'             => $cart_meta,
                    'project_type'          => $project_type,
                    'price'                 => $price,
                    'payment_type'          => 'hourly',
                    'transaction_id'        => $transaction_id,
                    'project_id'            => $project_id,
                    'proposal_id'           => $proposal_id,
                    'employer_id'              => $user_id,
                    'freelancer_id'             => $freelancer_id,
                );

                $woocommerce->cart->empty_cart();
                $cart_item_data = $cart_data;
                WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

                if( !empty($wallet) && !empty($user_balance) && $user_balance >= $price ){
                    $order_id               = workreap_place_order($user_id,'hourly-wallet');
                    $json['checkout_url']	= Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_id, true,'activity',$proposal_id).'&transaction_id='.intval($transaction_id);
                    $json['order_id']       = $order_id;
                    $json['type']           = 'success';
	                $json['message']    = esc_html__('Woohoo!', 'workreap');
                    $json['message_desc']   = esc_html__('You have successfully completed this order','workreap-hourly-addon');
                    if( empty($type) ){
                        wp_send_json( $json );
                    }
                } else if( !empty($option_type) && $option_type === 'mobile'){
                    $linked_profile_id  = workreap_get_linked_profile_id($user_id);
                    if( !empty($linked_profile_id) && !empty($cart_data) ){
                        update_post_meta( $linked_profile_id, 'mobile_checkout_data',$cart_data );
                        $mobile_checkout    = workreap_get_page_uri('mobile_checkout');
                        if(!empty($mobile_checkout) ){
                            $json['message_desc']   = esc_html__('You have successfully completed this order','workreap-hourly-addon');
                            $json['checkout_url']	= $mobile_checkout.'?post_id='.$linked_profile_id;
                        }
                    } 
                } else{
                    $json['message_desc']       = esc_html__('You have successfully redirect to checkout page','workreap-hourly-addon');
                    $json['checkout_url']       = wc_get_checkout_url();
                    $json['type']               = 'success';
                    if( empty($type) ){
                        wp_send_json( $json );
                    }
                }


            }
        }

        if( empty($option_type) ){
            wp_send_json($json);
        } else {
            return $json;
        }


    }
}

/**
 * Hire project option
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_hiring_options')) {
    function workreap_project_hiring_options($user_id,$proposal_id,$wallet,$transaction_id,$option_type) {
        if ( class_exists('WooCommerce') ) {
            global $current_user,$woocommerce,$workreap_settings;
            $admin_commision_employers     =  !empty($workreap_settings['admin_commision_employers']) ? $workreap_settings['admin_commision_employers'] : 0;
            $commission_text            =  !empty($workreap_settings['commission_text']) ? $workreap_settings['commission_text'] : esc_html__('Processing fee', 'workreap-hourly-addon');
  
            
            $project_id     = get_post_meta( $proposal_id, 'project_id',true );
            $project_id     = !empty($project_id) ? intval($project_id) : 0;
            $project_meta   = get_post_meta( $project_id, 'wr_project_meta',true );
            $project_meta   = !empty($project_meta) ? $project_meta : array();
            $project_type   = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';

            if( !empty($project_type) ){
                $proposal_meta  = get_post_meta( $proposal_id, 'proposal_meta',true );
                $proposal_meta  = !empty($proposal_meta) ? $proposal_meta : array();

                $time_slots     = get_post_meta( $proposal_id, 'wr_timetracking',true );
                $time_slots     = !empty($time_slots) ? $time_slots : array();
                $time_slots     = !empty($time_slots[$transaction_id]) ? $time_slots[$transaction_id] : array();

                $total_time     = !empty($time_slots['total_time']) ? $time_slots['total_time'] : 0;
                $hourly_rate    = isset($proposal_meta['price']) && $proposal_meta['price'] > 0 ? $proposal_meta['price'] : 0;

                $payment_mode   = get_post_meta( $project_id, 'payment_mode', true );
                $interval_name  = "";

                if( !empty($payment_mode) && $payment_mode === 'weekly' ){
                    $interval_name		    = workreap_get_hourly_week_intervals($transaction_id);
                } else if( !empty($payment_mode) && $payment_mode === 'daily' ){
                    $interval_name		    = date_i18n(get_option('date_format'),$transaction_id );
                } else if( !empty($payment_mode) && $payment_mode === 'monthly' ){
                    $interval_name		    = date_i18n('F Y',$transaction_id );
                }
                
                $price          = isset($total_time) && isset($hourly_rate) ? ($total_time*$hourly_rate) : 0;
                $cart_meta      = array();

                global $woocommerce;
                $woocommerce->cart->empty_cart();
                if( !empty($option_type) && $option_type === 'mobile' ){
                    check_prerequisites($user_id);
                }
                
                $service_fee    = workreap_commission_fee($price);
                $admin_shares   = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
                $freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $price;
                $freelancer_id      = get_post_field( 'post_author', $proposal_id );
                $user_balance   = !empty($user_id) ? get_user_meta( $user_id, '_employer_balance',true ) : '';
                $product_id     = workreap_employer_wallet_create();

                if( !empty($wallet) && !empty($user_balance) && $user_balance < $price ){
                    $cart_meta['wallet_price']		    = $user_balance;
                }

                if(function_exists('workreap_processing_fee_calculation')){
                    $employer_service_fee		= workreap_processing_fee_calculation('projects',$price);
                }
                

                $cart_meta['hiring_product_id']     = $product_id;
                $cart_meta['product_name']          = esc_html__('Hourly project hiring','workreap-hourly-addon');
                $cart_meta['price']                 = $price;
                $cart_meta['payment_type']          = 'hourly';
                $cart_meta['project_type']          = $project_type;
                $cart_meta['payment_mode']          = $payment_mode;
                $cart_meta['interval_name']         = $interval_name;
                $cart_meta['transaction_id']        = $transaction_id;
                $cart_meta['hourly_rate']           = $hourly_rate;
                $cart_meta['time_slots']            = $time_slots;
                $cart_meta['employer_id']		        = $user_id;
                $cart_meta['admin_shares']		    = $admin_shares;
                $cart_meta['freelancer_shares']		    = $freelancer_shares;
                $cart_meta['project_id']		    = $project_id;
                $cart_meta['proposal_id']		    = $proposal_id;
                $cart_meta['proposal_meta']		    = $proposal_meta;
                $cart_meta['processing_fee']	    = !empty( $employer_service_fee['commission_amount'] ) ? $employer_service_fee['commission_amount'] : 0.0;

                $cart_data  = array(
                    'hiring_product_id'     => $product_id,
                    'cart_data'             => $cart_meta,
                    'project_type'          => $project_type,
                    'price'                 => $price,
                    'payment_type'          => 'hourly',
                    'admin_shares'          => $admin_shares,
                    'transaction_id'        => $transaction_id,
                    'freelancer_shares'         => $freelancer_shares,
                    'project_id'            => $project_id,
                    'proposal_id'           => $proposal_id,
                    'employer_id'              => $user_id
                );
                
                $woocommerce->cart->empty_cart();
                $cart_item_data = $cart_data;
                WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

                if( !empty($wallet) && !empty($user_balance) && $user_balance >= $price ){
                    $order_id               = workreap_place_order($user_id,'hourly-wallet');
                    $json['checkout_url']	= Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_id, true,'activity',$proposal_id).'&transaction_id='.intval($transaction_id);
                    $json['order_id']       = $order_id;
                    $json['type']           = 'success';
	                $json['message']    = esc_html__('Woohoo!', 'workreap');
                    $json['message_desc']   = esc_html__('You have successfully completed this order','workreap-hourly-addon');
                    if( empty($type) ){
                        wp_send_json( $json );
                    }
                } else if( !empty($option_type) && $option_type === 'mobile'){
                    $linked_profile_id  = workreap_get_linked_profile_id($user_id);
                    if( !empty($linked_profile_id) && !empty($cart_data) ){
                        update_post_meta( $linked_profile_id, 'mobile_checkout_data',$cart_data );
                        $mobile_checkout    = workreap_get_page_uri('mobile_checkout');
                        if(!empty($mobile_checkout) ){
                            $json['message_desc']   = esc_html_e('You have successfully completed this order','workreap-hourly-addon');
                            $json['checkout_url']	= $mobile_checkout.'?post_id='.$linked_profile_id;
                        }
                    } 
                } else{
                    $json['checkout_url']       = wc_get_checkout_url();
                    $json['type']               = 'success';
                    if( empty($type) ){
                        wp_send_json( $json );
                    }
                }
            }
        }
    }
    add_action('workreap_project_hiring_options', 'workreap_project_hiring_options',10,5);
}

/**
 * After order complete
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_update_woocommerce_order_data')){
	function workreap_update_woocommerce_order_data($order_id,$order_detail,$user) {
        if ( class_exists('WooCommerce') ) {
            global $workreap_settings;

            if( !empty($order_detail['payment_type']) && $order_detail['payment_type'] == 'hourly' ){
                $gmt_time		= current_time( 'mysql', 1 );
                $employer_id		= !empty($order_detail['employer_id']) ? $order_detail['employer_id'] : 0;
                $proposal_id	= !empty($order_detail['proposal_id']) ? $order_detail['proposal_id'] : 0;
                $project_id		= !empty($order_detail['project_id']) ? $order_detail['project_id'] : 0;
                $interval_name	= !empty($order_detail['interval_name']) ? $order_detail['interval_name'] : '';
                $project_type	= !empty($order_detail['project_type']) ? $order_detail['project_type'] : 0;
                $wallet_price	= !empty($order_detail['wallet_price']) ? $order_detail['wallet_price'] : 0;
                $transaction_id	= !empty($order_detail['transaction_id']) ? $order_detail['transaction_id'] : 0;
                $payment_mode	= !empty($order_detail['cart_data']['payment_mode']) ? $order_detail['cart_data']['payment_mode'] : 0;
                $hourly_rate	= !empty($order_detail['hourly_rate']) ? $order_detail['hourly_rate'] : 0;
                $order_amount	= !empty($order_detail['price']) ? $order_detail['price'] : 0;

                if( !empty($wallet_price) ){
                    $user_balance   = !empty($employer_id) ? get_user_meta( $employer_id, '_employer_balance',true ) : '';
                    $user_balance   = !empty($user_balance) ? ($user_balance-$wallet_price) : 0;
        
                    update_user_meta( $employer_id,'_employer_balance',$user_balance);
                    update_post_meta( $order_id, '_wallet_amount', $wallet_price );
                    update_post_meta( $order_id, '_task_type', 'wallet' );
                }
        
                update_post_meta( $order_id, 'employer_id', $employer_id );
                update_post_meta( $order_id, 'proposal_id', $proposal_id );
                update_post_meta( $order_id, 'project_id', $project_id );
                update_post_meta( $order_id, 'project_type', $project_type );
                update_post_meta( $order_id, 'payment_mode', $payment_mode );
                update_post_meta( $order_id, 'hourly_rate', $hourly_rate );
                update_post_meta( $order_id, '_post_project_status', 'pending' );
                update_post_meta( $order_id, '_task_status', 'pending' );
                
                $time_slots     = get_post_meta( $proposal_id, 'wr_timetracking',true );
                $time_slots     = !empty($time_slots) ? $time_slots : array();
                $time_slots[$transaction_id]['status']      = 'draft';
                $time_slots[$transaction_id]['order_id']    = $order_id;
                update_post_meta( $order_id, 'transaction_id', $transaction_id );
                update_post_meta( $proposal_id, 'wr_timetracking', $time_slots );
                
                // Hire a project
                $gmt_time		                    = current_time( 'mysql', 1 );
                $employer_id                           = get_post_field( 'post_author', $project_id );
                $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
                $freelancer_id                          = get_post_field( 'post_author', $proposal_id );
                $freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
                $notifyDetails                      = array();
                $notifyDetails['employer_id']  	    = $employer_profile_id;
                $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
                $notifyDetails['project_id']  	    = $project_id;
                $notifyDetails['proposal_id']  	    = $proposal_id;
                $notifyDetails['interval_name']  	= $interval_name;
                $notifyDetails['transaction_id']  	= $transaction_id;
	            $notifyDetails['employer_name']     = workreap_get_username($employer_profile_id);
                $notifyData['receiver_id']		    = $freelancer_id;
                $notifyData['linked_profile']	    = $freelancer_profile_id;
                $notifyData['user_type']		    = 'freelancers';
                $proposal_status	                = get_post_status( $proposal_id );

                if( !empty($proposal_id) && $proposal_status != 'hired'){
                    $proposal_post = array(
                        'ID'           	=> $proposal_id,
                        'post_status'   => 'hired'
                    );

                    wp_update_post($proposal_post );
                    update_post_meta($proposal_id, 'hiring_date',$gmt_time );
                    update_post_meta($proposal_id, '_hired_status',true );
                    update_post_meta($proposal_id,'hiring_date_gmt',strtotime($gmt_time));
                    workreapUpdateProjectStatusOption($project_id,'hired');
                    $notifyData['type']		            = 'hired_proposal';
                    $notifyData['post_data']		    = $notifyDetails;
                    do_action('workreap_notification_message', $notifyData );

                    /* Email to freelancer */
                    $proposal_hired_switch        = !empty($workreap_settings['email_proposal_hired_freelancer']) ? $workreap_settings['email_proposal_hired_freelancer'] : true;
                    if(class_exists('Workreap_Email_helper') && !empty($proposal_hired_switch)){
                        $emailData                      = array();
                        $emailData['freelancer_email']      = get_userdata($freelancer_id)->user_email;
                        $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                        $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                        $emailData['project_title']     = get_the_title($project_id);
                        $emailData['project_link']     = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'activity',$proposal_id);
                        if (class_exists('WorkreapProposals')) {
                            $email_helper = new WorkreapProposals();
                            $email_helper->hired_proposal_freelancer_email($emailData);
                        }
                    }
                }

                $notifyData['type']		            = 'unlock_hours';
                $notifyData['post_data']		    = $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
                // Email for unblock hours

            }
        }
	}
    add_action('workreap_update_woocommerce_order_data', 'workreap_update_woocommerce_order_data',10,3);
}


/**
 * change price on cart
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_add_workreap_woo_convert_item_session_to_order_meta')){
	function workreap_add_workreap_woo_convert_item_session_to_order_meta($item_id, $item, $order_id) {
        if ( class_exists('WooCommerce') ) {
            $payment_type	= !empty($item->legacy_values['payment_type']) ? $item->legacy_values['payment_type'] : '';
            if( !empty($payment_type) && $payment_type === 'hourly' ){

                if ( !empty( $item->legacy_values['cart_data'] ) ) {
                    wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
                    update_post_meta( $order_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
                }
    
                if ( !empty( $item->legacy_values['project_id'] ) ) {
                    update_post_meta( $order_id, 'project_id', $item->legacy_values['project_id'] );
                }
                if ( !empty( $item->legacy_values['proposal_id'] ) ) {
                    update_post_meta( $order_id, 'proposal_id', $item->legacy_values['proposal_id'] );
                }
    
                if ( !empty( $item->legacy_values['payment_type'] ) ) {
                    update_post_meta( $order_id, 'payment_type', $item->legacy_values['payment_type'] );
                }
                if ( !empty( $item->legacy_values['payment_type'] ) ) {
                    update_post_meta( $order_id, 'payment_type', $item->legacy_values['payment_type'] );
                }
    
            }
        }
	}
    add_action('workreap_add_workreap_woo_convert_item_session_to_order_meta', 'workreap_add_workreap_woo_convert_item_session_to_order_meta',10,3);
}

/**
 * Custome order update
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_after_complete_proposal')){
	function workreap_after_complete_proposal($post_id=0,$type='') {
        $remaining_amount   = get_post_meta( $post_id,'remaining_amount', true );
        $user_id            = get_post_meta( $post_id,'employer_id', true );
        if( isset($remaining_amount) && $remaining_amount > 0 && !empty($user_id) ){
            global $woocommerce;
            if( !empty($type) && $type === 'mobile' ){
                check_prerequisites($user_id);
            }

            $project_id                 = get_post_meta( $post_id,'project_id', true );
            $project_id                 = !empty($project_id) ? intval($project_id) : 0;
            $product_id                 = workreap_employer_wallet_create();
            $cart_meta                  = array();
            $cart_meta['wallet_id']     = $product_id;
            $cart_meta['product_name']  = get_the_title($product_id);
            $cart_meta['price']         = $remaining_amount;
            $cart_meta['project_id']    = $project_id;
            $cart_meta['proposal_id']   = $post_id;
            $cart_meta['payment_type']  = 'wallet';
            $cart_data  = array(
                'wallet_id' 	=> $product_id,
                'cart_data' 	=> $cart_meta,
                'price'     	=> $remaining_amount,
                'payment_type'  => 'wallet'
            );
            
            $woocommerce->cart->empty_cart();
            $cart_item_data = $cart_data;
            WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
            workreap_place_order($user_id,'employer-wallet');
        }
	}
    add_action('workreap_after_complete_proposal', 'workreap_after_complete_proposal',10,2);
}