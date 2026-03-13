<?php

namespace App\Filament\Resources\PersonalBoards;

use App\Filament\Resources\PersonalBoards\Pages\CreatePersonalBoard;
use App\Filament\Resources\PersonalBoards\Pages\EditPersonalBoard;
use App\Filament\Resources\PersonalBoards\Pages\ListPersonalBoards;
use App\Filament\Resources\PersonalBoards\Pages\PersonalBoardTaskBoard;
use App\Filament\Resources\PersonalBoards\Pages\ViewPersonalBoard;
use App\Filament\Resources\PersonalBoards\Schemas\PersonalBoardForm;
use App\Filament\Resources\PersonalBoards\Schemas\PersonalBoardInfolist;
use App\Filament\Resources\PersonalBoards\Tables\PersonalBoardsTable;
use App\Models\PersonalBoard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PersonalBoardResource extends Resource
{
    protected static ?string $model = PersonalBoard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Personal';


    public static function form(Schema $schema): Schema
    {
        return PersonalBoardForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PersonalBoardInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonalBoardsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPersonalBoards::route('/'),
            'create' => CreatePersonalBoard::route('/create'),
            'view' => ViewPersonalBoard::route('/{record}'),
            'edit' => EditPersonalBoard::route('/{record}/edit'),
            'tasks' => PersonalBoardTaskBoard::route('/{record}/tasks'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return PersonalBoard::where('user_id', Auth::id());
    }
}
