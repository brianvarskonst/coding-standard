<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * Taken from symfony-docs: contributing/code/standards.rst
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme;

const BAM = 1;

/**
 * Coding standards demonstration.
 */
// phpcs:ignore Brianvarskonst.Namespace.Psr4.InvalidPSR4, Squiz.Classes.ClassFileName.NoMatch
class FooBar
{
    public const SOME_CONST = 42;
    public const STR_CONST  = '43';
    protected const PROTECT = 0;
    public const LALA       = 'lala';

    private string $fooBar;

    /** @param string $dummy Some argument description */
    public function __construct(string $dummy)
    {
        $this->fooBar = $this->transformText($dummy);
    }

    /** @deprecated */
    public function someDeprecatedMethod(): string
    {
        @\trigger_error(
            \sprintf(
                'The %s() method is deprecated since version 2.8 and will be removed in 3.0. Use %s instead.',
                __METHOD__,
                'Acme\Baz::someMethod()',
            ),
            E_USER_DEPRECATED,
        );

        return Baz::someMethod();
    }

    /**
     * Transforms the input given as first argument.
     *
     * @param bool|string $dummy   Some argument description
     * @param array       $options An options collection to be used within the transformation
     *
     * @return string|null The transformed input
     *
     * @throws \RuntimeException When an invalid option is provided
     */
    private function transformText(bool| string $dummy, array $options = []): ?string
    {
        /** @var array<string, string> $defaultOptions */
        $defaultOptions = [
            'some_default'    => 'values',
            'another_default' => 'more values',
        ];

        foreach ($options as $option) {
            if (!\in_array($option, $defaultOptions)) {
                throw new \RuntimeException(\sprintf('Unrecognized option "%s"', $option));
            }
        }

        $destructuredStuff = \array_flip(...$defaultOptions);

        $mergedOptions = \array_merge(
            $defaultOptions,
            $options,
        );

        if ($dummy === true) {
            return null;
        }

        if ($dummy === 'string') {
            if ($mergedOptions['some_default'] === 'values') {
                return \substr($dummy, 0, 5);
            }

            return \ucwords($dummy);
        }

        return $destructuredStuff;
    }

    /**
     * Performs some basic check for a given value.
     *
     * @param mixed $value     Some value to check against
     * @param bool  $theSwitch Some switch to control the method's flow
     *
     * @return bool|void The resultant check if $theSwitch isn't false, void otherwise
     */
    private function reverseBoolean($value = null, bool $theSwitch = false)
    {
        if (!$theSwitch) {
            return;
        }

        return !$value;
    }
}
