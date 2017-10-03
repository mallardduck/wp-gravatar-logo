<?php
/**
 * Enqueue scripts and styles.
 *
 * @package   @@pkg.name
 * @author    @@pkg.author
 * @license   @@pkg.license
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Gravatar_Logo_Scripts' ) ) :

	/**
	 * Enqueues JS & CSS assets
	 */
	class WP_Gravatar_Logo_Scripts {

		/**
		 * Translated strings
		 *
		 * @var array
		 */
		public $l10n = array();

		/**
		 * The class constructor.
		 * Adds actions to enqueue our assets.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles' ) );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );

			$this->l10n = array(
				'quote' => __( 'You must supply a title/label.', '@@textdomain' ),
			);
		}

		/**
		 * Enqueue the stylesheets required.
		 *
		 * @access public
		 */
		public function frontend_styles() {

			// Define where the control's scripts are.
			$css_dir = WP_GRAVATAR_LOGO_PLUGIN_URL . 'assets/css/';

			// Use minified libraries if SCRIPT_DEBUG is turned off.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style( 'wp-gravatar-logo-frontend', $css_dir . 'wp-gravatar-logo-frontend' . $suffix . '.css', null );
		}

		/**
		 * Enqueues scripts in the Customizer.
		 */
		public function customize_preview_init() {

			// Define where the scripts are.
			$js_dir  = WP_GRAVATAR_LOGO_PLUGIN_URL . 'assets/js/';

			// Use minified libraries if SCRIPT_DEBUG is turned off.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_script( 'wp-gravatar-logo-customize-preview', $js_dir . 'wp-gravatar-logo-customize-preview' . $suffix . '.js', array( 'customize-preview' ), WP_GRAVATAR_LOGO_VERSION, true );
		}

		/**
		 * Enqueues scripts in the Customizer.
		 */
		public function customize_controls_enqueue_scripts() {

			// Define where the scripts are.
			$js_dir  = WP_GRAVATAR_LOGO_PLUGIN_URL . 'assets/js/';

			// Use minified libraries if SCRIPT_DEBUG is turned off.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_script( 'wp-gravatar-logo-customize-controls', $js_dir . 'wp-gravatar-logo-customize-controls' . $suffix . '.js', array( 'customize-controls' ), WP_GRAVATAR_LOGO_VERSION, true );

			wp_localize_script( 'wp-gravatar-logo-customize-controls', 'wpgravatarlogoText', $this->l10n );
		}
	}

endif;

new WP_Gravatar_Logo_Scripts();
