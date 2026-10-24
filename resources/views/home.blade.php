@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold d-flex justify-content-between">
                        <p>QR Code Generator</p>
                        <div class="form-group">

                            <!--login form Modal -->
                            <div class="modal fade text-left" id="inlineForm" tabindex="-1"
                                aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel33">Bulk Excel</h4>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-x">
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                            </button>
                                        </div>
                                        <form action="#">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" id="inputGroupFile04"
                                                            aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                                        <button class="btn btn-primary" type="button"
                                                            id="inputGroupFileAddon04">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block ">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-success ms-1" data-bs-dismiss="modal">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Bulk</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                {{-- TEXT --}}
                                <div class="mb-3">
                                    <label for="qrText" class="form-label">Text / URL</label>
                                    <input type="text" id="qrText" class="form-control" placeholder="Masukkan text atau URL">
                                </div>

                                {{-- WARNA --}}
                                <div class="row mb-3 align-items-end">
                                    <div class="col-sm-4">
                                        <label for="qrColor" class="form-label">Warna QR</label>
                                        <input type="color" id="qrColor" class="form-control form-control-color"
                                            value="#000000">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="qrBgColor" class="form-label">Background</label>
                                        <input type="color" id="qrBgColor" class="form-control form-control-color"
                                            value="#ffffff" disabled>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="qrTransparent" checked>
                                            <label for="qrTransparent" class="form-check-label">
                                                Transparan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- UKURAN INPUT --}}
                                <div class="mb-3">
                                    <label for="qrSize" class="form-label">Ukuran QR (px)</label>
                                    <input type="number" id="qrSize" class="form-control" value="300" min="100"
                                        max="5000">
                                    <small class="text-muted">
                                        Instagram: &ge; 512 px &bull; Cetak: 1500&ndash;3000 px
                                    </small>
                                </div>

                                {{-- LOGO --}}
                                <div class="mb-3">
                                    <label for="qrLogo" class="form-label">Logo (Opsional)</label>
                                    <div class="input-group">
                                        <input type="file" id="qrLogo" class="form-control"
                                            accept="image/png,image/jpeg,image/webp" aria-describedby="qrLogoHelp qrLogoStatus">
                                        <button type="button" id="uploadLogo" class="btn btn-outline-primary" disabled>
                                            Upload
                                        </button>
                                    </div>
                                    <small id="qrLogoHelp" class="text-muted">
                                        PNG, JPG, atau WebP, maksimal 2 MB. Disarankan PNG transparan.
                                    </small>
                                    <div id="qrLogoStatus" class="small mt-1 text-muted" role="status" aria-live="polite"></div>
                                </div>

                                {{-- BUTTON --}}
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" onclick="generateQRCode()">
                                        Generate QR Code
                                    </button>
                                </div>
                            </div>

                            {{-- PREVIEW --}}
                            <div class="col-md-6">
                                <h5 class="text-center">Preview</h5>
                                <div class="text-center">
                                    <div class="qr-preview-viewport border rounded" role="region"
                                        aria-label="Preview QR Code" tabindex="0">
                                        <canvas id="qrCanvas" class="border rounded" width="300" height="300"
                                            role="img" aria-label="Preview QR Code"></canvas>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <a id="downloadQR" class="btn btn-outline-success d-none" download="qrcode.png">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .qr-preview-viewport {
            height: 340px;
            max-height: 60vh;
            overflow: auto;
            padding: 1rem;
        }

        #qrCanvas {
            display: block;
            width: 300px;
            height: 300px;
            max-width: none;
            margin: 0 auto;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <script>
        const logoInput = document.getElementById('qrLogo');
        const uploadLogoButton = document.getElementById('uploadLogo');
        const transparentInput = document.getElementById('qrTransparent');
        const backgroundInput = document.getElementById('qrBgColor');
        let uploadedLogo = null;
        let uploadedLogoFile = null;

        function syncBackgroundInput() {
            backgroundInput.disabled = transparentInput.checked;
        }

        transparentInput.addEventListener('change', syncBackgroundInput);
        syncBackgroundInput();

        function setLogoStatus(message, state = '') {
            const status = document.getElementById('qrLogoStatus');
            status.textContent = message;
            status.classList.toggle('text-danger', state === 'error');
            status.classList.toggle('text-success', state === 'success');
            status.classList.toggle('text-muted', state === '');
            logoInput.classList.toggle('is-invalid', state === 'error');
            logoInput.classList.toggle('is-valid', state === 'success');
        }

        logoInput.addEventListener('change', () => {
            uploadedLogo = null;
            uploadedLogoFile = null;
            uploadLogoButton.disabled = !logoInput.files.length;
            uploadLogoButton.textContent = 'Upload';
            setLogoStatus(logoInput.files.length ? 'Klik Upload untuk memvalidasi logo.' : '');
        });

        uploadLogoButton.addEventListener('click', () => {
            const file = logoInput.files[0];
            uploadedLogo = null;
            uploadedLogoFile = null;

            if (!file) {
                setLogoStatus('Pilih file logo terlebih dahulu.', 'error');
                return;
            }

            if (!['image/png', 'image/jpeg', 'image/webp'].includes(file.type)) {
                setLogoStatus('Format logo harus PNG, JPG, atau WebP.', 'error');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                setLogoStatus('Ukuran logo maksimal 2 MB.', 'error');
                return;
            }

            uploadLogoButton.disabled = true;
            uploadLogoButton.textContent = 'Memvalidasi...';
            setLogoStatus('Memvalidasi file logo...');

            const logo = new Image();
            const logoUrl = URL.createObjectURL(file);

            logo.onload = () => {
                URL.revokeObjectURL(logoUrl);
                if (logoInput.files[0] !== file) return;

                uploadedLogo = logo;
                uploadedLogoFile = file;
                uploadLogoButton.disabled = false;
                uploadLogoButton.textContent = 'Upload';
                setLogoStatus('Logo berhasil diupload dan siap digunakan.', 'success');
            };

            logo.onerror = () => {
                URL.revokeObjectURL(logoUrl);
                if (logoInput.files[0] !== file) return;

                uploadLogoButton.disabled = false;
                uploadLogoButton.textContent = 'Upload';
                setLogoStatus('File logo tidak dapat dibaca. Pilih gambar yang valid.', 'error');
            };

            logo.src = logoUrl;
        });

        function generateQRCode() {
            const text = document.getElementById('qrText').value.trim();
            if (!text) {
                alert('Text / URL wajib diisi');
                return;
            }

            const size = parseInt(document.getElementById('qrSize').value);
            if (isNaN(size) || size < 100 || size > 5000) {
                alert('Ukuran harus antara 100 - 5000 px');
                return;
            }

            const color = document.getElementById('qrColor').value;
            const bgInput = document.getElementById('qrBgColor').value;
            const isTransparent = document.getElementById('qrTransparent').checked;
            const canvas = document.getElementById('qrCanvas');

            if (logoInput.files.length && (!uploadedLogo || uploadedLogoFile !== logoInput.files[0])) {
                setLogoStatus('Upload logo yang valid sebelum membuat QR Code.', 'error');
                return;
            }

            // Generate QR
            new QRious({
                element: canvas,
                value: text,
                size: size,
                foreground: color,
                background: bgInput,
                backgroundAlpha: isTransparent ? 0 : 1,
                level: 'H'
            });

            const ctx = canvas.getContext('2d');

            // Logo opsional
            if (uploadedLogo) {
                const logoSize = size * 0.22; // aman scan
                const scale = logoSize / Math.max(uploadedLogo.naturalWidth, uploadedLogo.naturalHeight);
                const width = uploadedLogo.naturalWidth * scale;
                const height = uploadedLogo.naturalHeight * scale;
                const x = (size - width) / 2;
                const y = (size - height) / 2;
                ctx.drawImage(uploadedLogo, x, y, width, height);
            }

            enableDownload(canvas);
        }

        function enableDownload(canvas) {
            const btn = document.getElementById('downloadQR');
            btn.href = canvas.toDataURL('image/png');
            btn.classList.remove('d-none');
        }
    </script>
@endpush
