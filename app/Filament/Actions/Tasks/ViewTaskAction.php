<?php

namespace App\Filament\Actions\Tasks;

use App\Filament\Actions\BaseAction;
use App\Filament\Infolists\Components\SubTaskEntry;
use App\Models\Task;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Tilto\Commentable\Filament\Infolists\Components\CommentsEntry;

class ViewTaskAction extends BaseAction
{
    protected static function name(array $bindings): string
    {
        return 'viewTask';
    }

    // protected static function bindingRules(): array{
    //     return [
    //         'task_id' => [
    //             'required', 'exists:tasks,id'
    //         ],
    //     ];
    // }

    protected static function mutateFilamentAction(Action $filamentAction): Action
    {
        return $filamentAction
            ->outlined()
            ->slideOver()
            ->modalWidth(Width::Screen)
            ->color('info')
            ->model(Task::class)
            ->modalFooterActions([])
            ->icon(Heroicon::OutlinedViewfinderCircle)
            ->modalIcon(Heroicon::OutlinedViewfinderCircle);
    }

    protected static function schema(array $bindings): array|Closure
    {
        return [
            TextEntry::make('title')
                ->weight(FontWeight::Bold)
                ->size(TextSize::Large)
                ->hiddenLabel(),
            Grid::make(2)
                ->schema([
                    TextEntry::make('status')
                        ->hiddenLabel()
                        ->badge(),
                    TextEntry::make('priority')
                        ->hiddenLabel()
                        ->badge(),
                ]),
            TextEntry::make('description')
                ->html()
                ->state(function(Task $record){
                    return new HtmlString($record->description);
                })
                ->extraAttributes([
                    'class' => 'border p-2 rounded'
                ]),

            Tabs::make()
                ->contained(false)
                ->tabs([
                    Tab::make('Sub Tasks')
                        ->schema([
                            SubTaskEntry::make('subTasks'),
                        ]),
                    Tab::make('Comments')
                        ->schema([
                            CommentsEntry::make('comments')
                                ->buttonPosition('right')
                                ->mentions()
                                ->nestable(),
                        ]),
                ]),


        ];
    }

    protected static function action(array $bindings): Closure
    {
        return function (array $data) use ($bindings) {};
    }
}
