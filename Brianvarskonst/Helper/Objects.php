<?php

declare(strict_types=1);

namespace Brianvarskonst\CodingStandard\Helper;

use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Scopes;
use PHP_CodeSniffer\Files\File;

final class Objects
{
    public static function countProperties(File $file, int $position): int
    {
        /** @var array<int, array<string, mixed>> $tokens */
        $tokens = $file->getTokens();

        if (!\in_array(
            $tokens[$position]['code'] ?? null,
            Collections::ooPropertyScopes(),
            true,
        )
        ) {
            return 0;
        }

        [$start, $end] = Boundaries::objectBoundaries($file, $position);

        if (($start < 0) || ($end < 0)) {
            return 0;
        }

        $found = 0;

        $next = $start + 1;

        while ($next < $end) {
            [, $innerFunctionEnd] = Boundaries::functionBoundaries($file, $next);

            if ($innerFunctionEnd > 0) {
                $next = $innerFunctionEnd + 1;

                continue;
            }

            if ((($tokens[$next]['code'] ?? '') === T_VARIABLE)
                && Scopes::isOOProperty($file, $next)
            ) {
                $found++;
            }

            $next++;
        }

        return $found;
    }
}
