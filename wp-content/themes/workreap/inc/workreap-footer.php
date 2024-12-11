<?php
/**
 * Workreap footer template
 *
 * @link https://themeforest.net/user/amentotech/portfolio
 *
 * @package workreap
 */
global $workreap_settings;
$footer_copyright 	= !empty($workreap_settings['copyright']) ? $workreap_settings['copyright'] : esc_html__('Copyright &copy;', 'workreap') . date('Y') . '&nbsp;' . get_bloginfo();
$dashboard_type		= !empty($workreap_settings['dashboard_header_type']) ? $workreap_settings['dashboard_header_type'] : '';
$header_type		= !empty($workreap_settings['header_type_after_login']) ? $workreap_settings['header_type_after_login'] : '';
$workreap_social_icons		= !empty($workreap_settings['workreap_footer_social_icons']) ? $workreap_settings['workreap_footer_social_icons'] : false;
$user_id	  	    = is_user_logged_in() ? get_current_user_id() : 0 ;
$user_type		    = !empty($user_id) && function_exists('workreap_get_user_type') ? workreap_get_user_type($user_id) : '';

?>
<?php if( ( ($user_type ==='freelancers' || $user_type === 'employers') && $header_type === 'dashboard-header' && $dashboard_type === 'workreap-sidebar' ) || ( ($user_type ==='freelancers' || $user_type === 'employers') && $header_type !== 'dashboard-header' && $dashboard_type === 'workreap-sidebar' && is_workreap_template() ) ){
    return;
}

if (class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
	$did_header_location = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->do_location( 'footer' );
	if($did_header_location){
		return;
	}
}

?>

<footer class="wr-footer-two">
	<?php if ( defined('WORKREAP_DIRECTORY') && (is_active_sidebar('workreap-sidebar-f1') || is_active_sidebar('workreap-sidebar-f2') || is_active_sidebar('workreap-sidebar-f3')) ) {?>
        <div class=" wr-footer-two_head">
			<div class="container">
				<div class="row">
					<?php if (is_active_sidebar('workreap-sidebar-f1')) : ?>
						<div class="col-12 col-xl-4">
							<?php dynamic_sidebar('workreap-sidebar-f1'); ?>
						</div>
					<?php endif; ?>
					<?php if (is_active_sidebar('workreap-sidebar-f2')) : ?>
						<div class="col-12 col-lg-6 col-xl-4">
							<?php dynamic_sidebar('workreap-sidebar-f2'); ?>
						</div>
					<?php endif; ?>
					<?php if (is_active_sidebar('workreap-sidebar-f3')) : ?>
						<div class="col-12 col-lg-6 col-xl-4">
							<?php dynamic_sidebar('workreap-sidebar-f3'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="wr-footer-two_copyright">
		<div class="container">
			<div class="wr-footer-two_content<?php echo esc_attr($workreap_social_icons ? ' wr-footer-social-active' : ''); ?>">
				<div class="wr-fcopyright">
					<?php if ( has_nav_menu( 'footer-menu' ) ) {?>
						<div class="wr-fcopyright_list">
							<?php
							wp_nav_menu(
								array(
									'theme_location' 	=> 'footer-menu',
									'menu_class'		=> 'wr-footetbtmlinks',
									'menu_id'        	=> 'footer-menu',
								)
							);
							?>
						</div>
					<?php }?>
					<?php if(!empty($footer_copyright)){?>
						<span class="wr-fcopyright_info"><?php echo esc_html($footer_copyright);?></span>
					<?php }?>
				</div>
				<?php if($workreap_social_icons){  ?>
					<div class="wr-fsocials">
						<ul class="wr-fsocials-list">
							<?php if (isset($workreap_settings['workreap_footer_facebook_link']) && !empty($workreap_settings['workreap_footer_facebook_link'])){ ?>
								<li><a href="<?php echo esc_url($workreap_settings['workreap_footer_facebook_link']); ?>" target="_blank" class="wr-fsocials-list-item-link"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
							<?php } ?>
							<?php if (isset($workreap_settings['workreap_footer_linkedin_link']) && !empty($workreap_settings['workreap_footer_linkedin_link'])){ ?>
								<li><a href="<?php echo esc_url($workreap_settings['workreap_footer_linkedin_link']); ?>" target="_blank" class="wr-fsocials-list-item-link"><i class="fab fab fa-linkedin-in" aria-hidden="true"></i></a></li>
							<?php } ?>
							<?php if (isset($workreap_settings['workreap_footer_youtube_link']) && !empty($workreap_settings['workreap_footer_youtube_link'])){ ?>
								<li><a href="<?php echo esc_url($workreap_settings['workreap_footer_youtube_link']); ?>" target="_blank" class="wr-fsocials-list-item-link"><i class="fab fab fa-youtube" aria-hidden="true"></i></a></li>
							<?php } ?>
							<?php if (isset($workreap_settings['workreap_footer_twitter_link']) && !empty($workreap_settings['workreap_footer_twitter_link'])){ ?>
								<li><a href="<?php echo esc_url($workreap_settings['workreap_footer_twitter_link']); ?>" target="_blank" class="wr-fsocials-list-item-link"><i class="fab fab fa-twitter" aria-hidden="true"></i></a></li>
							<?php } ?>
							<?php if (isset($workreap_settings['workreap_footer_dribbble_link']) && !empty($workreap_settings['workreap_footer_dribbble_link'])){ ?>
								<li><a href="<?php echo esc_url($workreap_settings['workreap_footer_dribbble_link']); ?>" target="_blank" class="wr-fsocials-list-item-link"><i class="fab fab fa-dribbble" aria-hidden="true"></i></a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</footer>