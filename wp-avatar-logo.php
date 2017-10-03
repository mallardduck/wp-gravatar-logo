<?php
/**
 * Plugin Name: WP Avatar Logo
 * Plugin URI: https://themebeans.com
 * Description:
 * Author: ThemeBeans
 * Author URI: https://themebeans.com
 * Version: 1.0.0
 * Text Domain: wp-avatar-logo
 * Domain Path: languages
 *
 * WP Avatar Logo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Avatar Logo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Avatar Logo. If not, see <http://www.gnu.org/licenses/>.
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

if ( ! class_exists( 'WP_Avatar_Logo' ) ) :

	/**
	 * Main WP_Avatar_Logo Class.
	 *
	 * @since 1.4
	 */
	final class WP_Avatar_Logo {
		/** Singleton *************************************************************/

		/**
		 * WP_Avatar_Logo The one true WP_Avatar_Logo
		 *
		 * @var string $instance
		 */
		private static $instance;

		/**
		 * Main WP_Avatar_Logo Instance.
		 *
		 * Insures that only one instance of WP_Avatar_Logo exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @static
		 * @uses WP_Avatar_Logo::setup_constants() Setup the constants needed.
		 * @uses WP_Avatar_Logo::includes() Include the required files.
		 * @uses WP_Avatar_Logo::load_textdomain() load the language files.
		 * @see  WP_Avatar_Logo()
		 * @return object|WP_Avatar_Logo The one true WP_Avatar_Logo
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Avatar_Logo ) ) {
				self::$instance = new WP_Avatar_Logo;
				self::$instance->constants();
				self::$instance->actions();
				self::$instance->filters();
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '@@textdomain' ), '1.6' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '@@textdomain' ), '1.6' );
		}

		/**
		 * Setup plugin constants.
		 *
		 * @access private
		 * @return void
		 */
		private function constants() {
			$this->define( 'WP_AVATAR_LOGO_VERSION', '@@pkg.version' );
			$this->define( 'WP_AVATAR_LOGO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'WP_AVATAR_LOGO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'WP_AVATAR_LOGO_PLUGIN_FILE', __FILE__ );
			$this->define( 'WP_AVATAR_LOGO_ABSPATH', dirname( __FILE__ ) . '/' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string      $name Name of the definition.
		 * @param  string|bool $value Default value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Load the actions.
		 *
		 * @access public
		 * @return void
		 */
		public function actions() {
			add_action( 'customize_register', array( $this, 'load_customizer_controls' ), 11 );
		}

		/**
		 * Load the filters
		 *
		 * @return void
		 */
		public function filters() {
			add_filter( 'get_custom_logo', array( $this, 'avatar' ) );
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @return void
		 */
		private function includes() {
			require_once WP_AVATAR_LOGO_PLUGIN_DIR . 'includes/class-wp-avatar-logo-scripts.php';
			require_once WP_AVATAR_LOGO_PLUGIN_DIR . 'includes/customizer.php';
		}

		/**
		 * Register Customizer Controls.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function load_customizer_controls() {
			require_once WP_AVATAR_LOGO_PLUGIN_DIR . 'includes/class-wp-avatar-logo-range-control.php';
		}

		/**
		 * Output an <img> tag of the site logo.
		 */
		public function avatar() {

			// If we're not on 3.5 yet, exit now.
			if ( ! function_exists( 'the_custom_logo' ) ) {
				return;
			}

			/**
			 * Retreive the avatar size, which is two times what's set in the Customizer (for retina).
			 */
			$avatar_width = get_theme_mod( 'custom_logo_max_width', '50' );

			/**
			 * Filter the author avatar. Defaults to the admin's email address.
			 */
			$avatar = apply_filters( 'wp_avatar_logo_emailaddress', get_bloginfo( 'admin_email' ) );

			$html = sprintf( '<a href="%1$s" class="custom-logo-link custom-logo-link--avatar" rel="home" itemprop="urls">%2$s</a>',
				esc_url( home_url( '/' ) ),
				get_avatar( $avatar, $avatar_width * 2 )
			);

			return $html;
		}
	}

endif; // End if class_exists check.

/**
 * The main function for that returns WP_Avatar_Logo
 *
 * The main function responsible for returning the one true WP_Avatar_Logo
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wp_avatar_logo = wp_avatar_logo(); ?>
 *
 * @since 1.4
 * @return object|WP_Avatar_Logo The one true WP_Avatar_Logo Instance.
 */
function wp_avatar_logo() {
	return WP_Avatar_Logo::instance();
}

// Get WP_Avatar_Logo Running.
wp_avatar_logo();
