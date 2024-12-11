<?php

/* contain oneSignal notifications functions */
require_once ( WORKREAPPLUGINPATH . 'one-signal/guzzle/vendor/autoload.php'); //Onesignal initialization
require_once ( WORKREAPPLUGINPATH . 'one-signal/functions.php'); //Onesignal initialization
require_once ( WORKREAPPLUGINPATH . 'one-signal/class-onesignal-notifications.php'); //Onesignal initialization
require_once ( WORKREAPPLUGINPATH . 'one-signal/class-onesignal-admin-notification.php'); //Onesignal initialization

/**
 * User current browser
 */
if (!function_exists('workreap_get_current_browser')) {
    function workreap_get_current_browser($current_agent = '')
    {
        $agent_current = strtolower($current_agent);
        $agent_current = " " . $agent_current;
        if (strpos($agent_current, 'chrome')) return 5;
        elseif (strpos($agent_current, 'safari')) return 7;
        elseif (strpos($agent_current, 'firefox')) return 8;
        elseif (strpos($agent_current, 'macoS')) return 9;
        elseif (strpos($agent_current, 'ios')) return 0;
        elseif (strpos($agent_current, 'android')) return 1;
        return 10;
    }
}

/**
 * OneSignal User Notifications enable
 */
if (!function_exists('workreap_onesingal_notification_allow')) {
    function workreap_onesingal_notification_allow($return = 'yes', $args = array())
    {
        $defaults = array(
            'notify_hook' => '',
            'link_id' => 0,
            'user_id' => 0,
            'user_role' => ''
        );

        $args = wp_parse_args($args, $defaults);
        extract($args);

        if (!empty($notify_hook) && !empty($link_id)) {

            if ($user_role == 'employer') {
                if ($notify_hook == 'post_service') {
                    $key     = '_service_posted_notification';
                } elseif ($notify_hook == 'admin_notification') {
                    $key     = '_admin_notification_';
                }
            } elseif ($user_role == 'freelancer') {
                if ($notify_hook == 'job_notification') {
                    $key     = '_job_posted_notification';
                } elseif ($notify_hook == 'freelancer_hired') {
                    $key     = '_hire_freelancer_notification';
                } elseif ($notify_hook == 'admin_notification') {
                    $key     = '_admin_notification_';
                }
            }

            if (!empty($key)) {
                $nofitication_enable    = get_post_meta($link_id, $key, true);

                if (!empty($nofitication_enable)) {
                    $return    = (!empty($nofitication_enable) && $nofitication_enable == 'on') ?  'yes' : 'no';
                    return $return;
                }
            }
        }
        return 'no';
    }
    add_filter('workreap_onesingal_notification_allow', 'workreap_onesingal_notification_allow', 10, 2);
}
