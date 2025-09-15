<?php

namespace App\Filament\Resources\Order2ItemResource\Pages;

use App\Filament\Resources\Order2ItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderItem extends EditRecord
{
    protected static string $resource = Order2ItemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
