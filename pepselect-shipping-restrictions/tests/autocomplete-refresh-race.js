'use strict';

const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const vm = require( 'node:vm' );

const listeners = {};
const jqueryListeners = {};
let checkoutRefreshes = 0;

function field( id, value, tagName = 'INPUT' ) {
	return {
		id,
		value,
		tagName,
		checked: false,
		querySelector: () => null,
		appendChild: () => {},
		setAttribute: () => {},
		dispatchEvent: () => true,
	};
}

const fields = {
	shipping_address_1: field( 'shipping_address_1', '40228 Parker Road' ),
	shipping_address_2: field( 'shipping_address_2', '' ),
	shipping_city: field( 'shipping_city', 'Prairieville' ),
	shipping_state: field( 'shipping_state', 'LA', 'SELECT' ),
	shipping_postcode: field( 'shipping_postcode', '70769' ),
	shipping_country: field( 'shipping_country', 'US', 'SELECT' ),
	billing_address_1: field( 'billing_address_1', '254 Calle Cruz' ),
	billing_address_2: field( 'billing_address_2', 'Ste 1' ),
	billing_city: field( 'billing_city', 'San Juan' ),
	billing_state: field( 'billing_state', 'PR', 'SELECT' ),
	billing_postcode: field( 'billing_postcode', '00901' ),
	billing_country: field( 'billing_country', 'US', 'SELECT' ),
	billing_same_as_shipping: field( 'billing_same_as_shipping', '' ),
};
fields.billing_same_as_shipping.checked = true;

const documentMock = {
	body: {},
	readyState: 'loading',
	addEventListener( name, callback ) {
		listeners[ name ] = listeners[ name ] || [];
		listeners[ name ].push( callback );
	},
	getElementById( id ) {
		return fields[ id ] || null;
	},
	querySelectorAll() {
		return [];
	},
	querySelector() {
		return null;
	},
	createElement() {
		return field( '', '' );
	},
};

function jqueryMock( target ) {
	return {
		on( name, callback ) {
			jqueryListeners[ name ] = callback;
			return this;
		},
		trigger( name ) {
			if ( target === documentMock.body && name === 'update_checkout' ) {
				checkoutRefreshes += 1;
			}
			return this;
		},
		val( value ) {
			target.value = value;
			return this;
		},
	};
}

const windowMock = {
	jQuery: jqueryMock,
	pepSelectShippingRestrictions: {
		allowedStates: [ 'LA', 'PR' ],
	},
	setTimeout,
	clearTimeout,
};

const source = fs.readFileSync( path.join( __dirname, '..', 'assets', 'shipping-restrictions.js' ), 'utf8' );
vm.runInNewContext( source, { window: windowMock, document: documentMock, Date, Event: class {} } );

async function run() {
	const autocompleteEvent = {
		target: fields.shipping_address_1,
		detail: {
			place: {
				address_components: [ { types: [ 'country' ], short_name: 'US' } ],
			},
		},
	};

	listeners.fc_gaa_address_autocompleted.forEach( callback => callback( autocompleteEvent ) );
	await new Promise( resolve => setTimeout( resolve, 20 ) );

	// Simulate an older Fluid Checkout response restoring the prior address.
	fields.shipping_city.value = 'San Juan';
	fields.shipping_state.value = 'PR';
	fields.shipping_postcode.value = '00901';
	jqueryListeners.updated_checkout();
	await new Promise( resolve => setTimeout( resolve, 150 ) );

	assert.equal( fields.shipping_address_1.value, '40228 Parker Road' );
	assert.equal( fields.shipping_address_2.value, '' );
	assert.equal( fields.shipping_city.value, 'Prairieville' );
	assert.equal( fields.shipping_state.value, 'LA' );
	assert.equal( fields.shipping_postcode.value, '70769' );
	assert.equal( fields.billing_address_1.value, '40228 Parker Road' );
	assert.equal( fields.billing_address_2.value, '' );
	assert.equal( fields.billing_city.value, 'Prairieville' );
	assert.equal( fields.billing_state.value, 'LA' );
	assert.equal( fields.billing_postcode.value, '70769' );
	assert.ok( checkoutRefreshes >= 1, 'A final checkout refresh should serialize the complete address.' );

	console.log( 'Autocomplete checkout refresh race test passed.' );
}

run().catch( error => {
	console.error( error );
	process.exit( 1 );
} );
