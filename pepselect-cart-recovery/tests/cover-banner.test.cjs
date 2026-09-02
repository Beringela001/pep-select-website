const assert = require('assert');
const fs = require('fs');
const path = require('path');
const vm = require('vm');

const script = fs.readFileSync(path.resolve(__dirname, '..', 'assets', 'campaign-cover-banner.js'), 'utf8');
const listeners = {};
const styles = {};
const banner = {
  getBoundingClientRect: () => ({ top: 150 }),
  style: { setProperty: (name, value) => { styles[name] = value; } }
};

const windowMock = {
  innerHeight: 900,
  scrollY: 0,
  visualViewport: null,
  requestAnimationFrame: (callback) => { callback(); return 1; },
  cancelAnimationFrame: () => {},
  addEventListener: (name, callback) => { listeners[name] = callback; }
};

vm.runInNewContext(script, {
  document: {
    querySelector: (selector) => selector === '[data-pep-campaign-cover]' ? banner : null
  },
  window: windowMock,
  ResizeObserver: function () { return { observe: () => {} }; }
});

assert.strictEqual(styles['--pep-cover-available-height'], '750px', 'Initial banner height must fill the viewport below the header');
windowMock.innerHeight = 700;
listeners.resize();
assert.strictEqual(styles['--pep-cover-available-height'], '550px', 'Banner height must update when the browser is resized');

console.log('Pep Select campaign cover resize checks passed.');
