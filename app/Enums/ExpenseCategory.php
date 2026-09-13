<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ExpenseCategory: string implements HasLabel
{
    case FoodDrinks = 'Food & Drinks';
    case Utilities = 'Utilities';
    case Software = 'Software';
    case Travel = 'Travel';
    case OfficeSupplies = 'Office Supplies';
    case General = 'General';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
