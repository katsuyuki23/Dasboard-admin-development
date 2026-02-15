import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import axios from 'axios';
import GalleryModal from './GalleryModal';

export default function Gallery() {
    const [gallery, setGallery] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedItem, setSelectedItem] = useState(null);

    useEffect(() => {
        const fetchGallery = async () => {
            try {
                // Assuming API endpoint
                const baseUrl = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api';
                const response = await axios.get(`${baseUrl}/landing/gallery`);
                // API returns direct array of photos
                if (Array.isArray(response.data)) {
                    setGallery(response.data);
                }
            } catch (err) {
                console.error("Error loading gallery:", err);
            } finally {
                setLoading(false);
            }
        };

        fetchGallery();
    }, []);

    const openModal = (item) => setSelectedItem(item);
    const closeModal = () => setSelectedItem(null);

    return (
        <section id="gallery" className="py-20 bg-white">
            <div className="container mx-auto px-6">
                <div className="text-center mb-16">
                    <span className="text-secondary font-medium tracking-wider uppercase text-sm">Galeri Kegiatan</span>
                    <h2 className="text-3xl md:text-4xl font-heading font-bold mt-2 text-slate-900">Momen Kebersamaan</h2>
                    <div className="w-20 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-[200px] md:auto-rows-[250px]">
                    {loading ? (
                        [...Array(5)].map((_, i) => (
                            <div key={i} className={`animate-pulse bg-slate-200 rounded-2xl w-full h-full ${i === 0 ? 'md:col-span-2 md:row-span-2' : ''}`}></div>
                        ))
                    ) : (
                        gallery.map((item, index) => (
                            <motion.div
                                key={item.id}
                                onClick={() => openModal(item)}
                                initial={{ opacity: 0, scale: 0.9 }}
                                whileInView={{ opacity: 1, scale: 1 }}
                                transition={{ duration: 0.5, delay: index * 0.1 }}
                                viewport={{ once: true }}
                                className={`rounded-2xl overflow-hidden relative group cursor-pointer shadow-md bg-slate-100 ${index === 0 ? 'md:col-span-2 md:row-span-2' : ''
                                    }`}
                            >
                                <img
                                    src={item.image_url}
                                    alt={item.title}
                                    className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                    <h3 className="text-white font-bold text-lg leading-tight mb-1">{item.title}</h3>
                                    <p className="text-white/80 text-xs line-clamp-2">{item.description}</p>
                                </div>
                            </motion.div>
                        ))
                    )}
                </div>

                {/* Modal for full view */}
                <GalleryModal
                    isOpen={!!selectedItem}
                    onClose={closeModal}
                    item={selectedItem}
                />
            </div>
        </section>
    );
}
