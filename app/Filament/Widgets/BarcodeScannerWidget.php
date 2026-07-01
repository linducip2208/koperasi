<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class BarcodeScannerWidget extends Widget
{
    protected static string $view = 'filament.widgets.barcode-scanner';
    protected static ?int $sort = 50;
    protected int|string|array $columnSpan = 'full';

    public function mount(): void
    {
        //
    }
}
