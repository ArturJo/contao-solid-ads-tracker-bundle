<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Solidwork\ContaoSolidAdsTrackerBundle\ContaoSolidAdsTrackerBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoSolidAdsTrackerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
