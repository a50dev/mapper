<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets;

use DateTimeImmutable;
use A50\Mapper\Tests\Datasets\ValueObjects\PaymentStatusString;
use A50\Mapper\Tests\Datasets\ValueObjects\PublishedStatusInt;

final class WithScalarAndStatusEnum
{
    public function __construct(
        private readonly string $id,
        private readonly PublishedStatusInt $publishedStatus,
        private readonly PaymentStatusString $paymentStatus,
        private readonly DateTimeImmutable $createdAt,
    ) {
    }

    public function isEqualTo(WithScalarAndStatusEnum $other): bool
    {
        return $other->id === $this->id
            && $other->publishedStatus->value === $this->publishedStatus->value
            && $other->paymentStatus->value === $this->paymentStatus->value
            && $other->createdAt->format('Y-m-d H:i:s') === $this->createdAt->format('Y-m-d H:i:s');
    }
}
