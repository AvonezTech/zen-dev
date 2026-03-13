<?php

namespace App\Filament\Actions\Tasks;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use App\Filament\Actions\BaseAction;
use App\Models\PersonalBoard;
use App\Models\Task;
use App\Models\User;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;

class EditTaskAction extends BaseAction
{
    protected static function name(array $bindings): string
    {
        return 'editTask';
    }

    protected static function bindingRules(): array
    {
        return [
            // 'taskable_id' => [
            //     'required',
            //     'nullable'
            // ],
            // 'taskable_type' => [
            //     'required',
            //     'nullable'
            // ],
        ];
    }

    protected static function mutateFilamentAction(Action $filamentAction): Action
    {
        return $filamentAction
            ->outlined()
            ->slideOver()
            ->color('info')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->model(Task::class)
            ->modalIcon(Heroicon::OutlinedPencilSquare)
            ->fillForm(function (Task $record) {
                return $record->toArray();
            });
    }

    protected static function schema(array $bindings): array|Closure
    {
        return [
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
                        ->required()
                        ->hidden(function (Task $record) {
                            return $record->taskable_type == PersonalBoard::class;
                        }),
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
        ];
    }

    protected static function action(array $bindings): Closure
    {
        return function (array $data, Task $record) {
            try {
                $record->update($data);
                Notification::make()
                    ->title('Success')
                    ->body("Task edited successfully")
                    ->success()
                    ->send();
            } catch (\Throwable $th) {
                Log::error("Failed to edit task", [
                    'data' => $data,
                    'error' => [
                        'code' => $th->getCode(),
                        'message' => $th->getMessage(),
                        'trace' => $th->getTrace()
                    ]
                ]);
                Notification::make()
                    ->title('Failed')
                    ->body("Failed to edit task")
                    ->danger()
                    ->send();
            }
        };
    }
}
