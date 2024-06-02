<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\Array;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

/**
 * Unit test class for @see ArrayDoubleArrowAlignmentSniff
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class ArrayDoubleArrowAlignmentUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedErrorList = [
        'ArrayDoubleArrowAlignmentUnitTest.pass.inc' => [],
        'ArrayDoubleArrowAlignmentUnitTest.fail.inc' => [
            5   => 1,
            10  => 1,
            17  => 2,
            18  => 2,
            22  => 1,
            28  => 1,
            38  => 1,
            43  => 1,
            45  => 1,
            49  => 1,
            51  => 1,
            58  => 1,
            59  => 1,
            61  => 1,
            67  => 1,
            70  => 1,
            71  => 1,
            73  => 1,
            82  => 1,
            83  => 1,
            85  => 1,
            93  => 1,
            94  => 1,
            97  => 1,
            105 => 1,
            130 => 1,
            132 => 1,
            134 => 1,
            136 => 2,
            140 => 1,
            141 => 1,
            145 => 2,
            149 => 1,
        ],
    ];
}
