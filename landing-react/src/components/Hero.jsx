import { motion } from 'framer-motion';

export default function Hero({ onDonateClick }) {
    return (
        <section id="hero" className="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
            {/* Background Image */}
            <div className="absolute inset-0 z-0">
                <img
                    src={`${import.meta.env.BASE_URL}img/hero-bg.jpg`}
                    alt="Hero Background"
                    className="w-full h-full object-cover object-center"
                />
                <div className="absolute inset-0 bg-gradient-to-br from-primary-dark/90 via-primary/80 to-transparent"></div>
            </div>

            {/* Pattern Overlay */}
            <div className="absolute inset-0 opacity-10 bg-no-repeat bg-cover" style={{ backgroundImage: `url(${import.meta.env.BASE_URL}img/pattern.svg)` }}></div>

            <div className="container mx-auto px-6 relative z-10 text-center text-white">
                <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                >
                    <span className="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sm font-medium mb-6">
                        ✨ Berbagi Kebahagiaan, Menuai Berkah
                    </span>
                    <h1 className="text-5xl md:text-7xl font-heading font-bold mb-6 leading-tight">
                        Wujudkan Harapan <br />
                        <span className="text-secondary-light">Mereka Bersama Kami</span>
                    </h1>
                    <p className="text-lg md:text-xl text-white/90 mb-10 max-w-2xl mx-auto leading-relaxed">
                        Setiap uluran tangan Anda adalah sinar harapan bagi masa depan anak-anak asuh kami. Mari berbagi kasih dan kepedulian.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <button
                            onClick={onDonateClick}
                            className="btn-primary shadow-glow hover:shadow-secondary/50 transform hover:-translate-y-1 transition-all"
                        >
                            Mulai Berdonasi
                        </button>
                        <a href="#about" className="px-6 py-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 font-medium hover:bg-white/20 transition-all">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </motion.div>
            </div>

            {/* Scroll Indicator */}
            <motion.div
                className="absolute bottom-10 left-1/2 transform -translate-x-1/2"
                animate={{ y: [0, 10, 0] }}
                transition={{ repeat: Infinity, duration: 2 }}
            >
                <div className="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center p-1">
                    <div className="w-1 h-2 bg-white rounded-full"></div>
                </div>
            </motion.div>
        </section>
    );
}
