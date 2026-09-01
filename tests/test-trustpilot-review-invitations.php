<?php
define( 'ABSPATH', __DIR__ . '/' );
define( 'DAY_IN_SECONDS', 86400 );
define( 'HOUR_IN_SECONDS', 3600 );

$GLOBALS['pep_actions'] = array();
$GLOBALS['pep_options'] = array();
$GLOBALS['pep_orders'] = array();
$GLOBALS['pep_scheduled'] = array();
$GLOBALS['pep_mail'] = array();

function add_action( $hook, $callback, $priority = 10, $args = 1 ) { $GLOBALS['pep_actions'][ $hook ][] = $callback; }
function apply_filters( $hook, $value ) { return $value; }
function __( $text ) { return $text; }
function esc_html__( $text ) { return $text; }
function esc_html_e( $text ) { echo htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $url ) { return (string) $url; }
function home_url( $path = '/' ) { return 'https://pepselect.com' . $path; }
function plugin_dir_url() { return 'https://pepselect.com/wp-content/plugins/pepselect-trustpilot-review/'; }
function wp_date( $format ) { return date( $format ); }
function add_query_arg( $args, $url ) { return $url . '?' . http_build_query( $args ); }
function absint( $value ) { return abs( (int) $value ); }
function sanitize_email( $email ) { return filter_var( $email, FILTER_SANITIZE_EMAIL ); }
function is_email( $email ) { return (bool) filter_var( $email, FILTER_VALIDATE_EMAIL ); }
function wp_salt() { return 'test-salt'; }
function get_option( $key, $default = false ) { return array_key_exists( $key, $GLOBALS['pep_options'] ) ? $GLOBALS['pep_options'][ $key ] : $default; }
function update_option( $key, $value ) { $GLOBALS['pep_options'][ $key ] = $value; return true; }
function wc_get_order( $id ) { return $GLOBALS['pep_orders'][ $id ] ?? false; }
function as_has_scheduled_action( $hook, $args, $group ) { return isset( $GLOBALS['pep_scheduled'][ $hook . ':' . $args[0] ] ); }
function as_schedule_single_action( $timestamp, $hook, $args, $group, $unique ) { $GLOBALS['pep_scheduled'][ $hook . ':' . $args[0] ] = compact( 'timestamp', 'hook', 'args', 'group', 'unique' ); return 1; }
function as_unschedule_all_actions( $hook, $args, $group ) { unset( $GLOBALS['pep_scheduled'][ $hook . ':' . $args[0] ] ); }
function wp_mail( $recipient, $subject, $body, $headers ) { $GLOBALS['pep_mail'][] = compact( 'recipient', 'subject', 'body', 'headers' ); return true; }

class WC_Order {
	private $id;
	private $status;
	private $meta = array();
	public function __construct( $id, $status = 'completed' ) { $this->id = $id; $this->status = $status; }
	public function get_id() { return $this->id; }
	public function has_status( $status ) { return $this->status === $status; }
	public function set_status( $status ) { $this->status = $status; }
	public function get_billing_email() { return 'alex@example.com'; }
	public function get_billing_first_name() { return 'Alex'; }
	public function get_order_number() { return '12345'; }
	public function get_meta( $key ) { return $this->meta[ $key ] ?? ''; }
	public function update_meta_data( $key, $value ) { $this->meta[ $key ] = $value; }
	public function delete_meta_data( $key ) { unset( $this->meta[ $key ] ); }
	public function save() { return true; }
}

require dirname( __DIR__ ) . '/pepselect-trustpilot-review/pepselect-trustpilot-review.php';
PepSelect_Trustpilot_Review_Invitations::init();

$order = new WC_Order( 123 );
$GLOBALS['pep_orders'][123] = $order;

PepSelect_Trustpilot_Review_Invitations::schedule_for_order( 123 );
assert( empty( $GLOBALS['pep_scheduled'] ), 'Paused-by-default plugin must not schedule.' );

$GLOBALS['pep_options']['pepselect_trustpilot_review_enabled'] = 'yes';
PepSelect_Trustpilot_Review_Invitations::schedule_for_order( 123 );
$key = 'pepselect_send_trustpilot_review_invitation:123';
assert( isset( $GLOBALS['pep_scheduled'][ $key ] ), 'Eligible completed order must schedule.' );
assert( $GLOBALS['pep_scheduled'][ $key ]['timestamp'] >= time() + ( 7 * DAY_IN_SECONDS ) - 2, 'Default delay must be seven days.' );

PepSelect_Trustpilot_Review_Invitations::send_for_order( 123 );
assert( 1 === count( $GLOBALS['pep_mail'] ), 'Invitation must send once.' );
assert( 'alex@example.com' === $GLOBALS['pep_mail'][0]['recipient'], 'Invitation recipient must be the billing email.' );
assert( false !== strpos( $GLOBALS['pep_mail'][0]['body'], 'Share an honest review' ), 'Email must use neutral review language.' );
assert( false !== strpos( $GLOBALS['pep_mail'][0]['body'], 'https://www.trustpilot.com/evaluate/pepselect.com' ), 'Email must link to the official Trustpilot form.' );
assert( $order->get_meta( '_pepselect_trustpilot_review_sent_at' ), 'Successful send must be recorded.' );

PepSelect_Trustpilot_Review_Invitations::send_for_order( 123 );
assert( 1 === count( $GLOBALS['pep_mail'] ), 'Recorded order must not send twice.' );

PepSelect_Trustpilot_Review_Invitations::cancel_for_order( 123 );
assert( ! isset( $GLOBALS['pep_scheduled'][ $key ] ), 'Cancellation must clear the pending action.' );

echo "Trustpilot review invitation PHP behavior checks passed.\n";
