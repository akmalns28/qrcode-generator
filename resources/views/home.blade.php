@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold d-flex justify-content-between">
                        <p>QR Code Generator</p>
                        <div class="form-group">
                            <!-- Button trigger for login form modal -->
                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#inlineForm">
                                Bulk Excel
                            </button>

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

                        {{-- TEXT --}}
                        <div class="mb-3">
                            <label class="form-label">Text / URL</label>
                            <input type="text" id="qrText" class="form-control" placeholder="Masukkan text atau URL">
                        </div>

                        {{-- WARNA --}}
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Warna QR</label>
                                <input type="color" id="qrColor" class="form-control form-control-color"
                                    value="#000000">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Background</label>
                                <input type="color" id="qrBgColor" class="form-control form-control-color"
                                    value="#ffffff">
                            </div>

                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="qrTransparent">
                                    <label for="qrTransparent" class="form-check-label">
                                        Transparan
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- UKURAN INPUT --}}
                        <div class="mb-3">
                            <label class="form-label">Ukuran QR (px)</label>
                            <input type="number" id="qrSize" class="form-control" value="300" min="100"
                                max="5000">
                            <small class="text-muted">
                                Instagram: ≥ 512 px • Cetak: 1500–3000 px
                            </small>
                        </div>

                        {{-- LOGO --}}
                        <div class="mb-3">
                            <label class="form-label">Logo (Opsional)</label>
                            <input type="file" id="qrLogo" class="form-control" accept="image/*">
                            <small class="text-muted">
                                Disarankan PNG transparan
                            </small>
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary" onclick="generateQRCode()">
                                Generate QR Code
                            </button>
                        </div>

                        <hr>

                        {{-- PREVIEW --}}
                        <h5 class="text-center">Preview</h5>
                        <div class="text-center">
                            <div class="d-flex justify-content-center">
                                <canvas id="qrCanvas" class="border rounded"></canvas>
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
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <script>
        function generateQRCode() {
            const text = document.getElementById('qrText').value.trim();
            if (!text) {
                alert('Text / URL wajib diisi');
                return;
            }

            const size = parseInt(document.getElementById('qrSize').value);
            if (isNaN(size) || size < 100 || size > 5000) {
                alert('Ukuran harus antara 100 – 5000 px');
                return;
            }

            const color = document.getElementById('qrColor').value;
            const bgInput = document.getElementById('qrBgColor').value;
            const isTransparent = document.getElementById('qrTransparent').checked;
            const logoInput = document.getElementById('qrLogo');
            const canvas = document.getElementById('qrCanvas');

            const background = isTransparent ? 'transparent' : bgInput;

            // Generate QR
            new QRious({
                element: canvas,
                value: text,
                size: size,
                foreground: color,
                background: background,
                level: 'H'
            });

            const ctx = canvas.getContext('2d');

            // Logo opsional
            if (logoInput.files && logoInput.files[0]) {
                const logo = new Image();
                logo.src = URL.createObjectURL(logoInput.files[0]);

                logo.onload = () => {
                    const logoSize = size * 0.22; // aman scan
                    const x = (size - logoSize) / 2;
                    const y = (size - logoSize) / 2;
                    ctx.drawImage(logo, x, y, logoSize, logoSize);
                    enableDownload(canvas);
                };
            } else {
                enableDownload(canvas);
            }
        }

        function enableDownload(canvas) {
            const btn = document.getElementById('downloadQR');
            btn.href = canvas.toDataURL('image/png');
            btn.classList.remove('d-none');
        }
    </script>
@endpush
