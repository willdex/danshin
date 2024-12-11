<?php
/**
 *
 * The template part for displaying  Completed jobs
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$paged;
$user_identity 	 = $current_user->ID;
$post_id 		 = workreap_get_linked_profile_id($user_identity);

$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);

$meta_query_args = array();
$order 			= 'DESC';
$sorting 		= 'ID';
$search_keyword  = !empty($_GET['keyword']) ? $_GET['keyword'] : "";

$args 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'services-orders',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('hired'),
					'paged' 			=> $paged,
					'suppress_filters' 	=> false,
					's'                 => $search_keyword
				);

$meta_query_args[] = array(
						'key' 		=> '_service_author',
						'value' 	=> $current_user->ID,
						'compare' 	=> '='
					);
$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);
$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
	<div class="wt-dashboardbox wt-dashboardservcies">
		<div class="wt-dashboardboxtitle wt-titlewithsearch">
			<h2><?php esc_html_e('Ongoing services','workreap');?></h2>
			<?php do_action('workreap_dashboard_search_keyword','services','ongoing');?>
		</div>
		<div class="wt-dashboardboxcontent wt-categoriescontentholder">
			<?php if( $query->have_posts() ){ ?>
				<table class="wt-tablecategories wt-tableservice">
					<thead>
						<tr>
							<th><?php esc_html_e('Service name','workreap');?></th>
							<th><?php esc_html_e('Order by','workreap');?></th>
							<th><?php esc_html_e('Action','workreap');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$service_id			= get_post_meta($post->ID,'_service_id',true);
								$employer_id		= get_post_field ('post_author', $post->ID);
								$order_id			= get_post_meta($post->ID,'_order_id',true);
								
								?>
								<tr>
									<td><?php do_action('workreap_service_listing_basic', $service_id,'','',$order_id ); ?></td>
									<td><?php do_action('workreap_service_employer_html', $employer_id ); ?></td>
									<td>
										<div class="wt-actionbtn">
											<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity, '','history',$post->ID); ?>" class="wt-viewinfo wt-btnhistory">
												<?php esc_html_e('View History','workreap');?>
											</a>
										</div>
									</td>
								</tr>
						<?php
						endwhile;
						wp_reset_postdata();
						?>	
					</tbody>
				</table>
			<?php } else{ ?>
				<div class="wt-emptydata-holder">
					<?php do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No ongoing service yet.', 'workreap' )); ?>
				</div>
			<?php } ?>
			<?php
				if (!empty($count_post) && $count_post > $show_posts) {
					workreap_prepare_pagination($count_post, $show_posts);
				}
			?>

		</div>
	</div>
</div>