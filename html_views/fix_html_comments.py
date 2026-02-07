import os
import re

html_dir = r"c:\xampp\htdocs\caps3v1\caps3\html_views"

def fix_html_comments(content):
    """Fix broken HTML comment tags"""
    
    # Fix incomplete comment opening tags
    # Pattern: <!-- something (without closing -->)
    # This happens when our script removed content between <!-- and -->
    
    # Fix: <!-- Bootstrap 5 CDN - → <!-- Bootstrap 5 CDN -->
    content = re.sub(r'\u003c!--\s*([^-\u003e]+?)\s*-\s*\n', r'<!-- \1 -->\n', content)
    
    # Fix standalone orphan dashes at end of comments
    content = re.sub(r'\u003c!--\s*([^-\u003e]+?)\s*-\s*$', r'<!-- \1 -->', content, flags=re.MULTILINE)
    
    return content

def process_all_html_files():
    """Fix all HTML files"""
    
    print(f"\n{'='*70}")
    print(f"  FIXING HTML COMMENT SYNTAX ERRORS")
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
                    
                    # Check if file has broken comments
                    if re.search(r'\u003c!--[^-]*-\s*\n', content):
                        original_content = content
                        content = fix_html_comments(content)
                        
                        # Write back
                        with open(filepath, 'w', encoding='utf-8') as f:
                            f.write(content)
                        
                        fixed += 1
                        print(f"[{count}] Fixed: {rel_path}")
                    else:
                        print(f"[{count}] OK: {rel_path}")
                    
                except Exception as e:
                    print(f"[{count}] Error: {rel_path} - {e}")
    
    print(f"\n{'='*70}")
    print(f"  FIX COMPLETE!")
    print(f"  Files checked: {count}")
    print(f"  Files fixed: {fixed}")
    print(f"{'='*70}\n")

if __name__ == "__main__":
    process_all_html_files()
