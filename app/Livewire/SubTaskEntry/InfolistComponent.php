<?php

namespace App\Livewire\SubTaskEntry;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;

class InfolistComponent extends Component implements HasSchemas, HasActions
{
    use InteractsWithSchemas;
    use InteractsWithActions;

    public array $subTasks;
    public Task $parentTask;

    public ?array $data = [];


    public function mount(Task $parentTask, array $subTasks = [])
    {
        $this->subTasks = $subTasks;
        $this->parentTask = $parentTask;
        $this->form->fill([
            'taskable_id' => $parentTask->taskable_id,
            'taskable_type' => $parentTask->taskable_type,
            'parent_id' => $parentTask->id,
            'assigned_to_id' => $parentTask->assigned_to_id,
            'status' => TaskStatus::TODO,
            'priority' => TaskPriority::MEDIUM,
            'start_date' => now(),
            'subTasks' => $subTasks
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('taskable_id'),
                Hidden::make('taskable_type'),
                Hidden::make('parent_id'),
                Hidden::make('assigned_to_id'),
                Section::make("New sub task")
                    ->collapsed()
                    ->components([
                        TextInput::make('title'),
                        Grid::make([
                            'sm' => 2
                        ])
                            ->schema([
                                Select::make('status')
                                    ->default(TaskStatus::TODO)
                                    ->options(TaskStatus::class),
                                Select::make('priority')
                                    ->default(TaskPriority::MEDIUM)
                                    ->options(TaskPriority::class),
                                TextInput::make('estimated_days')
                                    ->numeric(),
                                DatePicker::make('start_date')
                                    ->default(now()),
                                DatePicker::make('due_date'),
                            ]),
                        MarkdownEditor::make('description'),
                    ])
                    ->footerActions([
                        Action::make('Create')
                            ->action(function (Get $get, Set $set) {
                                $getData = $get();

                                try {

                                    $subTaskData = [
                                        'taskable_id' => $getData['taskable_id'],
                                        'taskable_type' => $getData['taskable_type'],
                                        'parent_id' => $getData['parent_id'],
                                        'assigned_to_id' => $getData['assigned_to_id'],
                                        'title' => $getData['title'],
                                        'description' => $getData['description'],
                                        'status' => $getData['status'],
                                        'priority' => $getData['priority'],
                                        'estimated_days' => $getData['estimated_days'],
                                        'start_date' => $getData['start_date'],
                                        'due_date' => $getData['due_date'],
                                    ];

                                    $newSubTask = Task::create($subTaskData);

                                    $subTasks = [
                                        Str::uuid()->toString() => $newSubTask->toArray(),
                                        ...$getData['subTasks']
                                    ];

                                    $set('subTasks', $subTasks);

                                    Notification::make()
                                        ->title("Success")
                                        ->body("Sub task added successfully")
                                        ->success()
                                        ->send();
                                } catch (\Throwable $th) {
                                    Log::error("Failed to create sub task", [
                                        'data' => $getData,
                                        'error' => [
                                            'code' => $th->getCode(),
                                            'message' => $th->getMessage(),
                                            'trace' => $th->getTrace()
                                        ],
                                    ]);
                                    Notification::make()
                                        ->title("Error")
                                        ->body("Failed to add sub task")
                                        ->danger()
                                        ->send();
                                }
                            })
                    ]),

                // 

                Section::make()
                    ->contained(false)
                    ->compact()
                    ->schema([
                        Repeater::make('subTasks')
                            ->hiddenLabel()
                            ->addable(false)
                            ->reorderable()
                            ->collapsed()
                            ->orderColumn('position')
                            ->schema([
                                Hidden::make('title'),
                                Hidden::make('description'),
                                Hidden::make('status'),
                                Hidden::make('priority'),
                                Grid::make([
                                    'sm' => 2
                                ])
                                    ->schema([
                                        TextEntry::make('status')
                                            ->hiddenLabel()
                                            ->dehydrated(false)
                                            ->badge(),
                                        TextEntry::make('priority')
                                            ->hiddenLabel()
                                            ->dehydrated(false)
                                            ->badge(),
                                    ]),
                                TextEntry::make('description')
                                    ->hiddenLabel()
                                    ->html()
                                    ->dehydrated(false)
                                    ->columnSpanFull(),

                            ])
                            ->extraItemActions([
                                Action::make('edit')
                                    ->icon(Heroicon::OutlinedPencilSquare)
                                    ->color(Color::Blue)
                                    ->iconButton(),

                            ])
                            ->itemLabel(function (array $state): ?string {
                                if (
                                    !is_array($state) ||
                                    !isset($state['title']) ||
                                    is_null($state['title'])
                                ) {
                                    return null;
                                }
                                return $state['title'];
                            }),
                    ]),
            ])
            ->statePath('data');
    }

    public function subTasksInfolist(Schema $schema): Schema
    {
        return $schema
            ->record([
                'subTasks' => $this->subTasks
            ])
            ->components([]);
    }
    public function render()
    {
        return view('livewire.sub-task-entry.infolist-component');
    }
}
