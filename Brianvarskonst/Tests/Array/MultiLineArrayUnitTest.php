<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\Array;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

/**
 * Unit test class for @see MultiLineArraySniff
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class MultiLineArrayUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedErrorList = [
        'MultiLineArrayUnitTest.pass.inc' => [],
        'MultiLineArrayUnitTest.fail.inc' => [
            4  => 1,
            12 => 1,
            18 => 2,
            22 => 1,
            24 => 1,
            28 => 1,
            32 => 1,
        ],
    ];
}
