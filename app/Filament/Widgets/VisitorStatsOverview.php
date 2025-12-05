<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $todayVisitors = Visitor::getTodayVisitors();
        $todayUnique = Visitor::getTodayUniqueVisitors();
        $weekVisitors = Visitor::getThisWeekVisitors();
        $monthVisitors = Visitor::getThisMonthVisitors();
        $totalVisitors = Visitor::getTotalVisitors();
        $uniqueTotal = Visitor::getUniqueVisitors();

        // Get last 7 days data for sparkline
        $last7Days = Visitor::getVisitorsByDay(7);
        $chartData = array_values($last7Days);

        return [
            Stat::make('Today\'s Visitors', $todayVisitors)
                ->description($todayUnique . ' unique visitors')
                ->descriptionIcon('heroicon-m-user')
                ->color('success')
                ->chart($chartData ?: [0]),
                
            Stat::make('This Week', $weekVisitors)
                ->description('Page views this week')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('This Month', $monthVisitors)
                ->description('Page views this month')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
                
            Stat::make('Total Visitors', $totalVisitors)
                ->description($uniqueTotal . ' unique IPs')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary'),
        ];
    }
}

