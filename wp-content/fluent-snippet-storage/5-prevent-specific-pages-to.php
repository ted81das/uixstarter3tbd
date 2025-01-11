<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: Access Restrictions
* @name: Prevent Specific Pages to be Access by non-admins
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-12-07 21:46:57
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
 * Restrict access to specific admin pages based on user roles
 */
function restrict_admin_page_access() {
    // Get current user
    $user = wp_get_current_user();
    
    // Restricted roles
    $restricted_roles = array(
        'director',
        'sc_cart_manager',
        'sc_cart_administrator',
        'manage_workflows'
    );
    
    // Restricted pages (using partial URL matching)
    $restricted_pages = array(
        'flowmattic-integrations',
        'flowmattic-settings',
        'flowmattic-chatbot'
    );
    
    // Check if user has any of the restricted roles
    $has_restricted_role = array_intersect($restricted_roles, (array) $user->roles);
    
    // If user has a restricted role
    if (!empty($has_restricted_role)) {
        // Get current admin page
        $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        
        // Check if current page is restricted
        if (in_array($current_page, $restricted_pages)) {
            // Redirect to dashboard with error message
            wp_safe_redirect(add_query_arg(
                array('access_denied' => '1'),
                admin_url('index.php')
            ));
            exit;
        }
    }
}
add_action('admin_init', 'restrict_admin_page_access');

/**
 * Display admin notice for restricted access
 */
function display_access_denied_notice() {
    if (isset($_GET['access_denied'])) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Access Denied: You do not have permission to access that page.', 'your-text-domain'); ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'display_access_denied_notice');