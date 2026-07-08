<?php

namespace App\Enums;

enum PaymentCategory: string
{
    case FEATURED = 'featured';
    case PREMIUM = 'premium';
    case REKBER = 'rekber';
    case COMMISSION = 'commission';
    case REFUND = 'refund';
}