<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('priority')
                    ->badge(),
                TextEntry::make('client.name')
                    ->label('Client'),
                TextEntry::make('budget')
                    ->numeric(),

                RepeatableEntry::make('projectUsers')
                    ->label("Members")
                    ->columnSpanFull()
                    ->table([
                        TableColumn::make('User'),
                        TableColumn::make('Role'),
                    ])
                    ->schema([
                        TextEntry::make('user.name'),
                        TextEntry::make('role'),
                    ]),

                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),

                TextEntry::make('archived_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
