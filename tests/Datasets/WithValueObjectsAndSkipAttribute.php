<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets;

use A50\Mapper\EventDispatcher\EventRecordingCapabilities;
use A50\Mapper\Tests\Datasets\ValueObjects\DateTimeRFC3339;
use A50\Mapper\Tests\Datasets\ValueObjects\Id;
use A50\Mapper\Tests\Datasets\ValueObjects\PostTitle;
use A50\Mapper\Tests\Datasets\ValueObjects\Price;

final class WithValueObjectsAndSkipAttribute
{
    use EventRecordingCapabilities;

    private function __construct(
        private readonly Id $id,
        private readonly PostTitle $title,
        private readonly Price $price,
        private readonly DateTimeRFC3339 $createdAt,
    ) {
    }

    public static function create(Id $id, PostTitle $title, Price $price, DateTimeRFC3339 $createdAt): self
    {
        $self = new self($id, $title, $price, $createdAt);

        $self->registerThat(new class () {
            public function asString(): string
            {
                return 'Some Event';
            }
        });

        return $self;
    }

    public function isEqualTo(WithValueObjectsAndSkipAttribute $other): bool
    {
        return $other->id->asString() === $this->id->asString()
            && $other->title->asString() === $this->title->asString()
            && $other->price->isEqualTo($this->price)
            && $other->createdAt->asString() === $this->createdAt->asString();
    }
}
