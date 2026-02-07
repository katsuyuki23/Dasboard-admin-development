@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 text-white overflow-hidden shadow-lg" style="border-radius: 25px; background: linear-gradient(135deg, rgba(14, 77, 43, 0.9), rgba(10, 61, 32, 0.95)), url('{{ asset('assets/images/bg_pattern.jpg') }}'); background-size: cover; background-position: center;">
                <div class="card-body p-5 position-relative">
                    <div class="position-relative z-index-1">
                        <h1 class="display-5 fw-bold mb-2">Selamat Datang, Admin! 👋</h1>
                        <p class="lead opacity-75 mb-0" style="max-width: 600px;">
                            Ini adalah dashboard Panti Asuhan Assholihin. Pantau aktivitas anak asuh, donasi, dan pengeluaran dalam satu tampilan modern.
                        </p>
                    </div>
                    <!-- Decorative element (optional, removing shapes to let pattern show) -->
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards with Trend Indicators -->
    <div class="row mb-5">
        <!-- Anak Asuh -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-header">
                    @if($anakChange > 0)
                        <span class="trend-badge trend-up">
                            <i class="fas fa-arrow-up"></i> {{ number_format(abs($anakChange), 1) }}%
                        </span>
                    @elseif($anakChange < 0)
                        <span class="trend-badge trend-down">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($anakChange), 1) }}%
                        </span>
                    @else
                        <span class="trend-badge trend-neutral">
                            <i class="fas fa-minus"></i> 0%
                        </span>
                    @endif
                    <i class="fas fa-ellipsis-h text-muted"></i>
                </div>
                <div class="stat-body">
                    <h2 class="stat-value">{{ number_format($totalAnak) }}</h2>
                    <p class="stat-label">Anak Asuh</p>
                </div>
            </div>
        </div>

        <!-- Donasi -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-header">
                    @if($saldoChange > 0)
                        <span class="trend-badge trend-up">
                            <i class="fas fa-arrow-up"></i> {{ number_format(abs($saldoChange), 1) }}%
                        </span>
                    @elseif($saldoChange < 0)
                        <span class="trend-badge trend-down">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($saldoChange), 1) }}%
                        </span>
                    @else
                        <span class="trend-badge trend-neutral">
                            <i class="fas fa-minus"></i> 0%
                        </span>
                    @endif
                    <i class="fas fa-ellipsis-h text-muted"></i>
                </div>
                <div class="stat-body">
                    <h2 class="stat-value" id="totalSaldoDisplay">{{ number_format($totalSaldo, 0, ',', '.') }}</h2>
                    <p class="stat-label">Total Keuangan</p>
                </div>
            </div>
        </div>

        <!-- Pengurus -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-header">
                    @if($pengurusChange > 0)
                        <span class="trend-badge trend-up">
                            <i class="fas fa-arrow-up"></i> {{ number_format(abs($pengurusChange), 1) }}%
                        </span>
                    @elseif($pengurusChange < 0)
                        <span class="trend-badge trend-down">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($pengurusChange), 1) }}%
                        </span>
                    @else
                        <span class="trend-badge trend-neutral">
                            <i class="fas fa-minus"></i> 0%
                        </span>
                    @endif
                    <i class="fas fa-ellipsis-h text-muted"></i>
                </div>
                <div class="stat-body">
                    <h2 class="stat-value">{{ number_format($totalPengurus) }}</h2>
                    <p class="stat-label">Total Pengurus</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('anak.create') }}" class="btn btn-outline-primary btn-block h-100 d-flex flex-column align-items-center justify-content-center py-4 border-0 bg-light-primary text-primary quick-action-btn">
                                <i class="fas fa-plus-circle fa-2x mb-3"></i>
                                <span class="font-weight-bold">Tambah Anak Asuh</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('donasi.create') }}" class="btn btn-outline-success btn-block h-100 d-flex flex-column align-items-center justify-content-center py-4 border-0 bg-light-success text-success quick-action-btn">
                                <i class="fas fa-hand-holding-usd fa-2x mb-3"></i>
                                <span class="font-weight-bold">Catat Donasi</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pengeluaran.create') }}" class="btn btn-outline-danger btn-block h-100 d-flex flex-column align-items-center justify-content-center py-4 border-0 bg-light-warning text-warning quick-action-btn"> <!-- Changed to warning for aesthetics -->
                                <i class="fas fa-money-bill-wave fa-2x mb-3"></i>
                                <span class="font-weight-bold">Catat Pengeluaran</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('gallery.create') }}" class="btn btn-outline-info btn-block h-100 d-flex flex-column align-items-center justify-content-center py-4 border-0 bg-light-info text-info quick-action-btn">
                                <i class="fas fa-camera fa-2x mb-3"></i>
                                <span class="font-weight-bold">Upload Foto</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance & Expenses Row -->
    <div class="row">
        <!-- Wave Chart (Balance Assessment) -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold text-dark">Data Statistik</h5>
                    <div class="dropdown">
                        <select id="yearFilter" class="form-select form-select-sm border-0 bg-light fw-bold" style="width: 100px; cursor: pointer;">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h2 class="mb-0 fw-bold me-3" id="chartTotalSaldoDisplay">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h2>
                        <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill">
                            <i class="fas fa-wallet me-1"></i> Total Saldo
                        </span>
                    </div>
                    <div class="chart-container" style="position: relative; height: 350px;">
                        <canvas id="donasiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Bar Chart (Side) -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 24px;">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">Statistik Donatur</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div style="height: 300px; width: 100%;">
                        <canvas id="expenseChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-muted small">Jumlah Donatur Aktif per Bulan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Recent Transactions Info -->
    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 24px;">
        <div class="card-header border-0 bg-white py-3" style="border-radius: 24px 24px 0 0;">
            <h6 class="m-0 fw-bold">Transaksi Terakhir</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">Tanggal</th>
                            <th class="border-0">Kategori</th>
                            <th class="border-0">Keterangan</th>
                            <th class="border-0">Jenis</th>
                            <th class="border-0 rounded-end text-end">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransaksi as $t)
                        <tr>
                            <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $categoryIcons = [
                                        'PERMAKANAN' => 'fa-utensils',
                                        'OPERASIONAL' => 'fa-cog',
                                        'PENDIDIKAN' => 'fa-graduation-cap',
                                        'SARANA_PRASARANA' => 'fa-building'
                                    ];
                                    $iconClass = $categoryIcons[$t->kategori?->nama_kategori ?? 'UNKNOWN'] ?? 'fa-tag';
                                @endphp
                                <i class="fas {{ $iconClass }} me-2 text-muted"></i>
                                {{ ucwords(strtolower(str_replace('_', ' ', $t->kategori?->nama_kategori ?? 'Tidak Berkategori'))) }}
                            </td>
                            <td>{{ $t->keterangan ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $t->jenis_transaksi == 'MASUK' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $t->jenis_transaksi }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initial Data from Controller
        const initialDonasi = {!! json_encode($chartDonasi) !!};
        const initialPengeluaran = {!! json_encode($chartPengeluaran) !!};
        const initialDonatur = {!! json_encode($chartDonatur) !!};
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'];

        // --- Wave Chart Config (Multi-Wave Style) ---
        const ctxDonasi = document.getElementById('donasiChart').getContext('2d');
        

        // Gradients
        let gradientGreen = ctxDonasi.createLinearGradient(0, 0, 0, 300);
        gradientGreen.addColorStop(0, 'rgba(40, 167, 69, 0.2)');
        gradientGreen.addColorStop(1, 'rgba(40, 167, 69, 0.0)');

        let gradientRed = ctxDonasi.createLinearGradient(0, 0, 0, 300);
        gradientRed.addColorStop(0, 'rgba(220, 53, 69, 0.2)');
        gradientRed.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

        const donasiChart = new Chart(ctxDonasi, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan (Income)',
                        data: initialDonasi,
                        borderColor: '#28a745', // Green
                        backgroundColor: gradientGreen,
                        borderWidth: 3,
                        tension: 0.5, // Smooth Wave
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Pengeluaran (Expense)',
                        data: initialPengeluaran,
                        borderColor: '#dc3545', // Red
                        backgroundColor: gradientRed,
                        borderWidth: 3,
                        tension: 0.5, // Smooth Wave
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', align: 'end' },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#000',
                        bodyColor: '#333',
                        borderColor: '#f0f0f0',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { borderDash: [5, 5] }, ticks: { display: false } }
                }
            }
        });

        // --- Vertical Bar Chart Config ---
        const ctxExpense = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(ctxExpense, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Donatur Aktif',
                    data: initialDonatur,
                    backgroundColor: labels.map((_, i) => i % 2 === 0 ? '#4e73df' : '#e6e6e6'), // Blue Alternating
                    borderRadius: 10,
                    barThickness: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { display: false }
                }
            }
        });

        // --- AJAX Year Filter ---
        document.getElementById('yearFilter').addEventListener('change', function() {
            const year = this.value;
            fetch(`{{ route('dashboard') }}?year=${year}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                // Update Donasi Chart (Dataset 0)
                donasiChart.data.datasets[0].data = data.donasi;
                
                // Update Pengeluaran Chart (Dataset 1)
                if (donasiChart.data.datasets[1]) {
                    donasiChart.data.datasets[1].data = data.pengeluaran;
                }
                
                donasiChart.update();

                // Update Donor Chart (Previously Expense Chart)
                expenseChart.data.datasets[0].data = data.donatur;
                expenseChart.update();

                // Update Total Saldo Text (Small Card)
                if(data.total_saldo) {
                    const smallCard = document.getElementById('totalSaldoDisplay');
                    if(smallCard) smallCard.innerText = data.total_saldo;
                    
                    const bigCard = document.getElementById('chartTotalSaldoDisplay');
                    if(bigCard) bigCard.innerText = "Rp " + data.total_saldo;
                }
            });
        });

    </script>
@endsection
