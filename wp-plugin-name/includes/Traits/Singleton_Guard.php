<?php
declare( strict_types=1 );

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
	 * @throws \Exception
	 */
	final public function __clone() {
		throw new \Exception( 'Cloning a singleton is not allowed.' );
	}


	/**
	 * Handles the wakeup process for the class.
	 *
	 * This method prevents the unserialization of instances of this class.
	 *
	 * @return void
	 * @throws \Exception
	 */
	final public function __wakeup() {
		throw new \Exception( 'You cannot unserialize instances of this class.' );
	}
}
