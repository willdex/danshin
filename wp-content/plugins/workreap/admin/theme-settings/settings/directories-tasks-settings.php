<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Task settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

$workreap_plan_icon_fields = array(
	array(
		'id'       => 'custom_field_option',
		'type'     => 'switch',
		'title'    => esc_html__( 'Enable/disable custom field for freelancers', 'workreap' ),
		'default'  => false,
		'desc'     => esc_html__( 'Enable/disable custom field for freelancers to add while creating a task', 'workreap' )
	),
	array(
		'id' 		=> 'maxnumber_fields',
		'type' 		=> 'slider',
		'title' 	=> esc_html__('Set number of custom fields', 'workreap'),
		'desc' 		=> esc_html__('Set max number of fields that freelancer can add while creating a task', 'workreap'),
		"default" 	=> 5,
		"min" 		=> 1,
		"step" 		=> 1,
		"max" 		=> 100,
		'display_value' => 'label',
		'required'  => array('custom_field_option', '=', true),
	),
	array(
		'id' 		=> 'task_max_images',
		'type' 		=> 'slider',
		'title' 	=> esc_html__('Set number gallery images', 'workreap'),
		'desc' 		=> esc_html__('Set max number gallery image for the task', 'workreap'),
		"default" 	=> 3,
		"min" 		=> 1,
		"step" 		=> 1,
		"max" 		=> 100,
		'display_value' => 'label',
	),
	array(
		'id'       => 'task_downloadable',
		'type'     => 'switch',
		'title'    => esc_html__( 'Enable/disable task for downloadable', 'workreap' ),
		'default'  => true,
		'desc'     => esc_html__( 'Enable/disable sellser option to add downloadable task', 'workreap' )
	),
	array(
		'id'       => 'allow_tags',
		'type'     => 'switch',
		'title'    => esc_html__( 'Allow tags', 'workreap' ),
		'default'  => true,
		'desc'     => esc_html__( 'Allow tags while creating task', 'workreap' )
	),
    array(
        'id'       => 'task_description_length_option',
        'type'     => 'switch',
        'title'    => esc_html__( 'Enable/disable task description length', 'workreap' ),
        'default'  => false,
        'desc'     => esc_html__( 'Enable/disable to add minimum and maximum description length while creating a task', 'workreap' )
    ),
    array(
        'id' => 'task_description_length',
        'type' => 'slider',
        'title' => __('Task description length', 'workreap'),
        'desc' => __(' Define the minimum and maximum task description word length', 'workreap'),
        'default' => array(
            1 => 50,
            2 => 500,
        ),
        'min' => 0,
        'step' => 5,
        'max' => 1000,
        'display_value' => 'select',
        'handles' => 2,
        'required'  => array('task_description_length_option', '=', true),
    ),
	array(
		'id'    	=> 'hide_product_cat',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Hide product category', 'workreap' ),
		'data' 		=> 'terms',
		'args' 		=> array('taxonomies' => array( 'product_cat' ),'hide_empty' => false,),
		'multi'    	=> true,
		'desc'      => esc_html__('Select product for hiding on the search page', 'workreap'),
	),
//	array(
//		'id'        => 'task_listing_type',
//		'type'      => 'select',
//		'title'     => esc_html__('Task listing type', 'workreap'),
//		'desc'      => esc_html__('Enable Task listing type?', 'workreap'),
//		'options'   => array(
//			'v1'         => esc_html__('V1', 'workreap'),
//			'v2'         => esc_html__('V2', 'workreap')
//		),
//		'default'   => 'v1',
//	),

);

$workreap_service_plans = Workreap_Service_Plans::service_plans();
foreach($workreap_service_plans as $plan_key => $plan_feilds){
  $workreap_plan_icon_fields[] = array(
    'id'       => 'task_plan_icon_'.$plan_key,
    'type'     => 'media',
    'title'    => wp_sprintf( '%s %s', ucfirst($plan_key), esc_html__( ' plan icon', 'workreap' ) ),
    'default'  => array( 'url' => WORKREAP_DIRECTORY_URI.'/public/images/task-plan-icon.jpg' ),
  );
}

$workreap_plan_icon_fields[] = array(
	'id'       => 'hide_deadline',
	'type'     => 'select',
	'title'    => esc_html__('Hide task dealine', 'workreap'),
	'desc'     => esc_html__('You can hide the task deadline from ongoing order', 'workreap'),
	'options'  => array(
		'yes' 	=> esc_html__('Yes', 'workreap'),
		'no' 	=> esc_html__('No', 'workreap')
	),
	'default'  => 'no',
);

$workreap_plan_icon_fields[] = array(
	'id'       => 'service_status',
	'type'     => 'select',
	'title'    => esc_html__('Task default status', 'workreap'),
	'desc'     => esc_html__('Please select default status of task', 'workreap'),
	'options'  => array(
		'publish' 	=> esc_html__('Publish', 'workreap'),
		'pending' 	=> esc_html__('Pending', 'workreap')
	),
	'default'  => 'publish',
);

$workreap_plan_icon_fields[] = array(
	'id'       => 'remove_price_plans',
	'type'     => 'select',
	'title'    => esc_html__('Show only one package', 'workreap'),
	'desc'     => esc_html__('Show only one package while posting a service and hide other two packages', 'workreap'),
	'options'  => array(
		'yes' 	=> esc_html__('Yes, Show only one', 'workreap'),
		'no' 	=> esc_html__('No, Show 3 packages', 'workreap')
	),
	'default'  => 'no',
);

$workreap_plan_icon_fields[] = array(
	'id'    	=> 'resubmit_service_status',
	'type'  	=> 'select',
	'title' 	=> esc_html__( 'Does approved task edit approval require?', 'workreap' ),
	'options'  => array(
		'yes' 	=> esc_html__('Yes! It should get approved by the admin every time', 'workreap'),
		'no' 	=> esc_html__('No! Let it approve automatically', 'workreap')
	),
	'required'  => array('service_status', '=', 'pending'),
	'default'  	=> 'no',
);


Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Task settings ', 'workreap' ),
	'id'               => 'task_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'icon'			   => 'el el-braille',	
	'fields'           => $workreap_plan_icon_fields
	)
);