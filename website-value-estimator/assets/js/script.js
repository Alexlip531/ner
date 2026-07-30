/**
 * Website Value Estimator — frontend JavaScript.
 * Version: 1.0.0
 */
(function () {
    'use strict';

    var form = document.getElementById('wve-form');
    var urlInput = document.getElementById('wve-url');
    var submitBtn = document.getElementById('wve-submit');
    var progress = document.getElementById('wve-progress');
    var progressFill = progress ? progress.querySelector('.wve-progress-fill') : null;
    var progressText = progress ? progress.querySelector('.wve-progress-text') : null;
    var steps = progress ? progress.querySelectorAll('.wve-step') : [];
    var resultContainer = document.getElementById('wve-result');
    var errorContainer = document.getElementById('wve-error');

    if (!form) {
        return;
    }

    var startTime = 0;
    var progressTimer = null;
    var stepTimer = null;

    /**
     * Валидация URL (базовая, на клиенте).
     */
    function isValidUrl(url) {
        if (!url) return false;
        // Удаляем пробелы.
        url = url.trim();
        if (url.length < 3) return false;
        // Если есть схема — проверяем.
        if (/^https?:\/\//i.test(url)) {
            try {
                new URL(url);
                return true;
            } catch (e) {
                return false;
            }
        }
        // Без схемы — проверяем, что это похоже на домен.
        return /^([a-z0-9\-]+\.)+[a-z]{2,}(\/.*)?$/i.test(url);
    }

    /**
     * Старт анимации прогресса.
     */
    function startProgress() {
        if (!progress) return;
        progress.hidden = false;
        resultContainer.hidden = true;
        errorContainer.hidden = true;

        // Сброс шагов.
        steps.forEach(function (s) {
            s.classList.remove('is-active', 'is-done');
        });

        var progressValue = 5;
        var stepIndex = 0;

        if (steps.length > 0) {
            steps[0].classList.add('is-active');
        }

        progressTimer = setInterval(function () {
            // Плавный рост прогресса, замедляется к концу.
            var remaining = 95 - progressValue;
            progressValue += Math.max(0.5, remaining * 0.04);
            if (progressFill) {
                progressFill.style.width = Math.min(95, progressValue) + '%';
            }
        }, 200);

        stepTimer = setInterval(function () {
            if (stepIndex >= steps.length) {
                clearInterval(stepTimer);
                return;
            }
            // Отмечаем текущий как done, активируем следующий.
            if (stepIndex > 0) {
                steps[stepIndex - 1].classList.remove('is-active');
                steps[stepIndex - 1].classList.add('is-done');
            }
            steps[stepIndex].classList.add('is-active');
            if (progressText) {
                progressText.textContent = steps[stepIndex].querySelector('.wve-step-text').textContent + '...';
            }
            stepIndex++;
        }, 4500);
    }

    /**
     * Остановить прогресс.
     */
    function stopProgress(success) {
        if (progressTimer) {
            clearInterval(progressTimer);
            progressTimer = null;
        }
        if (stepTimer) {
            clearInterval(stepTimer);
            stepTimer = null;
        }
        if (success) {
            // Все шаги done.
            steps.forEach(function (s) {
                s.classList.remove('is-active');
                s.classList.add('is-done');
            });
            if (progressFill) {
                progressFill.style.width = '100%';
            }
            // Скрываем прогресс через короткую задержку.
            setTimeout(function () {
                if (progress) progress.hidden = true;
            }, 400);
        } else {
            if (progress) progress.hidden = true;
        }
    }

    /**
     * Показать ошибку.
     */
    function showError(message) {
        if (errorContainer) {
            errorContainer.textContent = message || wve_data.i18n.error;
            errorContainer.hidden = false;
        }
    }

    /**
     * Отправка запроса.
     */
    function submitForm(e) {
        if (e) e.preventDefault();

        var url = urlInput.value.trim();
        if (!isValidUrl(url)) {
            showError(wve_data.i18n.invalid_url);
            return;
        }

        // Блокируем кнопку.
        submitBtn.classList.add('is-loading');
        submitBtn.disabled = true;

        startProgress();

        var formData = new FormData();
        formData.append('action', 'wve_estimate');
        formData.append('nonce', wve_data.nonce);
        formData.append('url', url);

        fetch(wve_data.ajax_url, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(function (data) {
            stopProgress(true);

            if (data.success) {
                if (resultContainer) {
                    resultContainer.innerHTML = data.data.html;
                    resultContainer.hidden = false;
                    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                attachResultHandlers();
            } else {
                var msg = (data.data && data.data.message) ? data.data.message : wve_data.i18n.error;
                showError(msg);
            }
        })
        .catch(function (err) {
            stopProgress(false);
            showError(wve_data.i18n.error);
            console.error('WVE Error:', err);
        })
        .finally(function () {
            submitBtn.classList.remove('is-loading');
            submitBtn.disabled = false;
        });
    }

    /**
     * Обработчики внутри результата.
     */
    function attachResultHandlers() {
        var newBtn = document.getElementById('wve-new-estimate');
        var shareBtn = document.getElementById('wve-share-result');

        if (newBtn) {
            newBtn.addEventListener('click', function () {
                if (resultContainer) {
                    resultContainer.hidden = true;
                    resultContainer.innerHTML = '';
                }
                if (urlInput) {
                    urlInput.value = '';
                    urlInput.focus();
                }
                window.scrollTo({ top: form.offsetTop - 20, behavior: 'smooth' });
            });
        }

        if (shareBtn) {
            shareBtn.addEventListener('click', function () {
                var value = '';
                var amountEl = resultContainer.querySelector('.wve-value-number');
                if (amountEl) value = amountEl.textContent;
                var site = urlInput.value.trim();

                var text = 'Стоимость сайта ' + site + ' — ' + value + ' ' + wve_data.currency;

                if (navigator.share) {
                    navigator.share({
                        title: 'Оценка стоимости сайта',
                        text: text,
                        url: window.location.href
                    }).catch(function () {});
                } else if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(function () {
                        shareBtn.textContent = '✓ Скопировано';
                        setTimeout(function () {
                            shareBtn.textContent = 'Поделиться';
                        }, 2000);
                    });
                }
            });
        }
    }

    form.addEventListener('submit', submitForm);

    // Enter в поле ввода.
    if (urlInput) {
        urlInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitForm(e);
            }
        });

        // Автоматически убираем протокол для красоты.
        urlInput.addEventListener('blur', function () {
            var v = urlInput.value.trim();
            if (v && !/^https?:\/\//i.test(v) && !/^([a-z0-9\-]+\.)+[a-z]{2,}/i.test(v)) {
                urlInput.value = v.replace(/^www\./i, '');
            }
        });
    }
})();
