<?php

declare(strict_types=1);

namespace A50\Mapper\Hydrators;

use ReflectionEnum;
use UnitEnum;
use A50\Mapper\Hydrator;
use A50\Mapper\PropertyHydrator;

final class EnumPropertyHydrator implements PropertyHydrator
{
    public function hydrate(mixed $value, string $className, string $keyName, Hydrator $hydrator): UnitEnum
    {
        $reflectionClass = new ReflectionEnum($className);
        if ($reflectionClass->isBacked()) {
            $backingType = $reflectionClass->getBackingType()->getName();

            return $backingType === 'string' ? $className::from((string)$value[$keyName]) : $className::from((int) $value[$keyName]);
        }

        return $className::from((int)$value[$keyName]);
    }
}
