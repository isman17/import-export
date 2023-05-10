<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderWithItemExport implements FromView, WithTitle, WithMapping, ShouldAutoSize, WithHeadings, WithStyles
{
    use RemembersRowNumber;

    private $rowIndex = 0;

    private $mergeCells = [];

    public function __construct(
        private $included = [],
    )
    {
        
    }

    public function collection()
    {
        $data = DB::table('orders')
                ->when(
                    !empty($this->included),
                    function ($query) {
                        $query->whereIn('code', $this->included);
                    }
                )
                ->join('order_items', 'order_items.order_code', '=', 'orders.code')
                ->select([
                    'code',
                    'name',
                    'sku',
                    'quantity',
                ])
                ->get();
        return $data;
    }

    public function view(): View
    {
        $orders = DB::table('orders')
                ->when(
                    !empty($this->included),
                    function ($query) {
                        $query->whereIn('code', $this->included);
                    }
                )
                ->join('order_items', 'order_items.order_code', '=', 'orders.code')
                ->select([
                    'code',
                    'name',
                    'sku',
                    'quantity',
                ])
                ->get();

        return view('export', compact('orders'));
    }

    public function headings(): array
    {
        return [
            '#',
            'Order Code',
            'Order Item Name',
            'Order Item SKU',
            // 'Order Item Quantity',
        ];
    }

    public function map($order): array
    {
        $orders = [
            ++$this->rowIndex,
            $order->code,
            $order->name,
            $order->sku,
            // $order->quantity,
        ];
        return $orders;
    }

    public function styles(Worksheet $sheet)
    {
        // foreach($this->collection() as $order) {
        //     dd($order);
        // }
        // $sheet->mergeCells('B2');
    }

    public function title(): string
    {
        return 'Orders';
    }
}
