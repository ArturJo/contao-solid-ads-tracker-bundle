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
        $logFile = dirname(__DIR__, 5) . '/var/logs/ads-tracker-debug.txt';
        $request = $event->getRequest();

        file_put_contents($logFile,
            date('Y-m-d H:i:s') . ' CALLED isMain=' . ($event->isMainRequest() ? 'yes' : 'no')
            . ' method=' . $request->getMethod()
            . ' gclid=' . $request->query->get('gclid', '')
            . ' URI=' . $request->getUri() . "\n",
            FILE_APPEND
        );

        // Only handle the main request, not sub-requests
        if (!$event->isMainRequest()) {
            return;
        }

        // Only track regular page GET requests
        if (!$request->isMethod('GET') || $request->isXmlHttpRequest()) {
            return;
        }

        $gclid   = (string) $request->query->get('gclid', '');
        $msclkid = (string) $request->query->get('msclkid', '');

        // Nothing to track if neither parameter is present
        if ('' === $gclid && '' === $msclkid) {
            return;
        }

        file_put_contents($logFile,
            date('Y-m-d H:i:s') . ' TRACKING: gclid=' . $gclid . ' msclkid=' . $msclkid . "\n",
            FILE_APPEND
        );

        $source = '' !== $gclid ? 'google' : 'bing';
        $now    = time();

        try {
            $this->connection->insert('tl_solid_ads_visit', [
                'tstamp'       => $now,
                'source'       => $source,
                'visited_at'   => date('Y-m-d H:i:s', $now),
                'page_url'     => $request->getUri(),
                'gclid'        => $gclid,
                'msclkid'      => $msclkid,
                'utm_source'   => (string) $request->query->get('utm_source', ''),
                'utm_medium'   => (string) $request->query->get('utm_medium', ''),
                'utm_campaign' => (string) $request->query->get('utm_campaign', ''),
                'utm_term'     => (string) $request->query->get('utm_term', ''),
                'utm_content'  => (string) $request->query->get('utm_content', ''),
                'referrer'     => (string) $request->headers->get('referer', ''),
                'user_agent'   => (string) $request->headers->get('User-Agent', ''),
            ]);
        } catch (\Throwable $e) {
            file_put_contents(dirname(__DIR__, 5) . '/var/logs/ads-tracker-debug.txt',
                date('Y-m-d H:i:s') . ' DB ERROR: ' . $e->getMessage() . "\n" .
                '  URI: ' . $request->getUri() . "\n",
                FILE_APPEND
            );
        }
    }
}
