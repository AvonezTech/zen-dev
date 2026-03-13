<?php

namespace App\Filament\Pages;

use App\Enums\Task\TaskStatus;
use App\Filament\Actions\Tasks\CreateNewTaskAction;
use App\Filament\Actions\Tasks\EditTaskAction;
use App\Filament\Actions\Tasks\ViewTaskAction;
use App\Models\Project;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardResourcePage;
use Relaticle\Flowforge\Column;

class TaskBoardPage extends BoardResourcePage
{
    use InteractsWithRecord;

    use InteractsWithHeaderActions;

    public function getTitle(): string | Htmlable
    {
        return 'Tasks';
    }


    public function mount(int | string | null $record): void
    {
        if (!$record) {
            $this->record = null;
        } else {
            $this->record = $this->resolveRecord($record);
        }
    }

    protected function getHeaderActions(): array
    {
        if (
            $this->hasRecord() &&
            isset(static::$resource)
        ) {
            return [
                CreateNewTaskAction::make([
                    'taskable_id' => $this->getRecord()?->id,
                    'taskable_type' => get_class($this->getRecord()),
                ]),
                ActionGroup::make([
                    Action::make('view')
                        ->outlined()
                        ->icon(Heroicon::OutlinedViewfinderCircle)
                        ->url(static::$resource::getUrl('view', [
                            'record' => $this->getRecord()
                        ])),
                    Action::make('edit')
                        ->outlined()
                        ->icon(Heroicon::OutlinedPencilSquare)
                        ->url(static::$resource::getUrl('edit', [
                            'record' => $this->getRecord()
                        ])),
                ]),
            ];
        } else {
            return [];
        }
    }

    public function board(Board $board): Board
    {
        return $board
            ->query(function () {
                if (!$this->hasRecord()) {
                    return Task::where('id', 0);
                }

                // Get tasks for this specific campaign and current user's team
                return Task::with([
                    'assignedTo',
                    'subTasks'
                ])
                    ->where('taskable_id', $this->getRecord()?->id)
                    ->where('taskable_type', get_class($this->getRecord()))
                    ->whereNull('parent_id');
            })
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
            ->filtersLayout(FiltersLayout::BelowContent)
            ->filtersFormColumns(1)
            ->filters([
                Filter::make('assigned_to_me')
                    ->label('Assigned to me')
                    ->query(function (Builder $query) {
                        return $query->where('assigned_to_id', Auth::id());
                    })
                    ->hidden(function () {
                        if (!$this->hasRecord()) {
                            return true;
                        }
                        return is_a($this->getRecord(), Project::class);
                    })
                    ->toggle(),
            ]);
    }
}
