<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\Formatting;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

class AlphabeticalUseStatementsUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedErrorList = [
        'AlphabeticalUseStatementsUnitTest.pass.inc'   => [],
        'AlphabeticalUseStatementsUnitTest.pass.1.inc' => [],
        'AlphabeticalUseStatementsUnitTest.fail.1.inc' => [
            4  => 1,
            5  => 1,
            8  => 1,
            9  => 1,
            12 => 1,
        ],
        // Take care, more than one fix will be applied.
        'AlphabeticalUseStatementsUnitTest.fail.2.inc' => [
            6 => 1,
            8 => 1,
        ],
        'AlphabeticalUseStatementsUnitTest.fail.3.inc' => [
            7  => 1,
            8  => 1,
            10 => 1,
            15 => 1,
        ],
        'AlphabeticalUseStatementsUnitTest.fail.4.inc' => [
            4  => 1,
            8  => 1,
            13 => 1,
            17 => 1,
            20 => 1,
            21 => 1,
        ],
        'AlphabeticalUseStatementsUnitTest.fail.6.inc' => [5 => 1],
    ];
}
