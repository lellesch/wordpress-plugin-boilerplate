<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Core;

use MyVendorNamespace\MyPluginNamespace\Cron\Cron_Example;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin deactivation
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 **/
class Deactivator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate(): void {
		Cron_Example::get_instance()->deactivate_cron();
	}
}
