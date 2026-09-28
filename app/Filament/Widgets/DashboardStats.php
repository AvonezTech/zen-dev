<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Project; 
use App\Models\Client;  
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    // --- ADMIN AUTHORIZATION ---
    // Only allow admins to see this widget on the dashboard
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->is_admin == 1;
    }
    // ---------------------------

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Active Projects', Project::count())
                ->description('Ongoing work')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),

            Stat::make('Total Clients', Client::count())
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}