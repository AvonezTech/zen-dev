<?php

namespace App\Filament\Resources\DailyExpenses;

use App\Filament\Resources\DailyExpenses\Pages\CreateDailyExpense;
use App\Filament\Resources\DailyExpenses\Pages\EditDailyExpense;
use App\Filament\Resources\DailyExpenses\Pages\ListDailyExpenses;
use App\Filament\Resources\DailyExpenses\Schemas\DailyExpenseForm;
use App\Filament\Resources\DailyExpenses\Tables\DailyExpensesTable;
use App\Models\DailyExpense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DailyExpenseResource extends Resource
{
    protected static ?string $model = DailyExpense::class;

    protected static string|UnitEnum|null $navigationGroup = 'Transaction';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return DailyExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyExpensesTable::configure($table);
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
            'index' => ListDailyExpenses::route('/'),
            'create' => CreateDailyExpense::route('/create'),
            'edit' => EditDailyExpense::route('/{record}/edit'),
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
