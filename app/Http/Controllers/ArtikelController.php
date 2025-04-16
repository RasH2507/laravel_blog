<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Game;
use App\Models\User;
use App\Models\Artikel;
use App\Models\TagArtikel;
use Illuminate\Http\Request;
use App\Models\ArtikelSection;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $artikel = Artikel::with(['user'])
                ->where('id_artikel', 'LIKE', "%$search%")
                ->orWhere('status', 'LIKE', "%$search%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name',  'LIKE', "%$search%");
                })
                ->get();
        } else {
            $artikel = Artikel::with(['user'])->get();
        }

        return view('dashboard.artikel.index', compact('artikel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $game = Game::all();
        $tags = Tag::all();
        return view('dashboard.artikel.create', compact('users', 'game', 'tags'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
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
            'id_user' => $validatedData['id_user'],
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

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $artikel = Artikel::with([
            'user',
            'game',
            'tags',
            'sections',
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'comments.user'
        ])->findOrFail($id);

        if ($artikel->status !== 'confirmed') {
            abort(404);
        }

        $games = Game::all();

        return view('frontend.artikel', compact('artikel','games'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artikel $artikel)
    {
        $users = User::all();
        $game = Game::all();
        $tags = Tag::all();
        $artikelSections = $artikel->sections;
        $selectedTags = $artikel->tags->pluck('id_tag')->toArray();

        return view('dashboard.artikel.edit', compact('artikel', 'users', 'game', 'tags', 'artikelSections', 'selectedTags'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
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
            'sections.*.media_content' => 'nullable|string|required_if:sections.*.media_type,embed',
            'sections.*.deskripsi' => 'nullable|string',
        ]);

        $imagePath = $artikel->image; 
        if ($request->hasFile('image')) {
            if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
                Storage::disk('public')->delete($artikel->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $artikel->update([
            'id_user' => $validatedData['id_user'],
            'id_game' => $validatedData['id_game'],
            'judul' => $validatedData['judul'],
            'konten' => $validatedData['konten'],
            'image' => $imagePath,
            'status' => $validatedData['status'],
        ]);

        $artikel->tags()->sync($validatedData['tags'] ?? []);

        $artikel->sections()->delete();

        if (!empty($validatedData['sections'])) {
            foreach ($validatedData['sections'] as $index => $section) {
                $mediaContent = null;

                if (isset($section['media_type']) && $section['media_type'] === 'image') {
                    if ($request->hasFile("sections.$index.media_content_file")) {
                        $mediaContent = $request->file("sections.$index.media_content_file")->store('media', 'public');
                    }
                    elseif (isset($section['media_content'])) {
                        $mediaContent = $section['media_content'];
                    }
                }
                elseif (isset($section['media_type']) && $section['media_type'] === 'embed') {
                    $mediaContent = $section['media_content'] ?? null;
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

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->image) {
            Storage::disk('public')->delete($artikel->image);
        }

        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function confirm($id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->update(['status' => 'confirmed']);

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil dikonfirmasi.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $artikel = Artikel::findOrFail($id);
        $artikel->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('artikel.index')->with('success', 'Artikel telah ditolak.');
    }
}
