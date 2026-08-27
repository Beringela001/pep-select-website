<?php

defined( 'ABSPATH' ) || exit;

/** Renders the approved Concept 03 order experience inside the active theme. */
final class PepSelect_OE_Renderer {
	/** @param array<string,mixed> $model */
	public function render( array $model ): string {
		ob_start();
		$this->icons();
		?>
		<main id="order-content" class="pepselect-oe">
			<section class="pepselect-oe__hero">
				<div class="pepselect-oe__wrap">
					<p class="pepselect-oe__kicker">Order #<?php echo esc_html( $model['order_number'] ); ?></p>
					<h1>Thank you, <span><?php echo esc_html( $model['first_name'] ); ?>.</span></h1>
					<p class="pepselect-oe__hero-copy">Your order and its batch records are collected here. Match each vial to the batch below, open the lab report, and revisit this page whenever you need information about order #<?php echo esc_html( $model['order_number'] ); ?>.</p>
					<div class="pepselect-oe__status"><span class="pepselect-oe__date"><?php echo esc_html( strtoupper( $model['date'] ) ); ?></span><span class="pepselect-oe__status-pill"><svg><use href="#pepselect-oe-check"/></svg><?php echo esc_html( $model['status'] ); ?></span></div>
				</div>
			</section>

			<div class="pepselect-oe__wrap pepselect-oe__main">
				<section class="pepselect-oe__section" aria-labelledby="pepselect-oe-compounds">
					<div class="pepselect-oe__section-head"><div><p class="pepselect-oe__kicker">Your compounds</p><h2 id="pepselect-oe-compounds">Match the vial. Match the batch.</h2></div><p>The label and batch number on each vial should match the record shown here. Open the full report whenever you want to review the laboratory results.</p></div>
					<div class="pepselect-oe__ordered-grid">
						<?php foreach ( $model['items'] as $index => $item ) : $primary = $item['allocations'][0] ?? array(); ?>
							<article class="pepselect-oe__ordered-card">
								<div class="pepselect-oe__media">
									<?php if ( ! empty( $primary['image'] ) ) : ?><img src="<?php echo esc_url( $primary['image'] ); ?>" <?php if ( ! empty( $primary['image_srcset'] ) ) : ?>srcset="<?php echo esc_attr( $primary['image_srcset'] ); ?>" sizes="(max-width: 767px) 50vw, 33vw"<?php endif; ?> alt="<?php echo esc_attr( sprintf( '%s vial for batch %s', $item['name'], $primary['batch'] ?? '' ) ); ?>"><?php endif; ?>
									<span class="pepselect-oe__count"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
									<?php if ( empty( $primary['image_exact'] ) ) : ?><span class="pepselect-oe__image-note">Product image</span><?php endif; ?>
								</div>
								<div class="pepselect-oe__ordered-content">
								<div class="pepselect-oe__ordered-info">
									<div class="pepselect-oe__ordered-title"><h3><?php echo esc_html( $item['name'] ); ?></h3><?php if ( $item['strength'] ) : ?><span><?php echo esc_html( strtoupper( $item['strength'] ) ); ?></span><?php endif; ?></div>
									<p class="pepselect-oe__context-label">Studied for</p>
									<ul class="pepselect-oe__context-list"><?php foreach ( $item['bullets'] as $bullet ) : ?><li><?php echo esc_html( $bullet ); ?></li><?php endforeach; ?></ul>
								</div>
								<?php foreach ( $item['allocations'] as $allocation_index => $allocation ) : ?>
									<div class="pepselect-oe__batch<?php echo $allocation_index ? ' pepselect-oe__batch--additional' : ''; ?>">
										<p class="pepselect-oe__batch-heading"><svg><use href="#pepselect-oe-file"/></svg><?php echo $allocation_index ? 'Additional batch' : 'Batch summary'; ?></p>
										<dl class="pepselect-oe__batch-rows">
											<div><dt>Batch</dt><dd><?php echo esc_html( $allocation['batch'] ); ?></dd></div>
											<div><dt>Purity</dt><dd><?php echo esc_html( $allocation['purity'] ); ?></dd></div>
											<div><dt>Tested</dt><dd><?php echo esc_html( strtoupper( $allocation['test_date'] ) ); ?></dd></div>
											<div><dt>Lab</dt><dd><?php echo esc_html( strtoupper( $allocation['lab'] ) ); ?></dd></div>
										</dl>
										<div class="pepselect-oe__test-result pepselect-oe__test-result--<?php echo esc_attr( $allocation['status']['tone'] ); ?>"><svg><use href="#pepselect-oe-check"/></svg><?php echo esc_html( $allocation['status']['label'] ); ?></div>
										<?php if ( $allocation['coa_url'] ) : ?><a class="pepselect-oe__button pepselect-oe__button--full" href="<?php echo esc_url( $allocation['coa_url'] ); ?>">Review full report<svg><use href="#pepselect-oe-arrow"/></svg></a><?php else : ?><p class="pepselect-oe__unavailable">The full report is not available from this record.</p><?php endif; ?>
									</div>
								<?php endforeach; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
					<?php $this->receipt( $model ); ?>
					<p class="pepselect-oe__account-row"><a href="<?php echo esc_url( $model['account_order_url'] ); ?>">Review this order in My Account</a></p>
				</section>

				<section class="pepselect-oe__section pepselect-oe__storage" aria-labelledby="pepselect-oe-storage">
					<h2 id="pepselect-oe-storage">Storage and Handling</h2>
					<div class="pepselect-oe__storage-grid">
						<?php $this->fact( 'snow', 'Long-term storage', 'Freeze-dried: -20°C.' ); ?>
						<?php $this->fact( 'therm', 'Shorter periods', 'Freeze-dried: 2–8°C.' ); ?>
						<?php $this->fact( 'drop', 'After reconstitution', 'Reconstituted: 2–8°C.' ); ?>
						<?php $this->fact( 'repeat', 'Temperature changes', 'Avoid repeated freeze-thaw cycles.' ); ?>
					</div>
					<div class="pepselect-oe__storage-action"><a class="pepselect-oe__button pepselect-oe__button--light" href="<?php echo esc_url( $model['faq_url'] ); ?>">Read the storage FAQ<svg><use href="#pepselect-oe-arrow"/></svg></a></div>
				</section>

				<?php if ( $model['coupon'] ) : ?>
					<section class="pepselect-oe__section pepselect-oe__offer"><div><p class="pepselect-oe__kicker">A thank-you from us</p><h2>15% off your next order.</h2><p>We appreciate your order. Use this code on your next purchase.</p></div><div class="pepselect-oe__coupon"><code><?php echo esc_html( strtoupper( $model['coupon'] ) ); ?></code><button type="button" data-pepselect-copy="<?php echo esc_attr( $model['coupon'] ); ?>">Copy code</button></div></section>
				<?php endif; ?>

				<?php if ( $model['related'] ) : ?>
					<section class="pepselect-oe__section pepselect-oe__related" aria-labelledby="pepselect-oe-related"><div class="pepselect-oe__section-head"><div><p class="pepselect-oe__kicker">You may also want to explore</p><h2 id="pepselect-oe-related">More compounds related to what you ordered.</h2></div><p>These compounds have been studied alongside the ones in your order.</p></div><div class="pepselect-oe__related-grid">
						<?php foreach ( $model['related'] as $related ) : ?><a class="pepselect-oe__related-card<?php echo ! empty( $related['restocking'] ) ? ' pepselect-oe__related-card--restocking' : ''; ?>" href="<?php echo esc_url( $related['url'] ); ?>"><div class="pepselect-oe__related-image"><img src="<?php echo esc_url( $related['image'] ); ?>" alt="<?php echo esc_attr( $related['name'] . ' product vial' ); ?>"><?php if ( ! empty( $related['restocking'] ) ) : ?><span class="pepselect-oe__availability">Restocking</span><?php endif; ?></div><div class="pepselect-oe__related-body"><span class="pepselect-oe__related-to">Related to <?php echo esc_html( $related['related_to'] ); ?></span><h3><?php echo esc_html( $related['name'] ); ?></h3><p><?php echo esc_html( $related['reason'] ); ?></p><span class="pepselect-oe__related-link">View compound<svg><use href="#pepselect-oe-arrow"/></svg></span></div></a><?php endforeach; ?>
					</div></section>
				<?php endif; ?>

				<section class="pepselect-oe__section pepselect-oe__help-grid">
					<article class="pepselect-oe__help-card"><div class="pepselect-oe__help-icon"><svg><use href="#pepselect-oe-headset"/></svg></div><div><h2>Have any questions?</h2><p>Our team will gladly help. If your question is about this order, include order #<?php echo esc_html( $model['order_number'] ); ?> so we can find it faster.</p><a class="pepselect-oe__button" href="<?php echo esc_url( $model['contact_url'] ); ?>">Contact our team<svg><use href="#pepselect-oe-arrow"/></svg></a></div></article>
					<article class="pepselect-oe__help-card"><div class="pepselect-oe__help-icon"><svg><use href="#pepselect-oe-cart"/></svg></div><div><h2>Time to reorder these compounds?</h2><p>Use the button below to add the items from order #<?php echo esc_html( $model['order_number'] ); ?> to your cart.</p><?php if ( $model['can_reorder'] && ! $model['preview'] ) : ?><form method="post" action="<?php echo esc_url( $model['reorder_action'] ); ?>"><?php wp_nonce_field( 'pepselect_oe_reorder', 'pepselect_oe_nonce' ); ?><input type="hidden" name="pepselect_oe_action" value="reorder"><button class="pepselect-oe__button" type="submit"><span class="pepselect-oe__button-wide">Add these items to cart</span><span class="pepselect-oe__button-short">Add items to cart</span><svg><use href="#pepselect-oe-arrow"/></svg></button></form><?php elseif ( $model['preview'] ) : ?><span class="pepselect-oe__button pepselect-oe__button--disabled"><span class="pepselect-oe__button-wide">Add these items to cart</span><span class="pepselect-oe__button-short">Add items to cart</span><svg><use href="#pepselect-oe-arrow"/></svg></span><?php else : ?><p class="pepselect-oe__unavailable">These items are not available to reorder.</p><?php endif; ?></div></article>
				</section>
			</div>
		</main>
		<?php
		return (string) ob_get_clean();
	}

	/** @param array<string,mixed> $model */
	private function receipt( array $model ): void {
		?><details class="pepselect-oe__receipt"><summary><div><span>Order</span><strong>#<?php echo esc_html( $model['order_number'] ); ?></strong></div><div><span>Placed</span><strong><?php echo esc_html( strtoupper( $model['date'] ) ); ?></strong></div><div class="pepselect-oe__receipt-total"><span>Order total</span><strong><?php echo wp_kses_post( $model['order_total'] ); ?></strong></div><span class="pepselect-oe__button">Review order details<svg><use href="#pepselect-oe-arrow"/></svg></span></summary><div class="pepselect-oe__receipt-details"><div><?php foreach ( $model['items'] as $item ) : ?><div class="pepselect-oe__receipt-line"><span><?php echo esc_html( $item['name'] . ( $item['strength'] ? ' · ' . $item['strength'] : '' ) . ( $item['quantity'] > 1 ? ' × ' . $item['quantity'] : '' ) ); ?></span><span><?php echo wp_kses_post( $item['line_total'] ); ?></span></div><?php endforeach; ?></div><div><div class="pepselect-oe__receipt-line"><span>Subtotal</span><span><?php echo wp_kses_post( $model['subtotal'] ); ?></span></div><?php if ( $model['discount'] ) : ?><div class="pepselect-oe__receipt-line"><span>Discount</span><span>-<?php echo wp_kses_post( $model['discount'] ); ?></span></div><?php endif; ?><div class="pepselect-oe__receipt-line"><span>Shipping</span><span><?php echo wp_kses_post( $model['shipping'] ); ?></span></div><?php if ( $model['tax'] ) : ?><div class="pepselect-oe__receipt-line"><span>Tax</span><span><?php echo wp_kses_post( $model['tax'] ); ?></span></div><?php endif; ?><div class="pepselect-oe__receipt-line pepselect-oe__receipt-line--total"><span>Total</span><span><?php echo wp_kses_post( $model['order_total'] ); ?></span></div></div></div></details><?php
	}

	private function fact( string $icon, string $title, string $copy ): void {
		$parts = explode( ': ', $copy, 2 );
		?><article class="pepselect-oe__storage-fact"><span><svg><use href="#pepselect-oe-<?php echo esc_attr( $icon ); ?>"/></svg></span><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $parts[0] ); ?><?php if ( isset( $parts[1] ) ) : ?>: <span class="pepselect-oe__temperature"><?php echo esc_html( $parts[1] ); ?></span><?php endif; ?></p></article><?php
	}

	private function icons(): void {
		?><svg class="pepselect-oe__symbols" aria-hidden="true"><symbol id="pepselect-oe-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></symbol><symbol id="pepselect-oe-file" viewBox="0 0 24 24"><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v5h5M9 12h6M9 16h6"/></symbol><symbol id="pepselect-oe-arrow" viewBox="0 0 24 24"><path d="M5 12h14M14 7l5 5-5 5"/></symbol><symbol id="pepselect-oe-therm" viewBox="0 0 24 24"><path d="M10 14.5V5a2 2 0 0 1 4 0v9.5a4 4 0 1 1-4 0Z"/><path d="M12 8v8"/></symbol><symbol id="pepselect-oe-snow" viewBox="0 0 24 24"><path d="M12 2v20M4 7l16 10M4 17 20 7M9 4l3 3 3-3M9 20l3-3 3 3"/></symbol><symbol id="pepselect-oe-drop" viewBox="0 0 24 24"><path d="M12 2S6 9 6 14a6 6 0 0 0 12 0c0-5-6-12-6-12Z"/></symbol><symbol id="pepselect-oe-repeat" viewBox="0 0 24 24"><path d="m17 2 4 4-4 4M3 11V9a3 3 0 0 1 3-3h15M7 22l-4-4 4-4M21 13v2a3 3 0 0 1-3 3H3"/></symbol><symbol id="pepselect-oe-headset" viewBox="0 0 24 24"><path d="M4 14v-2a8 8 0 0 1 16 0v2M4 14h3v6H5a1 1 0 0 1-1-1zM20 14h-3v6h2a1 1 0 0 0 1-1zM17 20c0 1-1 2-3 2h-2"/></symbol><symbol id="pepselect-oe-cart" viewBox="0 0 24 24"><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M3 4h2l2 11h11l2-8H6"/></symbol></svg><?php
	}
}
