<?php

namespace App\Enum;

enum NotificationType: string
{
    case news = 'news';
    case promotion = 'promotion';
    case promotion_discount = 'promotion_discount';
    case coupon_discount = 'coupon_discount';
    case order = 'order';
    case order_checkout = 'order_checkout';
}
