<?php
/**
 * Directories settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
$theme_version 	  = wp_get_theme();
$listing_view     = array( 'left' => esc_html__('Left','workreap'),
						   'top' => esc_html__('Top','workreap')
						);

Redux::setSection( $opt_name, array(
        'title'             => esc_html__( 'Search settings', 'workreap' ),
        'id'                => 'search-settings',
        'desc'       	      => '',
        'icon' 			        => 'el el-search',
        'subsection'        => false,
            'fields'           => array(
                array(
                  'id'        => 'freelancer_listing_type',
                  'type'      => 'select',
                  'title'     => esc_html__('Freelancer filter position', 'workreap'),
                  'desc'      => esc_html__('Select Freelancer filter position', 'workreap'),
                  'options'   => $listing_view,
                  'default'   => 'top',
                ),
				array(
					'id'        => 'projects_listing_view',
					'type'      => 'select',
					'title'     => esc_html__('Projects filter position', 'workreap'),
					'desc'      => esc_html__('Select projects filter position', 'workreap'),
					'options'   => $listing_view,
					'default'   => 'top',
				),
				array(
					'id'        => 'task_listing_view',
					'type'      => 'select',
					'title'     => esc_html__('Task filter position', 'workreap'),
					'desc'      => esc_html__('Select task filter position', 'workreap'),
					'options'   => $listing_view,
					'default'   => 'top',
				),
        )
	)
);
Redux::setSection( $opt_name, array(
    'title'             	=> esc_html__( 'Task search settings', 'workreap' ),
    'id'                	=> 'task_search_settings',
    'desc'       	      	=> '',
    'subsection'        	=> true,
    'icon'			        => 'el el-search',	
    'fields'            	=>  array(			
			array(
				'id'        => 'hide_task_filter_location',
				'type'      => 'switch',
				'title'     => esc_html__('Show location in task search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the task search location filter in the task search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_task_filter_price',
				'type'      => 'switch',
				'title'     => esc_html__('Show price in task search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the price filter in the task search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_task_filter_categories',
				'type'      => 'switch',
				'title'     => esc_html__('Show categories in task search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the categories filter in the task search page', 'workreap'),
				'default'   => true,
			),
//			array(
//				'id'        => 'task_listing_type',
//				'type'      => 'select',
//				'title'     => esc_html__('Task listing type', 'workreap'),
//				'desc'      => esc_html__('Enable Task listing type?', 'workreap'),
//				'options'   => array(
//					'v1'         => esc_html__('V1', 'workreap'),
//					'v2'         => esc_html__('V2', 'workreap')
//				),
//				'default'   => 'v1',
//			),
		)
	));

Redux::setSection( $opt_name, array(
	'title'             	=> esc_html__( 'Project search settings', 'workreap' ),
	'id'                	=> 'project_search_settings',
	'desc'       	      	=> '',
	'subsection'        	=> true,
	'icon'			        => 'el el-search',	
	'fields'            	=>  array(		
			array(
				'id'        => 'hide_project_filter_type',
				'type'      => 'switch',
				'title'     => esc_html__('Show project type in project search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the project type filter in the project search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_location',
				'type'      => 'switch',
				'title'     => esc_html__('Show location in project search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the project search location filter in the project search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_skills',
				'type'      => 'switch',
				'title'     => esc_html__('Show project skills in project search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the project skills filter in the project search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_level',
				'type'      => 'switch',
				'title'     => esc_html__('Show project expertise level in project search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the project expertise level filter in the project search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_language',
				'type'      => 'switch',
				'title'     => esc_html__('Show project languages in project search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the project languages filter in the project search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_price',
				'type'      => 'switch',
				'title'     => esc_html__('Show price in project search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the price filter in the project search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_categories',
				'type'      => 'switch',
				'title'     => esc_html__('Show categories in project search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the categories filter in the project search page', 'workreap'),
				'default'   => true,
			),
		)
	));

Redux::setSection( $opt_name, array(
	'title'             	=> esc_html__( 'Freelancer search settings', 'workreap' ),
	'id'                	=> 'freelancer_search_settings',
	'desc'       	      	=> '',
	'subsection'        	=> true,
	'icon'			        => 'el el-search',	
	'fields'            	=>  array(
			array(
				'id'        => 'hide_freelancer_filter_type',
				'type'      => 'switch',
				'title'     => esc_html__('Show freelancer type in freelancer search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the freelancer type filter in the freelancer search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_freelancer_filter_location',
				'type'      => 'switch',
				'title'     => esc_html__('Show location in freelancer search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the freelancer search location filter in the freelancer search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_freelancer_filter_skills',
				'type'      => 'switch',
				'title'     => esc_html__('Show freelancer skills in freelancer search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the freelancer skills filter in the freelancer search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_freelancer_filter_level',
				'type'      => 'switch',
				'title'     => esc_html__('Show freelancer english level in freelancer search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the freelancer english level filter in the freelancer search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_freelancer_filter_language',
				'type'      => 'switch',
				'title'     => esc_html__('Show freelancer languages in freelancer search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the freelancer languages filter in the freelancer search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_freelancer_filter_price',
				'type'      => 'switch',
				'title'     => esc_html__('Show hourly rate in freelancer search', 'workreap'),
				'subtitle'  => esc_html__('Make it off to hide the hourly rate filter in the freelancer search page', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_freelancer_without_avatar',
				'type'      => 'select',
				'title'     => esc_html__('Hide freelancers', 'workreap'),
				'desc'      => esc_html__('Hide freelancers without profile picture', 'workreap'),
				'options'   => array(
					'yes'	=> esc_html__('Yes, hide profiles', 'workreap'),
					'no'	=> esc_html__('No', 'workreap'),
				),
				'default'   => 'no',
			),
		)
	));