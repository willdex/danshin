<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package workreap
 */

$theme_check = workreap_new_theme_active();

if(!$theme_check){
	do_action('workreap_do_process_footers');
}else{
?>
</main>
<?php get_template_part('inc/workreap','footer');?>
<?php }
wp_footer(); ?>
</body>
</html>
