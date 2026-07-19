/**
 * Header compound search typeahead.
 *
 * Fetches live suggestions from the theme's REST endpoint, renders them as
 * a listbox under the search pill, and supports full keyboard navigation.
 * Enter without a highlighted suggestion submits the form as usual.
 */
( function () {
	'use strict';

	const form = document.querySelector( '.pepselect-header__search' );
	const input = document.getElementById( 'pepselect-header-product-search' );

	if ( ! form || ! input || ! form.dataset.suggestEndpoint ) {
		return;
	}

	const endpoint = form.dataset.suggestEndpoint;
	let listbox = null;
	let items = [];
	let activeIndex = -1;
	let debounceTimer = 0;
	let lastTerm = '';
	let abortController = null;

	const ensureListbox = () => {
		if ( listbox ) {
			return listbox;
		}
		listbox = document.createElement( 'ul' );
		listbox.className = 'pepselect-header__suggestions';
		listbox.id = 'pepselect-header-search-suggestions';
		listbox.setAttribute( 'role', 'listbox' );
		form.appendChild( listbox );
		input.setAttribute( 'role', 'combobox' );
		input.setAttribute( 'aria-controls', listbox.id );
		input.setAttribute( 'aria-expanded', 'false' );
		input.setAttribute( 'aria-autocomplete', 'list' );
		return listbox;
	};

	const close = () => {
		if ( listbox ) {
			listbox.innerHTML = '';
			listbox.hidden = true;
		}
		items = [];
		activeIndex = -1;
		input.setAttribute( 'aria-expanded', 'false' );
		input.removeAttribute( 'aria-activedescendant' );
	};

	const setActive = ( index ) => {
		if ( ! listbox ) {
			return;
		}
		const options = listbox.querySelectorAll( '[role="option"]' );
		options.forEach( ( option ) => option.classList.remove( 'is-active' ) );
		activeIndex = index;
		if ( 0 <= index && options[ index ] ) {
			options[ index ].classList.add( 'is-active' );
			input.setAttribute( 'aria-activedescendant', options[ index ].id );
			options[ index ].scrollIntoView( { block: 'nearest' } );
		} else {
			input.removeAttribute( 'aria-activedescendant' );
		}
	};

	const render = ( results ) => {
		const box = ensureListbox();
		box.innerHTML = '';
		items = results;
		activeIndex = -1;

		if ( ! results.length ) {
			close();
			return;
		}

		results.forEach( ( item, index ) => {
			const li = document.createElement( 'li' );
			li.setAttribute( 'role', 'presentation' );

			const link = document.createElement( 'a' );
			link.id = 'pepselect-suggestion-' + index;
			link.setAttribute( 'role', 'option' );
			link.href = item.url;
			link.className = 'pepselect-header__suggestion';

			const name = document.createElement( 'span' );
			name.className = 'pepselect-header__suggestion-name';
			name.textContent = item.title;
			link.appendChild( name );

			if ( item.strength ) {
				const strength = document.createElement( 'span' );
				strength.className = 'pepselect-header__suggestion-strength';
				strength.textContent = item.strength;
				link.appendChild( strength );
			}

			if ( ! item.in_stock ) {
				const stock = document.createElement( 'span' );
				stock.className = 'pepselect-header__suggestion-stock';
				stock.textContent = 'Out of stock';
				link.appendChild( stock );
			}

			link.addEventListener( 'mouseenter', () => setActive( index ) );
			li.appendChild( link );
			box.appendChild( li );
		} );

		box.hidden = false;
		input.setAttribute( 'aria-expanded', 'true' );
	};

	const fetchSuggestions = ( term ) => {
		if ( abortController ) {
			abortController.abort();
		}
		abortController = new AbortController();

		fetch( endpoint + '?term=' + encodeURIComponent( term ), { signal: abortController.signal } )
			.then( ( response ) => ( response.ok ? response.json() : [] ) )
			.then( ( results ) => {
				if ( term === lastTerm ) {
					render( Array.isArray( results ) ? results : [] );
				}
			} )
			.catch( () => {} );
	};

	input.addEventListener( 'input', () => {
		const term = input.value.trim();
		lastTerm = term;
		window.clearTimeout( debounceTimer );

		if ( 2 > term.length ) {
			close();
			return;
		}

		debounceTimer = window.setTimeout( () => fetchSuggestions( term ), 250 );
	} );

	input.addEventListener( 'keydown', ( event ) => {
		if ( ! items.length || ! listbox || listbox.hidden ) {
			return;
		}

		if ( 'ArrowDown' === event.key ) {
			event.preventDefault();
			setActive( ( activeIndex + 1 ) % items.length );
		} else if ( 'ArrowUp' === event.key ) {
			event.preventDefault();
			setActive( 0 > activeIndex - 1 ? items.length - 1 : activeIndex - 1 );
		} else if ( 'Enter' === event.key && 0 <= activeIndex ) {
			event.preventDefault();
			window.location.assign( items[ activeIndex ].url );
		} else if ( 'Escape' === event.key ) {
			close();
		}
	} );

	document.addEventListener( 'click', ( event ) => {
		if ( ! form.contains( event.target ) ) {
			close();
		}
	} );
}() );
