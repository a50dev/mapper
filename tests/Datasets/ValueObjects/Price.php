<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets\ValueObjects;

use MonorepoBuilderPrefix202304\Webmozart\Assert\Assert;

final class Price
{
    private int $amount;
    private string $currency;

    private function __construct(int $amount, string $currency)
    {
        Assert::integer($amount);
        Assert::greaterThanEq($amount, 0);
        Assert::stringNotEmpty($currency);
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function of(int $amount, string $currency): self
    {
        return new self($amount, $currency);
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function isEqualTo(Price $other): bool
    {
        return $this->amount === $other->amount
            && $this->currency === $other->currency;
    }
}
