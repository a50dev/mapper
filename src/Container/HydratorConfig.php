<?php

declare(strict_types=1);

namespace A50\Mapper\Container;

use A50\Mapper\KeyFormatters\KeyFormatterForSnakeCasing;

final class HydratorConfig
{
    public function __construct(
        /**
         * @var array<string, callable>
         */
        private array $propertyHydrators,
        /**
         * @var class-string
         */
        private string $keyFormatter,
    ) {
    }

    /**
     * @param array<string, callable> $propertyHydrators
     * @param class-string $keyFormatterClassName
     */
    public static function withDefaults(
        array $propertyHydrators,
        string $keyFormatterClassName = KeyFormatterForSnakeCasing::class,
    ): self {
        return new self($propertyHydrators, $keyFormatterClassName);
    }

    /**
     * @return array<string, callable>
     */
    public function propertyHydrators(): array
    {
        return $this->propertyHydrators;
    }

    public function withPropertyHydrator(string $typeName, callable $propertyHydrator): self
    {
        $new = clone $this;
        $new->propertyHydrators[$typeName] = $propertyHydrator;

        return $new;
    }

    public function keyFormatter(): string
    {
        return $this->keyFormatter;
    }

    /**
     * @param class-string $keyFormatterClassName
     * @return $this
     */
    public function withKeyFormatter(string $keyFormatterClassName): self
    {
        $new = clone $this;
        $new->keyFormatter = $keyFormatterClassName;

        return $new;
    }
}
