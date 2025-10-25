<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Auth;

class ShortUrlController extends Controller
{
    // Affiche le formulaire de création d'URL courte
    public function create()
    {
        return view('shorturl.create');
    }

    // Traite la soumission du formulaire et crée l'URL courte
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $short = substr(md5($request->original_url . time()), 0, 6);

        $shortUrl = ShortUrl::create([
            'original_url' => $request->original_url,
            'short_url' => $short,
            'user_id' => Auth::id(),
            'usage_count' => 0,
        ]);

        return redirect()->route('dashboard')->with('success', 'URL raccourcie créée !');
    }

    // Redirige vers l'URL originale et incrémente le compteur
    public function redirect($short)
    {
        $shortUrl = ShortUrl::where('short_url', $short)->firstOrFail();
        $shortUrl->increment('usage_count');
        return redirect($shortUrl->original_url);
    }

    // Affiche le tableau de bord de l'utilisateur
    public function dashboard()
    {
        $urls = ShortUrl::where('user_id', Auth::id())->orderByDesc('id')->paginate(10);
        return view('shorturl.dashboard', compact('urls'));
    }
}
