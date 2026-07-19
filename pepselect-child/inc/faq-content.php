<?php
/**
 * Approved FAQ content for the full /faq/ page.
 *
 * Grouped into sections. The homepage FAQ keeps its own five-question subset
 * (see pepselect_child_get_homepage_faqs); this file is the complete record.
 * All facts here are approved for customer-facing use. Do not add unverified
 * claims: figures, policies, and mechanics must be confirmed before shipping.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the grouped FAQ sections for the full FAQ page.
 *
 * @return array<int,array{title:string,items:array<int,array{question:string,answer:string}>}>
 */
function pepselect_child_get_faq_sections() {
	return array(
		array(
			'title' => __( 'Research use', 'pepselect-child' ),
			'items' => array(
				array(
					'question' => __( 'Are these compounds for human use?', 'pepselect-child' ),
					'answer'   => __( 'No. Everything we sell is research-use-only (RUO) material, intended for laboratory research by qualified professionals. Nothing here is a drug, supplement, or product for human or veterinary use, and nothing on this site is a claim about safety, efficacy, or medical outcomes.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'Who can purchase?', 'pepselect-child' ),
					'answer'   => __( 'You must be 21 or older to order. By purchasing, you confirm you are acquiring these materials for lawful research use only, consistent with their research-use-only status.', 'pepselect-child' ),
				),
			),
		),
		array(
			'title' => __( 'Testing & records', 'pepselect-child' ),
			'items' => array(
				array(
					'question' => __( 'How do I find the documents for my batch?', 'pepselect-child' ),
					'answer'   => __( 'Every batch we have released has a permanent record on our testing page. Search the compound, or enter the batch code printed on your vial, to open the exact certificate of analysis for what you received. Records stay online after a batch sells out.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'What is a batch number, and how do I use it?', 'pepselect-child' ),
					'answer'   => __( 'A batch number is the identifier tied to one specific production batch of a compound. It is how the vial in your hand connects to a single, specific test record rather than a general product claim. Enter it on our testing page and you will land on the certificate of analysis for that exact batch, the same document our team reads. Batch numbers do not change once assigned, so the link between your vial and its record is permanent.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'What happens if a batch fails testing?', 'pepselect-child' ),
					'answer'   => __( 'We publish results as the lab returns them, and we do not hide a batch that falls short. A batch that does not meet spec is not sold, but its record stays on the testing page like any other, because a complete record includes the batches that did not pass, not only the ones that did.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'What is on a certificate of analysis?', 'pepselect-child' ),
					'answer'   => __( 'Each certificate is the third-party lab\'s report for that batch: the compound identified, the batch it came from, and the date it was tested. We publish them exactly as the lab returns them, unedited.', 'pepselect-child' ),
				),
			),
		),
		array(
			'title' => __( 'Storage & handling', 'pepselect-child' ),
			'items' => array(
				array(
					'question' => __( 'How should I store these materials?', 'pepselect-child' ),
					'answer'   => __( 'Store lyophilized (freeze-dried) material at -20 &deg;C for long-term storage; 2 to 8 &deg;C is acceptable for shorter periods. Reconstituted solutions are less stable and should be kept at 2 to 8 &deg;C. Avoid repeated freeze-thaw cycles.', 'pepselect-child' ),
				),
			),
		),
		array(
			'title' => __( 'Ordering, payment & rewards', 'pepselect-child' ),
			'items' => array(
				array(
					'question' => __( 'How does payment work?', 'pepselect-child' ),
					'answer'   => __( 'After you place an order, you will receive a secure Square payment link by email to complete your purchase. Orders that are not paid are automatically cancelled after 90 minutes, so inventory is not held.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'How does cash back work?', 'pepselect-child' ),
					'answer'   => __( 'You earn 3% cash back on every order: 3 points for every $1 you spend, where 100 points is worth $1. You can redeem once your balance reaches 500 points ($5). Your balance shows in your account.', 'pepselect-child' ),
				),
			),
		),
		array(
			'title' => __( 'Shipping', 'pepselect-child' ),
			'items' => array(
				array(
					'question' => __( 'When do orders ship?', 'pepselect-child' ),
					'answer'   => __( 'Orders placed by 10 a.m. ET, Monday through Thursday, ship the same day.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'What are the shipping options?', 'pepselect-child' ),
					'answer'   => __( 'USPS Priority and FedEx (two-day and next-day). FedEx two-day is free on orders with a subtotal of $200 or more. Rates are calculated at checkout.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'Where do you ship?', 'pepselect-child' ),
					'answer'   => __( 'All 50 U.S. states and Washington, D.C. Shipping is calculated at checkout.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'How do I track my order?', 'pepselect-child' ),
					'answer'   => __( 'Use our order tracking page and enter your details to see current status.', 'pepselect-child' ),
				),
			),
		),
		array(
			'title' => __( 'Orders & support', 'pepselect-child' ),
			'items' => array(
				array(
					'question' => __( 'My order arrived damaged or incorrect. What should I do?', 'pepselect-child' ),
					'answer'   => __( 'Contact support within 72 hours of delivery with your order number and photos of the product and packaging. Once verified, we resolve it with a replacement, refund, or store credit, per our published policy.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'I did not receive an order confirmation email.', 'pepselect-child' ),
					'answer'   => __( 'Email support@pepselect.com with your name and the last 6 digits of the card used, and we will trace the order for you.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'Do you offer a military or first responder discount?', 'pepselect-child' ),
					'answer'   => __( 'Yes. Verify your status on our Military &amp; Law Enforcement page, verification is handled by VerifyPass, to receive a one-time 20% discount code.', 'pepselect-child' ),
				),
				array(
					'question' => __( 'How do I contact you?', 'pepselect-child' ),
					'answer'   => __( 'Email support@pepselect.com and we will get back to you.', 'pepselect-child' ),
				),
			),
		),
	);
}
