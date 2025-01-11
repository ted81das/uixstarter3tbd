<?php
/**
 * Handle all the errors in the plugin
 *
 * @file The Errors Handle file
 * @package HMWP/Error
 * @since 4.0.0
 *
 */

defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

class HMWP_Classes_Error {

	/**
	 *
	 * Array of errors generated by the plugin
	 *
	 * @var array
	 */
	private static $errors = array();

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'hookNotices' ) );
		add_action( 'network_admin_notices', array( $this, 'hookNotices' ) );
	}

	/**
	 * Set the notification in WordPress
	 *
	 * @param  string  $error
	 * @param  string  $type
	 * @param  bool  $ignore
	 *
	 * @return void
	 * @deprecated changed with setNotification for multiple usage
	 */
	public static function setError( $error = '', $type = 'notice', $ignore = true ) {
		self::setNotification( $error, $type, $ignore );
	}

	/**
	 * Return the list of errors
	 *
	 * @return array
	 */
	public static function getErrors() {
		return self::$errors;
	}

	/**
	 * Set the notification in WordPress
	 *
	 * @param  string  $error  Error message to show in plugin
	 * @param  string  $type  Define the notification class 'notice', 'warning', 'dander'. Default 'notice'
	 * @param  bool  $ignore  Let user ignore this notification
	 *
	 * @return void
	 */
	public static function setNotification( $error = '', $type = 'notice', $ignore = true ) {
		if ( ! $error ) {
			return;
		}

		if ( $type == 'notice' && $ignore && $ignore_errors = (array) HMWP_Classes_Tools::getOption( 'ignore_errors' ) ) {
			if ( ! empty( $ignore_errors ) && in_array( strlen( $error ), $ignore_errors ) ) {
				return;
			}
		}

		self::$errors[ md5( $error ) ] = array(
			'type'   => $type,
			'ignore' => $ignore,
			'text'   => $error
		);

	}

	/**
	 * Return if error
	 *
	 * @return bool
	 */
	public static function isError() {
		if ( ! empty( self::$errors ) ) {
			foreach ( self::$errors as $error ) {
				if ( $error['type'] <> 'success' ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Clear the errors
	 *
	 * @return void
	 */
	public static function clearErrors() {
		self::$errors = array();
	}

	/**
	 * This hook will show the error in WP header
	 */
	public function hookNotices() {
		if ( is_array( self::$errors ) && ( ( is_string( HMWP_Classes_Tools::getValue( 'page', '' ) ) && stripos( HMWP_Classes_Tools::getValue( 'page', '' ), _HMWP_NAMESPACE_ ) !== false ) || ( is_string( HMWP_Classes_Tools::getValue( 'plugin', '' ) ) && stripos( HMWP_Classes_Tools::getValue( 'plugin', '' ), dirname( HMWP_BASENAME ) ) !== false ) ) ) {
			foreach ( self::$errors as $error ) {
				self::showError( $error['text'], $error['type'], $error['ignore'] );
			}
		}
		self::$errors = array();
	}

	/**
	 * Show the notices to WP
	 *
	 * @param  string  $message  Error message to show in plugin
	 * @param  string  $type  Define the notification class 'notice', 'warning', 'dander'. Default 'notice'
	 * @param  bool  $ignore  Let user ignore this notification
	 */
	public static function showError( $message, $type = 'notice', $ignore = true ) {

		//Initialize WordPress Filesystem
		$wp_filesystem = HMWP_Classes_ObjController::initFilesystem();

		if ( $wp_filesystem->exists( _HMWP_THEME_DIR_ . 'Notices.php' ) ) {
			include _HMWP_THEME_DIR_ . 'Notices.php';
		} else {
			echo wp_kses_post( $message ); //returns the
		}
	}

	/**
	 * Run the actions on submit
	 *
	 * @throws Exception
	 */
	public function action() {

		if ( ! HMWP_Classes_Tools::userCan( HMWP_CAPABILITY ) ) {
			return;
		}

		switch ( HMWP_Classes_Tools::getValue( 'action' ) ) {
			case 'hmwp_ignoreerror':
				$hash = HMWP_Classes_Tools::getValue( 'hash' );

				$ignore_errors = (array) HMWP_Classes_Tools::getOption( 'ignore_errors' );

				$ignore_errors[] = $hash;
				$ignore_errors   = array_unique( $ignore_errors );
				$ignore_errors   = array_filter( $ignore_errors );

				HMWP_Classes_Tools::saveOptions( 'ignore_errors', $ignore_errors );

				wp_redirect( remove_query_arg( array( 'hmwp_nonce', 'action', 'hash' ) ) );
				exit();
		}
	}

}
