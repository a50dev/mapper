<?php

declare(strict_types=1);

namespace A50\Mapper\Serializers;

use A50\Mapper\PropertySerializer;
use A50\Mapper\Serializer;

final class ObjectPropertySerializer implements PropertySerializer
{
    public function serialize(mixed $value, Serializer $serializer): mixed
    {
        $data = $serializer->serialize($value);

        if (\count($data) > 1) {
            return $data;
        }

        return \array_shift($data);
    }
}
