<?php

namespace App\Enums\Task;

use App\Traits\Enums\HasDashedEnum;
use Filament\Support\Contracts\HasLabel;

enum TaskStatus: string implements HasLabel
{
    use HasDashedEnum;
    
    // todo, in-progress, review, completed, blocked

    case TODO = 'todo';
    case IN_PROGRESS = 'in-progress';
    case REVIEW = 'review';
    case COMPLETED = 'completed';
    case BLOCKED = 'blocked';
}
