<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string
    {
        return 'Ubah Produk';
    }

    public function getSubheading(): ?string
    {
        return 'Perbarui nama, deskripsi, atau harga tanpa keluar dari workspace admin.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus produk'),
        ];
    }
}
