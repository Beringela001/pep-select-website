const assert = require('assert');
const fs = require('fs');
const path = require('path');
const vm = require('vm');

const loader = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'assets', 'js', 'confetti-loader.js'),
	'utf8'
);

let appended = 0;
const realCreate = (...args) => ({ args });
const window = {
	pepselectConfettiSource: 'https://example.test/confetti.js?ver=1.0',
};
const document = {
	createElement: () => ({}),
	head: {
		appendChild: (script) => {
			appended += 1;
			assert.strictEqual(script.src, window.pepselectConfettiSource);
			window.confetti = Object.assign(() => undefined, { create: realCreate });
			queueMicrotask(script.onload);
		},
	},
};

vm.runInNewContext(loader, { window, document, Error, Promise });

Promise.all([
	window.confetti.create('canvas', { resize: true }),
	window.confetti.create('second'),
]).then((results) => {
	assert.strictEqual(appended, 1, 'vendor bundle should load once');
	assert.deepStrictEqual(results[0], { args: ['canvas', { resize: true }] });
	assert.deepStrictEqual(results[1], { args: ['second'] });
	console.log('confetti on-demand loader verified');
}).catch((error) => {
	console.error(error);
	process.exitCode = 1;
});
