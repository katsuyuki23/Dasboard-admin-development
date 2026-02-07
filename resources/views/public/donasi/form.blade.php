<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Amanah - Yayasan Panti Asuhan Assholihin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #00A550;
            --primary-dark: #008040;
            --secondary: #F4C430;
            --bg-body: #F3F4F6;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
            background-color: #ECF5E1;
            position: relative;
        }

        /* Batik Animation adapted from Uiverse.io by SelfMadeSystem */
        .batik-bg {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            --l: #00A550; /* Primary Green */
            --b: #ECF5E1; /* Light Green BG */
            --c: #FFFFFF; /* White */
            --w: 104px;
            background: var(--b);
            overflow: hidden;
        }

        .batik-bg::after, .batik-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 3000px; /* Force wide width to cover screen */
            animation: weee 60s linear infinite; /* Slowed down for background */
            background-repeat: repeat-y;
            background-size: 50px 20px, 50px 20px, 50px 80px, 50px 80px, 50px 80px, 50px 80px, 50px 80px, 50px 80px;
            /* Generated 25 columns */
            background-position-x: 
                4px, 54px, 
                108px, 158px, 
                212px, 262px, 
                316px, 366px, 
                420px, 470px, 
                524px, 574px, 
                628px, 678px, 
                732px, 782px, 
                836px, 886px, 
                940px, 990px, 
                1044px, 1094px, 
                1148px, 1198px, 
                1252px, 1302px, 
                1356px, 1406px, 
                1460px, 1510px, 
                1564px, 1614px, 
                1668px, 1718px, 
                1772px, 1822px, 
                1876px, 1926px, 
                1980px, 2030px, 
                2084px, 2134px, 
                2188px, 2238px, 
                2292px, 2342px, 
                2396px, 2446px, 
                2500px, 2550px;
        }

        .batik-bg::after {
            animation: after-anim-opacity 5s linear infinite, weee 20s linear infinite;
            background-image: none, none,
                linear-gradient(45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%),
                linear-gradient(-45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%),
                none, none,
                linear-gradient(45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%),
                linear-gradient(-45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%);
        }

        .batik-bg::before {
            animation: before-anim-opacity 5s linear infinite, before-anim-bg 5s step-start infinite, weee 20s linear infinite;
        }

        @keyframes after-anim-opacity {
            5% { opacity: 1; }
            0%, 4.99%, 20%, 100% { opacity: 0; }
        }

        @keyframes before-anim-opacity {
            0%, 10.4% { opacity: 1; }
            9.9%, 10.39%, 30%, 100% { opacity: 0; }
        }

        @keyframes before-anim-bg {
            0%, 9.9%, 99% {
                background-image: none, none, none, none,
                linear-gradient(45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%),
                linear-gradient(-45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%);
            }
            10%, 40% {
                background-image: 
                linear-gradient(45deg, var(--c) 0% 14.2%, transparent 14.2% 28.5%, var(--c) 28.5% 42.8%, transparent 42.8% 57.1%, var(--c) 57.1% 71.4%, transparent 71.4% 85.7%, var(--c) 85.7% 100%),
                linear-gradient(-45deg, var(--c) 0% 14.2%, transparent 14.2% 28.5%, var(--c) 28.5% 42.8%, transparent 42.8% 57.1%, var(--c) 57.1% 71.4%, transparent 71.4% 85.7%, var(--c) 85.7% 100%),
                none, none, none, none, none, none,
                linear-gradient(45deg, var(--c) 0% 14.2%, transparent 14.2% 28.5%, var(--c) 28.5% 42.8%, transparent 42.8% 57.1%, var(--c) 57.1% 71.4%, transparent 71.4% 85.7%, var(--c) 85.7% 100%),
                linear-gradient(-45deg, var(--c) 0% 14.2%, transparent 14.2% 28.5%, var(--c) 28.5% 42.8%, transparent 42.8% 57.1%, var(--c) 57.1% 71.4%, transparent 71.4% 85.7%, var(--c) 85.7% 100%);
            }
        }

        @keyframes weee {
            to { background-position-y: 800px, 800px, 1040px, 1040px, 1360px, 1360px, 1040px, 1040px; }
        }

        .checkout-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        /* Sidebar with Batik Animation */
        .sidebar {
            background: var(--primary);
            color: white;
            padding: 40px;
            border-right: none;
            position: relative;
            overflow: hidden;
        }

        .batik-bg-sidebar {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            --l: #00A550; 
            --b: #008040; /* Darker Green for Sidebar BG */
            --c: #ffffff33; /* Transparent White for pattern */
            background: var(--b);
        }
        
        /* Reuse animation keyframes (unchanged) */
        .batik-bg-sidebar::after, .batik-bg-sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 3000px; 
            animation: weee 60s linear infinite; 
            background-repeat: repeat-y;
            background-size: 50px 20px, 50px 20px, 50px 80px, 50px 80px, 50px 80px, 50px 80px, 50px 80px, 50px 80px;
            background-position-x: 4px, 54px, 108px, 158px, 212px, 262px, 316px, 366px, 420px, 470px, 524px, 574px, 628px, 678px, 732px, 782px, 836px, 886px, 940px, 990px, 1044px, 1094px, 1148px, 1198px, 1252px, 1302px, 1356px, 1406px, 1460px, 1510px, 1564px, 1614px, 1668px, 1718px, 1772px, 1822px, 1876px, 1926px, 1980px, 2030px, 2084px, 2134px, 2188px, 2238px, 2292px, 2342px, 2396px, 2446px, 2500px, 2550px;
        }
        .batik-bg-sidebar::after {
            animation: after-anim-opacity 5s linear infinite, weee 20s linear infinite;
            background-image: none, none,
                linear-gradient(45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%),
                linear-gradient(-45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%),
                none, none,
                linear-gradient(45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%),
                linear-gradient(-45deg, var(--c) 0% 30.7%, transparent 30.7% 61.5%, var(--c) 61.5% 92.3%, transparent 92.3%);
        }
        .batik-bg-sidebar::before {
            animation: before-anim-opacity 5s linear infinite, before-anim-bg 5s step-start infinite, weee 20s linear infinite;
        }

        .sidebar-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo { 
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            color: white !important;
            font-size: 1.25rem;
            margin-bottom: 40px; /* Spacing from summary card */
            text-decoration: none !important;
        }
        
        .summary-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .summary-card .text-muted { color: rgba(255, 255, 255, 0.7) !important; }
        .summary-card .fw-bold.text-success { color: #86efac !important; } /* Light green */
        .summary-card .fw-bold.text-primary { color: white !important; }
        
        .benefits-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 24px;
            border-radius: 16px;
        }
        .benefits-card li { display: flex; gap: 12px; margin-bottom: 12px; }
        .benefits-card i { color: #86efac; }
        
        .trust-badge { display: none; } /* Hide redundant badge if space is tight or style conflict */



        /* Form (Right) */
        .main-content {
            padding: 50px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 165, 80, 0.1);
        }

        /* Amount Selector */
        .amount-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .amount-radio {
            display: none;
        }

        .amount-label {
            display: block;
            text-align: center;
            padding: 12px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .amount-radio:checked + .amount-label {
            border-color: var(--primary);
            background: #ECFDF5;
            color: var(--primary);
            box-shadow: 0 4px 6px -1px rgba(0, 165, 80, 0.1);
        }

        .amount-input-group {
            position: relative;
        }
        .amount-input-group .currency {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 700;
            color: var(--text-muted);
        }
        .amount-input-group input {
            padding-left: 45px;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .btn-pay {
            background: var(--primary);
            color: white;
            width: 100%;
            padding: 16px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            transition: all 0.2s;
            margin-top: 20px;
        }

        .btn-pay:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .checkout-container {
                flex-direction: column-reverse;
                max-width: 600px;
            }
            .sidebar { border-right: none; border-top: 1px solid var(--border-color); }
        }
    </style>
</head>
<body>
    <!-- <div class="batik-bg"></div> Removed global bg -->

<div class="container">
    <div class="checkout-container d-lg-flex">
        
        <!-- LEFT SIDEBAR: Summary & Trust -->
        <div class="sidebar col-lg-4">
            <!-- Animated Background -->
            <div class="batik-bg-sidebar"></div>
            
            <div class="sidebar-content">
                <a href="{{ route('landing') }}" class="brand-logo">
                    <!-- Logo with white bg for visibility -->
                    <img src="{{ asset('assets/images/logo_yayasan.png') }}" height="40" alt="Logo" class="bg-white rounded-circle p-1"> 
                    <span>ASSHOLIHIN</span>
                </a>

                <div class="summary-card">
                    <h6 class="fw-bold text-uppercase small mb-3" style="color: rgba(255,255,255,0.8);">Ringkasan Donasi</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50">Program</span>
                        <span class="fw-bold">Donasi Umum</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50">Biaya Admin</span>
                        <span class="fw-bold text-success">Rp 0</span>
                    </div>
                    <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                    <div class="d-flex flex-column align-items-end">
                        <span class="fw-bold fs-6 mb-1">Total Donasi</span>
                        <span class="fw-bold fs-4 text-primary text-break text-end lh-1" id="summaryTotal">Rp 0</span>
                    </div>
                </div>

                <div class="benefits-card mt-4">
                    <h6 class="fw-bold mb-3">Mengapa Donasi di sini?</h6>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex gap-3">
                            <i class="fas fa-check-circle mt-1"></i>
                            <span class="small text-white-50">Transparansi 100% dengan laporan publik real-time.</span>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="fas fa-check-circle mt-1"></i>
                            <span class="small text-white-50">Amanah & diawasi oleh Dinas Sosial.</span>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="fas fa-check-circle mt-1"></i>
                            <span class="small text-white-50">Pembayaran aman terenkripsi (SSL).</span>
                        </li>
                    </ul>
                </div>
                
                <div class="mt-4 text-center">
                     <a href="{{ route('landing') }}" class="text-decoration-none small fw-bold text-white-50 hover-white">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT MAIN: Input Form -->
        <div class="main-content col-lg-8">
            <h2 class="fw-bold mb-1">Mulai Berbagi</h2>
            <p class="text-muted mb-5">Lengkapi data di bawah ini untuk melanjutkan.</p>

            @if(session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            <form action="{{ route('public.donasi.store') }}" method="POST" id="donationForm">
                @csrf
                
                <!-- Amount Section -->
                <div class="mb-5">
                    <label class="form-label mb-3">Nominal Donasi</label>
                    <div class="amount-grid">
                        <label>
                            <input type="radio" name="preset_amount" class="amount-radio" value="50000" onclick="updateAmount(50000)">
                            <span class="amount-label">Rp 50rb</span>
                        </label>
                        <label>
                            <input type="radio" name="preset_amount" class="amount-radio" value="100000" onclick="updateAmount(100000)">
                            <span class="amount-label">Rp 100rb</span>
                        </label>
                        <label>
                            <input type="radio" name="preset_amount" class="amount-radio" value="250000" onclick="updateAmount(250000)">
                            <span class="amount-label">Rp 250rb</span>
                        </label>
                        <label>
                            <input type="radio" name="preset_amount" class="amount-radio" value="500000" onclick="updateAmount(500000)">
                            <span class="amount-label">Rp 500rb</span>
                        </label>
                        <label>
                            <input type="radio" name="preset_amount" class="amount-radio" value="1000000" onclick="updateAmount(1000000)">
                            <span class="amount-label">Rp 1 Juta</span>
                        </label>
                        <label>
                            <input type="radio" name="preset_amount" class="amount-radio" value="custom" id="customRadio" onclick="focusCustom()">
                            <span class="amount-label fw-bold text-primary">Lainnya</span>
                        </label>
                    </div>

                    <div class="amount-input-group">
                        <span class="currency">Rp</span>
                        <input type="number" name="jumlah" id="customAmount" class="form-control" placeholder="Masukkan nominal lainnya..." min="10000" max="100000000" required oninput="syncCustom(this.value)">
                    </div>
                    <div class="form-text text-muted ps-2">Minimal donasi Rp 10.000, Maksimal Rp 100.000.000</div>
                </div>

                <!-- Donor Info -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Anda / Hamba Allah" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email (Opsional)</label>
                        <input type="email" name="email" class="form-control" placeholder="Untuk kirim bukti donasi">
                    </div>
                </div>

                <!-- Payment Method Section -->
                <div class="mb-5">
                    <label class="form-label mb-3">Metode Pembayaran</label>
                    
                    @if(isset($channels) && count($channels) > 0)
                        <div class="payment-grid">
                            @foreach($channels as $channel)
                                <label class="payment-card">
                                    <input type="radio" name="method" value="{{ $channel['code'] }}" class="payment-radio" required>
                                    <div class="payment-card-content">
                                        <img src="{{ $channel['icon_url'] }}" alt="{{ $channel['name'] }}" class="payment-icon">
                                        <div class="payment-info">
                                            <span class="payment-name">{{ $channel['name'] }}</span>
                                            <span class="payment-group small text-muted">{{ $channel['group_name'] }}</span>
                                        </div>
                                        <i class="fas fa-check-circle payment-check"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Gagal memuat metode pembayaran. Pastikan API Key Tripay benar.
                        </div>
                    @endif
                </div>

                <style>
                    .payment-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                        gap: 15px;
                    }
                    .payment-card {
                        cursor: pointer;
                        position: relative;
                    }
                    .payment-radio {
                        position: absolute;
                        opacity: 0;
                    }
                    .payment-card-content {
                        border: 1px solid var(--border-color);
                        border-radius: 12px;
                        padding: 15px;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        transition: all 0.2s;
                        background: white;
                        height: 100%;
                    }
                    .payment-icon {
                        width: 40px;
                        height: auto;
                        object-fit: contain;
                    }
                    .payment-info {
                        display: flex;
                        flex-direction: column;
                        flex: 1;
                        line-height: 1.2;
                    }
                    .payment-name {
                        font-weight: 600;
                        font-size: 0.9rem;
                        color: var(--text-main);
                    }
                    .payment-group {
                        font-size: 0.75rem;
                    }
                    .payment-check {
                        color: var(--primary);
                        opacity: 0;
                        transition: all 0.2s;
                    }
                    
                    /* Selected State */
                    .payment-radio:checked + .payment-card-content {
                        border-color: var(--primary);
                        background: #ECFDF5;
                        box-shadow: 0 4px 10px rgba(0, 165, 80, 0.1);
                    }
                    .payment-radio:checked + .payment-card-content .payment-check {
                        opacity: 1;
                    }
                    .payment-card:hover .payment-card-content {
                        border-color: var(--primary);
                    }
                </style>

                <div class="mb-5">
                    <label class="form-label">Doa / Pesan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Tuliskan doa untuk anak-anak panti..."></textarea>
                </div>

                <button type="submit" class="btn btn-pay shadow-lg">
                    Lanjut Pembayaran <span id="btnAmount"></span>
                </button>
                
                <p class="text-center text-muted small mt-3 mb-0">
                    <i class="fas fa-lock me-1"></i> Data Anda diamankan dengan enkripsi SSL 256-bit.
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    function updateAmount(val) {
        // Update Custom Input
        const customInput = document.getElementById('customAmount');
        const summaryTotal = document.getElementById('summaryTotal');
        const btnAmount = document.getElementById('btnAmount');
        
        customInput.value = val;
        
        // Format Currency
        const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
        
        summaryTotal.innerText = formatted;
        btnAmount.innerText = '• ' + formatted;

        // Uncheck custom radio if specific amount selected
        // Actually keep radio logic simple: clicking preset fills input. Inputting custom clears preset radios visually if needed, but here we bind them.
    }

    function syncCustom(val) {
        const summaryTotal = document.getElementById('summaryTotal');
        const btnAmount = document.getElementById('btnAmount');
        const customInput = document.getElementById('customAmount');
        
        // Enforce Max Limit
        let numVal = parseFloat(val);
        if(numVal > 100000000) {
            numVal = 100000000;
            customInput.value = numVal;
            val = numVal; // Update val for formatting below
        }

        // Uncheck all presets
        document.querySelectorAll('input[name="preset_amount"]').forEach(el => el.checked = false);
        // Check "Lainnya"
        document.getElementById('customRadio').checked = true;

        if(val) {
            const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
            summaryTotal.innerText = formatted;
            btnAmount.innerText = '• ' + formatted;
        } else {
            summaryTotal.innerText = 'Rp 0';
            btnAmount.innerText = '';
        }
    }
    
    function focusCustom() {
        document.getElementById('customAmount').focus();
        document.getElementById('customAmount').value = '';
    }
</script>

</body>
</html>
