<?php
/**
 * Plugin Name: WP Gravatar Logo
 * Plugin URI: https://themebeans.com/plugins/wp-gravatar-logo
 * Description: @@pkg.description
 * Author: @@pkg.author
 * Author URI: https://richtabor.com
 * Version: @@pkg.version
 * Text Domain: @@pkg.textdomain
 * Domain Path: languages
 * Requires at least: 4.0
 * Tested up to: 4.8.2
 *
 * WP Gravatar Logo is free software: you can redistribute it and/or modify
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
 * @author    @@pkg.author
 * @license   @@pkg.license
 * @version   @@pkg.version
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Gravatar_Logo' ) ) :

	/**
	 * Main WP_Gravatar_Logo Class.
	 *
	 * @since 1.0
	 */
	final class WP_Gravatar_Logo {
		/** Singleton *************************************************************/

		/**
		 * WP_Gravatar_Logo The one true WP_Gravatar_Logo
		 *
		 * @var string $instance
		 */
		private static $instance;

		/**
		 * Main WP_Gravatar_Logo Instance.
		 *
		 * Insures that only one instance of WP_Gravatar_Logo exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @static
		 * @uses WP_Gravatar_Logo::setup_constants() Setup the constants needed.
		 * @uses WP_Gravatar_Logo::includes() Include the required files.
		 * @uses WP_Gravatar_Logo::load_textdomain() load the language files.
		 * @see  WP_Gravatar_Logo()
		 * @return object|WP_Gravatar_Logo The one true WP_Gravatar_Logo
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Gravatar_Logo ) ) {
				self::$instance = new WP_Gravatar_Logo;
				self::$instance->constants();
				self::$instance->filters();
				self::$instance->includes();
				self::$instance->load_textdomain();
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
			$this->define( 'WP_GRAVATAR_LOGO_VERSION', '@@pkg.version' );
			$this->define( 'WP_GRAVATAR_LOGO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'WP_GRAVATAR_LOGO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'WP_GRAVATAR_LOGO_PLUGIN_FILE', __FILE__ );
			$this->define( 'WP_GRAVATAR_LOGO_ABSPATH', dirname( __FILE__ ) . '/' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string|string $name Name of the definition.
		 * @param  string|bool   $value Default value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Load the filters
		 *
		 * @return void
		 */
		public function filters() {
			add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

			if ( true === get_theme_mod( 'wp_gravatar_logo__active', true ) || is_customize_preview() ) {
				add_filter( 'get_custom_logo', array( $this, 'get_gravatar' ) );
			}
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @return void
		 */
		private function includes() {
			require_once WP_GRAVATAR_LOGO_PLUGIN_DIR . 'includes/class-wp-gravatar-logo-scripts.php';
			require_once WP_GRAVATAR_LOGO_PLUGIN_DIR . 'includes/class-wp-gravatar-logo-customizer.php';
		}

		/**
		 * Output an <img> tag of the site logo.
		 */
		public function get_gravatar() {

			/**
			 * Retreive the avatar size, which is two times what's set in the Customizer (for retina).
			 */
			$avatar_width = get_theme_mod( 'wp_gravatar_logo__width', '50' );

			/**
			 * Filter the author avatar. Defaults to the admin's email address.
			 */
			$avatar = get_theme_mod( 'wp_gravatar_logo__email', get_bloginfo( 'admin_email' ) );

			$html = sprintf( '<a href="%1$s" class="custom-logo-link custom-logo-link--avatar" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				get_avatar( $avatar, $avatar_width * 2 )
			);

			$custom_logo = '';

			if ( is_customize_preview() ) {

				$custom_logo_id = get_theme_mod( 'custom_logo' );

				$custom_logo = sprintf( '<a href="%1$s" class="custom-logo-link custom-logo-link--original" rel="home" itemprop="url">%2$s</a>',
					esc_url( home_url( '/' ) ),
					wp_get_attachment_image( $custom_logo_id, 'full', false, array(
						'class'    => 'custom-logo',
					) )
				);
			}

			return $html . $custom_logo;
		}

		/**
		 * Plugins row action links.
		 *
		 * @param array|string  $links already defined action links.
		 * @param string|string $file plugin file path and name being processed.
		 * @return array $links
		 */
		function plugin_action_links( $links, $file ) {

			$settings_link = '<a href="' . esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ) . '">' . esc_html__( 'Customize', '@@textdomain' ) . '</a>';

			if ( 'wp-gravatar-logo/wp-gravatar-logo.php' == $file ) {
				array_unshift( $links, $settings_link );
			}

			return $links;
		}

		/**
		 * Load the translation files.
		 *
		 * @access public
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( '@@textdomain', false, dirname( plugin_basename( WP_GRAVATAR_LOGO_PLUGIN_DIR ) ) . '/languages/' );
		}
	}

endif;

/**
 * The main function for that returns WP_Gravatar_Logo
 *
 * The main function responsible for returning the one true WP_Gravatar_Logo
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wp_gravatar_logo = wp_gravatar_logo(); ?>
 *
 * @since 1.4
 * @return object|WP_Gravatar_Logo The one true WP_Gravatar_Logo Instance.
 */
function wp_gravatar_logo() {
	return WP_Gravatar_Logo::instance();
}

// Get WP_Gravatar_Logo Running.
wp_gravatar_logo();
