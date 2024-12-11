<?php
/**
 * Shortcode for categories v3
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

if( !class_exists('Workreap_Freelancer_By_Skills') ){
	class Workreap_Freelancer_By_Skills extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_freelancer_by_skills';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Freelancers by skills', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-product-categories';
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

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {
			$skills	= workreap_elementor_get_taxonomies('product','skills');
			$skills	= !empty($skills) ? $skills : array();
			
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'section_heading',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Heading', 'workreap' ),
					'description'   => esc_html__( 'Add section heading. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'section_desc',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap' ),
					'description'   => esc_html__( 'Add section description. Leave it empty to hide.', 'workreap' ),
				]
			);

			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Title', 'workreap'),
        			'description' 	=> esc_html__('Add button title. Leave it empty to hide button.', 'workreap'),
				]
			);

			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Title', 'workreap'),
        			'description' 	=> esc_html__('Add explore title, which will be displayed above show all button. Leave it empty to hide button.', 'workreap'),
				]
			);

			$this->add_control(
				'button_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Link', 'workreap'),
        			'description' 	=> esc_html__('Add button link, default will be #', 'workreap'),
				]
			);

			$this->add_control(
				'desc',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Description', 'workreap'),
        			'description' 	=> esc_html__('Add description, which will be displayed above show all button. Leave it empty to hide button.', 'workreap'),
				]
			);
			
			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Link', 'workreap'),
        			'description' 	=> esc_html__('Add button link. Leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Skills?', 'workreap'),
					'desc' 			=> esc_html__('Select Skills to display.', 'workreap'),
					'options'   	=> $skills,
					'multiple' 		=> true,
					'label_block' 	=> true,
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

			$section_heading	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$section_desc 		= !empty($settings['section_desc']) ? $settings['section_desc'] : '';
			$btn_title          = !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link           = !empty($settings['btn_link']) ? $settings['btn_link'] : '#';
			$title      		= !empty($settings['title']) ? $settings['title'] : '';
			$link       		= !empty($settings['link']) ? $settings['link'] : '#';
			$desc       		= !empty($settings['desc']) ? $settings['desc'] : '';
			$post_type          = 'freelancers_search_page';
			$categories         = !empty($settings['categories']) ? $settings['categories'] : array();

			$search_page	= '';
			if( function_exists('workreap_get_page_uri') ){
				$search_page  = workreap_get_page_uri($post_type);
			}
			
			$rand	= rand(1,99999);
			?>
			<div class="wt-sc-explore-categories-v3 wt-haslayout wt-categoriestwo-wrap sc-<?php echo esc_attr($rand);?>">
				<div class="row justify-content-center">
					<?php if (!empty($section_heading) || !empty($section_desc)) { ?>
						<div class="col-12 col-lg-8">
							<div class="wt-sectionheadvtwo wt-textcenter">
								<?php if (!empty($section_heading)) { ?>
									<div class="wt-sectiontitlevtwo">
										<h2><?php echo do_shortcode($section_heading); ?></h2>
									</div>
								<?php } ?>
								<?php if (!empty($section_desc)) { ?>
									<div class="wt-description">
										<?php echo wpautop(do_shortcode($section_desc)); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $categories )  ) { ?>
						<div class="col-12">
							<ul class="wt-categoryvtwo wt-categoryvthree">
								<?php foreach( $categories as $key => $cat_id ) { 
									$category      				= get_term($cat_id);
									$query_arg     				= array();
									$query_arg['skills[]']   	= urlencode($category->slug);
									$permalink                 	= add_query_arg( $query_arg, esc_url($search_page));
									
									$image_url	= "";
									if( function_exists( 'get_field' ) ) {
										$image 		= get_field('thumbnail', $category);
										$image_url	= !empty($image['url']) ? $image['url'] : '';
									}
									?>
									<li>
										<div class="wt-categorycontentvtwo">
											<?php if (!empty($image_url)){ ?>
													<figure>
														<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
													</figure>
											<?php } ?>
											<div class="wt-cattitlevtwo">
												<h4><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_html($category->name); ?></a></h4>
											</div>
											<?php if( !empty( $category->description ) ) { ?>
												<div class="wt-description">
													<p><?php echo esc_html($category->description); ?></p>
												</div>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
								<?php if(!empty($btn_title) || !empty($title) || !empty($desc)) { ?>
									<li class="wt-morecategory">
										<div class="wt-categorycontentvtwo">
											<?php if(!empty($title)) { ?>
												<div class="wt-cattitlevtwo">
													<h4><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h4>
												</div>
											<?php } ?>
											<?php if(!empty($desc)) { ?>
												<div class="wt-description">
													<p><?php echo esc_html($desc); ?></p>
												</div>
											<?php } ?>
											<?php if( $btn_title ) { ?>
											<div class="wt-btnarea">
												<a href="<?php echo esc_url( $btn_link ); ?>" class="wt-btntwo"><?php echo esc_html( $btn_title ); ?></a>
											</div>
											<?php } ?>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register( new Workreap_Freelancer_By_Skills ); 
}