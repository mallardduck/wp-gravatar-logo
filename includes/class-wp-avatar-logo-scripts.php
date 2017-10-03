<?php
/**
 * Enqueue scripts and styles.
 *
 * @package   @@pkg.name
 * @copyright @@pkg.copyright
 * @author    @@pkg.author
 * @license   @@pkg.license
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Avatar_Logo_Scripts' ) ) :

	/**
	 * Enqueues JS & CSS assets
	 */
	class WP_Avatar_Logo_Scripts {

		/**
		 * The class constructor.
		 * Adds actions to enqueue our assets.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles' ) );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
		}

		/**
		 * Enqueue the stylesheets required.
		 *
		 * @access public
		 */
		public function frontend_styles() {

			// Define where the control's scripts are.
			$css_dir = WP_AVATAR_LOGO_PLUGIN_URL . 'assets/css/';

			// Use minified libraries if SCRIPT_DEBUG is turned off.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style( 'wp-avatar-logo-frontend', $css_dir . 'wp-avatar-logo-frontend' . $suffix . '.css', null );
		}

		/**
		 * Enqueues scripts in the Customizer.
		 */
		public function customize_preview_init() {

			// Define where the scripts are.
			$js_dir  = WP_AVATAR_LOGO_PLUGIN_URL . 'assets/js/';

			// Use minified libraries if SCRIPT_DEBUG is turned off.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_script( 'wp-avatar-logo-customize-preview', $js_dir . 'wp-avatar-logo-customize-preview' . $suffix . '.js', array( 'customize-preview' ), WP_AVATAR_LOGO_VERSION, true );
		}
	}

endif;

new WP_Avatar_Logo_Scripts();
