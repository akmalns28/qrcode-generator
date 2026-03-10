@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Generate QR dari Excel</h2>

        <div class="row">
            <!-- Form Upload + Generate QR -->
            <div class="col-md-6">
                <div class="card p-4 shadow-sm">
                    <form id="generateForm" action="{{ route('qr.generate') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="excel" class="form-label">File Excel (xls/xlsx)</label>
                            <input type="file" name="excel" class="form-control" id="excel" required>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="hasHeader" id="hasHeader" class="form-check-input" value="1">
                            <label for="hasHeader" class="form-check-label">Excel memiliki header</label>
                        </div>

                        <div class="mb-3">
                            <label for="columnSelect" class="form-label">Pilih Kolom untuk QR</label>
                            <select id="columnSelect" name="columnIndex" class="form-select" required>
                                <!-- Opsi diisi JS -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="size" class="form-label">Ukuran QR (px)</label>
                            <input type="number" name="size" id="size" class="form-control" value="300"
                                min="100" max="1000">
                        </div>

                         <div class="form-check mb-3">
                            <input type="checkbox" name="transparent" id="transparent" class="form-check-input"
                                value="1">
                            <label for="transparent" class="form-check-label">Background Transparan</label>
                        </div>

                        <div class="d-flex gap-3">

                            <div class="mb-3">
                                <label for="color" class="form-label">Warna QR (Foreground)</label>
                                <input type="color" name="color" id="color" class="form-control form-control-color"
                                    value="#000000">
                            </div>

                            <div class="mb-3">
                                <label for="bg_color" class="form-label">Warna Background</label>
                                <input type="color" name="bg_color" id="bg_color" class="form-control form-control-color"
                                    value="#ffffff">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Generate QR & Download ZIP</button>
                    </form>
                </div>
            </div>

            <!-- Preview Data Excel -->
            <div class="col-md-6">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-3">Preview Data Excel</h5>
                    <ul id="previewList" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // Preview Excel & pilih kolom
            $('#excel').on('change', function() {
                var formData = new FormData();
                formData.append('excel', this.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('qr.preview.ajax') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        var list = $('#previewList');
                        var columnSelect = $('#columnSelect');
                        list.empty();
                        columnSelect.empty();

                        // Checkbox untuk header
                        var hasHeader = $('#hasHeader').is(':checked');
                        var startRow = hasHeader ? 1 : 0;

                        // Nama kolom
                        var firstRow = hasHeader ? res.data[0] : res.data[0] || [];
                        res.data[0].forEach(function(val, index) {
                            var colName = hasHeader ? (res.data[0][index] || 'Kolom ' +
                                (index + 1)) : 'Kolom ' + (index + 1);
                            columnSelect.append('<option value="' + index + '">' +
                                colName + '</option>');
                        });

                        function showPreview(colIndex) {
                            list.empty();
                            res.data.slice(startRow).forEach(function(row) {
                                list.append('<li class="list-group-item">' + (row[
                                    colIndex] ?? '') + '</li>');
                            });
                        }

                        showPreview(columnSelect.val());

                        // Update preview saat user pilih kolom
                        columnSelect.on('change', function() {
                            showPreview($(this).val());
                        });

                        // Update preview saat toggle header
                        $('#hasHeader').on('change', function() {
                            var hasHeader = $(this).is(':checked');
                            startRow = hasHeader ? 1 : 0;
                            // Rebuild dropdown nama kolom
                            columnSelect.empty();
                            res.data[0].forEach(function(val, index) {
                                var colName = hasHeader ? (res.data[0][index] ||
                                    'Kolom ' + (index + 1)) : 'Kolom ' + (
                                    index + 1);
                                columnSelect.append('<option value="' + index +
                                    '">' + colName + '</option>');
                            });
                            showPreview(columnSelect.val());
                        });

                    },
                    error: function() {
                        alert('Gagal membaca Excel!');
                    }
                });
            });

            // Generate QR & download ZIP
            $('#generateForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    processData: false,
                    contentType: false,
                    success: function(data, status, xhr) {
                        var filename = "qrcode.zip";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var matches = /filename="([^"]+)"/.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1];
                        }

                        var blob = new Blob([data], {
                            type: 'application/zip'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    },
                    error: function() {
                        alert('Gagal generate QR!');
                    }
                });
            });

        });
    </script>
@endsection
