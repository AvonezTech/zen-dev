<?php

namespace App\Enums\Project;

use App\Traits\Enums\HasDashedEnum;
use App\Traits\Enums\HasRadiodeckIcon;
use BackedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ProjectStatus: string implements HasLabel, HasIcon, HasColor
{
    use HasDashedEnum;
    use HasRadiodeckIcon;

    case PLANNING = 'planning';
    case ACTIVE = 'active';
    case ON_HOLD = 'on-hold';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::PLANNING => Heroicon::OutlinedInformationCircle,
            self::ACTIVE => Heroicon::OutlinedPlayCircle,
            self::ON_HOLD => Heroicon::OutlinedPauseCircle,
            self::COMPLETED => Heroicon::OutlinedCheckCircle,
            self::CANCELLED => Heroicon::OutlinedXCircle,
            default => null
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PLANNING => Color::Indigo,
            self::ACTIVE => Color::Blue,
            self::ON_HOLD => Color::Yellow,
            self::COMPLETED => Color::Green,
            self::CANCELLED => Color::Red,
            default => null
        };
    }
}
