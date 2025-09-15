<?php

namespace App\Filament\Resources\Order2ItemResource\Pages;

use App\Filament\Resources\Order2ItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderItems extends ListRecords
{
    protected static string $resource = Order2ItemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
