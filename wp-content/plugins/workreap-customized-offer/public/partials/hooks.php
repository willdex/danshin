<?php
/**
 * Offers listing template
 *
 * @since    1.0.0
*/
if (!function_exists('workreap_offers_listing')) {
    function workreap_offers_listing($args = array()){
        global $current_user;
        $user_identity  = intval($current_user->ID);
        $user_type		= apply_filters('workreap_get_user_type', $user_identity );
        workreap_custom_task_offer_get_template(
            $user_type.'-offers-listing.php',
            $args
        );
    }
    add_action('workreap_offers_listing', 'workreap_offers_listing');
}

/**
 * Offers cart template
 *
 * @since    1.0.0
*/
if (!function_exists('workreap_offers_cart')) {
    function workreap_offers_cart($args = array()){
        workreap_custom_task_offer_get_template(
            'offers-cart-page.php',
            $args
        );
    }
    add_action('workreap_offers_cart', 'workreap_offers_cart');
}


/**
 * Print task details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_offers_order_details')) {
	function workreap_offers_order_details( $cart_items, $product_id=0, $offers_id=0, $type='') {
		$workreap_plans_values 	= get_post_meta($offers_id, 'workreap_product_plans', TRUE);
		$workreap_plans_values	= !empty($workreap_plans_values) ? $workreap_plans_values : array();
		$subtasks				= array();
		$task_pan				= !empty($cart_items['task']) ? $cart_items['task'] : '';
		$product_cat 			= wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
		$workreap_subtask 		= get_post_meta($product_id, 'workreap_product_subtasks', TRUE);
		$plan_array				= array(
									'product_tabs' 			=> array('plan'),
									'product_plans_category'=> $product_cat
								);

		$acf_fields				= workreap_acf_groups($plan_array);
		$title					= esc_html__('Custom Offer', 'customized-task-offer');
        $price    		        = get_post_meta($offers_id, 'offer_price', true);
		$tab_contents			= '';
		$order_contents					= array();
		$order_contents['key']			= $task_pan;
		$order_contents['title']		= $title;
		$order_contents['description']	= '';
		$order_contents['price']		= $price;
		$fields							= array();
        $contents_array					= workreap_task_custom_fields($offers_id,$task_pan);
        $db_delivery_time               = wp_get_post_terms($offers_id, 'delivery_time', array('fields' => 'ids'));
		if( !empty($acf_fields) || !empty($contents_array['wr_custom_fields']) || !empty($db_delivery_time) ){
			$counter_checked	= 0;
			$tab_contents	.='<div class="wr-sectiontitle wr-sectiontitlev2">';
			if( !empty($title) ){
				$tab_contents	.='<h3 class="wr-theme-color">'.esc_html($title).'</h3>';
			}
			if( !empty($price) ){
				$tab_contents	.='<h4 class="wr-theme-color">'.workreap_price_format($price,'return').'</h4>';
			}
			$tab_contents	.='<div class="wr-sectiontitle__list--title"><h6>'.esc_html__('Features included','customized-task-offer').'</h6><ul class="wr-sectiontitle__list wr-sectiontitle__listv2">';
            if( !empty($acf_fields) ){
                foreach($acf_fields as $acf_field ){
                    $plan_value	= !empty($acf_field['key']) && !empty($workreap_plans_values[$task_pan][$acf_field['key']]) ? $workreap_plans_values[$task_pan][$acf_field['key']] : '--';
                    $counter_checked++;
                    $tab_contents	.= workreap_task_package_details($acf_field,$plan_value);
                    $field_values					= $acf_field;
                    $field_values['selected_val']	= $plan_value;
                    $fields[]	                    = $field_values;
                }
            }

			
			$tab_contents					        .= !empty($contents_array['contents']) ? $contents_array['contents'] : '';
			$order_contents['wr_custom_fields']		= !empty($contents_array['wr_custom_fields']) ? $contents_array['wr_custom_fields'] : array();
			$order_contents['price']		        = $price;
            if(function_exists('workreap_offer_delivery_time') ){
                $tab_contents	.= workreap_offer_delivery_time($offers_id,'v3');
            }
			$tab_contents	            .='</ul></div>';
			$order_contents['fields']	= $fields;

			
			$tab_contents	.='</div>';
		}
		if( !empty($type) && $type == 'return' ){
			return $order_contents;
		}
		return $tab_contents;
	}
}

/**
 * Offers post status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_offers_status_filter')) {
    function workreap_list_offers_status_filter($type = '')
    {
        global $workreap_settings,$current_user;
        $user_type  = apply_filters('workreap_get_user_type', $current_user->ID );

        $list = array(
            'any'      => esc_html__('All offers', 'customized-task-offer'),
            'publish'  => esc_html__('Published', 'customized-task-offer'),
            'rejected' => esc_html__('Rejected', 'customized-task-offer'),
        );
        $service_status             = !empty($workreap_settings['service_status']) ? $workreap_settings['service_status'] : '';
        if( !empty($service_status) && $service_status === 'pending'){
            $list['publish']    = esc_html__('Approved', 'customized-task-offer');
        }
        if(!empty($user_type) && $user_type !== 'employers'){
            $list['draft']  = esc_html__('Drafted', 'customized-task-offer');
        }
        $list = apply_filters('workreap_filters_list_offers_status_filter_by', $list);
        return $list;
    }
    add_filter('workreap_list_offers_status_filter', 'workreap_list_offers_status_filter', 10, 1);
}

 /**
 * Tasks delievery time
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
*/
if (!function_exists('workreap_offer_delivery_time')) {
    function workreap_offer_delivery_time($post_id, $version = 'v1')
    {
        $days   = 0;

        if(!empty($post_id)){
            $db_delivery_time   = get_post_meta( $post_id, '_delivery_time',true );
            if( !empty($db_delivery_time) ){
                $delivery_time = 'delivery_time_' . $db_delivery_time;
                if (function_exists('get_field')) {
                    $days = get_field('days', $delivery_time);
                } 
            } else {
                $delivery_terms     = wp_get_post_terms($post_id, 'delivery_time', array('fields' => 'ids'));
                $days = array();
                foreach ($delivery_terms as $delivery_id) {
                    $delivery_time = 'delivery_time_' . $delivery_id;

                    if (function_exists('get_field')) {
                        $days[] = get_field('days', $delivery_time);
                    } else {
                        $days[] = 0;
                    }
                }
                $days = !empty($days) && is_array($days) ? min($days) : 0;
            }
        }
        
        ob_start();
        if (!empty($version) && $version == 'v1' && !empty($days)) { ?>
            <li>
                <span class="wr-greenbox"><i class="fas fa-calendar-check"></i></span>
                <div class="wr-sales__title">
                    <em><?php esc_html_e('Delivery time', 'customized-task-offer'); ?></em>
                    <h6><?php echo sprintf(_n( '%s Day', '%s Days', $days, 'customized-task-offer' ), $days); ?></h6>
                </div>
            </li>
        <?php echo ob_get_clean();
            } elseif (!empty($version) && $version == 'v2' && !empty($days)) { ?>
            <li>
                <div class="wr-pkgresponse__content wr-greenbox wr-change-timedays">
                    <i class="wr-icon-gift"></i>
                    <h6><?php echo sprintf(_n( '%s Day', '%s Days', $days, 'customized-task-offer' ), $days); ?></h6>
                    <span><?php esc_html_e('Delivery', 'customized-task-offer'); ?></span>
                </div>
            </li>
        <?php echo ob_get_clean();
        } elseif (!empty($version) && $version == 'v3' && !empty($days)) {
            ?>
                <li class="wr-offer-del-key">
                    <i class="wr-icon-gift"></i>
                    <span><?php echo sprintf(_n( 'Delivery in %s day', 'Delivery in %s days', $days, 'customized-task-offer' ), $days); ?></span>
                    
                </li>
            <?php
            return ob_get_clean();
        }
    }
    add_action('workreap_offer_delivery_time', 'workreap_offer_delivery_time',10,2);
}

/**
 * Handle a custom 'customvar' query var to get orders with the 'customvar' meta.
 * @param array $query - Args for WP_Query.
 * @param array $query_vars - Query vars from WC_Order_Query.
 * @return array modified $query
 */
/**
 * Tasks no of sales
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
*/
function handle_custom_query_var( $query, $query_vars ) {
    if ( ! empty( $query_vars['offers_id'] ) ) {
        $query['meta_query'][] = array(
            'key' => 'offers_id',
            'value' => esc_attr( $query_vars['offers_id'] ),
        );
    }

    return $query;
}
add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'handle_custom_query_var', 10, 2 );

/**
 * Offers no of sales
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
*/
if (!function_exists('workreap_offer_sales')) {
    function workreap_offer_sales($post_id, $version = 'v1')
    {
        if(!empty($post_id)){
            $sales = count(wc_get_orders( array( 'offers_id' => $post_id ) ));
        }
        $sales = !empty($sales) ? sprintf("%02d", intval($sales)) : 0;
        ob_start();
        if (!empty($version) && $version == 'v1') { ?>
            <li>
            <div class="wr-pkgresponse__content wr-purple">
                <i class="wr-icon-archive"></i>
                <em><?php esc_html_e('No. of sales', 'customized-task-offer'); ?></em>
                <h6><?php echo intval($sales); ?></h6>
                
            </div>
            </li>
        <?php } else if (!empty($version) && $version == 'v2') { ?>
            <li>
                <div class="wr-pkgresponse__content wr-purple">
                    <i class="wr-icon-clock"></i>
                    <h6><?php echo intval($sales); ?></h6>
                    <span><?php esc_html_e('No. of sales', 'customized-task-offer'); ?></span>
                </div>
            </li>
        <?php }
        echo ob_get_clean();
    }
    add_action('workreap_offer_sales', 'workreap_offer_sales');
}
    
/**
 * Custom offer details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
*/
if (!function_exists('workreap_custom_offer_details')) {
    function workreap_custom_offer_details($cart_items= array())
    {  
        $offers_id      = !empty($cart_items['offers_id']) ? $cart_items['offers_id'] : 0;
		$product_id     = !empty($cart_items['product_id']) ? $cart_items['product_id'] : 0;
        if( !empty( $cart_items['cart_data'] ) && function_exists('workreap_offers_order_details') ){
            ob_start();
            $contents			= workreap_offers_order_details($cart_items['cart_data'],$product_id,$offers_id);
            if( !empty($contents) ){ ?>
                <div class="wr-haslayout wr-offer-checkout">
                    <div class="cart-data-wrap">
                        <h3><?php esc_html_e('Summary','customized-task-offer');?></h3>
                        <div class="selection-wrap">
                            <?php echo do_shortcode( $contents );?>
                        </div>
                    </div>
                </div>
            <?php
            }
            echo ob_get_clean();
        }
    }
    add_action('workreap_custom_offer_details', 'workreap_custom_offer_details');
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_update_custom_offer_data')) {
    function workreap_update_custom_offer_data( $order_id,$order_detail ) {
    	global $workreap_settings;
		$product_id		= !empty($order_detail['task_id']) ? $order_detail['task_id'] : 0;
		$offers_id		= !empty($order_detail['offers_id']) ? $order_detail['offers_id'] : 0;
		$freelancer_shares	= !empty($order_detail['freelancer_shares']) ? $order_detail['freelancer_shares'] : 0;
		$admin_shares	= !empty($order_detail['admin_shares']) ? $order_detail['admin_shares'] : 0;
		$employer_id		= !empty($order_detail['employer_id']) ? $order_detail['employer_id'] : 0;
		$freelancer_id		= !empty($order_detail['freelancer_id']) ? $order_detail['freelancer_id'] : 0;
		$wallet_price	= !empty($order_detail['wallet_price']) ? $order_detail['wallet_price'] : 0;
		$order_amount	= !empty($order_detail['price']) ? $order_detail['price'] : 0;
		$employer_amount	= $order_amount;
		if( !empty($wallet_price) ){
			$user_balance   = !empty($employer_id) ? get_user_meta( $employer_id, '_employer_balance',true ) : '';
			$user_balance   = !empty($user_balance) ? ($user_balance-$wallet_price) : 0;

			update_user_meta( $employer_id,'_employer_balance',$user_balance);
			update_post_meta( $order_id, '_wallet_amount', $wallet_price );
			update_post_meta( $order_id, '_task_type', 'wallet' );
			$employer_amount	= !empty($employer_amount) ? ($employer_amount + $wallet_price) : 0;
		}

		update_post_meta( $order_id, 'employer_id', $employer_id );
		update_post_meta( $order_id, 'freelancer_id', $freelancer_id );
		update_post_meta( $order_id, '_task_status', 'hired' );
        update_post_meta( $order_id, '_order_task_type', 'custom_offer' );  
        
        update_post_meta( $order_id, 'offers_id', $offers_id );
        update_post_meta( $offers_id, 'order_id', $order_id );
        $offer_arg = array(
            'ID'           => $offers_id,
            'post_status'  => 'hired',
        );

        $offer_update   = wp_update_post( $offer_arg );
		$contents	    = workreap_offers_order_details($order_detail,$product_id,$offers_id,'return');
        $delivery_terms = wp_get_post_terms($offers_id, 'delivery_time', array('fields' => 'ids'));
        $days = array();
        if( !empty($delivery_terms) ){
            foreach ($delivery_terms as $delivery_id) {
                $delivery_time = 'delivery_time_' . $delivery_id;

                if (function_exists('get_field')) {
                    $days[] = get_field('days', $delivery_time);
                } else {
                    $days[] = 0;
                }
            }
        }

        $days           = !empty($days) && is_array($days) ? min($days) : 0;
        $days			= !empty($days) ? $days : 0;
        $gmt_time		= current_time( 'mysql', 1 );
        $gmt_strtotime	= strtotime($gmt_time . " +".$days." days");

        update_post_meta( $order_id, 'delivery_date', $gmt_strtotime );

        $delivery_dattails									= array();
        $delivery_dattails[$delivery_id]['days']			= $days;
        $delivery_dattails[$delivery_id]['delivery_date']	= $gmt_strtotime;
        update_post_meta( $order_id, 'delivery_dattails', $delivery_dattails );

		//Send email to users/admin
		$employer      	= get_user_by( 'id', $employer_id );
		$employer_name 	= !empty($employer->first_name) ? $employer->first_name : '';
		$freelancer         = get_user_by( 'id', $freelancer_id );
		$freelancer_name    = !empty($freelancer->first_name) ? $freelancer->first_name : '';
		$product    	= wc_get_product( $product_id );
		$task_name  	= $product->get_title();
		$freelancer_email 	= !empty($freelancer->user_email) ? $freelancer->user_email : '';
		$employer_email 	= !empty($employer->user_email) ? $employer->user_email : '';
		$task_link   	= get_permalink($offers_id);

		$freelancer_profile_id  = workreap_get_linked_profile_id($freelancer_id,'','freelancers');
		$employer_profile_id  	= workreap_get_linked_profile_id($employer_id,'','employers');

		$notifyData						= array();
        $notifyDetails					= array();
        $notifyDetails['task_id']     	= $product_id;
        $notifyDetails['freelancer_id']   	= $freelancer_profile_id;
		$notifyDetails['employer_id']   	= $employer_profile_id;
		$notifyDetails['order_id']   	= $order_id;
        $notifyDetails['offers_id']   	= $offers_id;
		$notifyDetails['freelancer_order_amount'] = $freelancer_shares;
		$notifyDetails['employer_amount'] 	= $employer_amount;
        $notifyData['receiver_id']		= $freelancer_id;
        $notifyData['type']			    = 'freelancer_new_order';
        $notifyData['linked_profile']	= $freelancer_profile_id;
        $notifyData['user_type']		= 'freelancers';
        $notifyData['post_data']		= $notifyDetails;

        do_action('workreap_notification_message', $notifyData );

		$notifyData['receiver_id']		= $employer_id;
		$notifyData['type']			    = 'employer_new_order';
        $notifyData['linked_profile']	= $employer_profile_id;
        $notifyData['user_type']		= 'employers';
        $notifyData['post_data']		= $notifyDetails;
        do_action('workreap_notification_message', $notifyData );

		if (class_exists('Workreap_Email_helper')) {
			if (class_exists('WorkreapOrderStatuses')) {
				$blogname                 = get_option( 'blogname' );
				$default_chat_mesage      = wp_kses(__('Congratulations! You have hired for the task "{{taskname}}" {{tasklink}}', 'customized-task-offer'), //default email content
					array(
						'a' => array(
						'href' => array(),
						'title' => array()
						),
						'br' => array(),
						'em' => array(),
						'strong' => array(),
					)
				);
				$emailData = array();
				$emailData['freelancer_name']       = $freelancer_name;
				$emailData['employer_name'] 		= $employer_name;
				$emailData['task_name']         = $task_name;
				$emailData['freelancer_email']      = $freelancer_email;
				$emailData['employer_email']       = $employer_email;
				$emailData['task_link']         = $task_link;
				$emailData['order_id'] 			= $order_id;
                $emailData['offers_id']   	    = $offers_id;
				$emailData['order_amount']      = $order_amount;
				$emailData['notification_type'] = 'noty_new_order';
				$emailData['sender_id']         = $freelancer_id; //freelancer id
				$emailData['receiver_id']       = $employer_id; //employer id

				/* New Order Email */
				$email_helper = new WorkreapOrderStatuses();
				if( !empty($workreap_settings['email_new_order_freelancer']) ) {
					$email_helper->new_order_freelancer_email($emailData);
					if( (in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins')))) && $workreap_settings['hire_freelancer_chat_switch']==true){
						$message = !empty($workreap_settings['hire_freelancer_chat_mesage']) ? $workreap_settings['hire_freelancer_chat_mesage'] : $default_chat_mesage;
						$chat_mesage  = str_replace("{{taskname}}", $task_name, $message);
						$chat_mesage  = str_replace("{{tasklink}}", $task_link, $chat_mesage);
						do_action('wpguppy_send_message_to_user',$employer_id,$freelancer_id,$chat_mesage);
					}
				}

				if ( !empty($workreap_settings['email_new_order_employer'])) {
					$email_helper->new_order_employer_email($emailData);
				}
				do_action('noty_push_notification', $emailData);
			}
		}

		update_post_meta( $order_id, 'order_details', $contents );
    }
    add_action( 'workreap_update_custom_offer_data', 'workreap_update_custom_offer_data',10,2 );
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('workreap_custom_offer_display_order_data_success')) {
    function workreap_custom_offer_display_order_data_success( $order_id=0,$order_detail=array() ) {
        global $current_user;
    	$product_id 	= get_post_meta( $order_id, 'task_product_id', true );
        $offers_id 	    = get_post_meta( $order_id, 'offers_id', true );
        $product_id		= !empty($product_id) ? $product_id : 0;
        $dashboard_url	= !empty($current_user->ID) ? Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $current_user->ID, true, 'insights') : '';
        $redirect_url	= !empty($current_user->ID) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $current_user->ID, true, 'detail',$order_id) : '';
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="cart-data-wrap">
                    <div class="selection-wrap">
                    <?php
                        if( !empty( $order_detail ) ){
                            $contents			= workreap_offers_order_details($order_detail,$product_id,$offers_id);
                            if( !empty($contents) ){ ?>
                                <div class="wr-haslayout wr-offer-checkout">
                                    <div class="cart-data-wrap">
                                        <h3><?php esc_html_e('Summary','customized-task-offer');?></h3>
                                        <div class="selection-wrap">
                                            <?php echo do_shortcode( $contents );?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        }?>
                        <div class="wr-go-dbbtn">
                            <a href="<?php echo esc_url_raw($redirect_url);?>" class="button"><?php esc_html_e('Go to task','customized-task-offer');?></a>
                            <a href="<?php echo esc_url_raw($dashboard_url);?>" class="button"><?php esc_html_e('Go to dashboard','customized-task-offer');?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    add_action( 'workreap_custom_offer_display_order_data_success', 'workreap_custom_offer_display_order_data_success',10,2 );
}

/**
 * Custom offer employer email settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_offer_employer_email')){
	function workreap_offer_employer_email($employer_email) {
        $new_array  = array(
            /* Email to employer on hourly request from freelancer */
            array(
                'id'      => 'divider_offer_send_employer_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Customized offer request', 'customized-task-offer' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'offer_send_employer_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'customized-task-offer'),
                'subtitle' => esc_html__('Email to employer on custom offer request', 'customized-task-offer'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'offer_send_employer_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'customized-task-offer' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'customized-task-offer' ),
                'default' 	=> esc_html__( 'Customized task offer request', 'customized-task-offer'),
                'required'  => array('offer_send_employer_email_switch','equals','1')
            ),
            array(
                'id'      => 'offer_send_employer_email_information',
                'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>
                            {{freelancer_name}} — To display the freelancer name.<br>
                            {{offer_title}} — To display the offer title.<br>
                            {{offer_link}} — To display the offer link.<br>'
                            , 'customized-task-offer' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'customized-task-offer' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('offer_send_employer_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'offer_send_employer_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'customized-task-offer' ),
                'desc'    	=> esc_html__( 'Please add text.', 'customized-task-offer' ),
                'default' 	=> esc_html__( 'Hello {{employer_name}},', 'customized-task-offer'),
                'required'  => array('offer_send_employer_email_switch','equals','1')
            ),
            array(
                'id'        => 'offer_send_employer_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( '{{freelancer_name}} send you an custom task offer.<br/>Please click on the button below to view the offer <br/> {{offer_link}}', 'customized-task-offer'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'customized-task-offer' ),
                'required'  => array('offer_send_employer_email_switch','equals','1')
            ),

            array(
                'id'      => 'divider_decline_offer_send_freelancer_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Customized offer decline email', 'customized-task-offer' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'offer_decline_freelancer_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'customized-task-offer'),
                'subtitle' => esc_html__('Email to freelancer on decline offer request', 'customized-task-offer'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'offer_decline_freelancer_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'customized-task-offer' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'customized-task-offer' ),
                'default' 	=> esc_html__( 'Customized decline offer request', 'customized-task-offer'),
                'required'  => array('offer_decline_freelancer_email_switch','equals','1')
            ),
            array(
                'id'      => 'decline_offer_send_freelancer_email_information',
                'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>
                            {{freelancer_name}} — To display the freelancer name.<br>
                            {{offer_title}} — To display the offer title.<br>
                            {{decline_reason}} — To display the offer link.<br>'
                            , 'customized-task-offer' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'customized-task-offer' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('offer_decline_freelancer_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'decline_offer_send_freelancer_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'customized-task-offer' ),
                'desc'    	=> esc_html__( 'Please add text.', 'customized-task-offer' ),
                'default' 	=> esc_html__( 'Hello {{freelancer_name}},', 'customized-task-offer'),
                'required'  => array('offer_decline_freelancer_email_switch','equals','1')
            ),
            array(
                'id'        => 'decline_offer_send_freelancer_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( '{{employer_name}} has been decline your custom request.<br/>  {{decline_reason}}', 'customized-task-offer'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'customized-task-offer' ),
                'required'  => array('offer_decline_freelancer_email_switch','equals','1')
            ),
        );
        
        return array_merge($employer_email,$new_array);
	}
    add_filter( 'workreap_filter_employer_email_fields', 'workreap_offer_employer_email');

}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_offer_notification')){
	function workreap_offer_notification($data=array()) {
    
        //notification for offer employer
        $data['custom_task_offer']  = array(
            'type'      => 'task',
            'settings'  => array(
                'image_type'    => 'defult',
                'tage'          => array('email','sitename','offer_name','freelancer_name','task_name','offer_link'),
                'btn_settings'  => array('link_type'=>'view_freelancer_custom_offer', 'text'=> esc_html__('View offer','customized-task-offer'))

            ),
            'options'   => array(
                'title'             => esc_html__('Customize task offer','customized-task-offer'),
                'tag_title'         => esc_html__('Notification setting variables','customized-task-offer'),
                'content_title'     => esc_html__('Notification content','customized-task-offer'),
                'enable_title'      => esc_html__('Enable/disable notification for customize task.','customized-task-offer'),
                'flash_message_title'=> esc_html__('Enable/disable flash message','customized-task-offer'),
                'flash_message_option'     => false,
                'content'           => __('{{freelancer_name}} send you an custom offer.<br/>Please click on the button below to view the offer <br/> {{offer_link}}', 'customized-task-offer'),
                'tags'  => __('
                    {{offer_link}}       — To display the offer link.<br>
                    {{freelancer_name}}     — To display the freelancer name.<br>
                    {{task_name}}       — To display the offer title.<br>
                    {{email}}    — To display the email address.<br>
                    {{sitename}} — To display the site name.<br>
                '),
            ),

        );

        $data['decline_custom_task_offer']  = array(
            'type'      => 'task',
            'settings'  => array(
                'image_type'    => 'defult',
                'tage'          => array('email','sitename','offer_name','employer_name','freelancer_name','task_name','offer_link'),
                'btn_settings'  => array('link_type'=>'edit_custom_offer', 'text'=> esc_html__('View offer','customized-task-offer'))

            ),
            'options'   => array(
                'title'             => esc_html__('Decline customize task offer by employer','customized-task-offer'),
                'tag_title'         => esc_html__('Notification setting variables','customized-task-offer'),
                'content_title'     => esc_html__('Notification content','customized-task-offer'),
                'enable_title'      => esc_html__('Enable/disable notification for customize task.','customized-task-offer'),
                'flash_message_title'=> esc_html__('Enable/disable flash message','customized-task-offer'),
                'flash_message_option'     => false,
                'content'           => __('<b>{{employer_name}}</b> decline your offer for task <b>{{task_name}}</b>', 'customized-task-offer'),
                'tags'  => __('
                    {{offer_link}}       — To display the offer link.<br>
                    {{freelancer_name}}     — To display the freelancer name.<br>
                    {{task_name}}       — To display the offer title.<br>
                    {{email}}           — To display the email address.<br>
                    {{sitename}}        — To display the site name.<br>
                '),
            ),

        );
        return $data;
	}
    add_filter( 'workreap_filter_list_notification', 'workreap_offer_notification');
}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_offer_notification_button')){
	function workreap_offer_notification_button($button_html,$post_id,$settings,$show_option) {
        $btn_settings			= !empty($settings['btn_settings']) ? $settings['btn_settings'] : array();
		$link_class				= !empty($show_option) && $show_option === 'listing' ? 'wr-btn-solid' : '';
        if( !empty($btn_settings) ){
			$link_type	= !empty($btn_settings['link_type']) ? $btn_settings['link_type'] : '';
			$btn_link	= '';
			$post_data	= get_post_meta( $post_id, 'post_data', true);
			$post_data	= !empty($post_data) ? $post_data : array();
            
			if( !empty($link_type) && $link_type === 'view_freelancer_custom_offer' ){

                $offer_id		= !empty($post_data['offer_id']) ? $post_data['offer_id'] : 0;
				$receiver_id	= get_post_field( 'post_author', $post_id );
				$order_id		= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
				$btn_link		= !empty($receiver_id) ? get_the_permalink($offer_id) : "";
                $button_html	= !empty($btn_settings['text']) ? '<a class="'.esc_attr($link_class).'" href="'.esc_url($btn_link).'">'.esc_html($btn_settings['text']).'</a>' : '';
            } else if( !empty($link_type) && $link_type === 'edit_custom_offer' ){
                $page_link      = workreap_get_page_uri('add_offer_page');
                $offer_id		= !empty($post_data['offer_id']) ? $post_data['offer_id'] : 0;
				$btn_link		= add_query_arg('post', $offer_id, $page_link);
                $button_html	= !empty($btn_settings['text']) ? '<a class="'.esc_attr($link_class).'" href="'.esc_url($btn_link).'">'.esc_html($btn_settings['text']).'</a>' : '';
            }
        }
        return $button_html;
	}
    add_filter( 'workreap_filter_notification_button', 'workreap_offer_notification_button',10,4);
}


/**
 * Custom offer title
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_custom_offer_title')){
	function workreap_custom_offer_title($task_title = '',$invoice_id=0) {
		$offers_id  = get_post_meta( $invoice_id, 'offers_id',true );
        if( !empty($offers_id) ){
            $task_title  = $task_title.'('.esc_html__('Custon offer','customized-task-offer').')';
        }

        return $task_title;
	}
    add_filter( 'workreap_custom_offer_title', 'workreap_custom_offer_title',10,2);
}

/**
 * Completed offer
 *
 * @since    1.0.0
*/
if (!function_exists('workreap_complete_task_order_activity')) {
    function workreap_complete_task_order_activity($order_id=0){
        $offers_id  = get_post_meta( $order_id, 'offers_id', true );
        $offers_id  = !empty($offers_id) ? intval($offers_id) : 0;
        if( !empty($offers_id) ){
            $offer_arg = array(
                'ID'           => $offers_id,
                'post_status'  => 'completed'
            );
            wp_update_post( $offer_arg );
        }
    }
    add_action('workreap_complete_task_order_activity', 'workreap_complete_task_order_activity');
}


/**
 * Cancelled offer
 *
 * @since    1.0.0
*/
if (!function_exists('workreap_after_cancelled_task')) {
    function workreap_after_cancelled_task($order_id=0){
        $offers_id  = get_post_meta( $order_id, 'offers_id', true );
        $offers_id  = !empty($offers_id) ? intval($offers_id) : 0;
        if( !empty($offers_id) ){
            $offer_arg = array(
                'ID'           => $offers_id,
                'post_status'  => 'cancelled'
            );
            wp_update_post( $offer_arg );
        }
    }
    add_action('workreap_after_cancelled_task', 'workreap_after_cancelled_task');
}

/**
 * After dispute creation form employer
 *
 * @since    1.0.0
*/
if (!function_exists('workreap_after_dispute_creation')) {
    function workreap_after_dispute_creation($dispute_id=0){
        $dispute_order  = get_post_meta( $dispute_id, '_dispute_order', true);
        $dispute_order  = !empty($dispute_order) ? intval($dispute_order) : 0;
        $offers_id      = get_post_meta( $dispute_order, 'offers_id', true );
        $offers_id      = !empty($offers_id) ? intval($offers_id) : 0;
        if( !empty($offers_id) ){
            $offer_arg = array(
                'ID'           => $offers_id,
                'post_status'  => 'disputed'
            );
            wp_update_post( $offer_arg );
            update_post_meta( $offers_id, 'dispute_id', $dispute_id);
            update_post_meta( $dispute_id, 'offers_id', $offers_id);
        }
    }
    add_action('workreap_after_dispute_creation', 'workreap_after_dispute_creation');
}