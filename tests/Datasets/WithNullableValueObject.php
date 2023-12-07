<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets;

use A50\Mapper\Tests\Datasets\ValueObjects\Id;
use A50\Mapper\Tests\Datasets\ValueObjects\LastName;

final class WithNullableValueObject
{
    private function __construct(
        private readonly Id $id,
        private readonly ?LastName $lastName,
    ) {
    }

    public static function create(Id $id, ?LastName $lastName): self
    {
        return new self($id, $lastName);
    }

    public function isEqualTo(WithNullableValueObject $other): bool
    {
        return $other->id->asString() === $this->id->asString()
            && $other->lastName?->asString() === $this->lastName?->asString();
    }
}
