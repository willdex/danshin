<?php

use GuzzleHttp\Client;

if (!class_exists('OneSignal_Push_Notification')) {
    class OneSignal_Push_Notification
    {
        private $enable_onesignal   = '';
        private $OneSignalAPPID     = '';
        private $OneSignalAPIKEY    = '';
        private $safari_web_id      = '';

        public function __construct()
        {
            add_action('wp', array(&$this, 'api_settings'), 1);
            add_action('wp_head', array(&$this, 'init_onesignal'), 10);
            add_action('wp_footer', array(&$this, 'oneSignal_update_player_ids'), 99);
            add_action('workreap_onesingal_push_notify', array(&$this, 'workreap_onesignal_notify'), 10);
            add_action('workreap_onesignal_admin_notification', array(&$this, 'workreap_onesignal_admin_notify'), 99);
        }

        /**
         * Get oneSignal app keys
         */
        public function api_settings()
        {
            $enable_onesignal = 'no';
            $oneSignal_instance_id = '';
            $oneSignal_api_key = '';
            $safari_web_id = '';
            if(function_exists('fw_get_db_settings_option')){
                $enable_onesignal           = fw_get_db_settings_option('enable_onesignal');
                $oneSignal_instance_id      = fw_get_db_settings_option('oneSignal_instance_id');
                $oneSignal_api_key          = fw_get_db_settings_option('oneSignal_api_key');
                $safari_web_id              = fw_get_db_settings_option('safari_web_id');
            }
            

            $this->enable_onesignal     = $enable_onesignal;
            $this->OneSignalAPPID       = $oneSignal_instance_id;
            $this->OneSignalAPIKEY      = $oneSignal_api_key;
            $this->safari_web_id        = $safari_web_id;
        }

        /**
         * initialize oneSignal app
         */
        public function init_onesignal()
        {
            if (is_user_logged_in()) {
                global $current_user;

                if (empty($this->OneSignalAPPID) || empty($this->OneSignalAPIKEY) || $this->enable_onesignal !== 'yes') {
                    return;
                }

                $files_path = apply_filters('workreap_core_onesingal_init_paths', array());
            ?>
                <script>
                    window.OneSignal = window.OneSignal || [];
                    var initConfig = {
                        appId: "<?php echo esc_attr($this->OneSignalAPPID); ?>",
                        safari_web_id: "<?php echo esc_attr($this->safari_web_id); ?>",
                        notifyButton: {
                            enable: true
                        },
                        allowLocalhostAsSecureOrigin: true, //only for localhost
                    };

                    OneSignal.push(function() {
                        OneSignal.SERVICE_WORKER_PARAM = {
                            scope: '<?php echo $files_path['param_path']; ?>'
                        };
                        OneSignal.SERVICE_WORKER_PATH = '<?php echo $files_path['path']; ?>'
                        OneSignal.SERVICE_WORKER_UPDATER_PATH = '<?php echo $files_path['updater_path']; ?>'
                        OneSignal.init(initConfig);
                    });
                </script>
            <?php
            }
        }

        /**
         * Save user player ids
         */
        public function oneSignal_update_player_ids()
        {
            global $current_user;
            if (empty($this->OneSignalAPPID) || empty($this->OneSignalAPIKEY) || $this->enable_onesignal !== 'yes') {
                return;
            }

            ?>
            <script>
                let subscription = 'subscribe';
                let useragentid = '';
                OneSignal.push(function() {
                    /* These examples are all valid */
                    var isPushSupported = OneSignal.isPushNotificationsSupported();
                    if (isPushSupported) {
                        console.log('isPushSupported : ', isPushSupported);
                        OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                            console.log('isPushEnabled', isEnabled);
                            if (isEnabled) {
                                let externalUserId = "wt_<?php echo intval($current_user->ID); ?>";
                                OneSignal.push(function() {
                                    OneSignal.setExternalUserId(externalUserId);
                                });

                                OneSignal.getUserId().then(function(playerId) {
                                    useragentid = playerId;
                                    if (useragentid) {
                                        //ajax call
                                        jQuery.ajax({
                                            type: "POST",
                                            url: scripts_vars.ajaxurl,
                                            data: {
                                                action: 'workreap_save_and_delete_player_with_userId',
                                                playerId: useragentid,
                                                subscription: subscription,
                                                security: scripts_vars.ajax_nonce
                                            },
                                            success: function(response) {
                                                console.log('Playerid!', response);
                                                if (response.type == 'success') {
                                                    if (response.action == 'removed') {
                                                        console.log(response.message);
                                                    } else {
                                                        console.log(response.message);
                                                    }
                                                }
                                            }
                                        });
                                    }

                                }).then(function() {
                                    // this is custom function
                                    // here you can send post request to php file as well.
                                });

                            }
                        });
                    } else {
                        console.log('Not supported');
                    }

                    /**
                     * if subscription changed
                     *  */
                    OneSignal.on('subscriptionChange', function(isSubscribed) {
                        console.log('isSubscribed=', isSubscribed);
                        subscription = 'unsubscribe';
                        if (isSubscribed === true) {
                            subscription = 'subscribe';
                        } else {
                            OneSignal.removeExternalUserId();
                        }

                        OneSignal.getUserId().then(function(playerId) {
                            useragentid = playerId;

                            if (useragentid) {
                                //ajax call
                                jQuery.ajax({
                                    type: "POST",
                                    url: scripts_vars.ajaxurl,
                                    data: {
                                        action: 'workreap_save_and_delete_player_with_userId',
                                        playerId: useragentid,
                                        subscription: subscription,
                                        security: scripts_vars.ajax_nonce
                                    },
                                    success: function(response) {
                                        if (response.type == 'success') {
                                            if (response.action == 'removed') {
                                                console.log(response.message);
                                            } else {
                                                console.log(response.message);
                                            }
                                        }
                                    }
                                })
                            }

                        }).then(function() {
                            // this is custom function
                            // here you can send post request to php file as well.
                        });
                    });

                });
            </script>
        <?php
        }

        /**
         * Send onesignal notifications
         */
        public function workreap_onesignal_notify($args = array())
        {
            global $current_user;
            $defaults = array(
                'user_ids'          => $current_user->ID,
                'push_post_id'      => 0,
                'title'             => '',
                'message_key'       => '',
                'message_template'  => '',
                'data'              => array(),
            );

            $args = wp_parse_args($args, $defaults);
            extract($args);

            $this->api_settings();

            $user_id                        = $user_ids;
            $debug_data                     = $args;
            $debug_data['OneSignalAPPID']   = $this->OneSignalAPPID;
            $debug_data['OneSignalAPIKEY']  = $this->OneSignalAPIKEY;
            $debug_data['enable_onesignal'] = $this->enable_onesignal;

            if (!empty($this->OneSignalAPPID) && !empty($this->OneSignalAPIKEY) && $this->enable_onesignal == 'yes') {
                $message        = !empty($message_key) ? wp_strip_all_tags($message_key, true) : '';
                $subtitle       = !empty($title) ? $title : '';
                $title          = !empty($title) ? $title : esc_html__('Notification', 'workreap') . ' ' . workreap_unique_increment(8);
                $sender         = !empty($data['sender_id']) ? $data['sender_id'] : $user_ids;
                $notify_hook    = !empty($data['type']) ? $data['type'] : '';

                $player_ids                 = array();
                $include_external_user_ids  = array();
                $link_id                    = workreap_get_linked_profile_id($user_id);
                $user_role                  = apply_filters('workreap_get_user_type', $user_id);

                $enable_oneSingal_notify    = 'yes';
                $debug_data['enable_oneSingal_notify']   = $enable_oneSingal_notify;

                if ($enable_oneSingal_notify === 'yes') {
                    $include_external_user_ids  = array();
                    if (!empty($user_ids)) {
                        $include_external_user_ids[]  = "wt_$user_ids";
                    }
                    $player_ids     = get_user_meta($user_ids, 'user_playerID_onesignal', true);
                    $player_ids     = (!empty($player_ids) && is_array($player_ids)) ? array_values($player_ids) : array();
                    
                    try {

                        $campaign_name   = $title;
                        $request_body = [
                            "channel_for_external_user_ids" => "push",
                            'include_player_ids'            => $player_ids,
                            'name'                          => $campaign_name,
                            'app_id'                        => $this->OneSignalAPPID,
                            'include_external_user_ids'     => $include_external_user_ids,
                            'data'                          => ["screen_data" => $data],
                            'headings' => [
                                "en" => get_bloginfo('name')
                            ],
                            'contents' => [
                                "en" => $message
                            ],
                        ];

                        $debug_data['request_body']   = $request_body;

                        $client = new \GuzzleHttp\Client();
                        // $client = new \GuzzleHttp\Client([ 'verify' => false, ]); // in case of localhost testing
                        $response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                            'body' => json_encode($request_body),
                            'headers' => [
                                'Accept'            => 'application/json',
                                'Authorization'     => 'Basic ' . $this->OneSignalAPIKEY,
                                'Content-Type'      => 'application/json',
                            ],
                        ]);

                        $onesignal_data         = $response->getBody();
                        $data_                  = json_decode($onesignal_data);
                        $debug_data['data_']    = $data_;

                        update_post_meta($push_post_id, 'oneSignalpublishId', $data_->id);
                        update_post_meta($push_post_id, 'oneSignalresponse', $data_);
                        do_action('workreap_onesignal_notify_success', $data_, $push_post_id);
                    } catch (exception $e) {
                        /* code to handle the exception */
                        $debug_data['error']   = $e->getMessage();
                        update_post_meta($push_post_id, 'oneSignalresponse_error', $e->getMessage());
                        do_action('workreap_onesignal_notify_error', $e, $push_post_id);
                    }
                }
            }
            update_post_meta($push_post_id, 'debug_data', $debug_data);
        }

        /**
         * Send onesignal notifications from admin side
         */
        public function workreap_onesignal_admin_notify($data = array())
        {
            global $current_user;
            $title      = !empty($data['title']) ? $data['title'] : '';
            $message    = !empty($data['message']) ? $data['message'] : '';
            if (empty($title) || empty($message)) {
                return;
            }

            $user_id    = $current_user->ID;
            $this->api_settings();

            try {
                $campaign_name   = $title;
                $request_body = [
                    'included_segments'     => ["Subscribed Users"],
                    'app_id'                => $this->OneSignalAPPID,
                    'name'                  => $campaign_name,
                    'headings' => [
                        "en" => get_bloginfo('name')
                    ],
                    'contents' => [
                        "en" => $message
                    ],
                ];
                if (!empty($subtitle)) {
                    $request_body['subtitle'] = [
                        "en" => $subtitle
                    ];
                }

                $debug_data['request_body']   = $request_body;

                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                    'body' => json_encode($request_body),
                    'headers' => [
                        'Accept'            => 'application/json',
                        'Authorization'     => 'Basic ' . $this->OneSignalAPIKEY,
                        'Content-Type'      => 'application/json',
                    ],
                ]);

                $onesignal_data         = $response->getBody();
                $data_                  = json_decode($onesignal_data);
                $debug_data['data_']    = $data_;
                
                $admin_notifications = get_user_meta($user_id, 'admin_onesignal_notifications', true);
                if (empty($admin_notifications) || !is_array($admin_notifications)) {
                    $admin_notifications = array();
                }

                $admin_notifications[$data_->id] = array(
                    'data'          => $data_,
                    'datetime'      => date("Y-m-d H:i:s"),
                );

                update_user_meta($user_id, 'admin_onesignal_notifications', $admin_notifications);
                do_action('workreap_onesignal_segments_notify_success', $data_, $user_id);
            } catch (exception $e) {
                //code to handle the exception
                $debug_data['error']    = $e->getMessage();
                $admin_notifications    = get_user_meta($user_id, 'admin_onesignal_errors', true);
                if (empty($admin_notifications) || !is_array($admin_notifications)) {
                    $admin_notifications = array();
                }

                $admin_notifications[] = array(
                    'data' => $e->getMessage(),
                    'datetime' => date("Y-m-d H:i:s"),
                );
                update_user_meta($user_id, 'admin_onesignal_errors', $e->getMessage());
                do_action('workreap_onesignal_segments_notify_error', $e, $user_id);
            }
        }

        /**
         * OneSignal create segments
         */
        public function workreap_onesignal_create_segments()
        {
            global $current_user;
            /* current user already have segments or not */
            $user_segmant_data = get_user_meta($current_user->ID, 'user_segment', true);
            if (empty($user_segmant_data['name'])) {
                $name = 'localtest_' . $current_user->ID;
                $segment    = array(
                    'name'  => $name,
                    'filters'  => [
                        ['field' => 'device_type', 'relation' => '=', 'value' => 'iOS'],
                        ['operator' => 'OR'],
                        ['field' => 'device_type', 'relation' => '=', 'value' => 'Android'],
                        ['operator' => 'OR'],
                        ['field' => 'device_type', 'relation' => '=', 'value' => 'Web Push (Browser)'],
                    ]
                );

                try {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('POST', 'https://onesignal.com/api/v1/apps/' . $this->OneSignalAPPID . '/segments', [
                        'body' => json_encode($segment),
                        'headers' => [
                            'Accept'        => 'application/json',
                            'Authorization' => 'Basic ' . $this->OneSignalAPIKEY,
                            'Content-Type'  => 'application/json; charset=utf-8',
                        ],
                    ]);

                    $data = $response->getBody();

                    $user_segment   = [
                        'name'          => $name,
                        'response'      => json_decode($data)
                    ];

                    update_user_meta($current_user->ID, 'user_segment', $user_segment);
                } catch (exception $e) {
                    print_r($e->getMessage());
                }
            }
        }
    }

    new OneSignal_Push_Notification();
}
