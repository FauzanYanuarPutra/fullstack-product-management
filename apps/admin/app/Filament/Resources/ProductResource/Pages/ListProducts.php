<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Widgets\ProductStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string
    {
        return 'Katalog Produk';
    }

    public function getSubheading(): ?string
    {
        return 'Pantau produk, cek harga, dan kelola data dari satu tempat.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah produk'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductStatsOverview::class,
        ];
    }
}
