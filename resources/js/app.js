// VizzioDocs Frontend Interactions

import Alpine from 'alpinejs';

// Initialize a global Alpine store for sharing data across components
document.addEventListener('alpine:init', () => {
    Alpine.store('crop', {
        pdfUrl: null,
        sessionId: null,
        filename: null
    });

    // Lock Modal Store — replaces ugly alert() with premium modal
    Alpine.store('lockModal', {
        open: false,
        toolName: '',
        toolPath: '',
        show(toolName, toolPath) {
            this.toolName = toolName;
            this.toolPath = toolPath;
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });

    Alpine.data('sidebarManager', () => ({
        // Baca status terbuka/tutup dari localStorage;
        // Di mobile, selalu mulai dari tertutup agar tidak mengganggu konten
        sidebarOpen: (function () {
            const isMobileNow = window.innerWidth < 1024;
            if (isMobileNow) return false; // Mobile: selalu mulai tertutup
            const saved = localStorage.getItem('vizzio_sidebar_open');
            return saved !== null ? JSON.parse(saved) : true;
        })(),
        isMobile: window.innerWidth < 1024,

        init() {
            this.checkMobile();
            window.addEventListener('resize', () => this.checkMobile());

            // 1. Restore Scroll Position ASAP
            this.$nextTick(() => {
                const sidebarInner = document.getElementById('admin-sidebar-inner');
                if (sidebarInner) {
                    const savedScroll = localStorage.getItem('vizzio_sidebar_scroll');
                    if (savedScroll) {
                        sidebarInner.scrollTop = parseInt(savedScroll);
                    }

                    // Save scroll on the fly
                    sidebarInner.addEventListener('scroll', () => {
                        localStorage.setItem('vizzio_sidebar_scroll', sidebarInner.scrollTop);
                    }, { passive: true });
                }
                this.updateClasses();
            });

            this.$watch('sidebarOpen', (val) => {
                // Hanya simpan ke localStorage jika di desktop
                if (!this.isMobile) {
                    localStorage.setItem('vizzio_sidebar_open', val);
                }
                this.updateClasses();
            });
        },

        checkMobile() {
            const wasMobile = this.isMobile;
            this.isMobile = window.innerWidth < 1024;
            // Jika baru beralih ke mobile, tutup sidebar
            if (!wasMobile && this.isMobile) {
                this.sidebarOpen = false;
            }
        },

        toggle() {
            this.sidebarOpen = !this.sidebarOpen;
        },

        open() {
            this.sidebarOpen = true;
        },

        // close() selalu menutup sidebar — tidak perlu cek isMobile
        // sehingga tombol X di sidebar header selalu bekerja
        close() {
            this.sidebarOpen = false;
        },

        updateClasses() {
            // Alpine's :class binding handles .open/.collapsed on sidebar reactively
            // We only manage the footer margin/width here to avoid classList conflict
            const footer = document.getElementById('main-footer');
            if (footer) {
                if (this.isMobile) {
                    footer.style.marginLeft = '0';
                    footer.style.width = '100%';
                } else if (this.sidebarOpen) {
                    footer.style.marginLeft = '280px';
                    footer.style.width = 'calc(100% - 280px)';
                } else {
                    footer.style.marginLeft = '72px';
                    footer.style.width = 'calc(100% - 72px)';
                }
            }
            // Reposition toast after sidebar transition finishes (350ms)
            if (typeof positionToastContainer === 'function') {
                setTimeout(positionToastContainer, 370);
            }
        }
    }));

    Alpine.data('pdfUpload', () => ({
        isDragging: false,
        uploaded: false,
        isLoading: false,
        loadingText: 'Memproses PDF Anda...',
        errorMessage: '',

        init() {
            console.log('pdfUpload Alpine component initialized');
            // Listen for global clear-crop-file event from sidebar
            window.addEventListener('clear-crop-file', () => {
                this.clearFile();
            });
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            this.processFile(file);
        },

        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            this.processFile(file);
        },

        clearFile() {
            this.uploaded = false;
            this.errorMessage = '';
            this.isLoading = false;
            // Reset crop store
            Alpine.store('crop', {
                pdfUrl: null,
                sessionId: null,
                filename: null
            });
            // Clear file input
            const input = document.getElementById('pdf-upload-input');
            if (input) input.value = '';
        },

        async processFile(file) {
            this.errorMessage = '';
            if (!file) {
                return;
            }

            if (file.type !== 'application/pdf') {
                this.errorMessage = 'File yang diunggah harus berupa PDF.';
                return;
            }

            if (file.size > 20 * 1024 * 1024) { // 20MB limit
                this.errorMessage = 'Ukuran file PDF tidak boleh melebihi 20MB.';
                return;
            }

            this.isLoading = true;
            this.loadingText = `Mengunggah ${file.name}...`;

            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch('/pdf-crop/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Gagal mengunggah PDF.');
                }

                Alpine.store('crop', {
                    pdfUrl: data.pdf_url,
                    sessionId: data.sessionId,
                    filename: data.filename
                });

                this.uploaded = true;

            } catch (error) {
                console.error('Upload Error:', error);
                this.errorMessage = error.message || 'Gagal mengunggah PDF. Silakan coba lagi.';
            } finally {
                this.isLoading = false;
            }
        }
    }))
});

window.Alpine = Alpine;


Alpine.start();


// Register Alpine components for tools
window.pdfCropApp = function () {
    return {
        isDragging: false,
        isLoading: false,
        loadingText: 'Mengunggah PDF...',
        errorMessage: '',
        uploaded: false,
        sessionId: '',
        filename: '',
        pdfUrl: '',

        init() {
            window.addEventListener('clear-crop-file', () => {
                this.clearFile();
            });
        },

        clearFile() {
            this.uploaded = false;
            this.errorMessage = '';
            this.isLoading = false;
            this.sessionId = '';
            this.filename = '';
            this.pdfUrl = '';
            Alpine.store('crop', {
                pdfUrl: null,
                sessionId: null,
                filename: null
            });
            const input = document.getElementById('pdf-upload-input');
            if (input) input.value = '';
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) this.uploadPdf(file);
        },

        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            if (file) this.uploadPdf(file);
        },

        async uploadPdf(file) {
            if (file.type !== 'application/pdf') {
                this.errorMessage = 'Harap unggah file PDF yang valid.';
                return;
            }
            if (file.size > 20 * 1024 * 1024) {
                this.errorMessage = 'Ukuran file maksimal 20MB.';
                return;
            }

            this.isLoading = true;
            this.loadingText = 'Mengunggah PDF...';
            this.errorMessage = '';

            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch('/pdf-crop/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Gagal mengunggah PDF.');
                }

                this.sessionId = data.sessionId;
                this.filename = data.filename;
                this.pdfUrl = data.pdf_url;

                // Store data in Alpine store so pdfViewer can access it
                Alpine.store('crop', {
                    pdfUrl: data.pdf_url,
                    sessionId: data.sessionId,
                    filename: data.filename
                });

                this.uploaded = true;

            } catch (error) {
                console.error('Upload Error:', error);
                this.errorMessage = error.message || 'Gagal mengunggah PDF. Silakan coba lagi.';
            } finally {
                this.isLoading = false;
            }
        }
    }
};

// Global reference for pdfViewer instance (for external access)
window.pdfViewerInstance = null;

// Import custom Alpine components
window.pdfViewer = function () {
    return {
        pdfDoc: null,
        currentPage: 1,
        totalPages: 0,
        zoom: 1.0,
        pdfUrl: null,
        canvas: null,
        ctx: null,
        isCropping: false,
        cropBox: { x: 0, y: 0, width: 0, height: 0 },
        // cssBox: cropBox coordinates converted to CSS pixel space for overlay positioning.
        cssBox: { x: 0, y: 0, width: 0, height: 0 },
        startCropX: 0,
        startCropY: 0,
        errorMessage: '',
        rightOpen: true,
        renderTask: null,

        // Store this instance globally for external access
        init() {
            window.pdfViewerInstance = this;

            // Listen for reset-crop event from external triggers
            window.addEventListener('reset-crop', () => {
                this.resetCrop();
            });
        },

        // Helper: recalculate cssBox from current cropBox and canvas CSS size
        _updateCssBox() {
            if (!this.canvas || this.cropBox.width === 0 || this.cropBox.height === 0) {
                this.cssBox = { x: 0, y: 0, width: 0, height: 0 };
                return;
            }
            const rect = this.canvas.getBoundingClientRect();
            const scaleX = rect.width / this.canvas.width;
            const scaleY = rect.height / this.canvas.height;
            this.cssBox = {
                x: this.cropBox.x * scaleX,
                y: this.cropBox.y * scaleY,
                width: this.cropBox.width * scaleX,
                height: this.cropBox.height * scaleY
            };
        },

        initPdfViewer(url) {
            const cropStore = Alpine.store('crop');
            this.pdfUrl = url || (cropStore ? cropStore.pdfUrl : null);

            if (!this.pdfUrl) {
                this.errorMessage = 'PDF URL tidak ditemukan.';
                return;
            }

            this.canvas = document.getElementById('pdf-canvas');
            this.ctx = this.canvas.getContext('2d');
            this.loadPdf();

            this.$watch('currentPage', () => this.renderPage());
            this.$watch('zoom', () => this.renderPage());
            this.$watch('cropBox', () => this._updateCssBox());
            window.addEventListener('resize', () => this._updateCssBox());

            // Sync initial state of rightOpen with admin-main
            const main = document.getElementById('admin-main');
            if (main && this.rightOpen) {
                main.classList.add('right-open');
            }

            this.$watch('rightOpen', (val) => {
                const main = document.getElementById('admin-main');
                if (main) {
                    if (val) {
                        main.classList.add('right-open');
                    } else {
                        main.classList.remove('right-open');
                    }
                }
            });
        },

        async loadPdf() {
            try {
                const loadingTask = pdfjsLib.getDocument(this.pdfUrl);
                this.pdfDoc = await loadingTask.promise;
                this.totalPages = this.pdfDoc.numPages;
                this.renderPage();
            } catch (error) {
                console.error('Error loading PDF:', error);
                this.errorMessage = 'Gagal memuat PDF. Pastikan itu adalah file PDF yang valid.';
            }
        },

        async renderPage() {
            if (!this.pdfDoc) return;

            // Cancel any ongoing render task before starting a new one
            if (this.renderTask) {
                try {
                    this.renderTask.cancel();
                } catch (e) {
                    // Ignore error on cancellation
                }
                this.renderTask = null;
            }

            try {
                const page = await this.pdfDoc.getPage(this.currentPage);
                const viewport = page.getViewport({ scale: this.zoom });

                this.canvas.height = viewport.height;
                this.canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: this.ctx,
                    viewport: viewport,
                };

                this.renderTask = page.render(renderContext);

                try {
                    await this.renderTask.promise;
                    this.renderTask = null;
                    this.resetCrop();
                } catch (renderError) {
                    // Ignore rendering cancelled exceptions
                    if (renderError.name === 'RenderingCancelledException' || renderError.message === 'Rendering cancelled, page-change') {
                        return;
                    }
                    throw renderError;
                }
            } catch (error) {
                console.error('Error rendering page:', error);
                this.errorMessage = 'Gagal merender halaman.';
            }
        },

        zoomIn() {
            this.zoom = Math.min(this.zoom + 0.2, 3.0);
        },

        zoomOut() {
            this.zoom = Math.max(this.zoom - 0.2, 0.5);
        },

        nextPage() {
            const current = parseInt(this.currentPage);
            const total = parseInt(this.totalPages);
            if (!isNaN(current) && current < total) {
                this.currentPage = current + 1;
            }
        },

        previousPage() {
            const current = parseInt(this.currentPage);
            if (!isNaN(current) && current > 1) {
                this.currentPage = current - 1;
            }
        },

        goToPage() {
            const pageNum = parseInt(this.currentPage);
            if (!isNaN(pageNum) && pageNum >= 1 && pageNum <= this.totalPages) {
                this.currentPage = pageNum;
            } else {
                this.currentPage = 1;
            }
        },

        _getCanvasPos(event) {
            const rect = this.canvas.getBoundingClientRect();
            const scaleX = this.canvas.width / rect.width;
            const scaleY = this.canvas.height / rect.height;

            let clientX, clientY;
            if (event.touches && event.touches.length > 0) {
                clientX = event.touches[0].clientX;
                clientY = event.touches[0].clientY;
            } else if (event.changedTouches && event.changedTouches.length > 0) {
                clientX = event.changedTouches[0].clientX;
                clientY = event.changedTouches[0].clientY;
            } else {
                clientX = event.clientX;
                clientY = event.clientY;
            }

            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        },

        startCrop(event) {
            if (event.target !== this.canvas) return;
            this.isCropping = true;
            const pos = this._getCanvasPos(event);
            this.startCropX = pos.x;
            this.startCropY = pos.y;
            this.cropBox = { x: pos.x, y: pos.y, width: 0, height: 0 };
        },

        doCrop(event) {
            if (!this.isCropping) return;
            if (event.cancelable) event.preventDefault(); // Prevent page scroll during cropping

            const pos = this._getCanvasPos(event);
            const width = pos.x - this.startCropX;
            const height = pos.y - this.startCropY;

            this.cropBox.x = Math.min(this.startCropX, pos.x);
            this.cropBox.y = Math.min(this.startCropY, pos.y);
            this.cropBox.width = Math.abs(width);
            this.cropBox.height = Math.abs(height);
        },

        endCrop() {
            this.isCropping = false;
        },

        resetCrop() {
            this.cropBox = { x: 0, y: 0, width: 0, height: 0 };
            console.log('resetCrop called - cropBox cleared');
        },

        async cropPdf(cropAllPages) {
            // Check if file has been uploaded first
            const cropStore = Alpine.store('crop');
            if (!cropStore || !cropStore.filename) {
                this.errorMessage = 'Silakan unggah file PDF terlebih dahulu.';
                return;
            }

            if (this.cropBox.width === 0 || this.cropBox.height === 0) {
                this.errorMessage = 'Silakan pilih area cropping terlebih dahulu.';
                return;
            }

            this.errorMessage = '';

            const page = await this.pdfDoc.getPage(this.currentPage);
            const rotation = page.rotate || 0;
            const viewport = page.getViewport({ scale: 1.0 });

            const zoomedViewport = page.getViewport({ scale: this.zoom });
            const scaleX = viewport.width / zoomedViewport.width;
            const scaleY = viewport.height / zoomedViewport.height;

            const x = Math.round(this.cropBox.x * scaleX);
            const y = Math.round(this.cropBox.y * scaleY);
            const width = Math.round(this.cropBox.width * scaleX);
            const height = Math.round(this.cropBox.height * scaleY);

            const sessionId = cropStore ? cropStore.sessionId : '';
            const filename = cropStore ? cropStore.filename : '';

            const formData = new FormData();
            formData.append('sessionId', sessionId);
            formData.append('filename', filename);
            formData.append('page', this.currentPage);
            formData.append('x', x);
            formData.append('y', y);
            formData.append('width', width);
            formData.append('height', height);
            formData.append('rotation', rotation);
            formData.append('cropAllPages', cropAllPages ? '1' : '0');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                const response = await fetch('/pdf-crop/crop', {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Pemotongan PDF gagal.');
                }

                const result = await response.json();
                if (result.download_url) {
                    window.location.href = result.download_url;
                } else {
                    throw new Error('URL download tidak diterima.');
                }

            } catch (error) {
                console.error('Crop Error:', error);
                this.errorMessage = error.message || 'Gagal memotong PDF. Silakan coba lagi.';
            }
        },

        cropCurrentPage() {
            this.cropPdf(false);
        },

        cropAllPages() {
            this.cropPdf(true);
        },

        savePdf() {
            this.cropCurrentPage();
        },

        resizing: false,
        activeHandle: null,
        startMouseX: 0,
        startMouseY: 0,
        initialCropBox: null,

        _boundResizeHandler: null,
        _boundEndResizeHandler: null,

        startResize(event, handle) {
            event.stopPropagation();
            this.resizing = true;
            this.activeHandle = handle;

            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            const clientY = event.touches ? event.touches[0].clientY : event.clientY;

            this.startMouseX = clientX;
            this.startMouseY = clientY;
            this.initialCropBox = { ...this.cropBox };

            this._boundResizeHandler = this.doResize.bind(this);
            this._boundEndResizeHandler = this.endResize.bind(this);

            document.addEventListener('mousemove', this._boundResizeHandler);
            document.addEventListener('mouseup', this._boundEndResizeHandler);
            document.addEventListener('touchmove', this._boundResizeHandler, { passive: false });
            document.addEventListener('touchend', this._boundEndResizeHandler);
        },

        doResize(event) {
            if (!this.resizing) return;
            if (event.cancelable) event.preventDefault(); // Prevent touch scrolling during resize

            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            const clientY = event.touches ? event.touches[0].clientY : event.clientY;

            const rect = this.canvas.getBoundingClientRect();
            const cssScaleX = this.canvas.width / rect.width;
            const cssScaleY = this.canvas.height / rect.height;
            const dx = (clientX - this.startMouseX) * cssScaleX;
            const dy = (clientY - this.startMouseY) * cssScaleY;

            let newX = this.initialCropBox.x;
            let newY = this.initialCropBox.y;
            let newWidth = this.initialCropBox.width;
            let newHeight = this.initialCropBox.height;

            switch (this.activeHandle) {
                case 'tl':
                    newX = this.initialCropBox.x + dx;
                    newY = this.initialCropBox.y + dy;
                    newWidth = this.initialCropBox.width - dx;
                    newHeight = this.initialCropBox.height - dy;
                    break;
                case 'tr':
                    newY = this.initialCropBox.y + dy;
                    newWidth = this.initialCropBox.width + dx;
                    newHeight = this.initialCropBox.height - dy;
                    break;
                case 'bl':
                    newX = this.initialCropBox.x + dx;
                    newWidth = this.initialCropBox.width - dx;
                    newHeight = this.initialCropBox.height + dy;
                    break;
                case 'br':
                    newWidth = this.initialCropBox.width + dx;
                    newHeight = this.initialCropBox.height + dy;
                    break;
            }

            if (newWidth < 0) {
                newX += newWidth;
                newWidth = Math.abs(newWidth);
            }
            if (newHeight < 0) {
                newY += newHeight;
                newHeight = Math.abs(newHeight);
            }

            this.cropBox.x = Math.max(0, Math.min(newX, this.canvas.width - newWidth));
            this.cropBox.y = Math.max(0, Math.min(newY, this.canvas.height - newHeight));
            this.cropBox.width = Math.min(newWidth, this.canvas.width - this.cropBox.x);
            this.cropBox.height = Math.min(newHeight, this.canvas.height - this.cropBox.y);
        },

        endResize() {
            this.resizing = false;
            this.activeHandle = null;
            if (this._boundResizeHandler) {
                document.removeEventListener('mousemove', this._boundResizeHandler);
                document.removeEventListener('touchmove', this._boundResizeHandler);
                this._boundResizeHandler = null;
            }
            if (this._boundEndResizeHandler) {
                document.removeEventListener('mouseup', this._boundEndResizeHandler);
                document.removeEventListener('touchend', this._boundEndResizeHandler);
                this._boundEndResizeHandler = null;
            }
        },

        dragging: false,
        dragStartX: 0,
        dragStartY: 0,
        dragStartCropBox: null,
        _boundDragHandler: null,
        _boundEndDragHandler: null,

        startDragCrop(event) {
            this.dragging = true;

            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            const clientY = event.touches ? event.touches[0].clientY : event.clientY;

            this.dragStartX = clientX;
            this.dragStartY = clientY;
            this.dragStartCropBox = { ...this.cropBox };

            this._boundDragHandler = this.doDragCrop.bind(this);
            this._boundEndDragHandler = this.endDragCrop.bind(this);

            document.addEventListener('mousemove', this._boundDragHandler);
            document.addEventListener('mouseup', this._boundEndDragHandler);
            document.addEventListener('touchmove', this._boundDragHandler, { passive: false });
            document.addEventListener('touchend', this._boundEndDragHandler);
        },

        doDragCrop(event) {
            if (!this.dragging) return;
            if (event.cancelable) event.preventDefault(); // Prevent touch scrolling during drag

            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            const clientY = event.touches ? event.touches[0].clientY : event.clientY;

            const rect = this.canvas.getBoundingClientRect();
            const cssScaleX = this.canvas.width / rect.width;
            const cssScaleY = this.canvas.height / rect.height;
            const dx = (clientX - this.dragStartX) * cssScaleX;
            const dy = (clientY - this.dragStartY) * cssScaleY;

            let newX = this.dragStartCropBox.x + dx;
            let newY = this.dragStartCropBox.y + dy;

            newX = Math.max(0, Math.min(newX, this.canvas.width - this.dragStartCropBox.width));
            newY = Math.max(0, Math.min(newY, this.canvas.height - this.dragStartCropBox.height));

            this.cropBox.x = newX;
            this.cropBox.y = newY;
        },

        endDragCrop() {
            this.dragging = false;
            if (this._boundDragHandler) {
                document.removeEventListener('mousemove', this._boundDragHandler);
                document.removeEventListener('touchmove', this._boundDragHandler);
                this._boundDragHandler = null;
            }
            if (this._boundEndDragHandler) {
                document.removeEventListener('mouseup', this._boundEndDragHandler);
                document.removeEventListener('touchend', this._boundEndDragHandler);
                this._boundEndDragHandler = null;
            }
        }
    }
};



// 1. Toast Notification Helper
function positionToastContainer() {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const isDesktop = window.innerWidth >= 1024;
    const sidebar = document.querySelector('.admin-sidebar');

    if (isDesktop && sidebar) {
        // Detect sidebar width from its actual rendered width
        const sidebarW = sidebar.getBoundingClientRect().width || 72;
        const availableW = window.innerWidth - sidebarW;
        const centerOfContent = sidebarW + availableW / 2;

        container.style.left = centerOfContent + 'px';
        container.style.transform = 'translateX(-50%)';
    } else {
        // Mobile: center in full viewport
        container.style.left = '50%';
        container.style.transform = 'translateX(-50%)';
    }
}

window.showToast = function (message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Recalculate center position on every toast
    positionToastContainer();

    const toast = document.createElement('div');
    toast.className = `flex items-center w-full max-w-xs p-4 text-slate-800 bg-white rounded-xl shadow-lg border transition-all duration-300 transform -translate-y-3 opacity-0 glass-panel`;

    let borderClass = 'border-slate-100';
    let iconHtml = '';

    if (type === 'success') {
        borderClass = 'border-emerald-100 bg-emerald-50/90';
        iconHtml = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>`;
    } else if (type === 'error') {
        borderClass = 'border-rose-100 bg-rose-50/90';
        iconHtml = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-rose-500 bg-rose-100 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>`;
    } else {
        borderClass = 'border-indigo-100 bg-indigo-50/90';
        iconHtml = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-indigo-500 bg-indigo-100 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>`;
    }

    toast.classList.add(...borderClass.split(' '));
    toast.innerHTML = `
        ${iconHtml}
        <div class="ms-3 text-sm font-medium">${message}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-transparent text-slate-400 hover:text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-300 p-1.5 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="toast" aria-label="Close">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor" stroke-width="2"><path d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
        </button>
    `;

    container.querySelector('div').appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('-translate-y-3', 'opacity-0');
    }, 10);

    // Auto-dismiss
    const timer = setTimeout(() => {
        toast.classList.add('-translate-y-3', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 4000);

    // Dismiss handler
    toast.querySelector('button').addEventListener('click', () => {
        clearTimeout(timer);
        toast.classList.add('-translate-y-3', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    });
};

// Reposition toast on resize
window.addEventListener('resize', positionToastContainer);
// Initial position
positionToastContainer();
// 2. Global Upload Form Handling

const toolForm = document.getElementById('tool-form');
if (toolForm) {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-input');
    const fileListContainer = document.getElementById('file-list-container');
    const filesList = document.getElementById('files-list');
    const submitBtn = document.getElementById('submit-btn');
    const mobileSubmitBtn = document.getElementById('mobile-submit-btn');
    const submitText = document.getElementById('submit-text');
    const processingSection = document.getElementById('processing-section');
    const progressBar = document.getElementById('progress-bar');
    const progressStatus = document.getElementById('progress-status');
    const successSection = document.getElementById('success-section');
    const downloadBtn = document.getElementById('download-btn');
    const viewBtn = document.getElementById('view-btn');
    const resultPreview = document.getElementById('result-preview');

    // File store to keep track of selected files
    let selectedFiles = [];
    const isMultiSelect = fileInput ? fileInput.hasAttribute('multiple') : false;

    // Trigger input click
    if (dropzone && fileInput) {
        dropzone.addEventListener('click', () => fileInput.click());
    }

    // File Input Change
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            handleSelectedFiles(e.target.files);
        });
    }

    // Drag and Drop Events
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('dropzone-active');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.remove('dropzone-active');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            handleSelectedFiles(dt.files);
        });
    }

    function handleSelectedFiles(files) {
        if (files.length === 0) return;

        if (isMultiSelect) {
            // For Merge PDF and Image/PNG conversions, accumulate files
            for (let i = 0; i < files.length; i++) {
                selectedFiles.push(files[i]);
            }
        } else {
            // For single conversions, replace the file
            selectedFiles = [files[0]];
        }

        renderFileList();
        updateSubmitState();
        updateSidebarFileInfo();
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        renderFileList();
        updateSubmitState();
        updateSidebarFileInfo();
    }

    function clearAllFiles() {
        selectedFiles = [];
        if (fileInput) fileInput.value = '';
        renderFileList();
        updateSubmitState();
        updateSidebarFileInfo();
        // Hide rotate preview if visible
        const rotateContainer = document.getElementById('rotate-preview-container');
        if (rotateContainer) rotateContainer.classList.add('hidden');
    }

    function renderFileList() {
        if (selectedFiles.length === 0) {
            fileListContainer.classList.add('hidden');
            filesList.innerHTML = '';
            return;
        }

        fileListContainer.classList.remove('hidden');
        filesList.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl shadow-xs';
            item.innerHTML = `
                <div class="flex items-center space-x-3 truncate">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-bold text-xs uppercase">
                        ${file.name.split('.').pop()}
                    </div>
                    <div class="truncate">
                        <p class="text-sm font-semibold text-slate-700 truncate">${file.name}</p>
                        <p class="text-xs text-slate-400">${sizeMb} MB</p>
                    </div>
                </div>
                <button type="button" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" data-index="${index}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            `;

            // Add delete event
            item.querySelector('button').addEventListener('click', (e) => {
                e.stopPropagation();
                removeFile(index);
            });

            filesList.appendChild(item);
        });

        // Special: Rotate PDF Live Preview
        if (toolForm.getAttribute('data-tool') === 'rotate' && selectedFiles.length > 0) {
            const pdfName = document.getElementById('rotate-pdf-name');
            if (pdfName) {
                pdfName.textContent = selectedFiles[0].name;
                document.getElementById('rotate-preview-container').classList.remove('hidden');
            }
        }
    }

    function updateSubmitState() {
        if (selectedFiles.length > 0) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            if (mobileSubmitBtn) {
                mobileSubmitBtn.disabled = false;
                mobileSubmitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            if (mobileSubmitBtn) {
                mobileSubmitBtn.disabled = true;
                mobileSubmitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    function updateSidebarFileInfo() {
        const sidebarDetails = document.getElementById('sidebar-file-info');
        if (!sidebarDetails) return;

        if (selectedFiles.length === 0) {
            sidebarDetails.innerHTML = `
                <div class="flex flex-col items-center py-4">
                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Belum ada file dipilih</p>
                </div>
            `;
            return;
        }

        const totalSize = selectedFiles.reduce((sum, f) => sum + f.size, 0);
        const totalSizeMb = (totalSize / (1024 * 1024)).toFixed(2);
        const firstFileName = selectedFiles[0].name;
        const fileExt = firstFileName.split('.').pop().toUpperCase();

        if (selectedFiles.length === 1) {
            sidebarDetails.innerHTML = `
                <div class="flex items-center space-x-3 mb-3">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/20">
                        ${fileExt}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate" title="${firstFileName}">${firstFileName}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">${totalSizeMb} MB</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs pt-3 border-t border-slate-100 dark:border-gray-700/50">
                    <span class="text-slate-400 dark:text-slate-500">Nama File</span>
                    <span class="text-slate-700 dark:text-slate-300 font-semibold truncate max-w-[130px]" title="${firstFileName}">${firstFileName}</span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1.5">
                    <span class="text-slate-400 dark:text-slate-500">Ukuran</span>
                    <span class="text-slate-700 dark:text-slate-300 font-semibold">${totalSizeMb} MB</span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1.5">
                    <span class="text-slate-400 dark:text-slate-500">Ekstensi</span>
                    <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold rounded-md text-[10px]">${fileExt}</span>
                </div>
                <button type="button" id="sidebar-clear-btn"
                    class="mt-4 w-full flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl
                        border border-rose-200 bg-rose-50/50 text-rose-600
                        hover:bg-rose-100 hover:border-rose-300
                        active:scale-[0.97] transition-all duration-200
                        text-xs font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batalkan Pilihan
                </button>
            `;
            // Attach clear event
            setTimeout(() => {
                const sideBtn = document.getElementById('sidebar-clear-btn');
                if (sideBtn) sideBtn.addEventListener('click', clearAllFiles);
            }, 0);
        } else {
            sidebarDetails.innerHTML = `
                <div class="flex items-center justify-center w-10 h-10 mx-auto mb-2 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-lg shadow-amber-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-700 dark:text-slate-300">${selectedFiles.length} File Dipilih</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Total ${totalSizeMb} MB</p>
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-gray-700/50 max-h-28 overflow-y-auto space-y-1.5">
                    ${selectedFiles.slice(0, 5).map((f, i) => `
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400 truncate max-w-[120px]" title="${f.name}">${i + 1}. ${f.name}</span>
                            <span class="text-slate-700 dark:text-slate-300 font-semibold">${(f.size / (1024 * 1024)).toFixed(1)} MB</span>
                        </div>
                    `).join('')}
                    ${selectedFiles.length > 5 ? `<p class="text-[10px] text-slate-400 text-center pt-1">+${selectedFiles.length - 5} file lainnya</p>` : ''}
                </div>
                <button type="button" id="sidebar-clear-btn-multi"
                    class="mt-4 w-full flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl
                        border border-rose-200 bg-rose-50/50 text-rose-600
                        hover:bg-rose-100 hover:border-rose-300
                        active:scale-[0.97] transition-all duration-200
                        text-xs font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batalkan Pilihan
                </button>
            `;
            // Attach clear event
            setTimeout(() => {
                const sideBtn = document.getElementById('sidebar-clear-btn-multi');
                if (sideBtn) sideBtn.addEventListener('click', clearAllFiles);
            }, 0);
        }
    }

    // Handle Form Submit (AJAX Upload + Processing animation)
    toolForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (selectedFiles.length === 0) return;

        // Prepare Form Data
        const formData = new FormData(toolForm);

        // Remove the default file inputs since we add files manually
        formData.delete('file');
        formData.delete('files[]');

        // Special handling for html-to-pdf: read HTML content as text
        const currentTool = toolForm.getAttribute('data-tool');
        if (currentTool === 'html-to-pdf') {
            // Read HTML file content as text
            const file = selectedFiles[0];
            const text = await file.text();
            formData.append('html_content', text);
        } else if (isMultiSelect) {
            selectedFiles.forEach(file => {
                formData.append('files[]', file);
            });
        } else {
            formData.append('file', selectedFiles[0]);
        }

        // Show Loading / Progress
        dropzone.classList.add('pointer-events-none', 'opacity-40');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        processingSection.classList.remove('hidden');
        successSection.classList.add('hidden');

        // Reset progress bar
        progressBar.style.width = '0%';
        progressStatus.textContent = 'Mengunggah file...';

        try {
            // Simulate uploading phase in progress bar
            let uploadProgress = 0;
            const progressInterval = setInterval(() => {
                if (uploadProgress < 90) {
                    uploadProgress += 15;
                    progressBar.style.width = `${uploadProgress}%`;
                    if (uploadProgress >= 90) {
                        progressStatus.textContent = 'Memproses dokumen... Harap tunggu sebentar.';
                    }
                }
            }, 400);

            const response = await fetch(toolForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            clearInterval(progressInterval);

            let data;
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                try {
                    data = await response.json();
                } catch (jsonErr) {
                    console.error('VizzioDocs: Failed to parse JSON response', jsonErr);
                    throw new Error('Respons server tidak valid. Silakan coba lagi.');
                }
            } else {
                // Not JSON (probably HTML error page, 500, or 419)
                const errorText = await response.text();
                console.error('VizzioDocs: Non-JSON response received:', errorText);

                if (response.status === 419) {
                    throw new Error('Sesi telah berakhir karena tidak ada aktivitas. Silakan refresh halaman dan coba lagi.');
                } else if (response.status === 413) {
                    throw new Error('File terlalu besar. Silakan kurangi ukuran file Anda.');
                } else if (response.status === 500) {
                    throw new Error('Terjadi kesalahan internal pada server (500). Silakan coba beberapa saat lagi.');
                } else {
                    throw new Error(`Terjadi kesalahan pada server (Status: ${response.status}).`);
                }
            }

            if (response.ok && data.success) {
                progressBar.style.width = '100%';
                progressStatus.textContent = 'Selesai!';
                showToast('Dokumen berhasil dikonversi!', 'success');

                // Setup download button
                downloadBtn.href = data.download_url;
                downloadBtn.download = data.filename || '';

                // Setup view button (opens in new tab)
                if (viewBtn) {
                    viewBtn.href = data.download_url;
                }

                // Display feature specific details (non-blocking)
                try {
                    displayResultDetails(data);
                } catch (detailErr) {
                    console.error('VizzioDocs: displayResultDetails error', detailErr);
                }

                // Reset selected files so user can start fresh
                selectedFiles = [];
                renderFileList();
                updateSidebarFileInfo();

                setTimeout(() => {
                    processingSection.classList.add('hidden');
                    successSection.classList.remove('hidden');
                }, 500);

            } else {
                // Check if quota exhausted / requires login
                if (data.requires_login) {
                    progressBar.style.width = '0%';
                    processingSection.classList.add('hidden');
                    dropzone.classList.remove('pointer-events-none', 'opacity-40');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    // Show quota popup with login/cancel options
                    showQuotaPopup(data);
                    return;
                }
                throw new Error(data.message || 'Gagal memproses file. Pastikan format file sesuai.');
            }

        } catch (err) {
            console.error('VizzioDocs: Tool form error', err);
            progressBar.style.width = '0%';
            processingSection.classList.add('hidden');
            showToast(err.message, 'error');

            // Restore button states so user can try again
            dropzone.classList.remove('pointer-events-none', 'opacity-40');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });

    // Reusable function to show quota popup with login or cancel options
    function showQuotaPopup(data) {
        const existing = document.getElementById('quota-popup');
        if (existing) existing.remove();

        const message = data && data.message ? data.message : 'Anda telah mencapai batas penggunaan hari ini.';
        const remaining = data && data.remaining !== undefined ? data.remaining : 0;

        // Create premium popup element
        const popup = document.createElement('div');
        popup.id = 'quota-popup';
        popup.className = 'fixed inset-0 z-[70] flex items-center justify-center p-4';
        popup.innerHTML = `
            <!-- 🔥 Premium Animated Backdrop with Deep Blur -->
            <div class="absolute inset-0 transition-all duration-700 ease-out">
                <!-- Dark Overlay -->
                <div class="absolute inset-0 bg-black/40 backdrop-blur-2xl"></div>
                <!-- Animated Gradient Orbs -->
                <div class="absolute -top-32 -left-32 w-96 h-96 bg-gradient-to-br from-amber-500/25 to-orange-600/20 rounded-full blur-3xl animate-[blob_8s_infinite] pointer-events-none"></div>
                <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-gradient-to-br from-rose-500/20 to-pink-400/20 rounded-full blur-3xl animate-[blob_8s_infinite_2s] pointer-events-none"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-br from-yellow-400/10 to-amber-400/10 rounded-full blur-3xl animate-[blob_8s_infinite_4s] pointer-events-none"></div>
                <!-- Subtle Grid Pattern Overlay -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:3rem_3rem] pointer-events-none"></div>
            </div>

            <!-- Modal Content -->
            <div class="relative w-full max-w-md transform transition-all duration-500 animate-[scaleIn_0.5s_cubic-bezier(.34,1.56,.64,1)]">
                <!-- Premium Outer Glow -->
                <div class="absolute -inset-2 bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 rounded-3xl blur-3xl opacity-30 animate-[pulseGlow_3s_ease-in-out_infinite]"></div>
                
                <!-- Glass Card -->
                <div class="relative bg-white/90 backdrop-blur-2xl rounded-3xl shadow-[0_20px_70px_-15px_rgba(245,158,11,0.35),0_8px_30px_-6px_rgba(0,0,0,0.15)] border border-white/40 overflow-hidden">
                    <!-- Subtle Inner Glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/60 via-transparent to-amber-50/30 pointer-events-none"></div>
                    
                    <!-- Top Accent Bar -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500"></div>

                    <div class="relative p-8 text-center">
                        <!-- Premium Warning Icon with Ring Animation -->
                        <div class="relative mx-auto w-20 h-20 mb-6">
                            <!-- Pulse Ring -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 animate-ping opacity-20"></div>
                            <!-- Outer Ring -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-amber-400/30 to-orange-500/30 blur-sm"></div>
                            <!-- Icon Container -->
                            <div class="relative w-full h-full flex items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 shadow-xl shadow-amber-300/40">
                                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="text-2xl font-extrabold bg-gradient-to-r from-amber-700 via-orange-700 to-rose-700 bg-clip-text text-transparent mb-2">
                            Batas Penggunaan Tercapai
                        </h3>
                        
                        <!-- Message -->
                        <p class="text-sm text-slate-600 leading-relaxed mb-1" id="quota-popup-message">${message}</p>
                        <p class="text-xs text-slate-400 mb-7">Login untuk melanjutkan menggunakan semua tools tanpa batas.</p>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button onclick="window.location.href='/login'" class="group relative w-full py-3.5 px-6 rounded-xl font-bold text-white overflow-hidden transition-all duration-500 hover:-translate-y-0.5 active:translate-y-0">
                                <!-- Button Gradient Background -->
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-size-200 bg-pos-0 group-hover:bg-pos-100 transition-all duration-500"></div>
                                <!-- Button Shine Overlay -->
                                <div class="absolute inset-0 bg-[linear-gradient(60deg,transparent_30%,rgba(255,255,255,0.15)_45%,rgba(255,255,255,0.15)_55%,transparent_70%)] bg-[length:250%_100%] bg-pos-0 group-hover:bg-pos-100 transition-all duration-700"></div>
                                <!-- Button Shadow -->
                                <div class="absolute inset-0 rounded-xl shadow-xl shadow-indigo-300/50 group-hover:shadow-2xl group-hover:shadow-indigo-400/60 transition-all duration-500"></div>
                                <span class="relative flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Login / Daftar</span>
                                </span>
                            </button>
                            <button data-close-btn class="relative w-full py-3 px-6 rounded-xl font-bold text-slate-500 bg-slate-100/80 hover:bg-slate-200 hover:text-slate-700 transition-all duration-300 active:scale-[0.98] border border-slate-200/60 backdrop-blur-sm overflow-hidden group">
                                <span class="relative flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Nanti Saja</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(popup);
        document.body.style.overflow = 'hidden';

        // Trigger entrance animation
        requestAnimationFrame(() => {
            const backdrop = popup.querySelector('[class*="absolute inset-0 transition-all"]');
            if (backdrop) backdrop.classList.remove('opacity-0');
        });

        // Close button handler
        const closeBtn = popup.querySelector('[data-close-btn]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeQuotaPopup(popup));
        }

        // Close on backdrop click
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                closeQuotaPopup(popup);
            }
        });

        // Close on ESC
        const escHandler = (e) => {
            if (e.key === 'Escape') {
                closeQuotaPopup(popup);
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);
    }

    function closeQuotaPopup(popup) {
        if (!popup || !document.body.contains(popup)) return;

        // Animate out
        const content = popup.querySelector('[class*="relative w-full max-w-md"]');
        const backdrop = popup.querySelector('[class*="absolute inset-0 transition-all"]');

        if (content) {
            content.style.transition = 'all 0.3s ease-out';
            content.style.opacity = '0';
            content.style.transform = 'scale(0.95) translateY(10px)';
        }
        if (backdrop) {
            backdrop.style.opacity = '0';
        }

        setTimeout(() => {
            popup.remove();
            document.body.style.overflow = '';
        }, 300);
    }

    function displayResultDetails(data) {
        resultPreview.innerHTML = '';

        // 1. PDF Compressor extra info
        if (data.original_size && data.compressed_size) {
            const origMb = (data.original_size / (1024 * 1024)).toFixed(2);
            const compMb = (data.compressed_size / (1024 * 1024)).toFixed(2);
            resultPreview.innerHTML = `
                <div class="p-4 bg-indigo-50/50 border border-indigo-100 rounded-xl mb-4 text-center">
                    <p class="text-sm font-medium text-slate-500">Ukuran Berkurang</p>
                    <p class="text-3xl font-extrabold text-indigo-600 my-1">-${data.ratio}%</p>
                    <div class="flex items-center justify-center space-x-4 text-xs font-semibold text-slate-600 mt-2">
                        <span>Sebelum: <strong class="text-slate-800">${origMb} MB</strong></span>
                        <span class="text-slate-300">|</span>
                        <span>Sesudah: <strong class="text-indigo-600">${compMb} MB</strong></span>
                    </div>
                </div>
            `;
        }

        // 2. PDF to JPG Preview (thumbnails)
        if (data.previews && data.previews.length > 0) {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'mt-4';
            previewContainer.innerHTML = `
                <p class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Preview Halaman (${data.total_pages} total)</p>
                <div class="grid grid-cols-3 gap-2">
                    ${data.previews.map((src, index) => `
                        <div class="relative aspect-[3/4] bg-slate-50 border border-slate-100 rounded-lg overflow-hidden group shadow-xs">
                            <img src="${src}" class="w-full h-full object-cover" alt="Halaman ${index + 1}" />
                            <div class="absolute bottom-1 right-1 px-1.5 py-0.5 bg-slate-900/70 text-[9px] font-bold text-white rounded">
                                Hal ${index + 1}
                            </div>
                        </div>
                    `).join('')}
            `;
            resultPreview.appendChild(previewContainer);
        }
    }
}

// 3. Rotate PDF Interactive Tool
const rotateAngleSelect = document.getElementById('rotate-angle');
if (rotateAngleSelect) {
    const previewBox = document.getElementById('rotate-preview-box');

    rotateAngleSelect.addEventListener('change', (e) => {
        const angle = e.target.value;

        // Remove existing rotation classes safely
        previewBox.classList.remove('rotate-90', 'rotate-180', '-rotate-90');

        if (angle === '90') {
            previewBox.classList.add('rotate-90');
        } else if (angle === '180') {
            previewBox.classList.add('rotate-180');
        } else if (angle === '270') {
            previewBox.classList.add('-rotate-90');
        }
    });
}

// 4. Split PDF interactive options
const splitModeSelect = document.getElementById('split-mode');
if (splitModeSelect) {
    const rangeContainer = document.getElementById('range-options-container');
    splitModeSelect.addEventListener('change', (e) => {
        if (e.target.value === 'range') {
            rangeContainer.classList.remove('hidden');
            rangeContainer.querySelectorAll('input').forEach(input => input.required = true);
        } else {
            rangeContainer.classList.add('hidden');
            rangeContainer.querySelectorAll('input').forEach(input => {
                input.required = false;
                input.value = '';
            });
        }
    });
}

// 5. Auth Modal Interactions
const authModal = document.getElementById('auth-modal');
const loginButton = document.getElementById('login-button');
const closeModalButton = document.getElementById('close-modal');
const modalBackdrop = document.getElementById('modal-backdrop');
const loginTab = document.getElementById('login-tab');
const registerTab = document.getElementById('register-tab');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');

if (authModal && loginButton) {
    // Open Modal
    loginButton.addEventListener('click', (e) => {
        e.preventDefault();
        authModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Reset to login tab by default
        switchToTab('login');
    });

    // Close Modal Function
    const closeModal = () => {
        authModal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Close on backdrop click
    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', closeModal);
    }

    // Close on close button click
    if (closeModalButton) {
        closeModalButton.addEventListener('click', closeModal);
    }

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !authModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Tab Switching
    const switchToTab = (tab) => {
        if (tab === 'login') {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
        } else {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        }
    };

    if (loginTab) {
        loginTab.addEventListener('click', () => switchToTab('login'));
    }

    if (registerTab) {
        registerTab.addEventListener('click', () => switchToTab('register'));
    }
}


/* ── FITUR PAGE FILTERING — handled directly in fitur.blade.php ── */
