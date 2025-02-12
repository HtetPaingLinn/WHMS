<x-dashboard>
    <div class="container mx-auto px-4 py-8 lg:ps-72">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">My Complaint History</h1>

            @if($complaints->isEmpty())
                <div class="bg-gray-100 rounded-lg p-6 text-center">
                    <p class="text-gray-600">You haven't submitted any complaints yet.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-lg">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 text-left">Date Submitted</th>
                            <th class="py-3 px-4 text-left">Complaint ID</th>
                            <th class="py-3 px-4 text-left">Invoice No</th>
                            <th class="py-3 px-4 text-left">Product</th>
                            <th class="py-3 px-4 text-left">Issue Type</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Admin Response</th>
                            <th class="py-3 px-4 text-left">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($complaints as $complaint)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    {{ $complaint->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $complaint->id }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $complaint->invoice_no }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $complaint->product_name }}
                                    <div class="text-sm text-gray-500">
                                        Quantity: {{ $complaint->quantity }}
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->issue_type)) }}
                                </td>
                                <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded-full text-sm
                                            @if($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($complaint->status == 'processing') bg-blue-100 text-blue-800
                                            @elseif($complaint->status == 'resolved') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($complaint->admin_response)
                                        {{ $complaint->admin_response }}
                                    @else
                                        <span class="text-gray-400">No response yet</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($complaint->status === 'pending')
                                        <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                onclick="return confirm('Are you sure you want to remove this complaint?')"
                                                class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded-lg transition duration-300">
                                                Remove
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-dashboard>
