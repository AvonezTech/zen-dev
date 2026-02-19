<?php

namespace App\Enums\Project;

use App\Traits\Enums\HasDashedEnum;
use Filament\Support\Contracts\HasLabel;


enum ProjectStatus: string implements HasLabel
{
    use HasDashedEnum;

    case PLANNING = 'planning';
    case ACTIVE = 'active';
    case ON_HOLD = 'on-hold';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
