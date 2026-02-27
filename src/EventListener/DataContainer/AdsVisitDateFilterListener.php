<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;

class AdsVisitDateFilterListener
{
    private const SESSION_KEY = 'ads_tracker_daterange';

    public function __construct(
        private readonly Connection $connection,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * Called via onload_callback – runs before data is fetched.
     * Reads POST, saves to session, applies filter to DCA.
     */
    public function applyDateFilter(DataContainer $dc): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            return;
        }

        $filter = $_SESSION[self::SESSION_KEY] ?? ['from' => '', 'to' => ''];

        if ($request->isMethod('POST')) {
            if ($request->request->has('daterange_reset')) {
                $filter = ['from' => '', 'to' => ''];
            } else {
                $filter['from'] = $request->request->get('daterange_from', '');
                $filter['to']   = $request->request->get('daterange_to', '');
            }

            $_SESSION[self::SESSION_KEY] = $filter;
        }

        if ($filter['from'] && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filter['from'])) {
            $GLOBALS['TL_DCA'][$dc->table]['list']['sorting']['filter'][] =
                "visited_at >= '" . $filter['from'] . " 00:00:00'";
        }

        if ($filter['to'] && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filter['to'])) {
            $GLOBALS['TL_DCA'][$dc->table]['list']['sorting']['filter'][] =
                "visited_at <= '" . $filter['to'] . " 23:59:59'";
        }
    }

    /**
     * Called via panel_callback – renders the date range inputs and entry count.
     */
    public function renderDateRangePanel(DataContainer $dc): string
    {
        $filter = $_SESSION[self::SESSION_KEY] ?? ['from' => '', 'to' => ''];

        $fromValue = htmlspecialchars((string) $filter['from'], ENT_QUOTES, 'UTF-8');
        $toValue   = htmlspecialchars((string) $filter['to'], ENT_QUOTES, 'UTF-8');

        $totalCount      = (int) $this->connection->fetchOne('SELECT COUNT(*) FROM tl_solid_ads_visit');
        $contaoFilters   = $this->getContaoSessionFilters();
        $hasActiveFilter = $filter['from'] || $filter['to'] || !empty($contaoFilters);
        $filteredCount   = $hasActiveFilter ? $this->getFilteredCount($filter, $contaoFilters) : $totalCount;

        if ($hasActiveFilter) {
            $countLabel = sprintf(
                $GLOBALS['TL_LANG']['tl_solid_ads_visit']['count_filtered'] ?? '%d von %d Einträgen',
                $filteredCount,
                $totalCount,
            );
        } else {
            $countLabel = sprintf(
                $GLOBALS['TL_LANG']['tl_solid_ads_visit']['count_total'] ?? '%d Einträge gesamt',
                $totalCount,
            );
        }

        return sprintf(
            '<div class="tl_filter tl_subpanel">
                <strong>%s</strong>
                <input type="date" name="daterange_from" value="%s" class="tl_text_4" style="width:130px">
                &ndash;
                <input type="date" name="daterange_to" value="%s" class="tl_text_4" style="width:130px">
                <button type="submit" name="filter_apply" value="1" class="tl_submit">%s</button>
                <button type="submit" name="daterange_reset" value="1" class="tl_submit">%s</button>
                <span style="margin-left:15px; color:#666; font-style:italic">%s</span>
            </div>',
            $GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_label'] ?? 'Zeitraum:',
            $fromValue,
            $toValue,
            $GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_apply'] ?? 'Anwenden',
            $GLOBALS['TL_LANG']['tl_solid_ads_visit']['daterange_reset'] ?? 'Reset',
            $countLabel,
        );
    }

    private function getContaoSessionFilters(): array
    {
        try {
            $bag        = $this->requestStack->getSession()->getBag('contao_backend');
            $filterData = $bag->get('filter', []);
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

    private function getFilteredCount(array $dateFilter, array $contaoFilters): int
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('tl_solid_ads_visit');

        if (!empty($dateFilter['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFilter['from'])) {
            $qb->andWhere('visited_at >= :from')->setParameter('from', $dateFilter['from'] . ' 00:00:00');
        }

        if (!empty($dateFilter['to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFilter['to'])) {
            $qb->andWhere('visited_at <= :to')->setParameter('to', $dateFilter['to'] . ' 23:59:59');
        }

        foreach ($contaoFilters as $field => $value) {
            $qb->andWhere($field . ' = :' . $field)->setParameter($field, $value);
        }

        return (int) $qb->executeQuery()->fetchOne();
    }
}
