<?php

declare(strict_types=1);

namespace A50\Mapper;

interface PropertyHydrator
{
    public function hydrate(mixed $value, string $className, string $keyName, Hydrator $hydrator): mixed;
}
