<?php
/**
 *
 */

namespace Freeshifter\SSC;

class SSC_Admin {

	/**
	 * The plugin text domain.
	 *
	 * @var string
	 */
	public static $text_domain = 'simple-stripe-checkout';

	/**
	 * The plugin instance.
	 *
	 * @var SSC_Admin|void
	 */
	private static $instance;

	/**
	 * The saved plugin options.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * The filterable plugin arguments.
	 *
	 * @var array
	 */
	public $args;

	/**
	 * SSC_Admin constructor.
	 * Only one instance allowed.
	 */
	private function __construct() {

		$this->args = [
			'page-slug'                        => 'ssc-settings',
			'options_key_live_stripe'          => 'stripe_public_key_live',
			'options_key_test_stripe'          => 'stripe_public_key_test',
			'options_section_stripe_keys_slug' => 'ssc-admin-settings'
		];

		$this->options = get_option( self::get_section_stripe_keys_slug() );

		add_action( 'admin_menu', [ $this, 'setup_page' ] );
		add_action( 'admin_init', [ $this, 'setup_settings'] );

	}

	/**
	 * @return SSC_Admin|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Setup the options page.
	 */
	public function setup_page() {
		add_options_page(
			__( 'Simple Stripe Checkout', self::$text_domain ),
			__( 'Simple Stripe Checkout', self::$text_domain ),
			'manage_options',
			'ssc-settings',
			[ $this, 'output_page' ]
		);
	}

	/**
	 * Output the options page markup
	 */
	public function output_page() {
		?>
		<div class="wrap">
			<h1>Simple Stripe Checkout</h1>
			<form method="post" action="<?= admin_url( 'options.php' ); ?>">
				<?php settings_fields( 'ssc-settings' );
				do_settings_sections( 'ssc-settings' );
				submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Setup the settings fields and sections for the Admin page.
	 */
	public function setup_settings() {
		add_settings_section(
			self::get_section_stripe_keys_slug(),
			'Plugin Settings',
			[$this, 'section_callback'],
			'ssc-settings'
		);

		add_settings_field(
			self::get_option_key_live_api_key(),
			'Live Public API Key',
			[$this, 'live_stripe_callback'],
			'ssc-settings',
			self::get_section_stripe_keys_slug(),
			[ 'label_for' => self::get_option_key_live_api_key() ]
		);

		add_settings_field(
			self::get_option_key_test_api_key(),
			'Test Public API Key',
			[$this, 'test_stripe_callback'],
			'ssc-settings',
			self::get_section_stripe_keys_slug(),
			[ 'label_for' => self::get_option_key_test_api_key() ]
		);

		register_setting( self::get_settings_page_slug(), self::get_section_stripe_keys_slug() );
	}

	/**
	 * Live Stripe API Key input.
	 */
	public function live_stripe_callback() {

		$value  = $this->options[ self::get_option_key_live_api_key() ] ?? '';
		$input  = sprintf( '<input name="%1$s[%2$s]" id="%2$s" type="text" value="%3$s" %4$s />',
			self::get_section_stripe_keys_slug(),
			self::get_option_key_live_api_key(),
			defined( 'SSC_STRIPE_LIVE_PUBLIC_API_KEY' ) ? esc_attr( SSC_STRIPE_LIVE_PUBLIC_API_KEY ) : esc_attr( $value ),
			defined( 'SSC_STRIPE_LIVE_PUBLIC_API_KEY' ) ? 'disabled' : ''
		);

		if ( defined( 'SSC_STRIPE_LIVE_PUBLIC_API_KEY' ) ) {
			$input .= '<p><small>This setting has been defined manually and cannot be changed from here. <br/>Please contact your webmaster if you have questions.</small></p>';
		}

		echo $input;

	}

	/**
	 * Test Stripe API Key input.
	 */
	public function test_stripe_callback() {

		$value  = $this->options[ self::get_option_key_test_api_key() ] ?? '';
		$input  = sprintf( '<input name="%1$s[%2$s]" id="%2$s" type="text" value="%3$s" %4$s />',
			self::get_section_stripe_keys_slug(),
			self::get_option_key_test_api_key(),
			defined( 'SSC_STRIPE_TEST_PUBLIC_API_KEY' ) ? esc_attr( SSC_STRIPE_TEST_PUBLIC_API_KEY ) : esc_attr( $value ),
			defined( 'SSC_STRIPE_TEST_PUBLIC_API_KEY' ) ? 'disabled' : ''
		);

		if ( defined( 'SSC_STRIPE_TEST_PUBLIC_API_KEY' ) ) {
			$input .= '<p><small>This setting has been defined manually and cannot be changed from here. <br/>Please contact your webmaster if you have questions.</small></p>';
		}

		echo $input;

	}

	/**
	 * The section markup for the settings page.
	 */
	public function section_callback() {
		?>
		<p>Your public API keys are found in your <a href="https://dashboard.stripe.com/account/apikeys" target="_blank">Stripe dashboard</a>.</p>
		<p>For better security, <a href="/">define your public keys</a> in your theme or a plugin so that we don't store them in the database.</p>
		<?php
	}

	/**
	 * Get saved Live API key name for options.
	 *
	 * @return bool|string
	 */
	public function get_option_key_live_api_key() {
		$val = $this->args['options_key_live_stripe'] ?? '';
		return (string) $val;
	}

	/**
	 * Get saved Test API key name for options.
	 *
	 * @return bool|string
	 */
	public function get_option_key_test_api_key() {
		$val = $this->args['options_key_test_stripe'] ?? '';
		return (string) $val;
	}

	/**
	 * Get the settings field key.
	 *
	 * @return bool|string
	 */
	public function get_section_stripe_keys_slug() {
		$val = $this->args['options_section_stripe_keys_slug'] ?? '';
		return (string) $val;
	}

	/**
	 * Get the settings page slug.
	 *
	 * @return bool|string
	 */
	public function get_settings_page_slug() {
		$val = $this->args['page-slug'] ?? '';
		return (string) $val;
	}
}

$scc_admin = SSC_Admin::get_instance();