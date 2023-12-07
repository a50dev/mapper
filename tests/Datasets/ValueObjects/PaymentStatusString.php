<?php

declare(strict_types=1);

namespace A50\Mapper\Tests\Datasets\ValueObjects;

enum PaymentStatusString: string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';
}
