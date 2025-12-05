<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VisitorChart extends ChartWidget
{
    protected static ?string $heading = 'Visitor Traffic (Last 14 Days)';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = 14;
        $data = [];
        $labels = [];

        // Generate all dates for the last N days
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('M j');
            $data[$date] = 0;
        }

        // Fill in actual visitor counts
        $visitors = Visitor::getVisitorsByDay($days);
        foreach ($visitors as $date => $count) {
            if (isset($data[$date])) {
                $data[$date] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => array_values($data),
                    'fill' => true,
                    'backgroundColor' => 'rgba(200, 146, 122, 0.2)',
                    'borderColor' => '#C4927A',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}

