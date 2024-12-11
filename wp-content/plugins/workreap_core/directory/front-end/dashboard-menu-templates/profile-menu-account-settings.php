<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;
?>
<li class="toolip-wrapo <?php echo esc_attr( $reference  === 'account-settings' ? 'wt-active' : ''); ?>">
	<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'manage'); ?>">
		<i class="ti-anchor"></i>
		<span><?php esc_html_e('Account settings','workreap');?></span>
		<?php do_action('workreap_get_tooltip','element','account-settings');?>
	</a>
</li>
