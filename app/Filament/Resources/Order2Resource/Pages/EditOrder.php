<?php

namespace App\Filament\Resources\Order2Resource\Pages;

use App\Filament\Resources\Order2Resource;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = Order2Resource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    
    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->url(static::getResource()::getUrl())
            ->color('secondary');
    }
}
