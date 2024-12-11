<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package workreap
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
    </head>
<body <?php body_class(); ?>>
<?php
$theme_check = workreap_new_theme_active();
if ( ! $theme_check ) {
    if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    } else {
        do_action( 'wp_body_open' );
    }
?>
<?php do_action('workreap_systemloader'); ?>
<?php do_action('workreap_app_available'); ?>
<?php do_action('workreap_demo_preview'); ?>
    <div id="wt-wrapper" class="wt-wrapper wt-haslayout">
		<?php do_action('workreap_do_process_headers'); ?>
		<?php do_action('workreap_prepare_titlebars'); ?>
        <main id="wt-main" class="wt-main wt-haslayout">
<?php } else {
	wp_body_open();
	get_template_part( 'inc/workreap', 'header' );
	?>
    <main class="workreap-main">
<?php }