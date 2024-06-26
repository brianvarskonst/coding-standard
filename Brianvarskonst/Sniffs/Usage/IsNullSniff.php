<?php

declare(strict_types=1);

namespace Brianvarskonst\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Identify usage of is_null and replace it with a comparison against null.
 */
class IsNullSniff implements Sniff
{
    /** @return array<int> */
    public function register(): array
    {
        return [T_STRING];
    }

    /**
     * @param int $stackPtr The position in the PHP_CodeSniffer file's token stack where the token
     * was found.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['content'] !== 'is_null') {
            return;
        }

        $ignore = [
            T_DOUBLE_COLON             => true,
            T_OBJECT_OPERATOR          => true,
            T_NULLSAFE_OBJECT_OPERATOR => true,
            T_FUNCTION                 => true,
            T_CONST                    => true,
        ];

        // Check to make sure it's a function call to is_null (not $this->, etc.)
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);

        if (isset($ignore[$tokens[$prevToken]['code']])) {
            return;
        }

        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true);

        if ($nextToken === false
            || $tokens[$nextToken]['code'] !== T_OPEN_PARENTHESIS
            || !isset($tokens[$nextToken]['parenthesis_closer'])
        ) {
            return;
        }

        $nsToken = null;

        if ($tokens[$prevToken]['code'] === T_NS_SEPARATOR) {
            $nsToken   = $prevToken;
            $prevToken = $phpcsFile->findPrevious(
                T_WHITESPACE,
                (int) $prevToken - 1,
                null,
                true,
            );

            if ($tokens[$prevToken]['code'] === T_STRING) {
                return;
            }
        }

        $hasBackslash = $nsToken === null;

        if ($this->isComparisonWithIsNull($phpcsFile, $stackPtr, $hasBackslash)) {
            $phpcsFile->addWarning(
                'Use a comparison against null instead of is_null',
                $stackPtr,
                'IsNull',
            );

            return;
        }

        $fix = $phpcsFile->addFixableWarning(
            'Use a comparison against null instead of is_null',
            $stackPtr,
            'IsNull',
        );

        if (!$fix) {
            return;
        }

        $stackPtrOpenParenthesis  = $nextToken;
        $stackPtrCloseParenthesis = $tokens[$nextToken]['parenthesis_closer'];

        $phpcsFile->fixer->beginChangeset();

        if (\is_int($nsToken)) {
            $phpcsFile->fixer->replaceToken($nsToken, '');
        }

        $phpcsFile->fixer->replaceToken($stackPtr, '');

        $notNullComparison = $tokens[$prevToken]['code'] === T_BOOLEAN_NOT;

        if ($this->keepParentheses($phpcsFile, $stackPtrOpenParenthesis, $stackPtrCloseParenthesis)) {
            if ($notNullComparison) {
                // Remove the boolean not operator, it will be moved to the comparison operator.
                $phpcsFile->fixer->replaceToken((int) $prevToken, '');
                $replacement = ') !== null';
            } else {
                $replacement = ') === null';
            }
        } else {
            // Remove opening parenthesis.
            $phpcsFile->fixer->replaceToken($stackPtrOpenParenthesis, '');

            // Remove following whitespace, if any.
            while ($tokens[$stackPtrOpenParenthesis + 1]['code'] === T_WHITESPACE) {
                $stackPtrOpenParenthesis++;
                $phpcsFile->fixer->replaceToken($stackPtrOpenParenthesis, '');
            }

            if ($notNullComparison) {
                // Remove the boolean not operator, it will be moved to the comparison operator.
                $phpcsFile->fixer->replaceToken((int) $prevToken, '');
                $replacement = ' !== null';
            } else {
                $replacement = ' === null';
            }

            $ptrBeforeCloseParenthesis = $stackPtrCloseParenthesis;

            // Remove whitespace preceding closing parenthesis, if any.
            while ($tokens[$ptrBeforeCloseParenthesis - 1]['code'] === T_WHITESPACE) {
                $ptrBeforeCloseParenthesis--;
                $phpcsFile->fixer->replaceToken($ptrBeforeCloseParenthesis, '');
            }
        }

        $phpcsFile->fixer->replaceToken($stackPtrCloseParenthesis, $replacement);

        $phpcsFile->fixer->endChangeset();
    }

    /**
     * Determines if the content between parenthesis warrants keeping the parenthesis for the null
     * comparison.
     */
    private function keepParentheses(
        File $phpcsFile,
        int $stackPtrOpenParenthesis,
        int $stackPtrCloseParenthesis,
    ): bool {

        $tokens = $phpcsFile->getTokens();

        // Skip first whitespace, if any.
        $stackPtrFirstExpressionToken = $stackPtrOpenParenthesis + 1;

        while ($tokens[$stackPtrFirstExpressionToken]['code'] === T_WHITESPACE) {
            $stackPtrFirstExpressionToken++;
        }

        // Skip last whitespace, if any.
        $stackPtrLastExpressionToken = $stackPtrCloseParenthesis - 1;

        while ($tokens[$stackPtrLastExpressionToken]['code'] === T_WHITESPACE) {
            $stackPtrLastExpressionToken--;
        }

        // Look for whitespace between the parentheses.
        $firstWhitespace = $phpcsFile->findNext(
            T_WHITESPACE,
            $stackPtrFirstExpressionToken,
            $stackPtrLastExpressionToken,
        );

        // Statements like is_null( $var ) or is_null( Class::method() ) are simple enough
        // not to require whitespace, so the parentheses can be dropped.
        // PHPCS will identify statements is_null($a?$b:$c) as missing whitespace before this
        // sniff is run.
        if ($firstWhitespace === false) {
            return false;
        }

        $innerParenthesis = $phpcsFile->findNext(
            T_OPEN_PARENTHESIS,
            $stackPtrFirstExpressionToken,
            $stackPtrLastExpressionToken,
        );

        // Something has been wrapped in parentheses ending just before the ending parenthesis of
        // the is_null statement.
        if ($innerParenthesis !== false
            && $tokens[$innerParenthesis]['parenthesis_closer'] === $stackPtrLastExpressionToken
        ) {
            $previousWhiteSpace = $phpcsFile->findPrevious(
                T_WHITESPACE,
                $innerParenthesis,
                $stackPtrFirstExpressionToken,
            );

            // Obviously, statements such as is_null( $a ? $b : ( $c ) ) will trick this check.
            // They should retain their parenthesis, so see if there is any whitespace before
            // the opening parenthesis.
            if ($previousWhiteSpace === false) {
                return false;
            }
        }

        // When in doubt, keep parenthesis.
        return true;
    }

    /**
     * Comparisons that compare a variable to the result of is_null or to the result of another
     * is_null, like $var === is_null( $var ) or is_null( $var ) === is_null( $var ).
     *
     * These can't be replaced by other constructions and should remain untouched.
     */
    private function isComparisonWithIsNull(File $phpcsFile, int $stackPtr, bool $hasBackslash): bool
    {
        $prevOnStack = $hasBackslash ? 1 : 2;

        $tokens    = $phpcsFile->getTokens();
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - $prevOnStack, null, true);
        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true);
        $nextToken = $phpcsFile->findNext(
            Tokens::$emptyTokens,
            $tokens[$nextToken]['parenthesis_closer'] + 1,
            null,
            true,
        );

        return $tokens[$prevToken]['code'] === T_IS_EQUAL ||
            $tokens[$prevToken]['code'] === T_IS_IDENTICAL ||
            $tokens[$prevToken]['code'] === T_IS_NOT_EQUAL ||
            $tokens[$prevToken]['code'] === T_IS_NOT_IDENTICAL ||
            $tokens[$nextToken]['code'] === T_IS_EQUAL ||
            $tokens[$nextToken]['code'] === T_IS_IDENTICAL ||
            $tokens[$nextToken]['code'] === T_IS_NOT_EQUAL ||
            $tokens[$nextToken]['code'] === T_IS_NOT_IDENTICAL;
    }
}
