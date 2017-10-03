/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	// Site avatar.

	wp.customize( 'wp_avatar_logo__width', function( value ) {
		value.bind( function( to ) {
			$( '.custom-logo-link--avatar img' ).css( 'width', to );
		});
	});

} )( jQuery );
