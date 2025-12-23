<?php
// app/Http/Controllers/ApprovalController.php
namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use App\Models\Approval;
use App\Models\ActivityLog;
use App\Models\GeneratedDocument;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    public function regen(InternshipApplication $internship)
    {
        // DEV ONLY
        if (!app()->environment('local')) {
            abort(403);
        }

        // hapus file + db
        Storage::deleteDirectory("generated_documents/{$internship->id}");
        GeneratedDocument::where('internship_application_id', $internship->id)->delete();

        // generate ulang
        (new DocumentGenerationService)
            ->generateDepartmentLetterAndEndorsement($internship);

        return back()->with('success', 'Dokumen digenerate ulang');
    }

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
            'kaprodi' => 'diverifikasi_jurusan',
            'kajur' => 'disetujui_kaprodi',
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
            'kaprodi' => 'disetujui_kaprodi',
            'kajur' => 'disetujui_akademik',
            'kpa' => 'diproses_kpa',
            'wadir1' => 'disetujui_wadir1',
        ];

        $internship->update(['status' => $nextStatus[$user->role]]);
        
        // Create approval and generate signature
        $approval = $this->createApproval($internship, $user->role, 'approve', $request->note);
        $this->generateSignature($approval, $internship);
        
        $this->logActivity('approve_application', "Menyetujui pengajuan magang sebagai {$user->role}", $internship->id);

        // Generate documents automatically when all three levels approve
        if ($user->role === 'kajur') {
            $service = new DocumentGenerationService();
            $service->generateDepartmentLetterAndEndorsement($internship);
            $this->logActivity('auto_generate_documents', 'Dokumen surat pengantar jurusan dan halaman pengesahan di-generate otomatis', $internship->id);
        }

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
        $approval = $this->createApproval($internship, 'kpa', 'approve', 'Surat pengantar resmi telah digenerate');
        $this->generateSignature($approval, $internship);
        
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

    private function generateSignature(Approval $approval, InternshipApplication $internship)
    {
        try {
            $user = Auth::user();
            
            // Create QR data - using verification URL for easier scanning
            $qrData = route('internships.show', $internship);

            // Generate QR Code using simple-qrcode in SVG format (compatible with dompdf)
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(200)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($qrData);
            
            // Save QR Code as SVG
            $qrPath = "signatures/qr_{$approval->id}.svg";
            Storage::disk('public')->put($qrPath, $qrCode);

            // Update approval with QR path
            $approval->update([
                'qr_code_path' => $qrPath,
            ]);

            return true;
        } catch (\Exception $e) {
            // Log error but don't fail the approval process
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            
            // Create a simple SVG fallback with text
            $fallbackSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <rect width="100" height="100" fill="#f0f0f0" stroke="#ccc"/>
                <text x="50" y="50" text-anchor="middle" font-size="10" fill="#666">QR Error</text>
            </svg>';
            
            $fallbackPath = "signatures/qr_{$approval->id}.svg";
            Storage::disk('public')->put($fallbackPath, $fallbackSvg);
            
            $approval->update([
                'qr_code_path' => $fallbackPath,
            ]);
            
            return false;
        }
    }

    public function revise(Request $request, InternshipApplication $internship)
    {
        $user = Auth::user();
        
        if (!$user->isPejabat()) {
            return back()->with('error', 'Unauthorized action.');
        }
        $request->validate([
            'note' => 'required|string',
        ]);
        // Store previous status before revision
        $previousStatus = $internship->status;
        
        // Update status to revisi with previous status stored
        $internship->update([
            'status' => 'revisi',
            'revision_note' => $request->note,
            'previous_status' => $previousStatus,
        ]);
        // Create approval record for revision
        $this->createApproval($internship, $user->role, 'revise', $request->note);
        $this->logActivity('request_revision', "Meminta revisi pengajuan magang sebagai {$user->role}", $internship->id);
        return back()->with('warning', 'Pengajuan dikembalikan untuk revisi.');
    }

    private function generateLetterNumber(InternshipApplication $internship)
    {
        // Format: XXX/POLSRI-MAGANG/YEAR/MONTH
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
