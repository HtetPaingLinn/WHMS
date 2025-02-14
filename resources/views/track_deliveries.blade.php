<!-- filepath: /Users/htoothetnaung/Documents/Hackathon/hmz_whms_admin/resources/views/track_deliveries.blade.php -->
<x-adm-dsh-nav>
    <!-- Add Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <!-- filepath: /Users/htoothetnaung/Documents/Hackathon/hmz_whms_admin/resources/views/track_deliveries.blade.php -->
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Left side: Map -->
        <div class="lg:w-2/3">
            <!-- Title Card -->
            <div class="w-full bg-white shadow-md rounded-lg p-6 mb-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-800">Track Deliveries</h1>
                    <div class="flex items-center space-x-4">
                        <p class="text-sm text-gray-500">
                            <span class="text-red-500">🟥</span> Delivery points |
                            <span class="text-blue-500">🔵</span> Warehouse & Customer Service |
                            <span class="text-green-500">🟢</span> Driver's location
                        </p>
                        <div class="flex items-center">
                            <input
                                type="text"
                                id="invoiceSearch"
                                placeholder="Enter Invoice Number"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10"
                            />
                            <button onclick="searchInvoice()" class="ml-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- React Map Container -->
            <div id="react-map" class="w-full h-[calc(100vh-16rem)]"></div>
        </div>
        <!-- Right side: Delivery Information -->
        <div class="lg:w-1/3 bg-white shadow-md rounded-lg p-6 h-[calc(100vh-8rem)] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Delivery Routes Information</h2>
            <div id="delivery-info">
                <!-- This div will be populated by React -->
            </div>
        </div>
    </div>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/react/main.tsx'])

    <!-- Search Invoice Function -->
    <script>
        function searchInvoice() {
            const invoiceInput = document.getElementById('invoiceSearch').value.trim();

            if (invoiceInput !== "") {
                const event = new CustomEvent('invoiceSearch', {
                    detail: { invoiceNumber: invoiceInput }
                });

                window.dispatchEvent(event);
            } else {
                alert('Please enter an invoice number.');
            }
        }
    </script>
</x-adm-dsh-nav>
