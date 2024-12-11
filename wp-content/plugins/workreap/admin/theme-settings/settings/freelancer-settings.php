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

$workreap_freelancers = array(
	array(
		'id'        => 'hide_english_level',
		'type'      => 'select',
		'title'     => esc_html__('Hide english level', 'workreap'),
		'desc'      => esc_html__('Hide english level from freelancer settings and profile detail page', 'workreap'),
		'options'   => array(
			'yes'         => esc_html__('Yes', 'workreap'),
			'no'         => esc_html__('No', 'workreap')
		),
		'default'   => 'no',
	),
	array(
		'id'        => 'hide_skills',
		'type'      => 'select',
		'title'     => esc_html__('Hide skills', 'workreap'),
		'desc'      => esc_html__('Hide skills from freelancer settings and profile detail page', 'workreap'),
		'options'   => array(
			'yes'         => esc_html__('Yes', 'workreap'),
			'no'         => esc_html__('No', 'workreap')
		),
		'default'   => 'no',
	),
	array(
		'id'        => 'hide_languages',
		'type'      => 'select',
		'title'     => esc_html__('Hide languages', 'workreap'),
		'desc'      => esc_html__('Hide languages from freelancer settings and profile detail page', 'workreap'),
		'options'   => array(
			'yes'         => esc_html__('Yes', 'workreap'),
			'no'         => esc_html__('No', 'workreap')
		),
		'default'   => 'no',
	),
);


Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Freelancer settings ', 'workreap' ),
	'id'               => 'freelancer_settings',
	'desc'       	   => '',
	'subsection'       => false,
	'icon'			   => 'el el-braille',	
	'fields'           => $workreap_freelancers
	)
);