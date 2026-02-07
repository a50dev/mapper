<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets;

use A50\Mapper\Tests\Datasets\ValueObjects\Id;

final readonly class MessageWithNullableId
{
    private function __construct(
        private Id $userId,
        private ?Id $workspaceId,
    ) {
    }

    public static function create(Id $userId, ?Id $workspaceId): self
    {
        return new self($userId, $workspaceId);
    }

    public function isEqualTo(MessageWithNullableId $other): bool
    {
        return $other->userId->asString() === $this->userId->asString()
            && $other->workspaceId?->asString() === $this->workspaceId?->asString();
    }
}
