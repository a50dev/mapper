<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets;

use DateTimeImmutable;

final class WithoutTypeName
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var DateTimeImmutable
     */
    protected $expiryDateTime;

    /**
     * @var string
     */
    protected $userIdentifier;

    public function __construct(string $identifier, DateTimeImmutable $expiryDateTime, string $userIdentifier)
    {
        $this->identifier = $identifier;
        $this->expiryDateTime = $expiryDateTime;
        $this->userIdentifier = $userIdentifier;
    }
}
