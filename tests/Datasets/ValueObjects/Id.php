<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets\ValueObjects;

use Webmozart\Assert\Assert;

final class Id
{
    private string $uuid;

    private function __construct(string $uuid)
    {
        Assert::uuid($uuid);
        $this->uuid = $uuid;
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    /**
     * @return non-empty-string
     */
    public function asString(): string
    {
        return $this->uuid;
    }
}
