<?php

declare(strict_types=1);

namespace A50\Mapper\Hydrators;

use Closure;
use ReflectionClass;
use A50\Mapper\Attributes\Skip;
use A50\Mapper\Hydrator;
use A50\Mapper\KeyFormatter;

final class ObjectHydratorUsingReflection implements Hydrator
{
    public function __construct(
        private readonly array $propertyHydrators,
        private readonly KeyFormatter $keyFormatter,
    ) {
    }

    private function getPropertyHydrator(mixed $value, string $typeName): Closure
    {
        if (\class_exists($typeName)) {
            $isEnum = \enum_exists($typeName);

            if ($isEnum) {
                return $this->propertyHydrators['enum'];
            }
        }

        return $this->propertyHydrators[$typeName] ?? $this->propertyHydrators['object'];
    }

    public function hydrate(string $className, array $data): object
    {
        $reflectionClass = new ReflectionClass($className);
        $object = $reflectionClass->newInstanceWithoutConstructor();

        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(Skip::class);
            $hasSkipAttribute = \count($attributes) > 0;
            if ($hasSkipAttribute) {
                continue;
            }
            $propertyName = $property->getName();

            $keyName = $this->keyFormatter->propertyNameToKey($propertyName);

            $rawValues = array_filter($data, static fn ($key) => str_starts_with($key, $keyName), ARRAY_FILTER_USE_KEY);

            $rawValue = \count($rawValues) > 0 ? $rawValues : $data[$keyName];
            $typeName = $property->getType()->getName();

            if ($property->getType()->allowsNull() && $rawValue[$keyName] === null) {
                $property->setValue($object, $rawValue[$keyName]);
                continue;
            }

            $propertyHydrator = $this->getPropertyHydrator($rawValue, $typeName)();
            $value = $propertyHydrator->hydrate($rawValue, $typeName, $keyName, $this);

            $property->setValue($object, $value);
        }

        return $object;
    }

    public function withKeyFormatter(KeyFormatter $keyFormatter): Hydrator
    {
        return new self(
            $this->propertyHydrators,
            $keyFormatter,
        );
    }
}
