import { useState } from 'react';
import { motion } from 'framer-motion';
import { MapPinIcon, PhoneIcon, EnvelopeIcon, PaperAirplaneIcon } from '@heroicons/react/24/outline';

export default function Contact() {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        message: ''
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // In a real app, this would send data to a backend
        console.log('Form submitted:', formData);
        alert('Terima kasih! Pesan Anda telah kami terima.');
        setFormData({ name: '', email: '', message: '' });
    };

    return (
        <section id="contact" className="py-20 bg-white relative overflow-hidden">
            {/* Background Pattern */}
            <div className="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div className="absolute top-10 right-10 w-64 h-64 bg-secondary/5 rounded-full blur-3xl"></div>
                <div className="absolute bottom-10 left-10 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
            </div>

            <div className="container mx-auto px-6 relative z-10">
                <div className="text-center mb-16">
                    <span className="text-secondary font-medium tracking-wider uppercase text-sm">Hubungi Kami</span>
                    <h2 className="text-3xl md:text-4xl font-heading font-bold mt-2 text-slate-900">Tetap Terhubung</h2>
                    <div className="w-20 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
                    <p className="mt-4 text-slate-600 max-w-2xl mx-auto">
                        Punya pertanyaan atau ingin berkunjung? Jangan ragu untuk menghubungi kami. Tim kami siap membantu Anda.
                    </p>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                    {/* Contact Info */}
                    <motion.div
                        initial={{ opacity: 0, x: -30 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        viewport={{ once: true }}
                        transition={{ duration: 0.6 }}
                        className="space-y-8"
                    >
                        <div className="bg-slate-50 p-8 rounded-3xl border border-slate-100 shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <h3 className="text-2xl font-bold font-heading mb-6 text-slate-800">Informasi Kontak</h3>
                            <div className="space-y-6">
                                <div className="flex items-start gap-4">
                                    <div className="bg-primary/10 p-3 rounded-xl shrink-0">
                                        <MapPinIcon className="w-6 h-6 text-primary" />
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-slate-800 mb-1">Alamat</h4>
                                        <p className="text-slate-600 leading-relaxed">
                                            F68R+W8X, Candi Mulyo, Kec. Jombang,<br />
                                            Kabupaten Jombang, Jawa Timur 61413
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-start gap-4">
                                    <div className="bg-secondary/10 p-3 rounded-xl shrink-0">
                                        <PhoneIcon className="w-6 h-6 text-secondary" />
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-slate-800 mb-1">Telepon</h4>
                                        <p className="text-slate-600 hover:text-secondary transition-colors cursor-pointer">
                                            +62 812 3456 7890 (WhatsApp Available)
                                        </p>
                                        <p className="text-slate-600 text-sm mt-1">Senin - Minggu: 08.00 - 17.00</p>
                                    </div>
                                </div>

                                <div className="flex items-start gap-4">
                                    <div className="bg-primary/10 p-3 rounded-xl shrink-0">
                                        <EnvelopeIcon className="w-6 h-6 text-primary" />
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-slate-800 mb-1">Email</h4>
                                        <p className="text-slate-600 hover:text-primary transition-colors cursor-pointer">
                                            info@pantiasuhan.org
                                        </p>
                                        <p className="text-slate-600 hover:text-primary transition-colors cursor-pointer">
                                            support@pantiasuhan.org
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Map Embed (Placeholder) */}
                        <div className="h-64 rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-slate-100 relative group">
                            <iframe
                                src="https://maps.google.com/maps?q=F68R%2BW8X%2C%20Candi%20Mulyo%2C%20Kec.%20Jombang%2C%20Kabupaten%20Jombang%2C%20Jawa%20Timur%2061413&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                width="100%"
                                height="100%"
                                style={{ border: 0 }}
                                allowFullScreen=""
                                loading="lazy"
                                title="Map Location"
                                className="grayscale group-hover:grayscale-0 transition-all duration-500"
                            ></iframe>
                        </div>
                    </motion.div>

                    {/* Contact Form */}
                    <motion.div
                        initial={{ opacity: 0, x: 30 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        viewport={{ once: true }}
                        transition={{ duration: 0.6, delay: 0.2 }}
                        className="bg-white p-8 md:p-10 rounded-3xl shadow-xl border border-slate-100 relative"
                    >
                        <div className="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-bl-full -z-0"></div>

                        <h3 className="text-2xl font-bold font-heading mb-6 text-slate-800 relative z-10">Kirim Pesan</h3>
                        <form onSubmit={handleSubmit} className="space-y-5 relative z-10">
                            <div>
                                <label htmlFor="name" className="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    required
                                    className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-slate-400"
                                    placeholder="Masukkan nama Anda"
                                />
                            </div>

                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-slate-700 mb-2">Alamat Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleChange}
                                    required
                                    className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-slate-400"
                                    placeholder="contoh@email.com"
                                />
                            </div>

                            <div>
                                <label htmlFor="message" className="block text-sm font-medium text-slate-700 mb-2">Pesan</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    value={formData.message}
                                    onChange={handleChange}
                                    required
                                    rows="5"
                                    className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-slate-400 resize-none"
                                    placeholder="Tulis pesan Anda di sini..."
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                className="w-full btn-primary py-4 px-6 rounded-xl flex items-center justify-center gap-2 group hover:shadow-lg transition-all"
                            >
                                <span>Kirim Pesan</span>
                                <PaperAirplaneIcon className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                            </button>
                        </form>
                    </motion.div>
                </div>
            </div>
        </section>
    );
}
