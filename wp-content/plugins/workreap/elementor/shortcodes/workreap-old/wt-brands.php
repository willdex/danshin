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

if( !class_exists('Workreap_Brands') ){
	class Workreap_Brands extends Widget_Base {

		public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);
			wp_enqueue_style('owl.carousel');
            wp_enqueue_script('owl.carousel');
        }

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_brands';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Brands', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-slider-album';
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
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'brands',
				[
					'label'  => esc_html__( 'Add Brands', 'workreap' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'link',
							'label' => esc_html__( 'Add Link', 'workreap' ),
							'type'  => Controls_Manager::TEXT,
						],
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Image', 'workreap' ),
							'description'   => esc_html__( 'Upload image.', 'workreap' ),
							'default' => [
								'url' => \Elementor\Utils::get_placeholder_image_src(),
							],
						]
						,
						[
							'name'  => 'link_target',
							'label' => esc_html__( 'Link Target', 'workreap' ),
							'type'  => Controls_Manager::SELECT,
							'default' => '_self',
							'options' => [
								'_blank' 	=> esc_html__('New Tab', 'workreap'),
                    			'_self' 	=> esc_html__('Current Tab', 'workreap'),
							],
						],
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

			$brands     = !empty($settings['brands']) ? $settings['brands'] : array();
			$loop       = !empty($settings['loop'] && $settings['loop'] == 'true' ) ? 'true' : 'false';
			$autoplay   = !empty($settings['autoplay'] && $settings['autoplay'] == 'true' ) ? 'true' : 'false';
			$uniq_flag  = rand(1,9999);
			$is_rtl = 'false';
			if( is_rtl() ){
				$is_rtl = 'true';
			}
			?>
			<div class="wt-sc-brand wt-haslayout">
				<?php if( !empty( $brands ) ) { ?>
					<div id="wt-brandslider-<?php echo esc_attr($uniq_flag); ?>" class="wt-barandslider wt-haslayout owl-carousel">
						<?php foreach ($brands as $brand) { 
							$image   = !empty( $brand['image']['url'] ) ? ($brand['image']['url']) : '';
							$link    = !empty( $brand['link'] ) ? $brand['link'] : '#';
							$target  = !empty( $brand['link_target'] ) ? $brand['link_target'] : '_blank';
							?>
							<figure class="item wt-brandimg">
								<a target="<?php echo esc_attr($target); ?>" href="<?php echo esc_url($link); ?>">
									<img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e('Brand', 'workreap'); ?>">
								</a>
							</figure>
						<?php } ?>
					</div>
					<script type="application/javascript">
						jQuery(document).ready(function () {
							jQuery('#wt-brandslider-<?php echo esc_js($uniq_flag);?>').owlCarousel({
								item: 6,
								loop: "<?php echo esc_js($loop);?>",
								nav:false,
								rtl: <?php echo $is_rtl;?>,
								margin: 0,
								autoplay:"<?php echo esc_js($autoplay);?>",
								responsiveClass:true,
								responsive:{
									0:{items:1,},
									481:{items:2,},
									768:{items:3,},
									991:{items:4,},
									992:{items:5,}
								}
							});
						});
					</script>
				<?php } ?>
			</div>
		<?php 
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Brands ); 
}