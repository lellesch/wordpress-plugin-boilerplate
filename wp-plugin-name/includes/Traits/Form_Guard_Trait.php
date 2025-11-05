<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Form_Guard_Trait {

	/**
	 * The action name used for the form and nonce validation.
	 *
	 * @var string
	 */
	protected string $form_action_name;

	/**
	 * The capability required to submit the form.
	 *
	 * @var string
	 */
	protected string $required_capability = 'manage_options';

	/**
	 * The nonce field name.
	 *
	 * @var string
	 */
	protected string $nonce_field_name = '_wpnonce';

	/**
	 * Validates the security check by verifying the presence of required POST parameters,
	 * user capabilities, and nonce consistency.
	 *
	 * @return bool Returns true if the security check is valid, otherwise false.
	 */
	private function is_security_check_valid(): bool {

		// Check if required POST parameters are present.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is checked below
		if ( ! isset( $_POST['action'] ) || ! isset( $_POST[ $this->nonce_field_name ] ) ) {
			return false;
		}

		// Check user capability.
		if ( ! current_user_can( $this->required_capability ) ) {
			return false;
		}

		// Verify action matches (no sanitization needed for internal property).
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is checked below
		$received_action = sanitize_text_field( wp_unslash( $_POST['action'] ) );

		if ( $received_action !== $this->form_action_name ) {
			return false;
		}

		// Verify nonce - this will wp_die() if invalid, but we check the return value anyway.
		if ( ! check_admin_referer( $this->form_action_name, $this->nonce_field_name ) ) {
			return false;
		}

		return true;
	}
}
