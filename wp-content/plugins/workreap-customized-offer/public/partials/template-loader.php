<?php
/**
 * Template loader
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/public
 */
if (!class_exists('Workreap_Customized_Task_Offers_PageTemplaterLoader')) {
    class Workreap_Customized_Task_Offers_PageTemplaterLoader {

        private static $instance;
        protected $templates;

        //get class instance
        public static function get_instance() {

            if ( null == self::$instance ) {
                self::$instance = new Workreap_Customized_Task_Offers_PageTemplaterLoader();
            }

            return self::$instance;
        }

        //Constructor
        private function __construct() {
            $this->templates = array();

            if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
                add_filter('page_attributes_dropdown_pages_args',array( $this, 'register_custom_templates' ));
            } else {
                add_filter('theme_page_templates', array( $this, 'workreap_add_new_template' ));
            }

            add_filter('wp_insert_post_data', array( $this, 'workreap_register_custom_templates' ) );
            add_filter('template_include', array( $this, 'workreap_view_custom_templates'), 9999 );
            $this->templates = array(
                'templates/add-offer.php'   => esc_html__('Add Offer','customized-task-offer'),
            );
        }

        //Add new templates
        public function workreap_add_new_template( $posts_templates ) {
            $posts_templates = array_merge( $posts_templates, $this->templates );
            return $posts_templates;
        }

        //Register Templates
        public function workreap_register_custom_templates( $atts ) {
            $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

            $templates = wp_get_theme()->get_page_templates();
            if ( empty( $templates ) ) {
                $templates = array();
            }

            wp_cache_delete( $cache_key , 'themes');
            $templates = array_merge( $templates, $this->templates );
            wp_cache_add( $cache_key, $templates, 'themes', 1800 );

            return $atts;

        }

        //Embed into dropdown
        public function workreap_view_custom_templates( $template ) {
            global $post,$woocommerce,$product;
           
            if ( ! $post ) {
                return $template;
            }

            if (is_singular() && $post->post_type == 'offers') {
              
                $template = workreap_custom_task_offer_locate_template( 'single-offer.php');

                if ( '' != $template ) {
                    return $template ;
                }
            }

            if ( ! isset( $this->templates[get_post_meta( $post->ID, '_wp_page_template', true )] ) ) {
                return $template;
            }

            $file = WORKREAP_CUSTOMIZED_TASK_OFFERS . get_post_meta($post->ID, '_wp_page_template', true);

            if ( file_exists( $file ) ) {
                return $file;
            } else {
                return $file;
            }
            return $template;
        }
    }
    
    add_action( 'plugins_loaded', array( 'Workreap_Customized_Task_Offers_PageTemplaterLoader', 'get_instance' ) );
}
