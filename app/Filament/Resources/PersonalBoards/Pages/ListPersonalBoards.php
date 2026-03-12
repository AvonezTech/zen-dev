<?php

namespace App\Filament\Resources\PersonalBoards\Pages;

use App\Filament\Resources\PersonalBoards\PersonalBoardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonalBoards extends ListRecords
{
    protected static string $resource = PersonalBoardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
