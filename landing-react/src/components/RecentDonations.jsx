import { useState, useEffect } from 'react';
import axios from 'axios';
import { motion, AnimatePresence } from 'framer-motion';

export default function RecentDonations() {
    const [donations, setDonations] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchDonations = async () => {
            try {
                const baseUrl = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api';
                const response = await axios.get(`${baseUrl}/landing/donations`);
                let data = [];
                if (Array.isArray(response.data)) {
                    data = response.data;
                } else if (response.data.data && Array.isArray(response.data.data)) {
                    data = response.data.data;
                }
                // Take top 10
                setDonations(data.slice(0, 10));
            } catch (err) {
                console.error("Error loading recent donations:", err);
            } finally {
                setLoading(false);
            }
        };

        fetchDonations();
        const interval = setInterval(fetchDonations, 30000); // 30s refresh
        return () => clearInterval(interval);
    }, []);

    const formatAmount = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    };

    if (loading) return null;

    if (donations.length === 0) {
        return (
            <div className="text-center py-10 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <p className="text-slate-500">Belum ada donasi saat ini.</p>
            </div>
        );
    }

    return (
        <div className="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden relative">
            {/* Header */}
            <div className="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center z-10 relative">
                <h3 className="flex items-center gap-3 font-heading font-bold text-xl text-slate-800">
                    <span className="relative flex h-3 w-3">
                        <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span className="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    Para Donatur
                </h3>
            </div>

            {/* Single Column Layout with Smooth Scrolling */}
            <div className="relative h-[400px] md:h-[600px] overflow-hidden bg-white group">
                {/* Gradients for smooth fade */}
                <div className="absolute top-0 left-0 right-0 h-16 md:h-20 bg-gradient-to-b from-white to-transparent z-10 pointer-events-none"></div>
                <div className="absolute bottom-0 left-0 right-0 h-16 md:h-20 bg-gradient-to-t from-white to-transparent z-10 pointer-events-none"></div>

                {/* Single Column - All Devices */}
                <div className="h-full relative overflow-hidden px-4 md:px-8">
                    <div className="absolute w-full animate-marquee-vertical group-hover:pause-animation" style={{ animationDuration: '50s' }}>
                        {/* Duplicate for seamless loop */}
                        {[...donations, ...donations].map((donation, index) => (
                            <div key={`donation-${donation.id}-${index}`} className="mb-4">
                                <DonationCard donation={donation} index={index} formatAmount={formatAmount} />
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}

function DonationCard({ donation, index, formatAmount }) {
    return (
        <div className="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow hover:border-primary/30 h-full">
            <div className={`
                w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-md
                ${index % 2 === 0 ? 'bg-gradient-to-br from-primary to-primary-light' : 'bg-gradient-to-br from-secondary to-secondary-light'}
            `}>
                {donation.initial}
            </div>
            <div className="flex-1 min-w-0">
                <div className="flex justify-between items-start mb-1">
                    <h4 className="font-bold text-slate-900 truncate" title={donation.name}>
                        {donation.name}
                    </h4>
                    <span className="text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded-full whitespace-nowrap">
                        {donation.time}
                    </span>
                </div>
                <div className="text-primary font-bold text-sm mb-1">
                    {formatAmount(donation.amount)}
                </div>
                {donation.message && (
                    <div className="relative bg-slate-50 p-2 rounded-lg mt-2">
                        <p className="text-xs text-slate-500 italic line-clamp-2 relative z-10">
                            "{donation.message}"
                        </p>
                    </div>
                )}
            </div>
        </div>
    );
}
