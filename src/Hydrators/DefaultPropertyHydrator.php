<?php

declare(strict_types=1);

namespace A50\Mapper\Hydrators;

use A50\Mapper\Hydrator;
use A50\Mapper\PropertyHydrator;

final class DefaultPropertyHydrator implements PropertyHydrator
{
    public function hydrate(mixed $value, string $className, string $keyName, Hydrator $hydrator): mixed
    {
        return $value[$keyName];
    }
}
