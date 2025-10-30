<?php
declare(strict_types=1);

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
	 * Unterstützt sowohl parameterlose als auch parametrisierte Instanziierung.
	 * Bei parametrisierter Instanziierung müssen die Parameter beim ersten Aufruf übergeben werden.
	 *
	 * @param mixed ...$args Konstruktor-Parameter (optional).
	 * @return static
	 */
	final public static function get_instance( ...$args ): static {
		$class = static::class;

		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static( ...$args );
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
	 *
	 * @throws \BadMethodCallException
	 */
	final public function __clone(): void {
		throw new \BadMethodCallException( 'Cloning a singleton is not allowed.' );
	}

	/**
	 * Verhindert das Unserialisieren der Singleton-Instanz (alte Serialisierung).
	 *
	 * @throws \BadMethodCallException
	 */
	final public function __wakeup(): void {
		throw new \BadMethodCallException( 'Unserializing a singleton is not allowed.' );
	}

	/**
	 * Verhindert das Serialisieren der Singleton-Instanz (neue Serialisierung).
	 *
	 * @return array
	 * @throws \BadMethodCallException
	 */
	final public function __serialize(): array {
		throw new \BadMethodCallException( 'Serializing a singleton is not allowed.' );
	}

	/**
	 * Verhindert das Unserialisieren der Singleton-Instanz (neue Serialisierung).
	 *
	 * @param array $data Serialized data.
	 * @throws \BadMethodCallException
	 */
	final public function __unserialize( array $data ): void {
		throw new \BadMethodCallException( 'Unserializing a singleton is not allowed.' );
	}
}
