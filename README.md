# WordPress Plugin: Курс валют

Репозиторий содержит WordPress-плагин **«Курс валют»**, который отображает актуальные курсы валют Банка России (ЦБ РФ) на вашем сайте.

## Возможности

- 🔌 Шорткод `[exchange_rates]` для вставки в записи/страницы
- 📦 Виджет «Курс валют ЦБ РФ» для сайдбара
- 🎨 Три варианта отображения: таблица, карточки, компактный список
- 🌍 40+ валют (USD, EUR, GBP, CNY, JPY, CHF, BYN, KZT и др.)
- 🏳️ Флаги стран (эмодзи) рядом с кодом валюты
- 📈 Изменение курса за день в абсолютных и процентных значениях
- 💾 Кэширование через Transients API (по умолчанию 1 час)
- 🔄 Принудительное обновление курсов из админки
- 📱 Адаптивная вёрстка, поддержка тёмной темы
- ⚡ Без внешних зависимостей и API-ключей

## Источник данных

Публичное API [cbr-xml-daily.ru](https://www.cbr-xml-daily.ru/daily_json.js) — бесплатно, без регистрации.

## Установка

1. Скопируйте папку `currency-exchange-rates/` в `/wp-content/plugins/`
2. Активируйте плагин в разделе **Плагины** админ-панели WordPress
3. Перейдите в **Курс валют** в боковом меню для настройки
4. Используйте шорткод `[exchange_rates]` в любой записи или добавьте виджет

## Использование шорткода

```text
[exchange_rates]
```

С параметрами:

```text
[exchange_rates currencies="USD,EUR,GBP" layout="cards" show_flag="1" show_change="1" show_date="1"]
```

| Параметр      | Тип        | По умолчанию | Описание                              |
|---------------|------------|--------------|---------------------------------------|
| `currencies`  | строка     | из настроек  | Коды валют через запятую              |
| `layout`      | строка     | `table`      | `table`, `cards` или `compact`        |
| `show_flag`   | 1 / 0      | 1            | Показывать флаги                      |
| `show_change` | 1 / 0      | 1            | Показывать изменение курса            |
| `show_date`   | 1 / 0      | 1            | Показывать дату                       |

## Структура

```text
currency-exchange-rates/
├── currency-exchange-rates.php       # Главный файл
├── uninstall.php                     # Очистка при удалении
├── readme.txt                        # WordPress readme
├── README.md                         # Документация
├── includes/
│   ├── class-cbr-api.php             # API ЦБ РФ + кэш
│   ├── class-shortcode.php           # Шорткод
│   ├── class-widget.php              # Виджет
│   ├── class-admin.php               # Админ-страница
│   └── templates/                    # Шаблоны отображения
│       ├── table.php
│       ├── cards.php
│       └── compact.php
└── assets/
    ├── css/style.css                 # Стили
    └── js/script.js                  # Скрипты
```

## Требования

- WordPress 5.5+
- PHP 7.2+
- Расширение `cURL`

## Лицензия

GPL-2.0-or-later

## Автор

**Alexlip531** — [github.com/Alexlip531](https://github.com/Alexlip531)
