<?php

namespace App\Enums\Project;

use App\Traits\Enums\HasDashedEnum;
use Filament\Support\Contracts\HasLabel;

enum ProjectPriority: string implements HasLabel
{
    use HasDashedEnum;

    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';
}
