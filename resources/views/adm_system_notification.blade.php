<x-adm-dsh-nav>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">System Notifications</h1>

        <div class="grid gap-4">
            @foreach($lowStockItems as $item)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <i class="fa-regular fa-triangle-exclamation text-red-500 mr-2"></i>
                                <h3 class="text-lg font-semibold text-red-700">Low Stock Alert</h3>
                            </div>
                            <p class="text-gray-600 mt-1">Item "{{ $item->product->item_name }}" is running low ({{ $item->closing_balance }} remaining)</p>
                        </div>
                        <a href="{{ route('inventory.show') }}"
                           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                            Manage Stock
                        </a>
                    </div>
                </div>
            @endforeach

            @if($lowStockItems->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-gray-600">No notifications at this time</p>
                </div>
            @endif
        </div>
    </div>
</x-adm-dsh-nav>
