<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Singleton_Guard {

	/**
	 * Handles the cloning operation for this singleton.
	 * Cloning a singleton is not allowed, so this method will trigger an appropriate error.
	 *
	 * @return void
	 */
	final public function __clone() {
		_doing_it_wrong(
			__FUNCTION__,
			'Cloning a singleton is not allowed.',
			WP_PLUGIN_VERSION
		);
	}


	/**
	 * Handles the wakeup process for the class.
	 *
	 * This method prevents the unserialization of instances of this class.
	 *
	 * @return void
	 */
	final public function __wakeup() {
		_doing_it_wrong(
			__FUNCTION__,
			'You cannot unserialize instances of this class.',
			WP_PLUGIN_VERSION
		);
	}
}
