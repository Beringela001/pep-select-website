(function () {
  'use strict';

  var optionPrefix = 'pepselect_cart_recovery_settings[';
  var adminConfig = window.pepSelectPopupAdmin || {};

  function setting(key) {
    return document.querySelector('[data-pep-setting="' + key + '"]');
  }

  function value(key) {
    var field = setting(key);
    if (!field) return '';
    return field.type === 'checkbox' ? field.checked : field.value;
  }

  function discountLabel() {
    var amount = String(value('discount_amount') || '0').replace(/\.00$/, '');
    return value('discount_type') === 'fixed_cart' ? '$' + amount : amount + '%';
  }

  function tokens(text) {
    return String(text || '')
      .replaceAll('{discount}', discountLabel() || adminConfig.discount || '')
      .replaceAll('{days}', String(value('coupon_expiry_days') || adminConfig.days || ''))
      .replaceAll('{support_email}', adminConfig.support || 'support@pepselect.com');
  }

  function hexToRgba(hex, opacity) {
    var clean = String(hex || '#001D3A').replace('#', '');
    if (clean.length === 3) clean = clean.split('').map(function (character) { return character + character; }).join('');
    var number = parseInt(clean, 16);
    var alpha = Math.max(0, Math.min(1, Number(opacity || 0)));
    return 'rgba(' + ((number >> 16) & 255) + ',' + ((number >> 8) & 255) + ',' + (number & 255) + ',' + alpha + ')';
  }

  function updateStatus(key) {
    document.querySelectorAll('[data-pep-status="' + key + '"]').forEach(function (status) {
      var enabled = Boolean(value(key));
      status.textContent = enabled ? 'On' : 'Off';
      status.classList.toggle('is-on', enabled);
    });
  }

  function updatePreview(prefix) {
    var preview = document.querySelector('[data-pep-preview="' + prefix + '"]');
    if (!preview) return;

    preview.querySelectorAll('[data-pep-preview-bind]').forEach(function (element) {
      var key = element.getAttribute('data-pep-preview-bind');
      element.textContent = tokens(value(key));
    });
    preview.querySelectorAll('[data-pep-preview-placeholder]').forEach(function (element) {
      element.setAttribute('placeholder', tokens(value(element.getAttribute('data-pep-preview-placeholder'))));
    });

    var image = value(prefix + '_card_image');
    preview.style.setProperty('--pep-offer-overlay', hexToRgba(value(prefix + '_overlay_color'), value(prefix + '_overlay_opacity')));
    preview.style.setProperty('--pep-offer-card-color', value(prefix + '_card_color'));
    preview.style.setProperty('--pep-offer-card-image', image ? 'url("' + image.replaceAll('"', '') + '")' : 'none');
    preview.style.setProperty('--pep-offer-card-tint', image ? hexToRgba(value(prefix + '_card_tint_color'), value(prefix + '_card_tint_opacity')) : 'rgba(0,0,0,0)');
    preview.style.setProperty('--pep-offer-text', value(prefix + '_text_color'));
    preview.style.setProperty('--pep-offer-muted', value(prefix + '_muted_color'));
    preview.style.setProperty('--pep-offer-accent', value(prefix + '_accent_color'));
    preview.style.setProperty('--pep-offer-button', value(prefix + '_button_color'));
    preview.style.setProperty('--pep-offer-button-text', value(prefix + '_button_text_color'));

    var code = preview.querySelector('[data-pep-preview-code]');
    if (code) code.hidden = !String(value('promo_code') || '').trim();
    var campaignButton = preview.querySelector('[data-pep-preview-bind="promo_button"]');
    if (campaignButton) {
      campaignButton.hidden = !String(value('promo_button') || '').trim();
      campaignButton.style.display = campaignButton.hidden ? 'none' : '';
    }
  }

  function updateCoverPreview() {
    var preview = document.querySelector('[data-pep-preview="cover_banner"]');
    if (!preview) return;

    preview.querySelectorAll('[data-pep-preview-bind]').forEach(function (element) {
      element.textContent = value(element.getAttribute('data-pep-preview-bind'));
    });

    var mobile = preview.classList.contains('is-mobile');
    var desktopImage = String(value('cover_banner_desktop_image') || '');
    var mobileImage = String(value('cover_banner_mobile_image') || '');
    var imageUrl = mobile && mobileImage ? mobileImage : desktopImage || mobileImage;
    var image = preview.querySelector('[data-pep-cover-preview-image]');
    if (image) {
      image.hidden = !imageUrl;
      image.src = imageUrl;
    }

    preview.style.setProperty('--pep-cover-preview-bg', value('cover_banner_background'));
    preview.style.setProperty('--pep-cover-preview-overlay', value('cover_banner_overlay'));
    preview.style.setProperty('--pep-cover-preview-opacity', value('cover_banner_overlay_opacity'));
    preview.style.setProperty('--pep-cover-preview-text', value('cover_banner_text_color'));
    preview.style.setProperty('--pep-cover-preview-x', String(value('cover_banner_focal_x') || 50) + '%');
    preview.style.setProperty('--pep-cover-preview-y', String(value('cover_banner_focal_y') || 50) + '%');
	preview.classList.toggle('is-fit-contain', value('cover_banner_display_mode') === 'contain');
  }

  function updateAll() {
    updatePreview('exit');
    updatePreview('promo');
    updateCoverPreview();
    updateStatus('enabled');
    updateStatus('promo_enabled');
    updateStatus('cover_banner_enabled');
  }

  function activateTab(name) {
    document.querySelectorAll('[data-pep-tab]').forEach(function (tab) {
      var active = tab.getAttribute('data-pep-tab') === name;
      tab.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    document.querySelectorAll('[data-pep-panel]').forEach(function (panel) {
      var active = panel.getAttribute('data-pep-panel') === name;
      panel.hidden = !active;
      panel.classList.toggle('is-active', active);
    });
    try { window.localStorage.setItem('pep_popup_admin_tab', name); } catch (error) { /* Optional. */ }
  }

  document.addEventListener('click', function (event) {
    var tab = event.target.closest('[data-pep-tab]');
    if (tab) {
      event.preventDefault();
      activateTab(tab.getAttribute('data-pep-tab'));
      return;
    }

	var device = event.target.closest('[data-pep-device]');
    if (device) {
      event.preventDefault();
      var preview = device.closest('[data-pep-preview]');
      var mobile = device.getAttribute('data-pep-device') === 'mobile';
      preview.classList.toggle('is-mobile', mobile);
      preview.querySelectorAll('[data-pep-device]').forEach(function (button) {
        var active = button === device;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
      });
		updateCoverPreview();
		return;
	}

	var view = event.target.closest('[data-pep-view]');
	if (view) {
		event.preventDefault();
		var viewPreview = view.closest('[data-pep-preview]');
		var viewName = view.getAttribute('data-pep-view');
		viewPreview.querySelectorAll('[data-pep-preview-screen]').forEach(function (screen) {
			screen.hidden = screen.getAttribute('data-pep-preview-screen') !== viewName;
		});
		viewPreview.querySelectorAll('[data-pep-view]').forEach(function (button) {
			var active = button === view;
			button.classList.toggle('is-active', active);
			button.setAttribute('aria-pressed', active ? 'true' : 'false');
		});
		return;
	}

    var mediaButton = event.target.closest('[data-pep-media-target]');
    if (!mediaButton || !window.wp || !wp.media) return;
    event.preventDefault();
    var target = document.getElementById(mediaButton.getAttribute('data-pep-media-target'));
    if (!target) return;
    var frame = wp.media({ title: target.id.indexOf('cover_banner') !== -1 ? 'Choose campaign banner image' : 'Choose popup background', button: { text: 'Use this image' }, library: { type: 'image' }, multiple: false });
    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      target.value = attachment.url || '';
      target.dispatchEvent(new Event('input', { bubbles: true }));
    });
    frame.open();
  });

  document.addEventListener('input', function (event) {
    if (event.target.name && event.target.name.indexOf(optionPrefix) === 0) updateAll();
  });
  document.addEventListener('change', function (event) {
    if (event.target.name && event.target.name.indexOf(optionPrefix) === 0) updateAll();
  });

  var initialTab = 'exit';
  try { initialTab = window.localStorage.getItem('pep_popup_admin_tab') || initialTab; } catch (error) { /* Optional. */ }
  activateTab(['exit', 'promo', 'cover_banner'].indexOf(initialTab) !== -1 ? initialTab : 'exit');
  updateAll();
}());
