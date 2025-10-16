<?php

namespace App\Enums;

class TicketStatus
{
    public const OPEN = 'open';
    public const IN_PROGRESS = 'in_progress';
    public const CLOSED = 'closed';
    public const RESOLVED = 'resolved';
    public const REOPEN = 'reopen';

    public const ALL = [
        self::OPEN,
        self::IN_PROGRESS,
        self::CLOSED,
        self::RESOLVED,
        self::REOPEN,
    ];
}
