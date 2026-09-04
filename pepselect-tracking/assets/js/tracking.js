(function () {
	'use strict';

	var config = window.pepselectTrackingConfig || {};
	var consent = { analytics: false, marketing: false };
	var sent = Object.create(null);

	function log() {
		if (config.debug && window.console) {
			window.console.info.apply(window.console, ['Pep Select tracking:'].concat([].slice.call(arguments)));
		}
	}

	function blockedByPrivacyPreference() {
		return navigator.globalPrivacyControl === true || navigator.doNotTrack === '1';
	}

	function clean(value, maxLength) {
		return String(value || '').replace(/[^A-Za-z0-9._-]/g, '').slice(0, maxLength);
	}

	function attributionParams() {
		var source = config.attribution || {};
		var output = {};
		['source', 'medium', 'campaign', 'campaign_id', 'term', 'content'].forEach(function (key) {
			if (source[key]) output[key] = String(source[key]).slice(0, 160);
		});
		return output;
	}

	function siteKitOwns(eventName) {
		var events = window._googlesitekit && window._googlesitekit.wcdata && window._googlesitekit.wcdata.eventsToTrack;
		return Array.isArray(events) && events.indexOf(eventName) !== -1;
	}

	function gaEvent(eventName, params) {
		if (!consent.analytics || blockedByPrivacyPreference() || siteKitOwns(eventName)) return;
		params = Object.assign({}, attributionParams(), params || {});
		if (window._googlesitekit && typeof window._googlesitekit.gtagEvent === 'function') {
			window._googlesitekit.gtagEvent(eventName, params);
		} else if (typeof window.gtag === 'function') {
			window.gtag('event', eventName, params);
		} else {
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push({ event: eventName, ecommerce: params });
		}
		log('GA4', eventName, params);
	}

	function loadMeta() {
		if (!consent.marketing || blockedByPrivacyPreference() || !config.metaPixelId || typeof window.fbq === 'function') return;
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
		window.fbq('init', config.metaPixelId);
		window.fbq('track', 'PageView');
	}

	function metaEvent(eventName, params, eventId) {
		if (!consent.marketing || blockedByPrivacyPreference() || !config.metaPixelId) return;
		loadMeta();
		if (typeof window.fbq === 'function') {
			window.fbq('track', eventName, params || {}, eventId ? { eventID: eventId } : {});
			log('Meta', eventName);
		}
	}

	function hidden(name, value) {
		var form = document.querySelector('form.checkout');
		if (!form) return;
		var field = form.querySelector('input[name="' + name + '"]');
		if (!field) {
			field = document.createElement('input');
			field.type = 'hidden';
			field.name = name;
			form.appendChild(field);
		}
		field.value = value;
	}

	function gaIdentifiers() {
		var values = document.cookie.split(';').map(function (part) { return part.trim(); });
		var ga = values.find(function (part) { return part.indexOf('_ga=') === 0; });
		var session = values.find(function (part) { return /^_ga_[A-Z0-9]+=/.test(part); });
		var clientId = ga ? ga.split('=').slice(1).join('=').replace(/^GA\d+\.\d+\./, '') : '';
		var sessionMatch = session && session.match(/GS\d+\.\d+\.s(\d+)/);
		return { clientId: clean(clientId, 80), sessionId: sessionMatch ? clean(sessionMatch[1], 40) : '' };
	}

	function syncCheckoutIdentifiers() {
		hidden('pepselect_analytics_consent', consent.analytics ? 'granted' : 'denied');
		hidden('pepselect_marketing_consent', consent.marketing ? 'granted' : 'denied');
		hidden('pepselect_ga_client_id', '');
		hidden('pepselect_ga_session_id', '');
		hidden('pepselect_fbp', '');
		hidden('pepselect_fbc', '');
		if (consent.analytics) {
			var ids = gaIdentifiers();
			hidden('pepselect_ga_client_id', ids.clientId);
			hidden('pepselect_ga_session_id', ids.sessionId);
		}
		if (consent.marketing) {
			var cookies = document.cookie.split(';').map(function (part) { return part.trim(); });
			var fbp = cookies.find(function (part) { return part.indexOf('_fbp=') === 0; });
			var fbc = cookies.find(function (part) { return part.indexOf('_fbc=') === 0; });
			hidden('pepselect_fbp', fbp ? clean(fbp.split('=').slice(1).join('='), 100) : '');
			hidden('pepselect_fbc', fbc ? clean(fbc.split('=').slice(1).join('='), 140) : '');
		}
	}

	function dedupe(key) {
		if (sent[key]) return false;
		try {
			if (window.sessionStorage.getItem('pepselect_event_' + key)) return false;
			window.sessionStorage.setItem('pepselect_event_' + key, '1');
		} catch (error) {}
		sent[key] = true;
		return true;
	}

	function fireBeginCheckout() {
		var cart = config.cart || {};
		if (!cart.items || !cart.items.length) return;
		var key = 'begin_checkout_' + (cart.cartHash || 'cart');
		if (!dedupe(key)) return;
		var payload = { currency: config.currency || 'USD', value: Number(cart.value || 0), items: cart.items };
		gaEvent('begin_checkout', payload);
		metaEvent('InitiateCheckout', { currency: payload.currency, value: payload.value, content_ids: cart.items.map(function (item) { return item.item_id; }), contents: cart.items, content_type: 'product' }, key);
	}

	function fireViewItem() {
		if (config.context !== 'product' || !config.product || !config.product.item_id || !dedupe('view_item_' + config.product.item_id)) return;
		var payload = { currency: config.currency || 'USD', value: Number(config.product.price || 0), items: [config.product] };
		gaEvent('view_item', payload);
		metaEvent('ViewContent', { currency: payload.currency, value: payload.value, content_ids: [config.product.item_id], content_type: 'product' });
	}

	function bindAddToCart() {
		document.addEventListener('click', function (event) {
			var button = event.target.closest('.add_to_cart_button, .single_add_to_cart_button');
			if (!button) return;
			var id = button.getAttribute('data-product_sku') || button.getAttribute('data-product_id') || (config.product && config.product.item_id) || '';
			if (!id) return;
			var item = config.product && String(config.product.item_id) === String(id) ? config.product : { item_id: id, item_name: button.getAttribute('aria-label') || 'Product', quantity: Number(button.getAttribute('data-quantity') || 1) };
			var key = 'add_to_cart_' + id + '_' + Date.now();
			gaEvent('add_to_cart', { currency: config.currency || 'USD', value: Number(item.price || 0) * Number(item.quantity || 1), items: [item] });
			metaEvent('AddToCart', { currency: config.currency || 'USD', value: Number(item.price || 0), content_ids: [id], content_type: 'product' }, key);
		});
	}

	function updateConsent(next) {
		consent.analytics = next && next.analytics === true;
		consent.marketing = next && next.marketing === true;
		syncCheckoutIdentifiers();
		if (!consent.marketing && typeof window.fbq === 'function') {
			window.fbq('consent', 'revoke');
		} else {
			loadMeta();
			if (typeof window.fbq === 'function') window.fbq('consent', 'grant');
		}
		fireViewItem();
		if (config.context === 'checkout') fireBeginCheckout();
	}

	window.PepSelectTrackingConsent = { update: updateConsent };
	window.addEventListener('pepselect:consent', function (event) { updateConsent(event.detail || {}); });
	if (window.jQuery) window.jQuery(document.body).on('updated_checkout', syncCheckoutIdentifiers);
	bindAddToCart();
	syncCheckoutIdentifiers();
}());
