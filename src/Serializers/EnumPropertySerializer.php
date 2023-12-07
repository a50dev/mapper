<?php

declare(strict_types=1);

namespace A50\Mapper\Serializers;

use UnitEnum;
use Webmozart\Assert\Assert;
use A50\Mapper\PropertySerializer;
use A50\Mapper\Serializer;

final class EnumPropertySerializer implements PropertySerializer
{
    public function serialize(mixed $value, Serializer $serializer): mixed
    {
        Assert::isInstanceOf($value, UnitEnum::class);

        return $value->value;
    }
}
