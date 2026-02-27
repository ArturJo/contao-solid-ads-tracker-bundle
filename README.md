# Contao Solid Ads Tracker Bundle

Ein Contao 5.3 Bundle zum automatischen Protokollieren von Besuchern, die über **Google Ads** (`gclid`) oder **Bing Ads** (`msclkid`) auf die Website gelangt sind.

## Features

- Automatische Erkennung von Google-Ads-Klicks (`gclid`) und Bing-Ads-Klicks (`msclkid`)
- Speicherung aller Treffer in einer eigenen Datenbanktabelle (`tl_solid_ads_visit`)
- Erfassung aller UTM-Parameter (`utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`)
- Backend-Modul unter **System → Ads Tracker** zur Auswertung aller protokollierten Besuche
- Datumsfilter (Von / Bis) mit Anzeige der gefilterten Eintragsanzahl
- Filterung nach Quelle (Google / Bing), UTM-Source, -Medium und -Kampagne
- Volltextsuche über URL, Click-IDs, UTM-Parameter, Referrer und User-Agent
- Detailansicht pro Eintrag mit allen gespeicherten Daten
- Export der gefilterten Daten als **CSV** oder **JSON** (Dateiname enthält Datum, Uhrzeit und aktive Filter)
- Installierbar über den Contao Manager

---

## Installation

### Via Contao Manager (empfohlen)

1. Contao Manager öffnen
2. Nach `solidwork/contao-solid-ads-tracker-bundle` suchen
3. Installieren und Datenbank aktualisieren

### Via Composer

```bash
composer require solidwork/contao-solid-ads-tracker-bundle
```

Anschließend die Datenbanktabelle anlegen:

```bash
php bin/console contao:migrate
```

---

## Konsolen-Befehle

### Datenbank migrieren

Nach der Installation die Tabelle anlegen:

```bash
php bin/console contao:migrate
```

### Demo-Einträge laden

Um die Backend-Ansicht mit Beispieldaten zu testen:

```bash
php bin/console solidwork:ads-tracker:load-fixtures
```

Legt 10 realistische Beispieleinträge (Google Ads & Bing Ads) über einen Zeitraum von 60 Tagen an. Danach im Contao-Backend unter **System → Ads Tracker** sichtbar.

---

## Backend-Modul

Das Modul erscheint im Contao-Backend unter **System → Ads Tracker**.

### Listenansicht

Zeigt alle protokollierten Besuche mit folgenden Spalten:

| Spalte | Beschreibung |
|---|---|
| `#` | ID des Eintrags |
| Quelle | Google Ads oder Bing Ads |
| Datum & Uhrzeit | Zeitpunkt des Besuchs |
| UTM Source | utm_source-Parameter |
| UTM Medium | utm_medium-Parameter |
| UTM Kampagne | utm_campaign-Parameter |
| Aufgerufene URL | Vollständige URL des Besuchs |

### Datumsfilter

Im oberen Panel kann ein Zeitraum (Von / Bis) eingegeben werden. Nach Klick auf **Anwenden** wird die Liste auf diesen Zeitraum eingeschränkt. Neben dem Filter wird die aktuelle Eintragsanzahl angezeigt:

- Ohne Filter: `13 Einträge gesamt`
- Mit Filter: `3 von 13 Einträgen`

### Dropdown-Filter

Filterung nach folgenden Feldern per Dropdown:

- **Quelle** – Google Ads oder Bing Ads
- **UTM Source**
- **UTM Medium**
- **UTM Kampagne**

### Suche

Volltextsuche über folgende Felder:

- Aufgerufene URL
- Google Click-ID (`gclid`)
- Bing Click-ID (`msclkid`)
- UTM-Parameter
- Referrer
- Browser / User-Agent

### Detailansicht

Klick auf das Info-Symbol öffnet die Detailansicht mit allen gespeicherten Feldern des Eintrags.

### Export

Über die Buttons **CSV exportieren** und **JSON exportieren** können alle aktuell gefilterten Einträge heruntergeladen werden. Der Export berücksichtigt dabei alle aktiven Filter:

- Datumsfilter (Von / Bis)
- Dropdown-Filter (Quelle, UTM Source, UTM Medium, UTM Kampagne)

Der Dateiname enthält automatisch Datum, Uhrzeit und die aktiven Filter, z. B.:

```
ads-tracker_2026-02-27_14-32-05_source-google_utm_campaign-winter2026.csv
ads-tracker_2026-02-27_14-32-05_2026-02-01_bis_2026-02-27.json
```

---

## Wie funktioniert das Tracking?

Das Bundle registriert einen Symfony-Event-Listener auf `kernel.request` (Priorität 8). Bei jedem GET-Request wird geprüft, ob die URL einen `gclid`- oder `msclkid`-Parameter enthält. Falls ja, wird der Besuch sofort in der Datenbank gespeichert – bevor die Seite gerendert wird.

Es werden nur vollständige Seitenaufrufe erfasst (kein AJAX, keine Sub-Requests). Tritt ein Fehler auf (z. B. Tabelle noch nicht angelegt), schlägt der Listener still fehl ohne die Seite zu beeinflussen.

---

## Datenbankstruktur (`tl_solid_ads_visit`)

| Spalte         | Typ          | Beschreibung                          |
|----------------|--------------|---------------------------------------|
| `id`           | INT          | Primärschlüssel, auto-increment       |
| `tstamp`       | INT          | Unix-Timestamp (intern)               |
| `source`       | VARCHAR(10)  | `google` oder `bing`                  |
| `visited_at`   | VARCHAR(20)  | Datum & Uhrzeit (`Y-m-d H:i:s`)       |
| `page_url`     | TEXT         | Vollständige aufgerufene URL          |
| `gclid`        | VARCHAR(255) | Google Ads Click-ID                   |
| `msclkid`      | VARCHAR(255) | Bing Ads Click-ID                     |
| `utm_source`   | VARCHAR(255) | UTM-Parameter: source                 |
| `utm_medium`   | VARCHAR(255) | UTM-Parameter: medium                 |
| `utm_campaign` | VARCHAR(255) | UTM-Parameter: campaign               |
| `utm_term`     | VARCHAR(255) | UTM-Parameter: term                   |
| `utm_content`  | VARCHAR(255) | UTM-Parameter: content                |
| `referrer`     | TEXT         | HTTP-Referrer-Header                  |
| `user_agent`   | TEXT         | Browser / User-Agent                  |

---

## Systemanforderungen

- PHP 8.1 oder höher
- Contao 5.3 oder höher

---

## Lizenz

MIT License – siehe [LICENSE](LICENSE)

## Autor

[Solidwork](https://github.com/ArturJo)
