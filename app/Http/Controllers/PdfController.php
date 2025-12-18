<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfController extends Controller
{
    public function generateLetter(InternshipApplication $internship)
    {
        return $this->buildPdf($internship, 'stream');
    }

    public function downloadLetter(InternshipApplication $internship)
    {
        return $this->buildPdf($internship, 'download');
    }

    /**
     * Builder PDF Surat Pengantar
     */
    private function buildPdf(InternshipApplication $internship, string $mode)
    {
        if (!$internship->letter_number) {
            return back()->with('error', 'Nomor surat belum digenerate.');
        }

        $internship->load(['student', 'approvals.approver']);

        // Ambil approval Wadir 1
        $wadir1Approval = $internship->approvals
            ->where('role', 'wadir1')
            ->first();

        // QR default null
        $qrBase64 = null;

        // Generate QR HANYA jika sudah disetujui
        if ($wadir1Approval) {
            $svg = QrCode::format('svg')
                ->size(120)
                ->margin(1)
                ->generate(route('internships.show', $internship));

            $qrBase64 = base64_encode($svg);
        }

        $data = [
            'internship'     => $internship,
            'generated_at'   => now(),
            'wadir1Approval' => $wadir1Approval,
            'qrBase64'       => $qrBase64,
        ];

        $pdf = Pdf::loadView('pdf.letter', $data)
            ->setPaper('legal', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
            ]);

        $safeFilename = str_replace(['/', '\\'], '-', $internship->letter_number);

        return $mode === 'stream'
            ? $pdf->stream("Surat_Pengantar_{$safeFilename}.pdf")
            : $pdf->download("Surat_Pengantar_{$safeFilename}.pdf");
    }
}
