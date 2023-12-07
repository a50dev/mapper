<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets;

use DateTimeImmutable;

final class WithScalarAndDateTimeImmutable
{
    public function __construct(
        private readonly string $id,
        private readonly int $status,
        private readonly bool $isAvailable,
        private readonly DateTimeImmutable $createdAt,
    ) {
    }

    public function isEqualTo(WithScalarAndDateTimeImmutable $other): bool
    {
        return $other->id === $this->id
            && $other->status === $this->status
            && $other->isAvailable === $this->isAvailable
            && $other->createdAt->format('Y-m-d H:i:s') === $this->createdAt->format('Y-m-d H:i:s');
    }
}
