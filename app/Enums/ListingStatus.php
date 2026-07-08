<?php

namespace App\Enums;

enum ListingStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
    case RESERVED = 'reserved';
    case SOLD = 'sold';
    case EXPIRED = 'expired';
}