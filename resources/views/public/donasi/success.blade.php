<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Pembayaran - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F3F4F6; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-status { max-width: 400px; width: 100%; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; padding: 40px; background: white; }
        .spinner-border { width: 3rem; height: 3rem; color: #4F46E5; }
    </style>
</head>
<body>

<div class="card-status">
    <div class="mb-4">
        <div class="spinner-border" role="status"></div>
    </div>
    <h4 class="fw-bold mb-2">Memproses Pembayaran...</h4>
    <p class="text-muted mb-4">Mohon selesaikan pembayaran Anda di popup yang muncul.</p>
    
    <button id="pay-button" class="btn btn-primary rounded-pill px-4 w-100">
        Buka Ulang Popup Pembayaran
    </button>
    <a href="{{ route('landing') }}" class="btn btn-link text-muted mt-3 text-decoration-none">Batal / Kembali</a>
</div>

<script>
    const snapToken = "{{ $donasi->snap_token }}";

    function triggerSnap() {
        snap.pay(snapToken, {
            onSuccess: function(result) {
                // Redirect to a thank you page or show success message
                document.querySelector('.card-status').innerHTML = `
                    <div class="text-success mb-3" style="font-size: 50px;"><i class="fas fa-check-circle"></i></div>
                    <h3 class="fw-bold">Terima Kasih!</h3>
                    <p class="text-muted">Donasi Anda telah berhasil kami terima.</p>
                    <a href="{{ route('landing') }}" class="btn btn-primary rounded-pill w-100">Kembali ke Beranda</a>
                `;
            },
            onPending: function(result) {
                location.reload();
            },
            onError: function(result) {
                alert("Pembayaran Gagal!");
                location.reload();
            },
            onClose: function() {
                // Do nothing, let them click button to reopen
            }
        });
    }

    // Auto trigger
    triggerSnap();

    document.getElementById('pay-button').addEventListener('click', function() {
        triggerSnap();
    });
</script>

</body>
</html>
