<?php
/**
 * Layout Customizer Control
 *
 * @see ttps://developer.wordpress.org/reference/classes/wp_customize_control/
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

// Exit if WP_Customize_Control does not exsist.
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * This class is for the template selector in the Customizer.
 *
 * @access  public
 */
class WP_Gravatar_Logo_Range_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'wp-gravatar-logo-range';

	/**
	 * Enqueue neccessary custom control stylesheet.
	 */
	public function enqueue() {

		// Define where the control's scripts are.
		$js_dir = WP_GRAVATAR_LOGO_PLUGIN_URL . 'assets/js/';
		$css_dir = WP_GRAVATAR_LOGO_PLUGIN_URL . 'assets/css/';

		// Use minified libraries if SCRIPT_DEBUG is turned off.
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Custom control styles.
		wp_enqueue_style( 'wp-gravatar-logo-range-control', $css_dir . 'wp-gravatar-logo-range-control' . $suffix . '.css', WP_GRAVATAR_LOGO_VERSION, 'all' );

		// Custom control scripts.
		wp_enqueue_script( 'wp-gravatar-logo-range-control', $js_dir . 'wp-gravatar-logo-range-control' . $suffix . '.js', array( 'jquery' ), WP_GRAVATAR_LOGO_VERSION, 'all' );

	}

	/**
	 * Render the content.
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_customize_control/render_content/
	 */
	public function render_content() {

		if ( ! empty( $this->label ) ) : ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			</label>
		<?php endif; ?>

		<div class="value">
			<span><?php echo esc_attr( $this->value() ); ?></span>
			<input class="track-input" data-default-value="<?php echo esc_html( $this->default ); ?>" type="number"<?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			<em><?php echo esc_html( $this->description ); ?></em>
		</div>

		<input class="track" data-default-value="<?php echo esc_html( $this->default ); ?>" data-input-type="range" type="range"<?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		
		<a type="button" value="reset" class="range-reset"></a>
		
		<?php
	}
}
