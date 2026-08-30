( function () {
	'use strict';

	const root = document.querySelector( '.pepselect-login' );

	if ( ! root ) {
		return;
	}

	const tabs = Array.from( root.querySelectorAll( '[role="tab"]' ) );
	const panels = Array.from( root.querySelectorAll( '[data-account-panel]' ) );
	const title = root.querySelector( '.pepselect-login__title' );
	const lead = root.querySelector( '.pepselect-login__lead' );

	function activateTab( name, moveFocus ) {
		tabs.forEach( function ( tab ) {
			const selected = tab.getAttribute( 'aria-controls' ) === 'pepselect-' + name + '-panel';
			tab.setAttribute( 'aria-selected', selected ? 'true' : 'false' );
			tab.setAttribute( 'tabindex', selected ? '0' : '-1' );

			if ( selected && moveFocus ) {
				tab.focus();
			}
		} );

		panels.forEach( function ( panel ) {
			panel.hidden = panel.dataset.accountPanel !== name;
		} );

		[ title, lead ].forEach( function ( element ) {
			if ( element && element.dataset[ name + 'Text' ] ) {
				element.textContent = element.dataset[ name + 'Text' ];
			}
		} );
	}

	if ( tabs.length && panels.length ) {
		root.classList.add( 'pepselect-login--enhanced' );

		tabs.forEach( function ( tab ) {
			tab.addEventListener( 'click', function () {
				activateTab( tab.id === 'pepselect-register-tab' ? 'register' : 'login', false );
			} );

			tab.addEventListener( 'keydown', function ( event ) {
				if ( event.key !== 'ArrowLeft' && event.key !== 'ArrowRight' ) {
					return;
				}

				event.preventDefault();
				activateTab( tab.id === 'pepselect-register-tab' ? 'login' : 'register', true );
			} );
		} );

		activateTab( root.dataset.initialTab === 'register' ? 'register' : 'login', false );
	}

	/* Give Google's responsive sign-in screen enough room on desktop while
	 * keeping the popup inside the available display area. Nextend reads these
	 * attributes at click time and continues to own the OAuth flow. */
	const popupWidth = Math.max( 520, Math.min( 820, window.screen.availWidth - 48 ) );
	const popupHeight = Math.max( 600, Math.min( 720, window.screen.availHeight - 72 ) );

	root.querySelectorAll( 'a[data-plugin="nsl"][data-provider="google"]' ).forEach( function ( link ) {
		link.dataset.popupwidth = String( popupWidth );
		link.dataset.popupheight = String( popupHeight );
	} );
}() );
