<?php

namespace App\Filament\Pages;

use App\Models\Task;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardPage;
use UnitEnum;

class MyTasks extends BoardPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboard;
    protected static string | UnitEnum | null $navigationGroup = 'Personal';

    public function board(Board $board): Board
    {
        $boardPage = (new TaskBoardPage);

        $boardPage->mount(null);

        return $boardPage
            ->board($board)
            ->query(
                Task::with([
                    'assignedTo',
                    'subTasks'
                ])
                    ->whereNull('parent_id')
                    ->where('assigned_to_id', Auth::id())
            );
    }
}
