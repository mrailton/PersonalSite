<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Overdue = 'overdue';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
}
