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
use JaOcero\RadioDeck\Contracts\HasIcons;

enum ProjectPriority: string implements HasLabel, HasColor, HasIcon, HasIcons
{
    use HasDashedEnum;
    use HasRadiodeckIcon;

    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::LOW => Color::Green,
            self::MEDIUM => Color::Yellow,
            self::HIGH => Color::Orange,
            self::CRITICAL => Color::Red,
            default => null
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::LOW => Heroicon::OutlinedBars2,
            self::MEDIUM => Heroicon::OutlinedBars3,
            self::HIGH => Heroicon::OutlinedBellAlert,
            self::CRITICAL => Heroicon::OutlinedExclamationCircle,
            default => null
        };
    }
}
