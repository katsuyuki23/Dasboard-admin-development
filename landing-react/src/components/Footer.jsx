export default function Footer() {
    return (
        <footer className="bg-primary-dark text-white py-12 border-t border-white/10">
            <div className="container mx-auto px-6">
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div className="col-span-1 md:col-span-2">
                        <div className="flex items-center gap-3 mb-4">
                            <div className="bg-white/10 p-2 rounded-lg">
                                <img src={`${import.meta.env.BASE_URL}img/logo.png`} alt="Logo" className="h-8 w-auto brightness-0 invert" />
                            </div>
                            <span className="font-heading font-bold text-xl">Panti Asuhan</span>
                        </div>
                        <p className="text-white/60 max-w-sm">
                            Membangun masa depan yang lebih cerah bagi anak-anak yatim piatu dan dhuafa.
                        </p>
                    </div>

                    <div>
                        <h4 className="font-bold mb-4 text-secondary-light">Navigasi</h4>
                        <ul className="space-y-2 text-sm text-white/70">
                            <li><a href="#hero" className="hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="#about" className="hover:text-white transition-colors">Tentang Kami</a></li>
                            <li><a href="#gallery" className="hover:text-white transition-colors">Galeri</a></li>
                            <li><a href="#donate" className="hover:text-white transition-colors">Donasi</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="font-bold mb-4 text-secondary-light">Kontak</h4>
                        <ul className="space-y-2 text-sm text-white/70">
                            <li>F68R+W8X, Candi Mulyo, Kec. Jombang</li>
                            <li>Kabupaten Jombang, Jawa Timur 61413</li>
                            <li>+62 812 3456 7890</li>
                            <li>info@pantiasuhan.org</li>
                        </ul>
                    </div>
                </div>

                <div className="pt-8 border-t border-white/5 text-center text-sm text-white/40">
                    &copy; {new Date().getFullYear()} Panti Asuhan. All rights reserved.
                </div>
            </div>
        </footer>
    );
}
