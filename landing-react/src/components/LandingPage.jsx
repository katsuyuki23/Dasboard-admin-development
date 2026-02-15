import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Navbar from './Navbar';
import Hero from './Hero';
import Stats from './Stats';
import Gallery from './Gallery';
import Footer from './Footer';
import DonationModal from './DonationModal';

import RecentDonations from './RecentDonations';
import Contact from './Contact';

function LandingPage() {
    const navigate = useNavigate();
    const [isModalOpen, setIsModalOpen] = useState(false);

    return (
        <div className="font-sans text-slate-800 bg-slate-50 min-h-screen">
            <Navbar onDonateClick={() => setIsModalOpen(true)} />

            <main>
                <Hero onDonateClick={() => setIsModalOpen(true)} />

                {/* About / Intro Section (Simple placeholder matching existing design) */}
                <section id="about" className="py-20 bg-white">
                    <div className="container mx-auto px-6 text-center max-w-3xl">
                        <span className="text-secondary font-medium tracking-wider uppercase text-sm">Tentang Kami</span>
                        <h2 className="text-3xl md:text-4xl font-heading font-bold mt-2 mb-6 text-slate-900">Mewujudkan Masa Depan</h2>
                        <p className="text-lg text-slate-600 leading-relaxed">
                            Kami berdedikasi untuk memberikan pengasuhan, pendidikan, dan kasih sayang kepada anak-anak yatim piatu dan dhuafa. Bersama Anda, kita bangun jembatan menuju masa depan yang gemilang bagi mereka.
                        </p>
                    </div>
                </section>

                <Stats />

                <Gallery />

                {/* Recent Donations Section (Target for Navbar Link) */}
                <section id="donate" className="py-20 bg-slate-50">
                    <div className="container mx-auto px-6">
                        <div className="text-center mb-12">
                            <span className="text-secondary font-medium tracking-wider uppercase text-sm">Transparansi</span>
                            <h2 className="text-3xl md:text-4xl font-heading font-bold mt-2 text-slate-900">Para Donatur</h2>
                        </div>
                        <div className="max-w-7xl mx-auto">
                            <RecentDonations />
                        </div>
                    </div>
                </section>

                <Contact />

                {/* CTA / Donation Section */}
                <section className="py-24 relative overflow-hidden flex items-center justify-center bg-primary-dark text-white">
                    {/* Background Effects */}
                    <div className="absolute inset-0 z-0">
                        <div className="absolute top-0 left-1/4 w-96 h-96 bg-primary/30 rounded-full blur-[100px]"></div>
                        <div className="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary/20 rounded-full blur-[100px]"></div>
                        <div className="absolute inset-0 bg-no-repeat bg-cover opacity-10" style={{ backgroundImage: `url(${import.meta.env.BASE_URL}img/pattern.svg)` }}></div>
                    </div>

                    <div className="container mx-auto px-6 relative z-10 text-center">
                        <h2 className="text-4xl md:text-5xl font-heading font-bold mb-6">Siap Berbagi Kebahagiaan?</h2>
                        <p className="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
                            Setiap rupiah yang Anda donasikan akan sangat berarti bagi pendidikan dan kebutuhan sehari-hari anak-anak asuh kami.
                        </p>
                        <button
                            onClick={() => setIsModalOpen(true)}
                            className="bg-secondary text-white px-8 py-4 rounded-full font-bold text-lg shadow-glow hover:shadow-lg hover:bg-yellow-600 transform hover:-translate-y-1 transition-all"
                        >
                            Donasi Sekarang
                        </button>
                    </div>
                </section>
            </main>

            <Footer />

            <DonationModal isOpen={isModalOpen} setIsOpen={setIsModalOpen} />
        </div>
    );
}

export default LandingPage;
