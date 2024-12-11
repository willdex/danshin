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
if (!class_exists('WorkreapFavouriteRoutes')) {

    class WorkreapFavouriteRoutes extends WP_REST_Controller
    {
        /**
         * Register the routes for the favourites.
         */
        public function register_routes()
        {
            $version     = '1';
            $namespace     = 'api/v' . $version;
            $base         = 'favourite';

            /* remove from favourites */
            register_rest_route(
                $namespace,
                '/' . $base . '/remove-favourite',
                array(
                    array(
                        'methods'     => WP_REST_Server::CREATABLE,
                        'callback'     => array(&$this, 'remove_favourites'),
                        'args'         => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );
        }

        public function remove_favourites($data)
        {
            $headers                    = $data->get_headers();
            $request                    = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']       = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';
            $response                   = api_authentication($request);
            if (!empty($response) && $response['type'] == 'error') {
                return new WP_REST_Response($response, 203);
            }

            $item_type  = !empty($request['item_type']) ? $request['item_type'] : '';
            $user_id    = !empty($request['user_id']) ? $request['user_id'] : 0;
            $item_id    = !empty($request['item_id']) ? array(intval($request['item_id'])) : 0;
            $profile_id = workreap_get_linked_profile_id($user_id);
 
            if (empty($item_type) && empty($profile_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Something went wrong!', 'workreap_api');
                $items[] = $json;
                return new WP_REST_Response($items, 203);
            }
            if ($item_type === '_saved_projects' || $item_type === '_saved_services' || $item_type === '_saved_freelancers' || $item_type === '_following_employers') {
                if (!empty($item_id)) {
                    $saved_ids      = get_post_meta($profile_id, $item_type, true);
                    $updated_values = array_diff($saved_ids, $item_id);
                    update_post_meta($profile_id, $item_type, $updated_values);
                    $message        = esc_html__('Successfully! removed from your favourties', 'workreap_api');
                } else {
                    update_post_meta($profile_id, $item_type, '');
                    $message        = esc_html__('All saved items have been removed', 'workreap_api');
                }
            } else {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Something is missing!', 'workreap_api');
                $items[] = $json;
                return new WP_REST_Response($items, 203);
            }
            $json['type']       = 'success';
            $json['message']    = $message;
            return new WP_REST_Response($json, 200);
        }
    }
}

add_action(
    'rest_api_init',
    function () {
        $controller = new WorkreapFavouriteRoutes;
        $controller->register_routes();
    }
);
