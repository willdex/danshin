<?php
/**
 * Seller task listings
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/templates/
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $post, $current_user,$workreap_settings;
$ref                = !empty($_GET['ref']) ? esc_html($_GET['ref']) : '';
$mode               = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity      = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;

$user_type          = apply_filters('workreap_get_user_type', $current_user->ID );
$task_allowed       = workreap_task_create_allowed($current_user->ID);
$package_detail     = workreap_get_package($current_user->ID);
$employer_profile_id   = workreap_get_linked_profile_id($current_user->ID, 'users', 'employers');

$order_type     = !empty($_GET['order_type']) ? $_GET['order_type'] : 'any';
$menu_order     = workreap_list_offers_status_filter();
$page_url       = Workreap_Profile_Menu::workreap_profile_menu_link($ref, $user_identity, true, $mode);

$workreap_add_offer_page_url = '';
$workreap_add_offer_page_url = !empty($workreap_settings['tpl_add_offer_page']) ? get_permalink($workreap_settings['tpl_add_offer_page']) : '';
?>
<div class="wr-dhb-mainheading">
    <h2><?php esc_html_e('Customized offers', 'customized-task-offer');?></h2>
    <?php do_action('workreap_service_listing_notice');?>
    <div class="wr-sortby">
        <div class="wr-actionselect wr-actionselect2">
            <span><?php esc_html_e('Filter by:','customized-task-offer');?></span>
            <div class="wr-select">
                <select id="wr_task_type" name="order_type" class="form-control wr-selectv">
                    <?php foreach($menu_order as $key => $val ){
                        $selected   = '';

                        if( !empty($order_type) && $order_type == $key ){
                            $selected   = 'selected';
                        }
                        ?>
                        <option data-url="<?php echo esc_url($page_url);?>&task_type=<?php echo esc_attr($key);?>" value="<?php echo esc_attr($key);?>" <?php echo esc_attr($selected);?>>
                            <?php echo esc_html($val);?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$workreap_args = array(
    'post_type'         => 'offers',
    'post_status'       => array('publish', 'pending', 'draft','private', 'rejected'), 
    'posts_per_page'    => get_option('posts_per_page'),
    'paged'             => $paged,
    'orderby'           => 'date',
    'order'             => 'DESC',
    'meta_query' => array(
        array(
            'key'     => 'employer_id',
            'value'   => $employer_profile_id,
            'compare' => '=',
        ),
    ),
);
if(!empty($order_type) && $order_type!= 'any' ){

    $workreap_args['post_status'] = $order_type;
}

$workreap_query = new WP_Query( apply_filters('workreap_employer_offers_listings_args', $workreap_args) );

if ( $workreap_query->have_posts() ) :
    ?>
    <ul class="wr-savelisting wr-offer-listing">
        <?php do_action('workreap_employer_offers_listing_before');?>
        <?php
        while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
            $post           = get_post( $post->ID );
            $task_id        = get_post_meta($post->ID, 'task_id', true);
            $employer_id       = get_post_meta($post->ID, 'employer_id', true);
            $offer_price    = get_post_meta($post->ID, 'offer_price', true);
            $class_css      = '';
            $post_status    = get_post_status( $post->ID);

            $post_status    = !empty($post_status) ? $post_status : '';
            if(!empty($post_status) && $post_status == 'rejected'){
                $class_css   = 'wr-itemlinks-wt';
            }

            $profile_id = workreap_get_linked_profile_id($employer_id);
            $employer_name = workreap_get_username($employer_id);
 
            if(!empty($employer_id)) {
                $avatar = apply_filters(
                    'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $employer_id), array('width' => 50, 'height' => 50)
                );
                
                if (empty($avatar)){
                    $user_dp = workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/fravatar-50x50.jpg');
                    $avatar = !empty($workreap_settings['workreap_default_user_image']) ? $workreap_settings['defaul_freelancers_profile'] : $user_dp;
                }
            } 
            $workreap_add_service_page_edit_url = 'javascript:void(0);';
            if($workreap_add_offer_page_url){
                $workreap_add_service_page_edit_url = add_query_arg( array(
                    'post'    => $post->ID,
                    'step'    => 1,
                ), $workreap_add_offer_page_url );
            }
            $task_order_url = get_the_permalink($post->ID);
            ?>
            <li id="post-<?php the_ID(); ?>" <?php post_class('wr-tabbitem'); ?>>
                <?php do_action('workreap_employer_offers_item_before', $post);?>
                <div class="wr-tabbitem__list wr-tabbitem__listtwo">
                    <div class="wr-deatlswithimg">
                        <div class="wr-offer-holder">
                            <?php do_action('workreap_offer_status_tag', $post->ID);?>
                            <div class="wr-icondetails">
                                <?php echo do_action('workreap_task_categories', $task_id, 'product_cat');?>
                                <h6><?php the_title();?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="wr-itemlinks <?php echo esc_attr($class_css)?>">
                        <?php if(!empty($offer_price)){?>
                            <div class="wr-startingprice">
                                <i><?php esc_html_e('Customized price', 'customized-task-offer'); ?></i>
                                <span>
                                    <?php 
                                    if(function_exists('wmc_revert_price')){
                                        workreap_price_format(wmc_revert_price($offer_price));
                                    } else {
                                        workreap_price_format($offer_price);
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php } ?>
                        <?php if( !empty($post->post_status) && $post->post_status!='rejected' ){?>
                            <ul class="wr-tabicon">
                                <li><a class="wr-invite-bidbtn" href="<?php the_permalink();?>"><?php esc_html_e('View offer','customized-task-offer')?></a></li>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
                <?php do_action('workreap_employer_offers_item_after', $post);?>
            </li>
            <?php
        endwhile;
        do_action('workreap_employer_offers_listing_after');
        ?>
    </ul>
    <?php
    workreap_paginate($workreap_query);
else: ?>
    <div class="wr-submitreview wr-submitreviewv3 no-new-offer">
        <figure>
            <img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'public/images/empty.png')?>" alt="<?php esc_attr_e('No offer received yet','customized-task-offer');?>">
        </figure>
        <h4><?php esc_html_e( 'Oopss..!! you have not any offer right now', 'customized-task-offer'); ?></h4>
     </div>
    <?php
endif;
wp_reset_postdata();
$script = "
jQuery(document).on('ready', function(){
    jQuery(document).on('change', '#wr_order_type', function (e) {
        let _this       = jQuery(this);
        let page_url = _this.find(':selected').data('url');
		window.location.replace(page_url);
    });
});
";
wp_add_inline_script( 'customized-task-offer', $script, 'after' );