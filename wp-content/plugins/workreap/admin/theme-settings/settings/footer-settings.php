<?php
/**
 * Footer Settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

$theme 		=  wp_get_theme();
$theme_name = $theme->get( 'Name' );
$parent_theme_name = $theme->get( 'Template' );
if($theme->get( 'TextDomain' ) === 'workreap' || $theme_name === 'Workreap' || $parent_theme_name === 'workreap' ){

	Redux::setSection( $opt_name, array(
			'title'            => esc_html__( 'Footer settings', 'workreap' ),
			'id'               => 'footer_settings',
			'subsection'       => false,
			'icon'			   => 'el el-align-center',
			'fields'           => array(
				array(
					'id'       => 'copyright',
					'type'     => 'textarea',
					'title'    => esc_html__( 'Copyright text', 'workreap' ),
					'desc'     => esc_html__( '', 'workreap' ),
					'default'  => esc_html__('© 2024 Workreap','workreap'),
				),
				array(
					'id'        => 'workreap_footer_social_icons',
					'type'      => 'switch',
					'default'   => true,
					'title'     => esc_html__('Footer social icons', 'workreap'),
				),
				array(
					'id'      => 'workreap_footer_facebook_link',
					'type'    => 'text',
					'default' => 'https://www.facebook.com/',
					'title'   => esc_html__( 'Facebook link', 'workreap' ),
					'required' 	=> array('workreap_footer_social_icons','equals','1')
				),
				array(
					'id'      => 'workreap_footer_linkedin_link',
					'type'    => 'text',
					'default' => 'https://www.linkedin.com/',
					'title'   => esc_html__( 'Linkedin link', 'workreap' ),
					'required' 	=> array('workreap_footer_social_icons','equals','1')
				),
				array(
					'id'      => 'workreap_footer_youtube_link',
					'type'    => 'text',
					'default' => 'https://www.youtube.com/',
					'title'   => esc_html__( 'Youtube link', 'workreap' ),
					'required' 	=> array('workreap_footer_social_icons','equals','1')
				),
				array(
					'id'      => 'workreap_footer_twitter_link',
					'type'    => 'text',
					'default' => 'https://www.twitter.com/',
					'title'   => esc_html__( 'Twitter link', 'workreap' ),
					'required' 	=> array('workreap_footer_social_icons','equals','1')
				),
				array(
					'id'      => 'workreap_footer_dribbble_link',
					'type'    => 'text',
					'default' => 'https://dribbble.com/',
					'title'   => esc_html__( 'Dribbble link', 'workreap' ),
					'required' 	=> array('workreap_footer_social_icons','equals','1')
				),
			)
		)
	);

}