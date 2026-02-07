# ERD & UML Diagrams - CAPS3 Project
## Sistem Informasi Panti Asuhan Assholihin

---

## 1. Entity Relationship Diagram (ERD)

Diagram ini menunjukkan struktur database dan relasi antar tabel dalam sistem.

```mermaid
erDiagram
    %% Core Entities
    USERS ||--o{ DONATUR : "has profile"
    USERS ||--o{ ACTIVITY_LOG : "performs"
    
    ANAK ||--o{ RIWAYAT_KESEHATAN : "has"
    ANAK ||--o{ RIWAYAT_PENDIDIKAN : "has"
    ANAK ||--o{ DOKUMEN_ANAK : "has"
    ANAK ||--o{ FOTO_KEGIATAN : "tagged in"
    ANAK ||--o{ GROWTH_MONITORING : "monitored in"
    
    DONATUR ||--o{ DONASI : "makes"
    DONASI ||--|| TRANSAKSI_KAS : "creates"
    
    KAS ||--o{ TRANSAKSI_KAS : "contains"
    KATEGORI_TRANSAKSI ||--o{ TRANSAKSI_KAS : "categorizes"
    
    %% User Entity
    USERS {
        bigint id_user PK
        string name
        string email UK
        string password
        string role
        timestamp email_verified_at
        timestamp created_at
    }
    
    %% Anak (Children) Module
    ANAK {
        bigint id_anak PK
        string nomor_induk UK
        string nik
        string nisn
        string nama
        string tempat_lahir
        date tanggal_lahir
        enum jenis_kelamin
        enum status_anak
        string nama_ayah
        string nama_ibu
        string nama_wali
        string hubungan_wali
        string no_hp_wali
        string no_hp_keluarga
        text alamat_wali
        text alamat_asal
        text alasan_masuk
        date tanggal_masuk
        date tanggal_keluar
        string foto
    }
    
    RIWAYAT_KESEHATAN {
        bigint id_kesehatan PK
        bigint id_anak FK
        date tanggal_periksa
        string keluhan
        string diagnosa
        string tindakan
        string keterangan
    }
    
    RIWAYAT_PENDIDIKAN {
        bigint id_pendidikan PK
        bigint id_anak FK
        string jenjang
        string nama_sekolah
        string tahun_ajaran
        string kelas
        string prestasi
        string keterangan
    }
    
    DOKUMEN_ANAK {
        bigint id_dokumen PK
        bigint id_anak FK
        string jenis_dokumen
        string nama_file
        string path_file
        timestamp uploaded_at
    }
    
    FOTO_KEGIATAN {
        bigint id_foto PK
        bigint id_anak FK
        string judul
        text deskripsi
        string path_foto
        date tanggal_kegiatan
    }
    
    GROWTH_MONITORING {
        bigint id_monitoring PK
        bigint id_anak FK
        date tanggal_ukur
        int usia_bulan
        decimal berat_badan
        decimal tinggi_badan
        decimal lingkar_kepala
        decimal z_score_berat
        decimal z_score_tinggi
        string status_gizi
        text rekomendasi_ai
        text catatan
    }
    
    %% Donation Module
    DONATUR {
        bigint id_donatur PK
        bigint user_id FK
        string nama
        text alamat
    }
    
    DONASI {
        bigint id_donasi PK
        enum type_donasi
        bigint id_donatur FK
        string sumber_non_donatur
        int bulan
        int tahun
        decimal jumlah
        date tanggal_catat
    }
    
    %% Financial Module
    KAS {
        bigint id_kas PK
        decimal saldo_awal
        decimal total_masuk
        decimal total_keluar
        decimal saldo_akhir
        date tanggal_update
    }
    
    KATEGORI_TRANSAKSI {
        bigint id_kategori PK
        string nama_kategori
        enum jenis
        text deskripsi
    }
    
    TRANSAKSI_KAS {
        bigint id_transaksi PK
        bigint id_kas FK
        bigint id_kategori FK
        bigint id_donasi FK
        enum jenis_transaksi
        decimal nominal
        date tanggal
        text keterangan
    }
    
    %% Staff Module
    PENGURUS {
        bigint id_pengurus PK
        string nik UK
        string nama
        enum jenis_kelamin
        string tempat_lahir
        date tanggal_lahir
        date mulai_bekerja
        string jabatan
        string status_kepegawaian
        string pendidikan_terakhir
        text pelatihan
    }
    
    %% Activity Log
    ACTIVITY_LOG {
        bigint id_log PK
        bigint user_id FK
        string action
        string model
        bigint model_id
        text description
        json old_values
        json new_values
        timestamp created_at
    }
```

---

## 2. UML Class Diagram

Diagram ini menunjukkan struktur class model dan method-method yang tersedia.

```mermaid
classDiagram
    %% Eloquent Model Base
    class Model {
        <<abstract>>
        +timestamps
        +save()
        +delete()
        +update()
    }
    
    %% User Management
    class User {
        +id: bigint
        +name: string
        +email: string
        +password: string
        +role: string
        +email_verified_at: timestamp
        +donatur() Donatur
        +activityLogs() Collection
    }
    
    class ActivityLog {
        +id_log: bigint
        +user_id: bigint
        +action: string
        +model: string
        +model_id: bigint
        +description: text
        +old_values: json
        +new_values: json
        +user() User
    }
    
    %% Children Management
    class Anak {
        +id_anak: bigint
        +nomor_induk: string
        +nik: string
        +nama: string
        +tanggal_lahir: date
        +jenis_kelamin: enum
        +status_anak: enum
        +riwayatKesehatan() Collection
        +riwayatPendidikan() Collection
        +dokumen() Collection
        +fotoKegiatan() Collection
        +growthMonitoring() Collection
    }
    
    class RiwayatKesehatan {
        +id_kesehatan: bigint
        +id_anak: bigint
        +tanggal_periksa: date
        +keluhan: string
        +diagnosa: string
        +tindakan: string
        +anak() Anak
    }
    
    class RiwayatPendidikan {
        +id_pendidikan: bigint
        +id_anak: bigint
        +jenjang: string
        +nama_sekolah: string
        +tahun_ajaran: string
        +kelas: string
        +anak() Anak
    }
    
    class DokumenAnak {
        +id_dokumen: bigint
        +id_anak: bigint
        +jenis_dokumen: string
        +nama_file: string
        +path_file: string
        +anak() Anak
    }
    
    class FotoKegiatan {
        +id_foto: bigint
        +id_anak: bigint
        +judul: string
        +deskripsi: text
        +path_foto: string
        +tanggal_kegiatan: date
        +anak() Anak
    }
    
    class GrowthMonitoring {
        +id_monitoring: bigint
        +id_anak: bigint
        +tanggal_ukur: date
        +berat_badan: decimal
        +tinggi_badan: decimal
        +z_score_berat: decimal
        +status_gizi: string
        +rekomendasi_ai: text
        +anak() Anak
    }
    
    %% Donation Management
    class Donatur {
        +id_donatur: bigint
        +user_id: bigint
        +nama: string
        +alamat: text
        +user() User
        +donasi() Collection
    }
    
    class Donasi {
        +id_donasi: bigint
        +type_donasi: enum
        +id_donatur: bigint
        +sumber_non_donatur: string
        +bulan: int
        +tahun: int
        +jumlah: decimal
        +tanggal_catat: date
        +donatur() Donatur
        +transaksiKas() TransaksiKas
    }
    
    %% Financial Management
    class Kas {
        +id_kas: bigint
        +saldo_awal: decimal
        +total_masuk: decimal
        +total_keluar: decimal
        +saldo_akhir: decimal
        +tanggal_update: date
        +transaksi() Collection
    }
    
    class KategoriTransaksi {
        +id_kategori: bigint
        +nama_kategori: string
        +jenis: enum
        +deskripsi: text
        +transaksi() Collection
    }
    
    class TransaksiKas {
        +id_transaksi: bigint
        +id_kas: bigint
        +id_kategori: bigint
        +id_donasi: bigint
        +jenis_transaksi: enum
        +nominal: decimal
        +tanggal: date
        +keterangan: text
        +kas() Kas
        +kategori() KategoriTransaksi
        +donasi() Donasi
    }
    
    %% Staff Management
    class Pengurus {
        +id_pengurus: bigint
        +nik: string
        +nama: string
        +jabatan: string
        +status_kepegawaian: string
        +pendidikan_terakhir: string
    }
    
    %% Relationships
    Model <|-- User
    Model <|-- ActivityLog
    Model <|-- Anak
    Model <|-- RiwayatKesehatan
    Model <|-- RiwayatPendidikan
    Model <|-- DokumenAnak
    Model <|-- FotoKegiatan
    Model <|-- GrowthMonitoring
    Model <|-- Donatur
    Model <|-- Donasi
    Model <|-- Kas
    Model <|-- KategoriTransaksi
    Model <|-- TransaksiKas
    Model <|-- Pengurus
    
    User "1" --> "0..1" Donatur : has
    User "1" --> "*" ActivityLog : creates
    
    Anak "1" --> "*" RiwayatKesehatan : has
    Anak "1" --> "*" RiwayatPendidikan : has
    Anak "1" --> "*" DokumenAnak : has
    Anak "1" --> "*" FotoKegiatan : has
    Anak "1" --> "*" GrowthMonitoring : has
    
    Donatur "1" --> "*" Donasi : makes
    Donasi "1" --> "1" TransaksiKas : creates
    
    Kas "1" --> "*" TransaksiKas : contains
    KategoriTransaksi "1" --> "*" TransaksiKas : categorizes
    TransaksiKas "*" --> "0..1" Donasi : references
```

---

## 3. Database Summary

### Core Modules

| Module | Tables | Description |
|--------|--------|-------------|
| **User Management** | users, activity_log | Authentication, authorization, and audit trail |
| **Children Management** | anak, riwayat_kesehatan, riwayat_pendidikan, dokumen_anak, foto_kegiatan, growth_monitoring | Complete child records with health, education, documents, and growth tracking |
| **Donation Management** | donatur, donasi | Donor profiles and donation records |
| **Financial Management** | kas, kategori_transaksi, transaksi_kas | Cash management with categorized transactions |
| **Staff Management** | pengurus | Staff information and HR records |

### Key Relationships

- **One-to-Many (hasMany)**:
  - Anak → RiwayatKesehatan, RiwayatPendidikan, DokumenAnak, FotoKegiatan, GrowthMonitoring
  - Donatur → Donasi
  - Kas → TransaksiKas
  - KategoriTransaksi → TransaksiKas
  - User → ActivityLog

- **Many-to-One (belongsTo)**:
  - Donatur → User
  - Donasi → Donatur
  - TransaksiKas → Kas, KategoriTransaksi, Donasi
  - All child records → Anak

- **One-to-One**:
  - Donasi → TransaksiKas (each donation creates one transaction)
  - User → Donatur (each donor has one user account)

### Special Features

- **AI/ML Integration**: GrowthMonitoring has `rekomendasi_ai` field for AI-powered nutrition recommendations
- **Audit Trail**: ActivityLog tracks all important actions with old/new values
- **Flexible Donation**: Supports both registered donors (DONATUR_TETAP) and anonymous donations (NON_DONATUR)
- **Growth Monitoring**: WHO Z-score based stunting detection and monitoring

---

## 4. SYSTEM ARCHITECTURE DIAGRAM

### Arsitektur 5 Layer

```mermaid
graph TB
    subgraph CLIENT["CLIENT LAYER"]
        Browser["🌐 Web Browser<br/>Chrome, Firefox, Edge"]
        Mobile["📱 Mobile Browser"]
        Tablet["📱 Tablet Browser"]
    end

    subgraph PRESENTATION["PRESENTATION LAYER"]
        Blade["📄 Blade Templates<br/>• layouts/app.blade.php<br/>• auth/login.blade.php<br/>• dashboard/index.blade.php<br/>• anak/*, pengurus/*, etc"]
        Assets["🎨 Assets<br/>• Bootstrap 5<br/>• Font Awesome<br/>• Chart.js"]
    end

    subgraph APPLICATION["APPLICATION LAYER"]
        Routes["🛣️ Routes<br/>67 Routes"]
        Middleware["🔒 Middleware<br/>• Authentication<br/>• CSRF Protection<br/>• Role-based Access"]
        Controllers["🎮 Controllers<br/>• AnakController<br/>• DashboardController<br/>• DonasiController<br/>• TransaksiKasController<br/>• LaporanController<br/>• PengurusController<br/>• GalleryController"]
    end

    subgraph BUSINESS["BUSINESS LOGIC LAYER"]
        Models["📦 Models (Eloquent ORM)<br/>• Anak<br/>• Pengurus<br/>• Donasi<br/>• TransaksiKas<br/>• RiwayatKesehatan<br/>• RiwayatPendidikan<br/>• DokumenAnak<br/>• FotoKegiatan"]
        Exports["📊 Export Services<br/>• LaporanKeuanganExport<br/>• RekapTahunanExport<br/>• AnakExport<br/>• PDF Generation"]
    end

    subgraph DATA["DATA LAYER"]
        Database["🗄️ MySQL Database 8.0+<br/>20 Tables"]
        Storage["💾 File Storage<br/>• storage/app/public/anak<br/>• storage/app/public/dokumen<br/>• storage/app/public/gallery"]
    end

    Browser --> Blade
    Mobile --> Blade
    Tablet --> Blade
    
    Blade --> Routes
    Assets --> Blade
    
    Routes --> Middleware
    Middleware --> Controllers
    
    Controllers --> Models
    Controllers --> Exports
    
    Models --> Database
    Exports --> Database
    Exports --> Storage
    Models --> Storage

    style CLIENT fill:#E3F2FD,stroke:#1976D2,stroke-width:2px
    style PRESENTATION fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px
    style APPLICATION fill:#E8F5E9,stroke:#388E3C,stroke-width:2px
    style BUSINESS fill:#FFF3E0,stroke:#F57C00,stroke-width:2px
    style DATA fill:#FCE4EC,stroke:#C2185B,stroke-width:2px
```

---

## 5. USE CASE DIAGRAM

```mermaid
graph LR
    Admin((👤 Admin))
    Pengurus((👤 Pengurus))
    
    subgraph SISTEM["SISTEM INFORMASI PANTI ASUHAN"]
        UC1[Kelola Data Anak Asuh]
        UC2[Kelola Data Pengurus]
        UC3[Kelola Keuangan]
        UC4[Kelola Gallery]
        UC5[Lihat Dashboard]
        UC6[Generate Laporan]
        UC7[Lihat Data Anak]
        UC8[Lihat Laporan]
    end
    
    Admin --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC5
    Admin --> UC6
    
    Pengurus --> UC7
    Pengurus --> UC5
    Pengurus --> UC8
    
    UC1 -.->|include| UC1A[Upload Dokumen]
    UC1 -.->|include| UC1B[Upload Foto]
    UC1 -.->|include| UC1C[Kelola Riwayat]
    
    UC3 -.->|include| UC3A[Catat Donasi]
    UC3 -.->|include| UC3B[Catat Pengeluaran]
    
    UC6 -.->|include| UC6A[Export Excel]
    UC6 -.->|include| UC6B[Export PDF]

    style Admin fill:#4CAF50,stroke:#2E7D32,stroke-width:3px,color:#fff
    style Pengurus fill:#2196F3,stroke:#1565C0,stroke-width:3px,color:#fff
    style SISTEM fill:#F5F5F5,stroke:#9E9E9E,stroke-width:2px
```

---

## 6. SEQUENCE DIAGRAMS (KEY PROCESSES)

### 6.1 Login Process

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant Laravel
    participant Database

    Admin->>Browser: Buka halaman login
    Browser->>Laravel: GET /admin/login
    Laravel->>Browser: Return login form
    Browser->>Admin: Tampilkan form login
    
    Admin->>Browser: Input email & password
    Admin->>Browser: Klik "Login"
    Browser->>Laravel: POST /admin/login
    
    Laravel->>Laravel: Validate input
    Laravel->>Database: Query user by email
    Database->>Laravel: Return user data
    
    Laravel->>Laravel: Verify password (bcrypt)
    
    alt Password Valid
        Laravel->>Laravel: Create session
        Laravel->>Browser: Redirect to /admin/dashboard
        Browser->>Admin: Tampilkan dashboard
    else Password Invalid
        Laravel->>Browser: Return error message
        Browser->>Admin: Tampilkan error
    end
```

### 6.2 Catat Donasi

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant DonasiController
    participant TransaksiController
    participant Database

    Admin->>Browser: Pilih menu "Donasi"
    Admin->>Browser: Klik "Tambah Donasi"
    Browser->>DonasiController: GET /admin/donasi/create
    DonasiController->>Browser: Return form
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Pilih type donasi
    Admin->>Browser: Input jumlah & detail
    Admin->>Browser: Klik "Simpan"
    
    Browser->>DonasiController: POST /admin/donasi
    DonasiController->>DonasiController: Validate input
    
    alt Valid
        DonasiController->>Database: BEGIN TRANSACTION
        DonasiController->>Database: INSERT INTO donasi
        Database->>DonasiController: Return donasi_id
        
        DonasiController->>TransaksiController: Create transaksi MASUK
        TransaksiController->>Database: INSERT INTO transaksi_kas
        
        TransaksiController->>Database: UPDATE kas SET saldo = saldo + jumlah
        
        Database->>DonasiController: COMMIT
        DonasiController->>Browser: Redirect with success
        Browser->>Admin: Tampilkan success & updated saldo
    else Invalid
        DonasiController->>Browser: Return errors
        Browser->>Admin: Tampilkan error
    end
```

---

## 7. FLOWCHART - GENERATE LAPORAN

```mermaid
flowchart TD
    Start([START]) --> MenuLaporan[Pilih Menu Laporan]
    MenuLaporan --> InputPeriode[Input Periode<br/>Start Date - End Date]
    InputPeriode --> PilihFormat{Pilih Format}
    
    PilihFormat -->|Excel| FormatExcel[Format: Excel]
    PilihFormat -->|PDF| FormatPDF[Format: PDF]
    
    FormatExcel --> Export
    FormatPDF --> Export
    
    Export[Klik Export] --> Query[Query Data dari Database]
    Query --> CekData{Ada Data?}
    
    CekData -->|Tidak| EmptyReport[Generate Empty Report]
    CekData -->|Ya| HitungTotal[Hitung Total<br/>Pemasukan & Pengeluaran]
    
    HitungTotal --> FormatData[Format Data]
    EmptyReport --> FormatData
    
    FormatData --> CekFormat{Format?}
    
    CekFormat -->|Excel| GenExcel[Generate Excel File<br/>dengan Styling]
    CekFormat -->|PDF| GenPDF[Generate PDF File<br/>Landscape]
    
    GenExcel --> Download[Download File]
    GenPDF --> Download
    Download --> End([END])

    style Start fill:#4CAF50,stroke:#2E7D32,stroke-width:2px,color:#fff
    style End fill:#F44336,stroke:#C62828,stroke-width:2px,color:#fff
    style PilihFormat fill:#2196F3,stroke:#1565C0,stroke-width:2px
    style CekData fill:#FF9800,stroke:#E65100,stroke-width:2px
    style CekFormat fill:#9C27B0,stroke:#6A1B9A,stroke-width:2px,color:#fff
```

