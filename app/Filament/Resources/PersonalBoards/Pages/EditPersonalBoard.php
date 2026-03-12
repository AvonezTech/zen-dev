<?php

namespace App\Filament\Resources\PersonalBoards\Pages;

use App\Filament\Resources\PersonalBoards\PersonalBoardResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonalBoard extends EditRecord
{
    protected static string $resource = PersonalBoardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
