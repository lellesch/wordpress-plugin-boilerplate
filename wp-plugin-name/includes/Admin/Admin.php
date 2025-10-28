<?php

declare(strict_types=1);

namespace MyVendorNamespace\MyPluginNamespace\Admin;

use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Guard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {

	use Singleton_Guard;

	private static ?Admin $instance = null;

	private string $plugin_slug;

	private string $plugin_prefix;

	private string $plugin_version;

	public static function get_instance( string $plugin_slug, string $plugin_prefix, string $plugin_version ): Admin {
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin_slug, $plugin_prefix, $plugin_version );
		}

		return self::$instance;
	}

	private function __construct( string $plugin_slug, string $plugin_prefix, string $plugin_version ) {
		$this->plugin_slug    = $plugin_slug;
		$this->plugin_prefix  = $plugin_prefix;
		$this->plugin_version = $plugin_version;
	}


	public function enqueue_styles(): void {
		$style_path = WP_PLUGIN_DIR_URL . 'assets/css/admin.css';

		if ( file_exists( WP_PLUGIN_DIR_PATH . 'assets/css/admin.css' ) ) {
			wp_enqueue_style( $this->plugin_slug, $style_path, array(), $this->plugin_version, 'all' );
		}
	}


	public function enqueue_scripts(): void {
		$script_path = WP_PLUGIN_DIR_URL . 'assets/js/admin.js';

		if ( file_exists( WP_PLUGIN_DIR_PATH . 'assets/js/admin.js' ) ) {
			wp_enqueue_script( $this->plugin_slug, $script_path, array( 'jquery' ), $this->plugin_version, false );
		}
	}
}
