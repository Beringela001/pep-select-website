( () => {
	'use strict';

	const source = window.pepselectConfettiSource;

	if ( ! source || ( window.confetti && typeof window.confetti.create === 'function' ) ) {
		return;
	}

	let loadPromise;
	let proxy;

	const loadConfetti = () => {
		if ( loadPromise ) {
			return loadPromise;
		}

		loadPromise = new Promise( ( resolve, reject ) => {
			const script = document.createElement( 'script' );

			script.src = source;
			script.async = true;
			script.onload = () => {
				if ( window.confetti !== proxy && typeof window.confetti?.create === 'function' ) {
					resolve( window.confetti );
					return;
				}

				reject( new Error( 'Side-cart celebration library did not initialize.' ) );
			};
			script.onerror = () => reject( new Error( 'Side-cart celebration library could not load.' ) );
			document.head.appendChild( script );
		} );

		return loadPromise;
	};

	proxy = ( ...args ) => loadConfetti().then( ( library ) => library( ...args ) );
	proxy.create = async ( ...args ) => {
		const library = await loadConfetti();
		return library.create( ...args );
	};

	window.confetti = proxy;
} )();
