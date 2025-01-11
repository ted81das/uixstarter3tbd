<?php defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );
if ( ! isset( $view ) ) {
	return;
} ?>
<noscript>
    <style>#hmwp_wrap .tab-panel:not(.tab-panel-first) {
            display: block
        }</style>
</noscript>
<div id="hmwp_wrap" class="d-flex flex-row p-0 my-3">
	<?php echo $view->getAdminTabs( HMWP_Classes_Tools::getValue( 'page', 'hmwp_log' ) ); ?>
    <div class="hmwp_row d-flex flex-row p-0 m-0">
        <div class="hmwp_col flex-grow-1 p-0 pr-2 mr-2 mb-3">

            <div id="report" class="col-sm-12 p-0 m-0 tab-panel border-0 shadow-none tab-panel-first">
                <div class="card col-sm-12 p-0 m-0">
                    <h3 class="card-title hmwp_header p-2 m-0 mb-3"><?php echo esc_html__( 'Events Log Report', 'hide-my-wp' ); ?>
                        <a href="<?php echo esc_url( HMWP_Classes_Tools::getOption( 'hmwp_plugin_website' ) . '/kb/users-activity-log/#check_user_events' ) ?>" target="_blank" class="d-inline-block float-right mr-2" style="color: white"><i class="dashicons dashicons-editor-help"></i></a>
                    </h3>

                    <div class="card-body p-2 m-0">

                        <?php if ( HMWP_Classes_Tools::getOption( 'hmwp_activity_log' ) ) { ?>

                            <?php if ( apply_filters( 'hmwp_showlogs', true ) ) { ?>

                                <div class="card-body p-1 m-0">
                                    <?php $view->listTable->loadPageTable(); ?>
                                </div>

                                <?php if ( apply_filters( 'hmwp_showaccount', true ) ) { ?>
                                    <div class="mt-3 pt-2 border-top"></div>
                                    <div class="col-sm-12 text-center my-3">
                                        <a href="<?php echo esc_url(HMWP_Classes_Tools::getCloudUrl( 'events' )) ?>" class="btn rounded-0 btn-default btn-lg text-white px-4 securitycheck" target="_blank">
                                            <?php echo esc_html__( 'Go to Events Log Panel', 'hide-my-wp' ); ?>
                                        </a>
                                        <div class="text-black-50 small"><?php echo esc_html__( 'Search in user events log and manage the email alerts', 'hide-my-wp' ); ?></div>
                                    </div>
                                <?php } ?>

                            <?php } ?>

                        <?php } else { ?>
                            <div class="col-sm-12 p-1 text-center">
                                <div class="text-black-50 mb-2"><?php echo esc_html__( 'Activate the "Log Users Events" option to see the user activity log for this website', 'hide-my-wp' ); ?></div>
                                <a href="#log" class="btn btn-default hmwp_nav_item" data-tab="log"><?php echo esc_html__( 'Activate Log Users Events', 'hide-my-wp' ); ?></a>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>

            <form method="POST">
				<?php wp_nonce_field( 'hmwp_logsettings', 'hmwp_nonce' ) ?>
                <input type="hidden" name="action" value="hmwp_logsettings"/>

				<?php do_action( 'hmwp_event_log_form_beginning' ) ?>

                <div id="log" class="col-sm-12 p-0 m-0 tab-panel ">
                    <div class="card col-sm-12 p-0 m-0">
                        <h3 class="card-title hmwp_header p-2 m-0"><?php echo esc_html__( 'Events Log Settings', 'hide-my-wp' ); ?>
                        <a href="<?php echo esc_url( HMWP_Classes_Tools::getOption( 'hmwp_plugin_website' ) . '/kb/users-activity-log/' ) ?>" target="_blank" class="d-inline-block float-right mr-2" style="color: white"><i class="dashicons dashicons-editor-help"></i></a>
                    </h3>
                        <div class="card-body">
                            <div class="col-sm-12 row mb-1 ml-1 p-2">
                                <div class="checker col-sm-12 row my-2 py-1">
                                    <div class="col-sm-12 p-0 switch switch-sm">
                                        <input type="checkbox" id="hmwp_activity_log" name="hmwp_activity_log" class="switch" <?php echo( HMWP_Classes_Tools::getOption( 'hmwp_activity_log' ) ? 'checked="checked"' : '' ) ?> value="1"/>
                                        <label for="hmwp_activity_log"><?php echo esc_html__( 'Log Users Events', 'hide-my-wp' ); ?>
                                            <a href="<?php echo esc_url( HMWP_Classes_Tools::getOption( 'hmwp_plugin_website' ) . '/kb/users-activity-log/#activate_user_events_log' ) ?>" target="_blank" class="d-inline ml-1"><i class="dashicons dashicons-editor-help d-inline"></i></a>
                                        </label>
                                        <div class="text-black-50 ml-5"><?php echo esc_html__( 'Track and log events that happen on your WordPress site', 'hide-my-wp' ); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 row border-bottom border-light py-3 mx-0 my-3 hmwp_activity_log">
                                <div class="col-sm-4 p-1">
                                    <div class="font-weight-bold"><?php echo esc_html__( 'Log User Roles', 'hide-my-wp' ); ?>:</div>
                                    <div class="small text-black-50"><?php echo esc_html__( "Don't select any role if you want to log all user roles", 'hide-my-wp' ); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group">
                                    <select multiple name="hmwp_activity_log_roles[]" class="selectpicker form-control mb-1">
                                        <?php
                                        global $wp_roles;
                                        $roles = $wp_roles->get_names();
                                        foreach ( $roles as $key => $role ) {
                                            echo '<option value="' . esc_attr($key) . '" ' . ( in_array( $key, (array) HMWP_Classes_Tools::getOption( 'hmwp_activity_log_roles' ) ) ? 'selected="selected"' : '' ) . '>' . esc_html($role) . '</option>';
                                        } ?>
                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="col-sm-12 m-0 p-2 bg-light text-center" style="position: fixed; bottom: 0; right: 0; z-index: 100; box-shadow: 0 0 8px -3px #444;">
                            <button type="submit" class="btn rounded-0 btn-success px-5 mr-5 save"><?php echo esc_html__( 'Save', 'hide-my-wp' ); ?></button>
                        </div>
                    </div>
                </div>

				<?php do_action( 'hmwp_event_log_form_end' ) ?>

            </form>
        </div>

        <div class="hmwp_col hmwp_col_side p-0 pr-2 mr-2">
            <div class="card col-sm-12 m-0 p-0 rounded-0">
                <div class="card-body f-gray-dark text-left">
                    <h3 class="card-title"><?php echo esc_html__( 'Events Log', 'hide-my-wp' ); ?></h3>
                    <div class="text-info mb-3"><?php echo esc_html__( "Monitor everything that happens on your WordPress site!", 'hide-my-wp' ); ?></div>
                    <div class="text-info mb-3"><?php echo esc_html__( "All the logs are saved on Cloud for 30 days and the report is available if your website is attacked.", 'hide-my-wp' ); ?></div>
                    <div class="text-warning mb-3"><?php echo esc_html__( "Please be aware that if you do not consent to storing data on our Cloud, we kindly request that you refrain from activating this feature.", 'hide-my-wp' ); ?></div>
                </div>
            </div>
            <div class="card col-sm-12 p-0">
                <div class="card-body f-gray-dark text-left border-bottom">
                    <h3 class="card-title"><?php echo esc_html__( 'Features', 'hide-my-wp' ); ?></h3>
                    <ul class="text-info" style="margin-left: 16px; list-style: circle;">
                        <li class="mb-2"><?php echo esc_html__( "Monitor, track and log events on your website.", 'hide-my-wp' ); ?></li>
                        <li class="mb-2"><?php echo esc_html__( "Know what the other users are doing on your website.", 'hide-my-wp' ); ?></li>
                        <li class="mb-2"><?php echo esc_html__( "You can set to receive security alert emails and prevent data loss.", 'hide-my-wp' ); ?></li>
                        <li><?php echo esc_html__( "Compatible with all themes and plugins.", 'hide-my-wp' ); ?></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
