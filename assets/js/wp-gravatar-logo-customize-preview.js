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

	wp.customize( 'wp_gravatar_logo__active', function( value ) {
		value.bind( function( to ) {

			if ( true === to ) {

				$( '.custom-logo-link--avatar' ).css({
					clip: 'auto',
					position: 'relative'
				});

				$( '.custom-logo-link--original' ).css({
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute'
				});

				$( 'body' ).addClass( 'gravatar--active' );

			} else {

				$( '.custom-logo-link--avatar' ).css({
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute'
				});

				$( '.custom-logo-link--original' ).css({
					clip: 'auto',
					position: 'relative'
				});

				$( 'body' ).removeClass( 'gravatar--active' );
			}
		});
	});

} )( jQuery );
