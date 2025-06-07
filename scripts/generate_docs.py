import os
import re
from pathlib import Path

BASE_DIR = Path('app')

def parse_php(file_path):
    text = file_path.read_text(encoding='utf-8', errors='ignore')
    namespace_match = re.search(r'namespace\s+([^;]+);', text)
    class_match = re.search(r'(?:class|interface|trait)\s+(\w+)', text)
    methods = re.findall(r'function\s+(\w+)\s*\(', text)
    return {
        'path': file_path.as_posix(),
        'namespace': namespace_match.group(1) if namespace_match else None,
        'class': class_match.group(1) if class_match else None,
        'methods': methods,
    }

def main():
    entries = []
    for php_file in sorted(BASE_DIR.rglob('*.php')):
        entries.append(parse_php(php_file))
    lines = ['# Documentación del código', '']
    for entry in entries:
        lines.append(f"## {entry['class'] or 'Archivo'}: `{entry['path']}`")
        if entry['namespace']:
            lines.append(f"**Namespace**: `{entry['namespace']}`")
        if entry['class']:
            lines.append(f"**Clase**: `{entry['class']}`")
        if entry['methods']:
            lines.append('**Métodos**:')
            for m in entry['methods']:
                lines.append(f"- `{m}`")
        lines.append('')
    Path('docs/CodeBase.md').write_text('\n'.join(lines), encoding='utf-8')

if __name__ == '__main__':
    main()
