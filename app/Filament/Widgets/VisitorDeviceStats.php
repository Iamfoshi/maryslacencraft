<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;

class VisitorDeviceStats extends ChartWidget
{
    protected static ?string $heading = 'Visitors by Device';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $devices = Visitor::getDeviceBreakdown();
        
        // Default data if empty
        if (empty($devices)) {
            $devices = ['No data yet' => 1];
        }

        $colors = [
            'Desktop' => '#C4927A',
            'Mobile' => '#E8B4B8',
            'Tablet' => '#D4C4B0',
            'No data yet' => '#ccc',
        ];

        $backgroundColors = [];
        foreach (array_keys($devices) as $device) {
            $backgroundColors[] = $colors[$device] ?? '#999';
        }

        return [
            'datasets' => [
                [
                    'data' => array_values($devices),
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => array_keys($devices),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}

