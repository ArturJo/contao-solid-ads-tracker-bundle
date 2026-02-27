<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'solidwork:ads-tracker:load-fixtures',
    description: 'Loads demo entries into tl_solid_ads_visit for testing purposes.',
)]
class LoadFixturesCommand extends Command
{
    public function __construct(private readonly Connection $connection)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fixtures = [
            [
                'tstamp'       => strtotime('-60 days'),
                'source'       => 'google',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-60 days')),
                'page_url'     => 'https://example.com/produkte?gclid=CjwKCAiA1MCrBhAoEiwAC2d64VwXk1&utm_source=google&utm_medium=cpc&utm_campaign=herbst2025',
                'gclid'        => 'CjwKCAiA1MCrBhAoEiwAC2d64VwXk1',
                'msclkid'      => '',
                'utm_source'   => 'google',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'herbst2025',
                'utm_term'     => 'produkte kaufen',
                'utm_content'  => 'anzeige-a',
                'referrer'     => 'https://www.google.com/',
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            ],
            [
                'tstamp'       => strtotime('-45 days'),
                'source'       => 'bing',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-45 days')),
                'page_url'     => 'https://example.com/kontakt?msclkid=8a1b2c3d4e5f6a7b&utm_source=bing&utm_medium=cpc&utm_campaign=brand2025',
                'gclid'        => '',
                'msclkid'      => '8a1b2c3d4e5f6a7b',
                'utm_source'   => 'bing',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'brand2025',
                'utm_term'     => 'firma kontakt',
                'utm_content'  => '',
                'referrer'     => 'https://www.bing.com/',
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/121.0.0.0 Safari/537.36',
            ],
            [
                'tstamp'       => strtotime('-30 days'),
                'source'       => 'google',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-30 days')),
                'page_url'     => 'https://example.com/?gclid=Cj0KCQiAy9msBhD0ARIsANbk0A9mV3&utm_source=google&utm_medium=cpc&utm_campaign=winter2026',
                'gclid'        => 'Cj0KCQiAy9msBhD0ARIsANbk0A9mV3',
                'msclkid'      => '',
                'utm_source'   => 'google',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'winter2026',
                'utm_term'     => '',
                'utm_content'  => 'anzeige-b',
                'referrer'     => 'https://www.google.com/',
                'user_agent'   => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            ],
            [
                'tstamp'       => strtotime('-21 days'),
                'source'       => 'google',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-21 days')),
                'page_url'     => 'https://example.com/leistungen?gclid=AbCdEfGhIjKlMnOpQr&utm_source=google&utm_medium=cpc&utm_campaign=winter2026&utm_term=leistungen',
                'gclid'        => 'AbCdEfGhIjKlMnOpQr',
                'msclkid'      => '',
                'utm_source'   => 'google',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'winter2026',
                'utm_term'     => 'leistungen',
                'utm_content'  => 'anzeige-c',
                'referrer'     => '',
                'user_agent'   => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            ],
            [
                'tstamp'       => strtotime('-14 days'),
                'source'       => 'bing',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-14 days')),
                'page_url'     => 'https://example.com/ueber-uns?msclkid=9f2a1b3c4d5e6f7a&utm_source=bing&utm_medium=cpc&utm_campaign=brand2026',
                'gclid'        => '',
                'msclkid'      => '9f2a1b3c4d5e6f7a',
                'utm_source'   => 'bing',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'brand2026',
                'utm_term'     => '',
                'utm_content'  => '',
                'referrer'     => 'https://www.bing.com/',
                'user_agent'   => 'Mozilla/5.0 (Android 13; Mobile; rv:109.0) Gecko/117.0 Firefox/117.0',
            ],
            [
                'tstamp'       => strtotime('-10 days'),
                'source'       => 'google',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-10 days')),
                'page_url'     => 'https://example.com/produkte/detail?gclid=XyZ123AbC456&utm_source=google&utm_medium=cpc&utm_campaign=winter2026&utm_content=anzeige-a',
                'gclid'        => 'XyZ123AbC456',
                'msclkid'      => '',
                'utm_source'   => 'google',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'winter2026',
                'utm_term'     => 'produkt detail',
                'utm_content'  => 'anzeige-a',
                'referrer'     => 'https://www.google.com/',
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            ],
            [
                'tstamp'       => strtotime('-7 days'),
                'source'       => 'bing',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-7 days')),
                'page_url'     => 'https://example.com/?msclkid=1a2b3c4d5e6f7a8b&utm_source=bing&utm_medium=cpc&utm_campaign=winter2026',
                'gclid'        => '',
                'msclkid'      => '1a2b3c4d5e6f7a8b',
                'utm_source'   => 'bing',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'winter2026',
                'utm_term'     => '',
                'utm_content'  => '',
                'referrer'     => 'https://www.bing.com/',
                'user_agent'   => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            ],
            [
                'tstamp'       => strtotime('-3 days'),
                'source'       => 'google',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-3 days')),
                'page_url'     => 'https://example.com/kontakt?gclid=MnOpQrStUvWxYz&utm_source=google&utm_medium=cpc&utm_campaign=brand2026&utm_term=kontakt',
                'gclid'        => 'MnOpQrStUvWxYz',
                'msclkid'      => '',
                'utm_source'   => 'google',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'brand2026',
                'utm_term'     => 'kontakt',
                'utm_content'  => 'anzeige-b',
                'referrer'     => 'https://www.google.com/',
                'user_agent'   => 'Mozilla/5.0 (iPad; CPU OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1',
            ],
            [
                'tstamp'       => strtotime('-1 day'),
                'source'       => 'google',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-1 day')),
                'page_url'     => 'https://example.com/leistungen?gclid=QrStUvWxYz123&utm_source=google&utm_medium=cpc&utm_campaign=winter2026',
                'gclid'        => 'QrStUvWxYz123',
                'msclkid'      => '',
                'utm_source'   => 'google',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'winter2026',
                'utm_term'     => 'leistungen buchen',
                'utm_content'  => 'anzeige-a',
                'referrer'     => '',
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            ],
            [
                'tstamp'       => strtotime('-2 hours'),
                'source'       => 'bing',
                'visited_at'   => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'page_url'     => 'https://example.com/produkte?msclkid=Za1Yb2Xc3Wd4Ve5&utm_source=bing&utm_medium=cpc&utm_campaign=brand2026&utm_content=anzeige-c',
                'gclid'        => '',
                'msclkid'      => 'Za1Yb2Xc3Wd4Ve5',
                'utm_source'   => 'bing',
                'utm_medium'   => 'cpc',
                'utm_campaign' => 'brand2026',
                'utm_term'     => '',
                'utm_content'  => 'anzeige-c',
                'referrer'     => 'https://www.bing.com/',
                'user_agent'   => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            ],
        ];

        try {
            foreach ($fixtures as $fixture) {
                $this->connection->insert('tl_solid_ads_visit', $fixture);
            }
        } catch (\Throwable $e) {
            $io->error('Could not insert fixtures. Did you run "contao:migrate" first? ' . $e->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf('%d demo entries created in tl_solid_ads_visit.', count($fixtures)));

        return Command::SUCCESS;
    }
}
