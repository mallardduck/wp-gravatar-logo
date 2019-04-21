<?php
/**
 * Customizer functionality
 *
 * @package   WP_Gravatar_Logo
 * @author    Rich Tabor of ThemeBeans <hello@themebeans.com>
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @version   1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Gravatar_Logo_Customizer' ) ) :

	/**
	 * Enqueues JS & CSS assets
	 */
	class WP_Gravatar_Logo_Customizer {

		/**
		 * The class constructor.
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ), 11 );
			add_action( 'wp_enqueue_scripts', array( $this, 'css' ) );
			add_filter( 'body_class', array( $this, 'body_classes' ) );
		}

		/**
		 * Register Customizer Settings.
		 *
		 * @param WP_Customize_Manager $wp_customize the Customizer object.
		 */
		function customize_register( $wp_customize ) {

			/**
			 * Add custom controls.
			 */
			require_once WP_GRAVATAR_LOGO_PLUGIN_DIR . 'includes/class-wp-gravatar-logo-range-control.php';

			/**
			 * Add the avatar email address option to the Site Identity section.
			 */
			$wp_customize->add_setting( 'wp_gravatar_logo__email', array(
				'default'               => get_bloginfo( 'admin_email' ),
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'sanitize_email',
			) );

			$wp_customize->add_control( 'wp_gravatar_logo__email', array(
				'type'                  => 'email',
				'label'                 => esc_html__( 'Gravatar Email Address', '@@textdomain' ),
				'section'               => 'title_tagline',
				'priority'              => 9,
			) );

			$wp_customize->selective_refresh->add_partial( 'wp_gravatar_logo__email', array(
				'settings'            	=> 'wp_gravatar_logo__email',
				'selector'		=> '.custom-logo-link--avatar',
				'render_callback' 	=> function() { return WP_Gravatar_Logo()->get_gravatar(); },
			) );

			$wp_customize->add_setting( 'wp_gravatar_logo__active', array(
				'default'               => true,
				'transport'             => 'postMessage',
				'sanitize_callback'     => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( 'wp_gravatar_logo__active', array(
				'type'                  => 'checkbox',
				'label'                 => esc_html__( 'Display Gravatar', '@@textdomain' ),
				'description'           => '',
				'section'               => 'title_tagline',
				'priority'              => 9,
			) );

			/**
			 * Add the avatar width option to the Site Identity section.
			 */
			$wp_customize->add_setting( 'wp_gravatar_logo__width', array(
				'default'               => '50',
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'absint',
			) );

			$wp_customize->add_control( new WP_Gravatar_Logo_Range_Control( $wp_customize, 'wp_gravatar_logo__width', array(
				'default'               => '50',
				'type'                  => 'wp-gravatar-logo-range',
				'label'                 => esc_html__( 'Gravatar Width', '@@textdomain' ),
				'description'           => 'px',
				'section'               => 'title_tagline',
				'priority'              => 9,
				'input_attrs'           => array(
					'min'               => 0,
					'max'               => 200,
					'step'              => 2,
				),
			) ) );

		}

		/**
		 * Sanitize Checkbox.
		 *
		 * @param string|bool $checked Customizer option.
		 */
		public function sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true == $checked ) ? true : false );
		}

		/**
		 * Custom CSS.
		 */
		public function css() {

			$gravatar_width = get_theme_mod( 'wp_gravatar_logo__width', '50' );

			$css =
			'
			body .custom-logo-link.custom-logo-link--avatar img {
				width: ' . esc_attr( $gravatar_width ) . 'px;
			}
			';

			wp_add_inline_style( 'wp-gravatar-logo-frontend', wp_strip_all_tags( $css ) );
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 */
		public function body_classes( $classes ) {

			if ( is_customize_preview() || get_theme_mod( 'wp_gravatar_logo__active', true ) ) {
				$classes[] = 'gravatar--active';
			}

			return $classes;
		}
	}

endif;

new WP_Gravatar_Logo_Customizer();
