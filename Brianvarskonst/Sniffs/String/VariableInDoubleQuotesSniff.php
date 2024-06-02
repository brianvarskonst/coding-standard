<?php

declare(strict_types=1);

namespace Brianvarskonst\Sniffs\String;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Variable in Double Quotes sniff.
 *
 * Variables in double quotes must be surrounded by { }
 */
class VariableInDoubleQuotesSniff implements Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @see Tokens.php
     *
     * @return array<int, string>
     */
    public function register(): array
    {
        return [T_DOUBLE_QUOTED_STRING];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $varRegExp = '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

        $tokens  = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        $matches = [];

        \preg_match_all($varRegExp, $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            foreach ($match as [$var, $pos]) {
                if ($pos !== 1 && $content[$pos - 1] === '{') {
                    continue;
                }

                if (\strpos(\substr($content, 0, $pos), '{') > 0
                    && !\str_contains(\substr($content, 0, $pos), '}')
                ) {
                    continue;
                }

                $lastOpeningBrace = \strrpos(\substr($content, 0, $pos), '{');

                if ($lastOpeningBrace !== false
                    && $content[$lastOpeningBrace + 1] === '$'
                ) {
                    $lastClosingBrace = \strrpos(\substr($content, 0, $pos), '}');

                    if ($lastClosingBrace !== false
                        && $lastClosingBrace < $lastOpeningBrace
                    ) {
                        continue;
                    }
                }

                $fix = $phpcsFile->addFixableError(
                    \sprintf(
                        'must surround variable %s with { }',
                        $var,
                    ),
                    $stackPtr,
                    'NotSurroundedWithBraces',
                );

                if ($fix !== true) {
                    continue;
                }

                $correctVariable = $this->surroundVariableWithBraces(
                    $content,
                    $pos,
                    $var,
                );

                $this->fixPhpCsFile($stackPtr, $correctVariable, $phpcsFile);
            }
        }
    }

    /** Surrounds a variable with curly brackets */
    private function surroundVariableWithBraces(string $content, int $pos, string $var): string
    {
        $before = \substr($content, 0, $pos);
        $after  = \substr($content, $pos + \strlen($var));

        return $before . '{' . $var . '}' . $after;
    }

    /** Fixes the file */
    private function fixPhpCsFile(int $stackPtr, string $correctVariable, File $phpCsFile): void
    {
        $phpCsFile->fixer->beginChangeset();
        $phpCsFile->fixer->replaceToken($stackPtr, $correctVariable);
        $phpCsFile->fixer->endChangeset();
    }
}
