<x-dashboard>

    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-lg font-semibold mb-4">Recent Delivery Messages</h2>

        <!-- Search Bar -->
        <input type="text" id="searchBox" class="p-2 border rounded-lg w-full" placeholder="Search message..." onkeyup="searchMessage()">

        <!-- Messages Container -->
        <div id="messageContainer" class="mt-4 p-4 border rounded-xl bg-gray-100">
            No new messages
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastMessage" class="fixed bottom-10 right-10 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden">
        You have a new delivery message!
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Call function to check for new messages without reloading the page
            checkForNewMessage();
            // Optionally set an interval to check for new messages every 5 seconds
            setInterval(checkForNewMessage, 5000); // Check every 5 seconds
        });

        // Function to check and show new messages
        function checkForNewMessage() {
            const lastMessage = localStorage.getItem('lastMessage');
            const messageStatus = localStorage.getItem('messageStatus');

            if (lastMessage && messageStatus === 'sent') {
                // Update message container with the new message
                document.getElementById('messageContainer').innerText = lastMessage;

                // Show toast notification
                showToast("You have a new delivery message!");

                // Clear localStorage after displaying the message
                localStorage.removeItem('lastMessage');
                localStorage.removeItem('messageStatus');
            }
        }

        // Function to display toast notification
        function showToast(message) {
            const toast = document.getElementById('toastMessage');
            toast.innerText = message;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Search function to filter messages
        function searchMessage() {
            let input = document.getElementById('searchBox').value.toLowerCase();
            let message = document.getElementById('messageContainer').innerText.toLowerCase();

            if (message.includes(input)) {
                document.getElementById('messageContainer').style.display = "block";
            } else {
                document.getElementById('messageContainer').style.display = "none";
            }
        }
    </script>

</x-dashboard>
