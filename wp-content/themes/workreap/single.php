<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package workreap
 */

get_header();
$theme_check = workreap_new_theme_active();

if(!$theme_check){
	global $post;
	$sidebar_type = 'full';
	$sd_sidebar	   	  = '';
	$section_width    = 'col-12 col-sm-12 col-md-12 col-lg-12 float-left';
	do_action('workreap_post_views', get_the_ID(),'article_views');
	if (function_exists('workreap_sidebars_get_current_position')) {
		$current_position = workreap_sidebars_get_current_position($post->ID);
		if ( !empty($current_position['sd_layout']) && $current_position['sd_layout'] !== true && $current_position['sd_layout'] !== 'default' ) {
			$sidebar_type  		= !empty($current_position['sd_layout']) ? $current_position['sd_layout'] : 'full';
			$sd_sidebar	   		= !empty($current_position['sd_sidebar']) ? $current_position['sd_sidebar'] : '';
			if(!empty($sd_sidebar)){
				$section_width      = 'col-xs-12 col-sm-8 col-md-8 col-lg-8';
			}
		}else{
			if (function_exists('fw_get_db_settings_option')) {
				$sd_layout_posts    = fw_get_db_settings_option('sd_layout_posts');
				$sd_sidebar_posts   = fw_get_db_settings_option('sd_sidebar_posts');
				$sidebar_type  		= !empty($sd_layout_posts) ? $sd_layout_posts : 'full';
				$sd_sidebar	   		= !empty($sd_sidebar_posts) ? $sd_sidebar_posts : '';

				if(!empty($sd_sidebar)){
					$section_width 		= 'col-xs-12 col-sm-8 col-md-8 col-lg-8';
				}
			}
		}
	}

	if (!empty($sidebar_type) && $sidebar_type === 'right') {
		$aside_class   = 'pull-right';
		$content_class = 'pull-left';
	} else {
		$aside_class   = 'pull-left';
		$content_class = 'pull-right';
	}
	?>
    <div class="wt-haslayout single-main-section">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="wt-articlesingle-holder wt-bgwhite">
                    <div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>">
						<?php
						while (have_posts()) : the_post();
							global $post, $thumbnail, $post_video, $blog_post_gallery;
							$height    = intval(400);
							$width     = intval(1140);
							$user_ID   = get_the_author_meta('ID');
							$user_url  = get_author_posts_url($user_ID);
							$thumbnail = workreap_prepare_thumbnail($post->ID, $width, $height);

							$udata      = get_userdata($user_ID);
							$registered = $udata->user_registered;

							$enable_author     = '';
							$enable_comments   = 'enable';
							$enable_categories = 'enable';
							$post_settings     = '';

							$title_show	= 'true';

							if(function_exists('fw_get_db_settings_option')){
								$titlebar_type = fw_get_db_post_option($post->ID, 'titlebar_type', true);
								if(  isset( $titlebar_type['gadget'] )
								     && $titlebar_type['gadget'] === 'default'
								){
									$title_show	= 'false';
								} else if(  isset( $titlebar_type['gadget'] )
								            && $titlebar_type['gadget'] === 'none'
								){
									$title_show	= 'true';
								} else if(  isset( $titlebar_type['gadget'] )
								            && $titlebar_type['gadget'] === 'custom'
								){
									$title_show	= 'true';
								} else{
									$title_show	= 'false';
								}

							} else{
								$title_show	= 'false';
							}

							if (function_exists('fw_get_db_post_option')) {

								$enable_author      = fw_get_db_post_option($post->ID, 'enable_author', true);
								$enable_comments    = fw_get_db_post_option($post->ID, 'enable_comments', true);
								$enable_categories  = fw_get_db_post_option($post->ID, 'enable_categories', true);
								$enable_sharing     = fw_get_db_post_option($post->ID, 'enable_sharing', true);

								$post_settings      = fw_get_db_post_option($post->ID, 'post_settings', true);
								$enable_comments    = $enable_comments == 1 ? 'enable' : $enable_comments;
							}

							$blog_post_gallery = array();
							$post_video        = '';

							if (!empty($post_settings['gallery']['blog_post_gallery'])) {
								$blog_post_gallery = $post_settings['gallery']['blog_post_gallery'];
							}

							if (!empty($post_settings['video']['blog_video_link'])) {
								$post_video = $post_settings['video']['blog_video_link'];
							}
							?>
                            <div class="wt-articlesingle-content">
								<?php
								if (!empty($post_settings['gadget']) && $post_settings['gadget'] === 'image' && !empty($thumbnail)
								) {
									get_template_part('/template-parts/single-templates/image-single');
								} elseif (!empty($post_settings['gadget']) && $post_settings['gadget'] === 'gallery' && !empty($blog_post_gallery)
								) {
									get_template_part('/template-parts/single-templates/gallery-single');
								} elseif (!empty($post_settings['gadget']) && $post_settings['gadget'] === 'video' && !empty($post_video)
								) {
									get_template_part('/template-parts/single-templates/video-single');
								} else if (!empty($thumbnail)) {
									get_template_part('/template-parts/single-templates/image-single');
								}
								?>
								<?php if( $title_show === 'true' ){?>
                                    <div class="wt-title">
                                        <h2><?php workreap_get_post_title($post->ID); ?></h2>
                                    </div>
								<?php }?>
                                <ul class="wt-postarticlemeta">
                                    <li><?php workreap_get_post_date($post->ID); ?></li>
									<?php if (!empty($enable_author) && $enable_author === 'enable') { ?>
                                        <li><?php workreap_get_post_author( $user_ID , 'linked', $post->ID ); ?></li>
									<?php } ?>
									<?php if (!empty($enable_categories) && $enable_categories === 'enable') { 
										if(function_exists('workreap_get_post_categories')){?>
                                        <li><?php workreap_get_post_categories($post->ID, '', 'category', ''); ?></li>
									<?php } } ?>
                                </ul>
                                <div class="wt-description">
									<?php
									the_content();
									wp_link_pages( array(
										'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
										'after'       => '</ul></nav></div>',
									) );
									?>
                                </div>
								<?php if (( has_tag() ) || ( !empty($enable_sharing['gadget']) && $enable_sharing['gadget'] === 'enable' )) {?>
                                    <div class="wt-tagsshare aaaaaaaaaa">
										<?php
										if(has_tag()) {
											if(function_exists('workreap_get_post_tags')){
												workreap_get_post_tags($post->ID, 'tag', 'yes');
											}
										}
										?>
										<?php
										if (!empty($enable_sharing['gadget'])
										    && $enable_sharing['gadget'] === 'enable'
										    && function_exists('workreap_prepare_social_sharing')
										) {
											workreap_prepare_social_sharing(false, $enable_sharing['enable']['share_title'], 'true', '', $thumbnail);
										}
										?>
                                    </div>
								<?php } ?>
								<?php if (!empty($enable_author) && $enable_author === 'enable') {
									$post_author_id	= get_the_author_meta('ID');
									$user_type 		= function_exists('workreap_get_user_type') ? workreap_get_user_type($post_author_id) : '';
									if( !empty($user_type) && ($user_type == 'freelancer' || $user_type == 'employer')){
										$profile_id = function_exists('workreap_get_linked_profile_id') ? workreap_get_linked_profile_id($post_author_id):0;
										$url        = get_permalink($profile_id);
									} else {
										$url    = get_author_posts_url($post_author_id);
									}
									?>
                                    <div class="wt-author">
                                        <div class="wt-authordetails">
                                            <figure><a href="<?php echo esc_url($url); ?>">  <?php echo get_avatar($user_ID, 80); ?></a></figure>
                                            <div class="wt-authorcontent">
                                                <div class="wt-authorhead">
                                                    <div class="wt-boxleft">
                                                        <h3><a href="<?php echo esc_url($url); ?>"><?php echo get_the_author(); ?></a></h3>
                                                        <span><?php esc_html_e('Author Since', 'workreap'); ?>:&nbsp;<?php echo date_i18n(get_option('date_format'), strtotime($registered)); ?></span>
                                                    </div>
													<?php
													$facebook  = get_the_author_meta('facebook', $user_ID);
													$twitter   = get_the_author_meta('twitter', $user_ID);
													$pinterest = get_the_author_meta('pinterest', $user_ID);
													$linkedin  = get_the_author_meta('linkedin', $user_ID);
													$tumblr    = get_the_author_meta('tumblr', $user_ID);
													$google    = get_the_author_meta('google', $user_ID);
													$instagram = get_the_author_meta('instagram', $user_ID);
													$skype     = get_the_author_meta('skype', $user_ID);

													if (!empty($facebook) ||
													    !empty($twitter) ||
													    !empty($pinterest) ||
													    !empty($linkedin) ||
													    !empty($tumblr) ||
													    !empty($google) ||
													    !empty($instagram)
													    || !empty($skype) ) {
														?>
                                                        <div class="wt-boxright">
                                                            <ul class="wt-socialiconssimple">
																<?php if (!empty($facebook)) { ?>
                                                                    <li class="wt-facebook">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('facebook', $user_ID)); ?>">
                                                                            <i class="fa fa-facebook-f"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
																<?php if (!empty($twitter)) { ?>
                                                                    <li class="wt-twitter">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('twitter', $user_ID)); ?>">
                                                                            <i class="fa fa-twitter"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
																<?php if (!empty($pinterest)) { ?>
                                                                    <li class="wt-dribbble">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('pinterest', $user_ID)); ?>">
                                                                            <i class="fa fa-pinterest-p"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
																<?php if (!empty($linkedin)) { ?>
                                                                    <li class="wt-linkedin">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('linkedin', $user_ID)); ?>">
                                                                            <i class="fa fa-linkedin"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
																<?php if (!empty($tumblr)) { ?>
                                                                    <li class="wt-tumblr">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('tumblr', $user_ID)); ?>">
                                                                            <i class="fa fa-tumblr"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
																<?php if (!empty($google)) { ?>
                                                                    <li class="wt-googleplus">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('google', $user_ID)); ?>">
                                                                            <i class="fa fa-google"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
																<?php if (!empty($instagram)) { ?>
                                                                    <li class="wt-dribbble">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('instagram', $user_ID)); ?>">
                                                                            <i class="fa fa-instagram"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
																<?php if (!empty($skype)) { ?>
                                                                    <li  class="wt-skype">
                                                                        <a href="<?php echo esc_url(get_the_author_meta('skype', $user_ID)); ?>">
                                                                            <i class="fa fa-skype"></i>
                                                                        </a>
                                                                    </li>
																<?php } ?>
                                                            </ul>
                                                        </div>
													<?php } ?>
                                                </div>
                                                <div class="wt-description">
                                                    <p><?php echo nl2br(get_the_author_meta('description', $user_ID)); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
								<?php
								if (!empty($enable_comments) && $enable_comments === 'enable') {
									if (comments_open() || get_comments_number()) :
										comments_template();
									endif;
								}
								?>
                            </div>
						<?php endwhile; ?>
                    </div>
					<?php
					if (function_exists('workreap_sidebars_get_current_position')) {
						if (isset($sidebar_type) && $sidebar_type != 'full' && !empty($sd_sidebar)) {?>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 <?php echo sanitize_html_class($aside_class); ?>">
                                <aside id="wt-sidebar" class="wt-sidebar">
									<?php dynamic_sidebar( $sd_sidebar );?>
                                </aside>
                            </div>
							<?php
						}
					}
					?>
                </div>
            </div>
        </div>
    </div>
	<?php
}else{
	global $post, $workreap_settings;
	$author_details		= !empty($workreap_settings['author_details']) ? $workreap_settings['author_details'] : '';
	$related_article	= !empty($workreap_settings['related_article']) ? $workreap_settings['related_article'] : '';
	$side_layout		= get_post_meta($post->ID, 'workreap_postlayout_setting', true);
	$social_list  		= apply_filters('workreap_user_social_fields_listing',array());
	$side_layout		= !empty($side_layout) ? $side_layout : 'right';
	$page_sidebar		= get_post_meta($post->ID, 'workreap_postlayout_sidebar', true);
	$sidebar_selected   = !empty($page_sidebar) ? $page_sidebar : 'single-post-sidebar';
	do_action('workreap_post_views', $post->ID,'workreap_post_views');
	$section_col = 'col-xl-12';

	if(!empty($side_layout) && ($side_layout == 'left' || $side_layout == 'right') && is_active_sidebar( $sidebar_selected )){
		$section_col = 'col-xl-8 col-xxl-9';
	}
	?>
    <div class="wr-main-section">
        <div class="container">
			<?php while ( have_posts() ) :
				global $post;
				the_post();
				$author_id 			= get_the_author_meta( 'ID' );?>
                <div class="row">

					<?php if(!empty($side_layout) && $side_layout == 'left' && is_active_sidebar( $sidebar_selected ) ){?>
                        <div class="col-xl-4 col-xxl-3">
							<?php if ( is_active_sidebar( $sidebar_selected ) ) {?>
                                <div class="wr-asidewrapper">
                                    <a href="javascript:void(0)" class="wr-dbmenu"><i class="wr-icon-layout"></i></a>
                                    <div class="wr-aside-menu">
										<?php
										// Author details
										if(!empty($author_details) && !empty(get_the_author_meta( 'description',$author_id ))){
											$object_id 			= get_the_author_meta( 'ID' );
											$avatar_url 		= get_avatar_url($author_id, 100);
											$avatar 			= '<img src="'.esc_url($avatar_url).'" alt="'.esc_attr(get_the_author()).'">';
											?>
                                            <div class="wr-blogprofileuser wr-single-author-box">
                                                <div class="wr-authorhead">
													<?php echo do_shortcode($avatar); ?>
                                                    <div class="wr-profilrtitle">
                                                        <h6><?php esc_html_e('Blog author','workreap');?></h6>
                                                        <h5><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author_meta('nickname'); ?></a></h5>
                                                    </div>
                                                </div>
												<?php if(!empty($social_list)){?>
                                                    <div class="wr-boxtopsocial">
                                                        <ul class="wr-socialicons">
															<?php
															foreach($social_list as $key => $item ){
																$url  = get_the_author_meta($key, $author_id);
																if (!empty($url)){?>
                                                                    <li class="wr-<?php echo esc_attr($key); ?>">
                                                                        <a href="<?php echo esc_url($url); ?>">
                                                                            <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                                                                        </a>
                                                                    </li>
																<?php }} ?>
                                                        </ul>
                                                    </div>
												<?php } ?>
												<?php if ( get_the_author_meta( 'description',$author_id ) ) : ?>
                                                    <div class="wr-blogprofileuser__description"><p><?php the_author_meta( 'description',$author_id ); ?></p></div>
												<?php endif; ?>
                                            </div>
										<?php }?>
										<?php dynamic_sidebar( $sidebar_selected ); ?>
                                    </div>
                                </div>
							<?php }?>
                        </div>
					<?php }?>
                    <div class="<?php echo esc_attr($section_col);?>">
                        <div class="wr-blogwrapper">
                            <div class="wr-blogdescription">
                                <div class="wr-bloginfo">
									<?php workreap_entry_footer(); ?>
                                </div>
                                <div class="wr-blogtitle">
                                    <h3><?php the_title()?></h3>
                                </div>
                                <ul class="wr-blogiteminfo">
                                    <li><?php workreap_posted_on();?></li>
                                    <li>
                                        <i class="wr-icon-message-square">
                                        </i>
                                        <span><?php comments_number(esc_html__('0 Comments' , 'workreap') , esc_html__('1 Comment' , 'workreap') , esc_html__('% Comments' , 'workreap')); ?></span>
                                    </li>
									<?php do_action('workreap_post_views',$post->ID);?>
                                </ul>
								<?php workreap_post_thumbnail(); ?>
                                <div class="wr-description wr-blogs-bottom">
									<?php	get_template_part( 'template-parts/content', get_post_type() );?>
                                </div>
                            </div>

							<?php
							// Blog post tags
							workreap_post_tags();
							// Related posts
							if(!empty($related_article)){
								get_template_part( 'template-parts/content','related-articles' );
							}

							// If comments are open or we have at least one comment, load up the comment template.
							if ( ( comments_open() || get_comments_number() ) && get_post_type() === 'post' ) :
								comments_template();
							endif;
							?>
                        </div>
                    </div>
					<?php if(!empty($side_layout) && $side_layout == 'right' && is_active_sidebar( $sidebar_selected ) ){?>
                        <div class="col-xl-4 col-xxl-3">

							<?php if ( is_active_sidebar( $sidebar_selected ) ) {?>
                                <div class="wr-asidewrapper">
                                    <a href="javascript:void(0)" class="wr-dbmenu"><i class="wr-icon-layout"></i></a>
                                    <div class="wr-aside-menu">
										<?php
										// Author details
										if(!empty($author_details) && !empty(get_the_author_meta( 'description',$author_id ))){
											$object_id 			= get_the_author_meta( 'ID' );
											$avatar_url 		= get_avatar_url($author_id, 100);
											$avatar 			= '<img src="'.esc_url($avatar_url).'" alt="'.esc_attr(get_the_author()).'">';
											?>
                                            <div class="wr-blogprofileuser wr-single-author-box">
                                                <div class="wr-authorhead">
													<?php echo do_shortcode($avatar); ?>
                                                    <div class="wr-profilrtitle">
                                                        <h6><?php esc_html_e('Blog author','workreap');?></h6>
                                                        <h5><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author_meta('nickname'); ?></a></h5>
                                                    </div>
                                                </div>
												<?php if(!empty($social_list)){?>
                                                    <div class="wr-boxtopsocial">
                                                        <ul class="wr-socialicons">
															<?php
															foreach($social_list as $key => $item ){
																$url  = get_the_author_meta($key, $author_id);
																if (!empty($url)){?>
                                                                    <li class="wr-<?php echo esc_attr($key); ?>">
                                                                        <a href="<?php echo esc_url($url); ?>">
                                                                            <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                                                                        </a>
                                                                    </li>
																<?php }} ?>
                                                        </ul>
                                                    </div>
												<?php } ?>
												<?php if ( get_the_author_meta( 'description',$author_id ) ) : ?>
                                                    <div class="wr-blogprofileuser__description"><p><?php the_author_meta( 'description',$author_id ); ?></p></div>
												<?php endif; ?>
                                            </div>
										<?php }?>
										<?php dynamic_sidebar( $sidebar_selected ); ?>
                                    </div>
                                </div>
							<?php }?>
                        </div>
					<?php }?>
                </div>
			<?php endwhile; // End of the loop.?>
        </div>
    </div>
	<?php
}
get_footer();
