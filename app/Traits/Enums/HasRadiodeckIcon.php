<?php

namespace App\Traits\Enums;

trait HasRadiodeckIcon
{
    public function getIcons(): ?string {
        return $this->getIcon()->value;
    }

    public static function getRadiodeckIcons(): array
    {
        return collect(static::cases())
            ->mapWithKeys(function ($enum) {
                return [
                    $enum->value => 'heroicon-' . $enum->getIcon()->value
                ];
            })
            ->toArray();
    }
}
