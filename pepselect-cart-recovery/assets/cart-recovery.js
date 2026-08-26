(function () {
  'use strict';

  if (!window.pepSelectRecovery) return;

  var config = window.pepSelectRecovery;
  var root = document.querySelector('[data-pep-exit-offer]');
  if (!root) return;

  var form = root.querySelector('[data-pep-exit-form]');
  var message = root.querySelector('[data-pep-exit-message]');
  var success = root.querySelector('[data-pep-exit-success]');
  var codeButton = root.querySelector('[data-pep-exit-code]');
  var emailInput = root.querySelector('input[name="email"]');
  var opened = false;
  var eligible = false;
  var mobileReady = false;
  var storageKey = 'pep_exit_offer_state';

  function event(name, detail) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push(Object.assign({ event: name }, detail || {}));
  }

  function state() {
    try { return JSON.parse(localStorage.getItem(storageKey) || '{}'); } catch (error) { return {}; }
  }

  function saveState(value) {
    try { localStorage.setItem(storageKey, JSON.stringify(value)); } catch (error) { /* Storage is optional. */ }
  }

  function isSuppressed() {
    var saved = state();
    return saved.until && Date.now() < saved.until;
  }

  function suppress(days, status) {
    saveState({ status: status, until: Date.now() + days * 86400000 });
  }

  function open() {
    if (opened || !eligible || isSuppressed()) return;
    opened = true;
    root.hidden = false;
    requestAnimationFrame(function () { root.classList.add('is-open'); });
    document.documentElement.classList.add('pep-exit-offer-open');
    emailInput.focus({ preventScroll: true });
    event('pep_exit_offer_view');
  }

  function close() {
    root.classList.remove('is-open');
    document.documentElement.classList.remove('pep-exit-offer-open');
    suppress(Number(config.dismissDays || 30), 'dismissed');
    event('pep_exit_offer_dismiss');
    window.setTimeout(function () { root.hidden = true; }, 180);
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

  document.addEventListener('mouseout', function (eventObject) {
    if (eventObject.clientY <= 8 && !eventObject.relatedTarget) open();
  });

  window.setTimeout(function () {
    eligible = true;
    event('pep_exit_offer_eligible');
  }, 15000);

  if (window.matchMedia('(pointer: coarse)').matches) {
    window.setTimeout(function () { mobileReady = true; }, 45000);
    window.addEventListener('scroll', function () {
      var available = document.documentElement.scrollHeight - window.innerHeight;
      if (mobileReady && available > 0 && window.scrollY / available > 0.55) open();
    }, { passive: true });
  }

  root.querySelectorAll('[data-pep-exit-close]').forEach(function (button) {
    button.addEventListener('click', close);
  });

  document.addEventListener('keydown', function (eventObject) {
    if (eventObject.key === 'Escape' && opened && !root.hidden) close();
  });

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
    button.textContent = 'Making your code…';
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
      codeButton.textContent = response.data.code;
      success.hidden = false;
      suppress(180, 'submitted');
      event('pep_exit_offer_success');
      if (response.data.hasCart) identifyCart(email);
    }).catch(function (error) {
      message.textContent = error.message;
      button.disabled = false;
      button.textContent = original;
    });
  });

  codeButton.addEventListener('click', function () {
    if (navigator.clipboard) navigator.clipboard.writeText(codeButton.textContent);
    codeButton.textContent = codeButton.textContent + ' · copied';
  }, { once: true });

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
