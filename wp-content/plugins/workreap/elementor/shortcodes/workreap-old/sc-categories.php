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

if( !class_exists('Workreap_Caregories') ){
	class Workreap_Caregories extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_categories';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Explore Categories', 'workreap' );
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
						
			$categories	= workreap_elementor_get_taxonomies('product','product_cat');
			$categories	= !empty($categories) ? $categories : array();
			
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
				'section_subheading',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Sub Heading', 'workreap' ),
					'description'   => esc_html__( 'Add section sub heading. Leave it empty to hide.', 'workreap' ),
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
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Link', 'workreap'),
        			'description' 	=> esc_html__('Add button link. Leave it empty to hide.', 'workreap'),
				]
			);
			$this->add_control(
				'post_type',
				[
					'label' => esc_html__( 'Product Type?', 'workreap' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'project_search_page',
					'options' => [
						'project_search_page' => esc_html__('Projects', 'workreap'),
						'service_search_page' => esc_html__('Services', 'workreap'),
					],
				]
			);
			
			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Categories?', 'workreap'),
					'desc' 			=> esc_html__('Select categories to display.', 'workreap'),
					'options'   	=> $categories,
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
			$settings 				= $this->get_settings_for_display();
			$section_heading     	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$section_sub_heading 	= !empty($settings['section_subheading']) ? $settings['section_subheading'] : '';
			$btn_title           	= !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link            	= !empty($settings['btn_link']) ? $settings['btn_link'] : '';
			$post_type           	= !empty($settings['post_type']) ? $settings['post_type'] : 'project_search_page';
			$categories          	= !empty($settings['categories']) ? $settings['categories'] : array();
			
			$search_page	= '';
			if( function_exists('workreap_get_page_uri') ){
				$search_page  = workreap_get_page_uri($post_type);
			}
			?>
			<div class="wt-sc-explore-categories-default wt-haslayout">
				<div class="row justify-content-md-center">
					<?php if (!empty($section_heading) || !empty($section_sub_heading)) { ?>
						<div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
							<div class="wt-sectionhead wt-textcenter">
								<div class="wt-sectiontitle">
									<?php if (!empty($section_sub_heading)) { ?>
                                        <span><?php echo esc_html($section_sub_heading); ?></span>
									<?php } ?>
									<?php if (!empty($section_heading)) { ?>
										<h2><?php echo esc_html($section_heading); ?></h2>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $categories )  ) { ?>
						<div class="wt-categoryexpl">
							<?php foreach( $categories as $key => $cat_id ) { 
								$category      = get_term($cat_id);
								$icon          = array();
								
								$thumbnail_id 				= get_woocommerce_term_meta( $cat_id, 'thumbnail_id', true );
								$image        				= !empty($thumbnail_id) ? wp_get_attachment_url( $thumbnail_id ) : '';
								$query_arg['category']   	= urlencode($category->slug);
								$permalink                 = add_query_arg( $query_arg, esc_url($search_page));
								?>
								<div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 float-left">
									<div class="wt-categorycontent-flipper wt-haslayout">
										<div class="wt-categorycontent">
											<div class="wt-front-board">
												<?php
													if (!empty($image)) {?>
														<figure><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>"></figure>
														<?php
													}
												?>
												<div class="wt-cattitle">
													<h3><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_html($category->name); ?></a></h3>
												</div>
											</div>
											<div class="wt-categoryslidup">
												<?php if( !empty( $category->description ) ) { ?>
													<p><?php echo esc_html($category->description); ?></p>
												<?php } ?>
												<a href="<?php echo esc_url( $permalink );?>"><?php esc_html_e('Explore', 'workreap') ?>&nbsp;<i class="fa fa-arrow-right"></i></a>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if( $btn_title ) { ?>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
									<div class="wt-btnarea">
										<a href="<?php echo esc_url( $btn_link ); ?>" class="wt-btn"><?php echo esc_html( $btn_title ); ?></a>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register( new Workreap_Caregories ); 
}