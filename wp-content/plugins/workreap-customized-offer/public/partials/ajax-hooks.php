<?php

/**
 * Redirect page
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_offers_cart_page')) {
    function workreap_offers_cart_page()
    {
        if( function_exists('workreap_verify_token') ){
            workreap_verify_token($_POST['security']);
        }

        $type               = !empty($_POST['type']) ? $_POST['type'] : '';
        $json['message']    = esc_html__('Hire for a task', 'customized-task-offer');
        
        if( !empty($type) ){
            $json['message_desc']   = esc_html__('You need to login to perfom this action.', 'customized-task-offer');
            session_start();
            $json['redirect']       = workreap_get_page_uri('registration');
            if(!empty($type) && $type === 'post_task'){
                $_SESSION["redirect_type"]  = 'post_task';
                $_SESSION["redirect_url"]   = workreap_get_page_uri('add_service_page');
            } else if(!empty($type) && $type === 'task_cart'){
                $page_url                   = !empty($_POST['page_url']) ? $_POST['page_url'] : '';
                $_SESSION["redirect_type"]  = 'task_cart';
                $_SESSION["redirect_url"]   = $page_url;
            } else if(!empty($type) && $type === 'task'){
                $page_url                   = !empty($_POST['page_url']) ? $_POST['page_url'] : '';
                $_SESSION["redirect_type"]  = 'task_cart';
                $_SESSION["redirect_url"]   = $page_url;
                $json['redirect']           = workreap_get_page_uri('login');
                $json['message']            = '';
                $json['message_desc']       = esc_html__('You must login as a employer to send a message to this freelancer.', 'customized-task-offer');
            }

            $json['type']           = 'success';
        } else {
            $json['type']           = 'error';
            $json['message_desc']   = esc_html__('Oops! you are not allowed to perfom this action', 'customized-task-offer');
        }
        wp_send_json($json);
    }
    add_action( 'wp_ajax_workreap_offers_cart_page', 'workreap_offers_cart_page' );
    add_action( 'wp_ajax_nopriv_workreap_offers_cart_page', 'workreap_offers_cart_page' );
}

/**
 * Download product
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_download_offer_zip_file')) {
    function workreap_download_offer_zip_file()
    {
        global $current_user;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if( function_exists('workreap_verify_token') ){
            workreap_verify_token($_POST['security']);
        }

        $json             = array();
        $product_id       = !empty($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $json['message']  = esc_html__('Download files','customized-task-offer');

        if( !empty($product_id)){
            $attachments_files  = get_post_meta( $product_id, '_offer_attachments',true );
            if( !empty( $attachments_files ) ){

                if( class_exists('ZipArchive') ){
                    $zip                = new ZipArchive();
                    $uploadspath	    = wp_upload_dir();
                    $folderRalativePath = $uploadspath['baseurl']."/downloads";
                    $folderAbsolutePath = $uploadspath['basedir']."/downloads";
                    wp_mkdir_p($folderAbsolutePath);
                    $rand	    = workreap_unique_increment(5);
                    $filename	= $rand.round(microtime(true)).'.zip';
                    $zip_name   = $folderAbsolutePath.'/'.$filename;
                    $zip->open($zip_name,  ZipArchive::CREATE);
                    $download_url	= $folderRalativePath.'/'.$filename;

                    foreach($attachments_files as $key => $value) {
                        $file_url	= $value['url'];
                        $response	= wp_remote_get( $file_url );
                        $filedata   = wp_remote_retrieve_body( $response );
                        $zip->addFromString(basename( $file_url ), $filedata);
                    }

                    $zip->close();
                } else {
                    $json['type']           = 'error';
                    $json['message']        = esc_html__('Oops', 'customized-task-offer');
                    $json['message_desc']   = esc_html__('Zip library is not installed on the server, please contact to hosting provider', 'customized-task-offer');
                    wp_send_json($json);
                }
            }

            $json['type']           = 'success';
            $json['attachment']     = workreap_add_http_protcol( $download_url );
            $json['message_desc']   = esc_html__('Your files have been donwloaded', 'customized-task-offer');
            wp_send_json($json);
        }
    }
    add_action( 'wp_ajax_workreap_download_offer_zip_file', 'workreap_download_offer_zip_file' );
}

/**
 * Offers checkout
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_offer_checkout')) {
    function workreap_offer_checkout()
    {
        global $current_user,$woocommerce;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        if( function_exists('workreap_verified_user') ) {
            workreap_verified_user();
        }

        if( function_exists('workreap_verify_token') ){
            workreap_verify_token($_POST['security']);
        }

        if ( !class_exists('WooCommerce') ) {
            return;
        }

        $data           = !empty($_POST['data']) ? $_POST['data'] : array();
        parse_str($data,$data);
        $wallet         = !empty($data['wallet']) ? esc_html($data['wallet']) : 0;
        $product_id     = !empty($data['id']) ? intval($data['id']) : 0;
        $offers_id      = !empty($data['offers_id']) ? intval($data['offers_id']) : 0;
        $task           = !empty($data['product_task']) ? $data['product_task'] : '';
        $subtasks       = array();
        $freelancer_id      = get_post_field( 'post_author', $offers_id );
        $plans 	        = get_post_meta($offers_id, 'workreap_product_plans', TRUE);
        $plans	        = !empty($plans) ? $plans : array();
        $user_balance   = !empty($current_user->ID) ? get_user_meta( $current_user->ID, '_employer_balance',true ) : '';
        $plan_price     = get_post_meta($offers_id, 'offer_price', true);
        $total_price    = $plan_price;

        if ( class_exists('WooCommerce') ) {
            $woocommerce->cart->empty_cart(); //empty cart before update cart
            $user_id        = $current_user->ID;
            $service_fee    = workreap_commission_fee($total_price);
            $admin_shares   = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
            $freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $total_price;

            if( !empty($wallet) && !empty($user_balance) && $user_balance < $total_price ){
                $cart_meta['wallet_price']		    = $user_balance;
            }
            $cart_meta['task_id']		    = $product_id;
            $cart_meta['offers_id']		    = $offers_id;
            $cart_meta['total_amount']		= $total_price;
            $cart_meta['task']		        = $task;
            $cart_meta['price']		        = $plan_price;
            $cart_meta['subtasks']		    = $subtasks;
            $cart_meta['employer_id']		    = $user_id;
            $cart_meta['freelancer_id']		    = $freelancer_id;
            $cart_meta['admin_shares']		= $admin_shares;
            $cart_meta['freelancer_shares']		= $freelancer_shares;
            $cart_meta['payment_type']      = 'tasks';
            $cart_meta['post_type']         = 'offers';
            $cart_data = array(
                'product_id'        => $product_id,
                'offers_id'         => $offers_id,
                'cart_data'         => $cart_meta,
                'price'             => $plan_price,
                'payment_type'      => 'tasks',
                'admin_shares'      => $admin_shares,
                'freelancer_shares'     => $freelancer_shares,
                'employer_id'          => $user_id,
                'freelancer_id'         => $freelancer_id,
                'post_type'         => 'offers',

            );
            
            $woocommerce->cart->empty_cart();
            $cart_item_data = apply_filters('workreap_service_checkout_cart_data',$cart_data);
            WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

            if( !empty($subtasks) ){
                foreach($subtasks as $subtasks_id){
                    WC()->cart->add_to_cart( $subtasks_id, 1 );
                }
            }

            if( !empty($wallet) && !empty($user_balance) && $user_balance >= $total_price ){
                workreap_place_order($current_user->ID,'task-wallet');
                $json['checkout_url']	= Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $current_user->ID, true);
            } else {
                $json['checkout_url']	= wc_get_checkout_url();
            }

            $json['type'] 		        = 'success';
            wp_send_json( $json );
        }
    }
    add_action( 'wp_ajax_workreap_offer_checkout', 'workreap_offer_checkout' );
}