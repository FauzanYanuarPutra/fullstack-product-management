<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Widgets\ProductStatsOverview;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Katalog Produk';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $modelLabel = 'produk';

    protected static ?string $pluralModelLabel = 'produk';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi produk')
                ->description('Lengkapi data produk untuk admin, API, dan frontend.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama produk')
                        ->placeholder('Contoh: Starter Kit Midnight')
                        ->helperText('Gunakan nama yang mudah dicari saat katalog bertambah banyak.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('price')
                        ->label('Harga')
                        ->required()
                        ->prefix('Rp')
                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                        ->stripCharacters('.')
                        ->inputMode('numeric')
                        ->rule('numeric')
                        ->minValue(0)
                        ->formatStateUsing(
                            fn ($state): ?string => filled($state) ? number_format((float) $state, 0, ',', '.') : null,
                        )
                        ->placeholder('350.000'),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->required()
                        ->rows(6)
                        ->placeholder('Tulis deskripsi singkat untuk katalog dan halaman detail.')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->emptyStateHeading('Belum ada produk di katalog')
            ->emptyStateDescription('Tambahkan produk pertama untuk mulai mengelola katalog dari panel ini.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama produk')
                    ->weight(FontWeight::SemiBold)
                    ->description(fn (Product $record): string => Str::limit($record->description, 84))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(60)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(
                        fn ($state): string => 'Rp '.number_format((float) $state, 2, ',', '.'),
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('minimum_price')
                    ->label('Harga minimum')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Harga minimum')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->stripCharacters('.')
                            ->inputMode('numeric')
                            ->rule('numeric')
                            ->minValue(0)
                            ->placeholder('100.000'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['amount'] ?? null,
                            fn (Builder $query, $amount): Builder => $query->where('price', '>=', $amount),
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus terpilih'),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    public static function getWidgets(): array
    {
        return [
            ProductStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
