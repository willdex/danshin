<?php
/**
 * Template part for displaying related articles on single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Workreap
 */
$workreap_args = array(
    'post_type'     => 'post',
    'posts_per_page' => 3,
    'post__not_in'  => array(get_the_ID()),
    'category__in' => wp_get_post_categories($post->ID), 
);  
$workreap_related_posts    = new \WP_Query(apply_filters('workreap_related_post_args', $workreap_args));
$total_posts  = $workreap_related_posts->found_posts;
if($workreap_related_posts->have_posts()){?>
    <div class="wr-relatedatricles">
        <div class="wr-blogtitle">
            <h3><?php esc_html_e('Explore related articles','workreap')?> </h3>
        </div>
        <div class="row gy-4">
            <?php  while ($workreap_related_posts->have_posts()) {
                $workreap_related_posts->the_post();
                $image_id           = get_post_thumbnail_id($post->ID);
                $post_url           = !empty($post->ID) ? get_the_permalink($post->ID) : '';
                $author_id          = get_post_field ('post_author', $post->ID);
                $author_name        = get_the_author_meta( 'nickname' , $author_id ); 
                $term_list          = wp_get_post_terms($post->ID, 'category', array("fields" => "all"));
                $post_date          =  date_i18n(get_option('date_format'), strtotime(get_the_date())) ;
                ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class('col-md-6 col-lg-4 col-xl-6 col-xxl-4'); ?>>
                    <div class="wr-articleitem">
                        <?php if (has_post_thumbnail( $post->ID ) ): ?>
                            <figure>
                                <img src="<?php echo get_the_post_thumbnail_url($post->ID, 'workreap_blog_medium'); ?>" alt="<?php echo esc_attr(get_the_title())?>">
                            </figure>
                        <?php endif;?>                        
                        <div class="wr-articleinfo">
                            <?php echo get_the_term_list( $post->ID, 'category', '<ul class="wr-taglinks wr-taglinksm"><li>', '</li><li>', '</li></ul>'); ?>
                            <div class="wr-arrticltitle">
                                <h5><a href="<?php echo esc_url( get_the_permalink());?>"><?php echo esc_html(get_the_title());?></a></h5>
                            </div>
                            <ul class="wr-articleauth">
                                <li class="wr-articleauthor">
                                    <i class="wr-icon-message-square"></i>
				                    <span><?php comments_number(esc_html__('0 Comments' , 'workreap') , esc_html__('1 Comment' , 'workreap') , esc_html__('% Comments' , 'workreap')); ?></span>
                                </li>
                                <li>
                                    <i class="wr-icon-calendar"></i>
                                    <span><?php echo esc_html($post_date); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
    <?php
}
wp_reset_postdata();
