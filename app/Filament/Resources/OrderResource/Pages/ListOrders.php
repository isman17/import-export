<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
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
    protected static string $resource = OrderResource::class;

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
                ->fields([
                    ImportField::make('code')
                        ->required()
                        ->label(trans('resource.order.code'))
                        ->translateLabel(trans('resource.order.code')),
                    ImportField::make('status')
                        ->required()
                        ->label(trans('resource.order.status'))
                        ->translateLabel(trans('resource.order.status')),
                    // ImportField::make('created_at')
                    //     ->required()
                    //     ->label(trans('resource.order.created_at'))
                    //     ->translateLabel(trans('resource.order.created_at')),
                    ImportField::make('total_quantity')
                        ->required()
                        ->label(trans('resource.order.total_quantity'))
                        ->translateLabel(trans('resource.order.total_quantity')),
                    ImportField::make('parent_sku')
                        ->required()
                        ->label(trans('resource.order.item.parent_sku'))
                        ->translateLabel(trans('resource.order.item.parent_sku')),
                    ImportField::make('name')
                        ->required()
                        ->label(trans('resource.order.item.name'))
                        ->translateLabel(trans('resource.order.item.name')),
                    ImportField::make('sku')
                        ->required()
                        ->label(trans('resource.order.item.sku'))
                        ->translateLabel(trans('resource.order.item.sku')),
                    ImportField::make('variant_name')
                        ->required()
                        ->label(trans('resource.order.item.variant_name'))
                        ->translateLabel(trans('resource.order.item.variant_name')),
                    // ImportField::make('regular_price')
                    //     ->required()
                    //     ->label(trans('resource.order.item.regular_price'))
                    //     ->translateLabel(trans('resource.order.item.regular_price')),
                    // ImportField::make('discount_price')
                    //     ->required()
                    //     ->label(trans('resource.order.item.discount_price'))
                    //     ->translateLabel(trans('resource.order.item.discount_price')),
                    ImportField::make('quantity')
                        ->required()
                        ->label(trans('resource.order.item.quantity'))
                        ->translateLabel(trans('resource.order.item.quantity')),
                    // ImportField::make('total_price')
                    //     ->required()
                    //     ->label(trans('resource.order.item.total_price'))
                    //     ->translateLabel(trans('resource.order.item.total_price')),
                    // ImportField::make('total_discount')
                    //     ->required()
                    //     ->label(trans('resource.order.item.total_discount'))
                    //     ->translateLabel(trans('resource.order.item.total_discount')),
                ], columns:2)
                ->handleRecordCreation(function($data){
                    $order = Order::updateOrCreate([
                        'code' => $data['code']
                    ], [
                        'status' => $data['status'],
                        // 'created_at' => $data['created_at'],
                        'total_quantity' => $data['total_quantity'],
                    ]);
                    
                    $orderItem = OrderItem::create([
                        'order_code' => $data['code'],
                        'parent_sku' => $data['parent_sku'],
                        'name' => $data['name'],
                        'sku' => $data['sku'],
                        'variant_name' => $data['variant_name'],
                        // 'regular_price' => (double)$data['regular_price'],
                        // 'discount_price' => (double)$data['discount_price'],
                        'quantity' => $data['quantity'],
                        // 'total_price' => (double)$data['total_price'],
                        // 'total_discount' => (double)$data['total_discount'],
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
        return Excel::download(new \App\Exports\OrderWithItemExport($included), 'orders.xlsx');
    }

    public function resetDB()
    {
        DB::beginTransaction();

        try {
            OrderItem::truncate();
            Order::truncate();
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