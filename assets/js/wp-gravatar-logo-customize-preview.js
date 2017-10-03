/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	// Gravatar size.
	wp.customize( 'wp_gravatar_logo__width', function( value ) {
		value.bind( function( to ) {
			$( '.custom-logo-link--avatar img' ).css( 'width', to );
		});
	});

} )( jQuery );
