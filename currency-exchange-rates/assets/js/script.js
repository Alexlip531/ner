/**
 * Currency Exchange Rates — фронтенд-скрипты.
 *
 * @package CurrencyExchangeRates
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var wrappers = document.querySelectorAll('.cer-wrapper');
		if (!wrappers.length) {
			return;
		}

		// Лёгкая анимация появления.
		wrappers.forEach(function (wrapper) {
			wrapper.style.opacity = '0';
			wrapper.style.transition = 'opacity 0.3s ease';
			requestAnimationFrame(function () {
				wrapper.style.opacity = '1';
			});
		});

		// Подсветка строк таблицы при клике.
		var tableRows = document.querySelectorAll('.cer-table tbody tr');
		tableRows.forEach(function (row) {
			row.addEventListener('click', function () {
				row.style.transition = 'background 0.2s ease';
				row.style.background = '#eff6ff';
				setTimeout(function () {
					row.style.background = '';
				}, 600);
			});
		});
	});
})();
