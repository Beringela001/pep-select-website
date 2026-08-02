<?php
/**
 * Cart page block filters (WEB M10).
 *
 * Three presentation changes on the cart page, all applied to rendered block
 * output rather than to plugin files:
 *
 *   - The "New in store" heading gains a mobile-only alternative label.
 *   - Out-of-stock products in the empty-cart recommendation list are marked
 *     so CSS can drop them on small screens.
 *   - The empty-cart block's placeholder image is replaced with a coded brand
 *     mark, and its default title with the approved two-line copy.
 *
 * Every filter is gated on is_cart() and every rewrite is conditional: when a
 * pattern does not match, the original markup is returned untouched. The block
 * markup is owned by WooCommerce Blocks and changes between plugin versions,
 * so nothing here assumes a selector it has not just found.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether this request is the front-end cart page.
 *
 * Resolved once per request. The block filters below run for every block on
 * the page, so this must stay cheap.
 *
 * @return bool
 */
function pepselect_child_is_cart_request() {
	static $is_cart = null;

	if ( null !== $is_cart ) {
		return $is_cart;
	}

	$is_cart = ! is_admin() && function_exists( 'is_cart' ) && is_cart();

	return $is_cart;
}

/**
 * Return the coded empty-cart brand mark.
 *
 * A line-weight vial outline matching the header icon treatment: 48x48,
 * 1.5 stroke, currentColor, no fill. Deliberately inline and coded so the
 * cart never depends on an uploaded asset again.
 *
 * @return string
 */
function pepselect_child_empty_cart_mark() {
	return '<div class="ps-empty-cart__mark">'
		. '<svg class="ps-empty-cart__glyph" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
		. '<path d="M18 6h12" />'
		. '<path d="M20 6v6.4l-3.1 4.2A6 6 0 0 0 15.8 20v18a4 4 0 0 0 4 4h8.4a4 4 0 0 0 4-4V20a6 6 0 0 0-1.1-3.4L28 12.4V6" />'
		. '<path d="M15.8 28.5h16.4" />'
		. '</svg>'
		. '</div>';
}

/**
 * Swap the "New in store" heading for a labelled pair.
 *
 * Both labels are rendered; CSS shows the mobile label below 768px and the
 * desktop label at 768px and up, so the desktop wording is unchanged. Only
 * heading elements are touched, and only the first occurrence inside one.
 *
 * @param string $html Rendered block HTML.
 * @return string
 */
function pepselect_child_cart_swap_new_in_store( $html ) {
	if ( false === stripos( $html, 'New in store' ) ) {
		return $html;
	}

	if ( false !== strpos( $html, 'ps-label-desktop' ) ) {
		return $html;
	}

	$swapped = preg_replace_callback(
		'#(<h[1-6]\b[^>]*>)(.*?)(</h[1-6]>)#is',
		static function ( $matches ) {
			if ( false === stripos( $matches[2], 'New in store' ) ) {
				return $matches[0];
			}

			// The matched text is reused verbatim for the desktop label so a
			// heading authored in a different case is not silently re-cased.
			$inner = preg_replace_callback(
				'/New in store/i',
				static function ( $found ) {
					return '<span class="ps-label-desktop">' . $found[0] . '</span>'
						. '<span class="ps-label-mobile">Selected compounds</span>';
				},
				$matches[2],
				1
			);

			if ( null === $inner ) {
				return $matches[0];
			}

			return $matches[1] . $inner . $matches[3];
		},
		$html
	);

	return null === $swapped ? $html : $swapped;
}

/**
 * Replace the empty-cart placeholder image and default title.
 *
 * The placeholder is matched on the block's own class, never on an image URL,
 * and is removed whether the block renders it as an <img> or as an inline
 * <svg>. The title is replaced only when it is found; when it is not, the
 * markup is returned exactly as received and a diagnostic is recorded for
 * shop managers (see pepselect_child_cart_report_markup()).
 *
 * @param string $html Rendered block HTML.
 * @return string
 */
function pepselect_child_cart_rewrite_empty( $html ) {
	if ( false !== strpos( $html, 'ps-empty-cart__mark' ) ) {
		return $html;
	}

	$title_pattern = '#<(h[1-6]|p)\b[^>]*>\s*Your cart is currently empty[!.]?\s*</\1>#i';

	if ( ! preg_match( $title_pattern, $html ) ) {
		pepselect_child_cart_report_markup( $html );

		return $html;
	}

	$rewritten = $html;

	// Remove the placeholder mark, matched on the block's class in either form.
	$rewritten = preg_replace(
		'#<img\b[^>]*class="[^"]*\bwc-block-cart__empty-cart__image\b[^"]*"[^>]*>#i',
		'',
		$rewritten,
		1
	);

	$rewritten = preg_replace(
		'#<svg\b[^>]*class="[^"]*\bwc-block-cart__empty-cart__image\b[^"]*"[^>]*>.*?</svg>#is',
		'',
		$rewritten,
		1
	);

	if ( null === $rewritten ) {
		return $html;
	}

	$replacement = pepselect_child_empty_cart_mark()
		. '<p class="ps-empty-cart__title">An empty cart is a clean bench.</p>'
		. '<p class="ps-empty-cart__sub">Pick a compound and we will handle the paperwork.</p>';

	$rewritten = preg_replace( $title_pattern, $replacement, $rewritten, 1 );

	return null === $rewritten ? $html : $rewritten;
}

/**
 * Tag the empty-cart product list container with ps-empty-carousel.
 *
 * The container class differs by WooCommerce Blocks version, so it is found by
 * parsing the rendered markup and locating the element that actually holds the
 * product items, rather than by testing a list of guessed class names. The
 * element with the most product-item children wins; when no element holds at
 * least two, nothing is tagged and the markup is returned untouched so the
 * grid keeps its default layout.
 *
 * @param string $html Rendered block HTML.
 * @return string
 */
function pepselect_child_cart_tag_carousel( $html ) {
	if ( false !== strpos( $html, 'ps-empty-carousel' ) ) {
		return $html;
	}

	if ( '' === trim( $html ) || ! class_exists( 'DOMDocument' ) ) {
		return $html;
	}

	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$loaded = $doc->loadHTML( '<?xml encoding="utf-8" ?><div id="pepselect-carousel-root">' . $html . '</div>', LIBXML_NOWARNING | LIBXML_NOERROR );
	libxml_clear_errors();

	if ( ! $loaded ) {
		return $html;
	}

	$xpath = new DOMXPath( $doc );

	// Product items as any current or past block version renders them.
	$items = $xpath->query(
		"//li[contains(@class,'product')]"
		. "|//li[contains(@class,'wc-block-grid__product')]"
		. "|//div[contains(@class,'wc-block-grid__product')]"
		. "|//li[contains(@class,'wc-block-product')]"
	);

	if ( ! $items || $items->length < 2 ) {
		pepselect_child_cart_report_markup( $html );

		return $html;
	}

	// Group siblings by parent; the parent holding the most items is the list.
	$parents = array();

	foreach ( $items as $item ) {
		$parent = $item->parentNode;

		if ( ! $parent instanceof DOMElement ) {
			continue;
		}

		$key = spl_object_hash( $parent );

		if ( ! isset( $parents[ $key ] ) ) {
			$parents[ $key ] = array(
				'node'  => $parent,
				'count' => 0,
			);
		}

		++$parents[ $key ]['count'];
	}

	$container = null;
	$best      = 1;

	foreach ( $parents as $candidate ) {
		if ( $candidate['count'] > $best ) {
			$best      = $candidate['count'];
			$container = $candidate['node'];
		}
	}

	if ( ! $container instanceof DOMElement ) {
		pepselect_child_cart_report_markup( $html );

		return $html;
	}

	$existing = trim( (string) $container->getAttribute( 'class' ) );
	$container->setAttribute( 'class', '' === $existing ? 'ps-empty-carousel' : $existing . ' ps-empty-carousel' );

	$root = $doc->getElementById( 'pepselect-carousel-root' );

	if ( ! $root instanceof DOMElement ) {
		return $html;
	}

	$rebuilt = '';

	foreach ( $root->childNodes as $child ) {
		$rebuilt .= $doc->saveHTML( $child );
	}

	// Never hand back markup that lost content in the round trip.
	if ( '' === trim( $rebuilt ) || strlen( $rebuilt ) < ( strlen( $html ) / 2 ) ) {
		return $html;
	}

	return $rebuilt;
}

/**
 * Record the real empty-cart markup when the expected title is absent.
 *
 * WooCommerce Blocks owns this markup and changes it between versions. Rather
 * than guess a selector or blank the block, the received markup is emitted as
 * an HTML comment for users who can manage WooCommerce, so the actual output
 * can be read from view-source on the live cart and the pattern corrected.
 *
 * @param string $html Rendered block HTML.
 * @return void
 */
function pepselect_child_cart_report_markup( $html ) {
	static $reported = false;

	if ( $reported ) {
		return;
	}

	if ( ! function_exists( 'current_user_can' ) || ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	$reported = true;

	add_action(
		'wp_footer',
		static function () use ( $html ) {
			// Neutralize comment delimiters so the payload cannot close the comment early.
			$safe = str_replace( array( '<!--', '-->' ), array( '(!--', '--)' ), $html );

			echo "\n<!-- pepselect: empty-cart title pattern did not match. Actual block markup follows.\n"
				. esc_html( $safe )
				. "\n-->\n";
		},
		999
	);
}

/**
 * Apply the cart block rewrites.
 *
 * @param string               $block_content Rendered block HTML.
 * @param array<string,mixed>  $block         Parsed block.
 * @return string
 */
function pepselect_child_cart_render_block( $block_content, $block = array() ) {
	if ( ! is_string( $block_content ) || '' === $block_content ) {
		return $block_content;
	}

	if ( ! pepselect_child_is_cart_request() ) {
		return $block_content;
	}

	$name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';

	// The empty-cart rewrite targets its own block, with a content-based
	// fallback for versions that render it inside the parent cart block.
	$is_empty_cart = 'woocommerce/empty-cart-block' === $name
		|| false !== strpos( $block_content, 'wc-block-cart__empty-cart__image' )
		|| false !== stripos( $block_content, 'Your cart is currently empty' );

	if ( $is_empty_cart ) {
		$block_content = pepselect_child_cart_rewrite_empty( $block_content );
		$block_content = pepselect_child_cart_tag_carousel( $block_content );
	}

	return pepselect_child_cart_swap_new_in_store( $block_content );
}
add_filter( 'render_block', 'pepselect_child_cart_render_block', 10, 2 );

/**
 * Mark out-of-stock products in the cart page's recommendation list.
 *
 * The class is added on the cart page only; CSS hides the marked items below
 * 768px and leaves every breakpoint above it untouched.
 *
 * @param string[] $classes Post classes.
 * @return string[]
 */
function pepselect_child_cart_mark_out_of_stock( $classes ) {
	if ( ! pepselect_child_is_cart_request() ) {
		return $classes;
	}

	if ( ! function_exists( 'wc_get_product' ) ) {
		return $classes;
	}

	$product = wc_get_product( get_the_ID() );

	if ( is_a( $product, 'WC_Product' ) && ! $product->is_in_stock() ) {
		$classes[] = 'ps-oos';
	}

	return $classes;
}
add_filter( 'post_class', 'pepselect_child_cart_mark_out_of_stock' );
