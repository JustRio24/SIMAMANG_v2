<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use App\Models\Document;
use App\Models\GeneratedDocument;
use App\Models\ActivityLog;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InternshipApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isMahasiswa()) {
            $applications = InternshipApplication::where('student_id', $user->id)
                ->with(['documents', 'approvals.approver'])
                ->latest()
                ->paginate(10);
        } else {
            $applications = InternshipApplication::with(['student', 'documents', 'approvals.approver'])
                ->latest()
                ->paginate(20);
        }

        return view('internships.index', compact('applications'));
    }

    public function create()
    {
        return view('internships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_city' => 'required|string',
            'company_phone' => 'nullable|string',
            'company_email' => 'nullable|email',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'internship_description' => 'nullable|string',
            'proposal' => 'required|file|mimes:pdf|max:5120',
            // 'surat_pengantar' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Calculate duration
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $duration = $start->diffInMonths($end);

        $application = InternshipApplication::create([
            'student_id' => Auth::id(),
            'company_name' => $validated['company_name'],
            'company_address' => $validated['company_address'],
            'company_city' => $validated['company_city'],
            'company_phone' => $validated['company_phone'],
            'company_email' => $validated['company_email'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_months' => $duration,
            'internship_description' => $validated['internship_description'],
            'status' => 'diajukan',
        ]);

        // Upload proposal
        if ($request->hasFile('proposal')) {
            $this->uploadDocument($request->file('proposal'), $application, 'proposal');
        }

        // // Upload surat pengantar
        // if ($request->hasFile('surat_pengantar')) {
        //     $this->uploadDocument($request->file('surat_pengantar'), $application, 'surat_pengantar');
        // }

        $this->logActivity('create_application', "Membuat pengajuan magang ke {$validated['company_name']}", $application->id);

        return redirect()->route('internships.show', $application)
            ->with('success', 'Pengajuan magang berhasil dibuat!');
    }

    public function show(InternshipApplication $internship)
    {
        $this->authorize('view', $internship);
        $generatedDocuments = GeneratedDocument::where(
            'internship_application_id',
            $internship->id
        )->get();
        
        $internship->load(['student', 'documents.uploader', 'approvals.approver', 'activityLogs.user']);
        
        return view('internships.show', compact('internship', 'generatedDocuments'));
    }

    public function edit(InternshipApplication $internship)
    {
        $this->authorize('update', $internship);
        
        if (!in_array($internship->status, ['diajukan', 'revisi'])) {
            return back()->with('error', 'Pengajuan tidak dapat diedit pada status ini.');
        }

        return view('internships.edit', compact('internship'));
    }

    public function update(Request $request, InternshipApplication $internship)
    {
        $this->authorize('update', $internship);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_city' => 'required|string',
            'company_phone' => 'nullable|string',
            'company_email' => 'nullable|email',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'internship_description' => 'nullable|string',
        ]);

        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $duration = $start->diffInMonths($end);

        // Determine new status: restore previous status if in revision, otherwise set to diajukan
        $newStatus = ($internship->status === 'revisi' && $internship->previous_status) 
            ? $internship->previous_status 
            : 'diajukan';

        $internship->update([
            'company_name' => $validated['company_name'],
            'company_address' => $validated['company_address'],
            'company_city' => $validated['company_city'],
            'company_phone' => $validated['company_phone'],
            'company_email' => $validated['company_email'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_months' => $duration,
            'internship_description' => $validated['internship_description'],
            'status' => $newStatus,
            'revision_note' => null,
            'previous_status' => null,
        ]);

        // Upload new documents if provided
        if ($request->hasFile('proposal')) {
            $this->uploadDocument($request->file('proposal'), $internship, 'proposal');
        }

        // if ($request->hasFile('surat_pengantar')) {
        //     $this->uploadDocument($request->file('surat_pengantar'), $internship, 'surat_pengantar');
        // }

        $this->logActivity('update_application', 'Mengupdate pengajuan magang', $internship->id);

        return redirect()->route('internships.show', $internship)
            ->with('success', 'Pengajuan magang berhasil diperbarui!');
    }

    public function uploadResponse(Request $request, InternshipApplication $internship)
    {
        $this->authorize('update', $internship);

        $request->validate([
            'response_letter' => 'nullable|file|mimes:pdf|max:5120',
            'official_letter' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Handle official letter upload from institution
        if ($request->hasFile('official_letter')) {
            $this->uploadDocument($request->file('official_letter'), $internship, 'surat_pengantar_resmi_instansi');
            $this->logActivity('upload_official_letter', 'Mengupload surat pengantar resmi dari instansi', $internship->id);
            return back()->with('success', 'Surat pengantar resmi berhasil diupload! Silakan upload surat balasan dari perusahaan.');
        }

        // Handle response letter upload from company
        if ($request->hasFile('response_letter')) {
            $this->uploadDocument($request->file('response_letter'), $internship, 'balasan');
            
            $internship->update(['status' => 'balasan_diterima']);
            
            $this->logActivity('upload_response', 'Mengupload surat balasan perusahaan', $internship->id);
            return back()->with('success', 'Surat balasan berhasil diupload!');
        }

        return back()->withErrors('Tidak ada file yang diunggah');
    }

    public function uploadGeneratedDocuments(
        Request $request,
        InternshipApplication $internship
    ) {
        $this->authorize('update', $internship);
    
        $request->validate([
            'documents' => 'required|array',
            'documents.*.id'   => 'required|exists:generated_documents,id',
            'documents.*.file' => 'required|file|mimes:pdf|max:10240',
        ]);
    
        $typeMap = [
            'surat_pengantar' => 'surat_pengantar_jurusan',
            'pengesahan'      => 'halaman_pengesahan_proposal',
        ];
    
        foreach ($request->documents as $key => $doc) {
            if (!isset($typeMap[$key])) {
                continue;
            }
    
            $this->storeGeneratedUpload(
                $doc['file'],
                $internship,
                $typeMap[$key]
            );
        }
    
        $this->logActivity(
            'upload_generated_documents',
            'Mengunggah surat pengantar dan halaman pengesahan',
            $internship->id
        );
    
        return back()->with(
            'success',
            'Surat Pengantar dan Halaman Pengesahan berhasil diupload.'
        );
    }
    
    
    
    private function uploadGeneratedDocumentFile(
        $file,
        InternshipApplication $internship,
        GeneratedDocument $document
    ) {
        $fileName = time() . '_' . $file->getClientOriginalName();
    
        $filePath = $file->storeAs(
            "documents/{$internship->id}",
            $fileName,
            'public'
        );
    
        $document->update([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
        ]);
    }
    

    private function uploadDocument($file, InternshipApplication $application, $type)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs("documents/{$application->id}", $fileName, 'public');

        Document::create([
            'internship_application_id' => $application->id,
            'type' => $type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);
    }

    private function logActivity($action, $description, $applicationId = null)
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

    public function downloadGeneratedDocument(
        InternshipApplication $internship,
        GeneratedDocument $document
    ) {
        if (
            $internship->student_id !== Auth::id() &&
            !Auth::user()->isPejabat()
        ) {
            abort(403, 'Unauthorized');
        }

        if (!Storage::exists($document->file_path)) {
            return back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $document->update([
            'status' => 'downloaded',
        ]);

        return Storage::download(
            $document->file_path,
            $document->file_name
        );
    }

    private function storeGeneratedUpload(
        $file,
        InternshipApplication $internship,
        string $documentType
    ) {
        $generatedDoc = GeneratedDocument::where(
            'internship_application_id',
            $internship->id
        )->where('document_type', $documentType)->firstOrFail();
    
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs(
            "documents/{$internship->id}",
            $fileName,
            'public'
        );
    
        $generatedDoc->update([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'status'    => 'uploaded',
        ]);
    
        Document::create([
            'internship_application_id' => $internship->id,
            'type'        => $documentType,
            'file_name'   => $file->getClientOriginalName(),
            'file_path'   => $filePath,
            'file_type'   => $file->getClientMimeType(),
            'file_size'   => $file->getSize(),
            'uploaded_by'=> Auth::id(),
        ]);
    }
    
}