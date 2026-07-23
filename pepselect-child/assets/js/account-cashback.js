/**
 * Cash back page: surface YITH's referral code and share link.
 *
 * YITH renders the referral code/link inside its my-points output, which the
 * template places in the referral section. This reads what YITH rendered and
 * presents it as a copyable code field plus the full share link, in brand
 * styling. Nothing is generated here: if a code or link cannot be read from
 * YITH's markup, YITH's own UI is left exactly as rendered and visible.
 */
( function () {
	'use strict';

	var URL_RE = /https?:\/\/[^\s"'<>]+/i;

	function text( el ) {
		return ( el.textContent || '' ).trim();
	}

	/** Collect candidate strings from YITH's referral markup. */
	function harvest( root ) {
		var found = { code: '', link: '' };

		// Links first: an anchor href or any URL in an input value / text.
		var anchor = root.querySelector( 'a[href^="http"]' );
		if ( anchor ) {
			found.link = anchor.getAttribute( 'href' );
		}

		var inputs = root.querySelectorAll( 'input, textarea' );
		for ( var i = 0; i < inputs.length; i++ ) {
			var value = ( inputs[ i ].value || '' ).trim();

			if ( ! value ) {
				continue;
			}

			if ( ! found.link && URL_RE.test( value ) ) {
				found.link = value.match( URL_RE )[ 0 ];
				continue;
			}

			// A short token with no spaces reads as the referral code.
			if ( ! found.code && value.length <= 32 && ! /\s/.test( value ) && ! URL_RE.test( value ) ) {
				found.code = value;
			}
		}

		if ( ! found.link ) {
			var match = text( root ).match( URL_RE );
			if ( match ) {
				found.link = match[ 0 ];
			}
		}

		// Fall back to the code carried in the share link, as YITH rendered it.
		if ( ! found.code && found.link ) {
			try {
				var url = new window.URL( found.link, window.location.origin );
				var keys = [ 'ywpar_referral', 'referral', 'ref', 'code', 'coupon' ];

				for ( var k = 0; k < keys.length; k++ ) {
					var param = url.searchParams.get( keys[ k ] );
					if ( param ) {
						found.code = param;
						break;
					}
				}
			} catch ( e ) {
				/* Ignore malformed URLs and leave the code empty. */
			}
		}

		return found;
	}

	function copyField( labelText, value, id ) {
		var wrap = document.createElement( 'div' );
		wrap.className = 'pepselect-copyfield';

		var label = document.createElement( 'span' );
		label.className = 'pepselect-copyfield__label';
		label.textContent = labelText;

		var input = document.createElement( 'input' );
		input.className = 'pepselect-copyfield__input';
		input.type = 'text';
		input.readOnly = true;
		input.value = value;
		input.id = id;

		var button = document.createElement( 'button' );
		button.className = 'pepselect-copyfield__copy';
		button.type = 'button';
		button.textContent = 'Copy';

		button.addEventListener( 'click', function () {
			input.select();

			var done = function () {
				button.classList.add( 'is-copied' );
				button.textContent = 'Copied';
				window.setTimeout( function () {
					button.classList.remove( 'is-copied' );
					button.textContent = 'Copy';
				}, 1800 );
			};

			if ( window.navigator.clipboard && window.navigator.clipboard.writeText ) {
				window.navigator.clipboard.writeText( input.value ).then( done, function () {} );
			} else if ( document.execCommand ) {
				document.execCommand( 'copy' );
				done();
			}
		} );

		wrap.appendChild( label );
		wrap.appendChild( input );
		wrap.appendChild( button );

		return wrap;
	}

	function init() {
		// YITH's shortcode renders #ywpar_referral_link_sc (print_user_referral_field);
		// fall back to the section wrapper if that id changes.
		var root =
			document.querySelector( '#ywpar_referral_link_sc' ) ||
			document.querySelector( '[data-pepselect-referral]' );

		if ( ! root ) {
			return;
		}

		// Keep the styled wrapper as the hide target when we read the inner node.
		var hideTarget = root.closest( '[data-pepselect-referral]' ) || root;

		var found = harvest( root );

		if ( ! found.code && ! found.link ) {
			// Nothing readable: leave YITH's own referral UI untouched and visible.
			return;
		}

		var host = document.createElement( 'div' );
		host.className = 'pepselect-cashback__referral-fields';

		if ( found.code ) {
			host.appendChild( copyField( 'Your referral code', found.code, 'pepselect-referral-code' ) );
		}

		if ( found.link ) {
			host.appendChild( copyField( 'Your share link', found.link, 'pepselect-referral-link' ) );
		}

		hideTarget.parentNode.insertBefore( host, hideTarget );

		// YITH's raw block is now represented by the fields above, so collapse it
		// rather than showing the same code twice.
		hideTarget.hidden = true;
	}

	if ( 'loading' !== document.readyState ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
