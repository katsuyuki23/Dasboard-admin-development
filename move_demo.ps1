$source = "c:\xampp\htdocs\caps3v1\caps3"
$dest = "c:\xampp\htdocs\caps3-demo"

# Create destination folders
New-Item -ItemType Directory -Force -Path "$dest\html"
New-Item -ItemType Directory -Force -Path "$dest\css"
New-Item -ItemType Directory -Force -Path "$dest\js"
New-Item -ItemType Directory -Force -Path "$dest\assets"
New-Item -ItemType Directory -Force -Path "$dest\plugins"

# Copy HTML files
Copy-Item -Path "$source\html_views\*" -Destination "$dest\html" -Recurse -Force

# Copy Assets
Copy-Item -Path "$source\public\css\*" -Destination "$dest\css" -Recurse -Force
Copy-Item -Path "$source\public\js\*" -Destination "$dest\js" -Recurse -Force
Copy-Item -Path "$source\public\assets\*" -Destination "$dest\assets" -Recurse -Force
Copy-Item -Path "$source\public\plugins\*" -Destination "$dest\plugins" -Recurse -Force

# Create Redirect Index
$htmlContent = @"
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="0; url=html/auth/login.html">
</head>
<body>
    <p>Redirecting to <a href="html/auth/login.html">login page</a>...</p>
</body>
</html>
"@
Set-Content -Path "$dest\index.html" -Value $htmlContent

Write-Host "Demo moved successfully to $dest"
