<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Order Code</th>
        <th>Name</th>
        <th>SKU</th>
    </tr>
    </thead>
    <tbody>
    @php
        $hasMerges = [];
    @endphp
    @foreach($orders as $order)
    @php
        $code = '';
        if(!in_array($order->code, $hasMerges)) {
            $code = $order->code;
        }

        $orderItems = \App\Models\Order::findByCode($code)?->items->count() ?? 0;
        if($orderItems > 1) {
            $hasMerges[] = $order->code;
        }
    @endphp
        <tr>
            <td>{{ $loop->index+1 }}</td>
            @if (!empty($code))
            <td style="display: flex; align-items: center; justify-content: start;" @if($orderItems > 1) rowspan="{{ $orderItems }}" @endif>{{ $code }}</td>
            @endif
            <td>{{ $order->name }}</td>
            <td>{{ $order->sku }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@php
    // die()
@endphp