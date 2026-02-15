import { Fragment, useState, useEffect } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { XMarkIcon, CheckCircleIcon, BanknotesIcon } from '@heroicons/react/24/outline';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

const PREDEFINED_AMOUNTS = [
    { label: '50rb', value: 50000 },
    { label: '100rb', value: 100000 },
    { label: '200rb', value: 200000 },
    { label: '500rb', value: 500000 },
    { label: '1 Juta', value: 1000000 },
];

export default function DonationModal({ isOpen, setIsOpen }) {
    const [step, setStep] = useState(1);
    const [loading, setLoading] = useState(false);
    const [formData, setFormData] = useState({
        nama: '',
        email: '',
        nominal: '',
        pesan: '',
        no_wa: ''
    });

    const navigate = useNavigate();

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleAmountClick = (amount) => {
        setFormData({ ...formData, nominal: amount });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);

        try {
            const baseUrl = import.meta.env.VITE_API_BASE_URL || '/api';
            // Use v1/donation to match @meee API structure
            const response = await axios.post(`${baseUrl}/v1/donation`, {
                donor_name: formData.nama,
                nama: formData.nama, // Support both styles
                donor_email: formData.email,
                email: formData.email,
                amount: formData.nominal,
                nominal: formData.nominal,
                no_wa: formData.no_wa,
                pesan: formData.pesan
            });

            if (response.data.status === 'success' || response.data.success) {
                const paymentUrl = response.data.payment_url || (response.data.data && response.data.data.payment_url);
                const orderId = response.data.invoice_number || (response.data.data && response.data.data.order_id);

                if (paymentUrl) {
                    if (orderId) {
                        localStorage.setItem('last_order_id', orderId);
                    }
                    setIsOpen(false);
                    window.location.href = paymentUrl;
                } else {
                    throw new Error('Payment URL not received');
                }
            } else {
                throw new Error(response.data.message || 'Gagal memproses donasi');
            }
        } catch (error) {
            console.error('Donation Error:', error);
            let errMsg = 'Terjadi kesalahan sistem. Mohon coba lagi nanti.';
            if (error.response?.data?.message) {
                errMsg = error.response.data.message;
            } else if (error.message) {
                errMsg = error.message;
            }
            alert(errMsg);
            setLoading(false);
        }
    };

    const resetModal = () => {
        setIsOpen(false);
        setTimeout(() => {
            setStep(1);
            setFormData({ nama: '', email: '', nominal: '', pesan: '', no_wa: '' });
        }, 300);
    };

    return (
        <Transition show={isOpen} as={Fragment}>
            <Dialog onClose={resetModal} className="relative z-50">
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" />
                </Transition.Child>

                <div className="fixed inset-0 overflow-y-auto">
                    <div className="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enterTo="opacity-100 translate-y-0 sm:scale-100"
                            leave="ease-in duration-200"
                            leaveFrom="opacity-100 translate-y-0 sm:scale-100"
                            leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        >
                            <Dialog.Panel className="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                                {/* Header Decorative Background */}
                                <div className="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary to-primary-light" />

                                <div className="absolute top-4 right-4 z-10">
                                    <button
                                        onClick={resetModal}
                                        className="rounded-full bg-slate-100 p-2 text-slate-400 hover:text-slate-500 hover:bg-slate-200 transition-colors"
                                    >
                                        <XMarkIcon className="h-5 w-5" />
                                    </button>
                                </div>

                                <div className="px-6 py-6 sm:px-8 sm:py-8">
                                    <div className="mb-6">
                                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 mb-4">
                                            <BanknotesIcon className="h-6 w-6 text-primary" aria-hidden="true" />
                                        </div>
                                        <Dialog.Title as="h3" className="text-xl font-heading font-bold leading-6 text-slate-900 text-center">
                                            Mulai Berbagi Kebaikan
                                        </Dialog.Title>
                                        <p className="text-sm text-slate-500 text-center mt-2">
                                            Lengkapi data di bawah ini untuk melanjutkan donasi Anda.
                                        </p>
                                    </div>

                                    <form onSubmit={handleSubmit} className="space-y-5">
                                        {/* Nominal Section */}
                                        <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Pilih Nominal Donasi</label>
                                            <div className="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-4">
                                                {PREDEFINED_AMOUNTS.map((amt) => (
                                                    <button
                                                        key={amt.value}
                                                        type="button"
                                                        onClick={() => handleAmountClick(amt.value)}
                                                        className={`px-2 py-2 text-xs font-semibold rounded-lg border transition-all ${formData.nominal === amt.value
                                                            ? 'bg-primary text-white border-primary shadow-md transform scale-105'
                                                            : 'bg-white text-slate-600 border-slate-200 hover:border-primary/50 hover:bg-slate-50'
                                                            }`}
                                                    >
                                                        {amt.label}
                                                    </button>
                                                ))}
                                            </div>
                                            <div className="relative">
                                                <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span className="text-slate-500 font-bold sm:text-sm">Rp</span>
                                                </div>
                                                <input
                                                    type="number"
                                                    name="nominal"
                                                    id="nominal"
                                                    required
                                                    min="10000"
                                                    max="100000000"
                                                    className="block w-full rounded-lg border-slate-300 pl-10 pr-12 focus:border-primary focus:ring-primary sm:text-lg font-bold text-slate-800 placeholder-slate-300 py-3"
                                                    placeholder="0"
                                                    value={formData.nominal}
                                                    onChange={(e) => {
                                                        const val = parseInt(e.target.value);
                                                        if (val > 100000000) return;
                                                        handleChange(e);
                                                    }}
                                                />
                                                <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <span className="text-slate-400 sm:text-sm">IDR</span>
                                                </div>
                                            </div>
                                            <p className="text-xs text-slate-400 mt-1 text-right">Maksimal Rp 100.000.000</p>
                                        </div>

                                        {/* Personal Info */}
                                        <div className="space-y-4">
                                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label htmlFor="nama" className="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                                                    <input
                                                        type="text"
                                                        name="nama"
                                                        id="nama"
                                                        required
                                                        className="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary sm:text-sm py-2.5 px-3"
                                                        placeholder="Hamba Allah"
                                                        value={formData.nama}
                                                        onChange={handleChange}
                                                    />
                                                </div>
                                                <div>
                                                    <label htmlFor="no_wa" className="block text-sm font-medium text-slate-700 mb-1">No. WhatsApp</label>
                                                    <input
                                                        type="tel"
                                                        name="no_wa"
                                                        id="no_wa"
                                                        required
                                                        className="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary sm:text-sm py-2.5 px-3"
                                                        placeholder="08xxxxxxxxxx"
                                                        value={formData.no_wa}
                                                        onChange={handleChange}
                                                    />
                                                </div>
                                            </div>

                                            <div>
                                                <label htmlFor="email" className="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                                <input
                                                    type="email"
                                                    name="email"
                                                    id="email"
                                                    required
                                                    className="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary sm:text-sm py-2.5 px-3"
                                                    placeholder="email@contoh.com"
                                                    value={formData.email}
                                                    onChange={handleChange}
                                                />
                                            </div>

                                            <div>
                                                <label htmlFor="pesan" className="block text-sm font-medium text-slate-700 mb-1">Doa / Pesan <span className="text-slate-400 font-normal">(Opsional)</span></label>
                                                <textarea
                                                    name="pesan"
                                                    id="pesan"
                                                    rows="2"
                                                    className="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 resize-none"
                                                    placeholder="Tuliskan doa atau harapan Anda..."
                                                    value={formData.pesan}
                                                    onChange={handleChange}
                                                ></textarea>
                                            </div>
                                        </div>

                                        <div className="mt-8">
                                            <button
                                                type="submit"
                                                disabled={loading}
                                                className="w-full flex items-center justify-center rounded-xl bg-gradient-to-r from-secondary to-secondary-metallic px-8 py-4 text-base font-bold text-white shadow-lg shadow-secondary/20 transition-all hover:shadow-gold-glow hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed"
                                            >
                                                {loading ? (
                                                    <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                ) : null}
                                                {loading ? 'Memproses...' : 'Lanjut Pembayaran'}
                                            </button>
                                            <p className="mt-4 text-center text-xs text-slate-400">
                                                <span className="material-symbols-outlined align-middle text-sm mr-1">lock</span>
                                                Data Anda dilindungi dan tidak akan dipublikasikan tanpa izin.
                                            </p>
                                        </div>
                                    </form>
                                </div>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition>
    );
}

