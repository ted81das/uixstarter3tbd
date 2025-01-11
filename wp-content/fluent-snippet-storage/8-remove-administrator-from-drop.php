<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: Access Restrictions
* @name: Remove Administrator from Drop down
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-12-07 22:27:19
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
function wdm_user_role_dropdown($all_roles) {
    // Check if user lacks admin capabilities
    if (!current_user_can('install_plugins') ) {
        // Remove administrator role from the list
        unset($all_roles['administrator']);
    }
    return $all_roles;
}

add_filter('editable_roles','wdm_user_role_dropdown');