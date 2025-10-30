<?php

declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Shortcodes;

use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Verwaltet alle Shortcodes des Plugins.
 */
final class Shortcode_Manager {

	use Singleton_Instance;

	private string $plugin_prefix;

	private function __construct( string $plugin_prefix ) {
		$this->plugin_prefix = $plugin_prefix;
	}

	/**
	 * Initialisiert alle Shortcodes.
	 */
	public function init(): void {
		// Content Box Shortcode.
		$content_box = Content_Box_Shortcode::get_instance( $this->plugin_prefix );
		$content_box->init();

		// Hier können weitere Shortcodes hinzugefügt werden.
	}
}
