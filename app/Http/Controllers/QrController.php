<?php

namespace App\Http\Controllers;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class QrController extends Controller
{
    public function index()
    {
        return view('qr.index');
    }

    // AJAX preview Excel dan kolom
    public function previewAjax(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xls,xlsx',
        ]);

        $file = $request->file('excel');

        // Simpan Excel sementara
        $excelName = 'upload_' . time() . '.' . $file->getClientOriginalExtension();
        $excelPath = Storage::disk('public')->putFileAs('excel', $file, $excelName);

        $rows = Excel::toArray([], $file);
        $data = $rows[0] ?? [];

        $columns = [];
        $hasHeader = false;

        // Buat nama kolom default
        if (!empty($data[0])) {
            foreach ($data[0] as $i => $val) {
                $columns[] = 'Kolom ' . ($i + 1);
            }
        }

        return response()->json([
            'data' => $data,
            'columns' => $columns,
            'excelPath' => $excelPath,
        ]);
    }

    // Generate QR dan ZIP
    public function generate(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xls,xlsx',
            'size' => 'nullable|integer|min:100|max:1000',
            'color' => 'nullable|string',
            'bg_color' => 'nullable|string',
            'transparent' => 'nullable|string',
            'columnIndex' => 'required|integer',
            'hasHeader' => 'nullable|string',
        ]);

        $size = (int) ($request->size ?? 300);
        $fgHex = $request->color ?? '#000000';
        $bgHex = $request->bg_color ?? '#ffffff';
        $transparent = $request->transparent === '1';
        $columnIndex = (int) $request->columnIndex;
        $hasHeader = $request->hasHeader === '1';

        $file = $request->file('excel');
        $excelName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Simpan Excel sementara
        $excelPath = Storage::disk('public')->putFileAs('excel', $file, 'temp_' . time() . '.' . $file->getClientOriginalExtension());

        // Baca Excel
        $rows = Excel::toArray([], storage_path('app/public/' . $excelPath));
        $texts = [];

        // Jika ada header, mulai dari baris kedua
        $startRow = $hasHeader ? 1 : 0;

        foreach (array_slice($rows[0], $startRow) as $row) {
            $texts[] = $row[$columnIndex] ?? '';
        }

        // Buat folder unik untuk QR
        $uniqueFolder = 'qrcodes_' . time() . '_' . Str::random(5);
        Storage::disk('public')->makeDirectory($uniqueFolder);

        $writer = new PngWriter();

        // Track nama file agar tidak duplikat
        $usedNames = [];

        foreach ($texts as $text) {
            $baseName = Str::slug($text ?: 'qr'); // slug teks
            $filename = $baseName . '.png';
            $count = 1;

            while (in_array($filename, $usedNames)) {
                $filename = $baseName . '-' . $count . '.png';
                $count++;
            }
            $usedNames[] = $filename;

            [$r1, $g1, $b1] = sscanf($fgHex, '#%02x%02x%02x');
            $fgColor = new Color($r1, $g1, $b1);

            if ($transparent) {
                $bgColor = new Color(0, 0, 0, 0, true);
            } else {
                [$r2, $g2, $b2] = sscanf($bgHex, '#%02x%02x%02x');
                $bgColor = new Color($r2, $g2, $b2);
            }

            $qr = new QrCode(data: $text, size: $size, margin: 10, foregroundColor: $fgColor, backgroundColor: $bgColor);
            $result = $writer->write($qr);

            Storage::disk('public')->put($uniqueFolder . '/' . $filename, $result->getString());
        }

        // Buat ZIP
        $qrcodeFiles = Storage::disk('public')->files($uniqueFolder);
        $timestamp = time();
        $zipName = 'qrcode-' . $excelName . '-' . $timestamp . '.zip';
        $zipPath = storage_path('app/public/' . $zipName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($qrcodeFiles as $file) {
                $zip->addFile(storage_path('app/public/' . $file), basename($file));
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
