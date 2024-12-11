<?php
/**
 *
 * The template used for displaying cart detail
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/templates/
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

global $current_user,$workreap_settings;
$custom_field_option    =  !empty($workreap_settings['custom_field_option']) ? $workreap_settings['custom_field_option'] : false;
$product_id     = !empty($_GET['id']) ? intval($_GET['id']) : 0;
$offer_id       = !empty($_GET['offers_id']) ? intval($_GET['offers_id']) : 0;
$post_author    = get_post_field( 'post_author', $product_id );
$post_author    = !empty($post_author) ? intval($post_author) : 0;

if( !empty($current_user->ID) && intval($post_author) == $current_user->ID ){
	do_action( 'workreap_notification', esc_html__('Restricted access','customized-task-offer'), esc_html__('Oops! you are not allowed to perfom this action','customized-task-offer') );
} else {

    $key              = !empty($_GET['key']) ? $_GET['key'] : '';
    $user_balance     = !empty($current_user->ID) ? get_user_meta($current_user->ID, '_employer_balance', true) : '';
    $user_balance     = !empty($user_balance) ? $user_balance : 0;

    $wallet_checked   = !empty($user_balance) ? 'checked' : '';
    $product          = wc_get_product($product_id);
    $image            = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), array(100, 100));
    $product_name     = !empty($product) ? $product->get_name() : '';
    $list_html = '';
    /* product related categories */
    $prod_cate_html     = '';
    $product_cate_ids   = wc_get_product_term_ids($product_id, 'product_cat');

    foreach ($product_cate_ids as $cat_id) {
        $term           = get_term_by('id', $cat_id, 'product_cat');
        $prod_cate_html .= '<a href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html($term->name) . '</a>, ';
    }
    /* getting features */
    $product_cat  = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
    $plan_array   = array(
        'product_tabs'            => array('plan'),
        'product_plans_category'  => $product_cat
    );

    /* getting acf fields */
    $acf_fields             = workreap_acf_groups($plan_array);
    /* workreap plan Values */
    $workreap_plans_values   = get_post_meta($offer_id, 'workreap_product_plans', TRUE);
    $workreap_plans_values   = !empty($workreap_plans_values) ? $workreap_plans_values : array();

    /* getting subtasks */
    $workreap_subtask        = get_post_meta($product_id, 'workreap_product_subtasks', TRUE);
    $workreap_subtask        = !empty($workreap_subtask) ? $workreap_subtask : array();

    $wr_custom_fields       = get_post_meta( $offer_id, 'wr_custom_fields',true );
    $wr_custom_fields       = !empty($wr_custom_fields) ? $wr_custom_fields : array();

    $price_plan             = get_post_meta($offer_id, 'offer_price', true);
    $delivery_time          = get_post_meta($offer_id, 'delivery_time', true);
    $package_title          = esc_html__('Custom Offer', 'customized-task-offer');
    ?>
    <div class="wr-main-section wr-offer-cart">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <div class="wr-servicedetail">
                        <div class="wr-checkoutinfo">
                            <?php if (!empty($image[0])) { ?>
                                <figure>
                                    <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>">
                                </figure>
                            <?php } ?>
                            <div class="wr-checkoutdetail">
                                <h6><?php echo do_shortcode($prod_cate_html); ?></h6>
                                <h5><?php echo esc_html($product_name); ?></h5>
                            </div>
                        </div>
                        <div class="wr-box">
                            <h4><?php esc_html_e('Features included', 'customized-task-offer'); ?>:</h4>
                            <?php
                            $counter_checked      = 0;
                            $price_package        = 0;
                            $package_key          = '';
                            $pkg_image            = '';
                            foreach ($workreap_plans_values as $plan_key => $plan_val) {
                                $counter_checked++;
                                
                                if (!empty($key) && $key == $plan_key) {
                                    $pkg_image	      = !empty($workreap_settings['task_plan_icon_'.$plan_key]['url']) ? $workreap_settings['task_plan_icon_'.$plan_key]['url'] : '';
                                    $package_price    = $price_plan;
                                    $package_key      = $plan_key;
                                }
                                
                                $feature_class = 'd-none';
                                if ($key === $plan_key) {
                                    $feature_class = '';
                                }
                                ?>
                                <ul class="wr-mainlist wr-mainlistvtwo wr-pkg-<?php echo esc_attr($plan_key) . ' ' . esc_attr($feature_class); ?>"
                                    id="wr-pkg-<?php echo esc_attr($plan_key); ?>">
                                    <?php
                                    foreach ($acf_fields as $acf_key => $acf_field) {
                                        $plan_value = !empty($acf_field['key']) && !empty($plan_val[$acf_field['key']]) ? $plan_val[$acf_field['key']] : '';
                                        
										if (!empty($acf_field['label'])) {
                                            if (!empty($acf_field['type']) && in_array($acf_field['type'], array('text', 'textarea', 'number'))) {
                                                echo do_shortcode('<li><span>' . esc_html($acf_field['label']) . '</span><em> (' . esc_html($plan_value) . ')</em></li>');
                                            } else if (!empty($acf_field['type']) && $acf_field['type'] === 'url' && !empty($plan_value)) {
                                                echo do_shortcode('<li><span>' . esc_html($acf_field['label']) . '</span><em><a href="' . esc_url($plan_value) . '" target="_blank"> (' . esc_html($plan_value) . ')</a></em></li>');
                                            } else if (!empty($acf_field['type']) && $acf_field['type'] === 'email' && !empty($plan_value)) {
                                                echo do_shortcode('<li><span>' . esc_html($acf_field['label']) . '</span><em><a href="mailto:' . esc_attr($plan_value) . '" target="_blank"> (' . esc_html($plan_value) . ')</a></em></li>');
                                            } else if (!empty($acf_field['type']) && in_array($acf_field['type'], array('checkbox'))) {
                                                $class = !empty($plan_value) && $plan_value === 'yes' ? 'wr-available' : 'wr-unavailable';
                                                echo do_shortcode('<li class="' . esc_attr($class) . '"><span>' . esc_html($acf_field['label']) . '</span></li>');
                                            }
                                        }
                                    }
                                    ?>
                                    <?php 
                                        if( !empty($wr_custom_fields) && !empty($custom_field_option) ){
                                            foreach($wr_custom_fields as $field_value){
                                                if( !empty($field_value['title']) ){?>
                                                <li>
                                                    <span><?php echo esc_html($field_value['title']); ?></span>
                                                    <em> (<?php echo esc_html($field_value[$plan_key]); ?>)</em>
                                                </li>
                                    <?php   }
                                        }
                                    } 
                                    if(function_exists('workreap_offer_delivery_time') ){
                                        $delivery_time_html	= workreap_offer_delivery_time($offer_id,'v3');
                                        echo do_shortcode( $delivery_time_html );
                                    }
                                    ?>
                                </ul>
                            <?php } ?>
                        </div>
                        
                    </div>
                </div>
                <div class="col-xl-4 wr-offer-cart-order">
                    <aside>
                        <form id="wr_cart_form" class="wr-orderdetailswrap">
                            <div class="wr-asideholder">
                                <div class='wr-asideboxsm'>
                                    <h5><?php esc_html_e('Order details', 'customized-task-offer'); ?></h5>
                                </div>
                                <div class="wr-pakagedetail wr-custom-offers-cart">
                                    <?php if( !empty($pkg_image) ){?>
                                        <figure>
                                        <img id="wr_pkg_image" src="<?php echo esc_url($pkg_image);?>" alt="<?php echo esc_attr($package_title); ?>">
                                        </figure>
                                    <?php } ?>
                                    <div class='wr-pakageinfo'>
                                        <h6><?php echo esc_html($package_title); ?></h6>
                                        <?php if( !empty($package_price) ) {?>
                                            <h4 id="wr_package_price"><?php workreap_price_format($package_price); ?></h4>
                                        <?php } ?>
                                        <input type="hidden" id="wr_task_cart" data-task_id="<?php echo intval($product_id); ?>">
                                        <input type="hidden" name="product_task" id="wr_project_task_key" value="<?php echo do_shortcode($package_key); ?>" data-task_key="<?php echo do_shortcode($package_key); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="wr-asideholder mt-0 border-top-0">
                               
                                <ul class="wr-featuredlisted">
                                    <li><?php esc_html_e('Subtotal','customized-task-offer');?> <span id="wr_task_total"><?php workreap_price_format($package_price); ?></span></li>
                                </ul>
                                <div class="wr-walletsystem">
                                    <div class="wr-form-checkbox">
                                        <input class="form-check-input wr-form-check-input-sm" type="checkbox"
                                            id="wr_wallet_option" name="wallet" <?php echo esc_attr($wallet_checked);?>>
                                        <label class="wr-additionolinfo" for="wr_wallet_option">
                                            <span><?php esc_html_e('Use my wallet credit', 'customized-task-offer'); ?></span>
                                            <em><?php esc_html_e('Wallet credit will be used during the checkout process', 'customized-task-offer'); ?></em>
                                        </label>
                                        <span class="wr-walletamount">
                                            <span>( <?php workreap_price_format($user_balance); ?>)</span>
                                        </span>
                                    </div>
                                    <div class="wr-btnwalletfund">
                                        <a href="javascript:void(0);" class="wr-btn-solid-lg wr-btnsfund" data-bs-toggle="modal" data-bs-target="#tbcreditwallet">
                                            <?php esc_html_e('Add funds to wallet', 'customized-task-offer'); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="wr-btnwallet">
                                    <a href="javascript:void(0);" class="wr-btn-solid-lg-lefticon" data-id="<?php echo intval($product_id); ?>" data-offers_id="<?php echo intval($offer_id); ?>" id="wr_offers_cart_btn">
                                        <i class="wr-icon-lock"></i>
                                        <?php esc_html_e('Proceed to secure checkout', 'customized-task-offer'); ?>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </aside>
                </div>
            </div>
        </div>
    </div>
<?php }
