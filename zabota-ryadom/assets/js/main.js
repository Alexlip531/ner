/**
 * Zabota Ryadom — основной JavaScript.
 *
 * @package ZabotaRyadom
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		// ====== Мобильное меню ======
		var menuToggle = document.querySelector('.menu-toggle');
		var mainNav = document.querySelector('.main-nav');

		if (menuToggle && mainNav) {
			menuToggle.addEventListener('click', function () {
				mainNav.classList.toggle('open');
				var expanded = mainNav.classList.contains('open');
				menuToggle.setAttribute('aria-expanded', expanded);
			});
		}

		// ====== Табы в hero ======
		var tabs = document.querySelectorAll('.hero-tab');
		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				tabs.forEach(function (t) {
					t.classList.remove('active');
					t.setAttribute('aria-selected', 'false');
				});
				tab.classList.add('active');
				tab.setAttribute('aria-selected', 'true');
			});
		});

		// ====== Калькулятор: ползунок бюджета ======
		var slider = document.getElementById('calcBudget');
		if (slider) {
			var budgetValueEl = document.querySelector('.calc-budget-value strong');
			var resultValueEl = document.querySelector('.calc-result-value strong');

			function formatNumber(n) {
				return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
			}

			slider.addEventListener('input', function () {
				var val = parseInt(slider.value, 10);
				var formatted = formatNumber(val) + ' ₽';
				if (budgetValueEl) { budgetValueEl.textContent = formatted; }
				if (resultValueEl) { resultValueEl.textContent = 'от ' + formatted; }
			});

			// Раскрашиваем дорожку слайдера.
			function updateSliderTrack() {
				var min = parseInt(slider.min, 10);
				var max = parseInt(slider.max, 10);
				var val = parseInt(slider.value, 10);
				var pct = ((val - min) / (max - min)) * 100;
				slider.style.background =
					'linear-gradient(to right, #10B981 0%, #10B981 ' + pct + '%, #E5E7EB ' + pct + '%, #E5E7EB 100%)';
			}
			updateSliderTrack();
			slider.addEventListener('input', updateSliderTrack);
		}

		// ====== Кнопка "Наверх" ======
		var scrollTopBtn = document.querySelector('.scroll-top');
		if (scrollTopBtn) {
			window.addEventListener('scroll', function () {
				if (window.scrollY > 400) {
					scrollTopBtn.classList.add('visible');
				} else {
					scrollTopBtn.classList.remove('visible');
				}
			});

			scrollTopBtn.addEventListener('click', function () {
				window.scrollTo({ top: 0, behavior: 'smooth' });
			});
		}

		// ====== Анимация избранных карточек ======
		var favButtons = document.querySelectorAll('.pension-fav, .pension-icon-btn');
		favButtons.forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				btn.classList.toggle('is-favorite');

				// Если у кнопки есть SVG с обводкой heart — переключаем на заливку.
				var svg = btn.querySelector('svg path');
				if (svg) {
					var isFilled = svg.getAttribute('fill') === '#EF4444' || svg.getAttribute('fill') === '#10B981';
					if (btn.classList.contains('is-favorite')) {
						svg.setAttribute('fill', '#EF4444');
						svg.setAttribute('stroke', '#EF4444');
					} else {
						svg.setAttribute('fill', 'none');
						svg.setAttribute('stroke', btn.classList.contains('pension-icon-btn-primary') ? 'white' : '#10B981');
					}
				}
			});
		});

		// ====== Плавное появление карточек при скролле ======
		if ('IntersectionObserver' in window) {
			var observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('in-view');
						observer.unobserve(entry.target);
					}
				});
			}, { threshold: 0.1 });

			document.querySelectorAll('.service-card, .situation-card, .pension-card, .trust-item').forEach(function (el) {
				observer.observe(el);
			});
		}

		// ====== Стилизованные select (для визуального обновления) ======
		var selects = document.querySelectorAll('.calc-select, .hero-search-select select');
		selects.forEach(function (sel) {
			sel.addEventListener('change', function () {
				if (sel.classList.contains('calc-select')) {
					// Пересчёт стоимости в калькуляторе при изменении опций.
					recalculateCost();
				}
			});
		});

		function recalculateCost() {
			if (!slider) { return; }
			var base = parseInt(slider.value, 10);
			var typeSelect = document.querySelectorAll('.calc-select')[1];
			var addSelect = document.querySelectorAll('.calc-select')[2];

			// Базовые коэффициенты.
			var multiplier = 1;
			if (typeSelect && typeSelect.value !== 'Самостоятельный') {
				multiplier += 0.2;
			}
			if (addSelect && addSelect.value !== 'Без особенностей') {
				multiplier += 0.15;
			}

			var total = Math.round(base * multiplier / 1000) * 1000;
			var resultValueEl = document.querySelector('.calc-result-value strong');
			if (resultValueEl) {
				resultValueEl.textContent = 'от ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽';
			}
		}

	});
})();
