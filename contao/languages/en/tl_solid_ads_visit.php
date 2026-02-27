<?php

declare(strict_types=1);

// Field labels [Label, Description]
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['id']     = ['#', 'Sequential record number'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['source']       = ['Source', 'Advertising platform the visitor came from'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['visited_at']   = ['Date & Time', 'Timestamp of the visit'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['page_url']     = ['Visited URL', 'The full URL the visitor accessed'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['gclid']        = ['Google Click-ID (gclid)', 'Unique identifier of the Google Ads click'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['msclkid']      = ['Bing Click-ID (msclkid)', 'Unique identifier of the Bing Ads click'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_source']   = ['UTM Source', 'utm_source parameter from the URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_medium']   = ['UTM Medium', 'utm_medium parameter from the URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_campaign'] = ['UTM Campaign', 'utm_campaign parameter from the URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_term']     = ['UTM Term', 'utm_term parameter from the URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_content']  = ['UTM Content', 'utm_content parameter from the URL'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['referrer']     = ['Referrer', 'Origin page from the HTTP referrer header'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['user_agent']   = ['Browser / User-Agent', 'Browser identifier of the visitor'];

// Filter options for the "source" field
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['sourceOptions'] = [
    'google' => 'Google Ads',
    'bing'   => 'Bing Ads',
];

// Date range filter
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_label'] = 'Date range:';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_apply'] = 'Apply';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_reset'] = 'Reset';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['count_total']     = '%d entries total';
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['count_filtered']  = '%d of %d entries';

// Operations
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['show']        = ['Show details', 'Show details of record #%s'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['delete']      = ['Delete record', 'Delete record #%s'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['export_csv']  = ['Export CSV', 'Download all filtered records as CSV'];
$GLOBALS['TL_LANG']['tl_solid_ads_visit']['export_json'] = ['Export JSON', 'Download all filtered records as JSON'];
