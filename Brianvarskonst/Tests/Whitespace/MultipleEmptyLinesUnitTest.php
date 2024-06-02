<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\Whitespace;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

/**
 * Unit test class for the MultipleEmptyLines sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class MultipleEmptyLinesUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedErrorList = [
        'MultipleEmptyLinesUnitTest.pass.inc' => [],
        'MultipleEmptyLinesUnitTest.fail.inc' => [
            2  => 1,
            14 => 1,
            21 => 1,
            24 => 1,
            29 => 1,
        ],
    ];
}
