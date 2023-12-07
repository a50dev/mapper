<?php

declare(strict_types=1);

namespace A50\Mapper\Serializers;

use A50\Mapper\PropertySerializer;
use A50\Mapper\Serializer;

final class DefaultPropertySerializer implements PropertySerializer
{
    public function serialize(mixed $value, Serializer $serializer): mixed
    {
        return $value;
    }
}
