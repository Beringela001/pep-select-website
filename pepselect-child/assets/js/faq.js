( function () {
	'use strict';

	const groups = Array.from( document.querySelectorAll( '[data-pepselect-faq-page]' ) );

	if ( ! groups.length ) {
		return;
	}

	const setExpanded = ( button, expanded ) => {
		const panelId = button.getAttribute( 'aria-controls' );
		const panel = panelId ? document.getElementById( panelId ) : null;

		button.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );

		if ( panel ) {
			panel.hidden = ! expanded;
		}
	};

	groups.forEach( ( group ) => {
		const buttons = Array.from( group.querySelectorAll( 'button[aria-controls]' ) );

		buttons.forEach( ( button, index ) => {
			// Each question toggles on its own. Opening one does not close the
			// others, so a reader can compare answers across a long page.
			button.addEventListener( 'click', () => {
				const willExpand = 'true' !== button.getAttribute( 'aria-expanded' );
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
	} );
}() );
