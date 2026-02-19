<?php

namespace App\Enums\Task;

use App\Traits\Enums\HasDashedEnum;
use Filament\Support\Contracts\HasLabel;

enum TaskPriority: string implements HasLabel
{
    use HasDashedEnum;

    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';
}
