<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests\String;

use Brianvarskonst\Tests\AbstractBrianvarskonstSniffUnitTest;

/**
 * Unit test class for the VariableInDoubleQuotedString sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class VariableInDoubleQuotesUnitTest extends AbstractBrianvarskonstSniffUnitTest
{
    protected array $expectedErrorList = [
        'VariableInDoubleQuotesUnitTest.pass.inc' => [],
        'VariableInDoubleQuotesUnitTest.fail.inc' => [
            3  => 1,
            4  => 1,
            5  => 2,
            6  => 2,
            7  => 1,
            8  => 1,
            9  => 1,
            10 => 1,
            11 => 1,
        ],
    ];
}
