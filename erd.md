# MERMAID DIAGRAMS - SISTEM INFORMASI PANTI ASUHAN ASSHOLIHIN

## 1. ENTITY RELATIONSHIP DIAGRAM (ERD)

### ERD Lengkap - 20 Tabel

```mermaid
erDiagram
    USERS ||--o| DONATUR : "has"
    DONATUR ||--o{ DONASI : "makes"
    DONASI ||--o| TRANSAKSI_KAS : "creates"
    KAS ||--o{ TRANSAKSI_KAS : "records"
    KATEGORI_TRANSAKSI ||--o{ TRANSAKSI_KAS : "categorizes"
    ANAK ||--o{ RIWAYAT_KESEHATAN : "has"
    ANAK ||--o{ RIWAYAT_PENDIDIKAN : "has"
    ANAK ||--o{ DOKUMEN_ANAK : "has"
    ANAK ||--o{ FOTO_KEGIATAN : "appears_in"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role "ADMIN, USER"
        timestamp created_at
        timestamp updated_at
    }

    DONATUR {
        bigint id_donatur PK
        bigint user_id FK
        string nama
        text alamat
        timestamp created_at
        timestamp updated_at
    }

    DONASI {
        bigint id_donasi PK
        bigint id_donatur FK "nullable"
        enum type_donasi "DONATUR_TETAP, NON_DONATUR"
        enum sumber_non_donatur "NON_DONATUR, BANTUAN, PROGRAM_UEP, KOTAK_AMAL"
        int bulan
        int tahun
        decimal jumlah
        date tanggal_catat
        timestamp created_at
        timestamp updated_at
    }

    KAS {
        bigint id_kas PK
        string nama_kas
        decimal saldo
        timestamp created_at
        timestamp updated_at
    }

    KATEGORI_TRANSAKSI {
        bigint id_kategori PK
        string nama_kategori "PERMAKANAN, OPERASIONAL, PENDIDIKAN, SARANA_PRASARANA"
    }

    TRANSAKSI_KAS {
        bigint id_transaksi PK
        bigint id_kas FK
        bigint id_kategori FK
        bigint id_donasi FK "nullable"
        enum jenis_transaksi "MASUK, KELUAR"
        decimal nominal
        date tanggal
        text keterangan
        timestamp created_at
        timestamp updated_at
    }

    ANAK {
        bigint id_anak PK
        string nomor_induk UK
        string nik UK "16 digits"
        string nisn "10 digits"
        string nama
        string tempat_lahir
        date tanggal_lahir
        enum jenis_kelamin "L, P"
        enum status_anak "AKTIF, KELUAR"
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
        date tanggal_keluar "nullable"
        string foto "nullable"
        timestamp created_at
        timestamp updated_at
    }

    RIWAYAT_KESEHATAN {
        bigint id_kesehatan PK
        bigint id_anak FK
        string kategori
        text keterangan
        timestamp created_at
        timestamp updated_at
    }

    RIWAYAT_PENDIDIKAN {
        bigint id_pendidikan PK
        bigint id_anak FK
        string jenjang
        string nama_sekolah
        timestamp created_at
        timestamp updated_at
    }

    DOKUMEN_ANAK {
        bigint id_dokumen PK
        bigint id_anak FK
        string jenis_dokumen
        string nama_file
        string path_file
        text keterangan
        timestamp created_at
        timestamp updated_at
    }

    FOTO_KEGIATAN {
        bigint id_foto PK
        bigint id_anak FK "nullable"
        string judul
        text deskripsi
        string path_foto
        date tanggal_kegiatan
        timestamp created_at
        timestamp updated_at
    }

    PENGURUS {
        bigint id_pengurus PK
        string nik UK "16 digits"
        string nama
        enum jenis_kelamin "L, P"
        string tempat_lahir
        date tanggal_lahir
        string jabatan
        enum status "AKTIF, NON_AKTIF"
        string pendidikan
        string pelatihan
        date tanggal_mulai
        date tanggal_selesai "nullable"
        timestamp created_at
        timestamp updated_at
    }
```

---

## 2. SYSTEM ARCHITECTURE DIAGRAM

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

## 3. USE CASE DIAGRAM

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

## 4. SEQUENCE DIAGRAMS - SEMUA FITUR

### 4.1 Sequence: Login Process

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

---

### 4.2 Sequence: Tambah Data Anak Asuh

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant Controller
    participant Validation
    participant Model
    participant Database
    participant Storage

    Admin->>Browser: Klik "Tambah Anak"
    Browser->>Controller: GET /admin/anak/create
    Controller->>Browser: Return form view
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Isi form data anak
    Admin->>Browser: Upload foto
    Admin->>Browser: Klik "Simpan"
    
    Browser->>Controller: POST /admin/anak
    Controller->>Validation: Validate request data
    
    alt Validation Failed
        Validation->>Browser: Return errors
        Browser->>Admin: Tampilkan error messages
    else Validation Success
        Validation->>Controller: Data valid
        Controller->>Model: Create new Anak
        Model->>Database: INSERT INTO anak
        Database->>Model: Return created record
        
        alt Foto uploaded
            Controller->>Storage: Store foto file
            Storage->>Controller: Return file path
            Controller->>Model: Update foto path
            Model->>Database: UPDATE anak SET foto
        end
        
        Controller->>Browser: Redirect to detail page
        Browser->>Admin: Tampilkan success message
    end
```

---

### 4.3 Sequence: Edit Data Anak Asuh

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant Controller
    participant Model
    participant Database
    participant Storage

    Admin->>Browser: Klik "Edit" pada data anak
    Browser->>Controller: GET /admin/anak/{id}/edit
    Controller->>Model: Find anak by ID
    Model->>Database: SELECT * FROM anak WHERE id
    Database->>Model: Return anak data
    Model->>Controller: Return anak object
    Controller->>Browser: Return edit form with data
    Browser->>Admin: Tampilkan form terisi
    
    Admin->>Browser: Ubah data
    Admin->>Browser: Klik "Update"
    Browser->>Controller: PUT /admin/anak/{id}
    
    Controller->>Controller: Validate input
    
    alt Validation Success
        Controller->>Model: Update anak
        Model->>Database: UPDATE anak SET...
        Database->>Model: Success
        
        alt Foto baru diupload
            Controller->>Storage: Delete old foto
            Controller->>Storage: Store new foto
            Storage->>Controller: Return new path
            Controller->>Model: Update foto path
        end
        
        Controller->>Browser: Redirect with success
        Browser->>Admin: Tampilkan success message
    else Validation Failed
        Controller->>Browser: Return errors
        Browser->>Admin: Tampilkan error
    end
```

---

### 4.4 Sequence: Hapus Data Anak Asuh

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant Controller
    participant Model
    participant Database
    participant Storage

    Admin->>Browser: Klik "Hapus" pada data anak
    Browser->>Admin: Tampilkan konfirmasi
    Admin->>Browser: Klik "Ya, Hapus"
    Browser->>Controller: DELETE /admin/anak/{id}
    
    Controller->>Model: Find anak by ID
    Model->>Database: SELECT * FROM anak WHERE id
    Database->>Model: Return anak data
    
    Controller->>Storage: Delete foto file
    Storage->>Controller: File deleted
    
    Controller->>Model: Delete anak
    Model->>Database: DELETE FROM anak WHERE id
    
    Note over Database: CASCADE DELETE:<br/>- riwayat_kesehatan<br/>- riwayat_pendidikan<br/>- dokumen_anak
    
    Database->>Model: Success
    Model->>Controller: Deleted
    Controller->>Browser: Redirect with success
    Browser->>Admin: Tampilkan success message
```

---

### 4.5 Sequence: Tambah Riwayat Kesehatan

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant Controller
    participant Model
    participant Database

    Admin->>Browser: Buka detail anak
    Admin->>Browser: Klik "Tambah Riwayat Kesehatan"
    Browser->>Controller: GET /admin/anak/{id}/riwayat-kesehatan/create
    Controller->>Browser: Return form modal
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Isi kategori & keterangan
    Admin->>Browser: Klik "Simpan"
    Browser->>Controller: POST /admin/riwayat-kesehatan
    
    Controller->>Controller: Validate input
    
    alt Valid
        Controller->>Model: Create riwayat
        Model->>Database: INSERT INTO riwayat_kesehatan
        Database->>Model: Success
        Controller->>Browser: Return success JSON
        Browser->>Admin: Update list & close modal
    else Invalid
        Controller->>Browser: Return errors
        Browser->>Admin: Tampilkan error
    end
```

---

### 4.6 Sequence: Catat Donasi

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

### 4.7 Sequence: Catat Pengeluaran

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant Controller
    participant Model
    participant Database

    Admin->>Browser: Pilih menu "Transaksi Keuangan"
    Admin->>Browser: Klik "Tambah Pengeluaran"
    Browser->>Controller: GET /admin/transaksi/create
    Controller->>Database: SELECT * FROM kategori_transaksi
    Database->>Controller: Return categories
    Controller->>Browser: Return form with categories
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Pilih kategori
    Admin->>Browser: Input nominal & keterangan
    Admin->>Browser: Klik "Simpan"
    
    Browser->>Controller: POST /admin/transaksi
    Controller->>Controller: Validate input
    
    alt Valid
        Controller->>Database: BEGIN TRANSACTION
        
        Controller->>Model: Create transaksi (KELUAR)
        Model->>Database: INSERT INTO transaksi_kas
        
        Controller->>Database: UPDATE kas SET saldo = saldo - nominal
        
        Database->>Controller: COMMIT
        Controller->>Browser: Redirect with success
        Browser->>Admin: Success message & updated saldo
    else Invalid
        Controller->>Browser: Return errors
        Browser->>Admin: Tampilkan error
    end
```

---

### 4.8 Sequence: Generate Laporan Excel

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant LaporanController
    participant Database
    participant ExcelExport
    participant PhpSpreadsheet

    Admin->>Browser: Pilih menu "Laporan"
    Browser->>LaporanController: GET /admin/laporan
    LaporanController->>Browser: Return form laporan
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Input start_date & end_date
    Admin->>Browser: Pilih format "Excel"
    Admin->>Browser: Klik "Download Laporan"
    
    Browser->>LaporanController: POST /admin/laporan/export
    LaporanController->>LaporanController: Validate dates
    
    LaporanController->>Database: Query transaksi by date range
    Database->>LaporanController: Return transaksi data
    
    LaporanController->>LaporanController: Calculate totals
    
    LaporanController->>ExcelExport: new LaporanKeuanganExport(data, dates)
    ExcelExport->>ExcelExport: Build headings
    ExcelExport->>ExcelExport: Map data rows
    ExcelExport->>ExcelExport: Add total rows
    
    ExcelExport->>PhpSpreadsheet: Apply styles
    PhpSpreadsheet->>PhpSpreadsheet: Set colors, borders, fonts
    PhpSpreadsheet->>PhpSpreadsheet: Format numbers
    PhpSpreadsheet->>PhpSpreadsheet: Set column widths
    
    PhpSpreadsheet->>ExcelExport: Return styled spreadsheet
    ExcelExport->>LaporanController: Return Excel file
    
    LaporanController->>Browser: Download laporan_keuangan.xlsx
    Browser->>Admin: File downloaded
```

---

### 4.9 Sequence: Generate Laporan PDF

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant LaporanController
    participant Database
    participant BladeView
    participant DomPDF

    Admin->>Browser: Input periode & pilih "PDF"
    Admin->>Browser: Klik "Download Laporan"
    
    Browser->>LaporanController: POST /admin/laporan/export
    LaporanController->>Database: Query transaksi
    Database->>LaporanController: Return data
    
    LaporanController->>LaporanController: Calculate totals
    
    LaporanController->>BladeView: Load laporan/pdf.blade.php
    BladeView->>BladeView: Render HTML with data
    BladeView->>LaporanController: Return HTML
    
    LaporanController->>DomPDF: loadView(html)
    DomPDF->>DomPDF: Convert HTML to PDF
    DomPDF->>DomPDF: Set paper A4 landscape
    DomPDF->>DomPDF: Apply CSS styling
    
    DomPDF->>LaporanController: Return PDF file
    LaporanController->>Browser: Download laporan_keuangan.pdf
    Browser->>Admin: File downloaded
```

---

### 4.10 Sequence: Upload Dokumen Anak

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant Controller
    participant Validation
    participant Storage
    participant Database

    Admin->>Browser: Buka detail anak
    Admin->>Browser: Klik "Upload Dokumen"
    Browser->>Controller: GET /admin/anak/{id}/dokumen
    Controller->>Browser: Return upload form
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Pilih jenis dokumen
    Admin->>Browser: Choose file
    Admin->>Browser: Input keterangan
    Admin->>Browser: Klik "Upload"
    
    Browser->>Controller: POST /admin/dokumen
    Controller->>Validation: Validate file
    
    alt Validation Failed
        Validation->>Browser: Return error
        Browser->>Admin: "File terlalu besar / format salah"
    else Validation Success
        Validation->>Controller: File valid
        
        Controller->>Storage: Store file
        Storage->>Controller: Return file path
        
        Controller->>Database: INSERT INTO dokumen_anak
        Database->>Controller: Success
        
        Controller->>Browser: Return success
        Browser->>Admin: "Dokumen berhasil diupload"
    end
```

---

### 4.11 Sequence: Upload Foto Kegiatan (Gallery)

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant GalleryController
    participant Storage
    participant Database

    Admin->>Browser: Pilih menu "Gallery"
    Admin->>Browser: Klik "Upload Foto"
    Browser->>GalleryController: GET /admin/gallery/create
    GalleryController->>Database: SELECT * FROM anak (for dropdown)
    Database->>GalleryController: Return anak list
    GalleryController->>Browser: Return form with anak options
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Input judul & deskripsi
    Admin->>Browser: Pilih anak (optional)
    Admin->>Browser: Upload foto
    Admin->>Browser: Input tanggal kegiatan
    Admin->>Browser: Klik "Simpan"
    
    Browser->>GalleryController: POST /admin/gallery
    GalleryController->>GalleryController: Validate input
    
    alt Valid
        GalleryController->>Storage: Store foto
        Storage->>GalleryController: Return path
        
        GalleryController->>Database: INSERT INTO foto_kegiatan
        Database->>GalleryController: Success
        
        GalleryController->>Browser: Redirect to gallery
        Browser->>Admin: Tampilkan foto baru di gallery
    else Invalid
        GalleryController->>Browser: Return errors
        Browser->>Admin: Tampilkan error
    end
```

---

### 4.12 Sequence: Lihat Dashboard

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant DashboardController
    participant Database

    Admin->>Browser: Login success
    Browser->>DashboardController: GET /admin/dashboard
    
    DashboardController->>Database: COUNT anak WHERE status = AKTIF
    Database->>DashboardController: Return total_anak
    
    DashboardController->>Database: COUNT pengurus
    Database->>DashboardController: Return total_pengurus
    
    DashboardController->>Database: SELECT saldo FROM kas
    Database->>DashboardController: Return total_saldo
    
    DashboardController->>Database: SUM donasi WHERE month = current
    Database->>DashboardController: Return donasi_bulan_ini
    
    DashboardController->>Database: SUM pengeluaran WHERE month = current
    Database->>DashboardController: Return pengeluaran_bulan_ini
    
    DashboardController->>Database: Query donasi per bulan (12 months)
    Database->>DashboardController: Return chart_data
    
    DashboardController->>Database: Query pengeluaran per kategori
    Database->>DashboardController: Return kategori_data
    
    DashboardController->>Database: Query transaksi terakhir (5 rows)
    Database->>DashboardController: Return recent_transaksi
    
    DashboardController->>Browser: Return dashboard view with all data
    Browser->>Admin: Tampilkan dashboard lengkap
```

---

### 4.13 Sequence: Kelola Data Pengurus

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant PengurusController
    participant Database

    Admin->>Browser: Pilih menu "Pengurus"
    Browser->>PengurusController: GET /admin/pengurus
    PengurusController->>Database: SELECT * FROM pengurus
    Database->>PengurusController: Return pengurus list
    PengurusController->>Browser: Return index view
    Browser->>Admin: Tampilkan list pengurus
    
    Admin->>Browser: Klik "Tambah Pengurus"
    Browser->>PengurusController: GET /admin/pengurus/create
    PengurusController->>Browser: Return form
    Browser->>Admin: Tampilkan form
    
    Admin->>Browser: Isi data pengurus
    Admin->>Browser: Klik "Simpan"
    Browser->>PengurusController: POST /admin/pengurus
    
    PengurusController->>PengurusController: Validate input
    
    alt Valid
        PengurusController->>Database: INSERT INTO pengurus
        Database->>PengurusController: Success
        PengurusController->>Browser: Redirect with success
        Browser->>Admin: "Data pengurus berhasil disimpan"
    else Invalid
        PengurusController->>Browser: Return errors
        Browser->>Admin: Tampilkan error
    end
```

---

### 4.14 Sequence: Export Data Anak (Excel)

```mermaid
sequenceDiagram
    actor Admin
    participant Browser
    participant AnakController
    participant AnakExport
    participant Database
    participant PhpSpreadsheet

    Admin->>Browser: Pilih menu "Data Anak"
    Admin->>Browser: Klik "Export Excel"
    Browser->>AnakController: GET /admin/anak/export-excel
    
    AnakController->>AnakExport: new AnakExport()
    AnakExport->>Database: Query all anak with relationships
    Database->>AnakExport: Return anak data (22 fields)
    
    AnakExport->>AnakExport: Build comprehensive headings
    AnakExport->>AnakExport: Map 22 fields per row
    AnakExport->>AnakExport: Format dates & calculate age
    AnakExport->>AnakExport: Concatenate riwayat data
    
    AnakExport->>PhpSpreadsheet: Apply professional styling
    PhpSpreadsheet->>PhpSpreadsheet: Green header, borders
    PhpSpreadsheet->>PhpSpreadsheet: Set column widths
    PhpSpreadsheet->>PhpSpreadsheet: Merge title cells
    
    PhpSpreadsheet->>AnakExport: Return styled Excel
    AnakExport->>AnakController: Return Excel file
    
    AnakController->>Browser: Download data_anak.xlsx
    Browser->>Admin: File downloaded (22 columns)
```

---



```mermaid
flowchart TD
    Start([START]) --> Login[Login Admin]
    Login --> Menu[Pilih Menu Data Anak]
    Menu --> Tambah[Klik Tambah Anak]
    Tambah --> Form[Isi Form Data Anak]
    Form --> Upload{Upload Foto?}
    
    Upload -->|Ya| UploadFile[Upload File Foto]
    Upload -->|Tidak| Validate
    UploadFile --> Validate
    
    Validate[Validasi Data]
    Validate --> Valid{Data Valid?}
    
    Valid -->|Tidak| Error[Tampilkan Error]
    Error --> Form
    
    Valid -->|Ya| Save[Simpan ke Database]
    Save --> Success[Success Message]
    Success --> Redirect[Redirect ke Detail]
    Redirect --> End([END])

    style Start fill:#4CAF50,stroke:#2E7D32,stroke-width:2px,color:#fff
    style End fill:#F44336,stroke:#C62828,stroke-width:2px,color:#fff
    style Valid fill:#FF9800,stroke:#E65100,stroke-width:2px
    style Upload fill:#2196F3,stroke:#1565C0,stroke-width:2px
    style Save fill:#9C27B0,stroke:#6A1B9A,stroke-width:2px,color:#fff
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

---

## 8. DATA FLOW DIAGRAM (DFD) - LEVEL 1

```mermaid
graph TB
    Admin((👤 Admin))
    
    subgraph DFD["DATA FLOW DIAGRAM - LEVEL 1"]
        P1[1.0<br/>Kelola Data Anak]
        P2[2.0<br/>Kelola Riwayat]
        P3[3.0<br/>Kelola Keuangan]
        P4[4.0<br/>Generate Laporan]
        
        D1[(D1: ANAK)]
        D2[(D2: RIWAYAT)]
        D3[(D3: DONASI)]
        D4[(D4: TRANSAKSI)]
    end
    
    Admin -->|Data Anak| P1
    P1 -->|Simpan| D1
    D1 -->|Read| P1
    P1 -->|Data Anak| Admin
    
    Admin -->|Data Riwayat| P2
    P2 -->|Simpan| D2
    D2 -->|Read| P2
    
    Admin -->|Data Donasi/Pengeluaran| P3
    P3 -->|Simpan Donasi| D3
    P3 -->|Simpan Transaksi| D4
    D3 -->|Read| P3
    D4 -->|Read| P3
    
    Admin -->|Request Laporan| P4
    D3 -->|Data Donasi| P4
    D4 -->|Data Transaksi| P4
    P4 -->|Laporan Excel/PDF| Admin

    style Admin fill:#4CAF50,stroke:#2E7D32,stroke-width:3px,color:#fff
    style P1 fill:#2196F3,stroke:#1565C0,stroke-width:2px,color:#fff
    style P2 fill:#2196F3,stroke:#1565C0,stroke-width:2px,color:#fff
    style P3 fill:#2196F3,stroke:#1565C0,stroke-width:2px,color:#fff
    style P4 fill:#2196F3,stroke:#1565C0,stroke-width:2px,color:#fff
    style D1 fill:#FF9800,stroke:#E65100,stroke-width:2px
    style D2 fill:#FF9800,stroke:#E65100,stroke-width:2px
    style D3 fill:#FF9800,stroke:#E65100,stroke-width:2px
    style D4 fill:#FF9800,stroke:#E65100,stroke-width:2px
```

---

## 9. STATE DIAGRAM - STATUS ANAK

```mermaid
stateDiagram-v2
    [*] --> Pendaftaran: Anak masuk panti
    
    Pendaftaran --> Aktif: Data lengkap & disetujui
    
    Aktif --> Aktif: Update data
    Aktif --> Keluar: Anak keluar dari panti
    
    Keluar --> [*]: Arsip data
    
    note right of Aktif
        Status: AKTIF
        - Mendapat fasilitas panti
        - Data dapat diupdate
        - Riwayat tercatat
    end note
    
    note right of Keluar
        Status: KELUAR
        - Data diarsip
        - Tanggal keluar tercatat
        - Riwayat tetap tersimpan
    end note
```

---

## 10. COMPONENT DIAGRAM - EXPORT SYSTEM

```mermaid
graph TB
    subgraph EXPORT["EXPORT SYSTEM"]
        Controller[LaporanController]
        
        subgraph Excel["Excel Export"]
            ExcelExport[LaporanKeuanganExport]
            RekapExport[RekapTahunanExport]
            AnakExport[AnakExport]
        end
        
        subgraph PDF["PDF Export"]
            PDFView[laporan/pdf.blade.php]
            DomPDF[DomPDF Library]
        end
        
        subgraph Styling["Styling Components"]
            PhpSpreadsheet[PhpSpreadsheet<br/>• Colors<br/>• Borders<br/>• Fonts<br/>• Number Format]
            CSS[CSS Styling<br/>• Layout<br/>• Colors<br/>• Typography]
        end
    end
    
    Controller --> ExcelExport
    Controller --> RekapExport
    Controller --> AnakExport
    Controller --> PDFView
    
    ExcelExport --> PhpSpreadsheet
    RekapExport --> PhpSpreadsheet
    AnakExport --> PhpSpreadsheet
    
    PDFView --> CSS
    PDFView --> DomPDF
    
    PhpSpreadsheet --> Output1[Excel File<br/>.xlsx]
    DomPDF --> Output2[PDF File<br/>.pdf]

    style Controller fill:#4CAF50,stroke:#2E7D32,stroke-width:2px,color:#fff
    style Excel fill:#2196F3,stroke:#1565C0,stroke-width:2px
    style PDF fill:#FF9800,stroke:#E65100,stroke-width:2px
    style Styling fill:#9C27B0,stroke:#6A1B9A,stroke-width:2px
    style Output1 fill:#00BCD4,stroke:#0097A7,stroke-width:2px
    style Output2 fill:#F44336,stroke:#C62828,stroke-width:2px,color:#fff
```

---

## CARA MENGGUNAKAN DIAGRAM MERMAID

### 1. Di GitHub/GitLab
File ini sudah siap! Cukup push ke repository dan diagram akan otomatis ter-render.

### 2. Di Mermaid Live Editor
- Buka: https://mermaid.live/
- Copy salah satu code block mermaid
- Paste di editor
- Export sebagai PNG/SVG/PDF

### 3. Di VS Code
- Install extension: **"Markdown Preview Mermaid Support"**
- Buka file `erd.md`
- Tekan `Ctrl+Shift+V` untuk preview
- Semua diagram akan ter-render

### 4. Di Notion
- Buat block "/code"
- Pilih language "Mermaid"
- Paste code mermaid
- Diagram otomatis render

### 5. Export untuk Laporan
- Gunakan Mermaid Live Editor
- Export sebagai PNG (high resolution)
- Insert ke Word/PowerPoint

---

**Semua diagram siap digunakan untuk laporan capstone project!** 🎓