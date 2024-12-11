<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package workreap
 */

 /**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
if (!function_exists('workreap_excerpt_length')) {
	function workreap_excerpt_length( $length=22 ) {
		return 51;
	}
	add_filter( 'excerpt_length', 'workreap_excerpt_length', 999 );
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
if (!function_exists('workreap_excerpt_more')) {
	function workreap_excerpt_more( $more='' ) {
		return '';
	}
	add_filter( 'excerpt_more', 'workreap_excerpt_more' );
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
if (!function_exists('workreap_pingback_header')) {
	function workreap_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
	add_action( 'wp_head', 'workreap_pingback_header' );
}

/**
 * Pangination
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_pagination')) {
  function workreap_pagination($workreap_query= '', $class= ''){
	global $wp_query;
	$workreap_total = $wp_query->max_num_pages;
	  
    if ($workreap_total > 1) {
      if( !empty($class)){ ?>
        <div class="<?php echo esc_attr($class);?>">
      <?php } ?>
      <div class="wr-pagination">
        <?php
          echo paginate_links(array(
            'base'         => str_replace(999999999, '%#%', esc_url_raw(get_pagenum_link(999999999))),
            'total'        => $workreap_total,
            'current'      => max(1, get_query_var('paged')),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'list',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf('<i class="wr-icon-chevron-left"></i>'),
            'next_text'    => sprintf('<i class="wr-icon-chevron-right"></i>'),
            'add_args'     => false,
            'add_fragment' => '',
          ));
        ?>
      </div>
 	 <?php
      if( !empty($class)){ ?>
        </div>
      <?php
      }
    }
  }
}

/**
 * comments listings
 * @return slug
 */
if (!function_exists('workreap_comments')) {

    function workreap_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$args['reply_text'] = esc_html__('Reply','workreap');
    $comment_author		= !empty($comment->user_id) ? $comment->user_id : get_the_author_meta('ID');
	$url    			= get_author_posts_url($comment_author);
	$empty_avatar = '';

	if(empty(get_avatar($comment, 100))){
		$empty_avatar = 'wr-empty-avatar';
	}

	$user_name		= '';
	$empty_avatar	= '';
	if(empty(get_avatar($comment->comment_ID, 100))){
		$empty_avatar = 'tu-empty-avatar';
	}
	
	$user_profile_id = apply_filters('workreap_get_linked_profile_id', $comment_author);
	
	if(!empty($user_profile_id) && function_exists('workreap_get_user_avatar')){
		$avatar_url         = apply_filters(
			'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $user_profile_id), array('width' => 100, 'height' => 100)
		);
		
		if(function_exists('workreap_get_username')){
			$user_name  = workreap_get_username($user_profile_id);
		}

		$avatar = '<img src="'.esc_url($avatar_url).'" alt="'.esc_attr($user_name).'">';
	} else {
		$avatar_url = get_avatar_url($comment_author, 100);
		
		$author_obj = get_user_by('id', $comment_author);

		if(!empty($author_obj->nickname)){
			$user_name  = $author_obj->nickname;
		}
		$avatar = '<img src="'.esc_url($avatar_url).'" alt="'.esc_attr($user_name).'">';
	}
	?>
	<li <?php comment_class('comment-entry clearfix '.$empty_avatar); ?>>
		<div class="wr-addcomment" id="comment-<?php comment_ID(); ?>">
            <div class="wr-blogimg">
				<?php if(!empty($avatar)){?><figure><?php echo do_shortcode($avatar); ?> </figure><?php }?>
    			<div class="wr-blogcmntinfo">
					<div class="wr-blogcmntinfonames">
						<div class="wr-icondetails"><span><?php echo sprintf( _x( '%s ago', '%s = human-readable time difference', 'workreap' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span></div>
						<div class="wr-comentinfodetail">
                            <h4><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html($user_name); ?></a></h4>
						</div>
					</div>
   					<div class="workreap-reply">
						<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
					</div>
    			</div>
            </div>
            <div class="wr-main-description">
				<?php if ($comment->comment_approved == '0') : ?>
					<p class="workreap-comment-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'workreap'); ?></p>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
		</div>
		<?php
	}
}

/**
 * comments wrap start
 * @return slug
 */
if (!function_exists('workreap_comment_form_top')) {
	add_action('comment_form_top', 'workreap_comment_form_top');

	function workreap_comment_form_top() {
		// Adjust this to your needs:
		$output = '';
		$output .='<div class="wr-themeform__wrap"><fieldset>';

		echo do_shortcode( $output);
	}

}

/**
 * @count items in array
 * @return {}
 */
if (!function_exists('workreap_count_items')) {
    function workreap_count_items($items) {
        if( is_array($items) ){
			return count($items);
		} else{
			return 0;
		}
    }
}

/**
 * comments wrap start
 * @return slug
 */
if (!function_exists('workreap_comment_form')) {
	add_action('comment_form', 'workreap_comment_form');

	function workreap_comment_form() {
		$output = '';
		$output .= '</fieldset></div>';

		echo do_shortcode( $output );
	}

}

/**
 * @Change Reply link Class
 * @return sizes
 */
if (!function_exists('workreap_replace_reply_link_class')) {
    add_filter('comment_reply_link', 'workreap_replace_reply_link_class');

    function workreap_replace_reply_link_class($class) {
        $class = str_replace("class='comment-reply-link'", 'class="comment-reply-link wr-theme-btn"', $class);
        return $class;
    }
}

/**
 * @Enqueue admin scripts and styles.
 * @return{}
 */
if (!function_exists('workreap_admin_enqueue')) {

    function workreap_admin_enqueue($hook) {
        global $post;
        $protolcol = is_ssl() ? "https" : "http";
        $theme_version = wp_get_theme('workreap');

	    wp_enqueue_style( 'workreap-admin-style', get_template_directory_uri() . '/admin/css/workreap-admin-style.css', array(), $theme_version->get('Version'));

        wp_localize_script('workreap-admin-functions', 'scripts_vars', array(
			'ajax_nonce' 		=> wp_create_nonce('ajax_nonce'),
        ));
    }

    add_action('admin_enqueue_scripts', 'workreap_admin_enqueue', 10, 1);
}

/**
 * @Theme Editor/guttenberg Style
 * 
 */
if (!function_exists('workreap_add_editor_styles')) {

    function workreap_add_editor_styles() {
		global $theme_settings;
		$protocol = is_ssl() ? 'https' : 'http';
        $theme_version = wp_get_theme('workreap');
		$editor_css  = '';
		
		if (function_exists('fw_get_db_settings_option')) {
            $color_base = fw_get_db_settings_option('color_settings');
        }
		
		$site_colors 	= '#ff5851';
		
		if (!empty($site_colors)) {
			$editor_css  .= 'body.block-editor-page .editor-styles-wrapper a,
			body.block-editor-page .editor-styles-wrapper p a,
			body.block-editor-page .editor-styles-wrapper p a:hover,
			body.block-editor-page .editor-styles-wrapper a:hover,
			body.block-editor-page .editor-styles-wrapper a:focus,
			body.block-editor-page .editor-styles-wrapper a:active{color: #1DA1F2;}';
			
			$editor_css  .= 'body.block-editor-page .editor-styles-wrapper blockquote:not(.blockquote-link),
							 body.block-editor-page .editor-styles-wrapper .wp-block-quote.is-style-large,
							 body.block-editor-page .editor-styles-wrapper .wp-block-quote:not(.is-large):not(.is-style-large),
							 body.block-editor-page .editor-styles-wrapper .wp-block-quote.is-style-large,
							 body.block-editor-page .editor-styles-wrapper .wp-block-pullquote, 
							 body.block-editor-page .editor-styles-wrapper .wp-block-quote, 
							 body.block-editor-page .editor-styles-wrapper .wp-block-quote:not(.is-large):not(.is-style-large),
							 body.block-editor-page .wp-block-pullquote, 
							 body.block-editor-page .wp-block-quote, 
							 body.block-editor-page .wp-block-verse, 
							 body.block-editor-page .wp-block-quote:not(.is-large):not(.is-style-large){border-color:'.$site_colors.';}';
		}
		
		$font_families	= array();
		$font_families[] = 'Inter:400,600,700,900';
		
		 $query_args = array (
			 'family' => implode('%7C' , $font_families) ,
			 'subset' => 'latin,latin-ext' ,
        );

        $theme_fonts = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');
		add_editor_style(esc_url_raw($theme_fonts));
		
		wp_enqueue_style('workreap-fonts-enqueue' , esc_url_raw($theme_fonts), array () , null);
		
		$editor_css .= "
		body.block-editor-page editor-post-title__input,
		body.block-editor-page .editor-post-title__block .editor-post-title__input
		{font: 700 1.75rem/1.3571428571em 'Inter', sans-serif;}";
		
		$editor_css .= "body.block-editor-page .editor-styles-wrapper{font: 400 1rem/1.625em 'Inter', sans-serif}";
		
		$editor_css .= "body.block-editor-page .editor-styles-wrapper{color: #0A0F26;}";
		$editor_css .= "body.block-editor-page editor-post-title__input,
		body.block-editor-page .editor-post-title__block .editor-post-title__input,
		body.block-editor-page .editor-styles-wrapper h1, 
				body.block-editor-page .editor-styles-wrapper h2, 
				body.block-editor-page .editor-styles-wrapper h3, 
				body.block-editor-page .editor-styles-wrapper h4, 
				body.block-editor-page .editor-styles-wrapper h5, 
				body.block-editor-page .editor-styles-wrapper h6 {font-family: 'Inter', sans-serif}";
							   
		wp_enqueue_style( 'workreap-editor-style', get_template_directory_uri() . '/admin/css/workreap-editor-style.css', array(), $theme_version->get('Version'));
		wp_add_inline_style( 'workreap-editor-style', $editor_css );
		
    }

    add_action('enqueue_block_editor_assets', 'workreap_add_editor_styles');
} 

/**
 * search type
 * @return slug
 */
if (!function_exists('workreap_custom_body_class')) {
	add_filter( 'body_class', 'workreap_custom_body_class' );
	function workreap_custom_body_class( $classes ) {
		global $workreap_settings;
		$page_id 	= get_the_ID();

		$pages		= !empty($workreap_settings['wr_dark_header']) ? $workreap_settings['wr_dark_header'] : array();

		if ( is_page() && !empty($pages) && in_array($page_id,$pages) ) {
			$classes[] = 'wr-mainbodydark';
		}

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'workreap-sidebar' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}
}
/**
 * @Comment field order
 * 
 */
if (!function_exists('workreap_comment_fields_custom_order')) {
	add_filter('comment_form_fields', 'workreap_comment_fields_custom_order');
	function workreap_comment_fields_custom_order($fields)
	{
		$comment_field			= '';
		$author_field			= '';
		$email_field			= '';
		$url_field				= '';
		$cookies_field			= '';

		if (isset($fields['comment'])) {
			$comment_field		= $fields['comment'];
		}

		if (isset($fields['author'])) {
			$author_field		= $fields['author'];
		}

		if (isset($fields['email'])) {
			$email_field		= $fields['email'];
		}

		if (isset($fields['cookies'])) {
			$cookies_field		= $fields['cookies'];
		}
		
		$comment_field	= $fields['comment'];
		$author_field	= $fields['author'];
		$email_field	= $fields['email'];

		if (isset($fields['url'])) {
			$url_field		= $fields['url'];
		}

		$cookies_field	= $fields['cookies'];

		unset($fields['comment']);
		unset($fields['author']);
		unset($fields['email']);
		unset($fields['url']);
		unset($fields['cookies']);

		// the order of fields is the order below, change it as needed:
		$fields['author']	= $author_field;
		$fields['email']	= $email_field;
		$fields['url']		= $url_field;
		$fields['comment']	= $comment_field;
		$fields['cookies']	= $cookies_field;
		// done ordering, now return the fields:
		return $fields;
	}
}

//Elementor Theme Builder
if (class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
	add_action( 'elementor/theme/register_locations', 'workreap_register_elementor_locations' );
	function workreap_register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location( 'header' );
		$elementor_theme_manager->register_location( 'footer' );
	}
}

if(!function_exists('workreap_set_canvas_to_elementor_template_library')){
	add_filter( 'single_template', 'workreap_set_canvas_to_elementor_template_library' );
	function workreap_set_canvas_to_elementor_template_library( $single_template ) {
		global $post;
		if ( defined( 'ELEMENTOR_PATH' ) && $post->post_type == 'elementor_library' ) {
			$template_path = ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
			if ( file_exists( $template_path ) ) {
				$single_template = $template_path;
			}
		}
		return $single_template;
	}
}