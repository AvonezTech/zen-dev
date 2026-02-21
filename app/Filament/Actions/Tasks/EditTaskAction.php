<?php

namespace App\Filament\Actions\Tasks;

use App\Filament\Actions\BaseAction;
use App\Models\Task;
use Closure;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class EditTaskAction extends BaseAction
{
    protected static function name(array $bindings): string
    {
        return 'editTask';
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
            ->icon(Heroicon::OutlinedPencilSquare)
            ->model(Task::class)
            ->modalIcon(Heroicon::OutlinedPencilSquare);
    }

    protected static function schema(array $bindings): array|Closure
    {
        return [];
    }

    protected static function action(array $bindings): Closure
    {
        return function (array $data) use ($bindings) {};
    }
}
