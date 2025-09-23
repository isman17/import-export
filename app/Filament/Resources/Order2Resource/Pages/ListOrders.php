<?php

namespace App\Filament\Resources\Order2Resource\Pages;

use App\Filament\Resources\Order2Resource;
use App\Models\Order2;
use App\Models\Order2Item;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use Maatwebsite\Excel\Facades\Excel;

class ListOrders extends ListRecords
{
    protected static string $resource = Order2Resource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('export')
                ->form([
                    Textarea::make('included')
                        ->label('Include only order code:')
                        ->placeholder('xxxxxxxxxx')
                ])
                ->action('export'),
            Actions\CreateAction::make(),
            ImportAction::make()
                ->beforeFormValidated(function () {
                    // Set custom temp directory within allowed paths
                    $tempDir = storage_path('app/temp');
                    if (!file_exists($tempDir)) {
                        mkdir($tempDir, 0755, true);
                    }
                    
                    // Set environment variables for Excel processing
                    putenv('TMPDIR=' . $tempDir);
                    putenv('TMP=' . $tempDir);
                    putenv('TEMP=' . $tempDir);
                    
                    // For PhpSpreadsheet specifically
                    if (class_exists('\PhpOffice\PhpSpreadsheet\Settings')) {
                        \PhpOffice\PhpSpreadsheet\Settings::setLibXmlLoaderOptions(
                            LIBXML_DTDLOAD | LIBXML_DTDATTR | LIBXML_NOCDATA
                        );
                    }
                })
                ->fields([
                    ImportField::make('code')
                        ->required()
                        ->label('Order ID'),
                    ImportField::make('status')
                        ->required()
                        ->label('Order Status'),
                    ImportField::make('quantity')
                        ->required()
                        ->label('Quantity'),
                    ImportField::make('name')
                        ->required()
                        ->label('Product Name'),
                    ImportField::make('sku')
                        ->required()
                        ->label('Seller SKU'),
                    ImportField::make('variant_name')
                        ->required()
                        ->label('Variation'),
                ], columns:2)
                ->handleRecordCreation(function($data){
                    if(in_array($data['code'], ['Order ID', 'Platform unique order ID.', ''])) {
                        return new Order2();
                    }

                    $order = Order2::updateOrCreate([
                        'code' => $data['code']
                    ], [
                        'status' => $data['status'],
                    ]);
                    
                    $orderItem = Order2Item::updateOrCreate([
                        'order_code' => $data['code'],
                        'sku' => $data['sku'],
                        'variant_name' => $data['variant_name'],
                    ], [
                        'name' => $data['name'],
                        'quantity' => $data['quantity'],
                    ]);

                    return $order;
                }),
            Actions\Action::make('reset')
                ->requiresConfirmation()
                ->color('danger')
                ->label('Reset Database')
                ->action('resetDB'),
        ];
    }

    public function export($data)
    {
        $included = (isset($data['included'])) ? array_filter(explode(',', str($data['included'])->replace(array("\n", "\r"), ','))) : [];
        return Excel::download(new \App\Exports\Order2WithItemExport($included), 'orders.xlsx');
    }

    public function resetDB()
    {
        DB::beginTransaction();

        try {
            Order2Item::truncate();
            Order2::truncate();
            DB::commit();
            Notification::make() 
                ->title('Reset Database successfully')
                ->success()
                ->send(); 
        } catch (\Throwable $throw) {
            DB::rollBack();
            Notification::make() 
                ->title('Reset Database failed. Error: ' . $throw->getMessage())
                ->success()
                ->send(); 
        }
    }
}