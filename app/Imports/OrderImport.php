<?php

namespace App\Imports;

use App\Models\ImportLog;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class OrderImport implements ToModel
{
    public function __construct(
        private $filename
    ) { }

    private $current_row = 0;

    public function model(array $row)
    {
        if($row[0] == 'No. Pesanan') return;
        
        Log::info("importing $row[0]");
        echo("importing $row[0]" . PHP_EOL);

        $order = Order::findByCode($row[0]);

        // if(!empty($order)) 
        // {
        //     ImportLog::create([
        //         'filename' => $this->filename,
        //         'row' => ++$this->current_row,
        //         'status' => 'success',
        //         'note' => "$row[0] order sudah ada",
        //         'error' => '',
        //     ]);

        //     return $order;
        // }

        // Create order
        try 
        {
            DB::beginTransaction();

            Log::info("creating $row[0]");
            echo("creating $row[0]" . PHP_EOL);
            $order = Order::updateOrCreate([
                'code' => $row[0]
            ], [
                'code' => $row[0],
                'status' => $row[1],
                // 'cancel_reason' => $row[2],
                // 'cancel_status' => $row[3],
                // 'airwaybill' => $row[4],
                // 'delivery_service' => $row[5],
                // 'delivery_pickup' => $row[6],
                // 'delivery_before' => $row[7],
                // 'delivery_at' => $row[8],
                'created_at' => $row[9],
                // 'pay_at' => $row[10],
                'total_quantity' => $row[22],
                // 'total_weight' => $row[23],
            ]);
            
            Log::info("created $row[0]");
            echo("created $row[0]" . PHP_EOL);

            DB::commit();
        } catch(\Throwable $throw) 
        {
            Log::info("failed to create $row[0]");
            Log::info(json_encode($throw));
            Log::info($throw->getMessage());
            echo("failed to create $row[0]" . PHP_EOL);
            DB::rollBack();
            
            ImportLog::create([
                'filename' => $this->filename,
                'row' => ++$this->current_row,
                'status' => 'error',
                'note' => "",
                'error' => $throw->getMessage(),
            ]);
        }

        // Create order item
        try 
        {
            DB::beginTransaction();

            Log::info("creating item $row[12]");
            echo("creating item $row[12]" . PHP_EOL);

            OrderItem::create([
                'order_code' => $row[0],
                'parent_sku' => $row[10],
                'name' => $row[11],
                'sku' => $row[12],
                'variant_name' => $row[13],
                'regular_price' => (double)$row[14],
                'discount_price' => (double)$row[15],
                'quantity' => $row[16],
                'total_price' => (double)$row[17],
                'total_discount' => (double)$row[18],
                // 'seller_discount' => $row[20],
                // 'shopee_discount' => $row[21],
                // 'weight' => $row[22],
            ]);

            Log::info($row[15]);
            Log::info((float)$row[15]);
    
            Log::info("created item $row[12]");
            echo("created item $row[12]" . PHP_EOL);

            ImportLog::create([
                'filename' => $this->filename,
                'row' => ++$this->current_row,
                'status' => 'success',
                'note' => "$row[12] item ditambahkan",
                'error' => '',
            ]);

            DB::commit();

            return $order;
        } catch(\Throwable $throw) 
        {
            Log::info("failed to create item $row[12]");
            Log::info(json_encode($throw));
            Log::info($throw->getMessage());
            echo("failed to create item $row[12]" . PHP_EOL);
            DB::rollBack();
            
            ImportLog::create([
                'filename' => $this->filename,
                'row' => ++$this->current_row,
                'status' => 'error',
                'note' => "",
                'error' => $throw->getMessage(),
            ]);

            return $order;
        }
    }
}
