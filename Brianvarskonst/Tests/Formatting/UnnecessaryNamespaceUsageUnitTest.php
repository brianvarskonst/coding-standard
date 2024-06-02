<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\Formatting;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

/**
 * Unit test class for the UnnecessaryNamespaceUsageUnitTest sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class UnnecessaryNamespaceUsageUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedWarningList = [
        'UnnecessaryNamespaceUsageUnitTest.pass.1.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.2.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.3.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.4.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.5.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.fail.1.inc' => [
            17 => 1,
            19 => 1,
            24 => 1,
            25 => 1,
            26 => 2,
            28 => 1,
            30 => 2,
            32 => 1,
            33 => 1,
            40 => 1,
            44 => 1,
            45 => 1,
            46 => 1,
            52 => 1,
            56 => 1,
        ],
        'UnnecessaryNamespaceUsageUnitTest.fail.2.inc' => [
            10 => 1,
            11 => 1,
        ],
        'UnnecessaryNamespaceUsageUnitTest.fail.3.inc' => [
            15 => 1,
            16 => 1,
            17 => 1,
            18 => 1,
            22 => 1,
            23 => 1,
            25 => 3,
        ],
        'UnnecessaryNamespaceUsageUnitTest.fail.4.inc' => [],
    ];
}
