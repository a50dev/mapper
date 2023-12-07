<?php

declare(strict_types=1);

namespace A50\Mapper\Serializers;

use DateTimeImmutable;
use Webmozart\Assert\Assert;
use A50\Mapper\PropertySerializer;
use A50\Mapper\Serializer;

final class DateTimeImmutablePropertySerializer implements PropertySerializer
{
    public function __construct(
        private readonly string $format = 'Y-m-d H:i:s'
    ) {
    }

    public function serialize(mixed $value, Serializer $serializer): mixed
    {
        Assert::isInstanceOf($value, DateTimeImmutable::class);

        return $value->format($this->format);
    }
}
