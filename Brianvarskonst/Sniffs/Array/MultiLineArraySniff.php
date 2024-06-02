<?php

declare (strict_types=1);

namespace Brianvarskonst\Sniffs\Array;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/** Multi Line Array sniff */
class MultiLineArraySniff implements Sniff
{
    /** Define all types of arrays */
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
            $arrayType = 'parenthesis';
            $start     = $current['parenthesis_opener'];
            $end       = $current['parenthesis_closer'];
        } else {
            $arrayType = 'bracket';
            $start     = $current['bracket_opener'];
            $end       = $current['bracket_closer'];
        }

        if ($tokens[$start]['line'] === $tokens[$end]['line']) {
            return;
        }

        if ($tokens[$start + 2]['line'] === $tokens[$start]['line']) {
            $fixable = $phpcsFile->addFixableError(
                \sprintf(
                    'opening %s of multi line array must be followed by newline',
                    $arrayType,
                ),
                $start,
                'OpeningMustBeFollowedByNewline',
            );

            if ($fixable === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewline($start);
                $phpcsFile->fixer->endChangeset();
            }
        }

        if ($tokens[$end - 2]['line'] !== $tokens[$end]['line']) {
            return;
        }

        $fixable = $phpcsFile->addFixableError(
            \sprintf(
                'closing %s of multi line array must in own line',
                $arrayType,
            ),
            $end,
            'ClosingMustBeInOwnLine',
        );

        if ($fixable !== true) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->addNewlineBefore($end);
        $phpcsFile->fixer->endChangeset();
    }
}
