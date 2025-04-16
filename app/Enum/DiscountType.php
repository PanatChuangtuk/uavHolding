<?php

namespace App\Enum;

enum DiscountType: string
{
    case AMOUNT = 'amount';
    case PERCENTAGE = 'percentage';
}
