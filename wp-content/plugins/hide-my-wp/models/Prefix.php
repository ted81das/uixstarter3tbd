<?php
/**
 * Change Database prefix
 *
 * @file  The Database prefix file
 * @package HMWP/Prefix
 * @since 7.3.0
 */
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

class HMWP_Models_Prefix {

	protected $prefix = 'wp_';

	/**
	 * Set the new prefix
	 *
	 * @param $value
	 *
	 * @return void
	 */
	public function setPrefix( $value ) {
		$this->prefix = $value;
	}

	/**
	 * Validate new prefix name
	 * Check if the new table prefix already exist as database prefix
	 *
	 * @return string the new database prefix
	 */
	public function generateValidateNewPrefix() {
		global $wpdb;

		// Generate a string with 5 chars
		$prefix = $this->generateRandomString( 5 );
		$prefix .= '_';

		if ( $wpdb->get_results( $wpdb->prepare( 'SHOW TABLES LIKE %s;', $prefix . '%' ), ARRAY_N ) ) {
			$prefix = $this->generateValidateNewPrefix();
		}

		return $prefix;
	}


	/**
	 * Get random string for a specific length
	 *
	 * @param $length
	 *
	 * @return string
	 */
	protected function generateRandomString( $length ) {

		//limit the string to these chars
		$characters   = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$randomString = '';
		$charCount    = strlen( $characters );

		for ( $i = 0; $i < $length; $i ++ ) {
			$randomString .= $characters[ wp_rand( 0, $charCount - 1 ) ];
		}

		return $randomString;
	}

	/**
	 * Run the change prefix process
	 *
	 * @return bool
	 */
	public function changePrefix() {

		try {

			if ( $this->prefix ) {

				//Change prefix in the config file
				if ( ! $this->changePrefixInConfig() ) {
					HMWP_Classes_Error::setNotification( __( 'Unable to update the <code>wp-config.php</code> file in order to update the Database Prefix.', 'hide-my-wp' ), 'error' );

					return false;
				}

				//Change the prefix of all tables
				$this->changePrefixInDatabase();
				//Change prefix in Options table
				$this->changePrefixOptionsDatabase();
				//Change prefix in User table
				$this->changePrefixUserDatabase();

				return true;
			}

		} catch ( Exception $e ) {
		}

		return false;
	}

	/**
	 * Process Database Prefix change in the wp-config file
	 *
	 * @return bool
	 * @throws Exception
	 * @since 7.3
	 *
	 */
	protected function changePrefixInConfig() {

		if ( $config_file = HMWP_Classes_Tools::getConfigFile() ) {

			$find    = '(\$table_prefix\s*=\s*)([\'"]).+?\\2(\s*;)';
			$replace = "\$table_prefix = '" . $this->prefix . "';" . PHP_EOL;

			//change the
			/** @var HMWP_Models_Rules $rulesModel */
			$rulesModel = HMWP_Classes_ObjController::getClass( 'HMWP_Models_Rules' );

			try {
				$wp_filesystem = HMWP_Classes_ObjController::initFilesystem();

				if ( ! $rulesModel->isConfigWritable( $config_file ) ) {
					$current_permission = $wp_filesystem->getchmod( $config_file );
					$wp_filesystem->chmod( $config_file, 0644 );
				}

				if ( $rulesModel->isConfigWritable( $config_file ) && $rulesModel->find( $find, $config_file ) ) {
					$return = $rulesModel->findReplace( $find, $replace, $config_file );

					if ( isset( $current_permission ) ) {
						$wp_filesystem->chmod( $config_file, octdec( $current_permission ) );
					}

					return $return;
				}

			} catch ( Exception $e ) {
			}

		}

		return false;

	}

	/**
	 * Process Database Prefix change in database
	 *
	 * @return void
	 * @throws Exception
	 * @since 7.3
	 *
	 */
	protected function changePrefixInDatabase() {
		global $wpdb;

		// Get all tables from DB
		$tables = $wpdb->get_results( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->base_prefix . '%' ), ARRAY_N );

		// Rename tables
		foreach ( $tables as $table ) {

			$table = substr( $table[0], strlen( $wpdb->base_prefix ), strlen( $table[0] ) );

			if ( $wpdb->query( 'RENAME TABLE `' . $wpdb->base_prefix . $table . '` TO `' . $this->prefix . $table . '`;' ) === false ) {
				HMWP_Classes_Error::setNotification( sprintf( __( 'Could not rename table %1$s. You may have to rename the table manually.', 'hide-my-wp' ), $wpdb->base_prefix . $table ), 'error' );
			}
		}

		// If WP Multisite, rename all blogs
		if ( HMWP_Classes_Tools::isMultisites() ) {
			$blogs = $wpdb->get_col( "SELECT blog_id FROM `" . $this->prefix . "blogs` WHERE public = '1' AND archived = '0' AND mature = '0' AND spam = '0' ORDER BY blog_id DESC" ); //get list of blog id's

			// Make sure there are other blogs to update
			if ( is_array( $blogs ) ) {
				// Update each blog's user_roles option
				foreach ( $blogs as $blog ) {
					$wpdb->query( 'UPDATE `' . $this->prefix . $blog . '_options` SET option_name = "' . $this->prefix . $blog . '_user_roles" WHERE option_name = "' . $wpdb->base_prefix . $blog . '_user_roles" LIMIT 1;' );
				}
			}
		}

	}

	/**
	 * Change Prefix in User Database
	 *
	 * @return void
	 */
	protected function changePrefixOptionsDatabase() {
		global $wpdb;

		// Update options table
		$updated_options = $wpdb->query( 'UPDATE `' . $this->prefix . 'options` SET option_name = "' . $this->prefix . 'user_roles" WHERE option_name = "' . $wpdb->base_prefix . 'user_roles" LIMIT 1;' );

		if ( $updated_options === false ) {
			HMWP_Classes_Error::setNotification( __( 'Could not update prefix references in options table.', 'better-wp-security' ), 'error' );
		}
	}

	/**
	 * Change Prefix in User Database
	 *
	 * @return void
	 */
	protected function changePrefixUserDatabase() {
		global $wpdb;

		// Get all usermeta data
		$rows = $wpdb->get_results( "SELECT * FROM `{$this->prefix}usermeta`" );

		// Change all prefixes in usermeta
		foreach ( $rows as $row ) {
			if ( 0 !== strpos( $row->meta_key, $wpdb->base_prefix ) ) {
				continue;
			}

			$pos     = $this->prefix . substr( $row->meta_key, strlen( $wpdb->base_prefix ), strlen( $row->meta_key ) );
			$updated = $wpdb->query( $wpdb->prepare( "UPDATE `{$this->prefix}usermeta` SET meta_key = %s WHERE meta_key = %s LIMIT 1", $pos, $row->meta_key ) );

			if ( ! $updated ) {
				HMWP_Classes_Error::setNotification( __( 'Could not update prefix references in usermeta table.', 'better-wp-security' ), 'error' );
			}
		}
	}

}
