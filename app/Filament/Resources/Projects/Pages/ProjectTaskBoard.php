<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;
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
            Action::make('newTask')
                ->outlined()
                ->slideOver()
                ->color('info')
                ->icon(Heroicon::OutlinedPlus)
                ->modalIcon(Heroicon::OutlinedPlus)
                ->schema([
                    Section::make()
                        ->contained(false)
                        ->columns(2)
                        ->schema([
                            TextInput::make('title')
                                ->columnSpanFull()
                                ->required(),
                            Select::make('status')
                                ->options(TaskStatus::class)
                                ->required(),
                            Select::make('priority')
                                ->options(TaskPriority::class)
                                ->required(),
                            Select::make('assigned_to_id')
                                ->label('AssignedTo')
                                ->options(
                                    User::pluck('name', 'id')
                                )
                                ->preload()
                                ->searchable()
                                ->required(),
                            TextInput::make('estimated_days')
                                ->numeric()
                                ->required(),
                            DatePicker::make('start_date')
                                ->required()
                                ->default(now()),
                            DatePicker::make('due_date')
                                ->required(),
                            RichEditor::make('description')
                                ->columnSpanFull()
                                ->required(),
                        ]),
                ])
                ->action(function (array $data) {
                    try {
                        $data['project_id'] = $this->getRecord()?->id;
                        Task::create($data);
                        Notification::make()
                            ->title('Success')
                            ->body("New task added")
                            ->success()
                            ->send();
                    } catch (\Throwable $th) {
                        Log::error("Failed to create task", [
                            'data' => $data,
                            'error' => [
                                'code' => $th->getCode(),
                                'message' => $th->getMessage(),
                                'trace' => $th->getTrace()
                            ]
                        ]);
                        Notification::make()
                            ->title('Failed')
                            ->body("Failed to add new task")
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function board(Board $board): Board
    {
        return $board
            ->query(
                // Get tasks for this specific campaign and current user's team
                Task::with('assignedTo')
                    ->where('project_id', $this->getRecord()?->id)
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
            ->cardSchema(function (Schema $schema) {
                return $schema
                    ->columns(2)
                    ->components([
                        TextEntry::make('assignedTo.name')
                            ->badge()
                            ->hiddenLabel(),
                        TextEntry::make('due_date')
                            ->hiddenLabel()
                            ->badge()
                            ->date(),
                    ]);
            });
    }
}
