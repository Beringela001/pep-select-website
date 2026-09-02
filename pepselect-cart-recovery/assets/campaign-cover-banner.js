(function () {
	'use strict';

	var banner = document.querySelector('[data-pep-campaign-cover]');
	if (!banner) return;

	var scheduledFrame = 0;

	function fitFirstScreen() {
		scheduledFrame = 0;
		var top = Math.max(0, banner.getBoundingClientRect().top + window.scrollY);
		var viewportHeight = window.visualViewport ? window.visualViewport.height : window.innerHeight;
		var available = Math.max(1, Math.round(viewportHeight - top));
		banner.style.setProperty('--pep-cover-available-height', available + 'px');
	}

	function scheduleFit() {
		if (scheduledFrame) window.cancelAnimationFrame(scheduledFrame);
		scheduledFrame = window.requestAnimationFrame(fitFirstScreen);
	}

	fitFirstScreen();
	window.requestAnimationFrame(fitFirstScreen);
	window.addEventListener('load', scheduleFit, { once: true });
	window.addEventListener('resize', scheduleFit, { passive: true });
	window.addEventListener('orientationchange', scheduleFit, { passive: true });
	if (window.visualViewport) window.visualViewport.addEventListener('resize', scheduleFit, { passive: true });

	if ('ResizeObserver' in window) {
		var header = document.querySelector('#pepselect-site-header, #site-header, header.site-header');
		if (header) new ResizeObserver(scheduleFit).observe(header);
	}
}());
