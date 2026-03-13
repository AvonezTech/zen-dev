<?php

namespace App\Enums\Task;

use App\Models\PersonalBoard;
use App\Models\Project;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TaskableType: string implements HasLabel
{
    case PROJECT = Project::class;
    case PERSONAL = PersonalBoard::class;

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::PROJECT => 'Project',
            self::PERSONAL => 'Personal',
        };
    }
    
}
