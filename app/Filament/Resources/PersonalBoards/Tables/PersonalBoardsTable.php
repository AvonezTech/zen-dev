<?php

namespace App\Filament\Resources\PersonalBoards\Tables;

use App\Filament\Resources\PersonalBoards\Pages\PersonalBoardTaskBoard;
use App\Models\PersonalBoard;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonalBoardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'lg' => 2,
            ])
            ->columns([
                Grid::make([
                    'md' => 2,
                ])
                    ->schema([

                        TextColumn::make('name')
                            ->searchable()
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordUrl(function (PersonalBoard $record) {
                return PersonalBoardTaskBoard::getUrl([
                    'record' => $record
                ]);
            })
            ->recordActions([])
            ->toolbarActions([]);
    }
}
