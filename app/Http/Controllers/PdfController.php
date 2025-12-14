<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function generateLetter(InternshipApplication $internship)
    {
        if (!$internship->letter_number) {
            return back()->with('error', 'Nomor surat belum digenerate.');
        }

        $data = [
            'internship' => $internship->load(['student', 'approvals.approver']),
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('pdf.letter', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Surat_Pengantar_{$internship->letter_number}.pdf");
    }

    public function downloadLetter(InternshipApplication $internship)
    {
        if (!$internship->letter_number) {
            return back()->with('error', 'Nomor surat belum digenerate.');
        }

        $data = [
            'internship' => $internship->load(['student', 'approvals.approver']),
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('pdf.letter', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download("Surat_Pengantar_{$internship->letter_number}.pdf");
    }
}