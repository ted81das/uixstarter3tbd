<?php
// <Internal Doc Start>
/*
*
* @description: typical cleanups
* @tags: 
* @group: cleanups
* @name: cleanups
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-12-07 21:19:53
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: all
* @load_as_file: 
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
<?php
add_action('admin_enqueue_scripts', 'ds_admin_theme_style');
add_action('login_enqueue_scripts', 'ds_admin_theme_style');
function ds_admin_theme_style() {
	if (!current_user_can( 'manage_options' )) {
		echo '<style>.update-nag, .updated, .error, .is-dismissible { display: none; }</style>';
	}
}


//set minimum comment length
add_filter( 'preprocess_comment', 'minimal_comment_length' );
function minimal_comment_length( $commentdata ) {
    $minimalCommentLength = 20;
    if ( strlen( trim( $commentdata['comment_content'] ) ) < $minimalCommentLength ){
    wp_die( 'All comments must be at least ' . $minimalCommentLength . ' characters long.' );
    }
    return $commentdata;
}


//unset url field from comment
function remove_comment_fields($fields) {
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','remove_comment_fields');

//use nicename instead of user slug

add_action( 'user_profile_update_errors', 'set_user_nicename_to_nickname', 10, 3 );
function set_user_nicename_to_nickname( &$errors, $update, &$user ) {
 if ( ! empty( $user->nickname ) ) {
  $user->user_nicename = sanitize_title( $user->nickname, $user->display_name );
 }
}

