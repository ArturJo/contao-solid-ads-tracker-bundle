# Contao Solid Ads Tracker Bundle

A Contao 5.3 bundle for automatically tracking visitors who arrive via **Google Ads** (`gclid`) or **Bing Ads** (`msclkid`).

## Features

- Automatic detection of Google Ads clicks (`gclid`) and Bing Ads clicks (`msclkid`)
- Stores all hits in a dedicated database table (`tl_solid_ads_visit`)
- Records all UTM parameters (`utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`)
- Backend module under **System → Ads Tracker** for reviewing all tracked visits
- Date range filter (From / To) with live entry count display
- Dropdown filters by source (Google / Bing), UTM Source, Medium and Campaign
- Full-text search across URL, Click-IDs, UTM parameters, referrer and user agent
- Detail view per entry with all stored fields
- Export filtered data as **CSV** or **JSON** (filename includes date, time and active filters)
- Installable via Contao Manager

---

## Installation

### Via Contao Manager (recommended)

1. Open Contao Manager
2. Search for `solidwork/contao-solid-ads-tracker-bundle`
3. Install and update the database

### Via Composer

```bash
composer require solidwork/contao-solid-ads-tracker-bundle
```

Then create the database table:

```bash
php bin/console contao:migrate
```

---

## Console Commands

### Run database migration

After installation, create the table:

```bash
php bin/console contao:migrate
```

### Load demo entries

To test the backend view with sample data:

```bash
php bin/console solidwork:ads-tracker:load-fixtures
```

Creates 10 realistic demo entries (Google Ads & Bing Ads) spread over 60 days. Visible in the Contao backend under **System → Ads Tracker** afterwards.

---

## Backend Module

The module appears in the Contao backend under **System → Ads Tracker**.

### List View

Displays all tracked visits with the following columns:

| Column | Description |
|---|---|
| `#` | Entry ID |
| Source | Google Ads or Bing Ads |
| Date & Time | Timestamp of the visit |
| UTM Source | utm_source parameter |
| UTM Medium | utm_medium parameter |
| UTM Campaign | utm_campaign parameter |
| Visited URL | Full URL of the visit |

### Date Range Filter

A date range (From / To) can be entered in the top panel. After clicking **Apply**, the list is restricted to that period. The current entry count is displayed next to the filter:

- Without filter: `13 entries total`
- With filter: `3 of 13 entries`

### Dropdown Filters

Filter by the following fields via dropdown:

- **Source** – Google Ads or Bing Ads
- **UTM Source**
- **UTM Medium**
- **UTM Campaign**

### Search

Full-text search across the following fields:

- Visited URL
- Google Click-ID (`gclid`)
- Bing Click-ID (`msclkid`)
- UTM parameters
- Referrer
- Browser / User-Agent

### Detail View

Clicking the info icon opens the detail view with all stored fields for that entry.

### Export

Use the **Export CSV** and **Export JSON** buttons to download all currently filtered entries. The export respects all active filters:

- Date range filter (From / To)
- Dropdown filters (Source, UTM Source, UTM Medium, UTM Campaign)

The filename automatically includes the date, time and active filters, e.g.:

```
ads-tracker_2026-02-27_14-32-05_source-google_utm_campaign-winter2026.csv
ads-tracker_2026-02-27_14-32-05_2026-02-01_bis_2026-02-27.json
```

---

## How Does Tracking Work?

The bundle registers a Symfony event listener on `kernel.request` (priority 8). Every GET request is checked for a `gclid` or `msclkid` parameter. If found, the visit is immediately saved to the database – before the page is rendered.

Only full page requests are tracked (no AJAX, no sub-requests). If an error occurs (e.g. table not yet created), the listener fails silently without affecting the page.

---

## Database Structure (`tl_solid_ads_visit`)

| Column         | Type         | Description                           |
|----------------|--------------|---------------------------------------|
| `id`           | INT          | Primary key, auto-increment           |
| `tstamp`       | INT          | Unix timestamp (internal)             |
| `source`       | VARCHAR(10)  | `google` or `bing`                    |
| `visited_at`   | VARCHAR(20)  | Date & time (`Y-m-d H:i:s`)           |
| `page_url`     | TEXT         | Full URL of the visited page          |
| `gclid`        | VARCHAR(255) | Google Ads Click-ID                   |
| `msclkid`      | VARCHAR(255) | Bing Ads Click-ID                     |
| `utm_source`   | VARCHAR(255) | UTM parameter: source                 |
| `utm_medium`   | VARCHAR(255) | UTM parameter: medium                 |
| `utm_campaign` | VARCHAR(255) | UTM parameter: campaign               |
| `utm_term`     | VARCHAR(255) | UTM parameter: term                   |
| `utm_content`  | VARCHAR(255) | UTM parameter: content                |
| `referrer`     | TEXT         | HTTP referrer header                  |
| `user_agent`   | TEXT         | Browser / user agent                  |

---

## Requirements

- PHP 8.1 or higher
- Contao 5.3 or higher

---

## License

MIT License – see [LICENSE](LICENSE)

## Author

[Solidwork](https://github.com/ArturJo)
