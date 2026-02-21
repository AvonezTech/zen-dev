<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('tasks')
                ->outlined()
                ->color('info')
                ->icon(Heroicon::OutlinedListBullet)
                ->label("Tasks View")
                ->url(
                    ProjectTaskBoard::getUrl([
                        'record' => $this->getRecord()
                    ])
                ),
        ];
    }
}
