<?php

declare (strict_types=1);

namespace Brianvarskonst\Sniffs\Array;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens as PHP_CodeSniffer_Tokens;

/**
 * Array Double Arrow Alignment sniff.
 *
 * '=>' must be aligned in arrays, and the key and the '=>' must be in the same line
 */
class ArrayDoubleArrowAlignmentSniff implements Sniff
{
    /**
     * Define all types of arrays.
     */
    protected array $arrayTokens = [
        // @phan-suppress-next-line PhanUndeclaredConstant
        T_OPEN_SHORT_ARRAY,
        T_ARRAY,
    ];

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array<int, int>
     */
    public function register(): array
    {
        return $this->arrayTokens;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param int  $stackPtr  The position of the current token in
     *                        the stack passed in $tokens.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens  = $phpcsFile->getTokens();
        $current = $tokens[$stackPtr];

        if ($current['code'] === T_ARRAY) {
            $start = $current['parenthesis_opener'];
            $end   = $current['parenthesis_closer'];
        } else {
            $start = $current['bracket_opener'];
            $end   = $current['bracket_closer'];
        }

        if ($tokens[$start]['line'] === $tokens[$end]['line']) {
            return;
        }

        // phpcs:disable
        /** @var array<int> $assignments */
        $assignments  = [];
        // phpcs:enable
        $keyEndColumn = -1;
        $lastLine     = -1;

        for ($i = $start + 1; $i < $end; $i++) {
            $current  = $tokens[$i];
            $previous = $tokens[$i - 1];

            // Skip nested arrays.
            if (\in_array($current['code'], $this->arrayTokens, true) === true) {
                $i = $current['code'] === T_ARRAY ? $current['parenthesis_closer'] + 1 : $current['bracket_closer'] + 1;

                continue;
            }

            // Skip closures in array.
            if ($current['code'] === T_CLOSURE) {
                $i = $current['scope_closer'] + 1;

                continue;
            }

            $i = (int) $i;

            if ($current['code'] !== T_DOUBLE_ARROW) {
                continue;
            }

            $assignments[] = $i;
            $column        = $previous['column'];
            $line          = $current['line'];

            if ($lastLine === $line) {
                $previousComma = $this->getPreviousComma($phpcsFile, $i, $start);

                $msg = 'only one "=>" assignments per line is allowed in a multi line array';

                if ($previousComma !== false) {
                    $fixable = $phpcsFile->addFixableError($msg, $i, 'OneAssignmentPerLine');

                    if ($fixable === true) {
                        $phpcsFile->fixer->beginChangeset();
                        $phpcsFile->fixer->addNewline((int) $previousComma);
                        $phpcsFile->fixer->endChangeset();
                    }
                } else {
                    // Remove current and previous '=>' from array for further processing.
                    \array_pop($assignments);
                    \array_pop($assignments);
                    $phpcsFile->addError($msg, $i, 'OneAssignmentPerLine');
                }
            }

            $hasKeyInLine = false;

            $index = $i - 1;

            while ($index >= 0 && $tokens[$index]['line'] === $current['line']) {
                if (\in_array($tokens[$index]['code'], PHP_CodeSniffer_Tokens::$emptyTokens, true) === false) {
                    $hasKeyInLine = true;
                }

                $index--;
            }

            if ($hasKeyInLine === false) {
                $fixable = $phpcsFile->addFixableError(
                    'in arrays, keys and "=>" must be on the same line',
                    $i,
                    'KeyAndValueNotOnSameLine',
                );

                if ($fixable === true) {
                    $phpcsFile->fixer->beginChangeset();
                    $phpcsFile->fixer->replaceToken($index, '');
                    $phpcsFile->fixer->endChangeset();
                }
            }

            if ($column > $keyEndColumn) {
                $keyEndColumn = $column;
            }

            $lastLine = $line;
        }

        $doubleArrowStartColumn = $keyEndColumn + 1;

        foreach ($assignments as $ptr) {
            $current = $tokens[$ptr];
            $column  = $current['column'];

            $beforeArrowPtr = $ptr - 1;
            $currentIndent  = \strlen($tokens[$beforeArrowPtr]['content']);
            $correctIndent  = $currentIndent - $column + $doubleArrowStartColumn;

            if ($column === $doubleArrowStartColumn) {
                continue;
            }

            $fixable = $phpcsFile->addFixableError(
                \sprintf(
                    // phpcs:ignore Generic.Files.LineLength.MaxExceeded
                    'each "=>" assignments must be aligned; current indentation before "=>" are %s space(s), must be %s space(s)',
                    $currentIndent,
                    $currentIndent,
                ),
                $ptr,
                'AssignmentsNotAligned',
            );

            if ($fixable === false) {
                continue;
            }

            $phpcsFile->fixer->beginChangeset();

            if ($tokens[$beforeArrowPtr]['code'] === T_WHITESPACE) {
                $phpcsFile->fixer->replaceToken($beforeArrowPtr, \str_repeat(' ', $correctIndent));
            } else {
                $phpcsFile->fixer->addContent($beforeArrowPtr, \str_repeat(' ', $correctIndent));
            }

            $phpcsFile->fixer->endChangeset();
        }
    }

    /** Find previous comma in array */
    private function getPreviousComma(File $phpcsFile, int $stackPtr, int $start): bool|int
    {
        $previousComma = false;
        $tokens        = $phpcsFile->getTokens();

        $ptr = $phpcsFile->findPrevious([T_COMMA, T_CLOSE_SHORT_ARRAY], $stackPtr, $start);

        while ($ptr !== false) {
            if ($tokens[$ptr]['code'] === T_COMMA) {
                $previousComma = $ptr;

                break;
            }

            if ($tokens[$ptr]['code'] === T_CLOSE_SHORT_ARRAY) {
                $ptr = $tokens[$ptr]['bracket_opener'];
            }

            $ptr = $phpcsFile->findPrevious([T_COMMA, T_CLOSE_SHORT_ARRAY], $ptr - 1, $start);
        }

        return $previousComma;
    }
}
