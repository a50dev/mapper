<?php

declare(strict_types=1);

namespace A50\Mapper;

interface PropertySerializer
{
    public function serialize(mixed $value, Serializer $serializer): mixed;
}
