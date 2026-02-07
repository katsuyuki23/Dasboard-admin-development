import os
import re

html_dir = r"c:\xampp\htdocs\caps3v1\caps3\html_views"

def fix_javascript_blade_remnants(content):
    """Fix JavaScript syntax errors from Blade remnants"""
    
    # Fix 1: Remove array map PHP/Blade remnants in data arrays
    # Pattern: data: [numbers] { return ... } 
    content = re.sub(
        r'(data:\s*\[[^\]]+\])\s*\{[^}]+\}\s*,\s*,',
        r'\1,',
        content
    )
    
    # Fix 2: Remove extra closing parenthesis after arrays
    # Pattern: labels: [...]),  → labels: [...],
    content = re.sub(
        r'(labels:\s*\[[^\]]+\])\s*\)',
        r'\1',
        content
    )
    content = re.sub(
        r'(data:\s*\[[^\]]+\])\s*\)',
        r'\1',
        content
    )
    
    # Fix 3: Clean @json() remnants
    content = re.sub(r'@json\([^)]*\)', '[]', content)
    
    # Fix 4: Remove PHP arrow function remnants
    content = re.sub(r'=>\s*item\s*', '', content)
    content = re.sub(r'item\.[\w]+', '0', content)
    
    # Fix 5: Clean map function remnants  
    content = re.sub(r'\.map\([^)]*\)', '', content)
    
    return content

def main():
    print(f"\n{'='*70}")
    print(f"  FIXING JAVASCRIPT SYNTAX ERRORS")
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
                    content = fix_javascript_blade_remnants(content)
                    
                    if content != original:
                        with open(filepath, 'w', encoding='utf-8') as f:
                            f.write(content)
                        fixed += 1
                        print(f"[{count}] ✓ Fixed JS: {rel_path}")
                    else:
                        print(f"[{count}] - OK: {rel_path}")
                        
                except Exception as e:
                    print(f"[{count}] ✗ Error: {rel_path} - {e}")
    
    print(f"\n{'='*70}")
    print(f"  COMPLETE!")
    print(f"  Total: {count} | Fixed: {fixed}")
    print(f"{'='*70}\n")

if __name__ == "__main__":
    main()
