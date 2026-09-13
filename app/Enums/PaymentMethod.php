<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Cash = 'Cash';
    case BankTransfer = 'Bank Transfer';
    case DigitalWallet = 'Digital Wallet';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
