<?php

declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles caching of data using transients in WordPress.
 */
class Cache_Transient {

	/**
	 * Caches data with an associated key and expiration time.
	 *
	 * @param string $key The unique identifier for the cached data.
	 * @param mixed  $data The data to be cached.
	 * @param int    $expiration The time in seconds until the cached data expires. Defaults to one hour.
	 *
	 * @return bool True if data was successfully cached, false otherwise.
	 */
	public static function cache_set( string $key, mixed $data, int $expiration = HOUR_IN_SECONDS ): bool {
		return set_transient( WP_PLUGIN_PREFIX . $key, $data, $expiration );
	}

	/**
	 * Retrieve a cached value from the transient cache.
	 *
	 * @param string $key The unique key for the cached value.
	 *
	 * @return mixed The cached value, or false if the key does not exist.
	 */
	public static function cache_get( string $key ): mixed {
		return get_transient( WP_PLUGIN_PREFIX . $key );
	}

	/**
	 * Deletes a cached transient with the given key.
	 *
	 * @param string $key The key of the transient to delete.
	 *
	 * @return bool True if the transient was successfully deleted, false otherwise.
	 */
	public static function cache_delete( string $key ): bool {
		return delete_transient( WP_PLUGIN_PREFIX . $key );
	}
}
