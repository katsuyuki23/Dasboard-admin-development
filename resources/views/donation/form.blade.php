<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Panti Asuhan Assholihin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-12 flex flex-col items-center">
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

            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Donasi Sekarang
            </button>
        </form>

        <div id="message" class="mt-4 text-center hidden"></div>
    </div>

    <!-- Donation History -->
    <div class="mt-12 bg-white p-8 rounded-lg shadow-md w-full max-w-6xl">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Donation History</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs leading-normal">
                        <th class="py-3 px-6">Invoice</th>
                        <th class="py-3 px-6">Donor</th>
                        <th class="py-3 px-6">Amount</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Date</th>
                        <th class="py-3 px-6">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($donations as $donation)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <span class="font-medium">{{ $donation->order_id }}</span>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <div class="flex items-center">
                                <div>
                                    <div class="font-bold">{{ $donation->donatur->nama ?? 'Hamba Allah' }}</div>
                                    <div class="text-xs text-gray-500">{{ $donation->donatur->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-left">
                            Rp {{ number_format($donation->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            @php
                                $status = strtolower($donation->status_pembayaran);
                            @endphp
                            @if($status == 'success' || $status == 'paid')
                                <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Success</span>
                            @elseif($status == 'pending')
                                <span class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs">Pending</span>
                            @elseif($status == 'failed')
                                <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Failed</span>
                            @else
                                <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-xs">{{ $donation->status_pembayaran }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-left">
                            {{ $donation->created_at->format('d M Y H:i') }}
                        </td>
                         <td class="py-3 px-6 text-center">
                             @if($status == 'pending' && $donation->payment_url)
                                <a href="{{ $donation->payment_url }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                    Pay Now
                                </a>
                             @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $donations->links() }}
        </div>
    </div>

    <script>
        document.getElementById('donationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Processing...';
            
            document.getElementById('message').classList.add('hidden');

            // Send both styles for maximum compatibility
            const formData = {
                donor_name: document.getElementById('donor_name').value,
                nama: document.getElementById('donor_name').value,
                donor_email: document.getElementById('donor_email').value,
                email: document.getElementById('donor_email').value,
                amount: document.getElementById('amount').value,
                nominal: document.getElementById('amount').value,
            };

            try {
                const response = await fetch('/api/v1/donation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok && (result.status === 'success' || result.success === true)) {
                    if (result.payment_url) {
                         window.location.href = result.payment_url;
                    } else if (result.data && result.data.payment_url) {
                         window.location.href = result.data.payment_url;
                    } else {
                        throw new Error('Unexpected payment response: No URL found');
                    }
                } else {
                    // Specific handling for validation errors from Laravel
                    let errorMsg = result.message || 'Payment initiation failed';
                    if (result.errors) {
                        const errorDetails = Object.values(result.errors).flat().join(' ');
                        errorMsg += ': ' + errorDetails;
                    }
                    throw new Error(errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('message').innerText = error.message;
                document.getElementById('message').className = 'mt-4 text-center text-red-600 bg-red-100 p-2 rounded border border-red-200';
                document.getElementById('message').classList.remove('hidden');
                
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });
    </script>
</body>
</html>
