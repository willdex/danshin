<?php
/**
 *
 * The template part for displaying saved freelancers
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

if (!empty($_GET['identity'])) {
    $url_identity = $_GET['identity'];
}

$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);

$order 			= 'DESC';
$sorting 		= 'ID';
$save_freelancers_ids	= get_post_meta( $post_id, '_saved_freelancers', true);
$post_array_ids		= !empty($save_freelancers_ids) ? $save_freelancers_ids : array(0);
$args = array(
	'posts_per_page' 	=> $show_posts,
    'post_type' 		=> 'freelancers',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'paged' 			=> $paged,
	'post__in' 			=> $post_array_ids,
    'suppress_filters' 	=> false
);
$query = new WP_Query($args);
$count_post = $query->found_posts;
if( $query->have_posts() ){
	$emptyClass = '';
} else{
	$emptyClass = 'wt-emptydata-holder';
}
?>
<div class="wt-addprojectsholder wt-likefreelan">
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Liked freelancers listing','workreap');?></h2>
		<?php if( $query->have_posts() ) { ?>
			<a href="#" onclick="event_preventDefault(event);" data-post-id="<?php echo intval($post_id);?>" data-itme-type="_saved_freelancers" class="wt-clicksave wt-clickremoveall">
				<i class="lnr lnr-cross"></i>
				<?php esc_html_e('Remove all liked freelancers','workreap');?>
			</a>
		<?php } ?>
	</div>
	<div class="wt-likedfreelancers wt-haslayout <?php echo esc_attr( $emptyClass );?>">
	<?php
		if( $query->have_posts() ){
			while ($query->have_posts()) : $query->the_post();
				global $post;
				$author_id 			= get_the_author_meta( 'ID' );
				$freelancer_title 	= esc_html( get_the_title( $post->ID ));
				$user_tagline 		= get_post_meta($post->ID, '_tag_line', true);
				$freelancer_avatar 	= apply_filters(
					'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $post->ID ), array( 'width' => 225, 'height' => 225 )
				);
			?>
			<div class="wt-userlistinghold wt-featured">
				<?php do_action('workreap_featured_freelancer_tag', $author_id); ?>
				<figure class="wt-userlistingimg">
					<img src="<?php echo esc_url( $freelancer_avatar );?>" alt="<?php echo esc_attr($freelancer_title);?>">
				</figure>
				<div class="wt-userlistingcontent">
					<div class="wt-contenthead">
						<div class="wt-title">
							<?php do_action( 'workreap_get_verification_check', $post->ID, $freelancer_title ); ?>
							<h2><?php echo esc_html($user_tagline); ?></h2>
						</div>
						<ul class="wt-userlisting-breadcrumb">
							<?php do_action('workreap_freelancer_per_hour_rate', $post->ID); ?>
							<?php do_action('workreap_print_location', $post->ID); ?>
							<?php do_action('workreap_trash_icon_project_html' , $post_id , $post->ID ,'_saved_freelancers'); ?>
						</ul>
					</div>
					<?php do_action( 'workreap_freelancer_get_reviews', $post->ID , 'v2' ); ?>
				</div>	
			</div>
			<?php
			endwhile;
			wp_reset_postdata();
			
		} else{
			do_action('workreap_empty_records_html','wt-empty-saved',esc_html__( 'You have not any freelancers in your favorite list.', 'workreap' ));
		}
	?>
	</div>
	<?php
		if (!empty($count_post) && $count_post > $show_posts) {
			workreap_prepare_pagination($count_post, $show_posts);
		}
	?>
</div>