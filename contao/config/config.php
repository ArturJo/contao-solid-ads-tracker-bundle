<?php

declare(strict_types=1);

/*
 * Register the backend module under the "system" navigation group.
 */
$GLOBALS['BE_MOD']['system']['solid_ads_tracker'] = [
    'tables' => ['tl_solid_ads_visit'],
];
