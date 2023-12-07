<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets;

use A50\Mapper\Tests\Datasets\ValueObjects\DateTimeRFC3339;
use A50\Mapper\Tests\Datasets\ValueObjects\Id;
use A50\Mapper\Tests\Datasets\ValueObjects\PostTitle;
use A50\Mapper\Tests\Datasets\ValueObjects\Price;

final class WithValueObjects
{
    private function __construct(
        private readonly Id $id,
        private readonly PostTitle $title,
        private readonly Price $price,
        private readonly DateTimeRFC3339 $createdAt,
    ) {
    }

    public static function create(Id $id, PostTitle $title, Price $price, DateTimeRFC3339 $createdAt): self
    {
        return new self($id, $title, $price, $createdAt);
    }

    public function isEqualTo(WithValueObjects $other): bool
    {
        return $other->id->asString() === $this->id->asString()
            && $other->title->asString() === $this->title->asString()
            && $other->price->isEqualTo($this->price)
            && $other->createdAt->asString() === $this->createdAt->asString();
    }
}
