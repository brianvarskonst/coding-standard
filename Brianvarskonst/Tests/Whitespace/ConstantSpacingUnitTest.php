<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\Whitespace;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

/**
 * Unit test class for the VariableInDoubleQuotedString sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class ConstantSpacingUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedErrorList = [
        'ConstantSpacingUnitTest.pass.inc' => [],
        'ConstantSpacingUnitTest.fail.inc' => [
            4  => 1,
            5  => 1,
            6  => 1,
            10 => 1,
            12 => 1,
            13 => 1,
            14 => 1,
            15 => 1,
            18 => 1,
            22 => 1,
            23 => 1,
            24 => 1,
        ],
    ];
}
