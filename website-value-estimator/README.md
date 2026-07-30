# Website Value Estimator

Плагин оценки стоимости сайта для WordPress.

Пользователь вводит ссылку на сайт → плагин проверяет его через бесплатные сервисы → на основе показателей выводит расчётную стоимость с детальным отчётом.

## Возможности

- **Шорткод** `[site_value_calculator]` — форма на любой странице
- **AJAX-форма** с анимированным прогресс-баром (4 шага)
- **Анализ через бесплатные API**:
  - Google PageSpeed Insights (performance, accessibility, best-practices, SEO)
  - RDAP — данные о домене (возраст, регистратор, дата регистрации)
  - SSL-проверка (эмитент, срок действия)
  - HTML-парсинг (meta-теги, Open Graph, Schema.org, аналитика, реклама, соцсети)
- **Расчёт стоимости** по прозрачной формуле:
  - Базовая стоимость (из настроек)
  - + Бонусы за PageSpeed scores (4 категории)
  - + Бонус за возраст домена
  - + Бонус за SSL
  - + Бонусы за SEO-метатеги
  - + Бонусы за аналитику и монетизацию (GA, YM, AdSense, FB Pixel, VK Pixel)
  - + Бонус за соцсети
  - − Штрафы за медленный LCP, CLS, большой размер HTML, долгий ответ сервера
- **Web Vitals**: LCP, FCP, CLS, TBT с цветовой индикацией
- **Категории стоимости**: низкая / средняя / высокая / очень высокая / премиум
- **5 валют**: USD, EUR, RUB, UAH, KZT
- **История оценок** в БД + страница в админке
- **Rate limit**: 5 запросов/мин с одного IP
- **Кэширование** результатов (настраиваемое)
- **Тёмная тема** (по `prefers-color-scheme`)
- **Адаптивная вёрстка**

## Установка

1. Скопируйте папку `website-value-estimator` в `/wp-content/plugins/`
2. Активируйте плагин в админке WordPress
3. Откройте **Настройки → Оценка сайтов** для конфигурации
4. Создайте страницу и вставьте шорткод:

```
[site_value_calculator]
```

## Параметры шорткода

```
[site_value_calculator
   title="Оценка стоимости сайта"
   subtitle="Введите URL — мы покажем расчётную стоимость"
   placeholder="example.com"
   button_text="Оценить сайт"
   show_history="1"
   history_limit="5"
]
```

## Файлы

```
website-value-estimator/
├── website-value-estimator.php     — главный файл
├── uninstall.php                   — очистка при удалении
├── readme.txt                      — WordPress readme
├── README.md                       — этот файл
├── includes/
│   ├── class-settings.php          — настройки + история в админке
│   ├── class-api-client.php        — получение данных (PageSpeed, RDAP, SSL, HTML)
│   ├── class-estimator.php         — расчёт стоимости + breakdown
│   ├── class-shortcode.php         — шорткод формы
│   └── class-ajax.php              — AJAX-обработчик + рендер результата
├── assets/
│   ├── css/style.css               — стили (включая тёмную тему)
│   └── js/script.js                — AJAX + прогресс-бар
└── languages/                      — .pot файлы
```

## Используемые API

| Сервис | URL | Назначение | Лимиты |
|--------|-----|-----------|--------|
| Google PageSpeed Insights | `https://www.googleapis.com/pagespeedonline/v5/runPagespeed` | Performance, SEO, accessibility | Без ключа — мало, с ключом — 25000/день |
| RDAP | `https://rdap.org/domain/{domain}` | Возраст домена, регистратор | Без лимитов |
| Прямой HTTP | Запрос к URL сайта | HTML-анализ, мета-теги | Зависит от сайта |
| SSL | `stream_socket_client('ssl://...')` | Сертификат | Без лимитов |

## Формула расчёта

```
Стоимость = Базовая стоимость
          + Σ (балл PageSpeed × вес категории × базовая стоимость)
          + бонус за возраст домена (5%/год, макс 200%)
          + бонус за SSL (+10%, +2% если долгосрочный)
          + бонусы за SEO-метатеги (description +3%, OG +4%, Twitter +2%, Schema +5%)
          + бонусы за аналитику (GA +3%, YM +3%)
          + бонусы за монетизацию (AdSense +15%, FB Pixel +5%, VK Pixel +3%)
          + бонус за соцсети (+2% за каждую, макс 5)
          − штрафы (медленный LCP -10%, большой HTML -5%, долгий ответ -5%, высокий CLS -5%)
```

Веса категорий PageSpeed:
- performance — 1.5
- seo — 1.0
- accessibility — 0.8
- best-practices — 0.6
- pwa — 0.4

## Лицензия

GPL v2 или позже.
