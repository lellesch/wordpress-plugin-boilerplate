<?php

namespace MyVendorNamespace\MyPluginNamespace\Frontend;

use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Instance_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


final class Frontend {

	use Singleton_Instance_Trait;


	private string $plugin_slug;


	private string $plugin_prefix;


	private string $plugin_version;


	private function __construct( string $plugin_slug, string $plugin_prefix, string $plugin_version ) {
		$this->plugin_slug    = $plugin_slug;
		$this->plugin_prefix  = $plugin_prefix;
		$this->plugin_version = $plugin_version;
	}

	/**
	 * Registriert die Hooks für Frontend-Assets.
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	public function enqueue_styles(): void {
		if ( is_admin() ) {
			return;
		}

		$style_path = WP_PLUGIN_DIR_URL . 'assets/css/frontend.css';

		if ( file_exists( WP_PLUGIN_DIR_PATH . 'assets/css/frontend.css' ) ) {
			wp_enqueue_style( $this->plugin_slug, $style_path, array(), $this->plugin_version, 'all' );
		}
	}


	public function enqueue_scripts(): void {
		if ( is_admin() ) {
			return;
		}

		$script_path = WP_PLUGIN_DIR_URL . 'assets/js/frontend.js';

		if ( file_exists( WP_PLUGIN_DIR_PATH . 'assets/js/frontend.js' ) ) {
			wp_enqueue_script(
				$this->plugin_slug,
				$script_path,
				array( 'jquery' ),
				$this->plugin_version,
				array(
					'strategy' => 'defer',
				)
			);
		}
	}
}
