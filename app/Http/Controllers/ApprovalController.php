<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use App\Models\Approval;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    public function verify(Request $request, InternshipApplication $internship)
    {
        // Admin Jurusan verification
        if (Auth::user()->role !== 'admin_jurusan') {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($internship->status !== 'diajukan') {
            return back()->with('error', 'Pengajuan tidak dapat diverifikasi pada status ini.');
        }

        $request->validate([
            'action' => 'required|in:verify,revise',
            'note' => 'nullable|string',
        ]);

        if ($request->action === 'verify') {
            $internship->update([
                'status' => 'diverifikasi_jurusan',
                'revision_note' => null,
            ]);

            $this->createApproval($internship, 'admin_jurusan', 'approve', $request->note);
            $this->logActivity('verify_application', 'Memverifikasi pengajuan magang', $internship->id);

            return back()->with('success', 'Pengajuan berhasil diverifikasi!');
        } else {
            $internship->update([
                'status' => 'revisi',
                'revision_note' => $request->note,
            ]);

            $this->createApproval($internship, 'admin_jurusan', 'revise', $request->note);
            $this->logActivity('request_revision', 'Meminta revisi pengajuan magang', $internship->id);

            return back()->with('warning', 'Pengajuan dikembalikan untuk revisi.');
        }
    }

    public function approve(Request $request, InternshipApplication $internship)
    {
        $user = Auth::user();
        
        if (!$user->isPejabat()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Check if status matches role
        $statusMap = [
            'sekjur' => 'diverifikasi_jurusan',
            'kajur' => 'disetujui_sekjur',
            'kpa' => 'disetujui_akademik',
            'wadir1' => 'diproses_kpa',
        ];

        if ($internship->status !== $statusMap[$user->role]) {
            return back()->with('error', 'Pengajuan tidak dapat disetujui pada status ini.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'note' => 'nullable|string',
        ]);

        if ($request->action === 'reject') {
            $internship->update(['status' => 'ditolak']);
            $this->createApproval($internship, $user->role, 'reject', $request->note);
            $this->logActivity('reject_application', 'Menolak pengajuan magang', $internship->id);

            return back()->with('error', 'Pengajuan ditolak.');
        }

        // Approve and move to next status
        $nextStatus = [
            'sekjur' => 'disetujui_sekjur',
            'kajur' => 'disetujui_akademik',
            'kpa' => 'diproses_kpa',
            'wadir1' => 'disetujui_wadir1',
        ];

        $internship->update(['status' => $nextStatus[$user->role]]);
        
        // Generate signature and QR code
        $approval = $this->createApproval($internship, $user->role, 'approve', $request->note);
        $this->generateSignature($approval);
        
        $this->logActivity('approve_application', "Menyetujui pengajuan magang sebagai {$user->role}", $internship->id);

        // If KPA, generate letter number
        if ($user->role === 'kpa') {
            $this->generateLetterNumber($internship);
        }

        // If Wadir1, mark as letter issued
        if ($user->role === 'wadir1') {
            $internship->update(['status' => 'surat_terbit']);
        }

        return back()->with('success', 'Pengajuan berhasil disetujui!');
    }

    public function generateLetter(InternshipApplication $internship)
    {
        if (Auth::user()->role !== 'kpa') {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($internship->status !== 'disetujui_akademik') {
            return back()->with('error', 'Surat hanya dapat digenerate pada status yang sesuai.');
        }

        // Generate letter number if not exists
        if (!$internship->letter_number) {
            $this->generateLetterNumber($internship);
        }

        // Update status
        $internship->update(['status' => 'diproses_kpa']);

        // Create approval record
        $this->createApproval($internship, 'kpa', 'approve', 'Surat pengantar resmi telah digenerate');
        $this->logActivity('generate_letter', 'Generate surat pengantar resmi', $internship->id);

        return redirect()->route('pdf.letter', $internship)
            ->with('success', 'Surat pengantar berhasil digenerate!');
    }

    private function createApproval($internship, $role, $action, $note = null)
    {
        return Approval::create([
            'internship_application_id' => $internship->id,
            'role' => $role,
            'approved_by' => Auth::id(),
            'action' => $action,
            'note' => $note,
            'approved_at' => now(),
        ]);
    }

    private function generateSignature(Approval $approval)
    {
        $user = Auth::user();
        
        // Generate QR Code
        $qrData = json_encode([
            'approval_id' => $approval->id,
            'application_id' => $approval->internship_application_id,
            'approver' => $user->name,
            'role' => $user->role,
            'timestamp' => $approval->approved_at->toIso8601String(),
        ]);

        $qrCode = QrCode::format('svg')->size(200)->generate($qrData);
        
        $qrPath = "signatures/qr_{$approval->id}.svg";
        Storage::disk('public')->put($qrPath, $qrCode);

        $approval->update([
            'qr_code_path' => $qrPath,
        ]);
    }

    private function generateLetterNumber(InternshipApplication $internship)
    {
        // Format: XXX/POLSRI/YEAR/MONTH
        $year = date('Y');
        $month = date('m');
        
        // Get last number this month
        $lastNumber = InternshipApplication::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('letter_number')
            ->count();

        $number = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $letterNumber = "{$number}/POLSRI-MAGANG/{$year}/{$month}";

        $internship->update(['letter_number' => $letterNumber]);
    }

    private function logActivity($action, $description, $applicationId)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'internship_application_id' => $applicationId,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}