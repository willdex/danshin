<?php

/**
 * Get template
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_custom_task_offer_get_template')) {
    function workreap_custom_task_offer_get_template($template_name='', $args = array(), $template_path = 'customized-task-offer', $default_path = '')
    {
        if (empty($template_name) ){
            return;
        }

        if (!empty($args) && is_array($args)) {
            extract($args);
        }

        $located = workreap_custom_task_offer_locate_template($template_name, $template_path, $default_path);
        if (!empty($return) && $return === true) {
            return $located;
        } else {
            include($located);
        }
    }
}

/**
 * Locate template
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_custom_task_offer_locate_template')) {
    function workreap_custom_task_offer_locate_template($template_name, $template_path = 'workreap', $default_path = '')
    {
        $template = locate_template(
            array(
                trailingslashit($template_path) . $template_name,
            )
        );
        if (!$template && $default_path !== false) {
            $default_path = $default_path ? $default_path : untrailingslashit(plugin_dir_path(dirname(__DIR__))) . '/templates/';
            if (file_exists(trailingslashit($default_path) . $template_name)) {
                $template = trailingslashit($default_path) . $template_name;
            }
        }
        return apply_filters('workreap_custom_task_offer_locate_template', $template, $template_name, $template_path);
    }
}

/**
 * Plugin template part
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_custom_task_offer_get_template_part')) {
    function servento_core_get_template_part($slug, $name = '', $args = '', $template_path = 'customized-task-offer', $default_path = '')
    {
        $template = '';
        if ($name) {
            $template = workreap_custom_task_offer_locate_template("{$slug}-{$name}.php", $template_path, $default_path);
        }
        if (!$template) {
            $template = workreap_custom_task_offer_locate_template("{$slug}.php", $template_path, $default_path);
        }
        $template = apply_filters('workreap_custom_task_offer_get_template_part', $template, $slug, $name, $args);
        if ($template) {
            load_template($template, FALSE, $args);
        }
    }
}

/**
 * Get task offer navigation
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_task_offer_list' ) ) {
    function workreap_task_offer_list( $type = '' ) {
		$list	= array(
			'1'	=> array(
				'title' 	=> esc_html__('Choose task', 'customized-task-offer'),
				'class'		=> 'wr-addservice-step'
			),
			'2'	=> array(
				'title' 	=> esc_html__('Customized pricing', 'customized-task-offer'),
				'class'		=> 'wr-addservice-step wr-addservice-step-2'
			),
			'3'	=> array(
				'title' 	=> esc_html__('Media/Attachments', 'customized-task-offer'),
				'class'		=> 'wr-addservice-step wr-addservice-step-3'
			),
		);
        
		$list 	= apply_filters('workreap_task_offer_list',$list);
		return $list;
    }
}

/**
 * Dashboard menu
 *
 * @global bolean $paid
 */
if(!function_exists('workreap_dasboard_tasks_menu_filter')){
	function workreap_dasboard_tasks_menu_filter($workreap_menu_list = array()) {
        global $current_user;
        $workreap_menu_list['custom_offer']  = array(
            'title'     => esc_html__('Custom offers','customized-task-offer'),
            'class'     => 'wr-tasklistings',
            'icon'	    => '',
            'ref'		=> 'offers',
            'mode'		=> 'listing',
            'sortorder'	=> 5,
            'type'		=> 'none',
        );
		return $workreap_menu_list;
	}
	add_filter( 'workreap_dasboard_tasks_menu_filter', 'workreap_dasboard_tasks_menu_filter');
}

/**
 * Product paln title
 * @global bolean $paid
 */
if( !function_exists('workreap_plan_conetnet')){
	function workreap_plan_conetnet($plan_title,$order_id) {
		$offers_id  = get_post_meta( $order_id, 'offers_id', true );
        $offers_id  = !empty($offers_id) ? intval($offers_id) : 0;
        if( !empty($offers_id) ){
            $task_url   = get_the_permalink( $offers_id );
            $plan_title = '<a href="'.esc_url($task_url).'" class="wr-paln-url">'.esc_html($plan_title).'</a>';
        }
        return $plan_title;
	}
    add_filter( 'workreap_plan_conetnet', 'workreap_plan_conetnet',10,2 );
}

/**
 * Dashboard menu
 *
 * @global bolean $paid
 */
if(!function_exists('workreap_employer_offers_menu_filter')){
	function workreap_employer_offers_menu_filter($workreap_menu_list = array()) {
        global $current_user;  
        $user_type		 = apply_filters('workreap_get_user_type', $current_user->ID );
        if( !empty($user_type) && $user_type === 'employers'){
            $workreap_menu_list['myorders']  = array(
                'title' 	=> esc_html__('Manage tasks', 'customized-task-offer'),
                'class'		=> 'wr-managetask',
                'icon'		=> 'wr-icon-file-text',
                'type'		=> 'employers',
                'ref'		=> '',
                'mode'		=> '',
                'sortorder'	=> 5,
                'submenu'	=> array(
	                'find-freelancer'	=> array(
		                'title' => esc_html__('Find freelancers', 'workreap'),
		                'class'	=> 'wr-find-freelancers',
		                'icon'	=> '',
		                'ref'		=> 'find-freelancers',
		                'mode'		=> 'listing',
		                'sortorder'	=> 1,
		                'type'		=> 'employers',
	                ),
	                'find-task'	=> array(
		                'title' => esc_html__('Explore task', 'workreap'),
		                'class'	=> 'wr-find-tasks',
		                'icon'	=> '',
		                'ref'		=> 'find-task',
		                'mode'		=> '',
		                'sortorder'	=> 2,
		                'type'		=> 'employers',
	                ),
	                'taskslistings'	=> array(
		                'title' => esc_html__('My tasks', 'workreap'),
		                'class'	=> 'wr-taskslistings',
		                'icon'	=> '',
		                'ref'		=> 'tasks-orders',
		                'mode'		=> 'listing',
		                'sortorder'	=> 3,
		                'type'		=> 'employers',
	                ),
	                'offers'	=> array(
		                'title' 	=> esc_html__('Custom offers', 'customized-task-offer'),
		                'class'		=> 'wr-myoffers',
		                'icon'		=> '',
		                'type'		=> 'employers',
		                'ref'		=> 'offers',
		                'mode'		=> 'listing',
		                'sortorder'	=> 4,
	                ),
                    ),
                );
        } 
		return $workreap_menu_list;
	}
	add_filter( 'workreap_filter_dashboard_submenu', 'workreap_employer_offers_menu_filter');
}

/**
 * List page templates
 *
 * @global bolean $paid
 */
if(!function_exists('workreap_update_templates_list')){
	function workreap_update_templates_list($add_page_template = array()) {
        $add_offer_page_template	= array(
            'id'    	=> 'tpl_add_offer_page',
            'type'  	=> 'select',
            'title' 	=> esc_html__( 'Add/edit offer', 'customized-task-offer' ),
            'data'  	=> 'pages',
            'desc'      => esc_html__('Select page for the Add/edit offer', 'customized-task-offer'),
        );
        array_push($add_page_template,$add_offer_page_template);
		return $add_page_template;
	}
	add_filter( 'workreap_list_page_template', 'workreap_update_templates_list');
}

/**
 * After dispute submission
 *
 * @since    1.0.0
*/
if (!function_exists('workreap_after_submit_dispute')) {
    function workreap_after_submit_dispute($dispute_id=0){
        $order_id   = get_post_meta( $dispute_id, '_dispute_order', true );
        $order_id   = !empty($order_id) ? intval($order_id) : 0;
        if( !empty($order_id) ){
            $offers_id   = get_post_meta( $order_id, 'offers_id', true );
            $offers_id   = !empty($offers_id) ? intval($offers_id) : 0;
            if( !empty($offers_id) ){
                update_post_meta( $dispute_id, 'offers_id', $offers_id );
            }
        }
    }
    add_action('workreap_after_submit_dispute', 'workreap_after_submit_dispute');
}

/**
 * Decline task offer
 *
 * @since    1.0.0
*/
if (!function_exists('workreap_task_decline')) {
    function workreap_task_decline($dispute_id=0){
        $order_id   = get_post_meta( $dispute_id, '_dispute_order', true );
        $order_id   = !empty($order_id) ? intval($order_id) : 0;
        if( !empty($order_id) ){
            $offers_id   = get_post_meta( $order_id, 'offers_id', true );
            $offers_id   = !empty($offers_id) ? intval($offers_id) : 0;
            if( !empty($offers_id) ){
                update_post_meta( $dispute_id, 'offers_id', $offers_id );
            }
        }
    }
    add_action('workreap_task_decline', 'workreap_task_decline');
}

/**
 * Project type html
 *
 */
if( !function_exists('workreap_offer_status_tag') ){
    function workreap_offer_status_tag($post_id=0) {
        $post_status    = get_post_status( $post_id );
        $post_status    = !empty($post_status) ? $post_status : '';
        $lable          = "";
        $status_class   = "";
        switch($post_status){
            case 'pending':
                $label          = esc_html__('Pending', 'customized-task-offer');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'draft':
                $label          = esc_html__('Drafted', 'customized-task-offer');
                $status_class   = 'bg-new';
                break;
            case 'publish':
                $label          = esc_html__('Publish', 'customized-task-offer');
                $status_class   = 'wr-tag-ongoing';
                break;
            case 'rejected':
                $label          = esc_html__('Rejected', 'customized-task-offer');
                $status_class   = 'wr-tag-ongoing bg-cancel';
                break;
            default:
                $label          = esc_html__('New', 'customized-task-offer');
                $status_class   = 'bg-new';
                break;
        }
        if( !empty($label) ){
            ob_start();
            ?>
                <div class="wr-tags">
                    <span class="<?php echo esc_attr($status_class);?>"><?php echo esc_html($label);?></span>
                </div>
            <?php 
            echo ob_get_clean();
        }
    }
    add_action('workreap_offer_status_tag', 'workreap_offer_status_tag',10,1);
}