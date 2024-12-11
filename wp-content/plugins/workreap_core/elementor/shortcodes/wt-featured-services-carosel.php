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

if( !class_exists('Workreap_Services_Carousel') ){
	class Workreap_Services_Carousel extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_services_carousel';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Services Carousel', 'workreap_core' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-settings';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      category of shortcode
		 */
		public function get_categories() {
			return [ 'workreap-elements' ];
		}

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {
			if(function_exists('fw_get_db_settings_option')){
				$services_categories	= fw_get_db_settings_option('services_categories');
			}
			
			$services_categories	= !empty($services_categories) ? $services_categories : 'no';

			if( !empty($services_categories) && $services_categories === 'no' ) {
				$taxonomy_type	= 'project_cat';
			}else{
				$taxonomy_type	= 'service_categories';
			}
			
			$services	= elementor_get_taxonomies('micro-services', $taxonomy_type);
			$services	= !empty($services) ? $services : array();
			
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap_core' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add newsletter title. leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Sub title', 'workreap_core'),
        			'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap_core'),
        			'description' 	=> esc_html__('Add newsletter description. leave it empty to hide.', 'workreap_core'),
				]
			);

			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Title', 'workreap_core'),
        			'description' 	=> esc_html__('Add button title. Leave it empty to hide button.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Link', 'workreap_core'),
        			'description' 	=> esc_html__('Add button link. Leave it empty to hide.', 'workreap_core'),
				]
			);
			
			$this->add_control(
				'services',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Choose Category', 'workreap_core'),
					'desc' 			=> esc_html__('Select category services to display.', 'workreap_core'),
					'options'   	=> $services,
					'label_block' 	=> true,
					'multiple' 		=> true,
				]
			);
			
			$this->add_control(
				'listing_type',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Show services by', 'workreap_core'),
					'description' 	=> esc_html__('Select type to list services by featured,verified,latest', 'workreap_core'),
					'default' 		=> '',
					'options' 		=> [
										'' 			=> esc_html__('Select services listing type', 'workreap_core'),
										'featured' 	=> esc_html__('Featured', 'workreap_core'),
										'All' 		=> esc_html__('Recents', 'workreap_core'),
										]
				]
			);
			
			$this->add_control(
				'show_posts',
				[
					'label' => esc_html__( 'Number of posts', 'workreap_core' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'posts' ],
					'range' => [
						'posts' => [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'posts',
						'size' => 6,
					]
				]
			);
			
			$this->add_control(
				'service_by',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Services by ID', 'workreap_core'),
        			'description' 	=> esc_html__('You can add comma separated ID\'s for the services to show specific services. Leave it empty to use above settings', 'workreap_core'),
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

			$title      = !empty( $settings['title'] ) ? $settings['title'] : '';
			$sub_title  = !empty( $settings['sub_title'] ) ? $settings['sub_title'] : '';
			$desc       = !empty( $settings['description'] ) ? $settings['description'] : '';
			$btn_title  = !empty( $settings['btn_title'] ) ? $settings['btn_title'] : '';
			$show_posts = !empty( $settings['show_posts']['size'] ) ? $settings['show_posts']['size'] : 6;
			$layout  	= !empty( $settings['layout'] ) ? $settings['layout'] : 'three';
			$listing_type   = !empty( $settings['listing_type'] ) ? $settings['listing_type'] : '';
			$service_by		= !empty( $settings['service_by'] ) ? $settings['service_by']  : '';
			
			$catgories		= !empty( $settings['services'] ) ? $settings['services']  : '';
			$catgories		= is_array($catgories) ? $catgories : array($catgories);
			
			$width			= 352;
			$height			= 200;
			$flag 			= rand(9999, 999999);
			
			if(function_exists('fw_get_db_settings_option')){
				$services_categories	= fw_get_db_settings_option('services_categories');
			}
			$services_categories	= !empty($services_categories) ? $services_categories : 'no';

			if( !empty($services_categories) && $services_categories === 'no' ) {
				$taxonomy_type	= 'project_cat';
			}else{
				$taxonomy_type	= 'service_categories';
			}
			
			$taxonomy_args = array();
			$tax_query_args  = array();
			$meta_query_args  = array();
			
			if(!empty($catgories)){
				foreach($catgories as $key => $cat){
					$taxonomy_args[]	= array (
										'taxonomy' 	=> $taxonomy_type,
										'field'		=> 'term_id',
										'terms'		=> $cat,
									);
				}
				
				$query_relation = array('relation' => 'OR',);
				$tax_query_args[] = array_merge($query_relation, $taxonomy_args);   
				
			}

			$micro_services = array(
								'posts_per_page' 	=> $show_posts,
								'post_type' 	 	=> 'micro-services',
								'orderby' 			=> 'meta_value_num',
								'order' 			=> 'DESC',
							);
			
			
			//featured services
			if(!empty($listing_type) && $listing_type === 'featured'){
				$meta_query_args[]  = array(
					'key' 			=> '_featured_service_string',
					'value' 		=> 1,
					'compare' 		=> '='
				);
			} else{
				$micro_services['meta_key']  = '_featured_service_string';
				$micro_services['orderby']	 = array( 
					'meta_value' 	=> 'DESC', 
					'ID'      		=> 'DESC'
				); 
			}
			
			if(!empty($service_by)){
				$micro_services['post__in'] = explode(',',$service_by);
			}else{
				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation 		= array('relation' => 'AND',);
					$meta_query_args 		= array_merge($query_relation, $meta_query_args);
					$micro_services['meta_query'] = $meta_query_args;
				}

				//Taxonomy Query
				if ( !empty( $tax_query_args ) ) {
					$query_relation = array('relation' => 'AND',);
					$micro_services['tax_query'] = array_merge($query_relation, $tax_query_args);
				}
			}

			$service_data = new \WP_Query($micro_services); 
			?>
			<div class="wt-sc-micro-services wt-featuredservicescarousel wt-haslayout">
				<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $desc ) ) {?>
					<div class="row justify-content-md-center">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
							<div class="wt-sectionhead wt-textcenter wt-topservices-title">
								<div class="wt-sectiontitle">
									<?php if( !empty( $title ) ) { ?><h2><?php echo esc_html( $title );?></h2><?php } ?>
									<?php if( !empty( $sub_title ) ) { ?><span><?php echo esc_html( $sub_title);?></span><?php } ?>
								</div>
								<?php if( !empty( $desc ) ) { ?>
									<div class="wt-description">
										<p><?php echo do_shortcode( $desc ) ;?></p>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div id="wt-services-silder-<?php echo intval($flag); ?>" class="wt-services-silder owl-carousel">
						<?php 
						if ($service_data->have_posts()) {
							while( $service_data->have_posts() ) { 
								$service_data->the_post();
								global $post;

								$author_id 			= get_the_author_meta( 'ID' );  
								$linked_profile 	= workreap_get_linked_profile_id($author_id);	
								$service_url		= get_the_permalink();
								$db_docs			= array();
								$delivery_time		= '';
								$order_details		= '';

								if (function_exists('fw_get_db_post_option')) {
									$db_docs   			= fw_get_db_post_option($post->ID,'docs');
									$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
									$order_details   	= fw_get_db_post_option($post->ID,'order_details');
								}

								if( count( $db_docs )>1 ) {
									$class	= 'wt-freelancers-services-'.intval( $flag ).' owl-carousel';
								} else {
									$class	= '';
								}

								if( empty($db_docs) ) {
									$empty_image_class	= 'wt-empty-service-image';
									$is_featured		= workreap_service_print_featured( $post->ID, 'yes');
									$is_featured    	= !empty( $is_featured ) ? 'wt-featured-service' : '';
								} else {
									$empty_image_class	= '';
									$is_featured		= '';
								}
							?>
							<div class="wt-services-grid">
								<div class="wt-freelancers-info <?php echo esc_attr( $empty_image_class );?> <?php echo esc_attr( $is_featured );?>">
									<?php if( !empty( $db_docs ) ) {?>
										<div class="wt-freelancers <?php echo esc_attr( $class );?>">
											<?php
											foreach( $db_docs as $key => $doc ){
												$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
												if(function_exists('workreap_prepare_image_source')){
													$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
												}
												
												if(!empty($thumbnail)){
												?>
												<figure class="item">
													<a href="<?php echo esc_url( $service_url );?>">
														<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Service ','workreap_core');?>" class="item">
													</a>
												</figure>
											<?php }} ?>
										</div>
									<?php } ?>
									<?php do_action('workreap_service_print_featured', $post->ID); ?>
									<?php do_action('workreap_service_shortdescription', $post->ID,$linked_profile); ?>
									<?php do_action('workreap_service_type_html',$post->ID);?>
								</div>
							</div>
						<?php } wp_reset_postdata();?>
						<?php }?>
					</div>
					<?php if( !empty( $page_link ) && !empty( $btn_title ) ) {?>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
							<div class="wt-btnarea btn-viewservices">
								<a href="<?php echo esc_url( $page_link );?>" class="wt-btn"><?php echo esc_html($btn_title);?></a>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<script type="application/javascript">
				jQuery(document).ready(function () {
					jQuery('.wt-freelancers-services-<?php echo esc_js($flag);?>').owlCarousel({
						items: 1,
						rtl: <?php echo workreap_owl_rtl_check();?>,
						loop:true,
						nav:true,
						margin: 0,
						autoplay:false,
						navClass: ['wt-prev', 'wt-next'],
						navContainerClass: 'wt-search-slider-nav',
						navText: ['<span class=\"lnr lnr-chevron-left\"></span>', '<span class=\"lnr lnr-chevron-right\"></span>'],
					});

                    jQuery("#wt-services-silder-<?php echo intval($flag); ?>").owlCarousel({
						item: 5,
						rtl: <?php echo workreap_owl_rtl_check();?>,
						loop:false,
						nav:false,
						margin: 30,
						autoplay:true,
						dots: true,
						autoHeight:true,
						dotsClass: 'wt-sliderdots',
						responsiveClass:true,
						responsive:{
							0:{items:1,},
							680:{items:2,},
							1081:{items:3,},
							1440:{items:4,},
							1760:{items:5,}
						}
					});
				});
			</script>
			
			<?php 
		}

	}

	Plugin::instance()->widgets_manager->register( new Workreap_Services_Carousel ); 
}