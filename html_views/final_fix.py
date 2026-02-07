import os
import re

html_dir = r"c:\xampp\htdocs\caps3v1\caps3\html_views"

def fix_all_issues(content):
    """Fix ALL HTML issues in one pass"""
    
    # Issue 1: Re-add comment tags that were stripped
    content = re.sub(
        r'^(\s*)Bootstrap 5 CDN\s*-\s*$',
        r'\1<!-- Bootstrap 5 CDN -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'^(\s*)Font Awesome\s*-\s*$',
        r'\1<!-- Font Awesome -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'^(\s*)Custom CSS\s*-\s*$',
        r'\1<!-- Custom CSS -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'(<div class="d-flex">) Wrapper[^<]*-\s*$',
        r'\1 <!-- Wrapper -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'^(\s*)Sidebar\s*-\s*$',
        r'\1<!-- Sidebar -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'^(\s*)Key Metrics Row\s*-\s*$',
        r'\1<!-- Key Metrics Row -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'^(\s*)Main Content Wrapper\s*-\s*$',
        r'\1<!-- Main Content Wrapper -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'^(\s*)Topbar\s*-\s*$',
        r'\1<!-- Topbar -->',
        content,
        flags=re.MULTILINE
    )
    content = re.sub(
        r'^(\s*)Scrollable Page Content\s*-\s*$',
        r'\1<!-- Scrollable Page Content -->',
        content,
        flags=re.MULTILINE
    )
    
    # Issue 2: Fix nav-link class attributes with orphan text
    # Replace: class="nav-link  'dashboard') " with class="nav-link"
    content = re.sub(
        r'class="nav-link\s+[\'"][^\'"]*?[\'"][^"]*"',
        'class="nav-link"',
        content
    )
    content = re.sub(
        r'class="nav-link\s+[^"]*?\)[^"]*?"',
        'class="nav-link"',
        content
    )
    
    # Issue 3: Clean up any remaining Blade artifacts
    content = re.sub(r"'[\w.]+'\)\s*\?\s*'active'\s*:\s*''", '', content)
    
    return content

def main():
    print(f"\n{'='*70}")
    print(f"  FINAL FIX: Restoring HTML Comments \u0026 Cleaning Attributes")
    print(f"{'='*70}\n")
    
    count = 0
    fixed = 0
    
    for root, dirs, files in os.walk(html_dir):
        for file in files:
            if file.endswith('.html'):
                count += 1
                filepath = os.path.join(root, file)
                rel_path = os.path.relpath(filepath, html_dir)
                
                try:
                    with open(filepath, 'r', encoding='utf-8') as f:
                        content = f.read()
                    
                    original = content
                    content = fix_all_issues(content)
                    
                    if content != original:
                        with open(filepath, 'w', encoding='utf-8') as f:
                            f.write(content)
                        fixed += 1
                        print(f"[{count}] ✓ Fixed: {rel_path}")
                    else:
                        print(f"[{count}] - OK Already: {rel_path}")
                        
                except Exception as e:
                    print(f"[{count}] ✗ Error: {rel_path} - {e}")
    
    print(f"\n{'='*70}")
    print(f"  COMPLETE!")
    print(f"  Total: {count} | Fixed: {fixed}")
    print(f"{'='*70}\n")
    print("✅ Silakan refresh browser dan test lagi!")

if __name__ == "__main__":
    main()
