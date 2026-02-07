# PowerShell Script untuk Konversi Blade ke HTML
# Usage: .\convert-blade-to-html.ps1

$sourceDir = "c:\xampp\htdocs\caps3v1\caps3\resources\views"
$targetDir = "c:\xampp\htdocs\caps3v1\caps3\html_views"
$layoutFile = "$sourceDir\layouts\app.blade.php"

# Read layout template
$layoutContent = Get-Content $layoutFile -Raw

function Convert-BladeToHTML {
    param (
        [string]$bladePath,
        [string]$htmlPath
    )
    
    Write-Host "Converting: $bladePath -> $htmlPath"
    
    $content = Get-Content $bladePath -Raw
    
    # Remove @extends and @section directives
    $content = $content -replace '@extends\(.*?\)', ''
    $content = $content -replace '@section\(.*?\)', ''
    $content = $content -replace '@endsection', ''
    $content = $content -replace '@push\(.*?\)', ''
    $content = $content -replace '@endpush', ''
    
    # Replace Blade echo syntax
    $content = $content -replace '\{\{\s*', ''
    $content = $content -replace '\s*\}\}', ''
    
    # Replace route helpers with #
    $content = $content -replace 'route\([^)]+\)', '#'
    
    # Replace asset paths with relative paths
    $content = $content -replace 'asset\([''"]([^''"]+)[''"]\)', '../$1'
    
    # Replace Auth references
    $content = $content -replace 'Auth::user\(\)->name', 'Admin User'
    $content = $content -replace 'Auth::user\(\)->role', 'ADMIN'
    
    # Remove @csrf and @method directives
    $content = $content -replace '@csrf', ''
    $content = $content -replace '@method\(.*?\)', ''
    
    # Convert @if, @foreach loops (basic conversion)
    $content = $content -replace '@if\(.*?\)', ''
    $content = $content -replace '@endif', ''
    $content = $content -replace '@foreach\(.*?\)', ''
    $content = $content -replace '@endforeach', ''
    $content = $content -replace '@forelse\(.*?\)', ''
    $content = $content -replace '@empty', ''
    $content = $content -replace '@endforelse', ''
    
    # Merge with layout if content has @extends
    if ($content -match '@extends') {
        $finalHTML = $layoutContent -replace '@yield\(''content''\)', $content
    } else {
        $finalHTML = $content
    }
    
    # Write to file
    $finalHTML | Out-File -FilePath $htmlPath -Encoding UTF8
    Write-Host "✓ Created: $htmlPath`n"
}

# Create all directories
$dirs = @(
    "auth\passwords",
    "dashboard",
    "analytics",
    "anak\growth",
    "pengurus",
    "keuangan\donasi",
    "keuangan\donatur",
    "keuangan\kas",
    "keuangan\kategori",
    "keuangan\transaksi",
    "laporan",
    "gallery",
    "profile"
)

foreach ($dir in $dirs) {
    $path = Join-Path $targetDir $dir
    if (-not (Test-Path $path)) {
        New-Item -ItemType Directory -Path $path -Force | Out-Null
    }
}

Write-Host "`n=== Starting Blade to HTML Conversion ===`n"

# Here you would list all the files to convert
# Example:
# Convert-BladeToHTML "$sourceDir\auth\login.blade.php" "$targetDir\auth\login.html"
# Convert-BladeToHTML "$sourceDir\dashboard\index.blade.php" "$targetDir\dashboard\index.html"
# ... etc

Write-Host "`n=== Conversion Complete ===`n"
Write-Host "Files converted to: $targetDir"
