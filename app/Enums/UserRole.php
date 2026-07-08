<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SELLER = 'seller'; // <-- Tambahkan baris ini
    case USER = 'user';
}