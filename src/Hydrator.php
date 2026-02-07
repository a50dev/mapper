<?php

declare(strict_types=1);

namespace A50\Mapper;

interface Hydrator
{
    public function hydrate(string $className, array $data): object;

    public function withKeyFormatter(KeyFormatter $keyFormatter): self;
}
