<?php

namespace App\Filament\Resources\PersonalBoards\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PersonalBoardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Hidden::make('user_id')
                    ->default(Auth::id()),
            ]);
    }
}
