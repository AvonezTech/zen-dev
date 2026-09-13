<?php

namespace App\Filament\Resources\DailyExpenses\Schemas;

use App\Enums\ExpenseCategory;
use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->default(now())
                    ->required()
                    ->native(false),
                
                Select::make('category')
                    ->options(ExpenseCategory::class)
                    ->searchable()
                    ->required(),
                
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('amount')
                    ->label('Total Bill Amount')
                    ->numeric()
                    ->minValue(0.01)
                    ->step(0.01)
                    ->prefix('NPR ')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('paid_amount', $state)),
                
                TextInput::make('paid_amount')
                    ->label('Paid Now (Cash/Bank Outflow)')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('NPR ')
                    ->required(),
                
                Select::make('payment_method')
                    ->options(PaymentMethod::class)
                    ->default(PaymentMethod::Cash)
                    ->required(),
                
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                
                TextInput::make('vendor')
                    ->maxLength(255)
                    ->nullable(),
                
                Select::make('status')
                    ->options(TransactionStatus::class)
                    ->default(TransactionStatus::Completed)
                    ->required(),
                
                Textarea::make('description')
                    ->rows(2)
                    ->columnSpanFull()
                    ->nullable(),
                
                Hidden::make('user_id')
                    ->default(fn () => auth()->id())
                    ->required(),
            ]);
    }
}
