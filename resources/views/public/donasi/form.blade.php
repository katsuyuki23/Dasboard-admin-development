<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Panti Asuhan Assholihin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Donasi Panti Asuhan Assholihin</h1>
        
        <form id="donationForm" class="space-y-4">
            @csrf
            <div>
                <label for="donor_name" class="block text-sm font-medium text-gray-700">Nama Donatur</label>
                <input type="text" id="donor_name" name="donor_name" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="donor_email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="donor_email" name="donor_email" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah Donasi (IDR)</label>
                <input type="number" id="amount" name="amount" min="10000" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Minimal donasi Rp 10.000</p>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                
                <div class="grid grid-cols-2 gap-2">
                    <!-- Virtual Accounts -->
                    <label class="border p-3 rounded cursor-pointer hover:bg-gray-50 flex items-center">
                        <input type="radio" name="payment_method" value="VA_BCA" required class="mr-2">
                        <span>BCA VA</span>
                    </label>
                    <label class="border p-3 rounded cursor-pointer hover:bg-gray-50 flex items-center">
                        <input type="radio" name="payment_method" value="VA_MANDIRI" required class="mr-2">
                        <span>Mandiri VA</span>
                    </label>
                    <label class="border p-3 rounded cursor-pointer hover:bg-gray-50 flex items-center">
                        <input type="radio" name="payment_method" value="VA_BRI" required class="mr-2">
                        <span>BRI VA</span>
                    </label>
                    <label class="border p-3 rounded cursor-pointer hover:bg-gray-50 flex items-center">
                        <input type="radio" name="payment_method" value="VA_BNI" required class="mr-2">
                        <span>BNI VA</span>
                    </label>
                    
                    <!-- E-Wallets -->
                    <label class="border p-3 rounded cursor-pointer hover:bg-gray-50 flex items-center">
                        <input type="radio" name="payment_method" value="EMONEY_DANA" required class="mr-2">
                        <span>DANA</span>
                    </label>
                     <label class="border p-3 rounded cursor-pointer hover:bg-gray-50 flex items-center">
                        <input type="radio" name="payment_method" value="EMONEY_SHOPEE_PAY" required class="mr-2">
                        <span>ShopeePay</span>
                    </label>
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Donasi Sekarang
            </button>
        </form>

        <div id="message" class="mt-4 text-center hidden"></div>
        
        <!-- VA Result Display -->
        <div id="vaResult" class="mt-6 hidden bg-green-50 p-4 rounded border border-green-200">
            <h3 class="font-bold text-green-800 mb-2 text-center">Kode Pembayaran (Virtual Account)</h3>
            <p class="text-sm text-gray-600 mb-1 text-center">Silakan transfer ke nomor berikut:</p>
            <div id="vaNumber" class="text-2xl font-mono font-bold text-center my-3 text-gray-800 bg-white p-2 border rounded"></div>
            <p class="text-xs text-gray-500 text-center">Simpan nomor ini. Pembayaran akan terverifikasi otomatis.</p>
        </div>
    </div>

    <!-- DOKU Checkout JS (Production) -->
    <script src="https://jokul.doku.com/jokul-checkout-js/v1/jokul-checkout-1.0.0.js"></script>

    <script>
        document.getElementById('donationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            
            document.getElementById('message').classList.add('hidden');
            document.getElementById('vaResult').classList.add('hidden');

            const formData = {
                donor_name: document.getElementById('donor_name').value,
                donor_email: document.getElementById('donor_email').value,
                amount: document.getElementById('amount').value,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value
            };

            try {
                const response = await fetch('/api/landing/payment/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    const data = result.data;
                    if (data.payment_url && !data.va_number) {
                         // Redirect needed (E-Wallet)
                         window.location.href = data.payment_url;
                    } else if (data.va_number) {
                        // Display VA Number
                        document.getElementById('vaNumber').innerText = data.va_number;
                        document.getElementById('vaResult').classList.remove('hidden');
                        
                        btn.innerText = 'Menunggu Pembayaran';
                    }
                } else {
                    throw new Error(result.message || 'Payment initiation failed');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('message').innerText = error.message;
                document.getElementById('message').className = 'mt-4 text-center text-red-600';
                document.getElementById('message').classList.remove('hidden');
                
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });
    </script>
</body>
</html>
