<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Singleton_Instance {
	/**
	 * Registry für alle Klassen, die diesen Trait nutzen.
	 *
	 * @var array<string, static>
	 */
	private static array $instances = [];

	/**
	 * Gibt die Singleton-Instanz der aufrufenden Klasse zurück.
	 *
	 * @return static
	 */
	final public static function get_instance(): static {
		$class = static::class;

		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static();
		}

		return self::$instances[ $class ];
	}

	/**
	 * Geschützter Konstruktor, um direkte Instanziierung zu verhindern.
	 * Klassen, die diesen Trait verwenden, können ihn überschreiben,
	 * falls sie eigenen Initialisierungscode benötigen.
	 */
	protected function __construct() {}

	/**
	 * Verhindert das Klonen der Singleton-Instanz.
	 */
	final public function __clone(): void {
		throw new \LogicException( 'Cloning a singleton is not allowed.' );
	}

	/**
	 * Verhindert das Unserialisieren der Singleton-Instanz.
	 */
	final public function __wakeup(): void {
		throw new \LogicException( 'Unserializing a singleton is not allowed.' );
	}

	/**
	 * Verhindert das Serialisieren der Singleton-Instanz.
	 */
	final public function __serialize(): array {
		throw new \LogicException( 'Serializing a singleton is not allowed.' );
	}
}
