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
            4   => 1,
            9   => 1,
            16  => 2,
            17  => 2,
            21  => 1,
            27  => 1,
            37  => 1,
            42  => 1,
            44  => 1,
            48  => 1,
            50  => 1,
            57  => 1,
            58  => 1,
            60  => 1,
            66  => 1,
            69  => 1,
            70  => 1,
            72  => 1,
            81  => 1,
            82  => 1,
            84  => 1,
            92  => 1,
            93  => 1,
            96  => 1,
            104 => 1,
            129 => 1,
            131 => 1,
            133 => 1,
            135 => 2,
            139 => 1,
            140 => 1,
            144 => 2,
            148 => 1,
        ],
    ];
}
