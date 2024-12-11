<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */

$theme_check = workreap_new_theme_active();

if(!$theme_check){ ?>
    <section class="no-results not-found">
        <header class="page-header"><h1 class="page-title"><?php esc_html_e('Nothing Found' , 'workreap'); ?></h1></header>
        <div class="page-content">
			<?php if (is_home() && current_user_can('publish_posts')) : ?>
                <p>
					<?php
					printf(wp_kses(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.' , 'workreap') , array (
						'a' => array (
							'href' => array () ) )) , esc_url(admin_url('post-new.php')));
					?>
                </p>
			<?php elseif (is_search()) : ?>
                <p><?php Workreap_Prepare_Notification::workreap_info( esc_html__('Sorry, but nothing matched your search terms. Please try again with some different keywords.' , 'workreap') );?></p>
				<?php get_search_form(); ?>
			<?php else : ?>
                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.' , 'workreap'); ?></p>
				<?php get_search_form(); ?>
			<?php endif; ?>
        </div>
    </section>
<?php }else{ ?>
    <section class="no-results not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'workreap' ); ?></h1>
        </header><!-- .page-header -->
        <div class="page-content">
			<?php
			if ( is_home() && current_user_can( 'publish_posts' ) ) :

				printf(
					'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'workreap' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					) . '</p>',
					esc_url( admin_url( 'post-new.php' ) )
				);

            elseif ( is_search() ) :?>
                <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'workreap' ); ?></p>
				<?php get_search_form(); ?>
                <div class="tu-description">
                    <p><?php echo sprintf( __( "You can also start from scratch. Go to %sHomepage%s instead", 'workreap' ), '<a href="'.esc_url(home_url('/')).'">', '</a>' );?></p>
                </div>
			<?php else :?>
                <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'workreap' ); ?></p>
				<?php
				get_search_form();
			endif;
			?>
        </div><!-- .page-content -->
    </section>
<?php }
