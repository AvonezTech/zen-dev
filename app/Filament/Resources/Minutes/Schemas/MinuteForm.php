<?php

namespace App\Filament\Resources\Minutes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MinuteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                DatePicker::make('meeting_date')
                    ->default(now())
                    ->required()
                    ->native(false),
                
                TextInput::make('venue')
                    ->maxLength(255),
                
                Hidden::make('recorded_by')
                    ->default(fn () => auth()->id())
                    ->required(),
                
                RichEditor::make('agenda')
                    ->columnSpanFull()
                    ->nullable(),
                
                RichEditor::make('discussion')
                    ->columnSpanFull()
                    ->nullable(),
                
                RichEditor::make('resolutions')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }
}