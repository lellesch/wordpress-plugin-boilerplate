<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use MyVendorNamespace\MyPluginNamespace\Admin\Admin;
use MyVendorNamespace\MyPluginNamespace\Admin\Admin_App_Menu;
use MyVendorNamespace\MyPluginNamespace\Admin\Admin_Settings;
use MyVendorNamespace\MyPluginNamespace\Cron\Cron_Example;
use MyVendorNamespace\MyPluginNamespace\Frontend\Frontend;
use MyVendorNamespace\MyPluginNamespace\Rest\Example_Rest_API;
use MyVendorNamespace\MyPluginNamespace\Shortcodes\Shortcode_Manager;
use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Instance;

final class Bootstrap {

	use Singleton_Instance;

	protected string $plugin_basename;
	protected string $plugin_version;
	protected string $plugin_slug;
	protected string $plugin_prefix;


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
			$this->plugin_slug,
			false,
			plugin_basename( dirname( __DIR__, 2 ) ) . '/languages'
		);
	}


	private function define_admin_hooks(): void {

		// Admin class.
		$plugin_admin = Admin::get_instance( $this->get_plugin_slug(), $this->get_plugin_prefix(), $this->get_plugin_version() );
		$plugin_admin->init();

		// Admin Settings.
		$admin_settings = Admin_Settings::get_instance();
		$admin_settings->init();

		// Add Admin menu Admin_App_Menu Class
		$admin_app_menu = Admin_App_Menu::get_instance( $this->get_plugin_slug(), $this->get_plugin_prefix(), $this->get_plugin_version() );
		$admin_app_menu->init();


		// Additional hooks can be added here.
	}


	private function define_public_hooks(): void {

		$frontend = Frontend::get_instance( $this->get_plugin_slug(), $this->get_plugin_prefix(), $this->get_plugin_version() );
		$frontend->init();

		// Shortcodes.
		$shortcodes = Shortcode_Manager::get_instance( $this->get_plugin_prefix() );
		$shortcodes->init();

		// Rest Example.
		$rest = Example_Rest_API::get_instance();
		$rest->init();

		// Cron Example.
		$cron = Cron_Example::get_instance();
		$cron->init();
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
