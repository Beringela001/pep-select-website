(function () {
  'use strict';

  if (!window.pepSelectRecovery) return;

  var config = window.pepSelectRecovery;
  var exitRoot = document.querySelector('[data-pep-popup="exit"]');
  var promoRoot = document.querySelector('[data-pep-popup="promo"]');
  var activeRoot = null;
  var previousFocus = null;
  var exitEligible = false;
  var pendingDesktopExit = false;
  var mobileReady = false;
  var coarsePointer = window.matchMedia('(pointer: coarse)').matches;
  var exitStorageKey = 'pep_exit_offer_state';
  var promoStorageKey = 'pep_promo_' + String(config.promo && config.promo.campaignId || 'campaign');

  function event(name, detail) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push(Object.assign({ event: name }, detail || {}));
  }

  function state(key) {
    try { return JSON.parse(localStorage.getItem(key) || '{}'); } catch (error) { return {}; }
  }

  function saveState(key, value) {
    try { localStorage.setItem(key, JSON.stringify(value)); } catch (error) { /* Storage is optional. */ }
  }

  function isSuppressed(key) {
    var saved = state(key);
    return saved.until && Date.now() < saved.until;
  }

  function suppress(key, days, status) {
    saveState(key, { status: status, until: Date.now() + Number(days || 1) * 86400000 });
  }

  function promoIsActive() {
    if (!config.promo || !config.promo.enabled) return false;
    var now = Math.floor(Date.now() / 1000);
    var start = Number(config.promo.start || 0);
    var end = Number(config.promo.end || 0);
    return Boolean(start && end && now >= start && now < end);
  }

  function focusable(root) {
    return Array.prototype.slice.call(root.querySelectorAll('a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])')).filter(function (element) {
      return element.offsetParent !== null;
    });
  }

  function openPopup(root, type) {
    if (!root || activeRoot || root.classList.contains('was-opened')) return;
    activeRoot = root;
    previousFocus = document.activeElement;
    root.classList.add('was-opened');
    root.hidden = false;
    requestAnimationFrame(function () { root.classList.add('is-open'); });
    document.documentElement.classList.add('pep-exit-offer-open');
    var targets = focusable(root);
    if (targets.length) targets[0].focus({ preventScroll: true });
    event(type === 'promo' ? 'pep_promo_view' : 'pep_exit_offer_view', type === 'promo' ? { campaign: config.promo.campaignId } : {});
  }

  function closePopup(root, type) {
    if (!root) return;
    root.classList.remove('is-open');
    document.documentElement.classList.remove('pep-exit-offer-open');
    if (type === 'promo') {
      suppress(promoStorageKey, config.promo.dismissDays, 'dismissed');
      event('pep_promo_dismiss', { campaign: config.promo.campaignId });
    } else {
      suppress(exitStorageKey, config.exit.dismissDays, 'dismissed');
      event('pep_exit_offer_dismiss');
    }
    activeRoot = null;
    window.setTimeout(function () { root.hidden = true; }, 180);
    if (previousFocus && typeof previousFocus.focus === 'function') previousFocus.focus({ preventScroll: true });
  }

  function post(data) {
    return fetch(config.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: new URLSearchParams(data).toString()
    }).then(function (response) { return response.json(); });
  }

  function identifyCart(email) {
    if (!email) return;
    post({
      action: 'cartflows_save_cart_abandonment_data',
      security: config.recoveryNonce,
      wcf_email: email,
      wcf_post_id: String(config.pageId || 0)
    }).then(function (response) {
      if (response && response.success) event('pep_cart_identified');
    }).catch(function () { /* Recovery plugin remains independently retryable at checkout. */ });
  }

  function exitIsBlocked() {
    return !config.exit || !config.exit.enabled || !exitRoot || isSuppressed(exitStorageKey) || (config.promo && config.promo.suppressExit && promoIsActive());
  }

  function tryExitOffer() {
    if (exitIsBlocked() || !exitEligible) return;
    openPopup(exitRoot, 'exit');
  }

  function desktopExit(eventObject) {
    if (coarsePointer || eventObject.relatedTarget || eventObject.clientY > 40 || exitIsBlocked()) return;
    if (!exitEligible) {
      pendingDesktopExit = true;
      return;
    }
    tryExitOffer();
  }

  if (exitRoot && config.exit && config.exit.enabled) {
    document.addEventListener('mouseout', desktopExit);
    document.documentElement.addEventListener('mouseleave', desktopExit);

    window.setTimeout(function () {
      exitEligible = true;
      event('pep_exit_offer_eligible');
      if (pendingDesktopExit) {
        pendingDesktopExit = false;
        tryExitOffer();
      }
    }, 15000);

    if (coarsePointer) {
      window.setTimeout(function () { mobileReady = true; }, 45000);
      window.addEventListener('scroll', function () {
        var available = document.documentElement.scrollHeight - window.innerHeight;
        if (mobileReady && available > 0 && window.scrollY / available > 0.55) tryExitOffer();
      }, { passive: true });
    }
  }

  if (promoRoot && promoIsActive() && !isSuppressed(promoStorageKey)) {
    window.setTimeout(function () {
      if (promoIsActive() && !isSuppressed(promoStorageKey)) openPopup(promoRoot, 'promo');
    }, Number(config.promo.delaySeconds || 0) * 1000);

	var millisecondsUntilEnd = Number(config.promo.end || 0) * 1000 - Date.now();
	if (millisecondsUntilEnd > 0 && millisecondsUntilEnd < 2147483647) {
	  window.setTimeout(function () {
		if (activeRoot === promoRoot) closePopup(promoRoot, 'promo');
		promoRoot.hidden = true;
	  }, millisecondsUntilEnd);
	}
  }

  document.querySelectorAll('[data-pep-popup]').forEach(function (root) {
    var type = root.getAttribute('data-pep-popup');
    root.querySelectorAll(type === 'exit' ? '[data-pep-exit-close]' : '[data-pep-popup-close]').forEach(function (button) {
      button.addEventListener('click', function () { closePopup(root, type); });
    });
  });

  document.addEventListener('keydown', function (eventObject) {
    if (!activeRoot) return;
    if (eventObject.key === 'Escape') {
      closePopup(activeRoot, activeRoot.getAttribute('data-pep-popup'));
      return;
    }
    if (eventObject.key !== 'Tab') return;
    var targets = focusable(activeRoot);
    if (!targets.length) return;
    var first = targets[0];
    var last = targets[targets.length - 1];
    if (eventObject.shiftKey && document.activeElement === first) {
      eventObject.preventDefault();
      last.focus();
    } else if (!eventObject.shiftKey && document.activeElement === last) {
      eventObject.preventDefault();
      first.focus();
    }
  });

  if (promoRoot) {
    var promoLink = promoRoot.querySelector('.pep-exit-offer__cta');
    if (promoLink) promoLink.addEventListener('click', function () {
      suppress(promoStorageKey, config.promo.dismissDays, 'clicked');
      event('pep_promo_click', { campaign: config.promo.campaignId });
    });
  }

  if (exitRoot) {
    var form = exitRoot.querySelector('[data-pep-exit-form]');
    var message = exitRoot.querySelector('[data-pep-exit-message]');
    var success = exitRoot.querySelector('[data-pep-exit-success]');
    var emailInput = exitRoot.querySelector('input[name="email"]');

    form.addEventListener('submit', function (eventObject) {
      eventObject.preventDefault();
      message.textContent = '';
      if (!emailInput.checkValidity()) {
        emailInput.reportValidity();
        return;
      }

      var button = form.querySelector('button[type="submit"]');
      var original = button.textContent;
      var trap = form.querySelector('input[name="company"]');
      button.disabled = true;
      button.textContent = config.exit.loadingText || 'Sending…';
      event('pep_exit_offer_submit');

      post({
        action: 'pepselect_capture_exit_offer',
        security: config.nonce,
        email: emailInput.value,
        company: trap.value
      }).then(function (response) {
        if (!response.success) throw new Error(response.data && response.data.message ? response.data.message : 'Please try again.');
        var email = emailInput.value;
        try { sessionStorage.setItem('pep_exit_offer_email', email); } catch (error) { /* Optional. */ }
        form.hidden = true;
        success.hidden = false;
        suppress(exitStorageKey, 180, 'submitted');
        event('pep_exit_offer_success');
        if (response.data.hasCart) identifyCart(email);
      }).catch(function (error) {
        message.textContent = error.message;
        button.disabled = false;
        button.textContent = original;
      });
    });
  }

  document.body.addEventListener('added_to_cart', function () {
    try { identifyCart(sessionStorage.getItem('pep_exit_offer_email')); } catch (error) { /* Optional. */ }
  });

  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', function () {
      try { identifyCart(sessionStorage.getItem('pep_exit_offer_email')); } catch (error) { /* Optional. */ }
    });
  }

  if (config.hasCart) {
    try { identifyCart(sessionStorage.getItem('pep_exit_offer_email')); } catch (error) { /* Optional. */ }
  }
}());
