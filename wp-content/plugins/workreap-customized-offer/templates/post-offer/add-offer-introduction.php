<?php
/**
 *  Offer introduction
 *
 * @package     Workreap_Customized_Task_Offers_Addon
 * @subpackage  Workreap_Customized_Task_Offers_Addon/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $workreap_settings, $current_user;
if ( !class_exists('WooCommerce') ) {
	return;
}
$hide_product_cat   = !empty($workreap_settings['hide_product_cat']) ? $workreap_settings['hide_product_cat'] : array();

$countries  = array();
if (class_exists('WooCommerce')) {
    $countries_obj   = new WC_Countries();
    $countries   = $countries_obj->__get('countries');
} 

$current_page_url   = get_the_permalink();
if(isset($_GET['post']) && !empty($_GET['post'])){
    $post_id = intval($_GET['post']);
    $current_page_url   = add_query_arg('post', $post_id, $current_page_url);
}

if(isset($_GET['step']) && !empty($_GET['step'])){
    $step = intval($_GET['step']);
    $current_page_url   = add_query_arg('step', $step, $current_page_url);
}

$country_class = "form-group";
if(!empty($workreap_settings['enable_zipcode']) ){
	$country_class = "form-group-half";
}
$user_id = $current_user->ID;
$args = array(
    'limit'     => -1, // All products
    'status'    => 'publish',
    'type'      => 'tasks',
    'orderby'   => 'date',
    'order'     => 'DESC',
    'author'    => $user_id
);
$workreap_tasks = wc_get_products( $args );
$args = array(
    'posts_per_page'    => -1, // All employers
    'status'            => 'publish',
    'post_type'         => 'employers',
    'orderby'           => 'date',
    'order'             => 'DESC',
);
$workreap_employers = get_posts( $args );
$employer_id       = !empty($post_id) ? get_post_meta( $post_id, 'employer_id', true ) : 0;
$employer_id       = !empty($employer_id) ? intval($employer_id) : 0;
$defult_name    = !empty($employer_id) ? workreap_get_username($employer_id) : esc_html__('Select employer','customized-task-offer');

?>
<div id="service-introduction-wrapper">
    <form id="offer-introduction-form" class="wr-themeform" action="<?php echo esc_url($current_page_url);?>" method="post" novalidate enctype="multipart/form-data">
        <fieldset>
            <?php do_action('workreap_offer_before_title', $args); ?>
            <div class="form-group form-group-half form-group_vertical" id="wr-select-task">
                <label class="form-group-title"><?php esc_html_e('Select task:', 'customized-task-offer'); ?></label>
                <span class="wr-select">
                    <select name="workreap_offer[task_id]">
                        <option value="" selected hidden disabled><?php esc_html_e('Select task', 'customized-task-offer'); ?></option>
                        <?php
                        if(!empty($workreap_tasks) && is_array($workreap_tasks) && count($workreap_tasks)>0){
                            foreach($workreap_tasks as $task){
                                $selected    = '';
                                
                                if(!empty($task_id)  && $task->get_id() == $task_id){
                                    $selected    = ' selected="selected"';
                                }?>
                                <option value="<?php echo (int)$task->get_id()?>" <?php echo do_shortcode($selected);?>><?php echo esc_html($task->get_name());?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </span>
            </div>

            <div class="form-group form-group-half form-group_vertical" id="wr-select-employer">
                <label class="form-group-title"><?php esc_html_e('Select employer:', 'customized-task-offer'); ?></label>
                <div class="wr-employer-select">
                    <?php if( !empty($defult_name) ){?>
                        <span><?php echo esc_html($defult_name)?></span>
                    <?php } ?>
                    <div class="wr-employer-select-wrapper">
                        <span class="wr-employer-search">
                            <input class="wr-employer-search__field" type="search">
                        </span>
                        <ul class="wr-employer-slect-list">
                            <?php
                            if(!empty($workreap_employers) && is_array($workreap_employers) && count($workreap_employers)>0){
                                foreach($workreap_employers as $employer){
                                    $selected           = '';
                                    $class_select_employer = '';
                                    $employer_name         = '';
                                    if( function_exists('workreap_get_username') ){
                                        $employer_name = workreap_get_username($employer->ID);
                                    }
                                    $wr_post_meta   = get_post_meta($employer->ID, 'wr_post_meta', true);
                                    $employer_tagline  = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
    
                                    $avatar = apply_filters(
                                        'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $employer->ID), array('width' => 50, 'height' => 50)
                                    );
                                    if(!empty($employer_id) && $employer->ID == $employer_id){
                                        $selected            = 'checked="checked"';
                                        $class_select_employer  = ' class="check_employer"';
                                    }?>
                                    <li <?php echo do_shortcode($class_select_employer)?>>
                                        <label for="employer_value<?php echo intval($employer->ID)?>">
                                            <div class="wr-employer-holder">
                                                <img src="<?php echo esc_url( $avatar );?>" alt="<?php echo esc_attr($employer_name); ?>">
                                                <div class ="wr-employer-content">
                                                    <h6 class="wr-selected-value"><?php echo esc_html($employer_name);?></h6>
                                                    <span><?php echo esc_html($employer_tagline);?></span>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="radio" name="workreap_offer[employer_id]" id="employer_value<?php echo intval($employer->ID)?>" value="<?php echo intval($employer->ID)?>" <?php echo do_shortcode($selected)?>>
                                    </li>     
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php do_action('workreap_offer_step_1_fields', $args); ?>
            <div class="form-group wr-postservicebtn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save & Continue” to add latest changes made by you', 'customized-task-offer'); ?></span>
                    <button type="submit" class="wr-btn"><?php esc_html_e('Save & Continue', 'customized-task-offer'); ?></button>
                    <input type="hidden" id="service_id" name="post_id" value="<?php echo intval($post_id); ?>">
                </div>
            </div>
        </fieldset>
    </form>
</div>
