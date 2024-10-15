<?php
declare( strict_types = 1 );

namespace MyVendorNamespace\MyPluginNamespace\Admin;

use MyVendorNamespace\MyPluginNamespace\Traits\Form_Guard;
use MyVendorNamespace\MyPluginNamespace\Traits\Singleton_Guard;

defined( 'ABSPATH' ) || exit;

class Admin_Settings {

	use Form_Guard;
	use Singleton_Guard;

	private static Admin_Settings $instance;

	private string $plugin_prefix = 'wp_plugin_name_';

	private string $options_name = 'wp_plugin_name_settings';

	private string $form_action_name;

	private array $default_options = array(
		'test_text_feld_name'     => '',
		'test_checkbox_feld_name' => 0,
	);

	private function __construct() {
		$this->form_action_name = $this->plugin_prefix . 'post_settings_form';
		$this->init();
	}

	public static function get_instance(): Admin_Settings {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init(): void {
		add_action( 'admin_init', array( $this, 'set_default_settings' ) );
		add_action( 'admin_post_' . $this->plugin_prefix . 'post_settings_form', array( $this, 'post_settings_form' ) );
	}

	public function get_option_settings(): mixed {
		return get_option( $this->options_name, array() );
	}

	public function save_option_settings( $data ): bool {
		return update_option( $this->options_name, $data );
	}

	public function set_default_settings(): void {
		$options = $this->get_option_settings();

		if ( empty( $options ) ) {
			$defaults = $this->default_options;
			$options  = array_merge( $defaults, $options );
			$this->save_option_settings( $options );
		}
	}

	/**
	 * Handles the submission of the settings form.
	 *
	 * This method processes and validates the form submission,
	 * updates the specified options, saves them,
	 * and redirects to the settings page with an appropriate notice.
	 *
	 * @return void
	 */
	public function post_settings_form(): void {
		status_header( 200 );

		// Verwende die Methode aus dem Trait für die Action-Überprüfung.
		if ( ! $this->is_form_action_valid() ) {
			return;
		}

		// Sicherheitsüberprüfung mit wp_die bei Fehler.
		if ( ! $this->is_security_check_valid() ) {
			wp_die( 'Sicherheitsprüfung fehlgeschlagen.', 'wp-plugin-name' );
		}

		$options = $this->get_option_settings();

		// Sanitize und verarbeite die übergebenen Formulardaten.
		$test_checkbox_feld_name = isset( $_POST['test_checkbox_feld_name'] ) ? 1 : 0;
		$test_text_feld_name     = isset( $_POST['test_text_feld_name'] ) ? sanitize_text_field( wp_unslash( $_POST['test_text_feld_name'] ) ) : '';

		$options['test_checkbox_feld_name'] = $test_checkbox_feld_name;
		$options['test_text_feld_name']     = $test_text_feld_name;

		// Speichere die Optionen und zeige eine Erfolgsmeldung an.
		if ( $this->save_option_settings( $options ) ) {
			Admin_Notice::add_notice( esc_html__( 'Einstellungen wurden gespeichert.', 'wp-plugin-name' ), 'success' );
		}

		// Nach erfolgreichem Speichern wird der Benutzer weitergeleitet.
		wp_safe_redirect( admin_url( 'admin.php?page=wp-plugin-name-settings' ) );
		exit();
	}


	public function display_settings_page(): void {
		$options = $this->get_option_settings();
		Admin_Notice::display_notices();
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action"
						value="<?php echo esc_attr( $this->plugin_prefix . 'post_settings_form' ); ?>">


				<h2><?php echo esc_html__( 'Beispiel #1', 'wp-plugin-name' ); ?></h2>
				<table class="form-table" role="presentation">

					<tbody>

					<tr>
						<th scope="row"><label
									for="test_text_feld_name"><?php esc_html_e( 'Beispiel Textfeld', 'wp-plugin-name' ); ?></label>
						</th>
						<td>
							<input name="test_text_feld_name" type="text" id="test_text_feld_name"
									value="<?php echo esc_attr( $options['test_text_feld_name'] ); ?>"
									class="regular-text">
							<p class="description"><?php esc_html_e( 'Hier kann eine kleine Beschreibung stehen.', 'wp-plugin-name' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="test_checkbox_feld_name"><?php esc_html_e( 'Beispiel Checkbox', 'wp-plugin-name' ); ?></label>
						</th>
						<td>
							<input <?php checked( $options['test_checkbox_feld_name'], 1 ); ?>
									name="test_checkbox_feld_name"
									type="checkbox"
									id="test_checkbox_feld_name"
									value="1">
							<p class="description"><?php esc_html_e( 'Eine kleine Beschreibung.', 'wp-plugin-name' ); ?></p>
						</td>
					</tr>

					</tbody>
				</table>

				<?php
				wp_nonce_field( $this->plugin_prefix . 'post_settings_form' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
