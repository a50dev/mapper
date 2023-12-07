<?php

declare(strict_types=1);

namespace A50\Mapper\Container;

use DateTimeImmutable;
use Psr\Container\ContainerInterface;
use A50\Container\ServiceProvider;
use A50\Mapper\Hydrator;
use A50\Mapper\Hydrators\DateTimeImmutablePropertyHydrator;
use A50\Mapper\Hydrators\DefaultPropertyHydrator;
use A50\Mapper\Hydrators\EnumPropertyHydrator;
use A50\Mapper\Hydrators\ObjectHydratorUsingReflection;
use A50\Mapper\Hydrators\ObjectPropertyHydrator;
use A50\Mapper\KeyFormatter;
use A50\Mapper\KeyFormatters\KeyFormatterForSnakeCasing;
use A50\Mapper\Serializer;
use A50\Mapper\Serializers\DateTimeImmutablePropertySerializer;
use A50\Mapper\Serializers\DefaultPropertySerializer;
use A50\Mapper\Serializers\EnumPropertySerializer;
use A50\Mapper\Serializers\ObjectPropertySerializer;
use A50\Mapper\Serializers\ObjectSerializerUsingReflection;

final class MapperServiceProvider implements ServiceProvider
{
    public static function getDefinitions(): array
    {
        return [
            Hydrator::class => static function (ContainerInterface $container) {
                /** @var HydratorConfig $config */
                $config = $container->get(HydratorConfig::class);

                /** @var KeyFormatter $keyFormatter */
                $keyFormatter = $container->get($config->keyFormatter());

                return new ObjectHydratorUsingReflection(
                    propertyHydrators: $config->propertyHydrators(),
                    keyFormatter: $keyFormatter,
                );
            },
            Serializer::class => static function (ContainerInterface $container) {
                /** @var SerializerConfig $config */
                $config = $container->get(SerializerConfig::class);

                /** @var KeyFormatter $keyFormatter */
                $keyFormatter = $container->get($config->keyFormatter());

                return new ObjectSerializerUsingReflection(
                    propertySerializers: $config->propertySerializers(),
                    keyFormatter: $keyFormatter,
                );
            },
            HydratorConfig::class => static fn () => HydratorConfig::withDefaults(
                propertyHydrators: [
                    DateTimeImmutable::class => static fn () => new DateTimeImmutablePropertyHydrator(),
                    'string' => static fn () => new DefaultPropertyHydrator(),
                    'int' => static fn () => new DefaultPropertyHydrator(),
                    'bool' => static fn () => new DefaultPropertyHydrator(),
                    'enum' => static fn () => new EnumPropertyHydrator(),
                    'object' => static fn () => new ObjectPropertyHydrator(
                        keyFormatter: new KeyFormatterForSnakeCasing(),
                    ),
                ]
            ),
            SerializerConfig::class => static fn () => SerializerConfig::withDefaults(
                propertySerializers: [
                    DateTimeImmutable::class => static fn () => new DateTimeImmutablePropertySerializer(),
                    'string' => static fn () => new DefaultPropertySerializer(),
                    'int' => static fn () => new DefaultPropertySerializer(),
                    'bool' => static fn () => new DefaultPropertySerializer(),
                    'enum' => static fn () => new EnumPropertySerializer(),
                    'object' => static fn () => new ObjectPropertySerializer(),
                ]
            ),
        ];
    }

    public static function getExtensions(): array
    {
        return [];
    }
}
