<?php

declare(strict_types=1);

namespace Solidwork\ContaoSolidAdsTrackerBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'solidwork:ads-tracker:setup',
    description: 'Configures .env.local so gclid/msclkid are not stripped by the Contao HTTP cache.',
)]
class SetupCommand extends Command
{
    public function __construct(private readonly string $projectDir)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Ads Tracker Setup');

        if (Command::FAILURE === $this->updateEnvFile($io)) {
            return Command::FAILURE;
        }

        $this->clearHttpCache($io);

        $io->section('Final step');
        $io->text('Rebuild the Symfony cache:');
        $io->newLine();
        $io->writeln('  <comment>php bin/console cache:clear</comment>');
        $io->newLine();

        return Command::SUCCESS;
    }

    private function updateEnvFile(SymfonyStyle $io): int
    {
        $envFile  = $this->projectDir . '/.env.local';
        $content  = file_exists($envFile) ? ((string) file_get_contents($envFile)) : '';
        $varName  = 'QUERY_PARAMS_REMOVE_FROM_DENY_LIST';
        $required = ['gclid', 'msclkid'];

        if (preg_match('/^' . $varName . '=(.*)$/m', $content, $matches)) {
            $existing = array_filter(array_map('trim', explode(',', $matches[1])));
            $missing  = array_diff($required, $existing);

            if (empty($missing)) {
                $io->success($varName . ' already contains gclid and msclkid – no changes needed.');

                return Command::SUCCESS;
            }

            $newValue = implode(',', array_merge($existing, $missing));
            $content  = (string) preg_replace(
                '/^' . $varName . '=.*$/m',
                $varName . '=' . $newValue,
                $content
            );

            $io->text(sprintf('Updated existing entry: %s=%s', $varName, $newValue));
        } else {
            $content = rtrim($content);
            if ('' !== $content) {
                $content .= "\n";
            }
            $content .= $varName . "=gclid,msclkid\n";

            $io->text(sprintf('Added to %s: %s=gclid,msclkid', basename($envFile), $varName));
        }

        if (false === file_put_contents($envFile, $content)) {
            $io->error(sprintf('Could not write to %s – check file permissions.', $envFile));

            return Command::FAILURE;
        }

        $io->success(sprintf('%s updated successfully.', $envFile));

        return Command::SUCCESS;
    }

    private function clearHttpCache(SymfonyStyle $io): void
    {
        $cacheDir = $this->projectDir . '/var/cache/prod/http_cache';

        if (!is_dir($cacheDir)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cacheDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($cacheDir);

        $io->text('HTTP cache directory cleared.');
    }
}
