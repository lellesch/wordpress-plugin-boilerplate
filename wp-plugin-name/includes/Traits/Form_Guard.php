<?php

namespace MyVendorNamespace\MyPluginNamespace\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Form_Guard {


	/**
	 * Validates the form action by comparing the expected action name with the received action name.
	 *
	 * @return bool True if the received action matches the expected action, false otherwise.
	 */
	private function is_form_action_valid(): bool {
		if ( ! isset( $_POST['action'] ) ) {
			return false;
		}

		$required_action = sanitize_text_field( $this->form_action_name );
		$received_action = sanitize_text_field( wp_unslash( $_POST['action'] ) );

		return $received_action === $required_action;
	}

	/**
	 * Validates the security check by ensuring the nonce field is set and the user has the required capabilities.
	 *
	 * @return bool True if the nonce is verified and the user can manage options, false otherwise.
	 */
	private function is_security_check_valid(): bool {

		if ( ! isset( $_POST['_wpnonce'] ) || ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		check_admin_referer( $this->form_action_name );

		return true;
	}
}
