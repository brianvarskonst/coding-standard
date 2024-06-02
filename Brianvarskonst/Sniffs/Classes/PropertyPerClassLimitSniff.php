<?php

declare(strict_types=1);

namespace Brianvarskonst\Sniffs\Classes;

use Brianvarskonst\CodingStandard\Helper\Names;
use Brianvarskonst\CodingStandard\Helper\Objects;
use PHPCSUtils\Tokens\Collections;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class PropertyPerClassLimitSniff implements Sniff
{
    public int $maxCount = 10;

    /** @return list<int|string> */
    public function register(): array
    {
        return \array_keys(Collections::ooPropertyScopes());
    }

    /** @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint */
    public function process(File $phpcsFile, $stackPtr): void
    {
        // phpcs:enable Inpsyde.CodeQuality.ArgumentTypeDeclaration
        $count = Objects::countProperties($phpcsFile, $stackPtr);

        if ($count <= $this->maxCount) {
            return;
        }

        $message = \sprintf(
            '"%s" has too many properties: %d. Can be up to %d properties.',
            Names::tokenTypeName($phpcsFile, $stackPtr),
            $count,
            $this->maxCount,
        );

        $phpcsFile->addWarning($message, $stackPtr, 'TooManyProperties');
    }
}
