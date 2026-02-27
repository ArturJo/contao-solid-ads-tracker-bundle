<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoSolidAdsTrackerBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
