<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Admin;

defined( 'ABSPATH' ) || exit;

final class Admin_Notice {
	private const string OPTION_KEY = WP_PLUGIN_PREFIX . 'notices';
	/** @var string[] */
	private const array ALLOWED_TYPES = [ 'success', 'error', 'warning', 'info' ];

	public static function init(): void {
		// Option einmalig ohne Autoload anlegen
		if ( false === get_option( self::OPTION_KEY, false ) ) {
			add_option( self::OPTION_KEY, [], '', 'no' );
		}

		add_action( 'admin_notices', [ __CLASS__, 'display_notices' ], 12 );
	}

	/**
	 * Fügt eine Admin-Notice hinzu (roh speichern, Whitelist auf type).
	 */
	public static function add_notice( string $message, string $type = 'success', bool $dismissible = true ): void {
		$type = in_array( $type, self::ALLOWED_TYPES, true ) ? $type : 'success';

		$notices   = get_option( self::OPTION_KEY, [] );
		$notices[] = [
			// keine harte Sanitization hier – erst beim Output escapen!
			'message'     => $message,
			'type'        => $type,
			'dismissible' => $dismissible,
		];

		update_option( self::OPTION_KEY, $notices, 'no' ); // autoload=no beibehalten
	}

	/**
	 * Rendert alle Notices und leert danach die Option.
	 */
	public static function display_notices(): void {
		$notices = get_option( self::OPTION_KEY, [] );
		if ( empty( $notices ) ) {
			return;
		}

		foreach ( $notices as $notice ) {
			self::render_notice( $notice );
		}

		delete_option( self::OPTION_KEY );
		// Optional: wieder anlegen ohne Autoload
		add_option( self::OPTION_KEY, [], '', 'no' );
	}

	/**
	 * Sicheres Rendering.
	 * - Klassen escapen mit esc_attr
	 * - Inhalt mit wp_kses_post (erlaubt Links/Bold etc.)
	 */
	private static function render_notice( array $notice ): void {
		$type        = isset( $notice['type'] ) ? (string) $notice['type'] : 'success';
		$dismissible = ! empty( $notice['dismissible'] );

		$classes = [ 'notice', 'notice-' . $type ];
		if ( $dismissible ) {
			$classes[] = 'is-dismissible';
		}

		$class_attr = esc_attr( implode( ' ', $classes ) );
		$content    = isset( $notice['message'] ) ? wp_kses_post( $notice['message'] ) : '';

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class_attr, $content );
	}
}
