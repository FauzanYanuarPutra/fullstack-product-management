<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string
    {
        return 'Tambah Produk';
    }

    public function getSubheading(): ?string
    {
        return 'Lengkapi data produk dengan gaya input yang ringkas untuk operasional katalog.';
    }
}
