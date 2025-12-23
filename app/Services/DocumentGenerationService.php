<?php

namespace App\Services;

use App\Models\InternshipApplication;
use App\Models\GeneratedDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class DocumentGenerationService
{
    public function generateDepartmentLetterAndEndorsement(InternshipApplication $internship)
    {
        $this->generateDepartmentLetter($internship);
        $this->generateEndorsementPage($internship);
    }

    private function generateDepartmentLetter(InternshipApplication $internship)
    {
        $internship->load(['student', 'approvals.approver']);
        $signatures = $this->collectSignatures($internship, ['kaprodi', 'kajur']);

        // $qrBase64 = $this->generateQrSignature(
        //     $internship,
        //     'surat_pengantar_jurusan'
        // );

        // $generatedAt = now();

        $qrKaprodi = isset($signatures['kaprodi'])
        ? $this->generateQrSignature(
            $internship,
            'surat_pengantar_jurusan|kaprodi'
        )
        : null;

        $qrKajur = isset($signatures['kajur'])
        ? $this->generateQrSignature(
            $internship,
            'surat_pengantar_jurusan|kajur'
        )
        : null;

        $pdf = Pdf::loadView('pdf.surat-pengantar-jurusan', [
            'internship'   => $internship,
            'student'      => $internship->student,
            'generated_at' => now(),
            'signatures'   => $signatures,
            'qrKaprodi'    => $qrKaprodi,
            'qrKajur'      => $qrKajur,
        ])->setPaper('a4', 'portrait');

        $fileName = "surat_pengantar_jurusan_{$internship->id}_" . time() . ".pdf";
        $filePath = "generated_documents/{$internship->id}/";

        Storage::makeDirectory($filePath);
        Storage::put($filePath . $fileName, $pdf->output());

        GeneratedDocument::updateOrCreate(
            [
                'internship_application_id' => $internship->id,
                'document_type' => 'surat_pengantar_jurusan',
            ],
            [
                'file_path' => $filePath . $fileName,
                'file_name' => $fileName,
                'status'    => 'generated',
                'signatures'=> $signatures,
            ]
        );
    }

    private function generateEndorsementPage(InternshipApplication $internship)
    {
        $internship->load(['student', 'approvals.approver']);
        $signatures = $this->collectSignatures($internship, ['kaprodi', 'kajur']);

        $qrKaprodi = isset($signatures['kaprodi'])
        ? $this->generateQrSignature(
            $internship,
            'surat_pengantar_jurusan|kaprodi'
        )
        : null;

        $qrKajur = isset($signatures['kajur'])
        ? $this->generateQrSignature(
            $internship,
            'surat_pengantar_jurusan|kajur'
        )
        : null;

        // $qrBase64 = $this->generateQrSignature(
        //     $internship,
        //     'halaman_pengesahan_proposal'
        // );

        // $generatedAt = now();

        $pdf = Pdf::loadView('pdf.halaman-pengesahan', [
            'internship'   => $internship,
            'student'      => $internship->student,
            'generated_at' => now(),
            'signatures'   => $signatures,
            'qrKaprodi'    => $qrKaprodi,
            'qrKajur'      => $qrKajur,
        ])->setPaper('a4', 'portrait');
        
        // $pdf = Pdf::loadView('pdf.halaman-pengesahan', [
        //     'internship'   => $internship,
        //     'student'      => $internship->student,
        //     'generated_at' => $generatedAt,
        //     'signatures'   => $signatures,
        //     'qrBase64'     => $qrBase64,
        // ]);

        $fileName = "halaman_pengesahan_{$internship->id}_" . time() . ".pdf";
        $filePath = "generated_documents/{$internship->id}/";

        Storage::makeDirectory($filePath);
        Storage::put($filePath . $fileName, $pdf->output());

        GeneratedDocument::updateOrCreate(
            [
                'internship_application_id' => $internship->id,
                'document_type' => 'halaman_pengesahan_proposal',
            ],
            [
                'file_path' => $filePath . $fileName,
                'file_name' => $fileName,
                'status'    => 'generated',
                'signatures'=> $signatures,
            ]
        );
    }

    /**
     * 🔐 Generate QR Code e-Signature
     */
    private function generateQrSignature(
        InternshipApplication $internship,
        string $documentType
    ): string {
        $payload = [
            'doc'        => $documentType,
            'internship' => $internship->id,
            'hash'       => hash(
                'sha256',
                $internship->id . $documentType . config('app.key')
            ),
            'issued_at'  => now()->toDateTimeString(),
        ];

        $qrSvg = QrCode::format('svg')
            ->size(200)
            ->generate(json_encode($payload));

        return base64_encode($qrSvg);
    }

    private function collectSignatures(InternshipApplication $internship, array $roles): array
    {
        $signatures = [];

        $approvals = $internship->approvals()
            ->whereIn('role', $roles)
            ->where('action', 'approve')
            ->get();

        foreach ($approvals as $approval) {

            // Payload QR → beda tiap pejabat
            $qrPayload = route('internships.show', $internship) .
                '?role=' . $approval->role .
                '&approval=' . $approval->id;

            // Generate SVG QR (sama persis konsep Wadir 1)
            $svg = QrCode::format('svg')
                ->size(120)
                ->margin(1)
                ->generate($qrPayload);

            $signatures[$approval->role] = [
                'name' => $approval->approver->name ?? '',
                'nip'  => $approval->approver->nip ?? '',
                'date' => $approval->approved_at?->translatedFormat('d F Y'),
                'role' => $approval->role,
                'qr'   => base64_encode($svg), // 🔥 INI KUNCINYA
            ];
        }

        return $signatures;
    }

}
