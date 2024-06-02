<?php

declare(strict_types=1);

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Util\Standards;

$codingStandard = 'Brianvarskonst';

require_once __DIR__ . '/../vendor/squizlabs/php_codesniffer/tests/bootstrap.php';

$standardPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . $codingStandard;

if (!is_dir($standardPath)) {
    throw new RuntimeException("Directory for {$standardPath} coding standard doesn't exist.");
}

Config::setConfigData('installed_paths', $standardPath, true);

// Ignore all other Standards in tests.
$standards   = Standards::getInstalledStandards();
$standards[] = 'Generic';

$ignoredStandardsStr = implode(
    ',',
    array_filter(
        $standards,
        static fn ($installed): bool => $installed !== $codingStandard,
    ),
);

putenv("PHPCS_IGNORE_TESTS={$ignoredStandardsStr}");
