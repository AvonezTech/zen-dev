<?php

namespace App\Filament\Resources\DailyExpenses\Pages;

use App\Filament\Resources\DailyExpenses\DailyExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyExpenses extends ListRecords
{
    protected static string $resource = DailyExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
