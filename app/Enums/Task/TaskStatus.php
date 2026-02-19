<?php

namespace App\Enums\Task;

use App\Traits\Enums\HasDashedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TaskStatus: string implements HasLabel, HasColor
{
    use HasDashedEnum;

        // todo, in-progress, review, completed, blocked

    case TODO = 'todo';
    case IN_PROGRESS = 'in-progress';
    case REVIEW = 'review';
    case COMPLETED = 'completed';
    case BLOCKED = 'blocked';
    case CANCELLED = 'cancelled';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::TODO => Color::Gray,
            self::IN_PROGRESS => Color::Blue,
            self::REVIEW => Color::Amber,
            self::COMPLETED => Color::Green,
            self::BLOCKED => Color::Red,
            self::CANCELLED => Color::Red,
        };
    }
}
