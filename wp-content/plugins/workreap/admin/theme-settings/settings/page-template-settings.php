<?php
/**
 * Template Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
$add_page_template	= array(
	array(
		'id'    	=> 'tpl_admin_dashboard',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Administrator dashboard', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select page for the administrator dashboard page', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_dashboard',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'User dashboard', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select page for the dashboard page', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_terms_conditions',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Terms & conditions', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select page for the terms & conditions', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_privacy',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Privacy policy', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select page for the privacy', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_login',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Login', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select login page', 'workreap'),
		'required'  => array('registration_view_type', '=', 'pages'),
	),
	array(
		'id'    	=> 'tpl_registration',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Registration', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select registration page', 'workreap'),
		'required'  => array('registration_view_type', '=', 'pages'),
	),
	  array(
		'id'    	=> 'tpl_forgot_password',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Forgot password', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select forgot password page', 'workreap'),
		'required'  => array('registration_view_type', '=', 'pages'),
	),
	  array(
		'id'    	=> 'tpl_service_search_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Search task', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select task search page', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_project_search_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Search project', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select project search page', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_freelancers_search_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Search freelancers', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select freelancers search page', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_add_service_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Add/edit task', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select add/edit task page template', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_add_project_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Add/edit project', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select add/edit project page template', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_submit_proposal_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Submit proposal', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select submit proposal page template', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_package_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Select packages page', 'workreap' ),
		'data'  	=> 'pages',
		'desc'      => esc_html__('Select packages page template', 'workreap'),

	),
);
$add_page_template	= apply_filters( 'workreap_list_page_template', $add_page_template );
Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Template settings', 'workreap' ),
        'id'               => 'template_settings',
        'desc'       	   => '',
		'icon' 			   => 'el el-search',
		'subsection'       => false,
        'fields'           => $add_page_template
	)
);
