<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: cleanups
* @name: Disable Welcome Message in the Gutenberg Editor
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-12-07 22:02:25
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
/**
 * Disable the "Disable Welcome Messages" in the Gutenberg Editor.
 *
 */
function disable_editor_welcome_message() {
	?>
	<script>
		window.onload = (event) => {
			wp.data && wp.data.select( 'core/edit-post' ).isFeatureActive( 'welcomeGuide' ) && wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'welcomeGuide' );
		};
	</script>
	<?php
}
add_action( 'admin_head-post.php', 'disable_editor_welcome_message' );
add_action( 'admin_head-post-new.php',  'disable_editor_welcome_message' );
add_action( 'admin_head-edit.php', 'disable_editor_welcome_message' );