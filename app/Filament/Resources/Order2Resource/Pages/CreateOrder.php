<?php

namespace App\Filament\Resources\Order2Resource\Pages;

use App\Filament\Resources\Order2Resource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = Order2Resource::class;
    
    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->url(static::getResource()::getUrl())
            ->color('secondary');
    }
}
