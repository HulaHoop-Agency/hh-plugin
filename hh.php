<?php

/**
 * Plugin Name:       HulaHoop
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
define('DOMAIN', $_SERVER['SERVER_NAME']);

if (!class_exists('HHInit')) :
	/**
	 * HHInit
	 */
	class HHInit {

		const TIMESTAMP_OPTION_NAME = 'HULAHOOP-TIMESTAMP';
		const HH_DEV_EMAIL = 'mrevellin@hula-hoop.fr';
		const CRON_PP_ACTION = 'HH_CHECK_PP';
		const CRON_PP_RECURRING = 'hourly';

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
			register_activation_hook(__FILE__, 'add_timestamp');
			register_activation_hook(__FILE__, 'register_cron');
			register_deactivation_hook(__FILE__, 'remove_timestamp');
			function add_timestamp() {
				if (!get_option(HHInit::TIMESTAMP_OPTION_NAME)) {
					add_option(HHInit::TIMESTAMP_OPTION_NAME, current_time('timestamp'));
				}
			}
			function register_cron() {
				if (!wp_next_scheduled(HHInit::CRON_PP_ACTION)) {
					wp_schedule_event(time(), HHInit::CRON_PP_RECURRING, HHInit::CRON_PP_ACTION);
				}
			}
			function remove_timestamp() {
				if (get_option(HHInit::TIMESTAMP_OPTION_NAME)) {
					delete_option(HHInit::TIMESTAMP_OPTION_NAME);
				}
			}
		}

		/**
		 * CRON
		 */
		public function crons() {
			add_action(HHInit::CRON_PP_ACTION, 'check_PP');
			function check_PP() {
				$register_timestamp = get_option(HHInit::TIMESTAMP_OPTION_NAME);
				if ($register_timestamp < time() - strtotime('-3 months') && DOMAIN === 'votrepreprod.fr') {
					wp_mail(HHInit::HH_DEV_EMAIL, '⚠️ - ' . DOMAIN, 'Ce site est en pre-production depuis plus de 3 mois, que faut-il faire avec ?', array('Content-Type: text/html; charset=UTF-8'));
				}
			}
		}
	}
	new HHInit();
endif;
