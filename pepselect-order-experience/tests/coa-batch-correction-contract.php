<?php
declare( strict_types = 1 );
define( 'ABSPATH', __DIR__ );

// Exercise the resolver with WordPress boundary doubles, including fail-closed cases.
$fixture = array( 'ids' => array( 123 ), 'batch' => 'ND50026205JS', 'private' => '', 'stage' => 'complete', 'outcome' => 'approved', 'photo' => 456 );
function post_type_exists( $type ) { return 'ps_coa_test' === $type; }
function get_posts( $args ) {
    global $fixture;
    if ( 'publish' !== $args['post_status'] || 2 !== $args['posts_per_page'] ) { throw new RuntimeException( 'Public and uniqueness guards changed.' ); }
    return $args['meta_query'][0]['value'] === $fixture['batch'] ? $fixture['ids'] : array();
}
function get_post_meta( $id, $key, $single ) {
    global $fixture;
    return array( '_ps_coa_private' => $fixture['private'], 'workflow_stage' => $fixture['stage'], 'coa_status' => $fixture['outcome'], 'batch_vial_photo' => $fixture['photo'], 'purity_percentage' => '99.87', 'testing_lab' => 'freedom-labs', 'test_date' => '2026-07-30' )[$key] ?? '';
}
function absint( $value ) { return abs( (int) $value ); }
function sanitize_key( $value ) { return strtolower( $value ); }
function get_permalink( $id ) { return 'https://pepselect.com/testing/nad-500-mg/nd50026205js/'; }
function wp_attachment_is_image( $id ) { return 456 === $id; }
function wp_get_attachment_image_url( $id, $size ) { return 'https://pepselect.com/vial-js.webp'; }
function wp_get_attachment_image_srcset( $id, $size ) { return 'https://pepselect.com/vial-js.webp 1000w'; }
function esc_url_raw( $value ) { return $value; }
function esc_attr( $value ) { return $value; }
function wp_date( $format, $timestamp ) { return date( $format, $timestamp ); }
function check( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }
require dirname( __DIR__ ) . '/includes/class-pepselect-oe-coa-resolver.php';
require dirname( __DIR__ ) . '/includes/class-pepselect-oe-view-model.php';
$resolver = new PepSelect_OE_COA_Resolver();
foreach ( array( 'ND50026205JP', ' nd50026205jp ', 'ND50026205JS' ) as $batch ) {
    $result = $resolver->resolve( $batch );
    check( 'ND50026205JS' === $result['batch'] && 456 === $result['image_id'], 'Corrected NAD photo did not resolve.' );
}
check( array() === $resolver->resolve( 'ND50026205JX' ), 'Unapproved typo matched.' );
check( array() === $resolver->resolve( '' ), 'Empty batch matched.' );
$fixture['private'] = '1';
check( array() === $resolver->resolve( 'ND50026205JP' ), 'Private COA leaked.' );
$fixture['private'] = '';
$fixture['ids'] = array( 123, 124 );
check( array() === $resolver->resolve( 'ND50026205JP' ), 'Ambiguous COA matched.' );
$fixture['ids'] = array( 123 );
$fixture['stage'] = 'waiting-on-vendor';
check( array() === $resolver->resolve( 'ND50026205JP' ), 'Unpublished workflow leaked.' );
$fixture['stage'] = 'complete';
$view = (new ReflectionClass( PepSelect_OE_View_Model::class ))->newInstanceWithoutConstructor();
(new ReflectionProperty( $view, 'coa' ))->setValue( $view, $resolver );
$allocation = array( 'batch_number' => 'ND50026205JP', 'coa_permalink' => 'https://pepselect.com/testing/nad-500-mg/nd50026205jp/', 'quantity' => 2 );
$result = (new ReflectionMethod( $view, 'allocation' ))->invoke( $view, $allocation, null );
check( 'ND50026205JS' === $result['batch'] && $result['image_exact'] && 2 === $result['quantity'], 'Corrected view model lost batch, photo, or quantity.' );
check( get_permalink( 123 ) === $result['coa_url'], 'Corrected link not canonical.' );
check( 'ND50026205JP' === $allocation['batch_number'], 'Historical snapshot mutated.' );
$fixture['batch'] = 'OTHER123';
check( 'OTHER123' === $resolver->resolve( 'OTHER123' )['batch'], 'Unrelated batch changed.' );
echo "COA batch correction contract: OK\n";
