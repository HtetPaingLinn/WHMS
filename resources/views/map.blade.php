<?php
use App\Models\SalesInvoice;
$hasDeliveries = SalesInvoice::where('delivered', 1)->exists();
?>

<x-dashboard>
    <div class="w-full lg:ps-64">
        <div class="p-6 space-y-6">
            @if($hasDeliveries)
                <!-- Info Container -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Delivery Map System</h1>
                        <p class="text-sm text-gray-600 mt-2 mb-4">
                            🟥 Red pin: Delivery points |
                            🔵 Blue pin: Warehouse & Customer Service |
                            🟢 Green circle: Driver's location
                        </p>

                        <!-- Search and Driver Selection -->
                        <div class="flex items-center gap-4 mt-2">
                            <form id="searchForm" class="flex items-center">
                                <input
                                    type="text"
                                    id="invoiceSearch"
                                    placeholder="Search Invoice..."
                                    class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    🔍
                                </button>
                            </form>

                            <select
                                id="driverSelect"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select Driver</option>
                            </select>
                        </div>

                        <!-- Driver Details Card (now inside the info container) -->
                        <div id="driverDetailsCard" class="hidden mt-4 border border-gray-200 rounded-lg bg-gray-50 p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold text-gray-800">Driver Details</h3>
                                <button onclick="toggleDriverCard()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <span class="text-xl">✖</span>
                                </button>
                            </div>
                            <div id="driverDetailsContent" class="space-y-4">
                                <div class="bg-white p-3 rounded-md shadow-sm">
                                    <p class="mb-2 text-gray-700"><strong class="text-gray-900">Driver:</strong> <span id="selectedDriverName" class="ml-2">-</span></p>
                                    <p class="mb-2 text-gray-700"><strong class="text-gray-900">Contact:</strong> <span id="driverContact" class="ml-2">-</span></p>
                                    <p class="text-gray-700"><strong class="text-gray-900">Status:</strong> <span id="driverStatus" class="ml-2">-</span></p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Assigned Deliveries</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-white p-3 rounded-md shadow-sm">
                                            <p class="text-sm font-medium text-gray-900 mb-1">Invoice Numbers:</p>
                                            <ul id="invoiceList" class="list-disc ml-4 text-sm text-gray-600 space-y-1"></ul>
                                        </div>
                                        <div class="bg-white p-3 rounded-md shadow-sm">
                                            <p class="text-sm font-medium text-gray-900 mb-1">Delivery Locations:</p>
                                            <ul id="shopList" class="list-disc ml-4 text-sm text-gray-600 space-y-1"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="bg-white rounded-lg shadow p-6 dark:bg-neutral-800">
                    <div id="react-map" style="height: 600px;"></div>
                    <div id="delivery-info" class="mt-4"></div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-6 dark:bg-neutral-800">
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4M16 16l4-4-4-4" />
                        </svg>
                        <h2 class="mt-4 text-lg font-semibold text-gray-900">Your Order is Still Processing</h2>
                        <p class="mt-2 text-sm text-gray-600">
                            We're currently processing your order in the warehouse.
                            Sorry for any inconvenience.
                        </p>
                        <p class="mt-4 text-sm text-gray-600">
                            For inquiries, please call:
                            <a href="tel:0966800150" class="font-medium text-blue-600 hover:text-blue-500">
                                096-680-0150
                            </a>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($hasDeliveries)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        @vite(['resources/js/react/main2.tsx'])

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Fetch drivers from db.json
                fetch('http://localhost:3001/deliveries')
                    .then(response => response.json())
                    .then(deliveries => {
                        const select = document.getElementById('driverSelect');
                        select.innerHTML = '<option value="">Select Driver</option>'; // Reset options
                        deliveries.forEach(delivery => {
                            const option = document.createElement('option');
                            option.value = delivery.id;
                            option.textContent = `${delivery.driverName} (ID: ${delivery.id})`;
                            select.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching drivers:', error));

                // Update driver selection handler
                document.getElementById('driverSelect').addEventListener('change', function(e) {
                    const driverId = e.target.value;
                    if (driverId) {
                        fetch('http://localhost:3001/deliveries')
                            .then(response => response.json())
                            .then(deliveries => {
                                const driver = deliveries.find(d => d.id.toString() === driverId.toString());
                                if (driver) {
                                    updateDriverDetails(driverId);

                                    // Dispatch event with both ID and name
                                    const driverSelectEvent = new CustomEvent('driverSelect', {
                                        detail: {
                                            driverId: driver.id,
                                            driverName: driver.driverName
                                        }
                                    });
                                    window.dispatchEvent(driverSelectEvent);
                                }
                            });
                    }
                });
            });

            function updateDriverDetails(driverId) {
                console.log('Updating driver details for ID:', driverId); // Debug log
                fetch('http://localhost:3001/deliveries')
                    .then(response => response.json())
                    .then(deliveries => {
                        const driver = deliveries.find(d => d.id.toString() === driverId.toString());
                        console.log('Found driver:', driver); // Debug log
                        if (driver) {
                            document.getElementById('selectedDriverName').textContent = driver.driverName;
                            document.getElementById('driverContact').textContent = driver.driverContact;
                            document.getElementById('driverStatus').textContent = driver.status;

                            document.getElementById('invoiceList').innerHTML =
                                driver.invoiceNum.map(inv => `<li>Invoice #${inv}</li>`).join('');

                            document.getElementById('shopList').innerHTML =
                                driver.shopName.map(shop => `<li>${shop}</li>`).join('');

                            document.getElementById('driverDetailsCard').classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating driver details:', error);
                    });
            }

            function toggleDriverCard() {
                const card = document.getElementById('driverDetailsCard');
                card.classList.toggle('hidden');
            }
        </script>
    @endif
</x-dashboard>
