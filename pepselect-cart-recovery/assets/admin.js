(function () {
  'use strict';

  document.addEventListener('click', function (event) {
    var button = event.target.closest('[data-pep-media-target]');
    if (!button || !window.wp || !wp.media) return;

    event.preventDefault();
    var target = document.getElementById(button.getAttribute('data-pep-media-target'));
    if (!target) return;

    var frame = wp.media({
      title: 'Choose popup background',
      button: { text: 'Use this image' },
      library: { type: 'image' },
      multiple: false
    });

    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      target.value = attachment.url || '';
      target.dispatchEvent(new Event('change', { bubbles: true }));
    });

    frame.open();
  });
}());
