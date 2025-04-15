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
        private readonly string $format = DATE_ATOM
    ) {
    }

    public function serialize(mixed $value, Serializer $serializer): mixed
    {
        if (\is_null($value)) {
            return null;
        }

        Assert::isInstanceOf($value, DateTimeImmutable::class);

        return $value->format($this->format);
    }
}
