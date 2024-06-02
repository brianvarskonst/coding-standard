<?php

declare(strict_types=1);

namespace Brianvarskonst\Sniffs\Namespace;

use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class Psr4Sniff implements Sniff
{
    public array $psr4 = [];

    public array $exclude = [];

    /** @return list<int|string> */
    public function register(): array
    {
        return [T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM];
    }

    /**
     * @param int $stackPtr
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $className = ObjectDeclarations::getName($phpcsFile, $stackPtr) ?? '';

        /** @var array<int, array<string, mixed>> $tokens */
        $tokens     = $phpcsFile->getTokens();
        $code       = $tokens[$stackPtr]['code'];
        $entityType = 'class';

        if ($code !== T_CLASS) {
            $entityType = $code === T_TRAIT ? 'trait' : 'interface';
        }

        $this->normalizeExcluded();

        if (!$this->psr4) {
            $this->checkFilenameOnly($phpcsFile, $stackPtr, $className, $entityType);

            return;
        }

        $this->checkPsr4($phpcsFile, $stackPtr, $className, $entityType);
    }

    private function checkFilenameOnly(
        File $file,
        int $position,
        string $className,
        string $entityType,
    ): void {
        if (\basename($file->getFilename()) === "{$className}.php") {
            return;
        }

        $file->addError(
            \sprintf(
                "File containing %s '%s' is named '%s' instead of '%s'.",
                $entityType,
                $className,
                $file->getFilename(),
                "{$className}.php",
            ),
            $position,
            'WrongFilename',
        );
    }

    private function checkPsr4(
        File $file,
        int $position,
        string $className,
        string $entityType,
    ): void {

        $namespace = Namespaces::determineNamespace($file, $position);

        $fullyQualifiedName = $namespace . "\\{$className}";

        foreach ($this->exclude as $excluded) {
            if (\str_starts_with($fullyQualifiedName, (string) $excluded)) {
                return;
            }
        }

        $filePath = \str_replace('\\', '/', $file->getFilename());

        foreach ($this->psr4 as $baseNamespace => $foldersStr) {
            if (!\is_string($baseNamespace) || !\is_string($foldersStr)) {
                continue;
            }

            $baseNamespace = \trim($baseNamespace, '\\');

            if (!\str_starts_with($namespace, $baseNamespace)) {
                continue;
            }

            $folders = \explode('|', $foldersStr);

            $valid = $this->checkPsr4Folders(
                $filePath,
                $baseNamespace,
                $namespace,
                $className,
                ...$folders,
            );

            if ($valid) {
                return;
            }
        }

        $file->addError(
            \sprintf(
                "%s '%s', located at '%s', is not compliant with PSR-4 configuration.",
                \ucfirst($entityType),
                $fullyQualifiedName,
                $filePath,
            ),
            $position,
            'InvalidPSR4',
        );
    }

    private function checkPsr4Folders(
        string $filePath,
        string $baseNamespace,
        string $namespace,
        string $className,
        string ...$folders,
    ): bool {
        foreach ($folders as $folder) {
            $folder      = \trim(\str_replace('\\', '/', $folder), './');
            $folderSplit = \explode("/{$folder}/", $filePath);

            if (\count($folderSplit) < 2) {
                continue;
            }

            $relativePath = \array_pop($folderSplit);

            if (\basename($relativePath) !== "{$className}.php") {
                continue;
            }

            $relativeNamespace = \str_replace('/', '\\', \dirname($relativePath));
            $expectedNamespace = $relativeNamespace === '.'
                ? $baseNamespace
                : "{$baseNamespace}\\{$relativeNamespace}";

            if ("{$expectedNamespace}\\{$className}" === "{$namespace}\\{$className}") {
                return true;
            }
        }

        return false;
    }

    private function normalizeExcluded(): void
    {
        $excluded = $this->exclude;

        $this->exclude = [];

        foreach ($excluded as $className) {
            if (!\is_string($className)) {
                continue;
            }

            $trimmedClassName = \ltrim($className, '\\');
            $this->exclude[]  = $trimmedClassName;
        }
    }
}
