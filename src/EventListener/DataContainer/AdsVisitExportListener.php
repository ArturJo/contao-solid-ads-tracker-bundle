<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;

class AdsVisitExportListener
{
    private const SESSION_KEY = 'ads_tracker_daterange';

    public function __construct(
        private readonly Connection $connection,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function handleExport(DataContainer $dc): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            return;
        }

        $key = $request->query->get('key');

        if ($key !== 'export_csv' && $key !== 'export_json') {
            return;
        }

        $dateFilter    = $_SESSION[self::SESSION_KEY] ?? ['from' => '', 'to' => ''];
        $contaoFilters = $this->getContaoSessionFilters();

        $qb = $this->connection->createQueryBuilder()
            ->select('source', 'visited_at', 'page_url', 'gclid', 'msclkid',
                     'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
                     'referrer', 'user_agent')
            ->from('tl_solid_ads_visit')
            ->orderBy('visited_at', 'DESC');

        if ($dateFilter['from']) {
            $qb->andWhere('visited_at >= :from')
               ->setParameter('from', $dateFilter['from'] . ' 00:00:00');
        }

        if ($dateFilter['to']) {
            $qb->andWhere('visited_at <= :to')
               ->setParameter('to', $dateFilter['to'] . ' 23:59:59');
        }

        foreach ($contaoFilters as $field => $value) {
            $qb->andWhere($field . ' = :' . $field)->setParameter($field, $value);
        }

        $rows     = $qb->executeQuery()->fetchAllAssociative();
        $filename = $this->buildFilename($dateFilter, $contaoFilters);

        if ($key === 'export_csv') {
            $this->sendCsv($rows, $filename);
        } else {
            $this->sendJson($rows, $filename);
        }
    }

    private function buildFilename(array $dateFilter, array $contaoFilters): string
    {
        $parts = ['ads-tracker', date('Y-m-d_H-i-s')];

        if ($dateFilter['from'] || $dateFilter['to']) {
            $range = ($dateFilter['from'] ?: 'start') . '_bis_' . ($dateFilter['to'] ?: 'heute');
            $parts[] = $range;
        }

        foreach ($contaoFilters as $field => $value) {
            $parts[] = $field . '-' . $value;
        }

        return implode('_', $parts);
    }

    private function getContaoSessionFilters(): array
    {
        try {
            $bag         = $this->requestStack->getSession()->getBag('contao_backend');
            $filterData  = $bag->get('filter', []);
            $tableFilter = $filterData['tl_solid_ads_visit'] ?? [];

            $active = [];
            foreach (['source', 'utm_source', 'utm_medium', 'utm_campaign'] as $field) {
                if (!empty($tableFilter[$field])) {
                    $active[$field] = $tableFilter[$field];
                }
            }

            return $active;
        } catch (\Throwable) {
            return [];
        }
    }

    private function sendCsv(array $rows, string $filename): void
    {
        $filename .= '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        if (!empty($rows)) {
            fputcsv($output, array_keys($rows[0]));
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
        }

        fclose($output);
        exit();
    }

    private function sendJson(array $rows, string $filename): void
    {
        $filename .= '.json';

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}
