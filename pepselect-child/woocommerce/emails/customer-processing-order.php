<?php
/**
 * Customer processing order email — Pep Select child theme override.
 *
 * The shared customer order-status canvas detects the processing email object
 * and renders the approved payment-confirmed state with canonical WooCommerce
 * order, product, total, and address data.
 *
 * @package WooCommerce\Templates\Emails
 * @version 10.9.0
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/customer-completed-order.php';
