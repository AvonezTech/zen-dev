<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestProjects extends BaseWidget
{
    protected static ?string $heading = 'Recent Projects';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()->latest()->limit(6)
            )
            ->contentGrid([
                'md' => 2, 
                'xl' => 3, 
            ])
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->weight(FontWeight::Bold) 
                        ->icon('heroicon-m-code-bracket') 
                        ->iconColor('primary')
                        ->size('lg'), // Removed ->searchable() from here
                        
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->since() 
                        ->badge()
                        ->color('success') 
                        ->icon('heroicon-m-clock'), 
                ])->space(3), 
            ])
            ->searchable(false) // Removes the search bar from the widget header entirely
            ->recordUrl(
                fn (Project $record): string => route('filament.project-management.resources.projects.edit', ['record' => $record]),
            )
            ->paginated(false);
    }
}