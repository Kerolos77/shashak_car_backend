import os

drives = ['C:\\', 'E:\\']
found = []

for drive in drives:
    print(f"Searching in {drive}...")
    for root, dirs, files in os.walk(drive):
        # skip some large common directories to speed up
        if any(p in root.lower() for p in ['appdata', 'windows', 'node_modules', 'vendor', 'python313', 'android-sdk', '.git', 'cache']):
            continue
        if 'php.exe' in files:
            p = os.path.join(root, 'php.exe')
            print(f"FOUND: {p}")
            found.append(p)

print("Done. Found paths:", found)
