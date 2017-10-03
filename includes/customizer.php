<?php
/**
 * Customizer functionality
 *
 * @package   @@pkg.name
 * @copyright @@pkg.copyright
 * @author    @@pkg.author
 * @license   @@pkg.license
 */

/**
 * Register Customizer Settings.
 *
 * @param WP_Customize_Manager $wp_customize the Customizer object.
 */
function wp_avatar_logo_customize_register( $wp_customize ) {

	/**
	 * Add the site logo max-width option to the Site Identity section.
	 */
	$wp_customize->add_setting( 'wp_avatar_logo__width', array(
		'default'               => '50',
		'transport'             => 'postMessage',
		'sanitize_callback'     => 'absint',
	) );

	$wp_customize->add_control( new WP_Avatar_Logo_Range_Control( $wp_customize, 'wp_avatar_logo__width', array(
		'default'               => '50',
		'type'                  => 'wp-avatar-logo-range',
		'label'                 => esc_html__( 'Avatar Width', '@@textdomain' ),
		'description'           => 'px',
		'section'               => 'title_tagline',
		'priority'              => 9,
		'input_attrs'           => array(
			'min'               => 0,
			'max'               => 300,
			'step'              => 2,
		),
	) ) );

}
add_action( 'customize_register', 'wp_avatar_logo_customize_register', 11 );
