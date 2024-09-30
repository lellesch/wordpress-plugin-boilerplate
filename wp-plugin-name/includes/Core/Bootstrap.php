<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use MyVendorNamespace\MyPluginNamespace\Admin\Admin;
use MyVendorNamespace\MyPluginNamespace\Admin\Admin_Settings;
use MyVendorNamespace\MyPluginNamespace\Cron\Cron_Example;
use MyVendorNamespace\MyPluginNamespace\Frontend\Frontend;
use MyVendorNamespace\MyPluginNamespace\Rest\Example_Rest_API;
use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Guard;

class Bootstrap {

	use Singleton_Guard;

	private static ?Bootstrap $instance = null;
	protected string $plugin_basename;
	protected string $plugin_version;
	protected string $plugin_slug;
	protected string $plugin_prefix;

	public static function get_instance( string $plugin_slug, string $plugin_prefix, string $plugin_version, string $plugin_basename ): Bootstrap {
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin_slug, $plugin_prefix, $plugin_version, $plugin_basename );
		}

		return self::$instance;
	}


	private function __construct( string $plugin_slug, string $plugin_prefix, string $plugin_version, string $plugin_basename ) {

		$this->plugin_slug     = $plugin_slug;
		$this->plugin_prefix   = $plugin_prefix;
		$this->plugin_version  = $plugin_version;
		$this->plugin_basename = $plugin_basename;

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_activation_deactivation_hooks();
	}


	private function register_activation_deactivation_hooks(): void {
		register_activation_hook( $this->plugin_basename, array( Activator::class, 'activate' ) );
		register_deactivation_hook( $this->plugin_basename, array( Deactivator::class, 'deactivate' ) );
	}


	private function set_locale(): void {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	public function load_plugin_textdomain(): void {
		load_plugin_textdomain(
			'wp-plugin-name',
			false,
			plugin_basename( dirname( __DIR__, 2 ) ) . '/languages'
		);
	}


	private function define_admin_hooks(): void {

		$plugin_admin = Admin::get_instance( $this->get_plugin_slug(), $this->get_plugin_prefix(), $this->get_plugin_version() );

		$plugin_admin_settings = Admin_Settings::get_instance();

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $plugin_admin, 'add_plugin_admin_menu' ) );

		// Additional hooks can be added here.
	}


	private function define_public_hooks(): void {

		$plugin_public = Frontend::get_instance( $this->get_plugin_slug(), $this->get_plugin_prefix(), $this->get_plugin_version() );

		// Rest Example.
		Example_Rest_API::get_instance();

		// Cron Example.
		Cron_Example::get_instance();

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );
	}


	public function get_plugin_slug(): string {
		return $this->plugin_slug;
	}


	public function get_plugin_prefix(): string {
		return $this->plugin_prefix;
	}


	public function get_plugin_version(): string {
		return $this->plugin_version;
	}
}
