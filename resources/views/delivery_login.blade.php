<x-Drv-dsh>
    <div class="flex items-center min-h-screen bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
        <div class="container mx-auto flex items-center justify-center space-x-12">
            <div class="max-w-md w-full p-8 bg-white rounded-xl shadow-xl transform transition-all duration-500 hover:scale-105 hover:shadow-2xl">
                <div class="text-center mb-6">
                    <h1 class="my-3 text-4xl font-semibold text-gray-800 dark:text-gray-200">Sign in</h1>
                    <p class="text-gray-500 dark:text-gray-400">Sign in to access your Driver account</p>
                </div>

                <!-- Right Column (Lottie Animation) -->
                <div class="w-90 flex items-center justify-center">
                    <dotlottie-player src="https://lottie.host/c724f0f2-8c31-41dd-9b03-4f9966d1c572/ItiWd8GgQZ.lottie" background="transparent" speed="1" style="width: 100%; height: 100%" loop autoplay></dotlottie-player>
                </div>

                <div class="m-7">
                    <form method="POST" class="space-y-6" action="{{ route('driver.login.submit') }}">
                        @csrf

                        <!-- Driver Email -->
                        <div class="mb-6">
                            <label for="email" class="block mb-2 text-lg font-medium text-gray-600 dark:text-gray-400">Delivery Email</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" placeholder="Your Delivery Email" required class="w-full px-4 py-3 rounded-md border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500 dark:border-gray-600 dark:focus:ring-indigo-500" />
                            </div>
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Driver Password -->
                        <div class="mb-6">
                            <div class="flex justify-between mb-2">
                                <label for="password" class="text-lg font-medium text-gray-600 dark:text-gray-400">Delivery Password</label>
                                <a href="javascript:void(0);" id="forgot-password-link" class="text-sm text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">Forgot password?</a>
                            </div>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="Password" required class="w-full px-4 py-3 rounded-md border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500 dark:border-gray-600 dark:focus:ring-indigo-500" />
                            </div>
                            @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sign in Button -->
                        <div class="mb-6">
                            <button type="submit" class="w-full px-4 py-3 text-white bg-indigo-600 rounded-md focus:outline-none hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 transform transition-all duration-200 active:transform active:translate-x-4">
                                Sign in
                            </button>
                        </div>

                        <!-- Sign up Link -->
                        <p class="text-sm text-center text-gray-400">Don't have an account yet? <a href="#!" class="text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">Sign up</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-Drv-dsh>

<style>
    /* Loader animation */
    .loader {
        display: inline-flex;
        gap: 5px;
    }
    .loader:before,
    .loader:after {
        content: "";
        width: 25px;
        aspect-ratio: 1;
        box-shadow: 0 0 0 3px inset #fff;
        animation: l5 1.5s infinite;
    }
    .loader:after {
        --s:-1;
    }
    @keyframes l5 {
        0%   {transform:scaleX(var(--s,1)) translate(0) scale(1)}
        33%  {transform:scaleX(var(--s,1)) translate(calc(50% + 2.5px)) scale(1)}
        66%  {transform:scaleX(var(--s,1)) translate(calc(50% + 2.5px)) scale(2)}
        100% {transform:scaleX(var(--s,1)) translate(0) scale(1)}
    }
</style>

<script>

    // Show modal on forgot password click
    document.getElementById('forgot-password-link').addEventListener('click', function() {
        document.getElementById('forgot-password-modal').classList.remove('hidden');
    });

    // Close modal
    function closeModal() {
        document.getElementById('forgot-password-modal').classList.add('hidden');
    }

    // Handle the reset password submission
    function submitResetPassword() {
        const email = document.getElementById('reset-email').value;
        // Simulate password reset logic here
        console.log(`Password reset for: ${email}`);
        closeModal();  // Close modal after submission
        alert('Password reset instructions sent to your email.');
    }

    // Loader Caller
    document.getElementById('sign-in-btn').addEventListener('click', function() {
        // Show the full-page loader
        document.getElementById('full-page-loader').classList.remove('hidden');

        // Simulate a delay for the sign-in process (e.g., 3 seconds)
        setTimeout(function() {
            // After the simulated sign-in process, hide the loader
            document.getElementById('full-page-loader').classList.add('hidden');
            // Optionally, you can redirect to another page
            window.location.href = '/del-dsh';  // Redirect after 3 seconds
        }, 3000); // Adjust the time as needed (in milliseconds)
    });


</script>
