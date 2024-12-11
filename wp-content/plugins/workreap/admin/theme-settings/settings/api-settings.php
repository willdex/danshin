<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Api Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
Redux::setSection( $opt_name,
  array(
    'title'       => esc_html__( 'API settings', 'workreap' ),
    'id'          => 'api-settings',
    'subsection'  => false,
    'desc'       	=> '',
    'icon'       	=> 'el el-key',
    'fields'      => array(
      array(
        'id'    =>'divider_1',
        'type'  => 'info',
        'title' => esc_html__('Google API Key', 'workreap'),
        'style' => 'info',
      ),
      array(
        'id'        => 'enable_zipcode',
        'type'      => 'switch',
        'title'     => esc_html__('Zipcode settings', 'workreap'),
        'desc'      => esc_html__('You can enable the zipcode settings and it will verify zipcode from Google Geocoding API and then user will be able to submit the task or profile settings etc. To disable, please make it off', 'workreap'),
        'default'   => false,
      ),
      array(
        'id'       => 'google_map',
        'type'     => 'text',
        'title'    => esc_html__( 'Google Map Key', 'workreap' ),
        'desc' 	   => wp_kses( __( 'Enter google map key here. It will be used for google maps. Get and Api key From <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> Get API KEY </a>', 'workreap' ), array(
          'a' => array(
            'href' => array(),
            'class' => array(),
            'title' => array()
            ),
          'br' => array(),
          'em' => array(),
          'strong' => array(),
        ) ),
        'default'  => '',
        'required'  => array('enable_zipcode', '=', true),
      ),
      array(
        'id'        => 'enable_social_connect',
        'type'      => 'switch',
        'title'     => esc_html__('Google Connect', 'workreap'),
        'subtitle'  => esc_html__('When enable user will able to login and register by using google account', 'workreap'),
        'default'   => false,
      ),
      array(
        'id'    => 'google_client_id',
        'type'  => 'text',
        'title' => esc_html__( 'Client ID', 'workreap' ),
        'required'  => array('enable_social_connect', '=', true),
      ),
      array(
        'id'    => 'google_client_secret',
        'type'  => 'text',
        'title' => esc_html__( 'Client secret', 'workreap' ),
        'required'  => array('enable_social_connect', '=', true),
      ),

      array(
        'id'        => 'enable_ai',
        'type'      => 'switch',
        'title'     => esc_html__('OpenAI', 'workreap'),
        'subtitle'  => esc_html__('When enable user will able to get AI feature', 'workreap'),
        'default'   => false,
      ),
      array(
        'id'    => 'ai_client_id',
        'type'  => 'text',
        'title' => esc_html__( 'OpenAI Key', 'workreap' ),
        'required'  => array('enable_ai', '=', true),
      ),
      array(
		'id'       => 'defaul_ai_img',
		'type'     => 'media',
		'title'    => esc_html__('Default AI image', 'workreap'),
        'default'  => array( 'url' => WORKREAP_DIRECTORY_URI.'/public/images/expertise.svg' ),
        'required'  => array('enable_ai', '=', true),
      ),
	    array(
		    'id'        => 'enable_ai_service',
		    'type'      => 'switch',
		    'title'     => esc_html__('AI prompt enable for service posting?', 'workreap'),
		    'default'   => false,
		    'required'  => array('enable_ai', '=', true),
	    ),
	    array(
		    'id'        => 'enable_ai_service_title',
		    'type'      => 'textarea',
		    'title'     => esc_html__('Add AI prompt for service title', 'workreap'),
		    'subtitle'  => esc_html__('Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.', 'workreap'),
		    'required'  => array('enable_ai_service', '=', true),
	    ),
	    array(
		    'id'        => 'enable_ai_service_content',
		    'type'      => 'textarea',
		    'title'     => esc_html__('Add AI prompt for service content', 'workreap'),
		    'subtitle'  => esc_html__('Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.','workreap'),
		    'required'  => array('enable_ai_service', '=', true),
	    ),
      array(
        'id'        => 'enable_ai_job',
        'type'      => 'switch',
        'title'     => esc_html__('AI prompt enable for project posting?', 'workreap'),
        'required'  => array('enable_ai', '=', true),
      ),
      array(
        'id'        => 'enable_ai_job_title',
        'type'      => 'textarea',
        'title'     => esc_html__('Add AI content for job title', 'workreap'),
        'subtitle'  => esc_html__('Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.', 'workreap'),
        'required'  => array('enable_ai_job', '=', true),
      ),
      array(
        'id'        => 'enable_ai_job_content',
        'type'      => 'textarea',
        'title'     => esc_html__('Add AI content', 'workreap'),
        'subtitle'  => esc_html__('Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.', 'workreap'),
        'required'  => array('enable_ai_job', '=', true),
      ),      
      array(
        'id'        => 'enable_ai_proposal',
        'type'      => 'switch',
        'title'     => esc_html__('AI prompt enable for submit proposal?', 'workreap'),
        'required'  => array('enable_ai', '=', true),
      ),
      array(
        'id'        => 'enable_ai_proposal_content',
        'type'      => 'textarea',
        'title'     => esc_html__('Add AI content for proposal submition', 'workreap'),
        'subtitle'  => esc_html__("Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.", 'workreap'),
        'required'  => array('enable_ai_proposal', '=', true),
      ),
      array(
        'id'        => 'enable_ai_service_hiring',
        'type'      => 'switch',
        'title'     => esc_html__('AI prompt enable for hired service?', 'workreap'),
        'required'  => array('enable_ai', '=', true),
      ),
      array(
        'id'        => 'enable_ai_service_hiring_content',
        'type'      => 'textarea',
        'title'     => esc_html__('Add AI content for hired service', 'workreap'),
        'subtitle'  => esc_html__("Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.", 'workreap'),
        'required'  => array('enable_ai_service_hiring', '=', true),
      ),
      array(
        'id'        => 'enable_ai_project_hiring',
        'type'      => 'switch',
        'title'     => esc_html__('AI prompt enable for hired project?', 'workreap'),
        'required'  => array('enable_ai', '=', true),
      ),
      array(
        'id'        => 'enable_ai_project_hiring_content',
        'type'      => 'textarea',
        'title'     => esc_html__('Add AI content for hired project content', 'workreap'),
        'subtitle'  => esc_html__("Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.", 'workreap'),
        'required'  => array('enable_ai_project_hiring', '=', true),
      ),
      
      array(
        'id'        => 'enable_ai_user',
        'type'      => 'switch',
        'title'     => esc_html__('AI prompt enable for user profile?', 'workreap'),
        'required'  => array('enable_ai', '=', true),
      ),
      array(
        'id'        => 'enable_ai_user_content',
        'type'      => 'textarea',
        'title'     => esc_html__('Add AI prompt for profile', 'workreap'),
        'subtitle'  => esc_html__('Add prompt using the {{ai_content}} parameter, which populates with the response from the OpenAI API.', 'workreap'),
        'required'  => array('enable_ai_user', '=', true),
      ),
    array(
	    'id'        => 'enable_ai_custom_offer',
	    'type'      => 'switch',
	    'title'     => esc_html__('AI prompt enable for custom offer?', 'workreap'),
	    'required'  => array('enable_ai', '=', true),
    ),
    array(
	    'id'        => 'enable_ai_custom_offer_content',
	    'type'      => 'textarea',
	    'title'     => esc_html__('Add AI prompt for custom offer', 'workreap'),
	    'required'  => array('enable_ai_custom_offer', '=', true),
    ),
    )
  )
);