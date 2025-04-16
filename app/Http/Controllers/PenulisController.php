<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Game;
use App\Models\Artikel;
use App\Models\TagArtikel;
use Illuminate\Http\Request;
use App\Models\ArtikelSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenulisController extends Controller
{
    public function myArticles(Request $request)
    {
        $user = Auth::user();
        $games = Game::all();

        $query = Artikel::where('id_user', $user->id);

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('konten', 'LIKE', "%{$search}%");
            });
        }

        $status = $request->input('status');
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
                $query->orderBy('judul', 'asc');
                break;
            default:
                $query->orderBy('updated_at', 'desc');
                break;
        }

        $artikels = $query->with('game')->paginate(10);

        $statusCounts = [
            'draft' => Artikel::where('id_user', $user->id)->where('status', 'draft')->count(),
            'pending' => Artikel::where('id_user', $user->id)->where('status', 'pending')->count(),
            'confirmed' => Artikel::where('id_user', $user->id)->where('status', 'confirmed')->count(),
            'rejected' => Artikel::where('id_user', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('frontend.penulis.index', compact('artikels', 'user', 'games', 'search', 'status', 'sort', 'statusCounts'));
    }
    public function createArtikel()
    {
        $user = Auth::user();
        $games = Game::all();
        $tags = Tag::all();

        return view('frontend.penulis.create', compact('user', 'games', 'tags'));
    }

    public function storeArtikel(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'id_game' => 'required|exists:game,id_game',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:draft,pending',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id_tag',
            'sections' => 'nullable|array',
            'sections.*.sub_judul' => 'required|string|max:255',
            'sections.*.media_type' => 'required|in:image,embed',
            'sections.*.media_content' => 'nullable|string|required_if:sections.*.media_type,embed',
            'sections.*.media_content_file' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5120|required_if:sections.*.media_type,image',
            'sections.*.deskripsi' => 'nullable|string',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        $artikel = Artikel::create([
            'id_user' => $user->id, 
            'id_game' => $validatedData['id_game'],
            'judul' => $validatedData['judul'],
            'konten' => $validatedData['konten'],
            'image' => $imagePath,
            'status' => $validatedData['status'],
        ]);

        if (!empty($validatedData['tags'])) {
            foreach ($validatedData['tags'] as $id_tag) {
                TagArtikel::create([
                    'id_artikel' => $artikel->id_artikel,
                    'id_tag' => $id_tag,
                ]);
            }
        }

        if (!empty($validatedData['sections'])) {
            foreach ($validatedData['sections'] as $index => $section) {
                $mediaContent = null;

                if ($section['media_type'] === 'image') {
                    if ($request->hasFile("sections.$index.media_content_file")) {
                        $mediaContent = $request->file("sections.$index.media_content_file")->store('media', 'public');
                    }
                } elseif ($section['media_type'] === 'embed') {
                    $mediaContent = $section['media_content'];
                }

                ArtikelSection::create([
                    'id_artikel' => $artikel->id_artikel,
                    'sub_judul' => $section['sub_judul'],
                    'media_type' => $section['media_type'],
                    'media_content' => $mediaContent,
                    'deskripsi' => $section['deskripsi'] ?? null,
                    'urutan' => $index + 1,
                ]);
            }
        }

        return redirect()->route('penulis.myarticles')->with('success', 'Article successfully created!');
    }

    public function editArtikel($id)
    {
        $user = Auth::user();
        $artikel = Artikel::where('id_artikel', $id)
            ->where('id_user', $user->id)
            ->firstOrFail();

        $games = Game::all();
        $tags = Tag::all();
        $artikelSections = $artikel->sections;
        $selectedTags = $artikel->tags->pluck('id_tag')->toArray();

        return view('frontend.penulis.edit', compact('artikel', 'user', 'games', 'tags', 'artikelSections', 'selectedTags'));
    }


    public function updateArtikel(Request $request, $id)
{
    $user = Auth::user();
    $artikel = Artikel::where('id_artikel', $id)
        ->where('id_user', $user->id)
        ->firstOrFail();

    $validatedData = $request->validate([
        'id_game' => 'required|exists:game,id_game',
        'judul' => 'required|string|max:255',
        'konten' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'status' => 'required|in:draft,pending',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tag,id_tag',
        'sections' => 'nullable|array',
        'sections.*.sub_judul' => 'required|string|max:255',
        'sections.*.media_type' => 'nullable|in:image,embed',
        'sections.*.media_content_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'sections.*.media_content' => 'nullable|string',
        'sections.*.deskripsi' => 'nullable|string',
        'sections.*.original_media_content' => 'nullable|string',
    ]);

    $imagePath = $artikel->image;
    if ($request->hasFile('image')) {
        if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
            Storage::disk('public')->delete($artikel->image);
        }
        $imagePath = $request->file('image')->store('images', 'public');
    }

    $artikel->update([
        'id_game' => $validatedData['id_game'],
        'judul' => $validatedData['judul'],
        'konten' => $validatedData['konten'],
        'image' => $imagePath,
        'status' => $validatedData['status'],
    ]);

    $artikel->tags()->sync($validatedData['tags'] ?? []);

    // Delete existing sections
    $artikel->sections()->delete();

    if (!empty($validatedData['sections'])) {
        foreach ($validatedData['sections'] as $index => $section) {
            $mediaContent = null;

            if (isset($section['media_type'])) {
                if ($section['media_type'] === 'image') {
                    // Check if a new file was uploaded
                    if ($request->hasFile("sections.$index.media_content_file")) {
                        $mediaContent = $request->file("sections.$index.media_content_file")->store('media', 'public');
                    } 
                    // Use the original media content if it exists and no new file was uploaded
                    elseif (isset($section['original_media_content']) && !empty($section['original_media_content'])) {
                        $mediaContent = $section['original_media_content'];
                    }
                } elseif ($section['media_type'] === 'embed') {
                    $mediaContent = $section['media_content'] ?? null;
                }
            }

            ArtikelSection::create([
                'id_artikel' => $artikel->id_artikel,
                'sub_judul' => $section['sub_judul'],
                'media_type' => $section['media_type'] ?? null,
                'media_content' => $mediaContent,
                'deskripsi' => $section['deskripsi'] ?? null,
                'urutan' => $index + 1,
            ]);
        }
    }

    return redirect()->route('penulis.myarticles')->with('success', 'Article successfully updated!');
}


    public function destroyArtikel($id)
    {
        $user = Auth::user();
        $artikel = Artikel::where('id_artikel', $id)
            ->where('id_user', $user->id)
            ->firstOrFail();

        if ($artikel->image) {
            Storage::disk('public')->delete($artikel->image);
        }

        foreach ($artikel->sections as $section) {
            if ($section->media_type === 'image' && $section->media_content) {
                Storage::disk('public')->delete($section->media_content);
            }
        }

        $artikel->delete();

        return redirect()->route('penulis.myarticles')->with('success', 'Article successfully deleted.');
    }
}
