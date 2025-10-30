<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Boilerplate
 * Description:       This is a short description of what the plugin does.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Requires at least: 6.5
 * Tested up to:      6.7
 * Requires PHP:      8.1
 * Author URI:        http://example.com
 * Text Domain:       wp-plugin-name
 * Domain Path:       /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

use MyVendorNamespace\MyPluginNamespace\Core\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the constants used across the plugin
 */
define( 'WP_PLUGIN_SLUG', 'wp-plugin-name' );
define( 'WP_PLUGIN_PREFIX', 'wp_plugin_name_' );
define( 'WP_PLUGIN_VERSION', '1.0.0' );
define( 'WP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );


if ( file_exists( WP_PLUGIN_DIR_PATH . 'vendor/autoload.php' ) ) {
	require WP_PLUGIN_DIR_PATH . 'vendor/autoload.php';
} else {
	if ( current_user_can( 'manage_options' ) ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-error"><p>';
			echo esc_html__( 'Der Composer-Autoloader wurde nicht gefunden. Bitte führe im Plugin-Verzeichnis den Befehl "composer install" aus.', 'wp-plugin-name' );
			echo '</p></div>';
		} );
	}

	// Abbrechen, um Fehler zu vermeiden.
	return;
}

/**
 * Initialize the plugin.
 */
Bootstrap::get_instance( WP_PLUGIN_SLUG, WP_PLUGIN_PREFIX, WP_PLUGIN_VERSION, WP_PLUGIN_BASENAME );
