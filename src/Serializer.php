<?php

declare(strict_types=1);

namespace A50\Mapper;

interface Serializer
{
    public function serialize(?object $object): array;

    public function withKeyFormatter(KeyFormatter $keyFormatter): self;
}
