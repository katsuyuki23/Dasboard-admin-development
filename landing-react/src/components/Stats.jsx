import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js';
import { Line } from 'react-chartjs-2';
import { motion } from 'framer-motion';
import { UserGroupIcon, FaceSmileIcon } from '@heroicons/react/24/solid';
import useLandingData from '../hooks/useLandingData';
import RecentDonations from './RecentDonations';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

export default function Stats() {
    const { stats, chartData, loading, year, setYear, formatCurrencyShort } = useLandingData();

    // Chart Configuration
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                align: 'end',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8,
                    font: {
                        family: "'Inter', sans-serif",
                        size: window.innerWidth < 768 ? 10 : 12
                    },
                    padding: 20
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                titleColor: '#1e293b',
                bodyColor: '#475569',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                callbacks: {
                    label: function (context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { family: "'Inter', sans-serif" } }
            },
            y: {
                border: { display: false },
                grid: { color: '#f1f5f9' },
                ticks: {
                    callback: function (value) {
                        return formatCurrencyShort(value);
                    },
                    font: { family: "'Inter', sans-serif" }
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        },
        elements: {
            point: {
                radius: 0, // No dots as requested
                hoverRadius: 0
            }
        }
    };

    const data = {
        labels: chartData.labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: chartData.income,
                borderColor: '#15803d', // primary-dark
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(21, 128, 61, 0.5)');
                    gradient.addColorStop(1, 'rgba(21, 128, 61, 0.0)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
            },
            {
                label: 'Pengeluaran',
                data: chartData.expense,
                borderColor: '#ca8a04', // secondary
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(202, 138, 4, 0.5)');
                    gradient.addColorStop(1, 'rgba(202, 138, 4, 0.0)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
            },
        ],
    };

    return (
        <section id="stats" className="py-20 relative">
            <div className="container mx-auto px-6">


                {/* Bento Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-[minmax(min-content,max-content)]">

                    {/* Chart Card (Large) */}
                    <div className="glass-card rounded-3xl p-8 md:col-span-2 lg:col-span-2 lg:row-span-2 relative overflow-hidden">
                        <div className="absolute inset-0 opacity-5" style={{ backgroundImage: `url(${import.meta.env.BASE_URL}img/grid.svg)` }}></div>
                        <div className="relative z-10 h-full flex flex-col">
                            <div className="flex justify-between items-start mb-6">
                                <div>
                                    <h3 className="text-2xl font-heading font-bold mb-1">Tren Donasi</h3>
                                    <p className="text-slate-500 text-sm">Pemasukan dan Pengeluaran</p>
                                </div>
                                <select
                                    value={year}
                                    onChange={(e) => setYear(e.target.value)}
                                    className="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-primary focus:border-primary block p-2 px-4 shadow-sm font-medium cursor-pointer hover:bg-slate-100 transition-colors"
                                >
                                    {stats.available_years && stats.available_years.length > 0 ? (
                                        stats.available_years.map((y) => (
                                            <option key={y} value={y}>{y}</option>
                                        ))
                                    ) : (
                                        <option value={new Date().getFullYear()}>{new Date().getFullYear()}</option>
                                    )}
                                </select>
                            </div>
                            <div className="h-[300px] md:h-[400px] w-full mt-4">
                                {loading ? (
                                    <div className="flex items-center justify-center h-full text-slate-400">Loading Chart...</div>
                                ) : (
                                    <Line options={options} data={data} />
                                )}
                            </div>

                            {/* Summary Stats Row */}
                            <div className="grid grid-cols-3 gap-4 mt-8 pt-8 border-t border-slate-100">
                                <div className="text-center p-4 rounded-2xl bg-slate-50">
                                    <div className="text-xs text-slate-400 uppercase tracking-wider mb-1">Total Keuangan</div>
                                    <div className="text-lg md:text-xl font-bold text-primary">{formatCurrencyShort(stats.total_keuangan)}</div>
                                </div>
                                <div className="text-center p-4 rounded-2xl bg-slate-50">
                                    <div className="text-xs text-slate-400 uppercase tracking-wider mb-1">Pengeluaran</div>
                                    <div className="text-lg md:text-xl font-bold text-secondary">{formatCurrencyShort(stats.pengeluaran)}</div>
                                </div>
                                <div className="text-center p-4 rounded-2xl bg-slate-50">
                                    <div className="text-xs text-slate-400 uppercase tracking-wider mb-1">Pemasukan</div>
                                    <div className="text-lg md:text-xl font-bold text-slate-600">{formatCurrencyShort(stats.pemasukan)}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Anak Asuh & Staff Column */}
                    <div className="flex flex-col gap-3 h-full">
                        {/* Anak Asuh Card */}
                        <motion.div
                            whileHover={{ y: -5 }}
                            className="glass-card rounded-3xl p-8 bg-gradient-to-br from-primary to-primary-dark text-white shadow-lg relative overflow-hidden group flex-1"
                        >
                            <div className="absolute top-0 right-0 p-8 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                                <FaceSmileIcon className="w-24 h-24" />
                            </div>
                            <div className="relative z-10 flex flex-col justify-between h-full">
                                <div className="bg-white/10 w-fit p-3 rounded-2xl mb-4 backdrop-blur-md">
                                    <FaceSmileIcon className="w-6 h-6 text-primary-light" />
                                </div>
                                <div>
                                    <div className="font-heading text-5xl font-bold mb-1">
                                        {loading ? '...' : stats.anak}
                                    </div>
                                    <div className="text-sm font-medium text-primary-light/90 tracking-wide uppercase">Anak Asuh</div>
                                </div>
                            </div>
                        </motion.div>

                        {/* Staff Card */}
                        <motion.div
                            whileHover={{ y: -5 }}
                            className="glass-card rounded-3xl p-8 bg-white border border-slate-100 shadow-xl relative overflow-hidden group flex-1"
                        >
                            <div className="absolute top-0 right-0 p-8 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                                <UserGroupIcon className="w-24 h-24 text-secondary" />
                            </div>
                            <div className="relative z-10 flex flex-col justify-between h-full">
                                <div className="bg-secondary/20 w-fit p-4 rounded-2xl mb-4 backdrop-blur-md">
                                    <UserGroupIcon className="w-6 h-6 text-secondary" />
                                </div>
                                <div>
                                    <div className="font-heading text-5xl font-bold mb-1 text-slate-800">
                                        {loading ? '...' : stats.pengurus}
                                    </div>
                                    <div className="text-sm font-medium text-slate-500 tracking-wide uppercase">Staff Pengajar</div>
                                </div>
                            </div>
                        </motion.div>
                    </div>


                </div>
            </div>
        </section>
    );
}
