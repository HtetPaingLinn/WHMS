<x-dashboard>
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-500 ease-in-out z-50">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        </div>

        <script>
            // Auto-hide the success message after 5 seconds
            setTimeout(function() {
                const successMessage = document.getElementById('success-message');
                if (successMessage) {
                    successMessage.style.opacity = '0';
                    successMessage.style.transition = 'opacity 0.5s ease-in-out';
                    setTimeout(function() {
                        successMessage.remove();
                    }, 500);
                }
            }, 5000);
        </script>
    @endif

    @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-500 ease-in-out z-50">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <p>{{ session('error') }}</p>
            </div>
        </div>

        <script>
            // Auto-hide the error message after 5 seconds
            setTimeout(function() {
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    errorMessage.style.opacity = '0';
                    errorMessage.style.transition = 'opacity 0.5s ease-in-out';
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 500);
                }
            }, 5000);
        </script>
    @endif
    <div class="bg-white rounded-lg shadow-lg p-6 w-full lg:ps-72">
        <!-- Pending Orders Section -->
        <h2 class="text-4xl font-bold text-gray-900 mb-6 tracking-wide">📊 Pending Orders</h2>
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
                        ->where('delivered', 0)
                        ->with('product')
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
                                <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to cancel this order?')"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-lg transition duration-300">
                                        Cancel Order
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Completed Orders Section -->
        <h2 class="text-4xl font-bold text-gray-900 my-6 tracking-wide">📦 Completed Orders</h2>
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
                        ->where('delivered', 1)
                        ->where('payment', 'Paid')
                        ->with('product')
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
                                <button type="button"
                                        onclick="showInvoiceDetails('{{ $sale->id }}', '{{ $sale->invoice_no }}')"
                                        data-product-id="{{ $sale->product->id }}"
                                        @if(App\Models\Complaint::where('invoice_no', $sale->invoice_no)
                                            ->where('product_id', $sale->product->id)
                                            ->exists())
                                            disabled
                                            class="bg-gray-400 cursor-not-allowed text-white font-bold py-1 px-4 rounded-lg"
                                            title="Complaint already submitted"
                                        @else
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-4 rounded-lg transition duration-300"
                                        @endif>
                                    {{ App\Models\Complaint::where('invoice_no', $sale->invoice_no)
                                        ->where('product_id', $sale->product->id)
                                        ->exists() ? 'Complaint Submitted' : 'Submit Complaint' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Complaint Form Modal -->
    <div id="complaintFormSection" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 transition-opacity overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-3xl relative transform transition-all my-8 mx-auto">
            <!-- Fixed Header -->
            <div class="sticky top-0 bg-white pb-4 border-b border-gray-200">
                <!-- Close Button -->
                <button onclick="hideComplaintForm()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="mb-4">
                    <h3 class="text-2xl font-semibold text-gray-700">📝 Submit Complaint</h3>
                    <p class="text-gray-600">Invoice: <span id="displayed_invoice_id" class="font-semibold">Select an invoice</span></p>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="mt-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Please check the following errors:</strong>
                        <ul class="mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('complaints.store') }}" method="POST" class="space-y-6 complaint-form">
                    @csrf
                    <input type="hidden" name="invoice_id" id="invoice_id">
                    <input type="hidden" name="product_id" id="product_id">

                    <!-- Customer Info (Auto-filled) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                            @php
                                $shopName = DB::table('partner_shops')
                                    ->where('partner_shops_id', Auth::user()->partner_shops_id)
                                    ->first();
                            @endphp
                            <input type="text" name="customer_name"
                                   value="{{ $shopName ? $shopName->partner_shops_name : 'Shop name not available' }}"
                                   readonly
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer Phone</label>
                            <input type="text" 
                                   name="customer_phone"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Products Selection -->
                    <div class="space-y-4">
                        <div class="border p-4 rounded-lg bg-white">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <span class="font-medium product-name text-lg"></span>
                                </div>
                                <span class="text-sm text-gray-500">Invoice No: <span id="displayed_invoice_id" class="font-semibold"></span></span>
                            </div>

                            <!-- Product Details Section (Always Visible) -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="number" 
                                               name="quantity" 
                                               id="quantity-input" 
                                               min="1"
                                               required
                                               class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="text-sm text-gray-500">Maximum available: <span id="max-quantity" class="font-semibold"></span></span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Issue Type</label>
                                    <select name="issue_type" 
                                            id="issue-type-select"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Issue</option>
                                        <option value="faulty_product">Faulty Product</option>
                                        <option value="mismatch_order">Mismatch Order</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Remarks (Optional)</label>
                                    <textarea name="remarks" 
                                              rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fixed Submit Button Container -->
                    <div class="sticky bottom-0 bg-white pt-4 border-t border-gray-200">
                        <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                            Submit Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle checkbox changes
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const productId = this.dataset.productId;
                    const detailsDiv = document.getElementById(`details-${productId}`);

                    if (this.checked) {
                        detailsDiv.classList.remove('hidden');
                    } else {
                        detailsDiv.classList.add('hidden');
                        // Clear values when unchecked
                        detailsDiv.querySelectorAll('input, select').forEach(input => {
                            input.value = '';
                        });
                    }
                });
            });
        });

        function showInvoiceDetails(invoiceId, invoiceNo) {
            // Show the modal
            const modal = document.getElementById('complaintFormSection');
            modal.classList.remove('hidden');

            // Set the hidden input values
            document.getElementById('invoice_id').value = invoiceId;
            document.getElementById('displayed_invoice_id').textContent = invoiceNo;

            // Get the row data
            const row = event.target.closest('tr');
            const productName = row.querySelector('td:nth-child(3)').textContent.trim();
            const productId = event.target.getAttribute('data-product-id');
            const orderQuantity = parseInt(row.querySelector('td:nth-child(6)').textContent);

            // Update the form with product details
            document.querySelector('.product-name').textContent = productName;
            document.getElementById('product_id').value = productId;
            
            // Set max quantity and its display
            const quantityInput = document.getElementById('quantity-input');
            quantityInput.max = orderQuantity;
            quantityInput.value = "1"; // Reset to 1
            document.getElementById('max-quantity').textContent = orderQuantity;

            // Add event listener for quantity validation
            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value > orderQuantity) {
                    this.value = orderQuantity;
                } else if (value < 1) {
                    this.value = 1;
                }
            });

            // Reset other form fields
            document.getElementById('issue-type-select').value = '';
            document.querySelector('input[name="customer_phone"]').value = '';
            document.querySelector('textarea[name="remarks"]').value = '';
        }

        function hideComplaintForm() {
            // Hide the modal
            document.getElementById('complaintFormSection').classList.add('hidden');

            // Clear form fields EXCEPT the readonly customer name
            document.querySelectorAll('.complaint-form input, .complaint-form textarea, .complaint-form select').forEach(input => {
                if (input.type !== 'hidden' && input.type !== 'submit' && !input.hasAttribute('readonly')) {
                    input.value = '';
                }
            });

            // Uncheck all checkboxes
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Hide all product details
            document.querySelectorAll('.product-details').forEach(details => {
                details.classList.add('hidden');
            });
        }

        // Close modal when clicking outside
        document.getElementById('complaintFormSection').addEventListener('click', function(event) {
            if (event.target === this) {
                hideComplaintForm();
            }
        });
    </script>
</x-dashboard>
