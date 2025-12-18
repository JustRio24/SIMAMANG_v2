<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin_jurusan') {
            $templates = Template::with('uploader')->latest()->get();
            return view('templates.admin', compact('templates'));
        }
        
        $templates = Template::where('is_active', true)->latest()->get();
        return view('templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin_jurusan') {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('templates', 'public');

        Template::create([
            'name' => $request->name,
            'description' => $request->description,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Template berhasil diupload!');
    }

    public function download(Template $template)
    {
        if (!$template->is_active && Auth::user()->role !== 'admin_jurusan') {
            return back()->with('error', 'Template tidak tersedia.');
        }

        return Storage::disk('public')->download($template->file_path, $template->file_name);
    }

    public function toggleStatus(Template $template)
    {
        if (Auth::user()->role !== 'admin_jurusan') {
            return back()->with('error', 'Unauthorized action.');
        }

        $template->update(['is_active' => !$template->is_active]);

        return back()->with('success', 'Status template berhasil diubah!');
    }

    public function destroy(Template $template)
    {
        if (Auth::user()->role !== 'admin_jurusan') {
            return back()->with('error', 'Unauthorized action.');
        }

        Storage::disk('public')->delete($template->file_path);
        $template->delete();

        return back()->with('success', 'Template berhasil dihapus!');
    }
}
