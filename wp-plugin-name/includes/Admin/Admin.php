<?php

declare(strict_types=1);

namespace MyVendorNamespace\MyPluginNamespace\Admin;

use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Admin {

	use Singleton_Instance;

	private string $plugin_slug;

	private string $plugin_prefix;

	private string $plugin_version;

	private function __construct( string $plugin_slug, string $plugin_prefix, string $plugin_version ) {
		$this->plugin_slug    = $plugin_slug;
		$this->plugin_prefix  = $plugin_prefix;
		$this->plugin_version = $plugin_version;
	}

	public function init(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
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
