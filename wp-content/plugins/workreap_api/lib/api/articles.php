<?php

/**
 * Blog Posts
 * 
 */

if (!class_exists('workreapPosts')) {

    class workreapPosts extends WP_REST_Controller
    {
        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes()
        {
            $version     = '1';
            $namespace     = 'api/v' . $version;
            $base         = 'blog';

            /* get post views */
            register_rest_route(
                $namespace,
                '/' . $base . '/blogpost_views',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'blogpost_views'),
                        'args' => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            /** get all post listings  */
            register_rest_route(
                $namespace,
                '/' . $base . '/blog_post_listings',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'blog_post_listings'),
                        'args' => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            /** Send/update comment on post  */
            register_rest_route(
                $namespace,
                '/' . $base . '/send_post_comment',
                array(
                    array(
                        'methods' => WP_REST_Server::CREATABLE,
                        'callback' => array(&$this, 'send_post_comment'),
                        'args' => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            /** comment reply  */
            register_rest_route(
                $namespace,
                '/' . $base . '/send_comment_reply',
                array(
                    array(
                        'methods' => WP_REST_Server::CREATABLE,
                        'callback' => array(&$this, 'send_comment_reply'),
                        'args' => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            /** Delete comment  */
            register_rest_route(
                $namespace,
                '/' . $base . '/delete_post_comment',
                array(
                    array(
                        'methods' => WP_REST_Server::CREATABLE,
                        'callback' => array(&$this, 'delete_post_comment'),
                        'args' => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

            /** get comments hierarchy by post id */
            register_rest_route(
                $namespace,
                '/' . $base . '/get_post_listing',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_comments_hierarchy'),
                        'args' => array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );
        }

        /**
         * get post views count 
         */
        public function blogpost_views($data)
        {
            $json                   = array();
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            } //if demo site then prevent

            $post_id               = !empty($request['post_id']) ? intval($request['post_id']) : 0;
            if (empty($post_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Post is is missing', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            $post_views             = get_post_meta($post_id, 'article_views', true);
            $post_views             = !empty($post_views) ? $post_views : 0;

            $json['type']       = 'success';
            $json['views']      = $post_views;
            return new WP_REST_Response($json, 200);
        }



        /**
         * get all post listings
         */
        public function blog_post_listings($data)
        {
            $json                   = $post_arr = array();
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

            $per_page               = !empty($request['per_page']) ? intval($request['per_page']) : 10;
            $page_num               = !empty($request['page_num']) ? intval($request['page_num']) : 1;

            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            }; //if demo site then prevent

            $args = array(
                'post_type'         => 'post',
                'post_status'       => array('publish'),
                'posts_per_page'    => $per_page,
                'paged'             => $page_num,
                'orderby'           => 'ID',
                'order'             => 'DESC',
                'suppress_filters'  => false
            );

            $posts = new WP_Query($args);
            $post_count = $posts->found_posts;

            if ($posts->have_posts()) {
                while ($posts->have_posts()) {
                    $posts->the_post();
                    $post_id                = get_the_ID();
                    $post_thumbnail         = workreap_prepare_thumbnail($post_id, 500, 500);
                    $comment_counts         = sprintf(_nx('1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'textdomain'), number_format_i18n(get_comments_number()));
                    $author_id              = get_post_field('post_author', $post_id);
                    $author_name            = get_the_author_meta('display_name', $author_id);
                    $post_date              = !empty($post_id) ? get_post_field('post_date', $post_id) : "";
                    $post_views             = get_post_meta($post_id, 'article_views', true);
                    $post_views             = !empty($post_views) ? $post_views : 0;
                    $categories             = get_the_category($post_id);
                    $cat_arr                = array();
                    if ($categories) {
                        foreach ($categories as $catVal) {
                            $cat_arr[] = array(
                                'ID'        => $catVal->term_id,
                                'name'      => $catVal->name,
                                'slug'      => $catVal->slug,
                                'slug'      => $catVal->slug,
                            );
                        }
                    }

                    $tags = get_the_tags($post_id);
                    $tag_arr = array();
                    if ($tags) {
                        foreach ($tags as $tagVal) {
                            $tag_arr[] = array(
                                'ID'        => $tagVal->term_id,
                                'name'      => $tagVal->name,
                                'slug'      => $tagVal->slug,
                                'slug'      => $tagVal->slug,
                            );
                        }
                    }

                    /* commnets */
                    $comments        = workreap_api_single_post_comments($post_id);
                    $comments        = !empty($comments) ? $comments : array();

                    $post_arr[] = array(
                        'ID'                => $post_id,
                        'title'             => get_the_title($post_id),
                        'author'            => $author_name,
                        'date'              => !empty($post_date) ? date(get_option('date_format'), strtotime($post_date)) : '',
                        'thumbnail'         => $post_thumbnail,
                        'post_views'        => intval($post_views),
                        'categories'        => $cat_arr,
                        'tags'              => $tag_arr,
                        'content'           => get_post_field('post_content', $post_id),
                        'comments_counts'   => $comment_counts,
                        'comments'          => $comments,
                    );
                }

                wp_reset_postdata();
            }

            $json['type']           = 'success';
            $json['counts']         = $post_count;
            $json['posts']          = $post_arr;
            return new WP_REST_Response($json, 200);
        }

        /** Send/update comment on post */
        public function send_post_comment($data)
        {
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

            $post_id                = !empty($request['post_id']) ? intval($request['post_id']) : 0;
            $name                   = !empty($request['name']) ? $request['name'] : '';
            $email                  = !empty($request['email']) ? $request['email'] : '';
            $content                = !empty($request['content']) ? $request['content'] : '';
            $comment_parent         = !empty($request['comment_parent']) ? intval($request['comment_parent']) : 0;

            $user_id                = !empty($request['user_id']) ? intval($request['user_id']) : 0;
            $comment_id             = !empty($request['comment_id']) ? intval($request['comment_id']) : 0;

            $fields    = array(
                'post_id'       => esc_html__('Post id is missing', 'workreap_api'),
                'content'       => esc_html__('Content is missing', 'workreap_api'),
                'user_id'       => esc_html__('User id is missing', 'workreap_api'),
            );

            foreach ($fields as $key => $item) {
                if (empty($request[$key])) {
                    $json['type']       = "error";
                    $json['message']    = $item;
                    return new WP_REST_Response($json, 203);
                }
            }

            if (empty($user_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('User id is required', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            if (comments_open($post_id)) {
                $user_datas     = get_userdata($user_id);

                $comment_data = array(
                    'user_id'               => $user_id,
                    'comment_post_ID'       => $post_id,
                    'comment_content'       => $content,
                    'comment_author'        => !empty($name) ? $name : $user_datas->user_login,
                    'comment_author_email'  => !empty($email) ? $email : $user_datas->user_email,
                    'comment_author_url'    => $user_datas->user_url,
                    'comment_parent'        => $comment_parent,
                    'comment_approved'      => 1,
                );

                if (!empty($comment_id)) {
                    $comment_data['comment_ID'] = $comment_id;
                    $is_updated = wp_update_comment($comment_data);

                    if ($is_updated) {
                        $json['type']           = 'success';
                        $json['message']        = esc_html__('Comment updated successfully', 'workreap_api');
                        $json['comment']        = workreap_api_single_post_comments($post_id);
                        return new WP_REST_Response($json, 200);
                    } else {
                        $json['type']       = 'error';
                        $json['message']    = esc_html__('Something went wrong!', 'workreap_api');
                        return new WP_REST_Response($json, 203);
                    }
                } else {
                    $comment_id = wp_insert_comment($comment_data);
                    if (!is_wp_error($comment_id)) {
                        $json['type']           = 'success';
                        $json['message']        = esc_html__('Comment successfully posted', 'workreap_api');
                        $json['comment']        = workreap_api_single_post_comments($post_id);
                        return new WP_REST_Response($json, 200);
                    } else {
                        $json['type']       = 'error';
                        $json['message']    = esc_html__('Something went wrong', 'workreap_api');
                        return new WP_REST_Response($json, 203);
                    }
                }
            } else {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Comments are closed on this post', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }
        }

        /**
         * Comment reply
         */
        public function send_comment_reply($data)
        {
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

            $reply_type             = !empty($request['reply_type']) ? $request['reply_type'] : '';
            $user_id                = !empty($request['user_id']) ? intval($request['user_id']) : 0;
            $comment_parent         = !empty($request['comment_parent']) ? intval($request['comment_parent']) : 0;
            $comment_id         = !empty($request['comment_id']) ? intval($request['comment_id']) : 0;
            $post_id                = !empty($request['post_id']) ? intval($request['post_id']) : 0;
            $name                   = !empty($request['name']) ? $request['name'] : '';
            $email                  = !empty($request['email']) ? $request['email'] : '';
            $content                = !empty($request['content']) ? $request['content'] : '';

            if (empty($content)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Comment message in required', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            $user_datas     = get_userdata($user_id);
            $comment_data = array(
                'user_id'               => $user_id,
                'comment_post_ID'       => $post_id,
                'comment_content'       => $content,
                'comment_author'        => !empty($name) ? $name : $user_datas->user_login,
                'comment_author_email'  => !empty($email) ? $email : $user_datas->user_email,
                'comment_author_url'    => $user_datas->user_url,
                'comment_parent'        => $comment_parent,
                'comment_approved'      => 1,
            );

            if ($reply_type === 'new') {
                $comment_id = wp_insert_comment($comment_data);
            } else {
                $comment_data['comment_ID'] = $comment_id;
                $comment_id = wp_update_comment($comment_data);
            }

            if (!is_wp_error($comment_id)) {
                $json['type']           = 'success';
                $json['message']        = esc_html__('Reply send successfully', 'workreap_api');
                $json['comment']        = workreap_api_single_post_comments($post_id);
                return new WP_REST_Response($json, 200);
            } else {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Something went wrong', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }
        }

        /**
         * Delete comment
         */
        public function delete_post_comment($data)
        {
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

            $comment_id             = !empty($request['comment_id']) ? intval($request['comment_id']) : 0;
            $user_id                = !empty($request['user_id']) ? intval($request['user_id']) : 0;
            $post_id                = !empty($request['post_id']) ? intval($request['post_id']) : 0;

            if (empty($comment_id) || empty($user_id)) {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Something is missing', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }

            $is_deleted = wp_delete_comment($comment_id, true);
            if ($is_deleted) {
                $json['type']           = 'success';
                $json['comment']        = workreap_api_single_post_comments($post_id);
                $json['message']        = esc_html__('Comment deleted', 'workreap_api');
                return new WP_REST_Response($json, 200);
            } else {
                $json['type']       = 'error';
                $json['message']    = esc_html__('Comment not deleted please try again', 'workreap_api');
                return new WP_REST_Response($json, 203);
            }
        }

        /**
         * Get comments Hierarchy
         */
        public function get_comments_hierarchy($data)
        {
            $headers                = $data->get_headers();
            $request                = !empty($data->get_params()) ? $data->get_params() : array();
            $request['authToken']   = !empty($headers['authorization'][0]) ? $headers['authorization'][0] : '';

            $per_page               = !empty($request['per_page']) ? intval($request['per_page']) : 10;
            $page_num               = !empty($request['page_num']) ? intval($request['page_num']) : 1;
            $user_id                = !empty($request['user_id']) ? intval($request['user_id']) : 0;
            $post_id                = !empty($request['post_id']) ? intval($request['post_id']) : 0;
            $number                 = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
            $post_arr               = array();

            if (function_exists('workreap_is_demo_site')) {
                workreap_is_demo_site();
            }; //if demo site then prevent

            $args = array(
                'post_type'         => 'post',
                'post_status'       => array('publish'),
                'posts_per_page'    => $per_page,
                'paged'             => $page_num,
                'orderby'           => 'ID',
                'order'             => 'DESC',
                'suppress_filters'  => false
            );

            $posts = new WP_Query($args);
            $post_count = $posts->found_posts;


            if ($posts->have_posts()) {
                while ($posts->have_posts()) {
                    $posts->the_post();
                    $post_id                = get_the_ID();
                    $post_thumbnail         = workreap_prepare_thumbnail($post_id, 500, 500);
                    $comment_counts         = sprintf(_nx('1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'textdomain'), number_format_i18n(get_comments_number()));
                    $author_id              = get_post_field('post_author', $post_id);
                    $author_name            = get_the_author_meta('display_name', $author_id);
                    $post_date              = !empty($post_id) ? get_post_field('post_date', $post_id) : "";
                    $post_views             = get_post_meta($post_id, 'article_views', true);
                    $post_views             = !empty($post_views) ? $post_views : 0;
                    $categories             = get_the_category($post_id);
                    $cat_arr                = array();

                    /* categories */
                    if ($categories) {
                        foreach ($categories as $catVal) {
                            $cat_arr[] = array(
                                'ID'        => $catVal->term_id,
                                'name'      => $catVal->name,
                                'slug'      => $catVal->slug,
                                'slug'      => $catVal->slug,
                            );
                        }
                    }

                    /* tags */
                    $tags = get_the_tags($post_id);
                    $tag_arr = array();
                    if ($tags) {
                        foreach ($tags as $tagVal) {
                            $tag_arr[] = array(
                                'ID'        => $tagVal->term_id,
                                'name'      => $tagVal->name,
                                'slug'      => $tagVal->slug,
                                'slug'      => $tagVal->slug,
                            );
                        }
                    }

                    /* commnets */
                    $author_id              = get_post_field('post_author', $post_id);
                    $user_profileId         = workreap_get_linked_profile_id($author_id);

                    $comment_data    = array(
                        'post_id'           => $post_id,
                        'post_type'         => 'post',
                        'status'            => array('approve', 'hold'),
                        'number'            => $number,
                        'orderby'           => 'comment_ID',
                        'type'              => 'comment',
                        'hierarchical'      => 'flat',
                    );

                    $comments = get_comments($comment_data);


                    $post_arr[] = array(
                        'ID'                => $post_id,
                        'title'             => get_the_title($post_id),
                        'author'            => $author_name,
                        'date'              => !empty($post_date) ? date(get_option('date_format'), strtotime($post_date)) : '',
                        'thumbnail'         => $post_thumbnail,
                        'post_views'        => intval($post_views),
                        'categories'        => $cat_arr,
                        'tags'              => $tag_arr,
                        'content'           => get_post_field('post_content', $post_id),
                        'comments_counts'   => $comment_counts,
                        'comments'          => $comments,
                    );
                }
            }

            $json['type']           = 'success';
            $json['counts']         = $post_count;
            $json['posts']          = $post_arr;
            return new WP_REST_Response($json, 200);
        }
    }

    add_action(
        'rest_api_init',
        function () {
            $controller = new workreapPosts;
            $controller->register_routes();
        }
    );
}
