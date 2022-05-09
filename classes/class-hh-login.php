<?php
class HH_Login {

	public function __construct() {

		$this->url = 'hh-admin';

		/**
		* Hooks
		*/

		add_action( 'login_head', array( $this, 'admin_login_url_change_redirect_error_page' ) );
		add_action( 'init', array( $this, 'admin_login_url_change_redirect_success_page' ) );
		add_action( 'wp_logout', array( $this, 'admin_login_url_change_redirect_login_page' ) );
		add_action( 'wp_login_failed', array( $this, 'admin_login_url_change_redirect_failed_login_page' ) );

	}


	/**
	* Redirect Error Page
	*/

	public function admin_login_url_change_redirect_error_page() {

		$hh_new_login = wp_unslash( $this->url );
		if ( strpos( $_SERVER['REQUEST_URI'], $hh_new_login ) === false ) {
			wp_safe_redirect( home_url( '404' ), 302 );
			exit();
		}
	}

	/**
	* Redirect Success Page
	*/

	public function admin_login_url_change_redirect_success_page() {
		$hh_new_login                       = wp_unslash( $this->url );
		$hh_wp_admin_login_current_url_path = wp_parse_url( $_SERVER['REQUEST_URI'] );

		if ( '/' . $hh_new_login === $hh_wp_admin_login_current_url_path['path'] ) {
			wp_safe_redirect( home_url( "wp-login.php?$hh_new_login&redirect=false" ) );
			exit();
		}
	}

	/**
	* Redirect Login Page
	*/

	public function admin_login_url_change_redirect_login_page() {
		$hh_new_login = wp_unslash( $this->url );
		wp_safe_redirect( home_url( "wp-login.php?$hh_new_login&redirect=false" ) );
		exit();
	}

	/**
	* Redirect Login Page for Login Failed
	*/

	public function admin_login_url_change_redirect_failed_login_page( $username ) {
		$hh_new_login = wp_unslash( $this->url );
		wp_safe_redirect( home_url( "wp-login.php?$hh_new_login&redirect=false" ) );
		exit();
	}


}

new HH_Login();
