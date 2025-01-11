<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: cleanups
* @name: hide admin notices
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-12-07 21:47:34
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
add_action(
    'admin_notices',
    function() {
        if ( ! current_user_can( 'install_plugins' ) ) {
            remove_all_actions( 'admin_notices' );
        }
    },
    0
);