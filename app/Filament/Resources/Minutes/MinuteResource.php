<?php

namespace App\Filament\Resources\Minutes;

use App\Filament\Resources\Minutes\Pages\CreateMinute;
use App\Filament\Resources\Minutes\Pages\EditMinute;
use App\Filament\Resources\Minutes\Pages\ListMinutes;
use App\Filament\Resources\Minutes\Schemas\MinuteForm;
use App\Filament\Resources\Minutes\Tables\MinutesTable;
use App\Models\Minute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MinuteResource extends Resource
{
    protected static ?string $model = Minute::class;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return MinuteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MinutesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMinutes::route('/'),
            'create' => CreateMinute::route('/create'),
            'edit' => EditMinute::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
