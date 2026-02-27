<?php

declare(strict_types=1);

use Contao\DataContainer;
use Contao\DC_Table;
use Solidwork\ContaoSolidAdsTrackerBundle\EventListener\DataContainer\AdsVisitDateFilterListener;
use Solidwork\ContaoSolidAdsTrackerBundle\EventListener\DataContainer\AdsVisitExportListener;

$GLOBALS['TL_DCA']['tl_solid_ads_visit'] = [

    // Config
    'config' => [
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => false,
        'notCreatable'     => true,
        'notCopyable'      => true,
        'notEditable'      => true,
        'onload_callback'  => [
            [AdsVisitDateFilterListener::class, 'applyDateFilter'],
            [AdsVisitExportListener::class, 'handleExport'],
        ],
        'sql' => [
            'keys' => [
                'id'     => 'primary',
                'tstamp' => 'index',
                'source' => 'index',
            ],
        ],
    ],

    // List view
    'list' => [
        'sorting' => [
            'mode'        => DataContainer::MODE_SORTABLE,
            'fields'      => ['visited_at DESC'],
            'panelLayout'    => 'filter,daterange;search,limit',
            'panel_callback' => [
                'daterange' => [AdsVisitDateFilterListener::class, 'renderDateRangePanel'],
            ],
        ],
        'label' => [
            'fields'      => ['id', 'source', 'visited_at', 'utm_source', 'utm_medium', 'utm_campaign', 'page_url'],
            'showColumns' => true,
        ],
        'global_operations' => [
            'export_csv' => [
                'label' => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['export_csv'],
                'href'  => 'key=export_csv',
                'class' => 'header_csv',
                'icon'  => 'tablewizard.svg',
            ],
            'export_json' => [
                'label' => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['export_json'],
                'href'  => 'key=export_json',
                'class' => 'header_json',
                'icon'  => 'tablewizard.svg',
            ],
            'all' => [
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
            ],
        ],
    ],

    // Fields
    'fields' => [

        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],

        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],

        // Quelle: google | bing
        'source' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['source'],
            'filter'    => true,
            'inputType' => 'select',
            'options'   => ['google', 'bing'],
            'reference' => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['sourceOptions'],
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 10, 'default' => ''],
        ],

        // Datum & Uhrzeit des Besuchs (Y-m-d H:i:s)
        'visited_at' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['visited_at'],
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 20, 'default' => ''],
        ],

        // Vollständige URL der aufgerufenen Seite
        'page_url' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['page_url'],
            'search'    => true,
            'inputType' => 'textarea',
            'eval'      => ['readonly' => true, 'tl_class' => 'clr', 'style' => 'height:50px'],
            'sql'       => ['type' => 'text', 'notnull' => false],
        ],

        // Google Ads Click-ID
        'gclid' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['gclid'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],

        // Bing Ads Click-ID
        'msclkid' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['msclkid'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],

        // UTM-Parameter
        'utm_source' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_source'],
            'filter'    => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],

        'utm_medium' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_medium'],
            'filter'    => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],

        'utm_campaign' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_campaign'],
            'filter'    => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],

        'utm_term' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_term'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],

        'utm_content' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['utm_content'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],

        // HTTP-Referrer
        'referrer' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['referrer'],
            'search'    => true,
            'inputType' => 'textarea',
            'eval'      => ['readonly' => true, 'tl_class' => 'clr', 'style' => 'height:50px'],
            'sql'       => ['type' => 'text', 'notnull' => false],
        ],

        // Browser-User-Agent
        'user_agent' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_solid_ads_visit']['user_agent'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['readonly' => true, 'tl_class' => 'clr'],
            'sql'       => ['type' => 'text', 'notnull' => false],
        ],
    ],
];
