const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );

const homepageScript = fs.readFileSync(
	path.join( __dirname, '..', 'pepselect-child', 'assets', 'js', 'homepage.js' ),
	'utf8'
);

assert.doesNotMatch( homepageScript, /scrollRestoration\s*=/, 'homepage must not disable native Back\/Forward scroll restoration' );
assert.match( homepageScript, /! window\.location\.hash/, 'homepage scroll reset does not preserve fragment navigation' );
assert.match( homepageScript, /'back_forward' === navigationEntry\.type/, 'homepage scroll reset does not preserve Back\/Forward navigation' );
assert.match( homepageScript, /event\.persisted/, 'homepage scroll reset does not preserve bfcache restoration' );
assert.match( homepageScript, /window\.requestAnimationFrame/, 'homepage scroll reset does not cover delayed browser restoration' );
assert.match( homepageScript, /window\.scrollTo\( 0, 0 \)/, 'homepage does not reset normal initial loads to the global header' );

console.log( 'homepage initial-scroll safeguards passed' );
