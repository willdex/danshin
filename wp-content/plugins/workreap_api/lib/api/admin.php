<?php

/**
 * APP API to admin
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           WorkreapAppApi
 *
 */

if (!class_exists('WorkreapAdminRoutes')) {
    class WorkreapAdminRoutes extends WP_REST_Controller
    {
        /**
         * Register the routes for the admin.
         */
        public function register_routes()
        {
            $version        = '1';
            $namespace      = 'api/v' . $version;
            $base           = 'admin';

            /* contact to admin */
            register_rest_route(
                $namespace,
                '/' . $base . '/contact-admin',
                array(
                    array(
                        'methods'       => WP_REST_Server::CREATABLE,
                        'callback'      => array(&$this, 'contact_to_admin'),
                        'args'          => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );
        }

        /**
         * Contact with admin
         */
        public function contact_to_admin($data)
        {
            $headers                    = $data->get_headers();
            $request                    = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']       = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response                   = api_authentication($request);

            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $contact_name       = !empty($request['contact_name']) ? sanitize_text_field($request['contact_name']) : '';
            $contact_email      = !empty($request['contact_email']) ? sanitize_email($request['contact_email']) : '';
            $contact_phone      = !empty($request['contact_phone']) ? sanitize_text_field($request['contact_phone']) : '-';
            $role_type          = !empty($request['role_type']) ? $request['role_type'] : '';
            $contact_desc       = !empty($request['contact_desc']) ? sanitize_text_field($request['contact_desc']) : '';
            $message            = esc_html__('Email not sent please try again later.', 'workreap_api');

            if (empty($contact_name) || empty($contact_email) || empty($contact_desc)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Something is missing!', 'workreap_api');
                $items[]            = $json;
                return new WP_REST_Response($items, 203);
            }

            if (class_exists('Workreap_Email_helper')) {
                $emailData = array();
                $emailData['name']      = $contact_name;
                $emailData['email']     = $contact_email;
                $emailData['phone']     = $contact_phone;
                $emailData['role']     = $role_type;
                $emailData['desc']      = $contact_desc;

                /* send admin contact email */
                if (class_exists('WorkreapRegisterEmail')) {
                    $email_helper = new WorkreapRegisterEmail();
                    $email_helper->send_admin_contact_email($emailData);
                }
                $message = esc_html__('Email send to the admin.', 'workreap_api');
            }

            $json['type']             = 'success';
            $json['message']         = $message;
            return new WP_REST_Response($json, 200);
        }
    }
}

add_action(
    'rest_api_init',
    function () {
        $controller = new WorkreapAdminRoutes;
        $controller->register_routes();
    }
);
