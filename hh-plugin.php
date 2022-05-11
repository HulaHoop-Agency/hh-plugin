<?php

/**
 * Plugin Name:       HH Plugin
 * Description:       Custom plugin made by HulaHoop for better website production.
 * Version:           1.0
 * Requires at least: 3.0 or higher
 * Requires PHP:      5.6
 * Tested up to:      5.9
 * Stable tag:        1.0
 * Author:            HulaHoop
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Contributors:      HulaHoop
 */

/**
 * CONSTANTS
 */
define( 'DOMAIN', $_SERVER['SERVER_NAME'] );
define( 'PLUGIN_PATH', get_template_directory() );
define( 'PLUGIN_CLASSES_PATH', plugin_dir_path( __FILE__ ) . '/classes/' );

if ( ! class_exists( 'HHInit' ) ) :
	/**
	 * HHInit
	 */
	class HHInit {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->setup_actions();
			$this->crons();
		}

		/**
		 * Setting up Hooks
		 */
		public function setup_actions() {
			register_activation_hook( __FILE__, 'add_timestamp' );
			register_activation_hook( __FILE__, 'register_cron' );
			register_deactivation_hook( __FILE__, 'remove_timestamp' );
			function add_timestamp() {
				if ( ! get_option( 'HULAHOOP-TIMESTAMP' ) ) {
					add_option( 'HULAHOOP-TIMESTAMP', current_time( 'timestamp' ) );
				}
			}
			function register_cron() {
				if ( ! wp_next_scheduled( 'HH_CHECK_PP' ) ) {
					wp_schedule_event( time(), 'hourly', 'HH_CHECK_PP' );
				}
			}
			function remove_timestamp() {
				if ( get_option( 'HULAHOOP-TIMESTAMP' ) ) {
					delete_option( 'HULAHOOP-TIMESTAMP' );
				}
			}
		}

		/**
		 * CRON
		 */
		public function crons() {
			add_action( 'HH_CHECK_PP', 'check_PP' );
			function check_PP() {
				$register_timestamp = get_option( 'HULAHOOP-TIMESTAMP' );
				if ( $register_timestamp < time() - strtotime( '-3 months' ) && DOMAIN === 'votrepreprod.fr' ) {
					wp_mail( 'mrevellin@hula-hoop.fr', '⚠️ - ' . DOMAIN, 'Ce site est en pre-production depuis plus de 3 mois, que faut-il faire avec ?', array( 'Content-Type: text/html; charset=UTF-8' ) );
				}
			}
		}
	}

	/**
	 * Require files before
	 */
	require PLUGIN_CLASSES_PATH . 'class-hh-login.php';
	require PLUGIN_CLASSES_PATH . 'class-hh-upgrade.php';
	new HHInit();
endif;
