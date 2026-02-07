<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suara Donatur - Yayasan Panti Asuhan Assholihin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #00A550;
            --primary-dark: #008040;
            --secondary: #F4C430;
            --bg-body: #ECF5E1;
            --text-main: #143322;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #4B5563;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 1rem 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
        }
        .navbar-brand {
            font-weight: 800;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.4rem;
        }
        .navbar-brand img { height: 50px; width: auto; }
        .nav-link { font-weight: 600; color: var(--text-main) !important; }

        /* Hero */
        .page-hero {
            padding: 120px 0 60px;
            text-align: center;
            background: linear-gradient(180deg, rgba(236,245,225,0) 0%, rgba(0,165,80,0.05) 100%);
        }

        /* Cards */
        .forum-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }
        .forum-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,165,80,0.1);
            border-color: var(--primary);
        }

        .quote-icon {
            color: var(--primary);
            opacity: 0.2;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        /* Pagination */
        .pagination .page-link {
            border: none;
            color: var(--text-main);
            margin: 0 5px;
            border-radius: 8px;
        }
        .pagination .active .page-link {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary-modern {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-primary-modern:hover {
            background-color: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,165,80,0.3);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <img src="{{ asset('assets/images/logo_yayasan.png') }}" alt="Logo"> 
                <span>ASSHOLIHIN</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('landing') }}" class="btn btn-outline-success rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="page-hero">
        <div class="container">
            <h6 class="text-primary fw-bold text-uppercase ls-1 mb-2">Forum Silaturahmi</h6>
            <h1 class="fw-bold text-main display-5 mb-3">Suara Donatur</h1>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Ruang bagi para donatur untuk berbagi doa, harapan, dan pesan kebaikan untuk anak-anak panti asuhan.
            </p>
        </div>
    </section>

    <!-- Submission Form Section -->
    <section class="mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 text-center">Kirim Doa & Harapan</h5>
                            
                            <!-- Alert removed, replaced with SweetAlert -->

                            <form action="{{ route('public.donasi.pesan.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama" class="form-label small fw-bold text-muted">Nama Anda</label>
                                    <input type="text" class="form-control bg-light border-0 py-2" id="nama" name="nama" placeholder="Tulis nama Anda..." required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-bold text-muted">Email (Opsional)</label>
                                    <input type="email" class="form-control bg-light border-0 py-2" id="email" name="email" placeholder="contoh@email.com">
                                </div>
                                <div class="mb-3">
                                    <label for="pesan" class="form-label small fw-bold text-muted">Pesan / Doa</label>
                                    <textarea class="form-control bg-light border-0 py-2" id="pesan" name="pesan" rows="3" placeholder="Tulis pesan kebaikan di sini..." required></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary-modern py-2 fw-bold">
                                        <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="pb-5">
        <div class="container">
            @if($testimonials->count() > 0)
                <div class="row g-4 mb-5">
                    @foreach($testimonials as $testi)
                    <div class="col-lg-4 col-md-6">
                        <div class="forum-card d-flex flex-column">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <p class="fs-5 fst-italic text-dark mb-4 flex-grow-1">"{{ $testi->deskripsi }}"</p>
                            
                            <div class="d-flex align-items-center mt-auto border-top pt-3">
                                <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-primary">{{ $testi->nama }}</h6>
                                    <small class="text-muted" style="font-size: 0.8rem;">
                                        {{ $testi->created_at ? $testi->created_at->diffForHumans() : 'Donatur' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $testimonials->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/green/surveillance.svg" alt="Empty" style="width: 200px; opacity: 0.7;">
                    <h4 class="mt-4 text-muted">Belum ada pesan donatur.</h4>
                    <p>Jadilah yang pertama memberikan doa dan dukungan!</p>
                    <a href="{{ route('public.donasi.form') }}" class="btn btn-primary mt-3 px-4 py-2 rounded-pill shadow-sm">
                        Berdonasi Sekarang
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container text-center">
            <small class="text-muted">&copy; {{ date('Y') }} Yayasan Panti Asuhan Assholihin. All rights reserved.</small>
        </div>
    </footer>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#00A550',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    </script>

</body>
</html>
