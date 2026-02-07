<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Sistem Panti Asuhan</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        /* Tailwind CSS styles omitted for brevity */
        body { margin: 0; font-family: Figtree, sans-serif; }
        .min-h-screen { min-height: 100vh; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .text-center { text-align: center; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-10 { padding-top: 2.5rem; padding-bottom: 2.5rem; }
        .bg-white { background-color: white; }
        .rounded-lg { border-radius: 0.5rem; }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col items-center justify-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div style="max-width: 1200px; width: 100%; padding: 24px;">
            <header style="display: grid; grid-template-columns: repeat(2, 1fr); align-items: center; gap: 8px; padding: 40px 0;">
                <div style="display: flex; justify-content: center;">
                    <h1 style="font-size: 28px; font-weight: 600; color: white;">Sistem Panti Asuhan</h1>
                </div>
                @if (Route::has('login'))
                    <nav style="display: flex; justify-content: flex-end;">
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" style="padding: 8px 16px; color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; text-decoration: none; transition: all 0.3s;">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" style="padding: 8px 16px; color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; text-decoration: none; transition: all 0.3s;">
                                Login Admin
                            </a>
                        @endauth
                    </nav>
                @endif
            </header>

            <main style="margin-top: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    <a href="{{ route('public.donasi.form') }}" style="display: flex; flex-direction: column; gap: 24px; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-decoration: none; transition: all 0.3s;">
                        <div style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255, 45, 32, 0.1);">
                            <svg style="width: 32px; height: 32px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path fill="#FF2D20" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 600; color: #1a202c;">Donasi</h2>
                            <p style="margin-top: 16px; font-size: 14px; color: #4a5568;">
                                Berdonasi untuk mendukung panti asuhan kami. Setiap kontribusi Anda sangat berarti bagi anak-anak di panti.
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('public.donasi.forum') }}" style="display: flex; flex-direction: column; gap: 24px; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-decoration: none; transition: all 0.3s;">
                        <div style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255, 45, 32, 0.1);">
                            <svg style="width: 32px; height: 32px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path fill="#FF2D20" d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 600; color: #1a202c;">Suara Donatur</h2>
                            <p style="margin-top: 16px; font-size: 14px; color: #4a5568;">
                                Lihat pesan dan testimonial dari para donatur yang telah berkontribusi untuk panti asuhan.
                            </p>
                        </div>
                    </a>
                </div>
            </main>

            <footer style="padding: 64px 0; text-align: center; font-size: 14px; color: rgba(255,255,255,0.7);">
                Sistem Panti Asuhan &copy; {{ date('Y') }}
            </footer>
        </div>
    </div>
</body>
</html>
