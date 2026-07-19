/**
 * Live Customizer preview for homepage hero framing.
 *
 * Maps each control to its CSS custom property so slider changes render
 * instantly without a preview refresh.
 */
( function ( api ) {
	'use strict';

	if ( ! api ) {
		return;
	}

	const root = document.documentElement.style;

	const position = () => {
		const x = parseInt( api( 'pepselect_hero_pos_x' )(), 10 );
		const y = parseInt( api( 'pepselect_hero_pos_y' )(), 10 );
		root.setProperty( '--pep-hero-image-position', ( isNaN( x ) ? 50 : x ) + '% ' + ( isNaN( y ) ? 50 : y ) + '%' );
	};

	api( 'pepselect_hero_fit', ( value ) => value.bind( ( fit ) => {
		root.setProperty( '--pep-hero-image-fit', 'contain' === fit ? 'contain' : 'cover' );
	} ) );

	api( 'pepselect_hero_pos_x', ( value ) => value.bind( position ) );
	api( 'pepselect_hero_pos_y', ( value ) => value.bind( position ) );

	api( 'pepselect_hero_zoom', ( value ) => value.bind( ( zoom ) => {
		const z = parseInt( zoom, 10 );
		root.setProperty( '--pep-hero-image-zoom', String( ( isNaN( z ) ? 100 : Math.min( 160, Math.max( 100, z ) ) ) / 100 ) );
	} ) );
}( window.wp && window.wp.customize ) );
