<?php

namespace App\Filament\Resources\PersonalBoards\Pages;

use App\Filament\Resources\PersonalBoards\PersonalBoardResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewPersonalBoard extends ViewRecord
{
    protected static string $resource = PersonalBoardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('tasks')
                ->outlined()
                ->color('info')
                ->icon(Heroicon::OutlinedListBullet)
                ->label("Tasks View")
                ->url(
                    PersonalBoardTaskBoard::getUrl([
                        'record' => $this->getRecord()
                    ])
                ),
        ];
    }
}
