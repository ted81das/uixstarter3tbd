<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: Asta
* @name: show one page starter templates in block editor
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-12-07 21:39:03
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
add_filter( 'ast_block_templates_white_label', '__return_false', 999 );

add_filter( 'ast_block_template_capability_additional_roles', function( $roles ) { 
 $roles[] = 'director'; // Add the 'miniadmin' role 
     $roles[] = 'siteadmin';
 return $roles; });
