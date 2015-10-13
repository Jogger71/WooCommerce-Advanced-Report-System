<?php

/**
 * Plugin Name: WooCommerce Advanced Report System
 * Plugin URI: https://github.com/Jogger71/WooCommerce-Advanced-Report-System
 * Author: Adrian Du Plessis
 * Author URI: https://github.com/Jogger71/
 *
 * Description: A highly sophisticated woocommerce reporting system
 *
 * Version: 0.0.0
 */

if (!defined('ABSPATH')) {
    exit('Cheaters Detected!');
}

if (!class_exists('WC_Advanced_Report_System')) {
    class WC_Advanced_Report_System
    {

        /**
         * Class Constructor
         */
        public function __construct()
        {
            /**
             * This plugin is not allowed to run when WooCommerce is inactive, or not installed. Therefore if this plugin
             * is activated when WooCommerce isn't active or WooCommerce gets deactivated, this plugin will deactivate.
             */
            $this->prerequisite_checks();

            //  Create definitions
            define('WCARS_PLUGIN_LOCATION', dirname(__FILE__));

            include_once(WCARS_PLUGIN_LOCATION . '/classes/abstracts/class-report.php');
            include_once(WCARS_PLUGIN_LOCATION . '/classes/class-admin.php');
            include_once(WCARS_PLUGIN_LOCATION . '/classes/abstracts/class-ui.php');
            $admin = new WCARS_Admin();

            add_action('admin_enqueue_scripts', array($this, 'wcars_admin_styles'));
            add_action('admin_footer', array($this, 'wcars_footer_scripts'));
        }

        /**
         * Prerequisite checks
         *
         * This functions checks to see that everything needed is in place before continuing
         *
         * @since 0.1.0
         */
        private function prerequisite_checks()
        {
            $woocommerce = 'woocommerce/woocommerce.php';
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            //  Check woocommerce status
            if (!is_plugin_active($woocommerce)) {
                add_action('admin_notices', array($this, 'admin_notice'));
                deactivate_plugins(plugin_basename(__FILE__));
            }
        }

        /**
         * WooCommerce Error notice
         *
         * @since 0.1.0
         */
        public function admin_notice()
        {
            echo '<div class="error"><p><strong>Warning! WooCommerce Advanced Report System cannot work without WooCommerce</strong></p></div>';
        }

        /**
         * Admin Styles
         *
         * @since 0.1.0
         */
        public function wcars_admin_styles()
        {
            wp_enqueue_style('wcars_admin_style', plugins_url('/assets/style.css', __FILE__));
            wp_enqueue_style('wcars_select2_style', plugins_url('/assets/select2.min.css', __FILE__));
            wp_enqueue_script('jquery');
            wp_enqueue_script('wcars_select2_script', plugins_url('/assets/select2.full.min.js', __FILE__), array('jquery'));
        }

        /**
         * Footer Scripts
         *
         * @since 0.1.0
         */
        public function wcars_footer_scripts()
        {
            wp_enqueue_script('wcars_admin_script', plugins_url('/assets/script.js', __FILE__), array('jquery', 'wcars_select2_script'));
        }
    }
}

register_activation_hook(__FILE__, array('WC_Advanced_Report_System', 'prerequisite_checks'));

$GLOBALS['wcars'] = new WC_Advanced_Report_System();