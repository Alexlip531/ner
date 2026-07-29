/**
 * Zabota Ryadom — JavaScript страницы каталога.
 *
 * @package ZabotaRyadom
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		// ====== Двойной range slider для цены ======
		var rangeLower = document.getElementById('rangeLower');
		var rangeUpper = document.getElementById('rangeUpper');
		var rangeFill  = document.getElementById('rangeFill');
		var priceFrom  = document.getElementById('priceFrom');
		var priceTo    = document.getElementById('priceTo');

		function formatPrice(n) {
			return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
		}

		function parsePrice(s) {
			return parseInt(String(s).replace(/\s/g, ''), 10) || 0;
		}

		function updateRangeFill() {
			if (!rangeLower || !rangeUpper || !rangeFill) { return; }
			var min = parseInt(rangeLower.min, 10);
			var max = parseInt(rangeLower.max, 10);
			var lo  = parseInt(rangeLower.value, 10);
			var hi  = parseInt(rangeUpper.value, 10);

			// Не даём ползункам пересекаться.
			if (lo > hi - 5000) {
				if (this === rangeLower) {
					rangeLower.value = hi - 5000;
					lo = hi - 5000;
				} else {
					rangeUpper.value = lo + 5000;
					hi = lo + 5000;
				}
			}

			var loPct = ((lo - min) / (max - min)) * 100;
			var hiPct = ((hi - min) / (max - min)) * 100;

			rangeFill.style.left  = loPct + '%';
			rangeFill.style.width = (hiPct - loPct) + '%';

			if (priceFrom) { priceFrom.value = formatPrice(lo); }
			if (priceTo)   { priceTo.value   = formatPrice(hi); }
		}

		if (rangeLower && rangeUpper) {
			updateRangeFill();
			rangeLower.addEventListener('input', updateRangeFill);
			rangeUpper.addEventListener('input', updateRangeFill);

			// Синхронизация инпутов с ползунками.
			if (priceFrom) {
				priceFrom.addEventListener('change', function () {
					var v = parsePrice(priceFrom.value);
					var min = parseInt(rangeLower.min, 10);
					var max = parseInt(rangeUpper.value, 10);
					v = Math.max(min, Math.min(v, max - 5000));
					rangeLower.value = v;
					priceFrom.value = formatPrice(v);
					updateRangeFill();
				});
			}
			if (priceTo) {
				priceTo.addEventListener('change', function () {
					var v = parsePrice(priceTo.value);
					var max = parseInt(rangeUpper.max, 10);
					var min = parseInt(rangeLower.value, 10);
					v = Math.min(max, Math.max(v, min + 5000));
					rangeUpper.value = v;
					priceTo.value = formatPrice(v);
					updateRangeFill();
				});
			}
		}

		// ====== Удаление chips ======
		document.querySelectorAll('.chip-remove').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var chip = btn.closest('.chip');
				if (chip) {
					chip.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
					chip.style.opacity = '0';
					chip.style.transform = 'scale(0.9)';
					setTimeout(function () { chip.remove(); }, 200);
				}
			});
		});

		// ====== Сбросить всё ======
		document.querySelectorAll('.chips-reset, .filters-reset').forEach(function (link) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				document.querySelectorAll('.chip').forEach(function (chip) {
					chip.remove();
				});
				document.querySelectorAll('.filter-checkboxes input[type="checkbox"]').forEach(function (cb) {
					cb.checked = false;
				});
				if (rangeLower && rangeUpper) {
					var min = parseInt(rangeLower.min, 10);
					var max = parseInt(rangeUpper.max, 10);
					rangeLower.value = min;
					rangeUpper.value = max;
					updateRangeFill();
				}
			});
		});

		// ====== Переключатель grid/list ======
		var viewBtns = document.querySelectorAll('.view-btn');
		var list = document.querySelector('.catalog-list');
		viewBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				viewBtns.forEach(function (b) { b.classList.remove('active'); });
				btn.classList.add('active');
				if (!list) { return; }
				if (btn.dataset.view === 'grid') {
					list.classList.add('is-grid');
				} else {
					list.classList.remove('is-grid');
				}
			});
		});

		// ====== Избранное: главная кнопка ======
		document.querySelectorAll('.plc-fav-btn').forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				btn.classList.toggle('is-fav');
				var path = btn.querySelector('svg path');
				if (path) {
					if (btn.classList.contains('is-fav')) {
						path.setAttribute('fill', '#EF4444');
						path.setAttribute('stroke', '#EF4444');
					} else {
						path.setAttribute('fill', 'none');
						path.setAttribute('stroke', 'white');
					}
				}
			});
		});

		// ====== Избранное: текстовая кнопка ======
		document.querySelectorAll('.plc-fav-toggle').forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				btn.classList.toggle('is-fav');
				var card = btn.closest('.pension-list-card');
				if (card) {
					var mainFav = card.querySelector('.plc-fav-btn');
					if (mainFav) {
						if (btn.classList.contains('is-fav')) {
							mainFav.classList.add('is-fav');
							var path = mainFav.querySelector('svg path');
							if (path) {
								path.setAttribute('fill', '#EF4444');
								path.setAttribute('stroke', '#EF4444');
							}
						} else {
							mainFav.classList.remove('is-fav');
							var p2 = mainFav.querySelector('svg path');
							if (p2) {
								p2.setAttribute('fill', 'none');
								p2.setAttribute('stroke', 'white');
							}
						}
					}
				}
			});
		});

		// ====== Кнопка "Показать ещё" в фильтрах (просто визуальный toggle) ======
		var showMore = document.querySelector('.filters-show-more');
		if (showMore) {
			var extraOpen = false;
			showMore.addEventListener('click', function () {
				extraOpen = !extraOpen;
				showMore.querySelector('svg').style.transform = extraOpen ? 'rotate(180deg)' : 'rotate(0)';
			});
		}

		// ====== Мобильный toggle фильтров ======
		var filtersToggle = document.querySelector('.filters-toggle-mobile');
		var sidebar = document.getElementById('catalogFilters');
		if (filtersToggle && sidebar) {
			filtersToggle.addEventListener('click', function () {
				sidebar.classList.toggle('open');
			});
		}

	});
})();
