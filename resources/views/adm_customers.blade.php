<style>
    #edit-modal {
        z-index: 150
    }
    #add-modal {
        z-index: 150
    }
    .bg-poutsa {
        background-color: #2563eb;
    }
</style>

<x-adm-dsh-nav>
    <div class="relative overflow-x-auto sm:rounded-lg">
        <table id="resizable-table" class="w-full my-4 text-sm text-left rtl:text-right text-gray-500 border-collapse">
            <div class="flex justify-between items-center p-5 bg-white">
                <div class="text-lg font-semibold text-left rtl:text-right text-gray-900">
                    Our Customers
                    <p class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                        Discover the individuals and businesses who trust us to meet their needs every day.
                    </p>
                </div>
                <button id="add-btn" class="group relative inline-flex h-9 items-center justify-center overflow-hidden rounded-lg px-7 font-medium text-xs text-white bg-poutsa gap-x-2 hover:bg-red-500 ml-4">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Shop</span>
                </button>
            </div>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr id="resizable-header-row">
                <th class="resizable px-6 py-3">Shop name</th>
                <th class="resizable px-6 py-3">Address</th>
                <th class="resizable px-6 py-3">Township</th>
                <th class="resizable px-6 py-3">Region</th>
                <th class="resizable px-6 py-3">Contact</th>
                <th class="px-6 py-3"><span class="sr-only">Edit</span></th>
            </tr>
            </thead>
            <tbody id="resizable-body">
            @foreach($shops as $shop)
                <tr class="resizable-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200" data-id="{{ $shop->partner_shops_id }}">
                    <td class="resizable px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" data-field="partner_shops_name">
                        {{ $shop->partner_shops_name }}
                    </td>
                    <td class="resizable px-6 py-4" data-field="partner_shops_address">{{ $shop->partner_shops_address }}</td>
                    <td class="resizable px-6 py-4" data-field="partner_shops_township">{{ $shop->partner_shops_township }}</td>
                    <td class="resizable px-6 py-4" data-field="partner_shops_region">{{ $shop->partner_shops_region }}</td>
                    <td class="resizable px-6 py-4" data-field="contact_primary">{{ $shop->contact_primary }}</td>
                    <td class="px-6 py-4 text-right flex flex-col gap-y-1">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline edit-btn"
                           data-id="{{ $shop->partner_shops_id }}"
                           data-shop-name="{{ $shop -> partner_shops_name }}"
                           data-shop-address="{{ $shop -> partner_shops_address }}"
                           data-shop-township="{{ $shop -> partner_shops_township }}"
                           data-shop-region="{{ $shop -> partner_shops_region }}"
                           data-shop-contact="{{ $shop -> contact_primary }}"
                        >Edit</a>

                        <form action="{{ route('customers.destroy', $shop->partner_shops_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="delete-btn" class="delete-btn font-medium text-red-500 dark:text-blue-500 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-2/3 max-h-[90vh] overflow-y-auto flex flex-col relative">
            <button id="closeModalButton" class="absolute top-0 right-0 p-6 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="font-semibold font-poppins text-lg text-gray-800">Edit customer details</h2>
            <p class="text-gray-500 text-xs mb-6">Update the fields below to modify your customer details.</p>

            <form action="{{ route('customers.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="flex flex-row w-full space-x-6">
                    <div class="flex-1">
                        <label for="edit-name" class="block mb-2 text-sm font-medium text-gray-900">Shop name</label>
                        <input type="text" id="edit-name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC" required />
                    </div>
                    <div class="flex-1">
                        <label for="edit-address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                        <input type="text" id="edit-address" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC Street" required />
                    </div>
                </div>
                <div class="flex flex-row w-full mt-4 space-x-6">
                    <div class="flex-1">
                        <label for="edit-brand" class="block mb-2 text-sm font-medium text-gray-900">Township</label>
                        <input type="text" id="edit-township" name="township" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC Tsp" required />
                    </div>
                    <div class="flex-1">
                        <label for="edit-region" class="block mb-2 text-sm font-medium text-gray-900">Region</label>
                        <input type="text" id="edit-region" name="region" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC city" required />
                    </div>
                    <div class="flex-1">
                        <label for="edit-contact" class="block mb-2 text-sm font-medium text-gray-900">Contact</label>
                        <input type="text" id="edit-contact" name="contact" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="09xxxxxxxxx" required />
                    </div>
                    <input type="hidden" id="edit-id" name="id">
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-700 text-white text-sm px-6 py-2 rounded-lg transition hover:bg-blue-900 duration-300 ease-in-out transform hover:scale-105">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="add-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-2/3 max-h-[90vh] overflow-y-auto flex flex-col relative">
            <button id="closeModalButton2" class="absolute top-0 right-0 p-6 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="font-semibold font-poppins text-lg text-gray-800">Add a customer</h2>
            <p class="text-gray-500 text-xs mb-6">Fill in the fields below to add a new customer.</p>

            <form action="{{ route('customers.store') }}" method="post">
                @csrf
                <div class="flex flex-row w-full space-x-6">
                    <div class="flex-1">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Shop name</label>
                        <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC" required />
                    </div>
                    <div class="flex-1">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                        <input type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC Street" required />
                    </div>
                </div>
                <div class="flex flex-row w-full mt-4 space-x-6">
                    <div class="flex-1">
                        <label for="township" class="block mb-2 text-sm font-medium text-gray-900">Township</label>
                        <input type="text" name="township" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC Tsp" required />
                    </div>
                    <div class="flex-1">
                        <label for="region" class="block mb-2 text-sm font-medium text-gray-900">Region</label>
                        <input type="text" name="region" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="ABC city" required />
                    </div>
                    <div class="flex-1">
                        <label for="contact" class="block mb-2 text-sm font-medium text-gray-900">Contact</label>
                        <input type="text" name="contact" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg block w-full p-2.5" placeholder="09xxxxxxxxx" required />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-700 text-white text-sm px-6 py-2 rounded-lg transition hover:bg-blue-900 duration-300 ease-in-out transform hover:scale-105">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            const closeModalButton = document.getElementById('closeModalButton');
            const editModal = document.getElementById('edit-modal');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    console.log("Edit button clicked!");
                    editModal.classList.remove('hidden');

                    const shopId = this.getAttribute('data-id');
                    const shopName = this.getAttribute('data-shop-name');
                    const address = this.getAttribute('data-shop-address');
                    const township = this.getAttribute('data-shop-township');
                    const region = this.getAttribute('data-shop-region');
                    const contact = this.getAttribute('data-shop-contact');

                    document.getElementById('edit-id').value = shopId;
                    document.getElementById('edit-name').value = shopName;
                    document.getElementById('edit-address').value = address;
                    document.getElementById('edit-township').value = township;
                    document.getElementById('edit-region').value = region;
                    document.getElementById('edit-contact').value = contact;
                });
            });

            // Close modal
            closeModalButton.addEventListener('click', function () {
                editModal.classList.add('hidden');
            });

            editModal.addEventListener('click', function (event) {
                if (event.target === editModal) {
                    editModal.classList.add('hidden');
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.getElementById('add-btn');
            const closeModalButton2 = document.getElementById('closeModalButton2');
            const addModal = document.getElementById('add-modal');

            addButton.addEventListener('click', function () {
                console.log("Add button clicked!");
                addModal.classList.remove('hidden');
            });

            closeModalButton2.addEventListener('click', function () {
                addModal.classList.add('hidden');
            });

            addModal.addEventListener('click', function (event) {
                if (event.target === addModal) {
                    addModal.classList.add('hidden');
                }
            });
        });

        document.querySelectorAll('#resizable-table th.resizable').forEach(th => {
            let startX, startWidth;

            const resizer = document.createElement('div');
            resizer.classList.add('resizer');
            th.appendChild(resizer);

            resizer.addEventListener('mousedown', e => {
                startX = e.pageX;
                startWidth = th.offsetWidth;

                const onMouseMove = e => {
                    const newWidth = startWidth + (e.pageX - startX);
                    th.style.width = `${newWidth}px`;
                };

                const onMouseUp = () => {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                };

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });
        });
    </script>

    <style>
        #resizable-table th,
        #resizable-table td {
            position: relative;
            border-right: 1px solid rgba(200, 200, 200, 0.6); /* Column divider lines */
        }

        #resizable-table th:last-child,
        #resizable-table td:last-child {
            border-right: none; /* No divider on the last column */
        }

        #resizable-table th .resizer {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            cursor: col-resize;
            z-index: 1;
            background-color: #2563eb; /* Pale line for resizing */
        }

        #resizable-table th .resizer:hover {
            background-color: rgba(150, 150, 150, 0.8); /* Highlight on hover */
        }
    </style>
</x-adm-dsh-nav>
