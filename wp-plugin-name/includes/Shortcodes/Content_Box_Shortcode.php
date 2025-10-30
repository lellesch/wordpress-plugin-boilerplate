<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Shortcodes;

use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Content Box Shortcode: [wp_plugin_name_content_box style="info" title="Titel"]Inhalt[/wp_plugin_name_content_box]
 *
 * Verwendungsbeispiele:
 * [wp_plugin_name_content_box style="info" title="Hinweis"]Dies ist ein Hinweis[/wp_plugin_name_content_box]
 * [wp_plugin_name_content_box style="warning" dismissible="yes"]Warnung![/wp_plugin_name_content_box]
 * [wp_plugin_name_content_box style="success"]Erfolgreich gespeichert[/wp_plugin_name_content_box]
 */
final class Content_Box_Shortcode {

	use Singleton_Instance;

	private string $plugin_prefix;
	private string $shortcode_tag;

	private function __construct( string $plugin_prefix ) {
		$this->plugin_prefix = $plugin_prefix;
		$this->shortcode_tag = $plugin_prefix . 'content_box';
	}

	/**
	 * Initialisiert den Shortcode.
	 */
	public function init(): void {
		add_shortcode( $this->shortcode_tag, array( $this, 'render' ) );
	}

	/**
	 * Rendert den Shortcode.
	 *
	 * @param array|string $atts Shortcode-Attribute.
	 * @param string|null  $content Shortcode-Inhalt.
	 * @return string HTML-Output.
	 */
	public function render( $atts, ?string $content = null ): string {
		$atts = shortcode_atts(
			array(
				'style'       => 'default', // default, info, warning, success, error
				'title'       => '',
				'icon'        => 'yes', // yes, no
				'dismissible' => 'no', // yes, no
			),
			$atts,
			$this->shortcode_tag
		);

		// Wenn kein Inhalt vorhanden ist, nichts zurückgeben.
		if ( empty( $content ) ) {
			return '';
		}

		// Validierung.
		$valid_styles  = array( 'default', 'info', 'warning', 'success', 'error' );
		$atts['style'] = in_array( $atts['style'], $valid_styles, true ) ? $atts['style'] : 'default';
		$atts['title'] = sanitize_text_field( $atts['title'] );
		$atts['icon']  = 'no' === $atts['icon'] ? 'no' : 'yes';

		// CSS-Klassen zusammenstellen.
		$classes = array(
			$this->plugin_prefix . 'content-box',
			$this->plugin_prefix . 'content-box--' . $atts['style'],
		);

		if ( 'yes' === $atts['dismissible'] ) {
			$classes[] = $this->plugin_prefix . 'content-box--dismissible';
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		     role="<?php echo 'error' === $atts['style'] || 'warning' === $atts['style'] ? 'alert' : 'region'; ?>">

			<?php if ( 'yes' === $atts['icon'] ) : ?>
				<span class="<?php echo esc_attr( $this->plugin_prefix . 'content-box__icon' ); ?>" aria-hidden="true">
					<?php echo wp_kses( $this->get_icon( $atts['style'] ), $this->get_allowed_icon_html() ); ?>
				</span>
			<?php endif; ?>

			<div class="<?php echo esc_attr( $this->plugin_prefix . 'content-box__content' ); ?>">
				<?php if ( ! empty( $atts['title'] ) ) : ?>
					<h4 class="<?php echo esc_attr( $this->plugin_prefix . 'content-box__title' ); ?>">
						<?php echo esc_html( $atts['title'] ); ?>
					</h4>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $this->plugin_prefix . 'content-box__text' ); ?>">
					<?php echo wp_kses_post( do_shortcode( $content ) ); ?>
				</div>
			</div>

			<?php if ( 'yes' === $atts['dismissible'] ) : ?>
				<button type="button"
				        class="<?php echo esc_attr( $this->plugin_prefix . 'content-box__close' ); ?>"
				        aria-label="<?php esc_attr_e( 'Schließen', 'wp-plugin-name' ); ?>"
				        onclick="this.parentElement.style.display='none'">
					<span aria-hidden="true">&times;</span>
				</button>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Gibt das passende Icon für den Stil zurück.
	 *
	 * @param string $style Der Box-Stil.
	 * @return string Das Icon als HTML.
	 */
	private function get_icon( string $style ): string {
		$icons = array(
			'info'    => '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm1 15H9v-6h2v6zm0-8H9V5h2v2z"/></svg>',
			'warning' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M10 0L0 17.32h20L10 0zm1 14.5H9v-2h2v2zm0-3.5H9v-5h2v5z"/></svg>',
			'success' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm-1 14.414L4.586 10 6 8.586l3 3 6-6L16.414 7 9 14.414z"/></svg>',
			'error'   => '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm5 13.59L13.59 15 10 11.41 6.41 15 5 13.59 8.59 10 5 6.41 6.41 5 10 8.59 13.59 5 15 6.41 11.41 10 15 13.59z"/></svg>',
			'default' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><circle cx="10" cy="10" r="8"/></svg>',
		);

		return $icons[ $style ] ?? $icons['default'];
	}

	/**
	 * Erlaubte HTML-Tags für Icons.
	 *
	 * @return array Erlaubte Tags und Attribute.
	 */
	private function get_allowed_icon_html(): array {
		return array(
			'svg'    => array(
				'width'   => array(),
				'height'  => array(),
				'viewbox' => array(),
				'fill'    => array(),
			),
			'path'   => array(
				'd' => array(),
			),
			'circle' => array(
				'cx' => array(),
				'cy' => array(),
				'r'  => array(),
			),
		);
	}
}
