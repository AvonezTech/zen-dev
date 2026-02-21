<?php

namespace App\Filament\Actions\Tasks;

use App\Filament\Actions\BaseAction;
use App\Models\Task;
use Closure;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;

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
                ->hiddenLabel()
                ->html(),
        ];
    }

    protected static function action(array $bindings): Closure
    {
        return function (array $data) use ($bindings) {};
    }
}
