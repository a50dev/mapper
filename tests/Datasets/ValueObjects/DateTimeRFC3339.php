<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

final class DateTimeRFC3339
{
    private const DATE_FORMAT = DateTimeInterface::RFC3339;
    private string $dateTime;

    private function __construct(string $dateTime)
    {
        if (!DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $dateTime)) {
            throw new InvalidArgumentException(
                sprintf('Invalid date provided: %s. Expected format: %s', $dateTime, self::DATE_FORMAT)
            );
        }

        $this->dateTime = $dateTime;
    }

    public static function fromString(string $dateTime): self
    {
        return new self($dateTime);
    }

    public static function fromDateTimeImmutable(DateTimeImmutable $dateTime): self
    {
        return new self($dateTime->format(self::DATE_FORMAT));
    }

    public function asString(): string
    {
        return $this->dateTime;
    }
}
