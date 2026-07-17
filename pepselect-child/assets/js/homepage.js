( function () {
	'use strict';

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
