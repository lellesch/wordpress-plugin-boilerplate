<?php

namespace MyVendorNamespace\MyPluginNamespace\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Form_Guard {

	/**
	 * Validates the security check by verifying the presence of required POST parameters,
	 * user capabilities, and nonce consistency.
	 *
	 * @return bool Returns true if the security check is valid, otherwise false.
	 */
	private function is_security_check_valid(): bool {

		if ( ! isset( $_POST['action'] ) || ! isset( $_POST['_wpnonce'] ) || ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$required_action = sanitize_text_field( $this->form_action_name );
		$received_action = sanitize_text_field( wp_unslash( $_POST['action'] ) );

		if ( $received_action !== $required_action ) {
			return false;
		}

		check_admin_referer( $this->form_action_name );

		return true;
	}
}
