/**
 * Enlarged back-in-stock dialog on compound cards.
 *
 * The notify action opens a centered dialog (native focus trap, Escape to
 * dismiss). Clicking the dimmed backdrop or the close control dismisses.
 * Without a dialog (plugin shortcode absent) or without native dialog
 * support, the link falls through to the product page unchanged.
 */
( function () {
	'use strict';

	document.addEventListener( 'click', ( event ) => {
		const toggle = event.target.closest( '[data-pepselect-notify-toggle]' );

		if ( toggle ) {
			const dialog = document.getElementById( toggle.getAttribute( 'aria-controls' ) );

			if ( ! dialog || 'function' !== typeof dialog.showModal ) {
				return; // Fall through to the product page link.
			}

			event.preventDefault();

			if ( ! dialog.open ) {
				dialog.showModal();
				const field = dialog.querySelector( 'input[type="email"], input[type="text"]' );
				if ( field ) {
					field.focus();
				}
			}
			return;
		}

		if ( event.target.closest( '[data-pepselect-notify-close]' ) ) {
			const dialog = event.target.closest( 'dialog' );
			if ( dialog ) {
				dialog.close();
			}
			return;
		}

		// Backdrop click: the dialog element itself is the target only when
		// the click lands outside its content box.
		if ( event.target instanceof HTMLDialogElement && event.target.classList.contains( 'pepselect-notify-dialog' ) ) {
			const box = event.target.getBoundingClientRect();
			const inside = event.clientX >= box.left && event.clientX <= box.right && event.clientY >= box.top && event.clientY <= box.bottom;
			if ( ! inside ) {
				event.target.close();
			}
		}
	} );
}() );

/* Swap the open dialog to its confirmation view on plugin-reported success. */
( function () {
	'use strict';

	if ( ! window.jQuery ) {
		return;
	}

	window.jQuery( document ).on( 'cwginstock_success_ajax', () => {
		const dialog = document.querySelector( 'dialog.pepselect-notify-dialog[open]' );

		if ( ! dialog ) {
			return;
		}

		const formView = dialog.querySelector( '.pepselect-notify-dialog__form-view' );
		const successView = dialog.querySelector( '.pepselect-notify-dialog__success' );

		if ( ! formView || ! successView ) {
			return;
		}

		const emailField = dialog.querySelector( '.cwgstock_email' );
		const emailOut = successView.querySelector( '[data-pepselect-subscriber-email]' );

		if ( emailField && emailOut ) {
			emailOut.textContent = emailField.value.trim();
		}

		formView.hidden = true;
		successView.hidden = false;

		const continueLink = successView.querySelector( '.pepselect-notify-dialog__continue' );
		if ( continueLink ) {
			continueLink.focus();
		}
	} );
}() );
