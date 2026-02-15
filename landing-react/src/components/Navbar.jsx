import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Bars3Icon, XMarkIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';

export default function Navbar({ onDonateClick }) {
    const [isOpen, setIsOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const navLinks = [
        { name: 'Beranda', href: '#hero' },
        { name: 'Tentang', href: '#about' },
        { name: 'Donasi', href: '#donate' },
        { name: 'Gallery', href: '#gallery' },
        { name: 'Kontak', href: '#contact' },
    ];

    return (
        <nav className={clsx(
            'fixed w-full z-50 transition-all duration-300',
            scrolled ? 'bg-white/90 backdrop-blur-md shadow-md py-4' : 'bg-transparent py-6'
        )}>
            <div className="container mx-auto px-6 flex justify-between items-center">
                <a href="#" className="flex items-center gap-3 group">
                    <div className="bg-white p-2 rounded-xl shadow-sm group-hover:scale-110 transition-transform">
                        <img src={`${import.meta.env.BASE_URL}img/logo.png`} alt="Logo" className="h-8 w-auto" />
                    </div>
                    <span className={clsx(
                        "font-heading font-bold text-xl tracking-tight transition-colors",
                        scrolled ? "text-primary" : "text-white"
                    )}>
                        Panti Asuhan
                    </span>
                </a>

                {/* Desktop Menu */}
                <div className="hidden md:flex items-center gap-8">
                    {navLinks.map((link) => (
                        <a
                            key={link.name}
                            href={link.href}
                            className={clsx(
                                "text-sm font-medium transition-colors hover:text-secondary",
                                scrolled ? "text-slate-600" : "text-white/90"
                            )}
                        >
                            {link.name}
                        </a>
                    ))}
                    <button onClick={onDonateClick} className="btn-primary">
                        Donasi Sekarang
                    </button>
                </div>

                {/* Mobile Menu Button */}
                <button
                    onClick={() => setIsOpen(!isOpen)}
                    className="md:hidden p-2 rounded-lg"
                >
                    {isOpen ? (
                        <XMarkIcon className={clsx("h-6 w-6", scrolled ? "text-slate-800" : "text-white")} />
                    ) : (
                        <Bars3Icon className={clsx("h-6 w-6", scrolled ? "text-slate-800" : "text-white")} />
                    )}
                </button>
            </div>

            {/* Mobile Menu */}
            <AnimatePresence>
                {isOpen && (
                    <motion.div
                        initial={{ opacity: 0, y: -20 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -20 }}
                        className="absolute top-full left-0 w-full bg-white shadow-lg md:hidden"
                    >
                        <div className="flex flex-col p-6 gap-4">
                            {navLinks.map((link) => (
                                <a
                                    key={link.name}
                                    href={link.href}
                                    className="text-slate-600 font-medium hover:text-primary"
                                    onClick={() => setIsOpen(false)}
                                >
                                    {link.name}
                                </a>
                            ))}
                            <button
                                className="btn-primary text-center w-full"
                                onClick={() => {
                                    setIsOpen(false);
                                    onDonateClick();
                                }}
                            >
                                Donasi Sekarang
                            </button>
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </nav>
    );
}
