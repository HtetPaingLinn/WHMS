<x-dashboard>
    <div class="bg-white rounded-lg shadow-lg p-6 w-full lg:ps-72">
        <!-- Heading -->
        <h2 class="text-4xl font-bold text-gray-900 mb-6 tracking-wide">📊 Sales Dashboard</h2>

        <!-- Daily Sales Table -->
        <div class="mt-8">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">📅 Daily Sales</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border rounded-lg shadow-md">
                    <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-center">Invoice No</th>
                        <th class="py-3 px-6 text-center">Sale Date</th>
                        <th class="py-3 px-6 text-center">Product</th>
                        <th class="py-3 px-6 text-center">Serial No</th>
                        <th class="py-3 px-6 text-center">Total (MMK)</th>
                        <th class="py-3 px-6 text-center">Quantity</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                    @foreach(App\Models\SalesInvoice::where('partner_shops_id', Auth::user()->partner_shops_id)
                        ->with('product') // Load product relationship
                        ->orderBy('sale_date', 'desc')
                        ->get() as $sale)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 px-6 text-center">{{ $sale->invoice_no }}</td>
                            <td class="py-3 px-6 text-center">{{ $sale->sale_date }}</td>
                            <td class="py-3 px-6 text-center">
                                {{ $sale->product->item_name ?? 'N/A' }}
                                <span class="text-gray-500 text-xs">{{ $sale->product->brand ?? '' }}</span>
                            </td>
                            <td class="py-3 px-6 text-center">{{ $sale->product->product_serial_number ?? 'N/A' }}</td>
                            <td class="py-3 px-6 text-center">{{ number_format($sale->total_mmk, 2) }}</td>
                            <td class="py-3 px-6 text-center">{{ $sale->quantity }}</td>
                            <td class="py-3 px-6 text-center">
                                <button type="button" onclick="showInvoiceDetails('{{ $sale->id }}')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-4 rounded-lg transition duration-300">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard>
