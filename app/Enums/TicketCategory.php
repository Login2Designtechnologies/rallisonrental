<?php

namespace App\Enums;

class TicketCategory
{
    public const INVOICE = 'invoice';
    public const ACCOUNT = 'account';
    public const TECHNICAL = 'technical';

    public const ALL = [
        self::INVOICE,
        self::ACCOUNT,
        self::TECHNICAL,
    ];
}
