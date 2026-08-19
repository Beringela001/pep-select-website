'use strict';

const assert = require('assert');
const fs = require('fs');
const path = require('path');
const plugin = fs.readFileSync(path.join(__dirname, '..', 'ps-access-gate.php'), 'utf8');

assert.match(plugin, /aria-modal="true" aria-labelledby="psag-title" aria-describedby="psag-intro"/);
assert.match(plugin, /id="psag-intro"/);
assert.match(plugin, /aria-controls="psagAttestBody"/);
assert.match(plugin, /id="psagExit" href="<\?php echo esc_url\( \$s\['exit_url'\] \); \?>"/);
assert.ok(plugin.includes("node.setAttribute('inert', '')"));
assert.ok(plugin.includes("node.setAttribute('aria-hidden', 'true')"));
assert.ok(plugin.includes("gate.addEventListener('keydown'"));
assert.ok(plugin.includes("if ('Tab' !== event.key) return"));
assert.ok(plugin.includes('window.requestAnimationFrame'));
assert.ok(plugin.includes("main.focus()"));
assert.doesNotMatch(plugin, /psagExit[^\n]+window\.location\.href/);
assert.ok(plugin.includes("attachment_url_to_postid( $s['logo_url'] )"));
assert.ok(plugin.includes("wp_get_attachment_image( $logo_id, 'medium'"));
assert.ok(plugin.includes("'sizes' => '(max-width: 480px) 160px, 220px'"));

console.log('PS_ACCESS_GATE_ACCESSIBILITY_TESTS=PASS');
