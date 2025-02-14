
<x-adm-dsh-nav>
    <div class="relative overflow-x-auto sm:rounded-lg">
        <table id="resizable-table" class="w-full my-4 text-sm text-left rtl:text-right text-gray-500 border-collapse">
            <div class="flex justify-between items-center p-5 bg-white">
                <div class="text-lg font-semibold text-left rtl:text-right text-gray-900">
                    POUT SA  |  Inventory
                    <p class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                        Discover our online shop's inventory at POUT SA. Trusted by many.
                    </p>
                </div>
                <form id="date-form" method="GET" action="{{ route('inventory.show') }}">
                    <input type="date" id="date-picker" name="date"
                           value="{{ request('date', today()->toDateString()) }}"
                           class="rounded-lg border-gray-300 text-gray-700 px-3 py-2 text-sm focus:ring focus:ring-blue-300">
                </form>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const datePicker = document.getElementById("date-picker");
                        const buttonsGp = document.getElementById("buttons-gp");
                        const addStockButtons = document.querySelectorAll("#add-stock-btn");

                        const today = new Date().toISOString().split("T")[0];
                        datePicker.value = today;
                        datePicker.max = today;
                    });

                    document.addEventListener("DOMContentLoaded", function () {
                        const urlParams = new URLSearchParams(window.location.search);
                        const selectedDate = urlParams.get('date');
                        const datePicker = document.getElementById("date-picker");

                        if (selectedDate) {
                            datePicker.value = selectedDate; // Keep the selected date after reload
                        }

                        datePicker.addEventListener("change", function () {
                            document.getElementById("date-form").submit();
                        });
                    });
                </script>

            </div>

            <div id="buttons-gp" class="inline-flex rounded-lg border border-gray-100 bg-gray-100 p-1">
                <button id="stocks-btn" class="tab-button inline-flex items-center gap-2 rounded-md bg-white px-4 py-1 text-sm text-blue-500 shadow-xs focus:relative" data-type="stocks">
                    <i class="fa-light fa-boxes-stacked"></i>
                    <span class="text-[13px]"> Stocks </span>
                </button>

                <button id="hot-items-btn" class="tab-button inline-flex items-center gap-2 rounded-md px-4 py-1 text-sm text-gray-500 hover:text-gray-700 focus:relative" data-type="hotItems">
                    <i class="fa-regular fa-fire"></i>
                    <span class="text-[13px]"> Hot items </span>
                </button>

                <button id="empty-stocks-btn" class="tab-button inline-flex items-center gap-2 rounded-md px-4 py-1 text-sm text-gray-500 hover:text-gray-700 focus:relative" data-type="emptyStocks">
                    <i class="fa-duotone fa-solid fa-person-dolly-empty"></i>
                    <span class="text-[13px]"> Empty stocks </span>
                </button>
            </div>

            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr id="resizable-header-row">
                <th class="resizable px-6 py-3">Item Name</th>
                <th class="resizable px-6 py-3">Branch</th>
                <th class="resizable px-6 py-3">Opening Stock</th>
                <th class="resizable px-6 py-3">Received</th>
                <th class="resizable px-6 py-3">Dispatched</th>
                <th class="resizable px-6 py-3">Closing Stock</th>
                <th class="px-6 py-3"><span class="sr-only">Edit</span></th>
            </tr>
            </thead>
            <tbody id="resizable-body">
            <tr class="resizable-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200" data-id="">
                <td class="resizable px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" data-field="partner_shops_name">
                    ABC
                </td>
                <td class="resizable px-6 py-4" data-field="partner_shops_address">ABC</td>
                <td class="resizable px-6 py-4" data-field="partner_shops_township">hdosf</td>
                <td class="resizable px-6 py-4" data-field="partner_shops_region">isffe</td>
                <td class="resizable px-6 py-4" data-field="contact_primary">ef99vf</td>
                <td class="resizable px-6 py-4" data-field="contact_primary">erhcier</td>
                <td class="px-6 py-4 text-right flex flex-col gap-y-1">
                    <a href="#" id="add-stock-btn" class="font-medium text-blue-600 dark:text-blue-500 hover:underline edit-btn" >Edit</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.tab-button');
            const tableBody = document.getElementById('resizable-body');

            // Example data (replace with your actual data fetching logic)
            const data = {
                stocks: @json($finalRecords),
                hotItems: @json($hotRecords),
                emptyStocks: @json($zeroRecords)
            };

            function renderTable(items, type, urlDate) {
                tableBody.innerHTML = '';

                // Get today's date in the same format as urlDate
                const today = new Date().toISOString().split('T')[0];

                if (items.length === 0) {
                    // Display message if no data exists for the selected section
                    const message = `
        <tr>
            <td colspan="7" class="text-center text-gray-500 py-4">
                ${type === 'emptyStocks' ? 'No shortages detected in the zero stocks section.' : 'No data available.'}
            </td>
        </tr>
        `;
                    tableBody.insertAdjacentHTML('beforeend', message);
                } else {
                    items.forEach(item => {
                        const addStockButton = urlDate === today
                            ? `<a href="#" id="add-stock-btn" class="font-medium text-blue-600 dark:text-blue-500 hover:underline edit-btn" data-id="${item.id}">Add Stock</a>`
                            : '';

                        const row = `
            <tr class="resizable-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <td class="resizable px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${item.item_name}</td>
                <td class="resizable px-6 py-4">${item.warehouse_branch}</td>
                <td class="resizable px-6 py-4">${item.opening_balance}</td>
                <td class="resizable px-6 py-4">${item.received}</td>
                <td class="resizable px-6 py-4">${item.dispatched}</td>
                <td class="resizable px-6 py-4">${item.closing_balance}</td>
                <td class="px-6 py-4 text-right flex flex-col gap-y-1">
                    ${addStockButton}
                </td>
            </tr>
            `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            }


            const today = new Date().toISOString().split("T")[0];
            const urlParams = new URLSearchParams(window.location.search);
            const urlDate = urlParams.get('date') || today;

            // Handle button clicks and update active styles
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    buttons.forEach(btn => btn.className = 'inline-flex items-center gap-2 rounded-md px-4 py-1 text-sm text-gray-500 hover:text-gray-700 focus:relative');
                    this.className = 'inline-flex items-center gap-2 rounded-md bg-white px-4 py-1 text-sm text-blue-500 shadow-xs focus:relative';

                    // Render corresponding data
                    const type = this.getAttribute('data-type');
                    renderTable(data[type], type, urlDate);
                });
            });



            // Set default active tab
            document.getElementById('stocks-btn').click();
        });

        document.addEventListener('click', function (event) {
            const editModal = document.getElementById('edit-modal');
            if (event.target.classList.contains('edit-btn')) {
                event.preventDefault();
                console.log("Edit button clicked!");

                editModal.classList.remove('hidden');

                document.getElementById('edit-id').value = event.target.getAttribute('data-id');
            }

            const closeModalButton = document.getElementById('closeModalButton');

            closeModalButton.addEventListener('click', function () {
                editModal.classList.add('hidden');
            });

            editModal.addEventListener('click', function (event) {
                if (event.target === editModal) {
                    editModal.classList.add('hidden');
                }
            });
        });
    </script>

    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 max-h-[90vh] overflow-y-auto flex flex-col relative">
            <button id="closeModalButton" class="absolute top-0 right-0 p-6 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="font-semibold font-poppins text-lg text-gray-800">Add Stock Here</h2>
            <p class="text-gray-500 text-xs mb-6">Update the fields below to modify your customer details.</p>

            <label for="name" class="block mb-2 text-xs font-medium text-gray-900">Enter amount</label>
            <form action="{{ route('inventory.update') }}" method="post">
                @csrf
                @method('PATCH')
                <div class="flex flex-row gap-4">
                    <input type="hidden" id="edit-id" name="id">
                    <input type="number" id="edit-id" name="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" required />
                    <button type="submit" class="bg-blue-700 text-white text-sm px-6 py-2 rounded-lg transition hover:bg-blue-900 duration-300 ease-in-out transform hover:scale-105">
                        Create
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-adm-dsh-nav>

<style>
    #edit-modal {
        z-index: 150
    }
</style>
