<!-- resources/views/components/app.blade.php -->
<!doctype html>
<html lang="en">
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="https://preline.co/">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Comprehensive overview with charts, tables, and a streamlined dashboard layout for easy data visualization and analysis.">

    <!-- Social Media Meta Tags -->
    <meta name="twitter:site" content="@preline">
    <meta name="twitter:creator" content="@preline">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tailwind CSS Admin Template | Preline UI, crafted with Tailwind CSS">
    <meta name="twitter:description" content="Comprehensive overview with charts, tables, and a streamlined dashboard layout for easy data visualization and analysis.">
    <meta name="twitter:image" content="https://preline.co/assets/img/og-image.png">

    <meta property="og:url" content="https://preline.co/">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Preline">
    <meta property="og:title" content="Tailwind CSS Admin Template | Preline UI, crafted with Tailwind CSS">
    <meta property="og:description" content="Comprehensive overview with charts, tables, and a streamlined dashboard layout for easy data visualization and analysis.">
    <meta property="og:image" content="https://preline.co/assets/img/og-image.png">

    <!-- Title -->
    <title> Pouk Sa | Delivery Man Dashboard </title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="shortcut icon" href="../../../public/favicon.ico">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">

    <!-- -->
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Theme Check and Update Script -->
    <script>
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
        else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
        else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
        else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
    </script>

    <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">
</head>
<body class="bg-gray-50">
<div class="container mx-auto">
    {{ $slot }} <!-- Content is injected here -->
</div>

<script>
    let currentDeliveryIndex = null;
    let currentDeliveryId = null;
    let updateDeliveryStatusRoute = "{{ route('updateDel') }}";
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function openFirstModal(index, name, address, phone, deliveryId) {
        document.getElementById('invoiceDetails').innerText = `Invoice No: ${index + 1} | ${name} - ${address}`;
        document.getElementById('messageBox').value = `Your order is on the way. Delivery will be made shortly. If you need to contact the driver, please call: ${phone}.`;
        currentDeliveryIndex = index;
        currentDeliveryId = deliveryId;
        document.getElementById('firstModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('firstModal').classList.remove('active');
    }

    //Send Messages
    function confirmPhone() {
        alert('Phone number confirmed!');
    }

    function sendMessage() {
        const message = document.getElementById('messageBox').value;
        console.log('sendMessage function called');

        // Get CSRF token from the meta tag
        console.log("CSRF Token:", csrfToken);

        fetch(updateDeliveryStatusRoute, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            credentials: 'same-origin',
            body: JSON.stringify({ id: currentDeliveryId })
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || "Failed to update status."); });
                }
                return response.json();
            })
            .then(data => {
                console.log("Success:", data);
                if (data.success) {
                    document.getElementById('status-' + currentDeliveryIndex).innerText = "Sent";
                    document.getElementById('status-' + currentDeliveryIndex).classList.replace('bg-red-500', 'bg-green-500');
                    document.getElementById('btn-' + currentDeliveryIndex).disabled = true;
                    showToast("Message sent successfully!");
                } else {
                    alert("Error: " + (data.message || "Failed to update status. Please try again later."));
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                alert("An unexpected error occurred. Please try again later.");
            });
        closeModal();
    }

    // function sendMessage() {
    //     const message = document.getElementById('messageBox').value;
    //     console.log('sendMessage function called');
    //     console.log("Message:", message);
    //
    //     // Disable the button when clicked to prevent multiple submissions
    //     const sendButton = document.getElementById('btn-' + currentDeliveryIndex);
    //     sendButton.disabled = true;
    //     sendButton.classList.add('cursor-not-allowed'); // Optionally change appearance of the button
    //
    //     // Simulate sending a request and receiving a response
    //     setTimeout(() => {
    //         // Simulate a successful response from the "backend"
    //         const data = { success: true };
    //
    //         if (data.success) {
    //             document.getElementById('status-' + currentDeliveryIndex).innerText = "Sent";
    //             document.getElementById('status-' + currentDeliveryIndex).classList.replace('bg-red-500', 'bg-green-500');
    //             showToast("Message sent successfully!");
    //         } else {
    //             alert("Failed to update status.");
    //         }
    //     }, 1000); // Simulate network delay
    //
    //     closeModal();
    // }

    function showToast(message) {
        const toast = document.getElementById('toastMessage');
        toast.innerText = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2000);
    }
</script>
</body>
</html>
