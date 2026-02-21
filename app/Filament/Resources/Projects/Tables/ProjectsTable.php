<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\Project\ProjectPriority;
use App\Enums\Project\ProjectStatus;
use App\Filament\Resources\Projects\Pages\ProjectTaskBoard;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class ProjectsTable
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
                        Stack::make([
                            TextColumn::make('name')
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold)
                                ->searchable(),
                            TextColumn::make('client.name')
                                ->size(TextSize::Medium)
                                ->weight(FontWeight::SemiBold)
                                ->searchable(),
                        ]),

                        Stack::make([
                            TextColumn::make('priority')
                                ->prefix('Priority: ')
                                ->badge()
                                ->searchable(),
                            TextColumn::make('status')
                                ->prefix('Status: ')
                                ->badge()
                                ->searchable(),
                        ]),


                        TextColumn::make('start_date')
                            ->date()
                            ->badge()
                            ->color('info')
                            ->prefix('Start: ')
                            ->sortable(),
                        TextColumn::make('end_date')
                            ->date()
                            ->badge()
                            ->color('danger')
                            ->prefix('End: ')
                            ->sortable(),

                    ]),

            ])
            ->filters([
                SelectFilter::make('priority')
                    ->options(ProjectPriority::class)
                    ->multiple()
                    ->searchable(),
                SelectFilter::make('status')
                    ->options(ProjectStatus::class)
                    ->multiple()
                    ->searchable(),
            ])
            ->recordUrl(function (Project $record) {
                return ProjectTaskBoard::getUrl([
                    'record' => $record
                ]);
            })
            ->recordActions([])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
