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

	// The two affirmative choices can be combined. The opt-out choice is
	// exclusive, while every checkbox remains unselected on initial load.
	document.addEventListener( 'change', function ( event ) {
		var changed = event.target.closest ? event.target.closest( '.pepselect-sms__checkbox' ) : null;

		if ( ! changed || ! changed.checked ) {
			return;
		}

		var form = changed.closest( '.pepselect-sms__form' );

		if ( ! form ) {
			return;
		}

		if ( 'none' === changed.value ) {
			form.querySelectorAll( '.pepselect-sms__checkbox:not([value="none"])' ).forEach( function ( checkbox ) {
				checkbox.checked = false;
			} );
			return;
		}

		var optOut = form.querySelector( '.pepselect-sms__checkbox[value="none"]' );

		if ( optOut ) {
			optOut.checked = false;
		}
	} );
}() );
