import os
import re
from pathlib import Path
import random
from datetime import datetime, timedelta

# Configuration
html_dir = r"c:\xampp\htdocs\caps3v1\caps3\html_views"

# Sample Data Templates
SAMPLE_CHILDREN = [
    {"nama": "Ahmad Rizki Maulana", "jk": "L", "status": "YATIM", "nisn": "0012345678", "tgl_masuk": "15/01/2023"},
    {"nama": "Siti Nurhaliza", "jk": "P", "status": "PIATU", "nisn": "0012345679", "tgl_masuk": "20/02/2023"},
    {"nama": "Muhammad Fauzan", "jk": "L", "status": "YATIM_PIATU", "nisn": "0012345680", "tgl_masuk": "10/03/2023"},
    {"nama": "Fatimah Azzahra", "jk": "P", "status": "YATIM", "nisn": "0012345681", "tgl_masuk": "05/04/2023"},
    {"nama": "Abdul Rahman", "jk": "L", "status": "YATIM", "nisn": "0012345682", "tgl_masuk": "12/05/2023"},
    {"nama": "Aisyah Putri", "jk": "P", "status": "PIATU", "nisn": "0012345683", "tgl_masuk": "18/06/2023"},
    {"nama": "Umar bin Khattab", "jk": "L", "status": "YATIM_PIATU", "nisn": "0012345684", "tgl_masuk": "22/07/2023"},
    {"nama": "Khadijah binti Ahmad", "jk": "P", "status": "YATIM", "nisn": "0012345685", "tgl_masuk": "30/08/2023"},
    {"nama": "Ali bin Abi Thalib", "jk": "L", "status": "PIATU", "nisn": "0012345686", "tgl_masuk": "05/09/2023"},
    {"nama": "Maryam Azzahra", "jk": "P", "status": "YATIM", "nisn": "0012345687", "tgl_masuk": "10/10/2023"},
]

SAMPLE_STAFF = [
    {"nama": "Dr. H. Abdullah Syafii", "jabatan": "Direktur", "nik": "3201012345678901", "status": "TETAP"},
    {"nama": "Hj. Siti Maryam, S.Pd", "jabatan": "Kepala Asrama", "nik": "3201012345678902", "status": "TETAP"},
    {"nama": "Ahmad Dahlan, S.E", "jabatan": "Bendahara", "nik": "3201012345678903", "status": "TETAP"},
    {"nama": "Nurul Hidayah, S.Psi", "jabatan": "Psikolog", "nik": "3201012345678904", "status": "KONTRAK"},
    {"nama": "Muhammad Yusuf", "jabatan": "Pengurus Harian", "nik": "3201012345678905", "status": "TETAP"},
    {"nama": "Fatimah Zahra, S.Pd", "jabatan": "Guru", "nik": "3201012345678906", "status": "KONTRAK"},
    {"nama": "Usman bin Affan", "jabatan": "Security", "nik": "3201012345678907", "status": "TETAP"},
    {"nama": "Aminah binti Wahab", "jabatan": "Cook/Chef", "nik": "3201012345678908", "status": "KONTRAK"},
]

SAMPLE_DONATIONS = [
    {"donor": "PT Berkah Jaya", "jumlah": "Rp 5.000.000", "tgl": "28/01/2026", "jenis": "TUNAI"},
    {"donor": "Ibu Siti Aminah", "jumlah": "Rp 1.500.000", "tgl": "25/01/2026", "jenis": "TRANSFER"},
    {"donor": "Bapak Ahmad Hidayat", "jumlah": "Rp 2.000.000", "tgl": "20/01/2026", "jenis": "TUNAI"},
    {"donor": "CV Mitra Sejahtera", "jumlah": "Rp 3.500.000", "tgl": "15/01/2026", "jenis": "TRANSFER"},
    {"donor": "Yayasan Peduli Umat", "jumlah": "Rp 10.000.000", "tgl": "10/01/2026", "jenis": "TRANSFER"},
    {"donor": "Hj. Fatimah Zuhra", "jumlah": "Rp 1.000.000", "tgl": "05/01/2026", "jenis": "TUNAI"},
    {"donor": "PT Cahaya Mandiri", "jumlah": "Rp 7.500.000", "tgl": "30/12/2025", "jenis": "TRANSFER"},
    {"donor": "Donatur Anonim", "jumlah": "Rp 500.000", "tgl": "25/12/2025", "jenis": "TUNAI"},
    {"donor": "Masjid Al-Ikhlas", "jumlah": "Rp 2.500.000", "tgl": "20/12/2025", "jenis": "INFAQ"},
    {"donor": "Keluarga Besar Sumarna", "jumlah": "Rp 1.200.000", "tgl": "15/12/2025", "jenis": "TUNAI"},
]

# Chart Data
MONTHLY_DONATIONS = [
    {"month": "Jan '25", "amount": 15500000},
    {"month": "Feb '25", "amount": 18200000},
    {"month": "Mar '25", "amount": 14800000},
    {"month": "Apr '25", "amount": 21500000},
    {"month": "May '25", "amount": 19300000},
    {"month": "Jun '25", "amount": 22100000},
    {"month": "Jul '25", "amount": 17900000},
    {"month": "Aug '25", "amount": 20500000},
    {"month": "Sep '25", "amount": 23400000},
    {"month": "Oct '25", "amount": 19800000},
    {"month": "Nov '25", "amount": 25300000},
    {"month": "Dec '25", "amount": 27800000},
]

def clean_blade_syntax(content):
    """Remove all Blade syntax remnants"""
    
    # Remove @directives
    content = re.sub(r'@extends\([^)]*\)', '', content)
    content = re.sub(r'@section\([^)]*\)', '', content)
    content = re.sub(r'@endsection', '', content)
    content = re.sub(r'@yield\([^)]*\)', '', content)
    content = re.sub(r'@push\([^)]*\)', '', content)
    content = re.sub(r'@endpush', '', content)
    content = re.sub(r'@csrf', '', content)
    content = re.sub(r'@method\([^)]*\)', '', content)
    content = re.sub(r'@if\([^)]*\)', '', content)
    content = re.sub(r'@elseif\([^)]*\)', '', content)
    content = re.sub(r'@else', '', content)
    content = re.sub(r'@endif', '', content)
    content = re.sub(r'@foreach\([^)]*\)', '', content)
    content = re.sub(r'@endforeach', '', content)
    content = re.sub(r'@forelse\([^)]*\)', '', content)
    content = re.sub(r'@empty', '', content)
    content = re.sub(r'@endforelse', '', content)
    content = re.sub(r'@php', '', content)
    content = re.sub(r'@endphp', '', content)
    content = re.sub(r'@auth', '', content)
    content = re.sub(r'@endauth', '', content)
    content = re.sub(r'@stack\([^)]*\)', '', content)
    content = re.sub(r'@json\([^)]*\)', '[]', content)
    
    # Clean PHP-like syntax
    content = re.sub(r'\$[\w>()-]+', '', content)
    content = re.sub(r'->', '', content)
    content = re.sub(r'\{\{([^}]*)\}\}', r'\1', content)
    content = re.sub(r'\{!!([^}]*?)!!\}', r'\1', content)
    
    # Fix specific patterns
    content = re.sub(r'request\(\)[^\'"\s]*', '', content)
    content = re.sub(r'Auth::user\(\)->name\s*\?\?\s*[\'"][\w\s]+[\'"]', 'Admin Demo', content)
    content = re.sub(r'Auth::user\(\)->role\s*\?\?\s*[\'"][\w\s]+[\'"]', 'ADMINISTRATOR', content)
    content = re.sub(r'Auth::user\(\)->\w+', 'Admin Demo', content)
    
    # Remove loop artifacts
    content = re.sub(r'as\s+\$\w+\)', '', content)
    content = re.sub(r'\$loop->\w+', '1', content)
    
    # Clean conditionals
    content = re.sub(r'\?\s*[\'"]active[\'"]\s*:\s*[\'"][\'"]\s*', '', content)
    content = re.sub(r'==\s*[\'"][A-Z_]+[\'"]', '', content)
    
    return content

def generate_table_row_anak(index, child):
    """Generate a table row for anak asuh"""
    jk_badge = 'Laki-laki' if child['jk'] == 'L' else 'Perempuan'
    status_class = {
        'YATIM': 'bg-light-info text-info',
        'PIATU': 'bg-light-warning text-warning',
        'YATIM_PIATU': 'bg-light-danger text-danger'
    }.get(child['status'], 'bg-light-secondary')
    
    initial = child['nama'][0]
    
    return f"""
                    <tr>
                        <td class="ps-3">{index}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-light-info rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="text-info fw-bold">{initial}</span>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{child['nama']}</div>
                                    <small class="text-muted">{child['nisn']}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light-secondary text-secondary">{jk_badge}</span></td>
                        <td><span class="badge {status_class}">{child['status'].replace('_', ' ')}</span></td>
                        <td>{child['tgl_masuk']}</td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="#" class="btn btn-sm btn-icon btn-light-primary text-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-icon btn-light-warning text-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger text-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>"""

def generate_table_row_pengurus(index, staff):
    """Generate a table row for pengurus"""
    status_class = 'bg-success' if staff['status'] == 'TETAP' else 'bg-warning'
    
    return f"""
                    <tr>
                        <td class="ps-3">{index}</td>
                        <td class="fw-bold text-dark">{staff['nama']}</td>
                        <td>{staff['nik']}</td>
                        <td>{staff['jabatan']}</td>
                        <td><span class="badge {status_class}">{staff['status']}</span></td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="#" class="btn btn-sm btn-icon btn-light-primary text-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-icon btn-light-warning text-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger text-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>"""

def generate_table_row_donasi(index, donasi):
    """Generate a table row for donasi"""
    return f"""
                    <tr>
                        <td class="ps-3">{index}</td>
                        <td>{donasi['tgl']}</td>
                        <td class="fw-bold text-dark">{donasi['donor']}</td>
                        <td><span class="badge bg-primary">{donasi['jenis']}</span></td>
                        <td class="text-success fw-bold">{donasi['jumlah']}</td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="#" class="btn btn-sm btn-icon btn-light-primary text-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-icon btn-light-warning text-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger text-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>"""

def inject_sample_data(filepath, content):
    """Inject appropriate sample data based on file type"""
    
    filename = os.path.basename(filepath)
    
    # Clean Blade syntax first
    content = clean_blade_syntax(content)
    
    # Inject data for specific pages
    if 'anak' in filepath and 'index.html' in filepath:
        # Generate table rows
        rows = '\n'.join([generate_table_row_anak(i+1, child) for i, child in enumerate(SAMPLE_CHILDREN)])
        # Replace empty tbody or sample comment
        content = re.sub(
            r'<tbody>.*?</tbody>',
            f'<tbody>\n{rows}\n                </tbody>',
            content,
            flags=re.DOTALL
        )
    
    elif 'pengurus' in filepath and 'index.html' in filepath:
        rows = '\n'.join([generate_table_row_pengurus(i+1, staff) for i, staff in enumerate(SAMPLE_STAFF)])
        content = re.sub(
            r'<tbody>.*?</tbody>',
            f'<tbody>\n{rows}\n                </tbody>',
            content,
            flags=re.DOTALL
        )
    
    elif 'donasi' in filepath and 'index.html' in filepath:
        rows = '\n'.join([generate_table_row_donasi(i+1, don) for i, don in enumerate(SAMPLE_DONATIONS)])
        content = re.sub(
            r'<tbody>.*?</tbody>',
            f'<tbody>\n{rows}\n                </tbody>',
            content,
            flags=re.DOTALL
        )
    
    # Fix chart data for analytics
    if 'analytics' in filepath or 'dashboard' in filepath:
        # Add chart data
        months = [m['month'] for m in MONTHLY_DONATIONS]
        amounts = [m['amount'] for m in MONTHLY_DONATIONS]
        
        content = re.sub(
            r'labels:\s*\[\]',
            f"labels: {months}",
            content
        )
        content = re.sub(
            r'data:\s*\[\]',
            f"data: {amounts}",
            content
        )
    
    # Fix user display
    content = re.sub(
        r'name=\s*Auth::user\(\)->name\s*\?\?\s*[\'"]User[\'"]',
        'name=Admin+Demo',
        content
    )
    
    # Update stats with real numbers
    content = re.sub(r'Rp\s*number_format[^<]+', 'Rp 25.500.000', content)
    content = re.sub(r'Prediksi Donasi.*?</div>', 'Prediksi Donasi</div>\n                            <div class="h5 mb-0 font-weight-bold text-dark">Rp 28.500.000</div>', content)
    
    return content

def process_all_files():
    """Process all HTML files in the directory"""
    
    print(f"\n{'='*70}")
    print(f"  ENHANCING HTML FILES TO PROFESSIONAL DEMO TEMPLATES")
    print(f"{'='*70}\n")
    
    count = 0
    total = 0
    
    # Count total files
    for root, dirs, files in os.walk(html_dir):
        for file in files:
            if file.endswith('.html'):
                total += 1
    
    # Process each file
    for root, dirs, files in os.walk(html_dir):
        for file in files:
            if file.endswith('.html'):
                count += 1
                filepath = os.path.join(root, file)
                rel_path = os.path.relpath(filepath, html_dir)
                
                print(f"[{count}/{total}] Processing: {rel_path}")
                
                try:
                    with open(filepath, 'r', encoding='utf-8') as f:
                        content = f.read()
                    
                    # Inject sample data and clean
                    enhanced = inject_sample_data(filepath, content)
                    
                    # Write back
                    with open(filepath, 'w', encoding='utf-8') as f:
                        f.write(enhanced)
                    
                    print(f"           ✓ Enhanced successfully")
                    
                except Exception as e:
                    print(f"           ✗ Error: {e}")
    
    print(f"\n{'='*70}")
    print(f"  ENHANCEMENT COMPLETE!")
    print(f"  Total: {count}/{total} files processed")
    print(f"{'='*70}\n")

if __name__ == "__main__":
    process_all_files()
