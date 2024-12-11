<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package workreap
 */

if ( ! is_active_sidebar( 'workreap-sidebar' ) ) {
	return;
}
?>
<?php dynamic_sidebar( 'workreap-sidebar' ); ?>
