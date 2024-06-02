<?php

declare(strict_types=1);

namespace Brianvarskonst\Sniffs\Usage;

use Brianvarskonst\CodingStandard\Helper\Names;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ElementNameMinimalLengthSniff implements Sniff
{
    public int $minLength = 3;

    /** @var list<string> */
    public array $allowedShortNames = [
        'a',
        'b',
        'an',
        'as',
        'at',
        'be',
        'by',
        'c',
        'db',
        'do',
        'ex',
        'go',
        'he',
        'hi',
        'i',
        'id',
        'if',
        'in',
        'io',
        'is',
        'it',
        'js',
        'me',
        'my',
        'no',
        'of',
        'ok',
        'on',
        'or',
        'pi',
        'so',
        'sh',
        'to',
        'up',
    ];

    /** @var list<string> */
    public array $additionalAllowedNames = [];

    /** @return list<int|string> */
    public function register(): array
    {
        return Names::NAMEABLE_TOKENS;
    }

    /** @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint */
    public function process(File $phpcsFile, $stackPtr): void
    {
        // phpcs:enable Inpsyde.CodeQuality.ArgumentTypeDeclaration

        $elementName = Names::nameableTokenName($phpcsFile, $stackPtr);

        if (($elementName === '') || ($elementName === null)) {
            return;
        }

        $elementNameLength = \mb_strlen($elementName);

        if ($this->shouldBeSkipped($elementNameLength, $elementName)) {
            return;
        }

        $typeName = Names::tokenTypeName($phpcsFile, $stackPtr);
        $message  = \sprintf(
            '%s name "%s" is only %d chars long. Must be at least %d.',
            $typeName,
            $elementName,
            $elementNameLength,
            $this->minLength,
        );

        $phpcsFile->addError($message, $stackPtr, 'TooShort');
    }

    private function shouldBeSkipped(int $elementNameLength, string $elementName): bool
    {
        return ($elementNameLength >= $this->minLength) || $this->isShortNameAllowed($elementName);
    }

    private function isShortNameAllowed(string $variableName): bool
    {
        $target = \strtolower($variableName);

        foreach ($this->allowedShortNames as $allowed) {
            if (\strtolower($allowed) === $target) {
                return true;
            }
        }

        foreach ($this->additionalAllowedNames as $allowed) {
            if (\strtolower($allowed) === $target) {
                return true;
            }
        }

        return false;
    }
}
