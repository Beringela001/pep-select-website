( function () {
	'use strict';

	/**
	 * Start direct homepage visits and reloads at the global header.
	 *
	 * Chrome restores the previous scroll offset on reload after the page's
	 * scripts begin running. Reset again on pageshow so an old header-height
	 * offset cannot strand the visitor below navigation. Preserve explicit
	 * fragment links and Back/Forward history restoration.
	 */
	const navigationEntry = window.performance && 'function' === typeof window.performance.getEntriesByType
		? window.performance.getEntriesByType( 'navigation' )[ 0 ]
		: null;
	const isHistoryTraversal = navigationEntry && 'back_forward' === navigationEntry.type;
	const shouldResetInitialScroll = ! window.location.hash && ! isHistoryTraversal;

	if ( shouldResetInitialScroll ) {
		const resetInitialScroll = () => window.scrollTo( 0, 0 );

		resetInitialScroll();
		window.addEventListener( 'pageshow', ( event ) => {
			if ( event.persisted ) {
				return;
			}

			resetInitialScroll();
			window.requestAnimationFrame( () => window.requestAnimationFrame( resetInitialScroll ) );
		}, { once: true } );
	}

	const accordion = document.querySelector( '[data-pepselect-faq]' );

	if ( ! accordion ) {
		return;
	}

	const buttons = Array.from( accordion.querySelectorAll( 'button[aria-controls]' ) );

	const setExpanded = ( button, expanded ) => {
		const panelId = button.getAttribute( 'aria-controls' );
		const panel = panelId ? document.getElementById( panelId ) : null;

		button.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );

		if ( panel ) {
			panel.hidden = ! expanded;
		}
	};

	buttons.forEach( ( button, index ) => {
		button.addEventListener( 'click', () => {
			const willExpand = 'true' !== button.getAttribute( 'aria-expanded' );

			buttons.forEach( ( candidate ) => setExpanded( candidate, false ) );
			setExpanded( button, willExpand );
		} );

		button.addEventListener( 'keydown', ( event ) => {
			let targetIndex = null;

			if ( 'ArrowDown' === event.key ) {
				targetIndex = ( index + 1 ) % buttons.length;
			} else if ( 'ArrowUp' === event.key ) {
				targetIndex = ( index - 1 + buttons.length ) % buttons.length;
			} else if ( 'Home' === event.key ) {
				targetIndex = 0;
			} else if ( 'End' === event.key ) {
				targetIndex = buttons.length - 1;
			}

			if ( null !== targetIndex ) {
				event.preventDefault();
				buttons[ targetIndex ].focus();
			}
		} );
	} );
}() );
