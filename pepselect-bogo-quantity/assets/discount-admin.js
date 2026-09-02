( function () {
	'use strict';

	var preview = document.querySelector( '[data-pep-sitewide-preview]' );
	if ( ! preview ) {
		return;
	}

	var form = document.querySelector( 'input[name="action"][value="pepselect_save_sitewide_discount"]' );
	form = form ? form.closest( 'form' ) : null;
	if ( ! form ) {
		return;
	}

	var regularPrice = 79.99;
	var amount = form.querySelector( '[name="rule[discount_amount]"]' );
	var type = form.querySelector( '[name="rule[discount_type]"]' );
	var threshold = form.querySelector( '[name="rule[threshold_type]"]' );
	var note = preview.querySelector( '[data-preview-note]' );

	function value( name, fallback ) {
		var field = form.querySelector( '[name="rule[' + name + ']"]' );
		return field && field.value ? field.value : fallback;
	}

	function update() {
		var percent = Math.max( 0, Math.min( 100, parseFloat( amount.value ) || 0 ) );
		var canShow = type.value === 'percent' && threshold.value === 'none';
		preview.style.setProperty( '--pep-preview-label', value( 'sale_label_color', '#087DA5' ) );
		preview.style.setProperty( '--pep-preview-regular', value( 'regular_price_color', '#667786' ) );
		preview.style.setProperty( '--pep-preview-sale', value( 'sale_price_color', '#002A53' ) );
		preview.querySelectorAll( '[data-preview-label]' ).forEach( function ( element ) { element.textContent = percent.toFixed( 2 ).replace( /\.00$/, '' ).replace( /(\.\d)0$/, '$1' ) + '% off'; } );
		preview.querySelectorAll( '[data-preview-sale]' ).forEach( function ( element ) { element.textContent = ( regularPrice * ( 1 - percent / 100 ) ).toFixed( 2 ); } );
		preview.classList.toggle( 'is-cart-only', ! canShow );
		note.textContent = canShow ? 'This exact treatment appears on product pages and product cards.' : 'Fixed-dollar or minimum-order discounts appear in Cart and Checkout only.';
	}

	form.querySelectorAll( '[name^="rule["]' ).forEach( function ( field ) { field.addEventListener( 'input', update ); field.addEventListener( 'change', update ); } );
	preview.querySelectorAll( '[data-preview-surface]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			preview.querySelectorAll( '[data-preview-surface]' ).forEach( function ( item ) { item.classList.toggle( 'is-active', item === button ); } );
			preview.querySelectorAll( '[data-preview-panel]' ).forEach( function ( panel ) { panel.hidden = panel.dataset.previewPanel !== button.dataset.previewSurface; } );
		} );
	} );
	preview.querySelectorAll( '[data-preview-device]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			preview.querySelectorAll( '[data-preview-device]' ).forEach( function ( item ) { item.classList.toggle( 'is-active', item === button ); } );
			preview.classList.toggle( 'is-mobile', button.dataset.previewDevice === 'mobile' );
		} );
	} );

	update();
}() );
