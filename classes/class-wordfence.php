<?php

defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'HHWordfenceImport' ) ) {

	class HHWordfenceImport {

		public $token;

		public function __construct() {

			$this->token = '1992fb400d15763cd916a6e46315b5c0fdc6ea8f1c876dd1cea391b86f4bbc8da116e7d8c32487d3e90194c718427bd5e077eabd1f9fec1bd828d3af5e0ea2be';

			if ( is_plugin_active( 'wordfence' ) ) {
				require_once ABSPATH . 'wp-content/plugins/wordfence/lib/wordfenceConstants.php';
				require_once ABSPATH . 'wp-content/plugins/wordfence/lib/wordfenceClass.php';
				require_once ABSPATH . '/wp-load.php';
				wordfence::importSettings( $this->token );
			}

		}

	}

	new HHWordfenceImport();

}

