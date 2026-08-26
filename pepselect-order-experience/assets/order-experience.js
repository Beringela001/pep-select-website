(function () {
	'use strict';
	document.addEventListener('click', function (event) {
		var button = event.target.closest('[data-pepselect-copy]');
		if (!button) return;
		var code = button.getAttribute('data-pepselect-copy') || '';
		if (!code || !navigator.clipboard) return;
		navigator.clipboard.writeText(code).then(function () {
			var original = button.textContent;
			button.textContent = 'Copied';
			window.setTimeout(function () { button.textContent = original; }, 1400);
		});
	});
}());
