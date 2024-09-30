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

	public static function get_instance( string $plugin_slug, string $plugin_prefix, string $plugin_version ): ?Admin {
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

	public function add_plugin_admin_menu(): void {

		add_menu_page(
			esc_html__( 'WordPress Plugin Boilerplate Dashboard', 'wp-plugin-name' ),
			esc_html__( 'WordPress Plugin Boilerplate', 'wp-plugin-name' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'render_menu_dashboard_page' ),
			'dashicons-admin-generic',
			100
		);

		add_submenu_page(
			$this->plugin_slug,
			esc_html__( 'WordPress Plugin Boilerplate Dashboard', 'wp-plugin-name' ),
			esc_html__( 'Dashboard', 'wp-plugin-name' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'render_menu_dashboard_page' )
		);

		add_submenu_page(
			$this->plugin_slug,
			esc_html__( 'Untermenü 1 Titel', 'wp-plugin-name' ),
			esc_html__( 'Untermenü 1', 'wp-plugin-name' ),
			'manage_options',
			$this->plugin_slug . '-page-slug-one',
			array( $this, 'render_menu_page_one' )
		);

		add_submenu_page(
			$this->plugin_slug,
			esc_html__( 'WordPress Plugin Boilerplate Einstellungen', 'wp-plugin-name' ),
			esc_html__( 'Einstellungen', 'wp-plugin-name' ),
			'manage_options',
			$this->plugin_slug . '-settings',
			array( $this, 'render_menu_settings_page' )
		);
	}


	public function render_menu_dashboard_page(): void {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Dashboard Seite', 'wp-plugin-name' ) . '</h1>';
		echo '</div>';
	}

	public function render_menu_page_one(): void {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Menü 1 Seite Beispiel', 'wp-plugin-name' ) . '</h1>';
		echo '</div>';
	}

	public function render_menu_settings_page(): void {
		/*
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Einstellungen Seite Beispiel', 'wp-plugin-name' ) . '</h1>';
		echo '</div>';*/

		$plugin_admin_settings_instance = Admin_Settings::get_instance();
		$plugin_admin_settings_instance->display_settings_page();
	}
}
