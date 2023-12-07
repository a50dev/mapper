<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use A50\Mapper\Hydrators\DateTimeImmutablePropertyHydrator;
use A50\Mapper\Hydrators\DefaultPropertyHydrator;
use A50\Mapper\Hydrators\EnumPropertyHydrator;
use A50\Mapper\Hydrators\ObjectHydratorUsingReflection;
use A50\Mapper\Hydrators\ObjectPropertyHydrator;
use A50\Mapper\KeyFormatters\KeyFormatterForSnakeCasing;
use A50\Mapper\Tests\Datasets\ValueObjects\DateTimeRFC3339;
use A50\Mapper\Tests\Datasets\ValueObjects\Id;
use A50\Mapper\Tests\Datasets\ValueObjects\PaymentStatusString;
use A50\Mapper\Tests\Datasets\ValueObjects\PostTitle;
use A50\Mapper\Tests\Datasets\ValueObjects\Price;
use A50\Mapper\Tests\Datasets\ValueObjects\PublishedStatusInt;
use A50\Mapper\Tests\Datasets\WithNullableValueObject;
use A50\Mapper\Tests\Datasets\WithScalarAndDateTimeImmutable;
use A50\Mapper\Tests\Datasets\WithScalarAndStatusEnum;
use A50\Mapper\Tests\Datasets\WithSomeNullableValueObjects;
use A50\Mapper\Tests\Datasets\WithValueObjects;
use A50\Mapper\Tests\Datasets\WithValueObjectsAndSkipAttribute;

/**
 * @internal
 */
final class HydratorTest extends TestCase
{
    public function providesData(): array
    {
        return [
            'WithScalarAndDateTimeImmutable' => [
                WithScalarAndDateTimeImmutable::class,
                [
                    'id' => '1',
                    'status' => '1',
                    'is_available' => false,
                    'created_at' => '2021-01-01 00:00:00',
                ],
                new WithScalarAndDateTimeImmutable(
                    id: '1',
                    status: 1,
                    isAvailable: false,
                    createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                ),
            ],
            'WithScalarAndStatusEnum' => [
                WithScalarAndStatusEnum::class,
                [
                    'id' => '1',
                    'published_status' => 1,
                    'payment_status' => 'paid',
                    'created_at' => '2021-01-01 00:00:00',
                ],
                new WithScalarAndStatusEnum(
                    id: '1',
                    publishedStatus: PublishedStatusInt::PUBLISHED,
                    paymentStatus: PaymentStatusString::PAID,
                    createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                ),
            ],
            'WithValueObjects' => [
                WithValueObjects::class,
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'title' => 'Hello World',
                    'price_amount' => 100,
                    'price_currency' => 'USD',
                    'created_at' => '2023-05-11T00:00:00+08:00',
                ],
                WithValueObjects::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    PostTitle::fromString('Hello World'),
                    Price::of(100, 'USD'),
                    DateTimeRFC3339::fromString('2023-05-11T00:00:00+08:00'),
                ),
            ],
            'WithSomeNullableValueObjects' => [
                WithSomeNullableValueObjects::class,
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'title' => 'Hello World',
                    'price_amount' => 100,
                    'price_currency' => 'USD',
                    'created_at' => null,
                ],
                WithSomeNullableValueObjects::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    PostTitle::fromString('Hello World'),
                    Price::of(100, 'USD'),
                    null,
                ),
            ],
            'WithNullableValueObject' => [
                WithNullableValueObject::class,
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'last_name' => null,
                ],
                WithNullableValueObject::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    null,
                ),
            ],
            'WithValueObjectsAndSkipAttribute' => [
                WithValueObjectsAndSkipAttribute::class,
                [
                    'id' => '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                    'title' => 'Hello World',
                    'price_amount' => 100,
                    'price_currency' => 'USD',
                    'created_at' => '2023-05-11T00:00:00+08:00',
                ],
                WithValueObjectsAndSkipAttribute::create(
                    Id::fromString('9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
                    PostTitle::fromString('Hello World'),
                    Price::of(100, 'USD'),
                    DateTimeRFC3339::fromString('2023-05-11T00:00:00+08:00'),
                ),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providesData
     */
    public function shouldHydrate(string $className, array $data, object $expected): void
    {
        $hydrator = new ObjectHydratorUsingReflection(
            propertyHydrators: [
                DateTimeImmutable::class => static fn () => new DateTimeImmutablePropertyHydrator(),
                'string' => static fn () => new DefaultPropertyHydrator(),
                'int' => static fn () => new DefaultPropertyHydrator(),
                'bool' => static fn () => new DefaultPropertyHydrator(),
                'enum' => static fn () => new EnumPropertyHydrator(),
                'object' => static fn () => new ObjectPropertyHydrator(
                    keyFormatter: new KeyFormatterForSnakeCasing(),
                ),
            ],
            keyFormatter: new KeyFormatterForSnakeCasing(),
        );

        $object = $hydrator->hydrate($className, $data);

        Assert::assertObjectEquals($expected, $object, 'isEqualTo');
    }
}
