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
            'original_url' => 'required|url|max:255',
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
           if (!$shortUrl->is_active) {
              return view('shorturl.inactive');
           }
           $shortUrl->increment('usage_count');
           return redirect($shortUrl->original_url);
    }

    // Affiche le tableau de bord de l'utilisateur
    public function dashboard()
    {
        $urls = ShortUrl::where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderByDesc('id')
            ->paginate(10);
        return view('shorturl.dashboard', compact('urls'));
    }

    // Affiche le formulaire d'édition d'une URL courte
    public function edit($id)
    {
        $url = ShortUrl::findOrFail($id);
        $this->authorize('update', $url);
        return view('shorturl.edit', compact('url'));
    }

    // Met à jour l'URL courte
    public function update(Request $request, $id)
    {
        $url = ShortUrl::findOrFail($id);
        $this->authorize('update', $url);
        $request->validate([
            'original_url' => 'required|url',
        ]);
        $url->original_url = $request->original_url;
        $url->save();
        return redirect()->route('dashboard')->with('success', 'URL modifiée avec succès !');
    }

    // Supprime l'URL courte
    public function destroy($id)
    {
        $url = ShortUrl::findOrFail($id);
        $this->authorize('delete', $url);
        $url->is_active = false;
        $url->save();
        return redirect()->route('dashboard')->with('success', 'URL désactivée avec succès !');
    }
}
