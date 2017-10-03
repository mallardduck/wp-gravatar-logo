/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	wp.customize( 'blogname', function( setting ) {
		setting.bind( function( value ) {
			var code = 'long_title';
			if ( value.length > 20 ) {
				setting.notifications.add( code, new wp.customize.Notification(
					code, {
						type: 'warning',
						message: wpgravatarlogoText.quote
					}
				) );
			} else {
				setting.notifications.remove( code );
			}
		} );
	} );

} )( jQuery );
