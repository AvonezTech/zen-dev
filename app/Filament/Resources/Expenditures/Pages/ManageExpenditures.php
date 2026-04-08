<?php

namespace App\Filament\Resources\Expenditures\Pages;

use App\Filament\Resources\Expenditures\ExpenditureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageExpenditures extends ManageRecords
{
    protected static string $resource = ExpenditureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
