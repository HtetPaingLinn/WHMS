<x-adm-dsh-nav>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="relative overflow-x-auto sm:rounded-lg">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 tracking-wide">📋 Customer Complaints Management</h2>

        @php
            $statuses = ['pending', 'processing', 'resolved', 'rejected'];
            $statusColors = [
                'pending' => [
                    'bg' => 'bg-yellow-100',
                    'text' => 'text-yellow-800',
                    'badge' => 'bg-yellow-500'
                ],
                'processing' => [
                    'bg' => 'bg-blue-100',
                    'text' => 'text-blue-800',
                    'badge' => 'bg-blue-500'
                ],
                'resolved' => [
                    'bg' => 'bg-green-100',
                    'text' => 'text-green-800',
                    'badge' => 'bg-green-500'
                ],
                'rejected' => [
                    'bg' => 'bg-red-100',
                    'text' => 'text-red-800',
                    'badge' => 'bg-red-500'
                ]
            ];
        @endphp

        @if(session('success'))
            <div id="status-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-500 ease-in-out z-50">
                {{ session('success') }}
            </div>
        @endif

        <div id="notification" class="fixed top-4 right-4 hidden transform transition-all duration-500 ease-in-out z-50">
        </div>

        @php
            \Log::info('View Service Centers:', ['centers' => $serviceCenters->toArray()]);
        @endphp

        @foreach($statuses as $statusKey)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4 p-3 {{ $statusColors[$statusKey]['bg'] }} rounded-lg">
                    <h3 class="text-1xl font-semibold flex items-center">
                        <span class="px-4 py-2 rounded-full {{ $statusColors[$statusKey]['badge'] }} text-white mr-2">
                            {{ ucfirst($statusKey) }}
                        </span>
                        <span class="text-gray-600">
                            Complaints ({{ count($complaints[$statusKey]) }})
                        </span>
                    </h3>
                    <span class="text-sm {{ $statusColors[$statusKey]['text'] }}">
                        {{ count($complaints[$statusKey]) }} items
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border rounded-lg shadow-md" id="{{ $statusKey }}-table">
                        <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-center">Complaint ID</th>
                            <th class="py-3 px-6 text-center">Date</th>
                            <th class="py-3 px-6 text-center">Customer</th>
                            <th class="py-3 px-6 text-center">Invoice No</th>
                            <th class="py-3 px-6 text-center">Product & Issue</th>
                            @if($statusKey === 'processing')
                                <th class="py-3 px-6 text-center">Response</th>
                            @endif
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                        @forelse($complaints[$statusKey] as $complaint)
                            <tr class="border-b border-gray-200 hover:bg-gray-50" id="complaint-{{ $complaint->id }}">
                                <td class="py-3 px-6 text-center">#{{ $complaint->id }}</td>
                                <td class="py-3 px-6 text-center">{{ $complaint->complain_date->format('d M Y') }}</td>
                                <td class="py-3 px-6 text-center">
                                    <span class="text-gray-500">{{ $complaint->customer_phone }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    {{ $complaint->salesInvoice->invoice_no ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="p-2 bg-gray-100 rounded">
                                        🧾 {{ $complaint->product_name }}<br>
                                        <span class="text-xs text-gray-500">
                                            Qty: {{ $complaint->quantity }}<br>
                                            Issue: {{ $complaint->issue_type }}
                                        </span>
                                    </div>
                                    @if($complaint->remark)
                                        <div class="mt-2 text-xs text-gray-500">
                                            Note: {{ $complaint->remark }}
                                        </div>
                                    @endif
                                </td>

                                @if($statusKey === 'processing')
                                    <td class="py-3 px-6 text-center">
                                        <div class="space-y-2 response-container" data-complaint-id="{{ $complaint->id }}">
                                            <div class="flex items-center space-x-4 justify-center">
                                                <label class="inline-flex items-center">
                                                    <input type="radio"
                                                           name="response_type_{{ $complaint->id }}"
                                                           value="service_center"
                                                           onclick="showServiceCenterModal({{ $complaint->id }})"
                                                           class="form-radio">
                                                    <span class="ml-2">Service Center</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio"
                                                           name="response_type_{{ $complaint->id }}"
                                                           value="warehouse"
                                                           onclick="showWarehouseModal({{ $complaint->id }})"
                                                           class="form-radio">
                                                    <span class="ml-2">Warehouse</span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                <td class="py-3 px-6 text-center">
                                    <form action="{{ route('complaints.update', $complaint) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="rounded px-2 py-1 text-sm border">
                                            @foreach(['pending', 'processing', 'resolved', 'rejected'] as $status)
                                                <option value="{{ $status }}" {{ $complaint->status == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $statusKey === 'processing' ? 7 : 6 }}" class="py-6 text-center text-gray-500">
                                    No {{ $statusKey }} complaints found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Service Center Modal -->
    <div id="serviceCenterModal" class="fixed inset-0 z-50 hidden overflow-y-auto"
         onclick="closeModalOnOutsideClick(event, 'serviceCenterModal')">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-xl font-semibold">Select Service Center</h3>
                    <button type="button" onclick="closeServiceCenterModal()"
                            class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
                <div class="max-h-[60vh] overflow-y-auto p-6">
                    <div class="mb-4 p-2 bg-gray-100 rounded">
                        Total Service Centers: {{ $serviceCenters->count() }}
                    </div>

                    <div class="grid grid-cols-2 gap-6" id="serviceCenterList">
                        @foreach($serviceCenters as $center)
                            <div class="flex items-center p-4 hover:bg-gray-100 rounded-lg border">
                                <input type="radio"
                                       name="service_center"
                                       value="{{ $center->center_id }}"
                                       id="service_center_{{ $center->center_id }}"
                                       class="mr-3 h-4 w-4">
                                <label for="service_center_{{ $center->center_id }}" class="text-lg cursor-pointer">
                                    {{ $center->service_center_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6 flex justify-end p-6 border-t bg-gray-50">
                    <button onclick="saveServiceCenter()"
                            class="bg-blue-500 text-white px-6 py-3 rounded-lg text-lg hover:bg-blue-600 transition-colors">
                        Confirm Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouse Modal -->
    <div id="warehouseModal" class="fixed inset-0 z-50 hidden overflow-y-auto"
         onclick="closeModalOnOutsideClick(event, 'warehouseModal')">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-semibold">Select Warehouse</h3>
                    <button type="button" onclick="closeWarehouseModal()"
                            class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    <div class="space-y-2">
                        <div class="flex items-center p-2 hover:bg-gray-100">
                            <input type="radio"
                                   name="warehouse_branch"
                                   value="Dawbon"
                                   id="warehouseDawbon"
                                   class="mr-2">
                            <label for="warehouseDawbon">Dawbon Warehouse</label>
                        </div>
                        <div class="flex items-center p-2 hover:bg-gray-100">
                            <input type="radio"
                                   name="warehouse_branch"
                                   value="Hlaing"
                                   id="warehouseHlaing"
                                   class="mr-2">
                            <label for="warehouseHlaing">Hlaing Warehouse</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end p-4 border-t bg-gray-50">
                    <button onclick="saveWarehouse()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Confirm Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let activeComplaintId = null;

        function showServiceCenterModal(complaintId) {
            console.log('Opening modal for complaint:', complaintId);
            activeComplaintId = complaintId;

            // Clear any previous selection
            document.querySelectorAll('input[name="service_center"]').forEach(radio => {
                radio.checked = false;
            });

            document.getElementById('serviceCenterModal').classList.remove('hidden');
        }

        function showWarehouseModal(complaintId) {
            console.log('Opening warehouse modal for complaint:', complaintId); // Debug log
            activeComplaintId = complaintId;
            // Clear any previously selected radio buttons
            document.querySelectorAll('input[name="warehouse_branch"]').forEach(radio => {
                radio.checked = false;
            });
            document.getElementById('warehouseModal').classList.remove('hidden');
        }

        function closeServiceCenterModal() {
            document.getElementById('serviceCenterModal').classList.add('hidden');
            // Clear radio selection
            document.querySelector(`input[name="response_type_${activeComplaintId}"]`).checked = false;
        }

        function closeWarehouseModal() {
            document.getElementById('warehouseModal').classList.add('hidden');
            // Clear radio selection if needed
            const radio = document.querySelector(`input[name="response_type_${activeComplaintId}"]`);
            if (radio) radio.checked = false;
        }

        function showNotification(message, type = 'error') {
            console.log('Showing notification:', message, type); // Debug log
            const notification = document.getElementById('notification');
            notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-500 ease-in-out z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            notification.classList.remove('hidden');

            setTimeout(() => {
                notification.classList.add('hidden');
            }, 5000);
        }

        function saveServiceCenter() {
            console.log('Save Service Center clicked');

            const selected = document.querySelector('input[name="service_center"]:checked');
            console.log('Selected radio button:', selected);
            console.log('Selected value:', selected?.value);

            if(!selected || !activeComplaintId) {
                showNotification('Please select a service center');
                return;
            }

            const data = {
                service_center_id: selected.value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };

            console.log('Sending data:', data);

            fetch(`/complaints/${activeComplaintId}/assign-service-center`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showNotification('Service center assigned successfully!', 'success');
                    closeServiceCenterModal();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to assign service center');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message || 'Failed to assign service center. Please try again.');
            });
        }

        function saveWarehouse() {
            console.log('Save Warehouse clicked');

            const selected = document.querySelector('input[name="warehouse_branch"]:checked');
            console.log('Selected Warehouse:', selected?.value);

            if(!selected || !activeComplaintId) {
                showNotification('Please select a warehouse');
                return;
            }

            const data = {
                warehouse: selected.value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };

            fetch(`/complaints/${activeComplaintId}/assign-warehouse`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showNotification('Warehouse assigned successfully!', 'success');
                    closeWarehouseModal();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to assign warehouse');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message || 'Failed to assign warehouse. Please try again.');
            });
        }

        // Add this new function for handling outside clicks
        function closeModalOnOutsideClick(event, modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = modal.querySelector('.bg-white');

            // Check if the click was outside the modal content
            if (event.target === modal) {
                if (modalId === 'serviceCenterModal') {
                    closeServiceCenterModal();
                } else if (modalId === 'warehouseModal') {
                    closeWarehouseModal();
                }
            }
        }
    </script>
</x-adm-dsh-nav>
