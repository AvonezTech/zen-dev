<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ProjectTaskBoard;
use App\Filament\Resources\Projects\Pages\ViewProject;
use App\Filament\Resources\Projects\RelationManagers\ExpendituresRelationManager;
use App\Filament\Resources\Projects\RelationManagers\IncomesRelationManager;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Schemas\ProjectInfolist;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Business';

    // --- DATA FILTERING LOGIC ---
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // If the user is an admin, show all projects
        if (auth()->check() && auth()->user()->is_admin == 1) {
            return $query;
        }

        // Filters projects using the many-to-many relationship (fixes the user_id column error)
        return $query->whereHas('users', function (Builder $query) {
            $query->where('users.id', auth()->id());
        });
    }

    // Only admins can create new projects
    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->is_admin == 1;
    }
    // ----------------------------

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            IncomesRelationManager::class,
            ExpendituresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
            'tasks' => ProjectTaskBoard::route('/{record}/tasks'),
        ];
    }
}