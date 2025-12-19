<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user,
            'weather' => $this->getWeather(),
        ];

        switch ($user->role) {
            case 'mahasiswa':
                $data['applications'] = InternshipApplication::where('student_id', $user->id)
                    ->with(['documents', 'approvals'])
                    ->latest()
                    ->get();
                $data['stats'] = [
                    'total' => $data['applications']->count(),
                    'pending' => $data['applications']->whereIn('status', ['diajukan', 'diverifikasi_jurusan'])->count(),
                    'approved' => $data['applications']->where('status', 'surat_terbit')->count(),
                    'completed' => $data['applications']->where('status', 'selesai')->count(),
                ];
                break;

            case 'admin_jurusan':
                $data['applications'] = InternshipApplication::whereIn('status', ['diajukan', 'revisi'])
                    ->with(['student', 'documents'])
                    ->latest()
                    ->get();
                $data['stats'] = [
                    'pending' => InternshipApplication::where('status', 'diajukan')->count(),
                    'verified' => InternshipApplication::where('status', 'diverifikasi_jurusan')->count(),
                    'revision' => InternshipApplication::where('status', 'revisi')->count(),
                    'total' => InternshipApplication::count(),
                ];
                break;

            case 'kaprodi':
            case 'kajur':
            case 'kpa':
            case 'wadir1':
                $statusMap = [
                    'kaprodi' => 'diverifikasi_jurusan',
                    'kajur' => 'disetujui_kaprodi',
                    'kpa' => 'disetujui_akademik',
                    'wadir1' => 'diproses_kpa',
                ];
                
                $data['applications'] = InternshipApplication::where('status', $statusMap[$user->role] ?? 'diajukan')
                    ->with(['student', 'documents', 'approvals'])
                    ->latest()
                    ->get();
                
                $data['stats'] = [
                    'pending' => $data['applications']->count(),
                    'approved_today' => InternshipApplication::whereHas('approvals', function($q) use ($user) {
                        $q->where('approved_by', $user->id)
                          ->whereDate('approved_at', today());
                    })->count(),
                    'total_approved' => InternshipApplication::whereHas('approvals', function($q) use ($user) {
                        $q->where('approved_by', $user->id);
                    })->count(),
                    'total' => InternshipApplication::count(),
                ];
                break;
        }

        $data['recent_activities'] = ActivityLog::where('user_id', $user->id)
            ->with('internshipApplication')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', $data);
    }

    private function getWeather()
    {
        try {
            $apiKey = config('services.weatherapi.key');
            if (!$apiKey) {
                return null;
            }

            $response = Http::get('https://api.weatherapi.com/v1/current.json', [
                'key' => $apiKey,
                'q' => 'Palembang',
                'aqi' => 'no'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'temp' => (int)$data['current']['temp_c'],
                    'condition' => $data['current']['condition']['text'],
                    'humidity' => $data['current']['humidity'],
                    'city' => $data['location']['name'] . ', ' . $data['location']['region'],
                ];
            }

            return null;
        } catch (\Exception $e) {
            \Log::warning('Weather API error: ' . $e->getMessage());
            return null;
        }
    }
}
