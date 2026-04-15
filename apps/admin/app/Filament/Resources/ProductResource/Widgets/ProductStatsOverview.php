<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Str;

class ProductStatsOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $latestProduct = Product::query()
            ->latest('created_at')
            ->first();

        $averagePrice = (float) Product::query()->avg('price');

        return [
            Stat::make('Total produk', number_format(Product::query()->count(), 0, ',', '.'))
                ->description('Seluruh item yang aktif di katalog')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),
            Stat::make('Harga rata-rata', 'Rp '.number_format($averagePrice, 0, ',', '.'))
                ->description('Patokan kasar harga seluruh produk')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Produk terbaru', $latestProduct ? Str::limit($latestProduct->name, 24) : 'Belum ada')
                ->description(
                    $latestProduct
                        ? 'Masuk pada '.($latestProduct->created_at?->format('d M Y H:i') ?? 'baru saja')
                        : 'Tambahkan produk pertama dari panel ini',
                )
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info'),
        ];
    }
}
