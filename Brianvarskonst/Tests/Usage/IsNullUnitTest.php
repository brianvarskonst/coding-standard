<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\Usage;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

class IsNullUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedWarningList = [
        'IsNullUnitTest.pass.inc' => [],
        'IsNullUnitTest.fail.inc' => [
            3  => 1,
            4  => 1,
            5  => 1,
            6  => 1,
            7  => 1,
            9  => 1,
            10 => 1,
            11 => 1,
            12 => 1,
            15 => 1,
            16 => 1,
            18 => 1,
            23 => 1,
            24 => 1,
            25 => 2,
            26 => 2,
            27 => 2,
            28 => 2,
            30 => 1,
            31 => 1,
            32 => 1,
            33 => 1,
            34 => 1,
            36 => 1,
            37 => 1,
            38 => 1,
            39 => 1,
            41 => 1,
            42 => 1,
            44 => 1,
            49 => 1,
            50 => 1,
            51 => 2,
            52 => 2,
            53 => 2,
            54 => 2,
        ],
    ];
}
