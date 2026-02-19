<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Enums\Task\TaskStatus;
use App\Filament\Resources\Projects\ProjectResource;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardResourcePage;
use Relaticle\Flowforge\Column;

class ProjectTaskBoard extends BoardResourcePage
{

    protected static string $resource = ProjectResource::class;

    public function board(Board $board): Board
    {
        return $board
            ->query(
                // Get tasks for this specific campaign and current user's team
                $this->getRecord()
                    ->tasks()
                    ->getQuery()
            )
            ->columnIdentifier('status')
            ->positionIdentifier('position')
            ->columns(
                collect(TaskStatus::cases())
                    ->map(function (TaskStatus $status) {
                        return Column::make($status->value)
                            ->label($status->getLabel())
                            ->color($status->getColor());
                    })
                    ->toArray(),
            );
    }
}
