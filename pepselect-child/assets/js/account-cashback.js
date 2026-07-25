/**
 * Cash back page: copy-to-clipboard for the referral share link.
 *
 * The share link is rendered server-side (an .pepselect-copyfield with a
 * readonly input and a Copy button). This only wires the Copy button; it no
 * longer reads or restyles any YITH markup. A delegated click handler keeps it
 * working regardless of when the field is added to the page.
 */
( function () {
	'use strict';

	function flash( button ) {
		button.classList.add( 'is-copied' );
		button.textContent = 'Copied';

		window.setTimeout( function () {
			button.classList.remove( 'is-copied' );
			button.textContent = 'Copy';
		}, 1800 );
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest ? event.target.closest( '.pepselect-copyfield__copy' ) : null;

		if ( ! button ) {
			return;
		}

		var field = button.closest( '.pepselect-copyfield' );
		var input = field ? field.querySelector( '.pepselect-copyfield__input' ) : null;

		if ( ! input ) {
			return;
		}

		input.focus();
		input.select();

		if ( window.navigator.clipboard && window.navigator.clipboard.writeText ) {
			window.navigator.clipboard.writeText( input.value ).then(
				function () {
					flash( button );
				},
				function () {
					if ( document.execCommand ) {
						document.execCommand( 'copy' );
						flash( button );
					}
				}
			);
		} else if ( document.execCommand ) {
			document.execCommand( 'copy' );
			flash( button );
		}
	} );
}() );
