const fs = require('fs');
const path = require('path');
const assert = require('assert');

const root = path.resolve(__dirname, '..');
const plugin = fs.readFileSync(path.join(root, 'pepselect-trustpilot-review', 'pepselect-trustpilot-review.php'), 'utf8');
const template = fs.readFileSync(path.join(root, 'pepselect-trustpilot-review', 'templates', 'review-email.php'), 'utf8');

assert.match(plugin, /woocommerce_order_status_completed/);
assert.match(plugin, /array\( 'cancelled', 'failed', 'refunded' \)/);
assert.match(plugin, /as_schedule_single_action/);
assert.match(plugin, /as_unschedule_all_actions/);
assert.match(plugin, /ORDER_SENT/);
assert.match(plugin, /hash_hmac\( 'sha256'/);
assert.match(plugin, /hash_equals/);
assert.match(plugin, /wp_mail\(/);
assert.match(plugin, /ENABLED_OPTION/);
assert.match(plugin, /manage_woocommerce/);
assert.match(plugin, /check_admin_referer/);
assert.match(plugin, /render_admin_preview/);
assert.match(plugin, /CUSTOMER_COOLDOWN_DAYS\s*=\s*180/);
assert.match(plugin, /CUSTOMER_SENT_OPTION/);
assert.match(plugin, /CUSTOMER_PENDING_OPTION/);
assert.match(plugin, /EXCLUSIONS_OPTION/);
assert.match(plugin, /Add exclusion/);
assert.match(plugin, /type="email"/);
assert.match(plugin, /email_is_excluded/);
assert.match(plugin, /self::cancel_for_order/);
assert.match(plugin, /schedule_historical_orders/);
assert.match(plugin, /get_date_completed/);
assert.match(plugin, /Schedule existing customers/);
assert.match(plugin, /https:\/\/www\.trustpilot\.com\/evaluate\/pepselect\.com/);

assert.match(template, /How was your Pep Select experience\?/);
assert.match(template, /Every rating is welcome/);
assert.match(template, /Share an honest review/);
assert.match(template, /official Pep Select review form on Trustpilot/);
assert.match(template, /Stop future review invitations/);
assert.match(template, /All products are intended for laboratory study and identification purposes only\. Not intended for human or animal use\./);
assert.doesNotMatch(template, /discount|coupon|reward|five-star|5-star/i);
assert.match(template, /@media only screen and \(max-width:520px\)/);

console.log('Trustpilot review invitation checks passed.');
