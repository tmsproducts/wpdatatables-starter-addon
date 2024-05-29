<?php
/**
 * @package Starter Addon for wpDataTables
 * @version 1.0.0
 *
 * Plugin Name: Starter Addon for wpDataTables
 * Plugin URI: https://wpdatatables.com/
 * Description: This is an example of a starter add-on/plugin that you can use to extend wpDataTables
 * Version: 1.0.0
 * Author: TMS-Plugins
 * Author URI: https://www.tmsproducts.io/
 * Text Domain: wpdatatables-starter-addon
 *
 *  Requires Plugins: wpdatatables
 *  wpdatatables Lite tested up to: 3.4.2.16
 *  wpdatatables Premium tested up to: 6.5
 * /
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Full path to the Starter Addon root directory
define('WPDATATABLES_STARTER_ADDON_ROOT_PATH', plugin_dir_path(__FILE__));
// URL of WPDATATABLES Starter Addon plugin
define('WPDATATABLES_STARTER_ADDON_ROOT_URL', plugin_dir_url(__FILE__));
// Basename of WPDATATABLES Starter Addon plugin
define('WPDATATABLES_STARTER_ADDON_BASENAME', plugin_basename(__FILE__));
// Current version of Starter Addon plugin
define('WPDATATABLES_STARTER_ADDON_VERSION', '1.0.0');
// Required wpDataTables version
define('WPDATATABLES_STARTER_ADDON_VERSION_TO_CHECK', '3.4');
// Path to Starter Addon templates
define('WPDATATABLES_STARTER_ADDON_TEMPLATE_PATH', WPDATATABLES_STARTER_ADDON_ROOT_PATH . 'templates/');

function wpDataTableStarterAddonLoad() {

    // Load plugin file
    require_once( __DIR__ . '/includes/plugin.php' );

    // Run the plugin
    \WPDataTableStarterAddon\Plugin::instance();

}
add_action( 'plugins_loaded', 'wpDataTableStarterAddonLoad' );