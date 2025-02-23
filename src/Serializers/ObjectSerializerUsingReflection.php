<?php

declare(strict_types=1);

namespace A50\Mapper\Serializers;

use A50\Mapper\AutonomousPropertySerializer;
use Closure;
use ReflectionClass;
use ReflectionProperty;
use UnitEnum;
use A50\Mapper\Attributes\Skip;
use A50\Mapper\KeyFormatter;
use A50\Mapper\Serializer;

final class ObjectSerializerUsingReflection implements Serializer
{
    public function __construct(
        private readonly array $propertySerializers,
        private readonly KeyFormatter $keyFormatter,
    ) {
    }

    private function getPropertySerializer(mixed $value, string $typeName): Closure
    {
        if (\is_object($value)) {
            $isEnum = \is_a($value, UnitEnum::class, true);

            if ($isEnum) {
                return $this->propertySerializers['enum'];
            }
        }

        return $this->propertySerializers[$typeName] ?? $this->propertySerializers['object'];
    }

    private function getPropertyData(ReflectionProperty $property): array
    {
        $name = $property->getName();
        $typeName = $property->getType()?->getName() ?? 'unknown';

        if ($typeName === 'unknown') {
            $docComment = $property->getDocComment();
            $hasTypeHint = \str_contains($docComment, '@var');

            if ($hasTypeHint) {
                $typeName = \str_replace(['@var', ' ', "\n", "\r"], '', $docComment);
            }
        }

        return [
            $name,
            $typeName,
        ];
    }

    public function serialize(mixed $object): array
    {
        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();

        $payload = [];
        foreach ($properties as $property) {
            $attributes = $property->getAttributes(Skip::class);
            $hasSkipAttribute = \count($attributes) > 0;
            if ($hasSkipAttribute) {
                continue;
            }

            $property->setAccessible(true);
            $value = $property->getValue($object);

            [$propertyName, $typeName] = $this->getPropertyData($property);

            if (\is_null($value)) {
                $keyName = $this->keyFormatter->propertyNameToKey($propertyName);
                $payload[$keyName] = null;
                continue;
            }

            $serializer = $this->getPropertySerializer($value, $typeName)();
            $serializedValue = $serializer->serialize($value, $this);

            $isAutonomous = $serializer instanceof AutonomousPropertySerializer;

            if ($isAutonomous) {
                $keyName = $this->keyFormatter->propertyNameToKey($propertyName);
                $payload[$keyName] = $serializedValue;

                continue;
            }

            if (\is_array($serializedValue)) {
                foreach ($serializedValue as $key => $item) {
                    $keyName = $this->keyFormatter->propertyNameToKey($propertyName . ucfirst($key));
                    $payload[$keyName] = $item;
                }

                continue;
            }

            $keyName = $this->keyFormatter->propertyNameToKey($propertyName);
            $payload[$keyName] = $serializedValue;
        }

        return $payload;
    }

    public function withKeyFormatter(KeyFormatter $keyFormatter): Serializer
    {
        return new self(
            $this->propertySerializers,
            $keyFormatter
        );
    }
}
