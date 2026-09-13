<?php

namespace App\Filament\Resources\DailyExpenses\Pages;

use App\Filament\Resources\DailyExpenses\DailyExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyExpense extends CreateRecord
{
    protected static string $resource = DailyExpenseResource::class;
}
