<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index()
    {
        $apiKey = config('services.newsapi.key');
        $news = [];
        $error = null;

        try {
            $response = Http::get('https://newsapi.org/v2/everything?', [
                'q' => 'internship OR magang OR pendidikan',
                'sortBy' => 'publishedAt',
                'language' => 'id',
                'pageSize' => 12,
                'apiKey' => $apiKey,
            ]);

            if ($response->successful()) {
                $news = $response->json()['articles'] ?? [];
            } else {
                $error = 'Gagal mengambil berita';
            }
        } catch (\Exception $e) {
            $error = 'Terjadi kesalahan saat mengambil berita';
        }

        return view('news.index', compact('news', 'error'));
    }
}
