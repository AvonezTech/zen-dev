<?php

namespace App\Filament\Resources\DailyExpenses\Tables;

use App\Models\DailyExpense;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DailyExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date('Y-m-d')
                    ->sortable(),
                
                TextColumn::make('category')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                
                TextColumn::make('vendor')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('project.name')
                    ->label('Project')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('payment_method')
                    ->badge()
                    ->colors([
                        'success' => 'Cash',
                        'info' => 'Bank Transfer',
                        'warning' => 'Digital Wallet',
                    ]),
                
                TextColumn::make('amount')
                    ->label('Total Bill')
                    ->numeric(decimalPlaces: 2)
                    ->prefix('NPR ')
                    ->sortable()
                    ->summarize(
                        Sum::make('amount')
                            ->label('Total Bill')
                            ->money('NPR')
                    ),
                
                TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->numeric(decimalPlaces: 2)
                    ->prefix('NPR ')
                    ->sortable()
                    ->summarize(
                        Sum::make('paid_amount')
                            ->label('Total Paid')
                            ->money('NPR')
                    ),

                TextColumn::make('payment_status')
                    ->label('Pay Status')
                    ->badge()
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'partial',
                        'danger' => 'unpaid',
                    ]),

                TextColumn::make('due_amount')
                    ->label('Due Balance')
                    ->numeric(decimalPlaces: 2)
                    ->prefix('NPR ')
                    ->color(fn (float $state) => $state > 0 ? 'danger' : 'success')
                    ->summarize(
                        Sum::make('due_amount')
                            ->label('Total Due')
                            ->money('NPR')
                    ),
                
                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
