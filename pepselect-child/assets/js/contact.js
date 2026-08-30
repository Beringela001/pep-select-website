/**
 * Set the contact-form start time in the browser so full-page caching cannot
 * weaken the server-side minimum-fill guard.
 */
( function () {
	'use strict';

	const setStartedAt = function () {
		const startedAt = document.querySelector( '[name="pepselect_contact_started_at"]' );

		if ( startedAt ) {
			startedAt.value = String( Math.floor( Date.now() / 1000 ) );
		}
	};

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', setStartedAt, { once: true } );
	} else {
		setStartedAt();
	}
}() );
