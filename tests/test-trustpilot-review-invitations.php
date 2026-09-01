<?php
define( 'ABSPATH', __DIR__ . '/' );
define( 'DAY_IN_SECONDS', 86400 );
define( 'HOUR_IN_SECONDS', 3600 );
define( 'MINUTE_IN_SECONDS', 60 );

$GLOBALS['pep_actions']   = array();
$GLOBALS['pep_options']   = array();
$GLOBALS['pep_orders']    = array();
$GLOBALS['pep_scheduled'] = array();
$GLOBALS['pep_mail']      = array();

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
function wc_get_orders() {
	$orders = array_values( $GLOBALS['pep_orders'] );
	usort( $orders, function( $a, $b ) { return $b->get_date_completed()->getTimestamp() <=> $a->get_date_completed()->getTimestamp(); } );
	return $orders;
}
function as_has_scheduled_action( $hook, $args, $group ) { return isset( $GLOBALS['pep_scheduled'][ $hook . ':' . $args[0] ] ); }
function as_schedule_single_action( $timestamp, $hook, $args, $group, $unique ) { $GLOBALS['pep_scheduled'][ $hook . ':' . $args[0] ] = compact( 'timestamp', 'hook', 'args', 'group', 'unique' ); return 1; }
function as_unschedule_all_actions( $hook, $args, $group ) { unset( $GLOBALS['pep_scheduled'][ $hook . ':' . $args[0] ] ); }
function wp_mail( $recipient, $subject, $body, $headers ) { $GLOBALS['pep_mail'][] = compact( 'recipient', 'subject', 'body', 'headers' ); return true; }

class Pep_Test_Date {
	private $timestamp;
	public function __construct( $timestamp ) { $this->timestamp = $timestamp; }
	public function getTimestamp() { return $this->timestamp; }
}

class WC_Order {
	private $id;
	private $status;
	private $email;
	private $completed;
	private $meta = array();
	public function __construct( $id, $email = 'alex@example.com', $completed = null, $status = 'completed' ) {
		$this->id        = $id;
		$this->email     = $email;
		$this->completed = null === $completed ? time() : $completed;
		$this->status    = $status;
	}
	public function get_id() { return $this->id; }
	public function has_status( $status ) { return $this->status === $status; }
	public function set_status( $status ) { $this->status = $status; }
	public function get_billing_email() { return $this->email; }
	public function get_billing_first_name() { return 'Alex'; }
	public function get_order_number() { return (string) $this->id; }
	public function get_date_completed() { return new Pep_Test_Date( $this->completed ); }
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
assert( $GLOBALS['pep_scheduled'][ $key ]['timestamp'] >= time() + ( 7 * DAY_IN_SECONDS ) - 2, 'Default delay must be seven days from completion.' );

$repeat = new WC_Order( 124, 'Alex@Example.com' );
$GLOBALS['pep_orders'][124] = $repeat;
PepSelect_Trustpilot_Review_Invitations::schedule_for_order( 124 );
assert( ! isset( $GLOBALS['pep_scheduled']['pepselect_send_trustpilot_review_invitation:124'] ), 'A customer must not have two pending invitations.' );

PepSelect_Trustpilot_Review_Invitations::send_for_order( 123 );
assert( 1 === count( $GLOBALS['pep_mail'] ), 'Invitation must send once.' );
assert( 'alex@example.com' === $GLOBALS['pep_mail'][0]['recipient'], 'Invitation recipient must be the billing email.' );
assert( false !== strpos( $GLOBALS['pep_mail'][0]['body'], 'Share an honest review' ), 'Email must use neutral review language.' );
assert( false !== strpos( $GLOBALS['pep_mail'][0]['body'], 'https://www.trustpilot.com/evaluate/pepselect.com' ), 'Email must link to the official Trustpilot form.' );
assert( $order->get_meta( '_pepselect_trustpilot_review_sent_at' ), 'Successful send must be recorded.' );

PepSelect_Trustpilot_Review_Invitations::send_for_order( 123 );
assert( 1 === count( $GLOBALS['pep_mail'] ), 'Recorded order must not send twice.' );

PepSelect_Trustpilot_Review_Invitations::schedule_for_order( 124 );
assert( ! isset( $GLOBALS['pep_scheduled']['pepselect_send_trustpilot_review_invitation:124'] ), 'A repeat customer inside 180 days must not schedule.' );

$email_hash = hash_hmac( 'sha256', 'alex@example.com', 'test-salt' );
$GLOBALS['pep_options']['pepselect_trustpilot_review_customer_sent'][ $email_hash ] = time() - ( 181 * DAY_IN_SECONDS );
PepSelect_Trustpilot_Review_Invitations::schedule_for_order( 124 );
$repeat_key = 'pepselect_send_trustpilot_review_invitation:124';
assert( isset( $GLOBALS['pep_scheduled'][ $repeat_key ] ), 'A repeat customer may schedule after the 180-day cooldown.' );
PepSelect_Trustpilot_Review_Invitations::cancel_for_order( 124 );
assert( ! isset( $GLOBALS['pep_scheduled'][ $repeat_key ] ), 'Cancellation must clear the pending action.' );

$excluded = new WC_Order( 125, 'Family@Example.com' );
$GLOBALS['pep_orders'][125] = $excluded;
PepSelect_Trustpilot_Review_Invitations::schedule_for_order( 125 );
$excluded_key = 'pepselect_send_trustpilot_review_invitation:125';
assert( isset( $GLOBALS['pep_scheduled'][ $excluded_key ] ), 'An otherwise eligible email must initially schedule.' );
assert( PepSelect_Trustpilot_Review_Invitations::add_exclusion( ' Family@Example.com ' ), 'Any valid email may be added to the admin exclusion list.' );
assert( ! isset( $GLOBALS['pep_scheduled'][ $excluded_key ] ), 'Adding an exclusion must cancel its pending invitation.' );
PepSelect_Trustpilot_Review_Invitations::schedule_for_order( 125 );
assert( ! isset( $GLOBALS['pep_scheduled'][ $excluded_key ] ), 'An excluded email must not schedule another invitation.' );
$family_hash = hash_hmac( 'sha256', 'family@example.com', 'test-salt' );
assert( 'family@example.com' === $GLOBALS['pep_options']['pepselect_trustpilot_review_exclusions'][ $family_hash ]['email'], 'Exclusions must be normalized and stored for admin display.' );
assert( PepSelect_Trustpilot_Review_Invitations::remove_exclusion( $family_hash ), 'An existing exclusion may be removed.' );
assert( empty( $GLOBALS['pep_options']['pepselect_trustpilot_review_exclusions'] ), 'Removing the exclusion must update the stored list.' );

$GLOBALS['pep_orders']    = array();
$GLOBALS['pep_scheduled'] = array();
$GLOBALS['pep_options']['pepselect_trustpilot_review_customer_sent']    = array();
$GLOBALS['pep_options']['pepselect_trustpilot_review_customer_pending'] = array();

$alex_old    = new WC_Order( 201, 'alex@example.com', time() - ( 30 * DAY_IN_SECONDS ) );
$alex_latest = new WC_Order( 202, 'alex@example.com', time() - ( 10 * DAY_IN_SECONDS ) );
$beth_recent = new WC_Order( 203, 'beth@example.com', time() - ( 3 * DAY_IN_SECONDS ) );
$chris_out   = new WC_Order( 204, 'chris@example.com', time() - ( 20 * DAY_IN_SECONDS ) );
$dana_sent   = new WC_Order( 205, 'dana@example.com', time() - ( 20 * DAY_IN_SECONDS ) );
$chris_out->update_meta_data( '_pepselect_trustpilot_review_opted_out', gmdate( 'c' ) );
$dana_sent->update_meta_data( '_pepselect_trustpilot_review_sent_at', gmdate( 'c', time() - DAY_IN_SECONDS ) );

foreach ( array( $alex_old, $alex_latest, $beth_recent, $chris_out, $dana_sent ) as $historical_order ) {
	$GLOBALS['pep_orders'][ $historical_order->get_id() ] = $historical_order;
}

$summary = PepSelect_Trustpilot_Review_Invitations::schedule_historical_orders();
assert( 4 === $summary['customers'], 'Catch-up must deduplicate completed orders by billing email.' );
assert( 1 === $summary['queued_now'], 'Catch-up must queue customers already past seven days.' );
assert( 1 === $summary['scheduled_later'], 'Catch-up must schedule recent customers for their seven-day mark.' );
assert( 2 === $summary['skipped'], 'Catch-up must skip opted-out and recently invited customers.' );
assert( ! isset( $GLOBALS['pep_scheduled']['pepselect_send_trustpilot_review_invitation:201'] ), 'Catch-up must not use an older repeat order.' );
assert( isset( $GLOBALS['pep_scheduled']['pepselect_send_trustpilot_review_invitation:202'] ), 'Catch-up must use the latest completed order for an existing customer.' );
assert( isset( $GLOBALS['pep_scheduled']['pepselect_send_trustpilot_review_invitation:203'] ), 'Catch-up must schedule a recent completed order.' );
assert( $GLOBALS['pep_scheduled']['pepselect_send_trustpilot_review_invitation:203']['timestamp'] >= time() + ( 4 * DAY_IN_SECONDS ) - 2, 'Recent catch-up order must wait until seven days after completion.' );
assert( ! empty( $GLOBALS['pep_options']['pepselect_trustpilot_review_catchup_v2']['completed_at'] ), 'Catch-up completion must be recorded.' );

echo "Trustpilot review invitation PHP behavior checks passed.\n";
