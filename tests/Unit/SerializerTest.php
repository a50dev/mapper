<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use A50\Mapper\KeyFormatters\KeyFormatterForSnakeCasing;
use A50\Mapper\Serializers\DateTimeImmutablePropertySerializer;
use A50\Mapper\Serializers\DefaultPropertySerializer;
use A50\Mapper\Serializers\EnumPropertySerializer;
use A50\Mapper\Serializers\ObjectPropertySerializer;
use A50\Mapper\Serializers\ObjectSerializerUsingReflection;
use A50\Mapper\Tests\Datasets\ValueObjects\DateTimeRFC3339;
use A50\Mapper\Tests\Datasets\ValueObjects\Id;
use A50\Mapper\Tests\Datasets\ValueObjects\PaymentStatusString;
use A50\Mapper\Tests\Datasets\ValueObjects\PostTitle;
use A50\Mapper\Tests\Datasets\ValueObjects\Price;
use A50\Mapper\Tests\Datasets\ValueObjects\PublishedStatusInt;
use A50\Mapper\Tests\Datasets\WithNullableValueObject;
use A50\Mapper\Tests\Datasets\WithoutTypeName;
use A50\Mapper\Tests\Datasets\WithScalarAndDateTimeImmutable;
use A50\Mapper\Tests\Datasets\WithScalarAndStatusEnum;
use A50\Mapper\Tests\Datasets\WithSomeNullableValueObjects;
use A50\Mapper\Tests\Datasets\WithValueObjects;
use A50\Mapper\Tests\Datasets\WithValueObjectsAndSkipAttribute;

/**
 * @internal
 */
final class SerializerTest extends TestCase
{
    public function providesData(): array
    {
        return [
            'WithScalarAndDateTimeImmutable' => [
                new WithScalarAndDateTimeImmutable(
                    id: '1',
                    status: 1,
                    isAvailable: false,
                    createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                ),
                [
                    'id' => '1',
                    'status' => 1,
                    'is_available' => false,
                    'created_at' => '2021-01-01 00:00:00',
                ],
            ],
            'WithScalarAndStatusEnum' => [
                new WithScalarAndStatusEnum(
                    id: '1',
                    publishedStatus: PublishedStatusInt::PUBLISHED,
                    paymentStatus: PaymentStatusString::PAID,
                    createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                ),
                [
                    'id' => '1',
                    'published_status' => 1,
                    'payment_status' => 'paid',
                    'created_at' => '2021-01-01 00:00:00',
                ],
            ],
            'WithValueObjects' => [
                WithValueObjects::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    PostTitle::fromString('Hello World'),
                    Price::of(100, 'USD'),
                    DateTimeRFC3339::fromString('2023-05-11T00:00:00+08:00'),
                ),
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'title' => 'Hello World',
                    'price_amount' => 100,
                    'price_currency' => 'USD',
                    'created_at' => '2023-05-11T00:00:00+08:00',
                ],
            ],
            'WithSomeNullableValueObjects' => [
                WithSomeNullableValueObjects::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    PostTitle::fromString('Hello World'),
                    Price::of(100, 'USD'),
                    null,
                ),
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'title' => 'Hello World',
                    'price_amount' => 100,
                    'price_currency' => 'USD',
                    'created_at' => null,
                ],
            ],
            'WithNullableValueObject' => [
                WithNullableValueObject::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    null,
                ),
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'last_name' => null,
                ],
            ],
            'WithValueObjectsAndSkipAttribute' => [
                WithValueObjectsAndSkipAttribute::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    PostTitle::fromString('Hello World'),
                    Price::of(100, 'USD'),
                    DateTimeRFC3339::fromString('2023-05-11T00:00:00+08:00'),
                ),
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'title' => 'Hello World',
                    'price_amount' => 100,
                    'price_currency' => 'USD',
                    'created_at' => '2023-05-11T00:00:00+08:00',
                ],
            ],
            'WithoutTypeName' => [
                new WithoutTypeName(
                    '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    new DateTimeImmutable('2023-05-11 00:00:00'),
                    '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb61',
                ),
                [
                    'identifier' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'expiry_date_time' => '2023-05-11T00:00:00+08:00',
                    'user_identifier' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb61',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providesData
     */
    public function shouldSerialize(object $object, array $expected): void
    {
        $serializer = new ObjectSerializerUsingReflection(
            propertySerializers: [
                DateTimeImmutable::class => static fn () => new DateTimeImmutablePropertySerializer(),
                'string' => static fn () => new DefaultPropertySerializer(),
                'int' => static fn () => new DefaultPropertySerializer(),
                'bool' => static fn () => new DefaultPropertySerializer(),
                'enum' => static fn () => new EnumPropertySerializer(),
                'object' => static fn () => new ObjectPropertySerializer(),
            ],
            keyFormatter: new KeyFormatterForSnakeCasing(),
        );

        $serializer->serialize($object);

        Assert::assertEquals(
            $expected,
            $serializer->serialize($object)
        );
    }
}
