document.addEventListener('alpine:init', () => {
    Alpine.data('pdfUpload', () => ({
        isDragging: false,
        uploaded: false,
        isLoading: false,
        loadingText: 'Memproses PDF Anda...',
        errorMessage: '',

        init() {
            console.log('pdfUpload Alpine component initialized');
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

        processFile(file) {
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

            // Simulate upload and processing
            setTimeout(() => {
                this.loadingText = 'Menganalisis PDF...';
                setTimeout(() => {
                    this.uploaded = true;
                    this.isLoading = false;
                    // In a real application, you would send the file to the server here
                    // and then display the PDF viewer with the processed PDF.
                    // For now, we just simulate the successful upload.
                }, 1500); // Simulate processing time
            }, 1500); // Simulate upload time
        }
    }))
});
