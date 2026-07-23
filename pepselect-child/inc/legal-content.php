<?php
/**
 * Published legal copy for the four coded legal pages.
 *
 * Reproduced verbatim from the live pages. This is legal copy: do not
 * rewrite, condense, reorder, or otherwise edit the wording. The only
 * departure from the published text is the last-updated date, which was the
 * literal placeholder 'Last updated: [DATE]' in the prose and is now the
 * 'last_updated' field below, rendered by the template so it is changed in
 * one place.
 *
 * Strings are intentionally not wrapped in translation calls. Legal wording
 * is approved as published and must not vary by locale.
 *
 * Body is an ordered array of blocks, each one of:
 *   heading    - level (int), text (plain)
 *   paragraph  - html (inline markup only: strong, em, a, br)
 *   list       - ordered (bool), items (array of inline html)
 *
 * Values are stored raw and escaped on output, never in the data.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return every legal document, keyed by page slug.
 *
 * @return array<string,array{slug:string,title:string,last_updated:string,body:array<int,array<string,mixed>>}>
 */
function pepselect_child_get_legal_documents() {
	return array(
		'terms-conditions' => array(
			'slug'         => 'terms-conditions',
			'title'        => 'Terms & Conditions',
			'last_updated' => 'July 23, 2026',
			'body'         => array(
				array(
					'type' => 'paragraph',
					'html' => 'Welcome to PepSelect. These Terms &amp; Conditions (“Terms”) govern your access to and use of pepselect.com (the “Site”) and any purchase made through it. The Site is operated by PepSelect (“PepSelect,” “we,” “us,” or “our”). By accessing the Site or placing an order, you agree to be bound by these Terms in full. If you do not agree, do not use the Site or purchase from us.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '1. Research Use Only',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Every product sold by PepSelect is supplied strictly for in-vitro laboratory research and analytical purposes. Our products are:',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Not for human or animal consumption of any kind',
						'Not for medical, veterinary, diagnostic, or therapeutic use',
						'Not for injection, ingestion, topical application, or any other form of personal use',
						'Not cosmetics, supplements, drugs, or food additives',
					),
				),
				array(
					'type' => 'paragraph',
					'html' => 'Products sold on this Site have not been evaluated or approved by the U.S. Food and Drug Administration. Nothing on this Site is medical advice, treatment guidance, or an endorsement of any off-label application. By purchasing, you acknowledge that you understand the nature of research compounds and agree to use them solely for lawful research in compliance with all applicable federal, state, and local laws.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '2. Eligibility & Researcher Certification',
				),
				array(
					'type' => 'paragraph',
					'html' => 'By placing an order with PepSelect, you certify that:',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'You are at least 21 years of age',
						'You are a researcher, or are otherwise legally qualified, to purchase and handle research materials',
						'You are purchasing exclusively for legitimate research purposes',
						'You will not use, distribute, or resell any product for human or animal use',
					),
				),
				array(
					'type' => 'paragraph',
					'html' => 'Providing false or misleading information is grounds for immediate order cancellation and refusal of future service.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '3. Ordering & Order Acceptance',
				),
				array(
					'type' => 'paragraph',
					'html' => 'By placing an order, you agree to provide accurate and complete information. All orders are subject to product availability and to our acceptance. We reserve the right to refuse or cancel any order at our sole discretion, including orders we believe are placed by resellers or distributors, or that present legal or regulatory risk. If an item you ordered becomes unavailable, we will contact you to arrange a substitution or refund. Prices are listed in U.S. dollars and may change without notice.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '4. Payment',
				),
				array(
					'type' => 'paragraph',
					'html' => 'After you place an order, a secure payment link is sent to the email address provided at checkout. Your order is not complete until payment is received. Orders must be paid within 90 minutes of placement; unpaid orders are automatically canceled and any reserved stock is released. We reserve the right to cancel any order where payment cannot be verified.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '5. Shipping & Risk of Loss',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Processing and dispatch times are set out in our Refund &amp; Shipping Policy. Transit times provided by carriers are estimates and are outside our control. Risk of loss and title pass to you when the package is delivered to the carrier. PepSelect is not responsible for delays caused by carriers, weather, customs, or other circumstances beyond our control. Full details are set out in our Refund &amp; Shipping Policy, which is incorporated into these Terms.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '6. Product Information & Certificates of Analysis',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We work to keep product descriptions, specifications, and labels accurate, but they may change without notice. Products are batch-tested by independent laboratories, and Certificates of Analysis (COAs) are published on our COA lookup page. Purity is verified at the batch level and is not guaranteed beyond the listed conditions and shelf life. All products are provided “as is” for research purposes only, without warranties of any kind, express or implied.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '7. Assumption of Responsibility',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Once an order leaves our facility, full responsibility for handling, storage, testing, and research use transfers to the purchaser. You acknowledge and agree that PepSelect is not responsible for misuse, mishandling, improper storage, or unauthorized use of any product, and is not liable for injury, harm, loss, or damage resulting from failure to follow these Terms.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '8. Limitation of Liability',
				),
				array(
					'type' => 'paragraph',
					'html' => 'To the fullest extent permitted by law, PepSelect shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising out of or relating to the purchase or use of our products or the Site, including any legal consequences of improper or unlawful use and any claims related to research outcomes or suitability. Our total liability for any claim shall not exceed the amount you paid for the specific product giving rise to the claim.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '9. Right to Refuse Service',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We may refuse or cancel any order at our discretion, including in cases involving suspected illegal, unsafe, or unauthorized use; violation of our policies; false or misleading information; or any activity that may expose PepSelect to legal or regulatory risk.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '10. Intellectual Property',
				),
				array(
					'type' => 'paragraph',
					'html' => 'All content on this Site — including the PepSelect name, logos, text, product imagery, and design — is the property of PepSelect and may not be used without our express written permission.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '11. Privacy',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Your use of the Site is also governed by our Privacy Policy, which explains how we collect, use, and protect your personal information.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '12. Governing Law',
				),
				array(
					'type' => 'paragraph',
					'html' => 'These Terms are governed by the laws of the State of Georgia, without regard to conflict-of-law principles.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '13. Changes to These Terms',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We may update these Terms at any time. Changes take effect when posted to this page, and your continued use of the Site after posting constitutes acceptance of the revised Terms. Please review this page periodically.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '14. Contact',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Questions about these Terms can be sent to [support@pepselect.com]. Mailing address: PepSelect, 2090 Baker Rd, Ste 304, Kennesaw, GA 30144.',
				),
			),
		),
		'privacy-policy' => array(
			'slug'         => 'privacy-policy',
			'title'        => 'Privacy Policy',
			'last_updated' => 'July 23, 2026',
			'body'         => array(
				array(
					'type' => 'paragraph',
					'html' => 'PepSelect (“PepSelect,” “we,” “us,” or “our”) operates pepselect.com. This Privacy Policy explains how we collect, use, share, and safeguard your information when you visit our website or place an order. By using the Site, you consent to the practices described here.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Information We Collect',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'<strong>Personal information: </strong>name, email address, shipping and billing address, phone number, and order details provided at registration or checkout.',
						'<strong>Payment information: </strong>payments are processed by secure third-party payment providers. We do not store full card numbers or banking details on our servers.',
						'<strong>Account information: </strong>username, encrypted password, order history, and saved preferences. If you sign in with Google, we receive your name and email address from Google; we never see your Google password.',
						'<strong>Automatically collected data: </strong>IP address, browser and device type, referring URLs, pages viewed, and time spent on the Site.',
					),
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'How We Use Your Information',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Process and fulfill your orders, including sending your secure payment link',
						'Send order confirmations, shipping updates, and receipts',
						'Manage your account and provide customer support',
						'Improve our website, products, and services',
						'Send promotional emails, only with your consent — you may unsubscribe at any time',
						'Detect and prevent fraud or unauthorized activity',
						'Comply with legal obligations',
					),
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Cookies & Tracking',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We use cookies and similar technologies to keep your cart working, analyze site traffic, and improve your experience. You can control cookies through your browser settings; disabling them may affect features such as the shopping cart and checkout.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Sharing Your Information',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We do not sell, rent, or trade your personal information. We share it only with trusted third parties who help us operate the business:',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Payment processors, to securely handle transactions',
						'Shipping carriers, to deliver your orders',
						'Email service providers, to send transactional and (with consent) promotional email',
						'Analytics providers, to help us understand how the Site is used',
						'Legal authorities, where required by law',
					),
				),
				array(
					'type' => 'paragraph',
					'html' => 'All service providers are obligated to safeguard your data.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Data Security',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We use industry-standard safeguards including SSL encryption, secure payment processing, and restricted access controls. No method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Data Retention',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We keep your personal information for as long as your account is active or as needed to provide services, comply with legal obligations, resolve disputes, and enforce our agreements. If you request account deletion, we will remove your personal data within 30 days, except where retention is required by law.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Your Rights',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Depending on where you live, you may have the right to:',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Access the personal data we hold about you',
						'Request correction of inaccurate data',
						'Request deletion of your personal data',
						'Opt out of marketing communications',
						'Request a copy of your data in a portable format',
					),
				),
				array(
					'type' => 'paragraph',
					'html' => 'California residents have additional rights under the CCPA/CPRA, including the right to know what personal data is collected and to opt out of its sale or sharing (we do not sell personal data). To exercise any of these rights, contact us using the details below.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Age Requirement',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Our website and products are intended solely for individuals 21 years of age or older. We do not knowingly collect personal information from anyone under 21; if we learn we have done so, we will promptly delete it.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Email & Marketing',
				),
				array(
					'type' => 'paragraph',
					'html' => 'You may unsubscribe from marketing emails at any time using the link in any message or by contacting us directly. We comply with CAN-SPAM requirements.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Changes to This Policy',
				),
				array(
					'type' => 'paragraph',
					'html' => 'We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date. We encourage you to review it periodically.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Contact Us',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Questions or concerns about this Privacy Policy can be sent to [support@pepselect.com]. Mailing address: PepSelect, 2090 Baker Rd, Ste 304, Kennesaw, GA 30144.',
				),
			),
		),
		'ruo-disclaimer' => array(
			'slug'         => 'ruo-disclaimer',
			'title'        => 'RUO Disclaimer',
			'last_updated' => 'July 23, 2026',
			'body'         => array(
				array(
					'type' => 'paragraph',
					'html' => 'All products sold by <b>Pep Select</b> are intended strictly for laboratory research purposes only. They are not intended for human or animal consumption, medical use, therapeutic use, diagnostic procedures, or any other application outside legitimate scientific research.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '1. Purchaser Acknowledgment',
				),
				array(
					'type' => 'paragraph',
					'html' => 'By purchasing products from Pep Select, you acknowledge and agree that:',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'You are purchasing these materials solely for lawful laboratory, analytical, educational, or in vitro research purposes.',
						'The products will not be used in or administered to humans or animals under any circumstances.',
						'Pep Select makes no representations regarding the safety, effectiveness, or therapeutic value of any product.',
						'You understand the risks associated with handling research materials and accept responsibility for their safe handling, storage, and disposal.',
						'You will comply with all applicable federal, state, and local laws governing the purchase, possession, and use of research compounds.',
					),
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '2. Intended Use',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Products sold by Pep Select are designated <b>Research Use Only (RUO)</b> and are intended exclusively for laboratory research and scientific investigation.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'These products are <b>not</b> intended for:',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Human consumption',
						'Animal use',
						'Medical treatment',
						'Diagnosis',
						'Prevention or cure of disease',
						'Clinical or veterinary applications',
					),
				),
				array(
					'type' => 'paragraph',
					'html' => 'Statements made on this website have not been evaluated by the U.S. Food and Drug Administration (FDA). Our products are not intended to diagnose, treat, cure, or prevent any disease.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '3. Purchaser Verification',
				),
				array(
					'type' => 'paragraph',
					'html' => 'By placing an order, you certify that the information provided during checkout is accurate.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Pep Select reserves the right to perform additional verification measures, including identity verification, IP address validation, or other authentication procedures when necessary.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Orders may be canceled or refused at Pep Select&#8217;s discretion if fraudulent or misleading information is detected.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '4. Limitation of Liability',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Pep Select assumes no responsibility for any misuse of its products.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'By purchasing from Pep Select, you agree to release, indemnify, and hold harmless Pep Select, its owners, employees, and affiliates from any claims, damages, losses, or liabilities resulting from improper or unauthorized use of these products.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Any use outside legitimate laboratory research is solely the responsibility of the purchaser.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '5. FDA Disclaimer',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Statements made by Pep Select have not been evaluated by the U.S. Food and Drug Administration.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Our products are not intended to diagnose, treat, cure, or prevent any disease and are sold exclusively for research purposes.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Pep Select does not recommend or endorse the use of its products in humans or animals.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => '6. Regulatory Notice',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Pep Select is not a manufacturer, compounding pharmacy, or outsourcing facility as defined under Sections 503A or 503B of the Federal Food, Drug, and Cosmetic Act.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Products are sold in the condition received from the manufacturer. Pep Select does not alter, compound, repackage, or modify the contents of sealed research products.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Any concerns regarding manufacturing defects should be directed to the original manufacturer.',
				),
			),
		),
		'refund-shipping-policy' => array(
			'slug'         => 'refund-shipping-policy',
			'title'        => 'Refund & Shipping Policy',
			'last_updated' => 'July 23, 2026',
			'body'         => array(
				array(
					'type' => 'paragraph',
					'html' => 'We aim to make every order fast, accurate, and predictable. Please read this policy carefully before purchasing — by placing an order with PepSelect you agree to the terms below.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Shipping',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Orders placed before 10:00 AM ET, Monday through Thursday, ship the same day. Orders placed after 10:00 AM ET, Monday through Thursday, ship the next day. Orders placed Friday through Sunday ship on Monday. Holidays can shift these times when carrier services are closed.',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Once your order ships, you will receive tracking information by email. Delivery estimates begin when the carrier takes possession of the package; transit times, delays, and final delivery are the responsibility of the carrier and are outside our control. We currently ship within the United States only.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Cancellations & Refunds Before Shipment',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Refunds may be requested only while your order has not yet shipped.',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Cancellation requests must be submitted by email before your order ships.',
						'If a shipping label has already been created, shipping fees are non-refundable.',
						'Unpaid orders are automatically canceled 90 minutes after placement.',
					),
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'After Shipment — All Sales Final',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Due to the sensitive nature of research compounds, all sales are final once an order has shipped. We do not accept returns or offer cancellations for orders in transit, and we cannot resell returned product. Carrier delays do not qualify for refunds.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Damaged or Incorrect Orders',
				),
				array(
					'type' => 'paragraph',
					'html' => 'If your order arrives damaged or incorrect, we will make it right. Contact us within 72 hours of delivery and include:',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Your order number',
						'Clear photos of the affected product(s) and the packaging',
					),
				),
				array(
					'type' => 'paragraph',
					'html' => 'Upon verification, we will offer a replacement shipment at no cost, a refund, or store credit, at our discretion. Requests submitted outside the 72-hour window may not be eligible.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Packages Marked as Delivered',
				),
				array(
					'type' => 'paragraph',
					'html' => 'If tracking shows a package was marked “Delivered,” responsibility transfers to the carrier and recipient. If you believe a delivered package is missing, first contact your local carrier or post office to open an inquiry. Proof of carrier contact is required before we can consider a reshipment; without carrier confirmation, reshipments cannot be issued. If tracking confirms a package was never delivered, contact us and we will investigate and work with you to resolve it.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Not Eligible for Refund or Replacement',
				),
				array(
					'type'    => 'list',
					'ordered' => false,
					'items'   => array(
						'Orders that have already shipped',
						'Shipping fees once a label has been created',
						'Incorrect shipping addresses entered at checkout',
						'Change of mind after ordering',
						'Packages marked as Delivered by the carrier (without carrier confirmation of loss)',
						'Carrier delays or mishandling after carrier acceptance',
						'Issues arising from misuse, improper storage, or misapplication of research materials',
					),
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Refund Processing',
				),
				array(
					'type' => 'paragraph',
					'html' => 'Approved refunds are issued to the original payment method within 5–10 business days. Shipping costs are non-refundable unless the issue was our error.',
				),
				array(
					'type'  => 'heading',
					'level' => 2,
					'text'  => 'Contact Us',
				),
				array(
					'type' => 'paragraph',
					'html' => 'If you believe your order qualifies for review, reach out to [support@pepselect.com]. We review every request case by case and will always do our best to help when eligibility criteria are met.',
				),
			),
		),
	);
}

/**
 * Return one legal document by slug, or null when the slug is not ours.
 *
 * @param string $slug Page slug.
 * @return array<string,mixed>|null
 */
function pepselect_child_get_legal_document( $slug ) {
	$documents = pepselect_child_get_legal_documents();

	return isset( $documents[ $slug ] ) ? $documents[ $slug ] : null;
}
