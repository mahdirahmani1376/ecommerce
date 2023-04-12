<?php

namespace App\Enums;

enum VoucherEnum: string
{
    case giftCard = 'gift card';
    case coupon = 'coupon';

    public static function getAllValues(): array
    {
        return array_column(VoucherEnum::cases(), 'value');
    }
}
