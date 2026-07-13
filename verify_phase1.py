#!/usr/bin/env python3
"""
Quick checker script to verify Phase 1 foundation is complete
Usage: python3 verify_phase1.py
"""

import os
import json
from pathlib import Path

class Phase1Verifier:
    def __init__(self, project_root):
        self.root = Path(project_root)
        self.checks = {
            "services": [],
            "components": [],
            "javascript": [],
            "documentation": [],
            "routes": [],
            "views": []
        }
    
    def verify(self):
        """Run all verification checks"""
        print("🔍 Verifying Phase 1 Foundation...\n")
        
        self.check_services()
        self.check_components()
        self.check_javascript()
        self.check_documentation()
        self.check_routes()
        self.check_views()
        
        self.print_summary()
    
    def check_services(self):
        """Check backend services"""
        print("📦 Backend Services:")
        services = [
            "FileValidationService.php",
            "FileStorageService.php",
            "ProcessingProgressService.php",
            "PdfProcessingService.php"
        ]
        
        services_path = self.root / "app" / "Services"
        for service in services:
            if (services_path / service).exists():
                print(f"  ✅ {service}")
                self.checks["services"].append((service, True))
            else:
                print(f"  ❌ {service}")
                self.checks["services"].append((service, False))
    
    def check_components(self):
        """Check frontend components"""
        print("\n🎨 Frontend Components:")
        components = [
            "progress-bar.blade.php",
            "notifications.blade.php"
        ]
        
        components_path = self.root / "resources" / "views" / "components"
        for component in components:
            if (components_path / component).exists():
                print(f"  ✅ {component}")
                self.checks["components"].append((component, True))
            else:
                print(f"  ❌ {component}")
                self.checks["components"].append((component, False))
    
    def check_javascript(self):
        """Check JavaScript utilities"""
        print("\n🔧 JavaScript Utilities:")
        js_files = ["pdf-processing-utils.js"]
        
        js_path = self.root / "resources" / "js"
        for js_file in js_files:
            if (js_path / js_file).exists():
                print(f"  ✅ {js_file}")
                self.checks["javascript"].append((js_file, True))
            else:
                print(f"  ❌ {js_file}")
                self.checks["javascript"].append((js_file, False))
    
    def check_documentation(self):
        """Check documentation files"""
        print("\n📚 Documentation:")
        docs = [
            "README_PHASE1.md",
            "QUICK_REFERENCE.md",
            "DEVELOPER_GUIDE.md",
            "TOOL_EXAMPLE_MERGE.md",
            "FITUR_INTEGRATION.md",
            "TASK_TRACKER.md",
            "DOCUMENTATION_INDEX.md",
            "PHASE_1_SUMMARY.md"
        ]
        
        for doc in docs:
            if (self.root / doc).exists():
                print(f"  ✅ {doc}")
                self.checks["documentation"].append((doc, True))
            else:
                print(f"  ❌ {doc}")
                self.checks["documentation"].append((doc, False))
    
    def check_routes(self):
        """Check route configuration"""
        print("\n🛣️  API Routes:")
        routes_file = self.root / "routes" / "web.php"
        
        if routes_file.exists():
            content = routes_file.read_text()
            if "/api/progress" in content:
                print("  ✅ GET /api/progress/{sessionId}")
                self.checks["routes"].append(("progress", True))
            else:
                print("  ❌ GET /api/progress/{sessionId}")
                self.checks["routes"].append(("progress", False))
            
            if "/api/session" in content:
                print("  ✅ DELETE /api/session/{sessionId}")
                self.checks["routes"].append(("session", True))
            else:
                print("  ❌ DELETE /api/session/{sessionId}")
                self.checks["routes"].append(("session", False))
    
    def check_views(self):
        """Check fitur view integration"""
        print("\n👁️  View Integration:")
        fitur_file = self.root / "resources" / "views" / "fitur.blade.php"
        
        if fitur_file.exists():
            content = fitur_file.read_text()
            
            if "components.notifications" in content:
                print("  ✅ Notifications component included")
                self.checks["views"].append(("notifications", True))
            else:
                print("  ❌ Notifications component included")
                self.checks["views"].append(("notifications", False))
            
            if "pdf-processing-utils.js" in content:
                print("  ✅ PDF utils script loaded")
                self.checks["views"].append(("utils", True))
            else:
                print("  ❌ PDF utils script loaded")
                self.checks["views"].append(("utils", False))
    
    def print_summary(self):
        """Print final summary"""
        print("\n" + "="*50)
        print("📊 PHASE 1 VERIFICATION SUMMARY")
        print("="*50)
        
        total = sum(sum(1 for _, status in items if status) for items in self.checks.values())
        max_total = sum(len(items) for items in self.checks.values())
        
        print(f"\n✅ Components Ready: {total}/{max_total}")
        
        if total == max_total:
            print("\n🎉 PHASE 1 FOUNDATION IS COMPLETE!")
            print("   Ready to build Phase 2 tools")
            print("\n📖 Next Steps:")
            print("   1. Read: README_PHASE1.md")
            print("   2. Read: QUICK_REFERENCE.md")
            print("   3. Read: DEVELOPER_GUIDE.md")
            print("   4. Start building your first tool!")
        else:
            print(f"\n⚠️  Missing items ({max_total - total} of {max_total})")
            print("   Check the ❌ marks above")
        
        print("\n" + "="*50)

if __name__ == "__main__":
    project_root = "C:/laragon/www/VizzioDocs"
    verifier = Phase1Verifier(project_root)
    verifier.verify()

