<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: cleanups
* @name: Default light Color Scheme
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-12-07 21:37:20
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
// add custom user meta data
add_action('personal_options_update', 'save_custom_admin_color_optios');
function save_custom_admin_color_optios( $user_id ) {
    update_user_meta($user_id, 'custom_admin_color_scheme', true);
}

// change default color scheme if not customized
$customized_color_scheme = get_user_option( 'custom_admin_color_scheme', get_current_user_id() );
if ( empty($customized_color_scheme) ) {
    update_user_meta(get_current_user_id(), 'admin_color', 'light');
}