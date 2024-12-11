<?php

/**
 * List project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_type')){
	function workreap_hourly_project_type($lists=array()) {
		
		$lists['hourly']  = array(
                    'title'     => esc_html__('Hourly','workreap-hourly-addon'),
                    'details'   => esc_html__('Pay each freelancer on hourly rate','workreap-hourly-addon'),
                    'icon'      => 'wr-icon-file-text wr-purple-icon'
            );
        return $lists;
	}
    add_filter( 'workreap_filter_project_type', 'workreap_hourly_project_type' );
}

/**
 * Requried field for job creation
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_validation_step2')){
	function workreap_hourly_project_validation_step2($required_fields=array(),$data=array()) {
        $project_type   = !empty($data['project_type']) ? $data['project_type'] : '';
        if( !empty($project_type) && $project_type === 'hourly'){
            $required_fields['min_hourly_price']    = esc_html__( 'Minimum hourly price is required', 'workreap-hourly-addon' );
            $required_fields['max_hourly_price']    = esc_html__( 'Maximum hourly price is required', 'workreap-hourly-addon' );
            $required_fields['payment_mode']        = esc_html__( 'Please select payment mode', 'workreap-hourly-addon' );
            $required_fields['max_hours']           = esc_html__( 'Maximum hours field is required', 'workreap-hourly-addon' );
        }
        return $required_fields;
	}
    add_filter( 'workreap_project_validation_step2', 'workreap_hourly_project_validation_step2',10,2 );
}

/**
 * Save hourly project step 2
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_save_project_step2')){
	function workreap_save_project_step2($project_id=0,$data=array()) {
        if( !empty($project_id) && !empty($data['project_type']) && $data['project_type'] === 'hourly' ){
            $min_price   = !empty($data['min_hourly_price']) ? sanitize_text_field($data['min_hourly_price']) : 0;
            $max_price   = !empty($data['max_hourly_price']) ? sanitize_text_field($data['max_hourly_price']) : 0;
            $max_hours          = !empty($data['max_hours']) ? sanitize_text_field($data['max_hours']) : 0;
            $payment_mode       = !empty($data['payment_mode']) ? sanitize_text_field($data['payment_mode']) : 0;

            $project_meta       = get_post_meta( $project_id, 'wr_project_meta',true );
            $project_meta       = !empty($project_meta) ? $project_meta : array();

            $project_meta['max_hours']              = $max_hours;
            $project_meta['payment_mode']           = $payment_mode;
            $project_meta['max_price']       = $max_price;
            $project_meta['min_price']       = $min_price;
            
            update_post_meta( $project_id, 'payment_mode',$payment_mode );
            update_post_meta( $project_id, 'max_hours',$max_hours );

            update_post_meta( $project_id, 'wr_project_meta',$project_meta );
            update_post_meta( $project_id, 'min_price',$min_price );
            update_post_meta( $project_id, 'max_price',$max_price );
        }
	}
    add_action('workreap_save_project_step2', 'workreap_save_project_step2',10,2);
}

/**
 * Horly price text
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_type_text')){
	function workreap_hourly_project_type_text($project_type='') {
		$text   = '';
		if( !empty($project_type) && $project_type === 'hourly'){
            $text   =  esc_html__('Hourly price project','workreap-hourly-addon');
        }
        return $text;
	}
    add_filter( 'workreap_filter_project_type_text', 'workreap_hourly_project_type_text' );
}

/**
 * Horly price class
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_type_class')){
	function workreap_hourly_project_type_class($project_type='') {
		$class  = '';
		if( !empty($project_type) && $project_type === 'hourly'){
            $class  = 'wr-success-tag';
        }
        return $class;
	}
    add_filter( 'workreap_filter_project_type_class', 'workreap_hourly_project_type_class' );
}

/**
 * duplicate project keys
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_filter_duplicat_project_keys')){
	function workreap_filter_duplicat_project_keys($project_id=0,$data=array()) {
        $project_type   = get_post_meta( $post_id, 'project_type',true );
        $project_type   = !empty($project_type) ? $project_type : '';

        if( !empty($project_type) && $project_type === 'hourly'){
            $data['min_price'];
            $data['max_price'];
            $data['payment_mode'];
            $data['max_hours'];
            $data['max_hours'];
        }

        return $data;
	}
    add_filter( 'workreap_duplicate_job_key_filter', 'workreap_filter_duplicat_project_keys',10,2 );
}

/**
 * project price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_project_price_text_fitler')){
	function workreap_project_price_text_fitler($post_id=0) {
        $project_meta       = get_post_meta( $post_id, 'wr_project_meta', true);
        $project_type       = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
        
        if( !empty($project_type) && $project_type === 'hourly'){
            $min_price      = !empty($project_meta['min_price']) ? $project_meta['min_price'] : 0;
            $max_price      = !empty($project_meta['max_price']) ? $project_meta['max_price'] : 0;
            $project_price  = workreap_price_format($min_price,'return').'-'.workreap_price_format($max_price,'return');
            $project_price  = sprintf(__('%s/hr','workreap-hourly-addon'),$project_price);
            return $project_price;
        }
        
	}
    add_filter( 'workreap_project_price_text', 'workreap_project_price_text_fitler');
}

/**
 * Proposal price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_proposal_listing_price')){
	function workreap_proposal_listing_price($proposal_id=0) {
        $proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
        $proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array();
        $price              = isset($proposal_meta['price']) ? workreap_price_format($proposal_meta['price'],'return') : 0;

        ob_start();
        echo sprintf(__('%s/hr','workreap-hourly-addon'),$price);
        echo ob_get_clean();
	}
    add_action('workreap_proposal_listing_price', 'workreap_proposal_listing_price');
}

/**
 * Get time difference
 *
 * @throws error
 * @return 
 */
if ( ! function_exists( 'workreap_cal_hours' ) ) {
    function workreap_cal_hours( $start_hours='',$end_hours='') {
		$starttime 		= strtotime($start_hours);
		$endtime 		= strtotime($end_hours);
		$difference = round(abs($endtime - $starttime) / 3600,2);
		return $difference;
	}
}

/**
 * Get Difference between dates
 *
 * @throws error
 * @return 
 */
if (!function_exists('workreap_get_diff_dates')) {
	function workreap_get_diff_dates($start,$end) {
		$diff = strtotime($end) - strtotime($start); 
		return abs(round($diff / 86400)); 
	}
}

/**
 * Week range
 *
 * @throws error
 * @return 
 */
if (!function_exists('workreap_get_weekrang')) {
	function workreap_get_weekrang ($date_inweek,$days=6) {
		$return_array	= array();
		date_default_timezone_set (date_default_timezone_get());
		$week_start     = get_option( 'start_of_week');
		$week_slug		= workreap_get_weekarray($week_start);
		$week_slug		= !empty($week_slug) ? $week_slug : '';
		$dt 			                = strtotime($date_inweek);
		$return_array['start_time']		= date('N', $dt) == $week_start ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last '.$week_slug, $dt));
        //$return_array['start_time']		= date ('Y-m-d', $dt);
		$end_week 		                = strtotime($return_array['start_time']."+".$days." day");
		$return_array['end_time']		=  date ('Y-m-d', $end_week);
		return $return_array;
	  }
}

/**
 * Week array
 *
 * @throws error
 * @return 
 */
if (!function_exists('workreap_get_weekarray')) {
	function workreap_get_weekarray ($key='0',$option='slug') {
		$week_days	= array(
			'0'	=> array('lable' => esc_html__('Sun','workreap-hourly-addon'),'slug'	=> 'sunday'),
			'1'	=> array('lable' => esc_html__('Mon','workreap-hourly-addon'),'slug'	=> 'monday'),
			'2'	=> array('lable' => esc_html__('Tue','workreap-hourly-addon'),'slug'	=> 'tuesday'),
			'3'	=> array('lable' => esc_html__('Wed','workreap-hourly-addon'),'slug'	=> 'wednesday'),
			'4'	=> array('lable' => esc_html__('Thurs','workreap-hourly-addon'),'slug'=> 'thursday'),
			'5'	=> array('lable' => esc_html__('Fri','workreap-hourly-addon'),'slug'	=> 'friday'),
			'6'	=> array('lable' => esc_html__('Sat','workreap-hourly-addon'),'slug'	=> 'saturday')
		);

		if( isset($key) && $key != ''){
			$week_days	= !empty($week_days[$key][$option]) ? $week_days[$key][$option] : '';
		} else{
		
			$set_key	= get_option( 'start_of_week');
			if( $set_key != 0 ){
				$array1 	= array_slice($week_days, $set_key, 6);
				$array2 	= array_slice($week_days, 0, $set_key);
				$week_days	= array_merge($array1,$array2);
			}
		}
		return $week_days;
	  }
}

if( !function_exists('workreap_date_range')){
    function workreap_date_range($start=0, $end=0, $step = '+1 day', $output_format = 'Y-m-d' ) {

        $dates      = array();
        $current    = !empty($start) ? strtotime($start) : 0;
        $end        = !empty($end) ? strtotime($end) : 0;

        while( $current <= $end ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }
}
/**
 * Payment mode
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_payment_mode')){
	function workreap_payment_mode($field='',$type='') {
		$lists  = array(
            'daily'     =>  array( 
                'title'     => esc_html__('Daily','workreap-hourly-addon'),
                'name'      => esc_html__('Day','workreap-hourly-addon'),
                'key'       => 'day',
            ),
            'weekly'    =>  array( 
                'title'     => esc_html__('Weekly','workreap-hourly-addon'),
                'name'      => esc_html__('Week','workreap-hourly-addon'),
                'key'       => 'week',
            ),
            'monthly'   =>  array( 
                'title' => esc_html__('Monthly','workreap-hourly-addon'),
                'name'  => esc_html__('Month','workreap-hourly-addon'),
                'key'       => 'month',
            ),
        );

		$lists  = apply_filters('workreap_filter_payment_mode', $lists);
        if( !empty($field) ){
            $updated_array  = array();
            foreach($lists as $key => $val ){
                $updated_array[$key]    = !empty($val[$field]) ?$val[$field] : '';
            }
            $lists  = $updated_array;
        }
        if( !empty($type) ){
           
           $lists   = !empty($lists[$type]) ? $lists[$type] : '';
        }
        
        return $lists;
	}
}


/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_notification')){
	function workreap_hourly_project_notification($data=array()) {
        $data['hours_submiation']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'freelancer_image',
                'tage'          => array('freelancer_name','employer_name','project_title','project_link','project_id','proposal_id','employer_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'employer_proposal_timeslot_activity', 'text'=> esc_html__('View activity','workreap-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to employer after hours submitation','workreap-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','workreap-hourly-addon'),
                'content_title'         => esc_html__('Notification content','workreap-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to freelancer after hours submitation','workreap-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','workreap-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{freelancer_name}}</strong> send you <strong>{{total_hours}}</strong> hours for the project <strong>{{project_title}}</strong>','workreap-hourly-addon'),
                'tags'                  => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>
                                            {{total_hours}}         — To display the no of hours.<br>'),
            ),
        );
        $data['hours_decline']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'employer_image',
                'tage'          => array('freelancer_name','employer_name','project_title','project_link','project_id','proposal_id','freelancer_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'freelancer_proposal_timeslot_activity', 'text'=> esc_html__('View activity','workreap-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to freelancer after hours decline','workreap-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','workreap-hourly-addon'),
                'content_title'         => esc_html__('Notification content','workreap-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to freelancer after hours decline','workreap-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','workreap-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{employer_name}}</strong> decline time for the project <strong>{{project_title}}</strong>','workreap-hourly-addon'),
                'tags'                  => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>'),
            ),
        );
        $data['hours_approved']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'employer_image',
                'tage'          => array('freelancer_name','employer_name','project_title','project_link','project_id','proposal_id','freelancer_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'freelancer_proposal_timeslot_activity', 'text'=> esc_html__('View activity','workreap-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to freelancer after hours approved','workreap-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','workreap-hourly-addon'),
                'content_title'         => esc_html__('Notification content','workreap-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to freelancer after hours approved','workreap-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','workreap-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{employer_name}}</strong> approved your time cart for the project <strong>{{project_title}}</strong>','workreap-hourly-addon'),
                'tags'                  => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>
                                            {{total_hours}}         — To display the approved time.<br>'),
            ),
        );
        $data['unlock_hours']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'employer_image',
                'tage'          => array('freelancer_name','employer_name','project_title','project_link','project_id','proposal_id','freelancer_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'freelancer_proposal_timeslot_activity', 'text'=> esc_html__('View activity','workreap-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to freelancer after unlock hours','workreap-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','workreap-hourly-addon'),
                'content_title'         => esc_html__('Notification content','workreap-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to freelancer after unlock hours','workreap-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','workreap-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{employer_name}}</strong> unlock your time cart for the project <strong>{{project_title}}</strong>','workreap-hourly-addon'),
                'tags'                  => __('
                                            {{employer_name}}          — To display the employer name.<br>
                                            {{freelancer_name}}         — To display the employer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>
                                            {{total_hours}}         — To display the approved time.<br>'),
            ),
        );
        return $data;
	}
    add_filter( 'workreap_filter_list_notification', 'workreap_hourly_project_notification');
}


/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_params')){
	function workreap_hourly_project_params($param_value,$post_id,$param) {
        $post_data		= get_post_meta( $post_id, 'post_data', true );
		$post_data		= !empty($post_data) ? $post_data : array();
		switch ($param) {
            case "total_hours":
				$param_value	= !empty($post_data['total_hours']) ? esc_html($post_data['total_hours']) : '';
			break;
            case "interval_name":
				$param_value	= !empty($post_data['interval_name']) ? esc_html($post_data['interval_name']) : '';
			break;
            case "decline_detail":
				$param_value	= !empty($post_data['decline_detail']) ? esc_html($post_data['decline_detail']) : '';
			break;
        }
        return $param_value;
	}
    add_filter( 'workreap_filter_notification_replaceparams', 'workreap_hourly_project_params',10,3);
}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_notification_button')){
	function workreap_hourly_project_notification_button($button_html,$post_id,$settings,$show_option) {
        $btn_settings			= !empty($settings['btn_settings']) ? $settings['btn_settings'] : array();
		$link_class				= !empty($show_option) && $show_option === 'listing' ? 'wr-btn-solid' : '';
        if( !empty($btn_settings) ){
			$link_type	= !empty($btn_settings['link_type']) ? $btn_settings['link_type'] : '';
			$btn_link	= '';
			$post_data	= get_post_meta( $post_id, 'post_data', true);
			$post_data	= !empty($post_data) ? $post_data : array();
            
			if( !empty($link_type) && $link_type === 'employer_proposal_timeslot_activity' ){
                $receiver_id	= !empty($post_data['employer_id']) ? get_post_field( 'post_author', $post_data['employer_id'] ) : 0;
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
                $transaction_id	= !empty($post_data['transaction_id']) ? $post_data['transaction_id'] : 0;
				$btn_link		= !empty($receiver_id) && !empty($proposal_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $receiver_id, true, 'activity',$proposal_id).'&transaction_id='.$transaction_id : "";
                $button_html	= !empty($btn_settings['text']) ? '<a class="'.esc_attr($link_class).'" href="'.esc_url($btn_link).'">'.esc_html($btn_settings['text']).'</a>' : '';
            } else if( !empty($link_type) && $link_type === 'freelancer_proposal_timeslot_activity' ){
                $receiver_id	= !empty($post_data['freelancer_id']) ? get_post_field( 'post_author', $post_data['freelancer_id'] ) : 0;
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
                $transaction_id	= !empty($post_data['transaction_id']) ? $post_data['transaction_id'] : 0;
				$btn_link		= !empty($receiver_id) && !empty($proposal_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $receiver_id, true, 'activity',$proposal_id).'&transaction_id='.$transaction_id : "";
                $button_html	= !empty($btn_settings['text']) ? '<a class="'.esc_attr($link_class).'" href="'.esc_url($btn_link).'">'.esc_html($btn_settings['text']).'</a>' : '';
            }
        }
        return $button_html;
	}
    add_filter( 'workreap_filter_notification_button', 'workreap_hourly_project_notification_button',10,4);
}

/**
 * Add email for employer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_employer_email')){
	function workreap_hourly_project_employer_email($employer_email) {
        $new_array  = array(
            /* Email to employer on hourly request from freelancer */
            array(
                'id'      => 'divider_hourly_request_send_employer_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Hourly project request', 'workreap-hourly-addon' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'hourly_request_send_employer_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'workreap-hourly-addon'),
                'subtitle' => esc_html__('Email to employer on hourly requst.', 'workreap-hourly-addon'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'hourly_request_send_employer_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'workreap-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'workreap-hourly-addon' ),
                'default' 	=> esc_html__( 'Hourly request on project', 'workreap-hourly-addon'),
                'required'  => array('hourly_request_send_employer_email_switch','equals','1')
            ),
            array(
                'id'      => 'hourly_request_send_employer_email_information',
                'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>
                            {{freelancer_name}} — To display the freelancer name.<br>
                            {{project_title}} — To display the project title.<br>
                            {{project_link}} — To display the project link.<br>'
                            , 'workreap-hourly-addon' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'workreap-hourly-addon' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('hourly_request_send_employer_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'hourly_request_send_employer_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'workreap-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add text.', 'workreap-hourly-addon' ),
                'default' 	=> esc_html__( 'Hello {{employer_name}},', 'workreap-hourly-addon'),
                'required'  => array('hourly_request_send_employer_email_switch','equals','1')
            ),
            array(
                'id'        => 'hourly_request_send_employer_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( '{{freelancer_name}} send you a hourly project request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'workreap-hourly-addon'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'workreap-hourly-addon' ),
                'required'  => array('hourly_request_send_employer_email_switch','equals','1')
            ),
        );
        
        return array_merge($employer_email,$new_array);
	}
    add_filter( 'workreap_filter_employer_email_fields', 'workreap_hourly_project_employer_email');
}

/**
 * Add email for freelancer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_project_freelancer_email')){
	function workreap_hourly_project_freelancer_email($freelancer_email) {
        $new_freelancer_array  = array(
            /* Hourly request approved from employer */
            array(
                'id'      => 'divider_hourly_request_approve_freelancer_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Hourly request approved', 'workreap-hourly-addon' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'hourly_request_approve_freelancer_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'workreap-hourly-addon'),
                'subtitle' => esc_html__('Email to freelancer on hourly request approved.', 'workreap-hourly-addon'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'hourly_request_approve_freelancer_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'workreap-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'workreap-hourly-addon' ),
                'default' 	=> esc_html__( 'Project hourly request approved', 'workreap-hourly-addon'),
                'required'  => array('hourly_request_approve_freelancer_email_switch','equals','1')
            ),
            array(
                'id'      => 'divider_hourly_request_approve_freelancer_email_information',
                'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>
                            {{freelancer_name}} — To display the freelancer name.<br>
                            {{project_title}} — To display the project title.<br>
                            {{project_link}} — To display the project link.<br>'
                            , 'workreap-hourly-addon' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'workreap-hourly-addon' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('hourly_request_approve_freelancer_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'hourly_request_approve_freelancer_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'workreap-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add text.', 'workreap-hourly-addon' ),
                'default' 	=> esc_html__( 'Hello {{freelancer_name}},', 'workreap-hourly-addon'),
                'required'  => array('hourly_request_approve_freelancer_email_switch','equals','1')
            ),
            array(
                'id'        => 'hourly_request_approve_freelancer_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( 'Congratulation! {{employer_name}} have approve your project hourly request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'workreap-hourly-addon'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'workreap-hourly-addon' ),
                'required'  => array('hourly_request_approve_freelancer_email_switch','equals','1')
            ),

            /* Hourly request decline from employer */
            array(
                'id'      => 'divider_hourly_request_decline_freelancer_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Hourly request declined', 'workreap-hourly-addon' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'hourly_request_decline_freelancer_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'workreap-hourly-addon'),
                'subtitle' => esc_html__('Email to freelancer on hourly request declined.', 'workreap-hourly-addon'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'hourly_request_decline_freelancer_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'workreap-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'workreap-hourly-addon' ),
                'default' 	=> esc_html__( 'Project hourly request declined', 'workreap-hourly-addon'),
                'required'  => array('hourly_request_decline_freelancer_email_switch','equals','1')
            ),
            array(
                'id'      => 'divider_hourly_request_decline_freelancer_email_information',
                'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>
                            {{freelancer_name}} — To display the freelancer name.<br>
                            {{project_title}} — To display the project title.<br>
                            {{project_link}} — To display the project link.<br>
                            {{decline_detail}} — To display the decline detail.<br>'
                            , 'workreap-hourly-addon' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'workreap-hourly-addon' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('hourly_request_decline_freelancer_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'hourly_request_decline_freelancer_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'workreap-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add text.', 'workreap-hourly-addon' ),
                'default' 	=> esc_html__( 'Hello {{freelancer_name}},', 'workreap-hourly-addon'),
                'required'  => array('hourly_request_decline_freelancer_email_switch','equals','1')
            ),
            array(
                'id'        => 'hourly_request_decline_freelancer_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( 'Oho! A project hourly request has been declined by {{employer_name}} with the reason of <br/> {{decline_detail}} <br />Please click on the button below to view the decline details.<br />{{project_link}}', 'workreap-hourly-addon'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'workreap-hourly-addon' ),
                'required'  => array('hourly_request_decline_freelancer_email_switch','equals','1')
            ),

        );
        return array_merge($freelancer_email,$new_freelancer_array);
    }
    add_filter( 'workreap_filter_freelancer_email_fields', 'workreap_hourly_project_freelancer_email');
}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_filter_invoice_title')){
	function workreap_filter_invoice_title($invoice_id) {
        $invoice_title  = '';
		$order_data     = get_post_meta( $invoice_id, 'cus_woo_product_data',true );
        if( !empty($order_data['project_type']) && $order_data['project_type'] === 'hourly' ){
            $project_id     = !empty($order_data['project_id']) ? $order_data['project_id'] : '';
            $project_title  = !empty($project_id) ? get_the_title( $project_id ) : '';
            $invoice_title  = !empty($order_data['interval_name']) && !empty($project_title) ? $project_title . ' ('. $order_data['interval_name'].')' : '';
        }

        return $invoice_title;
	}
    add_filter( 'workreap_filter_invoice_title', 'workreap_filter_invoice_title');
}

/**
 * Add Invoice URL
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_filter_invoice_url')){
	function workreap_filter_invoice_url($invoice_url='',$invoice_id=0) {
        global $current_user;
		$order_data     = get_post_meta( $invoice_id, 'cus_woo_product_data',true );
        if( !empty($order_data['project_type']) && $order_data['project_type'] === 'hourly' ){
            $invoice_url    = Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $current_user->ID, true, 'hourly-detail', intval($invoice_id));
        }

        return $invoice_url;
	}
    add_filter( 'workreap_filter_invoice_url', 'workreap_filter_invoice_url',10,2);
}

/**
 * Requried field for job creation
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_add_tooltip_list')){
	function workreap_add_tooltip_list($list=array()) {
        $list['add_max_hours']  = esc_html__('This would be a maximum working hours limit for a freelancer.','workreap-hourly-addon');
        return $list;
	}
    add_filter( 'workreap_filter_tooltip_array', 'workreap_add_tooltip_list');
}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_hourly_duplicate_job_filter')){
	function workreap_hourly_duplicate_job_filter($meta_keys=array(),$post_id=0) {
        $project_type   = get_post_meta( $post_id, 'project_type',true );
        $project_type   = !empty($project_type) ? $project_type : '';

        if( !empty($project_type) && $project_type === 'hourly' ){
            $meta_keys[]    = 'min_price';
            $meta_keys[]    = 'max_price';
            $meta_keys[]    = 'max_hours';
            $meta_keys[]    = 'payment_mode';
        }

        return $meta_keys;
	}
    add_filter( 'workreap_duplicate_job_filter', 'workreap_hourly_duplicate_job_filter',10,2);
}