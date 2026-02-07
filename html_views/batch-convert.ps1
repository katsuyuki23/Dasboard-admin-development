# Automation Script: Blade to HTML Converter
# Converts all 44 Blade files to static HTML

# Set paths  
$viewsPath = "c:\xampp\htdocs\caps3v1\caps3\resources\views"
$outputPath = "c:\xampp\htdocs\caps3v1\caps3\html_views"
$cssPath = "c:\xampp\htdocs\caps3v1\caps3\public\css\custom.css"

# Read layout and CSS
$layout = Get-Content "$viewsPath\layouts\app.blade.php" -Raw

# File mapping: source => destination
$files = @{
    # Auth
    "auth\login.blade.php" = "auth\login.html"
    "auth\register.blade.php" = "auth\register.html"  
    "auth\passwords\email.blade.php" = "auth\passwords\email.html"
    "auth\passwords\reset.blade.php" = "auth\passwords\reset.html"
    "auth\passwords\confirm.blade.php" = "auth\passwords\confirm.html"
    "auth\verify.blade.php" = "auth\verify.html"
    
    # Dashboard
    "dashboard\index.blade.php" = "dashboard\index.html"
    "home.blade.php" = "home.html"
    "welcome.blade.php" = "welcome.html"
    
    # Analytics
    "analytics\dashboard.blade.php" = "analytics\dashboard.html"
    
    # Anak
    "anak\index.blade.php" = "anak\index.html"
    "anak\create.blade.php" = "anak\create.html"
    "anak\edit.blade.php" = "anak\edit.html"
    "anak\show.blade.php" = "anak\show.html"
    "anak\pdf.blade.php" = "anak\pdf.html"
    "anak\_form.blade.php" = "anak\_form.html"
    "anak\growth\create.blade.php" = "anak\growth\create.html"
    
    # Pengurus
    "pengurus\index.blade.php" = "pengurus\index.html"
    "pengurus\create.blade.php" = "pengurus\create.html"
    "pengurus\edit.blade.php" = "pengurus\edit.html"
    "pengurus\show.blade.php" = "pengurus\show.html"
    "pengurus\_form.blade.php" = "pengurus\_form.html"
    
    # Keuangan
    "keuangan\donasi\index.blade.php" = "keuangan\donasi\index.html"
    "keuangan\donasi\create.blade.php" = "keuangan\donasi\create.html"
    "keuangan\donatur\index.blade.php" = " keuangan\donatur\index.html"
    "keuangan\donatur\create.blade.php" = "keuangan\donatur\create.html"
    "keuangan\donatur\edit.blade.php" = "keuangan\donatur\edit.html"
    "keuangan\kas\index.blade.php" = "keuangan\kas\index.html"
    "keuangan\kas\create.blade.php" = "keuangan\kas\create.html"
    "keuangan\kategori\index.blade.php" = "keuangan\kategori\index.html"
    "keuangan\transaksi\index.blade.php" = "keuangan\transaksi\index.html"
    "keuangan\transaksi\create.blade.php" = "keuangan\transaksi\create.html"
    "keuangan\transaksi\pengeluaran.blade.php" = "keuangan\transaksi\pengeluaran.html"
    "keuangan\transaksi\index_pengeluaran.blade.php" = "keuangan\transaksi\index_pengeluaran.html"
    "keuangan\transaksi\edit_pengeluaran.blade.php" = "keuangan\transaksi\edit_pengeluaran.html"
    
    # Laporan
    "laporan\index.blade.php" = "laporan\index.html"
    "laporan\pdf.blade.php" = "laporan\pdf.html"
    "laporan\rekap_excel.blade.php" = "laporan\rekap_excel.html"
    
    # Gallery
    "gallery\index.blade.php" = "gallery\index.html"
    "gallery\create.blade.php" = "gallery\create.html"
    
    # Profile
    "profile\show.blade.php" = "profile\show.html"
    "profile\edit.blade.php" = "profile\edit.html"
    "profile\change-password.blade.php" = "profile\change-password.html"
}

function Convert-BladeSyntax {
    param([string]$content)
    
    # Remove Blade directives
    $content = $content -replace '@extends\([^)]+\)', ''
    $content = $content -replace '@section\([^)]+\)', ''
    $content = $content -replace '@endsection', ''
    $content = $content -replace '@push\([^)]+\)', ''
    $content = $content -replace '@endpush', ''
   $content = $content -replace '@csrf', ''
    $content = $content -replace '@method\([^)]+\)', ''
    
    # Replace routes with #
    $content = $content -replace '\{\{\s*route\([^}]+\)\s*\}\}', '#'
    
    # Replace assets
    $content = $content -replace '\{\{\s*asset\([''"]([^''"]+)[''"]\)\s*\}\}', '../$1'
    
    # Replace Auth
    $content = $content -replace '\{\{\s*Auth::user\(\)->name\s*\}\}', 'Admin User'
    $content = $content -replace '\{\{\s*Auth::user\(\)->role\s*\}\}', 'ADMIN'
    
    # Simple foreach/if removal (basic)
    $content = $content -replace '@if\([^)]+\)', ''
    $content = $content -replace '@endif', ''
    $content = $content -replace '@foreach\([^)]+\)', ''
    $content = $content -replace '@endforeach', ''
    $content = $content -replace '@forelse\([^)]+\)', ''
    $content = $content -replace '@empty', '<!-- Empty State -->'
    $content = $content -replace '@endforelse', ''
    $content = $content -replace '@php', '<!-- PHP Block -->'
    $content = $content -replace '@endphp', '<!-- End PHP -->'
    
    # Replace remaining {{ }} with plain text removal
    $content = $content -replace '\{\{([^}]+)\}\}', '$1'
    
    return $content
}

Write-Host "`n=========================================" -ForegroundColor Cyan
Write-Host "  BLADE TO HTML CONVERTER - AUTO MODE  " -ForegroundColor Cyan
Write-Host "=========================================`n" -ForegroundColor Cyan

$count = 0
$total = $files.Count

foreach ($file in $files.GetEnumerator()) {
    $count++
    $sourcePath = Join-Path $viewsPath $file.Key
    $targetPath = Join-Path $outputPath $file.Value
    
    if (!(Test-Path $sourcePath)) {
        Write-Host "[$count/$total] ⚠️  SKIP: $($file.Key) (not found)" -ForegroundColor Yellow
        continue
    }
    
    Write-Host "[$count/$total] Converting: $($file.Key)..." -ForegroundColor Green
    
    try {
        $content = Get-Content $sourcePath -Raw -ErrorAction Stop
        $converted = Convert-BladeSyntax $content
        
        # Merge with layout if extends directive found
        if ($content -match '@extends') {
            $finalHTML = $layout -replace '@yield\([''"]content[''"]\)', $converted
            $finalHTML = Convert-BladeSyntax $finalHTML
        } else {
            $finalHTML = $converted
        }
        
        # Write output
        $finalHTML | Out-File -FilePath $targetPath -Encoding UTF8 -Force
        Write-Host "           ✓ Created: $($file.Value)" -ForegroundColor DarkGreen
    }
    catch {
        Write-Host "           ✗ ERROR: $_" -ForegroundColor Red
    }
}

Write-Host "`n=========================================" -ForegroundColor Cyan
Write-Host "  CONVERSION COMPLETE!                 " -ForegroundColor Cyan
Write-Host "  Total: $count/$total files processed   " -ForegroundColor Cyan
Write-Host "  Output: $outputPath" -ForegroundColor Cyan
Write-Host "=========================================`n" -ForegroundColor Cyan
