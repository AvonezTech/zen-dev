<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Project; // Assuming you have a Project model based on your sidebar
use App\Models\Client;  // Assuming you have a Client model based on your sidebar
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
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