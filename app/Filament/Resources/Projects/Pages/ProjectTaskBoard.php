<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use App\Filament\Actions\Tasks\CreateNewTaskAction;
use App\Filament\Actions\Tasks\EditTaskAction;
use App\Filament\Actions\Tasks\ViewTaskAction;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardResourcePage;
use Relaticle\Flowforge\Column;

class ProjectTaskBoard extends BoardResourcePage
{
    use InteractsWithRecord;

    use InteractsWithHeaderActions;

    protected static string $resource = ProjectResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateNewTaskAction::make([
                'project_id' => $this->getRecord()?->id
            ]),
            ActionGroup::make([
                Action::make('view')
                    ->outlined()
                    ->icon(Heroicon::OutlinedViewfinderCircle)
                    ->url(ViewProject::getUrl([
                        'record' => $this->getRecord()
                    ])),
                Action::make('edit')
                    ->outlined()
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->url(EditProject::getUrl([
                        'record' => $this->getRecord()
                    ])),
            ]),
        ];
    }

    public function board(Board $board): Board
    {
        return $board
            ->query(
                // Get tasks for this specific campaign and current user's team
                Task::with([
                    'assignedTo',
                    'subTasks'
                ])
                    ->where('project_id', $this->getRecord()?->id)
                    ->whereNull('parent_id')
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
                    ->values()
                    ->toArray(),
            )
            ->cardActions([
                EditTaskAction::make(),
                ViewTaskAction::make(),
            ])
            ->cardAction('viewTask')
            ->cardSchema(function (Schema $schema) {
                return $schema
                    ->components([
                        TextEntry::make('assignedTo.name')
                            ->badge()
                            ->prefix('@')
                            ->columnSpanFull()
                            ->hiddenLabel(),
                        Grid::make(2)
                            ->gap(false)
                            ->schema([
                                TextEntry::make('due_date')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->date(),
                                TextEntry::make('priority')
                                    ->hiddenLabel()
                                    ->badge(),
                            ]),
                    ]);
            })
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filters([
                SelectFilter::make('priority')
                    ->options(TaskPriority::class)
                    ->multiple(),
                Filter::make('assigned_to_me')
                    ->label('Assigned to me')
                    ->query(function (Builder $query) {
                        return $query->where('assigned_to_id', Auth::id());
                    })
                    ->toggle(),
            ]);
    }
}
