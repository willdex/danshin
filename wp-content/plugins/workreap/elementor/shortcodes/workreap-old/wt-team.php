<?php
/**
 * Shortcode
 *
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 * @author     Amentotech <theamentotech@gmail.com>
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists('Workreap_Teams') ){
	class Workreap_Teams extends Widget_Base {

		public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);
			wp_enqueue_script('hoverdir');
        }
		
		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_teams';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Teams', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-person';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      category of shortcode
		 */
		public function get_categories() {
			return [ 'workreap-ele' ];
		}

		public function get_script_depends() {
			return [ 'hoverdir' ];
		}

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Title', 'workreap' ),
					'description'   => esc_html__( 'Add section title. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Sub title', 'workreap' ),
					'description'   => esc_html__( 'Add section sub title. Leave it empty to hide.', 'workreap' ),
				]
			);

			
			
			$this->add_control(
				'team_members',
				[
					'label'  => esc_html__( 'Add team Member', 'workreap' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'name',
							'label' => esc_html__( 'Add name', 'workreap' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name'  => 'designation',
							'label' => esc_html__( 'Add designation', 'workreap' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name' 			=> 'avatar',
							'type'      	=> Controls_Manager::MEDIA,
							'default' => [
								'url' => \Elementor\Utils::get_placeholder_image_src(),
							],
							'label'     	=> esc_html__( 'Upload Image', 'workreap' ),
							'description'   => esc_html__( 'Upload image.', 'workreap' ),
						],
						[
							'name' 			=> 'facebook_link',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Facebook link', 'workreap' ),
							'description'   => esc_html__( 'Add facebook link. Leave it empty to hide.', 'workreap' ),
						],
						[
							'name' 			=> 'twitter_link',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Twitter link', 'workreap' ),
							'description'   => esc_html__( 'Add twitter link. Leave it empty to hide.', 'workreap' ),
						],
						[
							'name' 			=> 'linkedin_link',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'LinkedIn link', 'workreap' ),
							'description'   => esc_html__( 'Add LinkedIn link. Leave it empty to hide.', 'workreap' ),
						],
						[
							'name' 			=> 'instagram_link',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Instagram link', 'workreap' ),
							'description'   => esc_html__( 'Add instagram link. Leave it empty to hide.', 'workreap' ),
						],
						[
							'name' 			=> 'googleplus_link',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Google plus link', 'workreap' ),
							'description'   => esc_html__( 'Add google plus link. Leave it empty to hide.', 'workreap' ),
						],
						[
							'name' 			=> 'youtube_link',
							'type'      	=> Controls_Manager::TEXT,
							'label'     	=> esc_html__( 'Youtube link', 'workreap' ),
							'description'   => esc_html__( 'Add youtube link. Leave it empty to hide.', 'workreap' ),
						]
						
					],
					'default' => [],
				]
			);
			
			$this->add_control(
				'loop',
				[
					'label' 		=> esc_html__( 'Loop', 'workreap' ),
					'type'  		=> Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'True', 'workreap' ),
					'label_off' 	=> esc_html__( 'False', 'workreap' ),
					'return_value' 	=> 'true',
					'default' 		=> 'false',
				]
			);
			
			$this->add_control(
				'autoplay',
				[
					'label' 		=> esc_html__( 'Autoplay', 'workreap' ),
					'type'  		=> Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'True', 'workreap' ),
					'label_off' 	=> esc_html__( 'False', 'workreap' ),
					'return_value' 	=> 'true',
					'default' 		=> 'false',
				]
			);
			
			$this->end_controls_section();
		}

		/**
		 * Render shortcode
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();

			$title        = !empty($settings['title']) ? $settings['title'] : '';
			$sub_title    = !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$team_members = !empty($settings['team_members']) ? $settings['team_members'] : array();
			$loop         = !empty($settings['loop'] && $settings['loop'] == 'true' ) ? 'true' : 'false';
			$autoplay     = !empty($settings['autoplay'] && $settings['autoplay'] == 'true' ) ? 'true' : 'false';
			$uniq_flag    = rand(1,9999);


			?>
			<div class="wt-sc-teams wt-haslayout">
				<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $team_members ) ) { ?>
					<div class="wt-ourteamhold wt-haslayout wt-bgwhite">
						<div id="filter-masonry" class="wt-teamfilter wt-haslayout">
							<?php if( !empty( $title ) || !empty( $sub_title ) ) { ?>
							<div class="wt-sectionhead">
								<div class="wt-sectiontitle">
									<?php if( !empty( $sub_title ) ) { ?>
                                        <span><?php echo esc_html( $sub_title ); ?></span>
									<?php } ?>
									<?php if( !empty( $title ) ) { ?>
										<h2><?php echo esc_html( $title ); ?></h2>
									<?php } ?>
								</div>
							</div>
							<?php } ?>
							<?php if( !empty( $team_members ) ) { 
								foreach( $team_members as $member ) {
									$avatar 	  = !empty( $member['avatar']['url'] ) ? $member['avatar']['url'] : get_template_directory_uri().'/images/avatar.jpg';
									$name         = !empty( $member['name'] ) ? $member['name'] : '';
									$designation  = !empty( $member['designation'] ) ? $member['designation'] : '';
									
									$social_links	= workreap_social_profiles();

									if( !empty( $avatar ) ||
										!empty( $name ) ||
										!empty( $designation )   ) { 
										?>
										<div class="item wt-teamholder wt-teamholder-<?php echo esc_attr( $uniq_flag );?>">
											<?php if( !empty( $avatar ) ) { ?>
												<figure class="wt-speakerimg">
													<img src="<?php echo esc_attr( $avatar ); ?>" alt="<?php echo esc_attr($name) ?>">
												</figure>
											<?php } ?>
											<?php if( !empty( $name ) || !empty( $designation ) ) { ?>
												<div class="wt-teamcontent">
													<?php if( !empty( $name ) || !empty( $designation ) ) { ?>
														<div class="wt-title">
															<?php if( !empty( $name ) ) { ?>
																<h2><?php echo esc_html( $name ); ?></h2>
															<?php } ?>
															<?php if( !empty( $designation ) ) { ?>
																<span><?php echo esc_html( $designation ); ?></span>
															<?php } ?>
														</div>
													<?php } ?>
													<ul class="wt-socialicons wt-socialiconssimple">
														<?php 
															foreach ( $social_links as $key => $social_link ) { 
																if( !empty( $member[$key]) ) { 
															?>
																<li class="wt-social-icons <?php echo esc_attr( $social_link['class'] ); ?>">
																	<a href="<?php echo esc_url( $member[$key] ); ?>">
																		<i class="<?php echo esc_attr( $social_link['icon'] ); ?>"></i>
																	</a>
																</li>
															<?php } ?>
														<?php } ?>                                      
													</ul>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
							<script type="application/javascript">
								jQuery(function () {
									jQuery('.wt-teamholder-<?php echo esc_js( $uniq_flag );?>').each(function () {
										 jQuery(this).hoverdir();
									});
								});
							</script>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Teams ); 
}