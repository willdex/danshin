<?php
/**
 *
 * Comments Page
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
if (post_password_required()) {
    return;
}

$theme_check = workreap_new_theme_active();

if(!$theme_check){

	if (have_comments()) { ?>
        <div id="wt-comments" class="wt-comments">
            <h2><?php comments_number(esc_html__('0 Comment' , 'workreap') , esc_html__('1 Comment' , 'workreap') , esc_html__('% Comments' , 'workreap')); ?></h2>
            <ul><?php wp_list_comments(array ('callback' => 'workreap_comments' ));?></ul>
			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <div class="wt-haslayout wt-comments-paginate">
					<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                        <span class="wt-comment-prev"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'workreap')); ?></span>
                        <span class="wt-comment-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'workreap')); ?></span>
					<?php endif; ?>
                </div>
			<?php endif;?>
        </div>
	<?php } ?>

	<?php
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		Workreap_Prepare_Notification::workreap_info('', esc_html__('Comments are closed.', 'workreap'));
	}
	?>

	<?php if ( comments_open() ) { ?>
        <div class="wt-replaybox">
			<?php
			if(function_exists('workreap_modify_comment_form_fields')){
				workreap_modify_comment_form_fields();
			}
			$comments_args = array(
				'must_log_in'			=> '<div class="form-group"><p class="must-log-in">' .  sprintf( __( "You must be %slogged in%s to post a comment.", "workreap" ), '<a href="'.esc_url(wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) )).'">', '</a>' ) . '</p></div>',

				'logged_in_as'			=> '<div class="form-group"><p class="logged-in-as">' . esc_html__( "Logged in as","workreap" ).' <a href="' .esc_url( admin_url( "profile.php" ) ).'">'.$user_identity.'</a>. <a href="' .esc_url(wp_logout_url(get_permalink())).'" title="' . esc_attr__("Log out of this account", "workreap").'">'. esc_html__("Log out &raquo;", "workreap").'</a></p></div>',

				'comment_field'			=> '<div class="form-group"><textarea name="comment" id="comment" cols="39" rows="5" tabindex="4" class="form-control" placeholder="'. esc_attr__("Type your comment", "workreap").'"></textarea></div>',

				'notes'                => '' ,
				'comment_notes_before' => '' ,
				'comment_notes_after'  => '' ,
				'id_form'              => 'wt-formtheme_2' ,
				'id_submit'            => 'wt-formtheme' ,
				'class_form'           => 'wt-formtheme wt-formleavecomment' ,
				'class_submit'         => 'wt-btn' ,
				'name_submit'          => 'submit' ,
				'title_reply'          => esc_html__('Leave Your Comment' , 'workreap') ,
				'title_reply_to'       => esc_html__('Leave a reply to %s' , 'workreap') ,
				'title_reply_before'   => '<div class="wt-boxtitle"><h2>' ,
				'title_reply_after'    => '</h2></div>' ,
				'cancel_reply_before'  => '' ,
				'cancel_reply_after'   => '' ,
				'cancel_reply_link'    => esc_html__('Cancel reply' , 'workreap') ,
				'label_submit'         => esc_html__('Post Comment' , 'workreap') ,
				'submit_button'        => '<div class="form-group"><button name="%1$s" type="submit" id="%2$s" class="wt-btn" value="%4$s"> '.esc_html__( 'Send', 'workreap' ).'</button></div>' ,
				'submit_field'         => ' %1$s %2$s ' ,
				'format'               => 'xhtml' ,
			);
			comment_form($comments_args);
			?>
        </div>
	<?php }

}else{
	if (have_comments()) { ?>
        <div id="workreap-comments" class="workreap-comments wr-theme-box">
            <div class="wr-maintitle"><h3><?php comments_number(esc_html__('0 Comment' , 'workreap') , esc_html__('1 Comment' , 'workreap') , esc_html__('% Comments' , 'workreap')); ?></h3></div>
            <ul><?php wp_list_comments(array ('callback' => 'workreap_comments' ));?></ul>
			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <div class="workreap-haslayout workreap-comments-paginate">
					<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                        <span class="workreap-comment-prev"><?php previous_comments_link(esc_html__('&larr; Older comments', 'workreap')); ?></span>
                        <span class="workreap-comment-next"><?php next_comments_link(esc_html__('Newer comments &rarr;', 'workreap')); ?></span>
					<?php endif; ?>
                </div>
			<?php endif;?>
        </div>
	<?php } ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {?>
        <div class="workreap-comments-closed"><?php esc_html_e('Comments are closed.', 'workreap');?></div>
	<?php }?>

	<?php if ( comments_open() ) { ?>
		<?php
		$comments_args = array(
			'must_log_in'			=> '<div class="form-group"><p class="workreap-must-log-in">' .  sprintf( __( "You must be %slogged in%s to post a comment.", "workreap" ), '<a href="'.esc_url(wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) )).'">', '</a>' ) . '</p></div>',

			'logged_in_as'			=> '<div class="form-group"><p class="workreap-logged-in-as">' . esc_html__( "Logged in as","workreap" ).' <a href="' .esc_url( admin_url( "profile.php" ) ).'">'.$user_identity.'</a>. <a href="' .esc_url(wp_logout_url(get_permalink())).'" title="' . esc_attr__("Log out of this account", "workreap").'">'. esc_html__("Log out &raquo;", "workreap").'</a></p></div>',

			'fields' => apply_filters( 'workreap_default_comment_fields', array(
				'author'	=> '<div class="form-group  form-group-half"><label class="wr-label">'.esc_html__('Full Name','workreap').'</label><div class="wr-placeholderholder"><input type="text" name="author" id="author" value="'. esc_attr( $commenter['comment_author'] ) .'" placeholder="'. esc_attr__("Your name (required)", "workreap").'" size="22" tabindex="1" aria-required="true" class="form-control" /></div></div>',

				'email'	=> '<div class="form-group  form-group-half"><label class="wr-label">'.esc_html__('Email address','workreap').'</label><div class="wr-placeholderholder"><input type="text" name="email" id="email" value="'. esc_attr( $commenter['comment_author_email'] ) .'" placeholder="'. esc_attr__("Your email (required)", "workreap").'" size="22" tabindex="2" aria-required="true" class="form-control"  /></div></div>',

				'url'	=> '<div class="form-group"><label class="wr-label">'.esc_html__('Website','workreap').'</label><div class="wr-placeholderholder"><input type="text" name="url" id="url" value="'. esc_attr( $commenter['comment_author_url'] ) .'" placeholder="'. esc_attr__("Website", "workreap").'" size="22" tabindex="3" class="form-control" /></div></div>'
			)),
			'comment_field'			=> '<div class="form-group"><label class="wr-label">'.esc_html__('Description','workreap').'</label><div class="wr-placeholderholder"><textarea name="comment" id="comment" cols="39" rows="5" tabindex="4" class="form-control" placeholder="'. esc_attr__("Type your comment", "workreap").'"></textarea></div></div>',
			'notes'                => '' ,
			'comment_notes_before' => '' ,
			'comment_notes_after'  => '' ,
			'id_form'              => 'wr-themeform' ,
			'id_submit'            => 'workreap-formtheme' ,
			'class_form'           => 'wr-themeform' ,
			'class_submit'         => 'wr-theme-btn',
			'class_container'      => 'comment-respond wr-theme-box',
			'name_submit'          => 'submit' ,
			'title_reply'          => esc_html__('Leave your comment' , 'workreap') ,
			'title_reply_to'       => esc_html__('Leave a reply to %s' , 'workreap') ,
			'title_reply_before'   => '<div class="wr-maintitle"><h3>' ,
			'title_reply_after'    => '</h3></div>' ,
			'cancel_reply_before'  => '' ,
			'cancel_reply_after'   => '' ,
			'cancel_reply_link'    => esc_html__('Cancel reply' , 'workreap') ,
			'label_submit'         => esc_html__('Post comment' , 'workreap') ,
			'submit_button'        => '<div class="form-group"><button name="%1$s" type="submit" id="%2$s" class="wr-theme-btn" value="%4$s"> '.esc_html__( 'Post comment', 'workreap' ).'</button></div>' ,
			'submit_field'         => ' %1$s %2$s ' ,
			'format'               => 'xhtml' ,
		);
		comment_form($comments_args);
		?>

	<?php }
}