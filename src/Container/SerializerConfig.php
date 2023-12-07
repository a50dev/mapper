<?php

declare(strict_types=1);

namespace A50\Mapper\Container;

use A50\Mapper\KeyFormatters\KeyFormatterForSnakeCasing;

final class SerializerConfig
{
    public function __construct(
        /**
         * @var array<string, callable>
         */
        private array $propertySerializers,
        /**
         * @var class-string
         */
        private string $keyFormatter,
    ) {
    }

    /**
     * @param array<string, callable> $propertySerializers
     * @param class-string $keyFormatterClassName
     */
    public static function withDefaults(
        array $propertySerializers,
        string $keyFormatterClassName = KeyFormatterForSnakeCasing::class,
    ): self {
        return new self($propertySerializers, $keyFormatterClassName);
    }

    /**
     * @return array<string, callable>
     */
    public function propertySerializers(): array
    {
        return $this->propertySerializers;
    }

    public function withPropertyHydrator(string $typeName, callable $propertyHydrator): self
    {
        $new = clone $this;
        $new->propertySerializers[$typeName] = $propertyHydrator;

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
