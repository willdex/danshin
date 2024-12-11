<?php

/**
 * Authentication of API's
 *
 * @since   2.3
 */
function api_authentication($params)
{
    $json           = array();
    $type           = 'success';
    $message        = '';
    if (empty($params['user_id']) || empty(get_userdata($params['user_id']))) {
        $type               = 'error';
        $json['title']      = esc_html__('Restricted access!', 'workreap_api');
        $message            = esc_html__('You are not allowed to perform this action!!', 'workreap_api');
    } else {
        /* check purchase code */
        $whitelist      = ['127.0.0.1', '::1'];
        $options        = get_option('workreap_verify_settings');
        // $verified       = !empty($options['verified']) ? $options['verified'] : '';
        $is_localhost   = in_array($_SERVER['REMOTE_ADDR'], $whitelist);
        $verified   = 'yes';
        if (empty($verified) && empty($is_localhost) && (!in_array($_SERVER["SERVER_NAME"], array('amentotech.com', 'wp-guppy.com', 'houzillo.com')))) {
            $type               = 'error';
            $json['title']      = esc_html__('Restricted access!', 'workreap_api');
            $message            = esc_html__('You are not allowed to perform this action. Please activate plugin license.', 'workreap_api');
        } else {
            /* JWT authentication */
            $obj            = new WORKREAPAPI_JWT(WorkreapAppGlobalSettings::get_plugin_name(), WorkreapAppGlobalSettings::get_plugin_verion());
            $response       = $obj->verifyToken($params);
            $type           = $response['type'];
            $message        = $response['message_desc'];
        }
    }

    $json['type']       = $type;
    $json['title']      = esc_html__('Failed!', 'workreap_api');
    $json['message']    = $message;
    return $json;
}

/**
 * Workreap API 
 */
if (!function_exists('workreap_api_is_demo')) {
    function workreap_api_is_demo()
    {
        $json       = array();
        $message    = esc_html__("Sorry! you are restricted to perform this action on demo site.", 'workreap_api');
        $domains    = array('amentotech.com', 'wp-guppy.com', 'houzillo.com');

        if (isset($_SERVER["SERVER_NAME"]) && in_array($_SERVER["SERVER_NAME"], $domains)) {
            $json['type']       =  "error";
            $json['message']    =  $message;
            return new WP_REST_Response($json, 203);
        }
    }
}


/**
 * add protocol to uploaded media
 */
if(!function_exists('workreap_add_protocol')){
    function workreap_add_protocol($media_obj){
        if(!empty($media_obj)){
            foreach($media_obj as $key=>$val){
                if( $key === "url" ){
                    $media_obj[$key] = workreap_add_http($val);
                }
            }
        }
        return $media_obj;
    }
}