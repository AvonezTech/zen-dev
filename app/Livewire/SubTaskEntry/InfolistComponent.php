<?php

namespace App\Livewire\SubTaskEntry;

use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
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
            'project_id' => $parentTask->project_id,
            'parent_id' => $parentTask->id,
            'subTasks' => $subTasks
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('project_id'),
                Hidden::make('parent_id'),
                Section::make("New sub task")
                    ->collapsed()
                    ->compact()
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        MarkdownEditor::make('description'),
                    ])
                    ->footerActions([
                        Action::make('Create')
                            ->action(function (Get $get) {
                                $subTaskData = [
                                    'project_id' => $get('project_id'),
                                    'parent_id' => $get('parent_id'),
                                    'title' => $get('title'),
                                    'description' => $get('description'),
                                ];
                                dd($get());
                            })
                    ]),

                // 

                Section::make()
                    ->contained(false)
                    ->compact()
                    ->schema([
                        Repeater::make('subTasks')
                            ->hiddenLabel()
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable()
                            ->orderColumn('position')
                            ->schema([
                                Flex::make([
                                    TextEntry::make('title')
                                        ->hiddenLabel()
                                        ->grow()
                                        ->columnSpanFull(),
                                    TextEntry::make('status')
                                        ->hiddenLabel()
                                        ->grow(false)
                                        ->badge(),
                                    TextEntry::make('priority')
                                        ->hiddenLabel()
                                        ->grow(false)
                                        ->badge(),
                                ]),

                            ]),
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
