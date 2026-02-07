import os
import re
from pathlib import Path

# Paths
views_dir = r"c:\xampp\htdocs\caps3v1\caps3\resources\views"
output_dir = r"c:\xampp\htdocs\caps3v1\caps3\html_views"
layout_file = os.path.join(views_dir, "layouts", "app.blade.php")

# Read layout
with open(layout_file, 'r', encoding='utf-8') as f:
    layout_content = f.read()

def convert_blade_to_html(content):
    """Convert Blade syntax to HTML"""
    
    # Remove Blade directives
    content = re.sub(r'@extends\([^)]+\)', '', content)
    content = re.sub(r'@section\([^)]+\)', '', content)
    content = re.sub(r'@endsection', '', content)
    content = re.sub(r'@push\([^)]+\)', '', content)
    content = re.sub(r'@endpush', '', content)
    content = re.sub(r'@csrf', '', content)
    content = re.sub(r'@method\([^)]+\)', '', content)
    
    # Replace routes with #
    content = re.sub(r'\{\{\s*route\([^}]+\)\s*\}\}', '#', content)
    
    # Replace assets
    content = re.sub(r'\{\{\s*asset\([\'"]([^\'"]+)[\'"]\)\s*\}\}', r'../\1', content)
    
    # Replace Auth
    content = re.sub(r'\{\{\s*Auth::user\(\)->name\s*\}\}', 'Admin User', content)
    content = re.sub(r'\{\{\s*Auth::user\(\)->role\s*\}\}', 'ADMIN', content)
    content = re.sub(r'\{\{\s*date\([^}]+\)\s*\}\}', '2026', content)
    
    # Remove control structures (simple approach)
    content = re.sub(r'@if\([^)]+\)', '', content)
    content = re.sub(r'@elseif\([^)]+\)', '', content)
    content = re.sub(r'@else', '', content)
    content = re.sub(r'@endif', '', content)
    content = re.sub(r'@foreach\([^)]+\)', '', content)
    content = re.sub(r'@endforeach', '', content)
    content = re.sub(r'@forelse\([^)]+\)', '<!-- Sample Data -->', content)
    content = re.sub(r'@empty', '<!-- No Data -->', content)
    content = re.sub(r'@endforelse', '', content)
    content = re.sub(r'@php', '<!-- PHP Block -->', content)
    content = re.sub(r'@endphp', '<!-- End PHP -->', content)
    content = re.sub(r'@auth', '', content)
    content = re.sub(r'@endauth', '', content)
    
    # Replace {{ }} with content
    content = re.sub(r'\{\{([^}]+)\}\}', r'\1', content)
    
    # Replace {!! !!} with content
    content = re.sub(r'\{!!([^}]+)!!\}', r'\1', content)
    
    return content

def process_file(blade_path, html_path):
    """Process a single Blade file"""
    try:
        with open(blade_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Check if it extends layout
        if '@extends' in content:
            # Merge with layout
            converted_content = convert_blade_to_html(content)
            final_html = re.sub(r'@yield\([\'"]content[\'"]\)', converted_content, layout_content)
            final_html = convert_blade_to_html(final_html)
        else:
            # Standalone file
            final_html = convert_blade_to_html(content)
        
        # Ensure directory exists
        os.makedirs(os.path.dirname(html_path), exist_ok=True)
        
        # Write output
        with open(html_path, 'w', encoding='utf-8') as f:
            f.write(final_html)
        
        return True
    except Exception as e:
        print(f"Error processing {blade_path}: {e}")
        return False

# Get all blade files
blade_files = []
for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php') and 'layouts' not in root:
            blade_path = os.path.join(root, file)
            rel_path = os.path.relpath(blade_path, views_dir)
            html_path = os.path.join(output_dir, rel_path.replace('.blade.php', '.html'))
            blade_files.append((blade_path, html_path, rel_path))

# Process all files
print(f"\n{'='*60}")
print(f"  BLADE TO HTML CONVERTER")
print(f"{'='*60}\n")
print(f"Total files to convert: {len(blade_files)}\n")

success_count = 0
for i, (blade_path, html_path, rel_path) in enumerate(blade_files, 1):
    print(f"[{i}/{len(blade_files)}] Converting: {rel_path}")
    if process_file(blade_path, html_path):
        print(f"           ✓ Created: {os.path.basename(html_path)}")
        success_count += 1
    else:
        print(f"           ✗ FAILED")

print(f"\n{'='*60}")
print(f"  CONVERSION COMPLETE!")
print(f"  Success: {success_count}/{len(blade_files)}")
print(f"  Output: {output_dir}")
print(f"{'='*60}\n")
