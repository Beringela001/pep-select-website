'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const pluginPath = path.join(
  __dirname,
  '..',
  'pepselect-cart-recovery',
  'pepselect-cart-recovery.php'
);
const source = fs.readFileSync(pluginPath, 'utf8');

assert.match(source, /Version:\s*0\.4\.7/);
assert.match(source, /MARKETING_EMAILS_PER_SECOND\s*=\s*1/);
assert.match(source, /fluent_crm\/global_email_limit_per_second/);
assert.match(source, /return self::MARKETING_EMAILS_PER_SECOND;/);

console.log('Pep Select FluentCRM throttle contract checks passed.');
