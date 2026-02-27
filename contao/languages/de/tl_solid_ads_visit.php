<?php

declare(strict_types=1);

// Feldbezeichnungen [Label, Beschreibung]
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['id']     = ['#', 'Laufende Nummer des Eintrags'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['source']       = ['Quelle', 'Werbeplattform, über die der Besucher gekommen ist'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['visited_at']   = ['Datum & Uhrzeit', 'Zeitpunkt des Besuchs'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['page_url']     = ['Aufgerufene URL', 'Die vollständige URL, die der Besucher aufgerufen hat'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['gclid']        = ['Google Click-ID (gclid)', 'Eindeutige Kennung des Google-Ads-Klicks'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['msclkid']      = ['Bing Click-ID (msclkid)', 'Eindeutige Kennung des Bing-Ads-Klicks'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_source']   = ['UTM Source', 'utm_source-Parameter aus der URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_medium']   = ['UTM Medium', 'utm_medium-Parameter aus der URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_campaign'] = ['UTM Kampagne', 'utm_campaign-Parameter aus der URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_term']     = ['UTM Term', 'utm_term-Parameter aus der URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_content']  = ['UTM Content', 'utm_content-Parameter aus der URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['referrer']     = ['Referrer', 'Herkunftsseite laut HTTP-Referrer-Header'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['user_agent']   = ['Browser / User-Agent', 'Browser-Kennung des Besuchers'];

// Filteroptionen für das Feld "Quelle"
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['sourceOptions'] = [
    'google' => 'Google Ads',
    'bing'   => 'Bing Ads',
];

// Datumsfilter
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_label'] = 'Zeitraum:';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_apply'] = 'Anwenden';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_reset'] = 'Reset';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['count_total']     = '%d Einträge gesamt';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['count_filtered']  = '%d von %d Einträgen';

// Operationen
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['show']        = ['Details anzeigen', 'Details des Eintrags #%s anzeigen'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['delete']      = ['Eintrag löschen', 'Eintrag #%s löschen'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['export_csv']  = ['CSV exportieren', 'Alle gefilterten Einträge als CSV herunterladen'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['export_json'] = ['JSON exportieren', 'Alle gefilterten Einträge als JSON herunterladen'];
