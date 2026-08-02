<?php
/**
 * Cart page block filters (WEB M10).
 *
 * Three presentation changes on the cart page, all applied to rendered block
 * output rather than to plugin files:
 *
 *   - The "New in store" heading gains a mobile-only alternative label.
 *   - The empty-cart block's placeholder image is replaced with a coded brand
 *     mark, and its default title with the approved two-line copy.
 *   - The recommendation list is replaced with the theme's own query, rendered
 *     through the shared compound card; any further list block is dropped so
 *     exactly one survives.
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
 * Return the compounds shown in the empty cart.
 *
 * The list is queried here rather than filtered out of the block's own query.
 * The block asks for a small fixed number of products, so hiding the
 * out-of-stock ones from that result shrank the list instead of widening it:
 * a filter can only remove, it cannot reach past the block's post count. This
 * query is the source for both breakpoints, so mobile and desktop always show
 * the same compounds.
 *
 * @return WC_Product[]
 */
function pepselect_child_cart_empty_products() {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	$products = wc_get_products(
		array(
			'status'             => 'publish',
			'stock_status'       => 'instock',
			'catalog_visibility' => 'visible',
			'limit'              => 12,
			'orderby'            => 'menu_order title',
			'order'              => 'ASC',
			'return'             => 'objects',
		)
	);

	return is_array( $products ) ? $products : array();
}

/**
 * Render the empty-cart product list.
 *
 * Uses the same card partial as the homepage grid and the single-compound
 * related carousel, in the same ul/li wrapper the related carousel uses, so
 * the cards are the component already shipping elsewhere rather than a second
 * implementation. ps-empty-carousel stays on the container so the existing
 * mobile carousel rules apply unchanged.
 *
 * @return string Markup, or an empty string when there is nothing to show.
 */
function pepselect_child_cart_render_products() {
	$products = pepselect_child_cart_empty_products();

	if ( ! $products ) {
		return '';
	}

	ob_start();
	?>
	<ul class="ps-empty-products ps-empty-carousel">
		<?php foreach ( $products as $product ) : ?>
			<li>
				<?php get_template_part( 'template-parts/home/product-card', null, array( 'product' => $product, 'action' => 'add-to-cart' ) ); ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php

	return (string) ob_get_clean();
}

/**
 * Whether a rendered block is the empty cart's product list.
 *
 * Detected from content, because the block that renders this list differs by
 * WooCommerce version and by how the Cart page was built. Two or more product
 * items in one block is the signature; our own list is excluded by its class
 * so a second pass cannot match it.
 *
 * @param string $html Rendered block HTML.
 * @return bool
 */
function pepselect_child_cart_is_product_list( $html ) {
	if ( false !== strpos( $html, 'ps-empty-products' ) ) {
		return false;
	}

	$items = preg_match_all(
		'#<(?:li|div)\b[^>]*class="[^"]*\b(?:wc-block-grid__product|wc-block-product|product)\b[^"]*"#i',
		$html
	);

	return $items >= 2;
}

/**
 * Whether the cart is genuinely empty.
 *
 * The list swap must not touch a product list that happens to sit on the Cart
 * page while the cart has contents.
 *
 * @return bool
 */
function pepselect_child_cart_is_empty() {
	if ( ! function_exists( 'WC' ) ) {
		return false;
	}

	$wc = WC();

	return isset( $wc->cart ) && is_object( $wc->cart ) && $wc->cart->is_empty();
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
 * 0.19.0-beta.13 swapped the list from inside the empty-cart block's own
 * output. That block does not contain the list: the products render from a
 * separate block, so the swap found nothing, took its append fallback, and
 * added our carousel to the end of the empty-cart block while the stock list
 * carried on rendering in its own block further down. Both lists were on the
 * page, with the heading stranded between them.
 *
 * The product list is now handled where it actually renders. The first block
 * that looks like a product list becomes our carousel; any later one is
 * dropped, so exactly one list survives. Because the swap happens in place,
 * the heading block keeps its own position and ends up above the carousel,
 * giving mark, title, subline, heading, carousel.
 *
 * @param string              $block_content Rendered block HTML.
 * @param array<string,mixed> $block         Parsed block.
 * @return string
 */
function pepselect_child_cart_render_block( $block_content, $block = array() ) {
	static $list_rendered = false;

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
	}

	// The product list only belongs to us while the cart is actually empty; a
	// list sitting on the Cart page with items in the cart is left alone.
	if ( pepselect_child_cart_is_empty() && pepselect_child_cart_is_product_list( $block_content ) ) {
		if ( $list_rendered ) {
			return '';
		}

		$ours = pepselect_child_cart_render_products();

		if ( '' !== $ours ) {
			$list_rendered = true;

			return $ours;
		}
	}

	return pepselect_child_cart_swap_new_in_store( $block_content );
}
add_filter( 'render_block', 'pepselect_child_cart_render_block', 10, 2 );

/*
 * Note on the out-of-stock handling that used to live here.
 *
 * 0.19.0-beta.9 marked out-of-stock items with a post_class filter, which
 * never fired because the block product template does not call
 * get_post_class(). 0.19.0-beta.11 replaced that with a ps-oos class applied
 * in the DOM pass, from a product ID recovered out of the rendered item.
 *
 * Both are gone as of 0.19.0-beta.13. The list is now queried here with
 * stock_status instock, so an out-of-stock compound is never rendered in the
 * first place and there is nothing left to mark or hide. The product-ID
 * resolver and the ps-oos rule were removed rather than left as dead code.
 */
