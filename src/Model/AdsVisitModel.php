<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle\Model;

use Contao\Model;

/**
 * @property int    $id
 * @property int    $tstamp
 * @property string $source
 * @property string $visited_at
 * @property string $page_url
 * @property string $gclid
 * @property string $msclkid
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_term
 * @property string $utm_content
 * @property string $referrer
 * @property string $user_agent
 */
class AdsVisitModel extends Model
{
    protected static $strTable = 'tl_solid_ads_visit';
}
