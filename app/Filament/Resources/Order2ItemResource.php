<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Order2ItemResource\Pages;
use App\Models\Order2Item;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class Order2ItemResource extends Resource
{
    protected static ?string $model = Order2Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = "Tiktok";

    protected static ?string $modelLabel = "Order Item";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('parent_sku')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sku')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('variant_name')
                    ->maxLength(255),
                // Forms\Components\TextInput::make('regular_price'),
                // Forms\Components\TextInput::make('discount_price'),
                Forms\Components\TextInput::make('quantity'),
                // Forms\Components\TextInput::make('total_price'),
                // Forms\Components\TextInput::make('total_discount')
                    // ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent_sku')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('sku')
                    ->sortable(),
                Tables\Columns\TextColumn::make('variant_name'),
                // Tables\Columns\TextColumn::make('regular_price'),
                // Tables\Columns\TextColumn::make('discount_price'),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('total_price'),
                // Tables\Columns\TextColumn::make('total_discount'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }    
}
