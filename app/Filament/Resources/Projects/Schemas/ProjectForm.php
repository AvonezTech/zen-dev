<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\Project\ProjectPriority;
use App\Enums\Project\ProjectStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Wizard::make(
                    self::getSteps()
                ),
            ]);
    }

    public static function getSteps()
    {
        return [
            self::getGeneralStep(),
            self::getDatesAndOptionsStep(),
            self::getMembersStep(),
        ];
    }

    protected static function getGeneralStep(): Step
    {
        return  Step::make('General')
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
            ]);
    }

    protected static function getDatesAndOptionsStep(): Step
    {
        return Step::make('Dates and Options')
            ->columns(2)
            ->schema([
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),

                RadioDeck::make('status')
                    ->options(ProjectStatus::class)
                    ->icons(ProjectStatus::getRadiodeckIcons())
                    ->columns(5)
                    ->required()
                    ->columnSpanFull(),
                RadioDeck::make('priority')
                    ->options(ProjectPriority::class)
                    ->icons(ProjectPriority::getRadiodeckIcons())
                    ->columns(4)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    protected static function getMembersStep(): Step
    {
        return Step::make('Members')
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
            ]);
    }
}
