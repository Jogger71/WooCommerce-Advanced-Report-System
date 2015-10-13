<?php

/**
 * WC Advanced Report System Admin System
 *
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheaters Detected!' );
}

if ( ! class_exists( 'WCARS_Admin' ) ) {
	class WCARS_Admin {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'wcars_create_admin_pages' ) );
		}

		/**
		 * Create admin pages
		 *
		 * @since 0.1.0
		 */
		public function wcars_create_admin_pages() {
			/**
			 * Main Admin Page, Today's information
			 *
			 * @since 0.1.0
			 */
			add_menu_page(
				"Today's Figures",
				'WC Reports',
				'manage_options',
				'wcars-today',
				array( $this, 'wcars_today_admin_page' )
			);

			/**
			 * Today's Information
			 *
			 * @since 0.1.0
			 */
			add_submenu_page(
				'wcars-today',
				"Monthly Figures",
				'Monthly Figures',
				'manage_options',
				'wcars-today',
				array( $this, 'wcars_today_admin_page' )
			);
		}

		/**
		 * Today's Figures
		 *
		 * @since 0.1.0
		 */
		public function wcars_today_admin_page() {
			include_once( WCARS_PLUGIN_LOCATION . '/templates/reports/report-today/report.php' );
		}
	}
}