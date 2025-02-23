<?php

declare(strict_types=1);

namespace A50\Mapper\KeyFormatters;

use A50\Mapper\KeyFormatter;

use function lcfirst;
use function str_replace;
use function strtolower;
use function ucwords;

final class KeyFormatterForCamelCasing implements KeyFormatter
{
    public function propertyNameToKey(string $propertyName): string
    {
        return lcfirst(str_replace('_', '', ucwords($propertyName, '_')));
    }

    public function keyToPropertyName(string $key): string
    {
        return strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $key));
    }
}
