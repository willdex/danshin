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
$theme_version 	= wp_get_theme();
$datefomate_list= apply_filters('workreap_get_list_date_format', '');
$user_types     = apply_filters('workreap_get_user_types','');
$freelancer_view    = array(
                        'list' => esc_html__('List','workreap'),
                        'grid' => esc_html__('Grid','workreap')
                      );


Redux::setSection( $opt_name, array(
        'title'             => esc_html__( 'Directory settings', 'workreap' ),
        'id'                => 'directories-settings',
        'desc'       	      => '',
        'icon' 			        => 'el el-search',
        'subsection'        => false,
            'fields'           => array(
                  array(
                    'id'        => 'application_access',
                    'type'      => 'select',
                    'title'     => esc_html__('Application Access', 'workreap'),
                    'desc'      => esc_html__('Either projects can enabled or task or you can also enable both', 'workreap'),
                    'options'   => array(
                        'project_based'         => esc_html__('Project based application', 'workreap'),
                        'task_based'            => esc_html__('Task based application', 'workreap'),
                        'both'                  => esc_html__('Both Project and task based application', 'workreap'),
                    ),
                    'default'   => 'both',
                ),
                array(
                  'id'        => 'freelancer_listing_type',
                  'type'      => 'select',
                  'title'     => esc_html__('Freelancer listing view', 'workreap'),
                  'desc'      => esc_html__('Select freelancer listing view type', 'workreap'),
                  'options'   => $freelancer_view,
                  'default'   => 'list',
                ),
                array(
                  'id'        => 'remove_cancel_order',
                  'type'      => 'select',
                  'title'     => esc_html__('Cancel order', 'workreap'),
                  'desc'      => esc_html__('Remove cancel order options from the ongoing orders page', 'workreap'),
                  'default'   => 'no',
                  'options'   => array(
                    'yes'  	=> esc_html__('Yes', 'workreap'),
                    'no'  	=> esc_html__('No', 'workreap'),
                  ),
                ),
                array(
                    'id'       => 'user_update_option',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'User action', 'workreap' ),
                    'default'  => false,
                    'desc'     => esc_html__( 'Either user can submit any form without account approval or account verification is required. For example post a task by freelancers', 'workreap' )
                ),
                array(
                    'id'        => 'invoice_terms',
                    'type'      => 'editor',
                    'title'     => esc_html__('Invoice detail page note', 'workreap'),
                    'default'   => '',
                    'desc'      => esc_html__('Add note for the invoice detail page. ', 'workreap')
                ),
                
                array(
                    'id'        => 'invoice_billing_to',
                    'type'      => 'switch',
                    'title'     => esc_html__('Invoice billing to', 'workreap'),
                    'default'   => false,
                    'desc'      => esc_html__('Enable or disable admin billing address on invoice page', 'workreap'),
                ),
                array(
                    'id'        => 'invoice_billing_address',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Add billing address', 'workreap'),
                    'desc'      => esc_html__('Add billing address to show on invoice page', 'workreap'),
                    'required'  => array('invoice_billing_to', '=', true),
                ),
                array(
                  'id'        => 'invoice_billing_wallet',
                  'type'      => 'textarea',
                  'title'     => esc_html__('Add billing address for wallet', 'workreap'),
                  'desc'      => esc_html__('Add billing address for wallet payments to show on invoice page', 'workreap')
                ),
                array(
                  'id'        => 'invoice_billing_package',
                  'type'      => 'textarea',
                  'title'     => esc_html__('Add billing address for package', 'workreap'),
                  'desc'      => esc_html__('Add billing address for package to show on invoice page', 'workreap')
                ),
                array(
                    'id'        => 'default_image_extensions',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Image file extensions', 'workreap'),
                    'default'   => 'jpg,jpeg,gif,png',
                    'subtitle'  => esc_html__('Add image file extension by comma seperated text', 'workreap'),
                ),
                array(
                    'id'        => 'default_file_extensions',
                    'type'      => 'textarea',
                    'title'     => esc_html__('File extensions', 'workreap'),
                    'default'   => 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,js,php,html,txt',
                    'subtitle'  => esc_html__('Add file extension by comma seperated text', 'workreap'),
                ),
                array(
                  'id'        => 'upload_file_size',
                  'type'      => 'slider',
                  "default" => 5,
                  "min" 		=> 1,
                  "step" 		=> 1,
                  "max" 		=> 500,
                  'title'     => esc_html__('Upload file size', 'workreap'),
                  'desc'   => esc_html__('Add upload file size, this will be in MB, so write only integer value', 'workreap'),
               ),
                array(
                    'id'        => 'dateformat',
                    'type'      => 'select',
                    'title'     => esc_html__('Date format', 'workreap'),
                    'desc'      => esc_html__('Please select date format', 'workreap'),
                    'options'   => $datefomate_list,
                    'default'   => 'Y-m-d',
                ),
                array(
                    'id'        => 'address_format',
                    'type'      => 'select',
                    'title'     => esc_html__('Profile address format', 'workreap'),
                    'desc'      => esc_html__('Please select profile address format', 'workreap'),
                    'options'   => array(
                        'city_country'        => esc_html__('City, Country', 'workreap'),
                        'state_country'       => esc_html__('State, Country', 'workreap'),
                        'city_state_country'  => esc_html__('City, State, Country', 'workreap'),
                    ),
                    'default'   => 'state_country',
                ),
                array(
                    'id'        => 'activity_email',
                    'type'      => 'switch',
                    'title'     => esc_html__('Activity email', 'workreap'),
                    'default'   => true,
                    'desc'      => esc_html__('Enable/disable activity email', 'workreap')
                ),
                array(
                  'id'        => 'enable_state',
                  'type'      => 'switch',
                  'title'     => esc_html__('Enable states option', 'workreap'),
                  'default'   => false,
                  'desc'      => esc_html__('Enable/disable country/states option', 'workreap')
              ),
                array(
                    'id'        => 'shortname_option',
                    'type'      => 'switch',
                    'title'     => esc_html__('Short name', 'workreap'),
                    'default'   => false,
                    'desc'      => esc_html__('Enable/disable shortname', 'workreap')
                ),
                array(
                    'id'        => 'employer_refund_req_title',
                    'type'      => 'text',
                    'title'     => esc_html__('Refund request title', 'workreap'),
                    'default'   => esc_html__('Create refund request', 'workreap'),
                ),
                array(
                    'id'        => 'employer_refund_req_subheading',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Refund request sub heading', 'workreap'),
                    'default'   => '<h5>' . esc_html__('Choose issue you want to highlight', 'workreap') . '</h5>',
                    'subtitle'  => esc_html__('You can add text with HTML tags ', 'workreap'),
                ),
                array(
                  'id'        => 'employer_dispute_issues',
                  'type'      => 'multi_text',
                  'title'     => esc_html__('Employer dispute issues', 'workreap'),
                  'default'   => array(
                    esc_html__('The freelancer is not responding', 'workreap'),
                    esc_html__('The freelancer sent me an unfinished product', 'workreap'),
                    esc_html__('Freelancer is abusive or using unprofessional language', 'workreap'),
                    esc_html__('Freelancer not sure with his/her skills set', 'workreap'),
                    esc_html__('Others', 'workreap'),
                  ),
                  'desc'      => esc_html__('Add multiple dispute issues', 'workreap')
                ),
                array(
                  'id'        => 'freelancer_dispute_issues',
                  'type'      => 'multi_text',
                  'title'     => esc_html__('Freelancer dispute issues', 'workreap'),
                  'default'   => array(
                    esc_html__('The employer is not responding', 'workreap'),
                    esc_html__("I’m too busy to complete this job", 'workreap'),
                    esc_html__('Due to personal reasons, I can not complete this job', 'workreap'),
                    esc_html__('Employer requesting unplanned additional work', 'workreap'),
                    esc_html__('Others', 'workreap'),
                  ),
                  'desc'      => esc_html__('Add multiple dispute issues', 'workreap')
                ),
                array(
                  'id' 		  => 'employer_dispute_option',
                  'type' 		=> 'slider',
                  'title' 	=> esc_html__('Set dispute option for employer', 'workreap'),
                  'desc' 		=> esc_html__('Set min number of days that employer can add dispute', 'workreap'),
                  "default" => 3,
                  "min" 		=> 1,
                  "step" 		=> 1,
                  "max" 		=> 50,
                  'display_value' => 'label',
                ),
                array(
                    'id'        => 'ads_content',
                    'type'      => 'editor',
                    'title'     => esc_html__('Ads content', 'workreap'),
                    'subtitle'  => esc_html__('Add ads content', 'workreap'),
                ),
                array(
                    'id'        => 'admin_dashboard_copyright',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Admin dashboard footer text', 'workreap'),
                    'desc'      => esc_html__('Add admin dashboard footer text', 'workreap'),
                    'default'   => sprintf(esc_html__('Copyright  &copy;%s, All Right Reserved', 'workreap'),date('Y'))
                ),
                array(
                    'id'        => 'min_search_price',
                    'type'      => 'text',
                    'title'     => esc_html__('Min search price', 'workreap'),
                    'default'   => 1,
                ),

                array(
                    'id'        => 'max_search_price',
                    'type'      => 'text',
                    'title'     => esc_html__('Max search price', 'workreap'),
                    'default'   => 5000,
                ),

              array(
                  'id'        => 'disable_range_slider',
                  'type'      => 'switch',
                  'title'     => esc_html__('Disable range slider', 'workreap'),
                  'default'   => true,
                  'desc'      => esc_html__('Disable range slider for price filter', 'workreap')
              ),
        )
	)
);


Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Registration settings', 'workreap' ),
	'id'               => 'registration_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'icon'			   => 'el el-braille',	
	'fields'           =>  array(
        array(
          'id'        => 'defult_register_type',
          'type'      => 'select',
          'title'     => esc_html__('Defult user registration', 'workreap'),
          'desc'      => esc_html__('Please select new user type for defult registration', 'workreap'),
          'options'   => $user_types,
          'default'   => 'employers',
        ),
        array(
          'id'        => 'hide_registration',
          'type'      => 'select',
          'title'     => esc_html__('Remove registration', 'workreap'),
          'desc'      => esc_html__('You can disable registration from front-end', 'workreap'),
          'default'   => 'no',
          'options'   => array(
            'no'  => esc_html__('No', 'workreap'),
            'yes'   => esc_html__('Yes', 'workreap'),
          ),
        ),
        array(
          'id'        => 'hide_role',
          'type'      => 'select',
          'title'     => esc_html__('Hide role', 'workreap'),
          'desc'      => esc_html__('Hide one of the role from registration', 'workreap'),
          'default'   => 'both',
          'options'   => array(
            'both'    => esc_html__('Show both', 'workreap'),
            'freelancers'  => esc_html__('Freelancers', 'workreap'),
            'employers'   => esc_html__('Employers', 'workreap'),
          ),
        ),
        array(
          'id'        => 'registration_view_type',
          'type'      => 'select',
          'title'     => esc_html__('Login and registration type', 'workreap'),
          'desc'      => esc_html__('Please select login/reigistration type', 'workreap'),
          'options'   => array(
            'pages'         => esc_html__('Pages', 'workreap'),
            'popup'         => esc_html__('Popup', 'workreap'),
          ),
          'default'   => 'popup',
        ),
        array(
            'id'		  => 'popup_logo',
            'type' 		=> 'media',
            'url'		  => true,
            'title' 	=> esc_html__('Add logo for Popup', 'workreap'),
            'desc' 		=> esc_html__('Upload site logo for popup.', 'workreap'),
             'required'  => array('registration_view_type', '=', 'popup'),
        ),
        array(
          'id'        => 'email_user_registration',
          'type'      => 'select',
          'title'     => esc_html__('User verification', 'workreap'),
          'desc'      => esc_html__('Please select new user verification type', 'workreap'),
          'options'   => array(
            'verify_by_link'        => esc_html__('Verify by auto generated link', 'workreap'),
            'verify_by_admin'       => esc_html__('Verify by admin', 'workreap'),
          ),
          'default'   => 'verify_by_link',
        ),
		array(
			'id'        => 'user_password_strength',
			'type'      => 'select',
			'title'     => esc_html__('Password strength', 'workreap'),
			'desc'      => esc_html__('You can select password strength options from above.', 'workreap'),
			'options'   => array(
				'length'   			=> esc_html__('Length minimum 6 characters', 'workreap'),
				'upper'				=> esc_html__('1 Upper case letter', 'workreap'),
				'lower'  			=> esc_html__('1 Lower case letter', 'workreap'),
				'special_character' => esc_html__('Must have 1 special character', 'workreap'),
				'number'  			=> esc_html__('Must have 1 number', 'workreap')
			),
			'default'   => 'length',
			'multi'     => true,
		),
        array(
          'id'        => 'user_name_option',
          'type'      => 'switch',
          'title'     => esc_html__('Enable/disable user name', 'workreap'),
          'subtitle'  => esc_html__('Enable/disable user name on registration', 'workreap'),
          'default'   => false,
        ),
        array(
          'id'        => 'identity_verification',
          'type'      => 'switch',
          'title'     => esc_html__('User identity verification', 'workreap'),
          'default'   => false,
          'desc'      => esc_html__('Enable user identity verification, if enabled then users must have to upload identity documents to get verified', 'workreap')
        ),

        array(
          'id'        => 'remove_account_reasons',
          'type'      => 'multi_text',
          'title'     => esc_html__('Deactivate account', 'workreap'),
          'subtitle'  => 'Add deactivate account reasons',
          'default'   => array(
            esc_html__('Not interested anymore', 'workreap')
          )
        ),
        array(
          'id'        => 'switch_user',
          'type'      => 'switch',
          'title'     => esc_html__('Switch user', 'workreap'),
          'default'   => true,
          'desc'      => esc_html__('Enable/disable switch user', 'workreap')
        ),
        array(
          'id'        => 'login_redirect_employer',
          'type'      => 'select',
          'title'     => esc_html__('Login/registration redirect for employers', 'workreap'),
          'desc'      => esc_html__('Select page to redirect the employer after login/registration', 'workreap'),
          'default'   => 'profile',
          'options'   => array(
            'home'        => esc_html__('Home page', 'workreap'),
            'dashboard'   => esc_html__('Dashboard', 'workreap'),
            'freelancer'     => esc_html__('Freelancer search page', 'workreap'),
            'task'           => esc_html__('Task search page', 'workreap'),
          ),
        ),
        array(
          'id'        => 'login_redirect_freelancer',
          'type'      => 'select',
          'title'     => esc_html__('Login/registration redirect for freelancers', 'workreap'),
          'desc'      => esc_html__('Select page to redirect the freelancers after login/registration', 'workreap'),
          'default'   => 'profile',
          'options'   => array(
            'home'        => esc_html__('Home page', 'workreap'),
            'dashboard'   => esc_html__('Dashboard', 'workreap'),
            'projects'    => esc_html__('Freelancer project page', 'workreap'),
          ),
        ),
        array(
          'id'        => 'user_restriction',
          'type'      => 'switch',
          'title'     => esc_html__('After logged in restrict user', 'workreap'),
          'default'   => false,
          'desc'      => esc_html__('Enable/disable user to access front pages after login', 'workreap')
        ),
        array(
          'id'    	=> 'employer_access_pages',
          'type'  	=> 'select',
          'title' 	=> esc_html__( 'Employer restrict pages', 'workreap' ),
          'data'  	=> 'pages',
          'multi'    => true,
          'desc'      => esc_html__('Select restrict pages for employer after logged in', 'workreap'),
          'required'  => array('user_restriction', '=', true),
        ),
        array(
          'id'    	=> 'freelancer_access_pages',
          'type'  	=> 'select',
          'title' 	=> esc_html__( 'Freelancer restrict pages', 'workreap' ),
          'data'  	=> 'pages',
          'multi'    => true,
          'desc'      => esc_html__('Select restrict pages for freelancer after logged in', 'workreap'),
          'required'  => array('user_restriction', '=', true),
        ),
        array(
          'id' 		=> 'portf_max_images',
          'type' 		=> 'slider',
          'title' 	=> esc_html__('Set number portfolio gallery images', 'workreap'),
          'desc' 		=> esc_html__('Set max number portfolio gallery image for the task', 'workreap'),
          "default" 	=> 3,
          "min" 		=> 1,
          "step" 		=> 1,
          "max" 		=> 100,
          'display_value' => 'label',
        ),
      )
	)
);

$required_fields		= workreapProjectValidations();
$recomended_freelancers	= workreap_project_recomended_freelancers();
$project_plan_icon_fields = array(
	array(
		'id'        => 'fixed_projectmin_price',
		'type'      => 'text',
		'title'     => esc_html__('Fixed project min amount', 'workreap'),
		'default'   => 5,
		'desc'      => esc_html__('Add minimum amount for fixed project', 'workreap'),
	),
	array(
		'id'        => 'no_of_freelancers',
		'type'      => 'text',
		'title'     => esc_html__('Add maximum number of freelancers', 'workreap'),
		'default'   => 5,
		'desc'      => esc_html__('Add Maximum number of freelancers that employers add for project creation dropdown', 'workreap'),
	),
	array(
		'id'       => 'project_status',
		'type'     => 'select',
		'title'    => esc_html__('Project default status', 'workreap'),
		'desc'     => esc_html__('Please select the default status of the project', 'workreap'),
		'options'  => array(
			'publish' 	=> esc_html__('Publish', 'workreap'),
			'pending' 	=> esc_html__('Pending', 'workreap')
		),
		'default'  => 'publish',
	),
    array(
        'id'    	=> 'project_edit_after_submit',
        'type'  	=> 'switch',
        'title' 	=> esc_html__( 'Edit submit project', 'workreap' ),
        'desc' 	=> esc_html__( 'Enable/Disable to edit submitted project before approval.', 'workreap' ),
        'required'  => array('project_status', '=', 'pending'),
        'default'  	=> true,
    ),
  array(
		'id'       => 'hide_fixed_milestone',
		'type'     => 'select',
		'title'    => esc_html__('Fixed project options', 'workreap'),
		'desc'     => esc_html__('Hide fixed project options for freelancer if employer has requested the miestone base project', 'workreap'),
		'options'  => array(
			'yes' 	=> esc_html__('Yes, Hide it', 'workreap'),
			'no' 	  => esc_html__('No, Show both options to freelancers', 'workreap')
		),
		'default'  => 'no',
	),
  array(
		'id'       => 'project_multilevel_cat',
		'type'     => 'select',
		'title'    => esc_html__('Enable sub-categories', 'workreap'),
		'options'  => array(
			'enable' 	    => esc_html__('Enable', 'workreap'),
			'disbale' 	  => esc_html__('Disable', 'workreap')
		),
		'default'  => 'disbale',
	),
	array(
		'id'    	=> 'resubmit_project_status',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Does approved task edit approval require?', 'workreap' ),
		'options'  => array(
			'yes' 	=> esc_html__('Yes! It should get approved by the admin every time', 'workreap'),
			'no' 	=> esc_html__('No! Let it approve automatically', 'workreap')
		),
		'required'  => array('project_status', '=', 'pending'),
		'default'  	=> 'no',
	),
	array(
		'id'       	=> 'project_recomended_freelancers',
		'type'  	=> 'select',
		'title'    	=> esc_html__('Project recommended freelancers option','workreap'),
		'desc'      => esc_html__('Select fields for project recommended freelancers','workreap'),
		'options'	=> $recomended_freelancers,
		'multi'    	=> true,
		'default'  	=> array(),
	),
	array(
		'id'        => 'employer_project_dispute_issues',
		'type'      => 'multi_text',
		'title'     => esc_html__('Employer dispute issues', 'workreap'),
		'default'   => array(
		  esc_html__('The freelancer is not responding', 'workreap'),
		  esc_html__('The freelancer sent me an unfinished product', 'workreap'),
		  esc_html__('Freelancer is abusive or using unprofessional language', 'workreap'),
		  esc_html__('Freelancer not sure with his/her skills set', 'workreap'),
		  esc_html__('Others', 'workreap'),
		),
		'desc'      => esc_html__('Add multiple dispute issues', 'workreap')
	  ),
	  array(
		'id'        => 'freelancer_project_dispute_issues',
		'type'      => 'multi_text',
		'title'     => esc_html__('Freelancer dispute issues', 'workreap'),
		'default'   => array(
		  esc_html__('The employer is not responding', 'workreap'),
		  esc_html__("I’m too busy to complete this job", 'workreap'),
		  esc_html__('Due to personal reasons, I can not complete this job', 'workreap'),
		  esc_html__('Employer requesting unplanned additional work', 'workreap'),
		  esc_html__('Others', 'workreap'),
		),
		'desc'      => esc_html__('Add multiple dispute issues', 'workreap')
	  ),
    array(
      'id'       => 'remove_languages',
      'type'     => 'select',
      'title'    => esc_html__('Remove languages', 'workreap'),
      'desc'     => esc_html__('Remove languages from project posting', 'workreap'),
      'options'  => array(
        'yes' 	=> esc_html__('Yes, Hide it', 'workreap'),
        'no' 	  => esc_html__('No, show languages options', 'workreap')
      ),
      'default'  => 'no',
    ),
);

if( !empty($required_fields) ){
	foreach($required_fields as $key => $fields){
		$default_key	= !empty($fields['default']) ? $fields['default'] : array();
		$project_title	= !empty($fields['title']) ? $fields['title'] : "";
		$project_des	= !empty($fields['details']) ? $fields['details'] : "";
		$fields			= !empty($fields['fields']) ? $fields['fields'] : array();
		$project_plan_icon_fields[] = array(
			'id'       	=> 'project_val_step'.$key,
			'type'  	=> 'select',
			'title'    	=> $project_title, 
			'desc'      => $project_des,
			'options'	=> $fields,
			'multi'    	=> true,
			'default'  	=> $default_key,
		  );
	}
}

$project_plan_icon_fields[] = array(
    'id'    	=> 'enable_milestone_feature',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Does approved task edit approval require?', 'workreap' ),
		'options'   => array(
			'yes' 	  => esc_html__('Yes, Display milestone management in the project', 'workreap'),
			'no' 	    => esc_html__('No, Hide this', 'workreap')
		),
		'default'  	=> 'yes',
);

$project_plan_icon_fields[] = array(
  'id'       => 'hide_related',
  'type'     => 'select',
  'title'    => esc_html__('Hide related projects', 'workreap'),
  'desc'     => esc_html__('Hide related projects, default is No', 'workreap'),
  'options'  => array(
    'no' 	      => esc_html__('No', 'workreap'),
    'yes' 	    => esc_html__('Yes', 'workreap')
  ),
  'default'  => 'no',
);

$project_plan_icon_fields[] = array(
  'id'       => 'allow_hour_times',
  'type'     => 'select',
  'title'    => esc_html__('Allow to add previous days hours', 'workreap'),
  'desc'     => esc_html__('Allow the freelancers to add the past/future days hours in the time card. This options will only work when the hourly extension is installed and activated', 'workreap'),
  'options'  => array(
    'past' 	    => esc_html__('Allow only past hours', 'workreap'),
    'both' 	    => esc_html__('Allow past and future hours', 'workreap'),
    'no' 	      => esc_html__('Don\'t allow to add hours', 'workreap'),
  ),
  'default'  => 'past',
);

Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Project settings ', 'workreap' ),
	'id'               => 'project_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'icon'			   => 'el el-braille',	
	'fields'           => $project_plan_icon_fields
	)
);


$proposal_settings = array(
	array(
		'id'       => 'proposal_status',
		'type'     => 'select',
		'title'    => esc_html__('Proposal default status', 'workreap'),
		'desc'     => esc_html__('Please select default status of task', 'workreap'),
		'options'  => array(
			'publish' 	=> esc_html__('Auto approved', 'workreap')
		),
		'default'  => 'publish',
	),
  array(
		'id'       => 'milestone_option',
		'type'     => 'select',
		'title'    => esc_html__('Milestone proposal amount', 'workreap'),
		'options'  => array(
			'allow' 	  => esc_html__('Allow the freelancer to send less amount while submitting proposal', 'workreap'),
      'restrict' 	=> esc_html__('Restrict the freelancer to create milestones within proposed price', 'workreap')
		),
		'default'  => 'allow',
	),
  array(
    'id' 		  => 'credits_required',
    'type' 		=> 'slider',
    'title' 	=> esc_html__('Number of credit', 'workreap'),
    'desc' 		=> esc_html__('Set number of credits to apply on the project', 'workreap'),
    "default" => 5,
    "min" 		=> 1,
    "step" 		=> 1,
    "max" 		=> 50
  )
);
Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Proposal settings ', 'workreap' ),
	'id'               => 'proposal_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'icon'			   => 'el el-braille',	
	'fields'           => $proposal_settings
	)
);