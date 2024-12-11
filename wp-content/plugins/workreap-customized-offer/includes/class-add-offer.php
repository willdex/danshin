<?php

/**
 *
 * Class 'Workreap_Customized_Task_Offers_Addon_Dashboard_Hooks' defines to add tasks
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

class Workreap_Offer {

    public $task_allowed = true;
    public $task_plans_allowed = true;
    public $number_tasks_allowed = 0;
    public $package_detail = array();

	/**
	 * Task shortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
        add_action( 'workreap_add_offer_steps', array(&$this, 'workreap_add_offer_html'));
        add_action( 'wp_ajax_workreap_offer_inroduction_save', array(&$this, 'workreap_offer_inroduction_save') );
        add_action( 'wp_ajax_workreap_offer_decline', array(&$this, 'workreap_offer_decline') );
        add_action( 'wp_ajax_workreap_offer_media_attachments_save', array(&$this, 'workreap_offer_media_attachments_save') );
        add_action( 'wp_ajax_workreap_offer_plans_save', array(&$this, 'workreap_offer_plans_save') );
        add_action( 'wp_ajax_workreap_offer_next_step_template', array(&$this, 'workreap_offer_next_step_template') );
    }

    /**
	 * Add task steps
	 *
	 * @since    1.0.0
	 * @access   public
	*/
	public function workreap_add_offer_html($workreap_args = array()){
	    //add task introduction        
		if ( ! empty( $workreap_args ) && is_array( $workreap_args ) ) {
            extract( $workreap_args );
        }

        $workreap_args   = array( 'post_id'=>$post_id, 'step' => $step );
        $service_meta   = $this->workreap_task_step_values($post_id, $step);
        $workreap_args   = array_merge($service_meta, $workreap_args);

		if($step == 2){

			workreap_custom_task_offer_get_template(
                'post-offer/add-service-pricing.php',
                $workreap_args
            );

        } elseif ($step == 3){

            workreap_custom_task_offer_get_template(
                'post-offer/add-media-attachments.php',
                $workreap_args
            );
      
        } else {
            //add task template
            workreap_custom_task_offer_get_template(
				'post-offer/add-offer-introduction.php',
				$workreap_args
			);
        }
    }

    /**
	 * add task media attachment form
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_offer_next_step_template(){

        $step    = !empty($_POST['step']) ? intval($_POST['step']) : '1';
        $post_id = !empty($_POST['post_id']) ? intval($_POST['post_id']) : '';

        $workreap_args = array( 'post_id'=>$post_id, 'step' => $step );
        $service_meta = $this->workreap_task_step_values($post_id, $step);
        $workreap_args = array_merge($service_meta, $workreap_args);

        if($step == 2){

            workreap_custom_task_offer_get_template(
                'post-offer/add-service-pricing.php',
                $workreap_args
            );


        } elseif ($step == 3){
            workreap_custom_task_offer_get_template(
                'post-offer/add-media-attachments.php',
                $workreap_args
            );
            
        } else {
            workreap_custom_task_offer_get_template(
                'post-offer/add-service.php',
                $workreap_args
            );
        }

        exit;
    }

    /**
	 * Task next steps
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_task_step_values($post_id='', $step=1){
        global $current_user;

        if($step == 2){
            $this->task_allowed    = workreap_task_create_allowed($current_user->ID);
            $this->package_detail  = workreap_get_package($current_user->ID);

            $this->task_plans_allowed   = 'yes';

            $package_type   =  !empty($this->package_detail['type']) ? $this->package_detail['type'] : '';

            if($package_type == 'paid'){
                $this->task_plans_allowed   =  !empty($this->package_detail['package']['task_plans_allowed']) ? $this->package_detail['package']['task_plans_allowed'] : 'no';
            }

            $workreap_args = array(
                'offer_price'           =>  0.0,
                'offer_content'         =>  0,
                'delivery_time'         =>  0,
                'service_cat'           =>  0,
                'service_categories'    =>  array(),
                'service_meta'          =>  array(),
            );

            if($post_id){
                $post           = get_post($post_id);
                $offer_price    = get_post_meta($post_id, 'offer_price', true);
                $delivery_time  = get_post_meta($post_id, 'delivery_time', true);
                $employer_id       = get_post_meta($post_id, 'employer_id', true);
                $task_id        = get_post_meta($post_id, 'task_id', true);
                $offer_price    = !empty($offer_price)? $offer_price :'';
                $delivery_time  = !empty($delivery_time)? $delivery_time :'';
                $task_id        = !empty($task_id)? $task_id :'';
                $employer_id       = !empty($employer_id)? $employer_id :'';
                
                $service_cat    = wp_get_post_terms( $task_id, 'product_cat', array( 'fields' => 'ids' ) );
                $workreap_args   = array(
                    'task_id'               => $task_id,
                    'employer_id'              => $employer_id,
                    'offer_price'           => $offer_price,
                    'offer_content'         => $post->post_content,
                    'delivery_time'         => $delivery_time,
                    'service_cat'           => !empty($service_cat[0])?$service_cat[0]:0,
                    'service_categories'    => $service_cat,
                    'task_allowed'          => $this->task_allowed,
                    'task_plans_allowed'    => $this->task_plans_allowed,
                );
            }

            $workreap_args   = apply_filters( 'workreap_offer_plans_filter', $workreap_args,  $post_id);

        } elseif ($step == 3){

            $workreap_args   = array(
                'post_id'       => $post_id,
                'offer_gallery' => array(),
            );

            if(!empty($post_id)){
                $gallery        = get_post_meta($post_id, '_offer_attachments', true);
                $gallery        = !empty($gallery) ? $gallery : array();
                $workreap_args   = array(
                    'post_id'       => $post_id,
                    'offer_gallery' => $gallery,
                );
            }

            $workreap_args   = apply_filters( 'workreap_add_offer_media', $workreap_args, $post_id );
        } else {

            $workreap_args = array(
                'employer_id'  =>  '',
                'task_id'   =>  '',
            );

            if($post_id){
                $post       = get_post($post_id);
                $employer_id   = get_post_meta($post_id, 'employer_id', true);
                $task_id    = get_post_meta($post_id, 'task_id', true);
                $task_id    = !empty($task_id)? $task_id :'';
                $employer_id   = !empty($employer_id)? $employer_id :'';

                $workreap_args = array(
                    'employer_id'  =>  $employer_id,
                    'task_id'   =>  $task_id,
                );
            }

            $workreap_args   = apply_filters( 'workreap_add_offer_introduction', $workreap_args );
        }
        $workreap_args   = apply_filters( 'workreap_offer_args', $workreap_args );
        return $workreap_args;
    }

    /**
	 * Task faq save
	 *
	 * @since    1.0.0
	 * @access   public
	*/    
    public function workreap_add_service_faqs_save(){
        global $workreap_settings, $current_user;
        $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
		parse_str($post_data,$data);
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if( function_exists('workreap_verified_user') ) {
            workreap_verified_user();
        }

        $json   = array();

        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Restricted Access', 'customized-task-offer');
            $json['message_desc']   = esc_html__('You are not allowed to perform this action.', 'customized-task-offer');
            wp_send_json($json);
        }

        $service_status   = !empty( $workreap_settings['service_status'] ) ? $workreap_settings['service_status'] : 'publish';
        $post_id          = !empty( $data['post_id'] ) ? intval( $data['post_id'] ) : '';
        $resubmit_service_status    = !empty($workreap_settings['resubmit_service_status']) ? $workreap_settings['resubmit_service_status'] : 'no';

        if(!empty($post_id)){

            if( function_exists('workreap_verify_post_author') ){
                workreap_verify_post_author($post_id);
            }

            $servicefaq_id  = !empty( $data['servicefaq_id'] ) ? intval( $data['servicefaq_id'] ) : '';
            $workreap_faqs   = !empty( $data['faq'] ) ? wp_unslash( $data['faq'] ) : array();
            $workreap_faqs   = workreap_recursive_sanitize_text_field($workreap_faqs);
            $profile_id     = workreap_get_linked_profile_id($current_user->ID,'','freelancers');
            update_post_meta($post_id, 'workreap_service_faqs', $workreap_faqs);
            $this->task_allowed     = workreap_task_create_allowed($current_user->ID);
            $post_status            = get_post_status ( $post_id );
            $post_status            = !empty($post_status) ? $post_status : '';
            if(empty($this->task_allowed) && ($post_status == 'draft' && $service_status !== 'draft')){
                $service_status = 'draft';
            }

            // Update the post status the database
            if ( !empty($post_status) && ($post_status == 'draft' || $post_status == 'pending' || $post_status == 'rejected') ) {
                $service_post = array(
                    'ID'            => $post_id,
                    'post_status'   => $service_status,
                );

                wp_update_post( $service_post );
                if( !empty($service_status) && $service_status === 'pending' && !empty($resubmit_service_status) && $resubmit_service_status === 'yes'){
                    update_post_meta( $post_id, '_post_task_status', 'requested' );
                }
                wp_set_object_terms( $post_id, 'tasks', 'product_type', true );

              /* Send Email to freelancer and admin */
              if (class_exists('Workreap_Email_helper') && !empty( $post_id )) {
                $emailData	= array();

                if (class_exists('WorkreapTaskStatuses')) {
                  
                  $emailData['freelancer_name']		      = workreap_get_username($profile_id);
                  $emailData['freelancer_email']		  = get_userdata( $current_user->ID )->user_email;
                  $emailData['task_name']			  = get_the_title( $post_id );
                  $emailData['task_link']			  = get_permalink($post_id);
                  $emailData['notification_type']     = 'noty_admin_approval';
                  $emailData['sender_id']             = $current_user->ID; //freelancer id
                  $emailData['receiver_id']           = workreap_get_admin_user_id(); //admin id
                  $email_helper                       = new WorkreapTaskStatuses();

                  if($workreap_settings['email_post_task'] == true){
                    $email_helper->post_task_freelancer_email($emailData);
                  }

                  if($workreap_settings['email_admin_task_approval'] == true){
                    $email_helper->post_task_approval_admin_email($emailData);
                    $notifyData						= array();
                    $notifyDetails					= array();
                    $notifyDetails['task_id']       = $post_id;
                    $notifyDetails['freelancer_id']     = $profile_id;
                    $notifyData['receiver_id']		= $current_user->ID;
                    $notifyData['type']				= 'submint_task';
                    $notifyData['linked_profile']	= $profile_id;
                    $notifyData['user_type']		= 'freelancers';
                    $notifyData['post_data']		= $notifyDetails;
                    do_action('workreap_notification_message', $notifyData );
                  } else {
                    $notifyData						= array();
                    $notifyDetails					= array();
                    $notifyDetails['task_id']       = $post_id;
                    $notifyDetails['freelancer_id']     = $profile_id;
                    $notifyData['receiver_id']		= $current_user->ID;
                    $notifyData['type']				= 'task_approved';
                    $notifyData['linked_profile']	= $profile_id;
                    $notifyData['user_type']		= 'freelancers';
                    $notifyData['post_data']		= $notifyDetails;
                    do_action('workreap_notification_message', $notifyData );
                  }
                }

              }
            }

            $service_page_link	= Workreap_Profile_Menu::workreap_profile_menu_link('task', $current_user->ID, true, 'listing');

            $json['type']           = 'success';
            $json['post_id']        = (int)$post_id;
            $json['faq_id']         = (int)$servicefaq_id;
            $json['step']           = 4;
            $json['redirect']       = $service_page_link;
            $json['message']        = esc_html__('Woohoo!', 'customized-task-offer');
            $json['message_desc']   = esc_html__('Task has been added successfully!', 'customized-task-offer');

            do_action('workreap_add_service_faqs_save_activity', $post_id);

            wp_send_json($json);
        } else {
            $json['type']           = 'error';
            $json['message'] 		= esc_html__('Oops!', 'customized-task-offer');
            $json['message_desc'] 	= esc_html__('There is an error occur while saving into database.', 'customized-task-offer');
            wp_send_json($json);
        }
    }

    /**
	 * Task plan save
	 *
	 * @since    1.0.0
	 * @access   public
	*/    
    public function workreap_offer_plans_save(){
        global $current_user,$workreap_settings;
        $custom_field_option    =  !empty($workreap_settings['custom_field_option']) ? $workreap_settings['custom_field_option'] : false;
        $maxnumber_fields       =  !empty($workreap_settings['maxnumber_fields']) ? $workreap_settings['maxnumber_fields'] : 5;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        if( function_exists('workreap_verified_user') ) {
            workreap_verified_user();
        }
        $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
		parse_str($post_data,$post_data);

       

        $json = array();

        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Restricted Access', 'customized-task-offer');
            $json['message_desc'] 	= esc_html__('You are not allowed to perform this action.', 'customized-task-offer');
            wp_send_json($json);
        }

        $post_id = !empty( $post_data['post_id'] ) ? intval( $post_data['post_id'] ) : '';

        if(!empty($post_id)){

            if( function_exists('workreap_verify_post_author') ){
                workreap_verify_post_author($post_id);
            }

            $default_attribs = array(
                'id'    => array(),
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'data'  => array(),
            );

            $allowed_tags   = array(
                'a' => array_merge( $default_attribs, array(
                    'href'  => array(),
                    'title' => array()
                )),
                'br'        => array(),
                'h1'        => array(),
                'h2'        => array(),
                'h3'        => array(),
                'h4'        => array(),
                'h5'        => array(),
                'h6'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'u'             => $default_attribs,
                'i'             => $default_attribs,
                'q'             => $default_attribs,
                'b'             => $default_attribs,
                'ul'            => $default_attribs,
                'ol'            => $default_attribs,
                'li'            => $default_attribs,
                'br'            => $default_attribs,
                'hr'            => $default_attribs,
                'del'           => $default_attribs,
                'strike'        => $default_attribs,
                'em'            => $default_attribs,
                'code'          => $default_attribs,
                'strong'        => $default_attribs,
                'blockquote'    => $default_attribs,
            );
       
            $workreap_offer = !empty($post_data['workreap_offer']) ? $post_data['workreap_offer'] : '';
            $offer_price   = !empty($workreap_offer['offer_price']) ? $workreap_offer['offer_price'] : '';
            $delivery_time = !empty($workreap_offer['delivery_time']) ? $workreap_offer['delivery_time'] : '';
            $post_content  = !empty($workreap_offer['offer_content']) ? wp_kses($workreap_offer['offer_content'], $allowed_tags) : '';
            $workreap_plans = !empty( $post_data['plans'] ) ? wp_unslash( $post_data['plans'] ) : array('basic' => array());
            $custom_fields = !empty($post_data['custom_fields']) ? $post_data['custom_fields'] : array();

            wp_set_post_terms( $post_id, $delivery_time, 'delivery_time' );

            if( !empty($custom_field_option) ){ 
                $custom_field_array = array();
                if( !empty($custom_fields) ){
                    if( !empty($maxnumber_fields) && !empty($custom_fields) && is_array($custom_fields) && count($custom_fields) >= $maxnumber_fields ){
                        $json['type']           = 'error';
                        $json['message']        = esc_html__('Uh-Oh!', 'customized-task-offer');
                        $json['message_desc'] 	= sprintf(esc_html__('You are allowed to add only %s custom fields','customized-task-offer'),$maxnumber_fields);
                        wp_send_json($json);
                    }
                   
                    foreach($custom_fields as $key => $custom_field){
                        $custom_field_array[]   = $custom_field;
                        if( empty($custom_field['title'])){
                            $json['type']           = 'error';
                            $json['message']        = esc_html__('Uh-Oh!', 'customized-task-offer');
                            $json['message_desc'] 	= esc_html__("Please don't leave empty custom fields. Either remove this or add the field title", 'customized-task-offer');
                            wp_send_json($json);
                        }
                    }
                }
                
                update_post_meta( $post_id, 'wr_custom_fields',$custom_field_array );
            }

            $wr_post_data['ID']             = $post_id;
            $wr_post_data['post_content']   = $post_content;
            wp_update_post( $wr_post_data );

            update_post_meta($post_id, 'offer_price', $offer_price);
            update_post_meta($post_id, 'delivery_time', $delivery_time);

            $this->task_allowed     = workreap_task_create_allowed($current_user->ID);
            $this->package_detail   = workreap_get_package($current_user->ID);
            $workreap_plans          = workreap_recursive_sanitize_text_field($workreap_plans);

            if(isset($workreap_plans)){
                update_post_meta($post_id, 'workreap_product_plans', $workreap_plans);
            }

            do_action('workreap_offer_plans_save_activity', $post_id, $post_data);
            $json['type']           = 'success';
            $json['post_id']        = (int)$post_id;
            $json['step']           = 3;
            $json['message'] 	    = esc_html__('Woohoo!', 'customized-task-offer');
            $json['message_desc']   = esc_html__('Offer has been updated', 'customized-task-offer');
            wp_send_json($json);
        } else {
            $json['type']           = 'error';
            $json['message'] 	    = esc_html__('Oops', 'customized-task-offer');
            $json['message_desc']   = esc_html__('There is an error occur, please try again later', 'customized-task-offer');
            wp_send_json($json);
        }
    }

    /**
	 * add offer media attachments
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_offer_media_attachments_save(){
        global $workreap_settings, $current_user;
 
        $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if( function_exists('workreap_verified_user') ) {
            workreap_verified_user();
        }
		parse_str($post_data,$data);
        $json   = array();

        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Restricted Access', 'customized-task-offer');
            $json['message_desc']   = esc_html__('You are not allowed to perform this action.', 'customized-task-offer');
            wp_send_json($json);
        }
        $post_id    = !empty($data['post_id']) ? intval($data['post_id']) : '';

        if(!empty($post_id)){

            if( function_exists('workreap_verify_post_author') ){
                workreap_verify_post_author($post_id);
            }
        } 

        if(empty($post_id)){
            $json['type']           = 'error';
            $json['message']        = esc_html__('Oops!', 'customized-task-offer');
            $json['message_desc']   = esc_html__('There is an error occur, please try again later', 'customized-task-offer');
            wp_send_json($json);
        }

        $files             = !empty($data['attachments']) ? $data['attachments'] : array();
        $attachments_files = array();
        $attachment_ids    = array();

        if (!empty($files)) {
            foreach ($files as $key => $value) {

                if (!empty($value['attachment_id'])) {
                    $attachment_ids[]        = intval($value['attachment_id']);
                    $value['url']            = sanitize_text_field($value['url']);
                    $value['name']           = sanitize_text_field($value['name']);
                    $attachments_files[$key] = $value;
                } else {
                    $new_attachemt = workreap_temp_upload_to_media($value, $post_id);

                    $attachment_ids[]       = $new_attachemt['attachment_id'];
                    $new_attachemt['size']  = !empty($_POST['size'][$key]) ? sanitize_text_field($_POST['size'][$key]) : filesize(get_attached_file($value));
                    $attachments_files[]    = $new_attachemt;
                }

            }
        }
        if(!empty($attachment_ids)){

            if(is_array($attachment_ids) && !empty($attachment_ids['0'])){
                set_post_thumbnail( $post_id, $attachment_ids['0']);
            }
            $attachment_ids_string  = implode(',', $attachment_ids);
        }

        if(!empty($attachment_ids_string)){
            update_post_meta($post_id, '_offer_image_gallery', $attachment_ids_string);
            update_post_meta($post_id, '_offer_attachments', $attachments_files);
        } else {
            delete_post_meta( $post_id, '_offer_image_gallery');
            delete_post_meta( $post_id, '_offer_attachments');
        }
        $task_id    = get_post_meta( $post_id, 'task_id', true );
        $task_id    = !empty($task_id) ? $task_id : '';
        do_action('workreap_offer_media_attachments_update', $post_id);

        if($post_id){
            $my_post = array(
                'ID'           => $post_id,
                'post_status' => 'publish',
            );   
            wp_update_post( $my_post );
        }    
        /* Send Email to freelancer and admin */
        if (class_exists('Workreap_Email_helper') && !empty( $post_id )) {
            $emailData	= array();

            if (class_exists('WorkreapTaskStatuses')) {
                $profile_id     = workreap_get_linked_profile_id($current_user->ID,'','freelancers');
              
                $emailData['freelancer_name']           = workreap_get_username($profile_id);
                $emailData['freelancer_email']		    = get_userdata( $current_user->ID )->user_email;
                $emailData['offer_name']			= get_the_title( $task_id );
                $emailData['offer_link']			= get_permalink($post_id);
                $emailData['task_name']			    = get_the_title( $task_id );
                $emailData['task_link']			    = get_permalink($post_id);
                $emailData['notification_type']     = 'noty_admin_approval';
                $emailData['sender_id']             = $current_user->ID; //freelancer id
                $emailData['receiver_id']           = workreap_get_admin_user_id(); //admin id

                if($workreap_settings['offer_send_employer_email_switch'] == true) {
                    $employer_id       = get_post_meta( $post_id, 'employer_id', true );
                    $employer_userId   = get_post_meta( $employer_id, '_linked_profile', true );
                    $employer_email    = !empty($employer_userId) ? get_userdata( $employer_userId )->user_email : '';
                    $emailData['receiver_id']   = $employer_id;
                    $emailData['employer_name']    = workreap_get_username($employer_id);
                    $emailData['employer_email']	= $employer_email;
                    $email_helper   = new WorkreapOffersAddonEmails();
                    $email_helper->custom_offer_employer_email($emailData);

                    $notifyData						= array();
                    $notifyDetails					= array();
                    $notifyDetails['offer_id']      = $post_id;
                    $notifyDetails['freelancer_id']     = $profile_id;
                    $notifyDetails['task_id']       = $task_id;
                    $notifyData['receiver_id']		= $employer_userId;
                    $notifyData['task_id']		    = $task_id;
                    $notifyData['task_name']		= get_the_title( $task_id );
                    
                    $notifyData['type']				= 'custom_task_offer';
                    $notifyData['linked_profile']	= $employer_id;
                    $notifyData['user_type']		= 'employers';
                    $notifyData['post_data']		= $notifyDetails;
                    do_action('workreap_notification_message', $notifyData );
                }  
                $email_helper   = new WorkreapTaskStatuses();
                $email_helper->post_task_approval_admin_email($emailData);
            }
        }

        $service_page_link	= Workreap_Profile_Menu::workreap_profile_menu_link('offers', $current_user->ID, true, 'listing');

        $json['type']           = 'success';
        $json['post_id']        = (int)$post_id;
        $json['attachment_ids'] = $attachment_ids;
        $json['redirect']       = $service_page_link;   
        $json['step']           = 4;
        $json['message'] 		= esc_html__('Woohoo!', 'customized-task-offer');
        $json['message_desc'] 	= esc_html__('Offer has been updated!', 'customized-task-offer');
        wp_send_json($json);
    }

    /**
	 * save service introduction
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_offer_inroduction_save(){
        global $workreap_settings;
        $error      = array();
        $response   = array();
        $json       = array();
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        if (!wp_verify_nonce($_POST['ajax_nonce'], 'ajax_nonce')) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Restricted Access', 'customized-task-offer');
            $json['message_desc']   = esc_html__('You are not allowed to perform this action.', 'customized-task-offer');
            wp_send_json($json);
        }

        if( function_exists('workreap_verified_user') ) {
            workreap_verified_user();
        }
        
        if( !empty($_POST['action']) && $_POST['action'] == 'workreap_offer_inroduction_save' ) {
            $post_id    = !empty($_POST['post_id']) ? intval($_POST['post_id']) : '';

            if(!empty($post_id)){

                if( function_exists('workreap_verify_post_author') ){
                    workreap_verify_post_author($post_id);
                }
            }
            $post_data  = !empty($_POST['workreap_offer']) ? $_POST['workreap_offer'] : array();
            $validation_fields  = array(
                'task_id'    => esc_html__('Please select task.','customized-task-offer'),
                'employer_id'   => esc_html__('Please select employer.','customized-task-offer')
            );
      
            foreach($validation_fields as $key => $validation_field ){

                if( empty($post_data[$key]) ){
                    $json['type']           = 'error';
	                $json['message'] 		    = esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = $validation_field;
                    wp_send_json($json);
                }
            }

            $task_id    = !empty($post_data['task_id']) ? intval($post_data['task_id']) : '';
            $task       = get_post($task_id);
            $employer_id   = !empty($post_data['employer_id']) ? intval($post_data['employer_id']) : '';
 
             // Update post
             $wr_post_data = array(
                'post_title'    => wp_strip_all_tags($task->post_title),
                'post_type'     => 'offers',
                'post_author'   => get_current_user_id(),
            );

            if($post_id){
                // Update the post into the database
                $wr_post_data['ID']         = $post_id;
                wp_update_post( $wr_post_data );
            } else {
                $wr_post_data['post_status'] = 'draft';
                // insert the post into the database
                $post_id = wp_insert_post( $wr_post_data );
            }

            if($post_id){

                update_post_meta($post_id, 'task_id', $task_id);
                update_post_meta($post_id, 'employer_id', $employer_id);

                do_action('workreap_offer_create_activity', $post_id, $post_data);

                $json['type']           = 'success';
                $json['post_id']        = (int)$post_id;
                $json['step']           = 2;
                $json['message'] 		= esc_html__('Woohoo!', 'customized-task-offer');
                $json['message_desc']   = esc_html__('Offer has been updated', 'customized-task-offer');
                wp_send_json($json);
            } else {
                $json['type']           = 'error';
                $json['message'] 		= esc_html__('Oops', 'customized-task-offer');
                $json['message_desc']   = esc_html__('There is an error occur, please try again later', 'customized-task-offer');
                wp_send_json($json);
            }
        }
        exit;
    }

     /**
	 * decline offer
	 *
	 * @since    1.0.0
	 * @access   public
	*/
    public function workreap_offer_decline(){
        global $workreap_settings,$current_user;
        $error      = array();
        $response   = array();
        $json       = array();
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
         
        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Restricted Access', 'customized-task-offer');
            $json['message_desc']   = esc_html__('You are not alddlowed to perform this action.', 'customized-task-offer');
            wp_send_json($json);
        } 

        $validations = array(
            'offer_id'    => esc_html__('Something went wrong.', 'customized-task-offer'),
            'employer_id'    => esc_html__('Something went wrong.', 'customized-task-offer'),
            'details'     => esc_html__('Decline reason is required.', 'customized-task-offer'),
            'post_author' => esc_html__('Something went wrong.', 'customized-task-offer'),
          
        );

        foreach ($validations as $key => $value) {
            if (empty($_POST[$key])) {
                $json['title']      = esc_html__("Oops!", 'customized-task-offer');
                $json['type']       = 'error';
                $json['message']    = $value;
                wp_send_json($json); 
            }
        }
        $offer_id   = !empty($_POST['offer_id']) ? intval($_POST['offer_id']) : '';
        $employer_id   = !empty($_POST['employer_id']) ? intval($_POST['employer_id']) : '';
        $details    = !empty($_POST['details']) ? esc_html($_POST['details']) : '';
        $p_author   = !empty($_POST['post_author']) ? intval($_POST['post_author']) : '';
        $offer      = get_post($offer_id);

        $offer = array(
            'ID'           => $offer_id,
            'post_status'  => 'rejected',
        );

        // Update the post into the database
        $offer_update = wp_update_post( $offer );
        if ( ! is_wp_error( $offer_update ) ) {
            $task_id    = get_post_meta( $offer_id, 'task_id',true );
            $task_id    = !empty($task_id) ? $task_id : '';
            update_post_meta($offer_id, 'decline_reason', $details);
            /* Send Email to freelancer and admin */
            if (class_exists('Workreap_Email_helper') && !empty( $offer_id )) {
                $emailData	= array();
                $page_link                  = Workreap_Profile_Menu::workreap_profile_menu_link('offers', $current_user->ID, true, 'listing');
                $json['redirect_url'] 		= $page_link;
                if (class_exists('WorkreapTaskStatuses')) {
                    $profile_id         = workreap_get_linked_profile_id($p_author,'','freelancers');
                    $employer_email        = !empty($employer_id) ? get_userdata( $employer_id )->user_email : '';
                    $employer_profile_id   = workreap_get_linked_profile_id($employer_id,'','employers');

                    $emailData['freelancer_name']       = workreap_get_username($p_author);
                    $emailData['freelancer_email']		= get_userdata( $p_author )->user_email;
                    $emailData['offer_name']		= get_the_title( $offer_id );
                    $emailData['decline_reason']	= $details;
                    $emailData['notification_type'] = 'decline_offer_request';
                    $emailData['sender_id']         = $employer_id;
                    $emailData['receiver_id']       = $p_author;
                    $emailData['employer_name']        = workreap_get_username($employer_id);
                    $emailData['employer_email']	    = $employer_email;

                    if($workreap_settings['offer_decline_freelancer_email_switch'] == true) {
                    
                        $email_helper   = new WorkreapOffersAddonEmails();
                        $email_helper->custom_offer_decline_freelancer_email($emailData);

                        $notifyData					= array();
                        $notifyDetails				= array();
                        $notifyDetails['task_id']   = $task_id;
                        $notifyDetails['offer_id']  = $offer_id;
                        $notifyDetails['freelancer_id'] = $profile_id;
                        $notifyDetails['employer_id']  = $employer_profile_id;
                        $notifyData['receiver_id']	= $employer_id;
                        $notifyData['task_id']		= $task_id;
                        
                        $notifyData['type']				= 'decline_custom_task_offer';
                        $notifyData['linked_profile']	= $profile_id;
                        $notifyData['user_type']		= 'freelancers';
                        $notifyData['post_data']		= $notifyDetails;
                        do_action('workreap_notification_message', $notifyData );

                        $json['type']               = 'success';
                        $json['message'] 		    = esc_html__('Woohoo!', 'customized-task-offer');
                        $json['message_desc'] 		= esc_html__('Offer status has been updated', 'customized-task-offer');
                        wp_send_json($json);
                    }
                }
            }
           
            $json['type']               = 'success';
            $json['message'] 		    = esc_html__('Woohoo!', 'customized-task-offer');
            $json['message_desc'] 		= esc_html__('Offer status has been updated', 'customized-task-offer');
            wp_send_json($json);
        } else {
            $json['type']               = 'error';
            $json['message'] 		    = esc_html__('Oops', 'customized-task-offer');
            $json['message_desc'] 		= esc_html__('There is an error occur, please try again later', 'customized-task-offer');
            wp_send_json($json);
        }
    }
    
}

new Workreap_Offer();
