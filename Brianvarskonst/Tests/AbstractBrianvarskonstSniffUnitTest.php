<?php

declare(strict_types=1);

namespace Brianvarskonst\Tests;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Abstract class to make the writing of tests more convenient.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard.
 *
 * Expected errors and warnings are stored in the class fields $expectedErrorList
 * and $expectedWarningList
 */
abstract class AbstractBrianvarskonstSniffUnitTest extends AbstractSniffUnitTest
{
    /**
     * Array or Array containing the test file as key and as value the key-value pairs with line number and number of#
     * errors as describe in @see AbstractSniffUnitTest::getErrorList
     *
     * When the array is empty, the test will pass.
     *
     * @var array<string, array<int, int>> $expectedErrorList
     */
    protected array $expectedErrorList = [];

    /**
     * Array or Array containing the test file as key and as value the key-value pairs with line number and number of#
     * errors as describe in @see AbstractSniffUnitTest::getWarningList
     *
     * When the array is empty, the test will pass.
     *
     * @var array<string, array<int, int>> $expectedWarningList
     */
    protected array $expectedWarningList = [];

    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     *
     * @throws RuntimeException
     */
    protected function getErrorList(string $testFile = ''): array
    {
        return $this->getRecordForTestFile($testFile, $this->expectedErrorList);
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     *
     * @throws RuntimeException
     */
    protected function getWarningList(string $testFile = ''): array
    {
        return $this->getRecordForTestFile($testFile, $this->expectedWarningList);
    }

    /**
     * Returns the lines where warnings should occur for the error or warning list.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param array<string, array<int, int>> $list
     *
     * @return array<int, int>
     *
     * @throws RuntimeException
     */
    private function getRecordForTestFile(string $testFile, array $list): array
    {
        if ($list === []) {
            return [];
        }

        if (!\array_key_exists($testFile, $list)) {
            throw new RuntimeException(
                \sprintf(
                    '%s%s is not handled by %s',
                    \sprintf(
                        'Testfile %s in ',
                        $testFile,
                    ),
                    __DIR__,
                    self::class,
                ),
            );
        }

        return $list[$testFile];
    }
}
