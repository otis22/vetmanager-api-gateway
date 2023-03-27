<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\Enum\InvoiceDocument;

enum DiscountType: string
{
    case Card = 'card';
    case Promotion = 'promotion';
    case Coupon = 'coupon';
}
