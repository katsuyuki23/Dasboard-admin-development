import os
import re

html_dir = r"c:\xampp\htdocs\caps3v1\caps3\html_views"

def fix_broken_html(content):
    """Fix all HTML issues from cleanup script"""
    
    # Fix 1: Broken HTML comments - missing closing -->
    # Pattern: <!-- text - (newline or end of comment without -->)
    content = re.sub(r'<!--\s*([^-]+?)\s*-\s*(?=\n|$)', r'<!-- \1 -->', content)
    
    # Fix 2: Completely broken comment tags
    content = re.sub(r'<!--\s*Bootstrap\s+5\s+CDN\s*-\s*$', '<!-- Bootstrap 5 CDN -->', content, flags=re.MULTILINE)
    content = re.sub(r'<!--\s*Font\s+Awesome\s*-\s*$', '<!-- Font Awesome -->', content, flags=re.MULTILINE)
    content = re.sub(r'<!--\s*Custom\s+CSS\s*-\s*$', '<!-- Custom CSS -->', content, flags=re.MULTILINE)
    content = re.sub(r'<!--\s*Wrapper[^>]*-\s*$', '<!-- Wrapper -->', content, flags=re.MULTILINE)
    content = re.sub(r'<!--\s*Sidebar\s*-\s*$', '<!-- Sidebar -->', content, flags=re.MULTILINE)
    content = re.sub(r'<!--\s*Key\s+Metrics[^>]*-\s*$', '<!-- Key Metrics Row -->', content, flags=re.MULTILINE)
    
    # Fix 3: Nav links with broken attributes
    # Remove orphan text like "'dashboard') " from nav-link class
    content = re.sub(r'class="nav-link\s+[\'"][\w.()]+[\'"][^"]*"', 'class="nav-link"', content)
    content = re.sub(r'class="nav-link\s+[^"]*\'\w+\'\)[^"]*"', 'class="nav-link"', content)
    
    return content

def process_file(filepath):
    """Process single file"""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        fixed_content = fix_broken_html(content)
        
        if fixed_content != content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(fixed_content)
            return True
        return False
    except Exception as e:
        print(f"Error: {e}")
        return False

def main():
    print(f"\n{'='*70}")
    print(f"  FIXING ALL HTML SYNTAX ERRORS")
    print(f"{'='*70}\n")
    
    count = 0
    fixed = 0
    
    for root, dirs, files in os.walk(html_dir):
        for file in files:
            if file.endswith('.html'):
                count += 1
                filepath = os.path.join(root, file)
                rel_path = os.path.relpath(filepath, html_dir)
                
                if process_file(filepath):
                    fixed += 1
                    print(f"[{count}/{count}] ✓ Fixed: {rel_path}")
                else:
                    print(f"[{count}/{count}] - OK: {rel_path}")
    
    print(f"\n{'='*70}")
    print(f"  COMPLETE!")
    print(f"  Total files: {count}")
    print(f"  Files fixed: {fixed}")
    print(f"{'='*70}\n")

if __name__ == "__main__":
    main()
