<?php

/**
 * Path of files for
 * initializing oneSignal
 */
if (!function_exists('workreap_core_onesingal_init_paths')) {
    function workreap_core_onesingal_init_paths($path_arr = array())
    {
        /* if local host then path is */
        $whitelist = array('127.0.0.1', '::1');
        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            $site_url_parse         = wp_parse_url(WorkreapCoreURI);
            $site_url_parse         = !empty($site_url_parse['path']) ? ltrim($site_url_parse['path'], '/') : '';

            $path_arr['param_path']         = '/' . $site_url_parse . 'public/js/onesignal/';
            $path_arr['path']               = $site_url_parse . 'public/js/onesignal/OneSignalSDKWorker.js';
            $path_arr['updater_path']       = $site_url_parse . 'public/js/onesignal/OneSignalSDKWorker.js';
            
        } else {
            $site_url_parse         = wp_parse_url(WorkreapCoreURI);
            $site_url_parse         = !empty($site_url_parse['path']) ? ltrim($site_url_parse['path'], '/') : '';

            $path_arr['param_path']         = '/' . $site_url_parse . 'public/js/onesignal/';
            $path_arr['path']               = $site_url_parse . 'public/js/onesignal/OneSignalSDKWorker.js';
            $path_arr['updater_path']       = $site_url_parse . 'public/js/onesignal/OneSignalSDKWorker.js';
            
        }

        return apply_filters('extend_onesignal_files_path', $path_arr);
    }
    add_filter('workreap_core_onesingal_init_paths', 'workreap_core_onesingal_init_paths');
}

/**
 * Get notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_notification_settings')) {
    function workreap_get_notification_settings($key = '')
    {
        $notification_settings = array();
        if (!empty($key)) {
            $notification_settings = array(
                'freelancer' => array(
                    '_job_posted_notification'      => esc_html__('Enable/Disable notification on job post.', 'workreap'),
                    '_hire_freelancer_notification' => esc_html__('Enable/Disable notification on hired freelancer.', 'workreap'),
                ),
                'employer' => array(
                    '_service_posted_notification'  => esc_html__('Enable/Disable notification on service post.', 'workreap'),
                ),
            );
        }
        $settings    = apply_filters('workreap_filters_notifications_settings', $notification_settings);

        return !empty($settings[$key]) ? $settings[$key] : array();
    }
}

/**
 * Update notification settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_save_notification_settings')) {

    function workreap_save_notification_settings()
    {
        global $current_user;
        $user_identity   = $current_user->ID;
        $link_id         = workreap_get_linked_profile_id($user_identity);
        $user_type      = apply_filters('workreap_get_user_type', $user_identity);
        $json = array();

        if (function_exists('workreap_validate_privileges')) {
            //workreap_validate_privileges($link_id);
        }; //if user is not logged in then prevent

        //security check
        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json($json);
        }

        if (function_exists('workreap_is_demo_site')) {
            workreap_is_demo_site();
        }; //if demo site then prevent

        //update settings
        $settings         = workreap_get_notification_settings($user_type);
        if (!empty($settings)) {
            foreach ($settings as $key => $value) {
                $save_val     = !empty($_POST['settings'][$key]) ? $_POST['settings'][$key] : '';
                $db_val     = !empty($save_val) ?  $save_val : 'off';
                update_post_meta($link_id, $key, $db_val);
            }
        }

        $json = array(
            'type'      => 'success',
            'message'   => esc_html__('Notification settings Updated.', 'workreap')
        );

        wp_send_json($json);
    }
    add_action('wp_ajax_workreap_save_notification_settings', 'workreap_save_notification_settings');
}

/**
 * update usermeta with player id
 */
if (!function_exists('workreap_update_user_playerId')) {
    function workreap_update_user_playerId($playerID = '', $platform_type = '')
    {
        if (empty($playerID)) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Player ID is missing!', 'workreap');
            wp_send_json($json);
        } else {
            global $current_user;
            $player_ids  = get_user_meta($current_user->ID, 'user_playerID_onesignal', true);
            if (!empty($player_ids) && !is_array($player_ids)) {
                $player_ids = array();
            }

            $player_ids[]  = $playerID;
            //update user meta with player ID
            update_user_meta($current_user->ID, 'user_playerID_onesignal', $player_ids);
        }
    }
}

/**
 * Save player id
 */
if (!function_exists('workreap_save_and_delete_player_with_userId')) {

    function workreap_save_and_delete_player_with_userId()
    {
        global $current_user;

        $playerId   = !empty($_POST['playerId']) ? sanitize_text_field($_POST['playerId']) : '';
        $subscription   = !empty($_POST['subscription']) ? sanitize_text_field($_POST['subscription']) : '';


        if (empty($playerId)) {
            $json['type']       = 'error';
            $json['message']    = esc_html__('Player ID is missing!', 'workreap');
            wp_send_json($json);
        } else {
            //update user meta with player ID
            global $current_user;
            $player_ids = array();

            $player_ids  = get_user_meta($current_user->ID, 'user_playerID_onesignal', true);
            $player_ids     = !empty($player_ids) ? $player_ids : array();

            if (!empty($subscription) && $subscription === 'unsubscribe') {
                if (!empty($player_ids)) {
                    $key_value = array_search($playerId, $player_ids);
                    if (false !== $key_value) {
                        unset($player_ids[$key_value]);
                    }

                    //update user meta with player ID
                    update_user_meta($current_user->ID, 'user_playerID_onesignal', $player_ids);
                    $json['action'] = 'removed';
                    $message        = esc_html__('Player ID is removed!', 'servento');
                }
            } else {

                if (!empty($player_ids) && !is_array($player_ids)) {
                    $player_ids = array();
                }

                if (!is_array($player_ids)) {
                    $player_ids = array();
                }

                if (!in_array($playerId, $player_ids)) {
                    array_push($player_ids, $playerId);
                }
                
                array_unique($player_ids);

                //update user meta with player ID
                update_user_meta($current_user->ID, 'user_playerID_onesignal', $player_ids);
                $json['action'] = 'added';
                $message        = esc_html__('Player ID is added!', 'servento');
            }

            $json['type']           = 'success';
            $json['playerId']       = $playerId;
            $json['message']        = $message;
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_workreap_save_and_delete_player_with_userId', 'workreap_save_and_delete_player_with_userId');
}

/**
 * Save player id
 */
if (!function_exists('workreap_remove_player_with_userId')) {

    function workreap_remove_player_with_userId()
    {
        global $current_user;

        $playerId   = !empty($_POST['playerId']) ? sanitize_text_field($_POST['playerId']) : '';
        if (empty($playerId)) {
            $json['type']       = 'error';
            $json['message']    = esc_html__('Player ID is missing!', 'workreap');
            wp_send_json($json);
        } else {
            //update user meta with player ID

            global $current_user;
            $player_ids = array();
            $player_ids  = get_user_meta($current_user->ID, 'user_playerID_onesignal', true);
            if (!empty($player_ids) && !is_array($player_ids)) {
                $player_ids = array();
            }

            if (!is_array($player_ids)) {
                $player_ids = array();
            }

            if (in_array($playerId, $player_ids)) {
                if (($key = array_search($playerId, $player_ids)) !== false) {
                    unset($player_ids[$key]);
                }
            }

            array_unique($player_ids);

            //update user meta with player ID
            update_user_meta($current_user->ID, 'user_playerID_onesignal', $player_ids);
            $json['type']       = 'success';
            $json['playerId']       = $playerId;
            $json['message']    = esc_html__('Player ID is removed!', 'workreap');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_workreap_remove_player_with_userId', 'workreap_remove_player_with_userId');
}
