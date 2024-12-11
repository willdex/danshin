<?php

/**
 * APP API to manage users
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           WorkreapAppApi
 *
 */
if (!class_exists('WorkreapDisputesRoutes')) {

    class WorkreapDisputesRoutes extends WP_REST_Controller
    {
        /**
         * Register the routes for the favourites.
         */
        public function register_routes()
        {
            $version     = '1';
            $namespace     = 'api/v' . $version;
            $base         = 'disputes';

            /* remove from favourites */
            register_rest_route(
                $namespace,
                '/' . $base . '/create_disputes',
                array(
                    array(
                        'methods'       => WP_REST_Server::CREATABLE,
                        'callback'      => array(&$this, 'create_disputes'),
                        'args'          => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );
            register_rest_route(
                $namespace,
                '/' . $base . '/list_projects_services',
                array(
                    array(
                        'methods'       => WP_REST_Server::READABLE,
                        'callback'      => array(&$this, 'get_list_disputes_freelancer'),
                        'args'          => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );
            register_rest_route(
                $namespace,
                '/' . $base . '/get_disputes_reasons_list',
                array(
                    array(
                        'methods'       => WP_REST_Server::READABLE,
                        'callback'      => array(&$this, 'get_disputes_reasons_list'),
                        'args'          => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );
        }

        /**
         * Dispute reason list
         */
        public function get_disputes_reasons_list($data)
        {
            $headers                    = $data->get_headers();
            $request                    = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']       = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response                   = api_authentication($request);
            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }
            $disputes_reasons   = array();

            $user_identity  = !empty($request['user_id']) ? $request['user_id'] : '';
            $linked_profile = workreap_get_linked_profile_id($user_identity);
            $user_type        = apply_filters('workreap_get_user_type', $user_identity);

            if ($user_type === 'freelancer') {
                $disputes_reasons   = workreap_project_ratings('dispute_options_freelancer');
            } else {
                $disputes_reasons   = workreap_project_ratings('dispute_options');
            }
            if ($disputes_reasons) {
                $json['type']   = 'success';
                $json['list']   =   $disputes_reasons;
                return new WP_REST_Response($json, 200);
            } else {
                $json['type']       = 'error';
                $json['message']    = esc_html__('No Data found..!', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }
        }

        public function get_list_disputes_freelancer($data)
        {
            $headers                    = $data->get_headers();
            $request                    = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']       = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response                   = api_authentication($request);
            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $user_identity      = !empty($request['user_id']) ? $request['user_id'] : '';
            $linked_profile     = workreap_get_linked_profile_id($user_identity);
            $user_type          = apply_filters('workreap_get_user_type', $user_identity);

            if (!empty($user_type) && $user_type == 'freelancer') {
                $dispute_args = array(
                    'posts_per_page'    => -1,
                    'post_type'         => array('proposals'),
                    'orderby'           => 'ID',
                    'order'             => 'DESC',
                    'author'            => $user_identity,
                    'post_status'       => array('publish', 'cancelled'),
                    'suppress_filters'  => false,
                    'meta_query'        => array(
                        'relation'      => 'AND',
                        array(
                            'key'           => '_send_by',
                            'value'         => $linked_profile,
                            'compare'       => '='
                        ),
                        array(
                            'key'       => 'dispute',
                            'compare'   => 'NOT EXISTS'
                        ),
                    )
                );

                $dispute_service_args = array(
                    'posts_per_page'    => -1,
                    'post_type'         => array('services-orders'),
                    'orderby'           => 'ID',
                    'order'             => 'DESC',
                    'post_status'       => array('cancelled', 'hired'),
                    'suppress_filters'  => false,
                    'meta_query'        => array(
                        array(
                            'key'           => '_service_author',
                            'value'         => $user_identity,
                            'compare'       => '='
                        ),
                        array(
                            'key'       => 'dispute',
                            'compare'   => 'NOT EXISTS'
                        ),
                    )
                );
            } else {
                $dispute_args = array(
                    'posts_per_page'    => -1,
                    'post_type'         => array('proposals'),
                    'orderby'           => 'ID',
                    'order'             => 'DESC',
                    'post_status'       => array('cancelled'),
                    'suppress_filters'  => false,
                    'meta_query'        => array(
                        'relation'      => 'AND',
                        array(
                            'key'           => '_employer_user_id',
                            'value'         => $user_identity,
                            'compare'       => '='
                        ),
                        array(
                            'key'         => 'dispute',
                            'compare'     => 'NOT EXISTS'
                        ),
                    )
                );

                $dispute_service_args = array(
                    'posts_per_page'    => -1,
                    'post_type'         => array('services-orders'),
                    'orderby'           => 'ID',
                    'order'             => 'DESC',
                    'post_status'       => array('cancelled'),
                    'author'            => $user_identity,
                    'suppress_filters'  => false,
                    'meta_query'        => array(
                        array(
                            'key'       => 'dispute',
                            'compare'   => 'NOT EXISTS'
                        ),
                    )
                );
            }
            $dispute_query                 = get_posts($dispute_args);
            $dispute_service_query         = get_posts($dispute_service_args);
            if (!empty($dispute_query) && !empty($dispute_service_query)) {
                $dispute_query    = array_merge($dispute_query, $dispute_service_query);
            } else if (empty($dispute_query) && !empty($dispute_service_query)) {
                $dispute_query    = $dispute_service_query;
            } else if (!empty($dispute_query) && empty($dispute_service_query)) {
                $dispute_query    = $dispute_query;
            } else {
                $dispute_query    = array();
            }
            if ($dispute_query) {
                $services_projects  = array();
                foreach ($dispute_query as $key => $item) {
                    $post_title     = $item->post_title;
                    $post_type      = get_post_type($item->ID);
                    if (!empty($post_type) && $post_type === 'services-orders') {
                        $post_author        = get_post_field('post_author', $item->ID);
                        $user_id            = workreap_get_linked_profile_id($post_author);
                        $user_name          = workreap_get_username('', $user_id);
                        $post_title         = $post_title . ' (' . $user_name . ')';
                    }
                    $services_projects[]  = array(
                        'item_id'       => $item->ID,
                        'post_title'    => $post_title,
                    );
                }
                $json['type']   = 'success';
                $json['list']   =   $services_projects;
                return new WP_REST_Response($json, 200);
            } else {

                $json['type']       = 'error';
                $json['message']    = esc_html__('No project & service found!', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }
        }

        public function create_disputes($data)
        {
            $headers                    = $data->get_headers();
            $request                    = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']       = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response                   = api_authentication($request);
            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $user_identity  = !empty($request['user_id']) ? $request['user_id'] : 0;
            $user_type      = apply_filters('workreap_get_user_type', $user_identity);

            $fields    = array(
                'project'           => esc_html__('No project/service is selected', 'workreap_api'),
                'reason'            => esc_html__('Please select the reason', 'workreap_api'),
                'description'       => esc_html__('Please add dispute description', 'workreap_api'),
            );

            foreach ($fields as $key => $item) {
                if (empty($request['dispute'][$key])) {
                    $json['type']       = "error";
                    $json['message']    = $item;
                    wp_send_json($json);
                }
            }

            //Create dispute
            $dispute_options    = !empty($user_type) && $user_type == 'freelancer' ? 'dispute_options_freelancer' : 'dispute_options';
            $username           = workreap_get_username($user_identity);
            $linked_profile     = workreap_get_linked_profile_id($user_identity);
            $project            = sanitize_text_field($request['dispute']['project']);
            $title              = sanitize_text_field($request['dispute']['reason']);
            $description        = !empty($request['dispute']['description']) ? ($request['dispute']['description']) : '';
            $list               = workreap_project_ratings($dispute_options);
            $dispute_title      = !empty($list[$title]) ? $list[$title] : rand(1, 9999);
            $get_post_type      = get_post_type($project);

            $dispute_args = array(
                'posts_per_page'    => -1,
                'post_type'         => array('disputes'),
                'orderby'           => 'ID',
                'order'             => 'DESC',
                'post_status'       => array('pending', 'publish'),
                'author'            => $user_identity,
                'suppress_filters'  => false,
                'meta_query'        => array(
                    'relation'      => 'AND',
                    array(
                        'key'           => '_dispute_project',
                        'value'         => $project,
                        'compare'       => '='
                    )
                )
            );
            $dispute_is = get_posts($dispute_args);

            if (!empty($dispute_is)) {
                $json['type'] = "error";
                $json['message'] = esc_html__("You have already submitted the dispute against this project.", 'workreap_api');
                wp_send_json($json);
            }
            $project_id        = get_post_meta($project, '_project_id', true);
            $dispute_against    = '';

            if (!empty($user_type) && $user_type === 'freelancer') {
                if (!empty($get_post_type) && ($get_post_type === 'services-orders')) {
                    $postdata           = get_post($project);
                    $project_author     = $postdata->post_author;
                    $dispute_against    = workreap_get_username($project_author);
                    $author_data        = get_userdata($project_author);
                    $dispute_email_to   = $author_data->data->user_email;

                    $service_id                 = get_post_meta($postdata->ID, '_service_id', true);
                    $dispute_project_title      = get_the_title($service_id);
                    $dispute_project_link       = get_the_permalink($service_id);
                } else if (!empty($get_post_type) && ($get_post_type === 'proposals')) {
                    $project_id                 = get_post_meta($project, '_project_id', true);
                    $postdata                   = get_post($project_id);
                    $project_author             = $postdata->post_author;
                    $dispute_against            = workreap_get_username($project_author);
                    $author_data                = get_userdata($project_author);
                    $dispute_email_to           = $author_data->data->user_email;
                    $dispute_project_title      = $postdata->post_title;
                    $dispute_project_link       = get_the_permalink($postdata->ID);
                }
            } else if (!empty($user_type) && $user_type === 'employer') {
                if (!empty($get_post_type) && ($get_post_type === 'services-orders')) {
                    $service_author         = get_post_meta($project, '_service_author', true);
                    $dispute_against        = workreap_get_username($service_author);
                    $author_data            = get_userdata($service_author);
                    $dispute_email_to       = $author_data->data->user_email;

                    $service_id                 = get_post_meta($project, '_service_id', true);
                    $dispute_project_title      = get_the_title($service_id);
                    $dispute_project_link       = get_the_permalink($service_id);
                } else if (!empty($get_post_type) && ($get_post_type === 'proposals')) {
                    $project_id             = get_post_meta($project, '_project_id', true);
                    $postdata               = get_post($project);
                    $project_author         = $postdata->post_author;
                    $dispute_against        = workreap_get_username($project_author);

                    $author_data                = get_userdata($project_author);
                    $dispute_email_to           = $author_data->data->user_email;
                    $dispute_project_title      = $postdata->post_title;
                    $dispute_project_link       = get_the_permalink($postdata->ID);
                }
            }

            $dispute_post  = array(
                'post_title'    => wp_strip_all_tags($dispute_title), //proposal title
                'post_status'   => 'pending',
                'post_content'  => $description,
                'post_author'   => $user_identity,
                'post_type'     => 'disputes',
            );

            $dispute_id = wp_insert_post($dispute_post);
            update_post_meta($dispute_id, '_send_by', $user_identity);
            update_post_meta($dispute_id, '_dispute_key', $title);
            update_post_meta($dispute_id, '_dispute_project', $project); //propsal ID
            update_post_meta($dispute_id, '_project_id', $project_id);
            update_post_meta($project, 'dispute', 'yes');

            $post_type_object = get_post_type_object('proposals');
            $link = !empty($post_type_object->_edit_link) ? admin_url(sprintf($post_type_object->_edit_link . '&action=edit', $project)) : '';

            //Send email to user
            if (class_exists('Workreap_Email_helper')) {
                if (class_exists('WorkreapSendDispute')) {
                    $email_helper = new WorkreapSendDispute();
                    $emailData = array();
                    $emailData['project_link']      = $link;
                    $emailData['project_title']     = get_the_title($project);
                    $emailData['user_name']         = $username;
                    $emailData['user_link']         = get_the_permalink($linked_profile);
                    $emailData['message']           = $description;
                    $emailData['dispute_subject']   = $dispute_title;
                    $emailData['dispute_author']    = $username;
                    $emailData['dispute_against']   = $dispute_against;
                    $emailData['dispute_email_to']  = $dispute_email_to;

                    $email_helper->send($emailData);

                    $emailData['project_link']      = !empty($dispute_project_link) ?  $dispute_project_link : get_the_permalink($project);
                    $emailData['project_title']     = !empty($dispute_project_title) ?  $dispute_project_title : get_the_title($project);
                    $email_helper->dispute_notify($emailData);
                }
            }

            //Push notification
            $push    = array();
            $push['sender_id']              = $user_identity;
            $push['dispute_against']        = !empty($author_data->data->ID) ? $author_data->data->ID : 0;
            $push['project_id']             = !empty($project_id) ? $project_id : 0;
            $push['message']                = $description;
            $push['%dispute_author%']       = workreap_get_username($user_identity);
            $push['%dispute_against%']      = !empty($author_data->data->ID) ? workreap_get_username($author_data->data->ID) : 0;
            $push['%message%']              = $description;
            $push['%replace_message%']      = $description;
            $push['%project_link%']         = $dispute_project_link;
            $push['%project_title%']        = $dispute_project_title;
            $push['type']                   = 'dispute';

            do_action('workreap_user_push_notify', array($push['dispute_against']), '', 'pusher_dispute_user_content', $push);

            $json['type'] = "success";
            $json['message'] = esc_html__("We have received your dispute, soon we will get back to you.", 'workreap_api');
            wp_send_json($json);
        }
    }
}

add_action(
    'rest_api_init',
    function () {
        $controller = new WorkreapDisputesRoutes;
        $controller->register_routes();
    }
);
