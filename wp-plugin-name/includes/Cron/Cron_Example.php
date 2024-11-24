<?php
declare(strict_types=1);

namespace MyVendorNamespace\MyPluginNamespace\Cron;

use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Guard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Cron_Example {

	use Singleton_Guard;

	const PREFIX_NAME                      = WP_PLUGIN_PREFIX;
	private static ?Cron_Example $instance = null;

	private function __construct() {
		add_action( 'wp', [ $this, 'activate_cron' ] );
		add_action( self::PREFIX_NAME . 'cron_job_hook', array( $this, 'cron_job_hook' ) );
	}

	public static function get_instance(): ?Cron_Example {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Activates the cron job.
	 *
	 * This method should be called to activate the cron job. It checks if the cron job has already been scheduled
	 * and if not, it schedules it to run twice daily using the wp_schedule_event function.
	 *
	 * @return void
	 */
	public function activate_cron(): void {
		if ( ! wp_next_scheduled( self::PREFIX_NAME . 'cron_job_hook' ) ) {
			wp_schedule_event( time(), 'twicedaily', self::PREFIX_NAME . 'cron_job_hook' );
		}
	}

	public function deactivate_cron(): void {
		$timestamp = wp_next_scheduled( self::PREFIX_NAME . 'cron_job_hook' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::PREFIX_NAME . 'cron_job_hook' );
		}
	}

	/**
	 * Performs tasks for a cron job.
	 *
	 * This method should be called by the cron job script to execute any necessary tasks.
	 *
	 * It is recommended to place the necessary code for the cron job inside this method.
	 *
	 * @return void
	 */
	public function cron_job_hook(): void {
		// Code für Cronjob abarbeiten.
	}
}
