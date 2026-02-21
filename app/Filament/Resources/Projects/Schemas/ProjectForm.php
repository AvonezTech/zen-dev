<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\Project\ProjectPriority;
use App\Enums\Project\ProjectStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use ToneGabes\BetterOptions\Forms\Components\CheckboxCards;
use ToneGabes\BetterOptions\Forms\Components\RadioCards;
use ToneGabes\BetterOptions\Forms\Components\RadioList;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->required(),
                        Select::make('client_id')
                            ->label("Client")
                            ->preload()
                            ->searchable()
                            ->relationship('client', 'name')
                            ->required(),
                        TextInput::make('budget')
                            ->required()
                            ->numeric(),
                    ]),

                Section::make('Dates')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('start_date')
                            ->required(),
                        DatePicker::make('end_date')
                            ->required(),
                    ]),

                Section::make('Members')
                    ->compact()
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('projectUsers')
                            ->hiddenLabel()
                            ->relationship('projectUsers')
                            ->columns(2)
                            ->addActionLabel("Add Member")
                            ->table([
                                TableColumn::make('User'),
                                TableColumn::make('Role'),
                            ])
                            ->schema([
                                Select::make('user_id')
                                    ->label("User")
                                    ->relationship('user', 'name')
                                    ->preload()
                                    ->required()
                                    ->searchable(),
                                TextInput::make('role')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Options')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        RadioList::make('status')
                            ->options(ProjectStatus::class)
                            ->columns(2)
                            ->required(),
                        RadioList::make('priority')
                            ->options(ProjectPriority::class)
                            ->columns(2)
                            ->required(),
                    ]),
            ]);
    }
}
