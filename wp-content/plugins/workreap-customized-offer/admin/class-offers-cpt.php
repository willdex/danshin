<?php
/**
 * 
 * Class 'Workreap_Customized_Task_Offers_Addon_CPT_Offer' defines the custom post type offers
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Workreap_Admin_CPT_Offer {

	/**
	 * Buyers post type
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action('init', array(&$this, 'init_post_type'));		
	}

	/**
	 * @Init post type
	*/
	public function init_post_type() {
		$this->register_posttype();
	}

	/**
	 *Regirster employer post type
	*/ 
	public function register_posttype() {
		$labels = array(
			'name'                  => esc_html__( 'Customized offers', 'customized-task-offer' ),
			'singular_name'         => esc_html__( 'Offer', 'customized-task-offer' ),
			'menu_name'             => esc_html__( 'Offers', 'customized-task-offer' ),
			'name_admin_bar'        => esc_html__( 'Offers', 'customized-task-offer' ),
			'parent_item_colon'     => esc_html__( 'Parent offer:', 'customized-task-offer' ),
			'all_items'             => esc_html__( 'All offers', 'customized-task-offer' ),
			'add_new_item'          => esc_html__( 'Add new offer', 'customized-task-offer' ),
			'add_new'               => esc_html__( 'Add new offer', 'customized-task-offer' ),
			'new_item'              => esc_html__( 'New offer', 'customized-task-offer' ),
			'edit_item'             => esc_html__( 'Edit offer', 'customized-task-offer' ),
			'update_item'           => esc_html__( 'Update offer', 'customized-task-offer' ),
			'view_item'             => esc_html__( 'View offers', 'customized-task-offer' ),
			'view_items'            => esc_html__( 'View offers', 'customized-task-offer' ),
			'search_items'          => esc_html__( 'Search offers', 'customized-task-offer' ),
		);
		
		$args = array(
			'label'                 => esc_html__( 'Offers', 'customized-task-offer' ),
			'description'           => esc_html__( 'All offers.', 'customized-task-offer' ),
			'labels'                => apply_filters('workreap_product_taxonomy_duration_labels', $labels),
			'supports'              => array( 'title','editor','author','excerpt','thumbnail' ),
			'public' 				=> true,
			'show_ui' 				=> true,
			'capability_type' 		=> 'post',
			'map_meta_cap' 			=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> true,
			'hierarchical' 			=> false,
			'menu_position' 		=> 10,
			'rewrite' 				=> array('slug' => 'offer', 'with_front' => true),
			'query_var' 			=> false,
			'has_archive' 			=> false,
			'show_in_menu' 			=> 'edit.php?post_type=freelancers',
			'capabilities' 			=> array(
										'create_posts' => true
									),	
			'rest_base'             => 'offers',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		
		register_post_type( apply_filters('workreap_offers_post_type_name', 'offers'), $args );

	}  
}

new Workreap_Admin_CPT_Offer();