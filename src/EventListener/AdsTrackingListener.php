<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle\EventListener;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Listens on every GET request and records visits that contain
 * a Google Ads parameter (gclid) or a Bing Ads parameter (msclkid).
 */
class AdsTrackingListener
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // Contao's ContaoCache calls the inner kernel as SUB_REQUEST, so
        // isMainRequest() is always false and the Symfony request object may
        // have tracking params stripped. We use PHP superglobals instead,
        // which always reflect the original user request.

        // Only track GET requests
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
            return;
        }

        // Skip AJAX requests
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            return;
        }

        $gclid   = (string) ($_GET['gclid'] ?? '');
        $msclkid = (string) ($_GET['msclkid'] ?? '');

        if ('' === $gclid && '' === $msclkid) {
            return;
        }

        // Only track once per PHP request lifecycle (multiple sub-requests fire this event)
        static $tracked = false;
        if ($tracked) {
            return;
        }
        $tracked = true;

        // Reconstruct original URL from superglobals
        $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS'];
        $pageUrl = ($isHttps ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/');

        $source = '' !== $gclid ? 'google' : 'bing';
        $now    = time();

        try {
            $this->connection->insert('tl_solid_ads_visit', [
                'tstamp'       => $now,
                'source'       => $source,
                'visited_at'   => date('Y-m-d H:i:s', $now),
                'page_url'     => $pageUrl,
                'gclid'        => $gclid,
                'msclkid'      => $msclkid,
                'utm_source'   => (string) ($_GET['utm_source'] ?? ''),
                'utm_medium'   => (string) ($_GET['utm_medium'] ?? ''),
                'utm_campaign' => (string) ($_GET['utm_campaign'] ?? ''),
                'utm_term'     => (string) ($_GET['utm_term'] ?? ''),
                'utm_content'  => (string) ($_GET['utm_content'] ?? ''),
                'referrer'     => (string) ($_SERVER['HTTP_REFERER'] ?? ''),
                'user_agent'   => (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''),
            ]);
        } catch (\Throwable $e) {
            // Silently fail to not break the application
        }
    }
}
