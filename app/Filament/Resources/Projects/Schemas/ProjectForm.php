<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\Project\ProjectPriority;
use App\Enums\Project\ProjectStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('status')
                    ->options(ProjectStatus::class)
                    ->required(),
                Select::make('priority')
                    ->options(ProjectPriority::class)
                    ->required(),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('budget')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('archived_at'),
            ]);
    }
}
