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

Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Payment settings ', 'workreap' ),
	'id'               => 'payout_settings',
	'desc'       	   => '',
	'subsection'       => false,
	'icon'			   => 'el el-braille',	
	'fields'           => array(
			array(
				'id'      => 'freelancer_commision',
				'type'    => 'info',
				'title'   => esc_html__( 'Commission from freelancers', 'workreap' ),
				'style'   => 'info',
			),	
			array(
				'id' 		=> 'admin_commision',
				'type' 		=> 'slider',
				'title' 	=> esc_html__('Admin commission from freelancers', 'workreap'),
				'desc' 		=> esc_html__('Set task/project commission/fee in percentage ( % ), set it to 0 to make commission free website', 'workreap'),
				"default" 	=> 0,
				"min" 		=> 0,
				"step" 		=> 1,
				"max" 		=> 100,
				'display_value' => 'label',
			),
			array(
				'id'      => 'employer_commision',
				'type'    => 'info',
				'title'   => esc_html__( 'Commission from employers', 'workreap' ),
				'style'   => 'info',
			),
			array(
				'id' 		=> 'admin_commision_employers',
				'type' 		=> 'slider',
				'title' 	=> esc_html__('Admin commission from employers', 'workreap'),
				'desc' 		=> esc_html__('Set task/project hiring commission/fee in percentage ( % ), set it to 0 to make commission free website', 'workreap'),
				"default" 	=> 0,
				"min" 		=> 0,
				"step" 		=> 1,
				"max" 		=> 100,
				'display_value' => 'label',
			),
			array(
				'id'       => 'commission_text',
				'type'     => 'text',
				'title'    => esc_html__('Add text', 'workreap'),
				'desc'     => esc_html__('Add commission text, default is: Processing fee', 'workreap'),
				'default'  => esc_html__('Processing fee', 'workreap'),
			),	
			array(
				'id'      => 'general_payment_settings',
				'type'    => 'info',
				'title'   => esc_html__( 'General settings', 'workreap' ),
				'style'   => 'info',
			),
			array(
				'id'       => 'min_amount',
				'type'     => 'text',
				'title'    => esc_html__('Add minimum amount', 'workreap'),
				'desc'     => esc_html__('Add minimum amount which can be withdraw.', 'workreap'),
				'default'  => '',
			),
			
			array(
                'id'        => 'min_wallet_amount',
                'type'      => 'text',
                'title'     => esc_html__('Employer min wallet amount', 'workreap'),
                'default'   => 1,
                'desc'      => esc_html__('Add minimum amount to add wallet', 'workreap'),
            ),
			

			array(
				'id'       => 'payout_item_hide',
				'type'     => 'select',
				'multi'    => true,
				'title'    => esc_html__('Select payout method to hide.', 'workreap'),
				'options'  => array(
					'paypal'		=> esc_html__('PayPal','workreap'),
					'bank'			=> esc_html__('Bank transfer','workreap'),
					'payoneer'		=> esc_html__('Payoneer','workreap'),
				),
			),
		)
	)
);


Redux::setSection( $opt_name, array(
	'title'			=> esc_html__( 'Packages', 'workreap' ),
	'id'			=> 'package_setings',
	'icon'			=> 'el el-braille',
	'subsection'	=> true,
	'fields'		=>  array(
		array(
			'id'       => 'package_option',
			'type'     => 'select',
			'title'    => esc_html__('Packages?', 'workreap'),
			'desc'     => esc_html__('You can enable or disable packages for the both type of users', 'workreap'),
			'options'  => array(
				'both' 			=> esc_html__('Free listing for both type of users', 'workreap'),
				'paid' 			=> esc_html__('Paid listing for both', 'workreap'),
				'employer_free' 	=> esc_html__('Paid listing for freelancers', 'workreap'),
				'freelancer_free' 	=> esc_html__('Paid listing for employers', 'workreap')
			),
			'default'  => 'both'
		),
		array(
			'id'       => 'pkg_page_title',
			'type'     => 'text',
			'title'    => esc_html__('Add price plan title', 'workreap'),
			'desc'     => esc_html__('Add price plan title', 'workreap'),
			'default'  => 'We Genuinely Offer',
		),
		array(
			'id'       => 'pkg_page_sub_title',
			'type'     => 'text',
			'title'    => esc_html__('Add price plan sub title', 'workreap'),
			'desc'     => esc_html__('Add price plan sub title', 'workreap'),
			'default'  => 'Affordable price plans',
		),
		array(
			'id'       => 'pkg_page_details',
			'type'     => 'editor',
			'title'    => esc_html__('Add price plan description', 'workreap'),
			'desc'     => esc_html__('Add price plan description', 'workreap'),
			'default'  => '',
		),
	)
));