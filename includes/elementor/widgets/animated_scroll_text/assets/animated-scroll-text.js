(function ($) {
	'use strict';

	function splitWords(el, allWords) {
		var blockEls = el.querySelectorAll('p, h1, h2, h3, h4, h5, h6, li, td, th');
		var targets  = blockEls.length ? blockEls : [el];

		targets.forEach(function (node) {
			if (!node.textContent.trim()) return;

			var html = node.innerHTML;

			// Normalize <strong> → <b> so bold detection works uniformly
			html = html.replace(/<strong>/gi, '<b>').replace(/<\/strong>/gi, '</b>');

			// Split on <b>…</b> blocks (treated as one highlighted token) and individual words
			var parts = html.split(/(<b>[\s\S]*?<\/b>|\S+)/g);

			node.innerHTML = parts.map(function (s) {
				if (!s || !s.trim()) return s || '';
				var isBold = /^<b>/i.test(s);
				var text   = s.replace(/<\/?b>/gi, '');
				if (!text.trim()) return '';
				var cls = 'hb-ast-word' + (isBold ? ' hb-ast-hl' : '');
				return '<span class="' + cls + '">' + text + '</span>';
			}).join('');
		});

		el.querySelectorAll('.hb-ast-word').forEach(function (w) {
			allWords.push(w);
		});
	}

	function init(el) {
		if (!el || el.dataset.astInit) return;
		el.dataset.astInit = '1';

		if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
			return;
		}

		var start = el.dataset.scrollStart || 'top 75%';
		var end   = el.dataset.scrollEnd   || 'bottom 60%';
		var scrub = parseFloat(el.dataset.scrub) || 0.4;
		var words = [];

		splitWords(el, words);
		if (!words.length) return;

		gsap.registerPlugin(ScrollTrigger);

		ScrollTrigger.create({
			trigger : el,
			start   : start,
			end     : end,
			scrub   : scrub,
			onUpdate: function (st) {
				var n = Math.floor(st.progress * words.length);
				words.forEach(function (w, i) {
					w.classList.toggle('on', i <= n);
				});
			}
		});
	}

	// Elementor frontend (editor preview + live page)
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/animated_scroll_text.default',
			function ($scope) {
				init($scope[0].querySelector('.hb-animated-scroll-text'));
			}
		);
	});

	// Fallback: page load without Elementor frontend (e.g. plain HTML embed)
	$(document).ready(function () {
		if (typeof elementorFrontend === 'undefined') {
			document.querySelectorAll('.hb-animated-scroll-text').forEach(init);
		}
	});

}(jQuery));
