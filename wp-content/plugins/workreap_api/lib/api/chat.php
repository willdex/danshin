<?php

/**
 * APP API to manage chat
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           WorkreapAppApi
 *
 */
if (!class_exists('AndroidApp_Chat_Route')) {

    class AndroidApp_Chat_Route extends WP_REST_Controller
    {

        /**
         * Register the routes for the chat.
         */
        public function register_routes()
        {
            $version     = '1';
            $namespace     = 'api/v' . $version;
            $base         = 'chat';

            //user login
            register_rest_route(
                $namespace,
                '/' . $base . '/send_offer',
                array(
                    array(
                        'methods'     => WP_REST_Server::CREATABLE,
                        'callback'     => array(&$this, 'send_offer'),
                        'args'         => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            //Send user message
            register_rest_route(
                $namespace,
                '/' . $base . '/sendUserMessage',
                array(
                    array(
                        'methods'     => WP_REST_Server::CREATABLE,
                        'callback'     => array(&$this, 'sendUserMessage'),
                        'args'         => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            register_rest_route(
                $namespace,
                '/' . $base . '/employer_jobs',
                array(
                    array(
                        'methods'     => WP_REST_Server::READABLE,
                        'callback'     => array(&$this, 'employer_jobs'),
                        'args'         => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            register_rest_route(
                $namespace,
                '/' . $base . '/list_users',
                array(
                    array(
                        'methods'     => WP_REST_Server::READABLE,
                        'callback'     => array(&$this, 'list_users'),
                        'args'         => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            register_rest_route(
                $namespace,
                '/' . $base . '/list_user_messages',
                array(
                    array(
                        'methods'     => WP_REST_Server::READABLE,
                        'callback'     => array(&$this, 'list_user_messages'),
                        'args'         => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            register_rest_route(
                $namespace,
                '/' . $base . '/send_user_attachment',
                array(
                    array(
                        'methods'     => WP_REST_Server::CREATABLE,
                        'callback'     => array(&$this, 'send_user_attachment'),
                        'args'         => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );
        }

        /**
         * List single user messages
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function list_user_messages($request)
        {
            $json               = array();
            $senderID           = !empty($request['current_id']) ? intval($request['current_id']) : '';
            $receiverID         = !empty($request['reciver_id']) ? intval($request['reciver_id']) : '';
            $lastMsgId          = !empty($request['msg_id']) ? intval($request['msg_id']) : '';
            $thread_page        = !empty($request['page_number']) ? intval($request['page_number']) : 0;
            if ($receiverID != $senderID) {
                $usersThreadData = ChatSystem::getUsersThreadListData($senderID, $receiverID, 'fetch_thread_last_items', array(), '', $thread_page);
                /* Update Chat Status in DB */
                ChatSystem::getUsersThreadListData($senderID, $receiverID, 'set_thread_status', array(), '');
                /* Prepare Chat Nodes */
                $chat_nodes     = $chat_sidebar   = array();
                if (!empty($usersThreadData)) {
                    foreach ($usersThreadData as $key => $val) {
                        $chat_is_sender = 'no';
                        if ($val['sender_id'] == $senderID) {
                            $chat_is_sender = 'yes';
                        }

                        $timestamp                  = !empty($val['timestamp']) ? $val['timestamp'] : '';
                        $date                       = !empty($val['time_gmt']) ?  date_i18n(get_option('date_format'), strtotime($val['time_gmt'])) : '';
                        $chat_avatar                = ChatSystem::getUserInfoData('avatar', $val['sender_id'], array('width' => 100, 'height' => 100));
                        $chat_username              = ChatSystem::getUserInfoData('username', $val['sender_id'], array());
                        $chat_message               = $val['chat_message'];
                        $chat_date                  = $date;
                        $chat_id                    = intval($val['id']);
                        $chat_current_user_id       = intval($senderID);

                        $chat_nodes[] = array(
                            'chat_is_sender'        => $chat_is_sender,
                            'chat_avatar'           => $chat_avatar,
                            'chat_username'         => $chat_username,
                            'chat_message'          => $chat_message,
                            'chat_date'             => $chat_date,
                            'timestamp'             => $timestamp,
                            'chat_id'               => $chat_id,
                            'chat_current_user_id'  => $chat_current_user_id,
                        );
                    }

                    //Create Chat Sidebar Data
                    $avatar             = ChatSystem::getUserInfoData('avatar', $receiverID, array('width' => 100, 'height' => 100));
                    $username           = ChatSystem::getUserInfoData('username', $receiverID, array());
                    $user_register      = ChatSystem::getUserInfoData('user_register', $receiverID, array());
                    $chat_sidebar = array(
                        'avatar'            => $avatar,
                        'username'          => $username,
                        'user_register'     => $user_register
                    );
                }

                $json['chat_nodes']                 = $chat_nodes;
                $json['chat_receiver_id']           = intval($receiverID);
                $json['chat_sender_id']             = intval($senderID);
                $json['chat_sidebar']               = $chat_sidebar;
                return new WP_REST_Response($json, 200);
            }
        }

        /**
         * List users messages
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function sendUserMessage($data)
        {
            $json                    = array();
            $headers                = $data->get_headers();
            $request                 = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']      = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response                 = api_authentication($request);

            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $sender_id      = !empty($request['user_id']) ? intval($request['user_id']) : '';
            $receiver_id    = !empty($request['receiver_id']) ? intval($request['receiver_id']) : '';
            $status         = !empty($request['status']) && esc_attr($request['status']) === 'read' ? 0 : 1;
            $message        = !empty($request['message']) ? esc_textarea($request['message']) : '';

            if (empty($receiver_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('No kiddies please.', 'workreap_api');
                $json               = maybe_unserialize($json);
                return new WP_REST_Response($json, 203);
            }

            if (empty($sender_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('User ID is required.', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            if (empty($message)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Message field is required.', 'workreap_api');
                $json               = maybe_unserialize($json);
                return new WP_REST_Response($json, 203);
            }

            $current_time  = current_time('mysql');
            $gmt_time      = get_gmt_from_date($current_time);

            $insert_data = array(
                'sender_id'     => $sender_id,
                'receiver_id'   => $receiver_id,
                'chat_message'  => $message,
                'status'        => $status,
                'timestamp'     => time(),
                'time_gmt'      => $gmt_time,
            );

            $msg_id = ChatSystem::getUsersThreadListData($sender_id, $receiver_id, 'insert_msg', $insert_data, '');
            if (!empty($msg_id)) {
                $json['type']       = 'success';
                $json['message']    = esc_html__('Your message has been sent successfully', 'workreap_api');
                $json['msg_id']     = $msg_id;
                $message_data   = $message;

                /* Push notification */
                $push                   = array();
                $push['sender_id']      = $sender_id;
                $push['receiver_id']    = $receiver_id;
                $push['%username%']     = workreap_get_username($receiver_id);
                $push['%sender_name%']  = workreap_get_username($sender_id);
                $push['%message%']      = $message_data;
                $push['type']           = 'inbox_message';
                $push['%replace_message%']  = $message_data;

                do_action('workreap_user_push_notify', array($receiver_id), '', 'pusher_rec_chat_content', $push);

                $json = maybe_unserialize($json);
                return new WP_REST_Response($json, 200);
            } else {
                $json['type']   = 'error';
                $json['message'] = esc_html__('Something went wrong.!', 'workreap_api');
                $json['msg_id'] = esc_html__('Empty', 'workreap_api');
                $json = maybe_unserialize($json);
                return new WP_REST_Response($json, 203);
            }
        }
        /**
         * List users messages
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function list_users($request)
        {
            $user_id    = !empty($request['user_id']) ? intval($request['user_id']) : 0;

            if (!empty($user_id)) {
                $chat_messages  = $json = array();
                $usersdata      = ChatSystem::getUsersThreadListData($user_id, '', 'list', array(), '');

                if (!empty($usersdata)) {
                    foreach ($usersdata as $key => $users) {
                        $is_online      = apply_filters('workreap_is_user_online', $users['sender_id']);
                        $is_online      = !empty($is_online) ? $is_online : false;
                        $msg_id          = !empty($users['id']) ? intval($users['id']) : 0;
                        $chat_message    = !empty($users['chat_message']) ? sanitize_textarea_field($users['chat_message']) : '';
                        $message_status = !empty($users['status']) ? $users['status'] : '';

                        if ($user_id === intval($users['sender_id'])) {
                            $chat_user_id = intval($users['receiver_id']);
                        } else {
                            $chat_user_id = intval($users['sender_id']);
                        }

                        $receiver_id        = $chat_user_id;
                        $userAvatar         = ChatSystem::getUserInfoData('avatar', $chat_user_id, array('width' => 100, 'height' => 100));
                        $userName           = ChatSystem::getUserInfoData('username', $chat_user_id, array());
                        $userUrl            = ChatSystem::getUserInfoData('url', $chat_user_id, array());
                        $count              = ChatSystem::getUsersThreadListData($chat_user_id, $user_id, 'count_unread_msgs_by_user');
                        $unread             = !empty($count) ? $count : 0;
                        $image_url          = !empty($userAvatar) ? esc_url($userAvatar) : '';
                        $user_name          = !empty($userName) ? esc_attr($userName) : '';
                        $unread_count       = !empty($unread) ? intval($unread) : 0;

                        $chat_messages[] = array(
                            'user_id'           => $chat_user_id,
                            'msg_id'            => $msg_id,
                            'chat_message'      => $chat_message,
                            'message_status'    => $message_status,
                            'receiver_id'       => $receiver_id,
                            'image_url'         => $image_url,
                            'user_name'         => $user_name,
                            'unread_count'      => $unread_count,
                            'is_online'         => $is_online
                        );
                    }
                }

                $json['chats']      = ($chat_messages);
                return new WP_REST_Response($json, 200);
            } else {
                $json['type']           = "error";
                $json['message']        = esc_html__('User ID is required field.', 'workreap_api');
                $json['chats']          = array();
                $json['is_online']      = '';
                return new WP_REST_Response($json, 203);
            }
        }
        /**
         * Get job listing by employer
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function employer_jobs($data)
        {
            $json                       = array();
            $headers                    = $data->get_headers();
            $request                    = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']       = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response                   = api_authentication($request);

            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $employer_id = !empty($request['user_id']) ? intval($request['user_id']) : '';

            if (!empty($employer_id)) {
                $user_type  = apply_filters('workreap_get_user_type', $employer_id);
                $projects   = $project    = array();
                if ($user_type === 'employer') {
                    $args = array(
                        'author'            => $employer_id,
                        'orderby'           => 'post_date',
                        'post_type'         => 'projects',
                        'post_status'       => array('publish', 'pending'),
                        'order'             => 'ASC',
                        'posts_per_page'    => -1 // no limit
                    );
                    $projects = get_posts($args);
                    if (!empty($projects) && count($projects) > 0) {
                        $count_project    = 0;
                        foreach ($projects as $job) {
                            $count_project++;
                            $project[$count_project]['id']       = intval($job->ID);
                            $project[$count_project]['title']    = esc_attr($job->post_title);
                        }
                        $project    = array_values($project);
                    }
                    $json['type']       = "success";
                    $json['projects']   = $project;
                    $json               = maybe_unserialize($json);
                    return new WP_REST_Response($json, 200);
                } else {
                    $json['type']        = "error";
                    $json['message']     = esc_html__("User type is invalid.", 'workreap_api');
                    return new WP_REST_Response($json, 203);
                }
            } else {
                $json['type']        = "error";
                $json['message']     = esc_html__("User id is required.", 'workreap_api');
                return new WP_REST_Response($json, 203);
            }
        }

        /**
         * Send offer to freelancer
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request
         */
        public function send_offer($data)
        {
            $json                   = array();
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response               = api_authentication($request);
            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $receiver_id    = !empty($request['receiver_id']) ? intval($request['receiver_id']) : '';
            $senderId       = !empty($request['user_id']) ? intval($request['user_id']) : '';
            $project_id     = !empty($request['project_id']) ? intval($request['project_id']) : '';
            $status         = !empty($request['status']) && esc_attr($request['status']) === 'read' ? 0 : 1;
            $message        = !empty($request['message']) ? esc_textarea($request['message']) : '';

            if (empty($receiver_id)) {
                $json['type']         = 'error';
                $json['message']     = esc_html__('Freelancer ID is required.', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            if (empty($senderId)) {
                $json['type']         = 'error';
                $json['message']     = esc_html__('User ID is required.', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            if (empty($message)) {
                $json['type']         = 'error';
                $json['message']     = esc_html__('Message field is required.', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            $receiverId = intval($receiver_id);

            //Prepare Insert Message Data Array
            $current_time  = current_time('mysql');
            $gmt_time      = get_gmt_from_date($current_time);

            if (!empty($project_id)) {
                $link           = get_the_permalink($project_id);
                $message        = $message . ' ' . $link;
            }

            $insert_data = array(
                'sender_id'     => $senderId,
                'receiver_id'   => $receiverId,
                'chat_message'  => $message,
                'status'        => $status,
                'timestamp'     => time(),
                'time_gmt'      => $gmt_time,
            );

            $msg_id = ChatSystem::getUsersThreadListData($senderId, $receiverId, 'insert_msg', $insert_data, '');

            if (!empty($msg_id)) {
                $json['type']       = 'success';
                $json['message']    = esc_html__('Your offer has submitted', 'workreap_api');
                $json['msg_id']     = $msg_id;
                return new WP_REST_Response($json, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occurs, please try again later', 'workreap_api');
                $json['msg_id'] = esc_html__('Empty', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }
        }

        /**
         * Send offer to freelancer
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request 
         */
        public function send_user_attachment($data)
        {
            $json                   = array();
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response               = api_authentication($request);
            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $user_id        = !empty($request['user_id']) ? intval($request['user_id']) : '';
            $receiver_id    = !empty($request['receiver_id']) ? intval($request['receiver_id']) : '';
            $msg_type       = !empty($request['msg_type']) && esc_attr($request['msg_type']) ? $request['msg_type'] : '';
            $file_data      = !empty($request['file_info']) ? $request['file_info'] : true;

            if (empty($receiver_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Receiver user ID is missing', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            if (intval($receiver_id) === intval($user_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Something went wrong.', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            if (empty($file_data)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('File is missing.', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            //====================
            if (!empty($file_data)) {

                $file_data = $_FILES['file_info']; print_r($file_data);
                //rename file to server
                $upload             = wp_upload_dir();
                $upload_url         = $upload['baseurl'];
                $upload_dir         = $upload['basedir'];
                $filename           = $file_data['file_name'];
                $file               = $upload_url . '/chat_attachments/' . $filename;

                $file_detail            = pathinfo($file);
                $extension              = $file_detail['extension'];
                $filename               = $filename;
                $reverse_file_name      = strrev($filename);
                $timestamp              = current_time('timestamp');
                $new_file               = strrev(base64_encode($reverse_file_name . '^^wtkeychat_attachment^^' . $timestamp));
                $new_file_name          = $new_file . '.' . $extension;
                $old_name               = $upload_dir . '/chat_attachments/' . $file_data['file_name'];
                $name_name              = $upload_dir . '/chat_attachments/' . $new_file_name;
                rename($old_name, $name_name);

                $file_data['chat_hashname']    = $new_file_name;
                $file_data['timestamp']        = $timestamp;

                $senderId   = $user_id;
                $receiverId = intval($receiver_id);

                //Prepare Insert Message Data Array
                $current_time  = current_time('mysql');
                $gmt_time      = get_gmt_from_date($current_time);
                
                $insert_data = array(
                    'sender_id'         => $senderId,
                    'receiver_id'         => $receiverId,
                    'chat_message'         => serialize($file_data),
                    'status'             => 1,
                    'timestamp'         => time(),
                    'time_gmt'             => $gmt_time,
                );

                $msg_id = ChatSystem::getUsersThreadListData($senderId, $receiverId, 'insert_msg', $insert_data, '');

                if (!empty($msg_id)) {

                    $fetchRecentThread = ChatSystem::getUsersThreadListData('', '', 'fetch_recent_thread', array(), $msg_id);
                    $message    = !empty($fetchRecentThread['chat_message']) ?  unserialize($fetchRecentThread['chat_message']) : array();
                    $date       = !empty($fetchRecentThread['time_gmt']) ?  date_i18n(get_option('date_format'), strtotime($fetchRecentThread['time_gmt'])) : '';
                    $chat_nodes[0]['chat_avatar']             = ChatSystem::getUserInfoData('avatar', $fetchRecentThread['sender_id'], array('width' => 100, 'height' => 100));
                    $chat_nodes[0]['chat_username']         = ChatSystem::getUserInfoData('username', $fetchRecentThread['sender_id'], array());
                    $chat_nodes[0]['chat_filename']         = esc_attr($message['file_name']);
                    $chat_nodes[0]['chat_hashname']         = esc_attr($new_file_name);
                    $chat_nodes[0]['chat_filesize']         = esc_attr($message['file_size']);
                    $chat_nodes[0]['chat_filetype']         = esc_attr($message['file_type']);
                    $chat_nodes[0]['chat_date']             = $date;
                    $chat_nodes[0]['chat_id']                 = intval($fetchRecentThread['id']);
                    $chat_nodes[0]['chat_current_user_id']  = intval($senderId);
                    $chat_nodes[0]['chat_is_sender']        = 'yes';

                    //excerpt
                    if (strlen($message['file_name']) > 40) {
                        $message['file_name'] = substr($message['file_name'], 0, 40);
                    }

                    $json['type']           = 'success';
                    $json['msg_type']       = $msg_type;
                    $json['chat_nodes']     = $chat_nodes;

                    $chat_nodes[0]['chat_is_sender']    =  'no';
                    $json['chat_nodes_receiver']        = $chat_nodes;
                    $json['chat_receiver_id']           = intval($receiverId);
                    $json['chat_sender_id']             = intval($senderId);
                    $json['last_id']                    = intval($msg_id);
                    $json['mime_type']                  = esc_html($message['file_type']);
                    $json['chat_attachment']            = esc_url($upload_url . '/chat_attachments/' . esc_html($message['file_name']));

                    $json['replace_recent_msg_user'] = ChatSystem::getUserInfoData('username', $fetchRecentThread['receiver_id']);
                    $json['replace_recent_msg'] = !empty($message['file_name']) ? 'Filename: ' . html_entity_decode(stripslashes($message['file_name']), ENT_QUOTES) : '';
                    $json['message'] = esc_html__('Message sent!', 'workreap_api');
                    return new WP_REST_Response($json, 200);
                    //====================
                }
            }
        }
    }
}

add_action(
    'rest_api_init',
    function () {
        $controller = new AndroidApp_Chat_Route;
        $controller->register_routes();
    }
);
