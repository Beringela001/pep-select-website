'use strict';

const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );

const root = path.join( __dirname, '..' );
const template = fs.readFileSync(
	path.join( root, 'pepselect-child', 'woocommerce', 'myaccount', 'form-login.php' ),
	'utf8'
);
const accountPhp = fs.readFileSync(
	path.join( root, 'pepselect-child', 'inc', 'account.php' ),
	'utf8'
);
const css = fs.readFileSync(
	path.join( root, 'pepselect-child', 'assets', 'css', 'account.css' ),
	'utf8'
);
const script = fs.readFileSync(
	path.join( root, 'pepselect-child', 'assets', 'js', 'account-login.js' ),
	'utf8'
);

assert.match( template, /role="tablist"/ );
assert.match( template, /data-account-panel="login"/ );
assert.match( template, /data-account-panel="register"/ );
assert.match( template, /pepselect_child_render_registration_fields_without_birthday/ );
assert.doesNotMatch( template, /name=["']yith_birthday/ );

assert.match( accountPhp, /name=\(\["\\'\]\)yith_birthday/ );
assert.match( accountPhp, /assets\/js\/account-login\.js/ );
assert.match( script, /popupWidth/ );
assert.match( script, /Math\.min\( 820/ );
assert.match( script, /aria-selected/ );

assert.match( css, /overflow-x: clip/ );
assert.match( css, /max-width: 520px/ );
assert.match( css, /--pep-radius-card-inner/ );
assert.doesNotMatch( css, /grid-template-columns: 1fr 1fr;\s*gap: 40px/ );

console.log( 'Account login experience checks passed.' );
