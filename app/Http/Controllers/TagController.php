<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $tag = Tag::where('nama_tag', 'LIKE', "%$search%")
                ->orWhere('id_tag', 'LIKE', "%$search%")
                ->get();
        } else {
            $tag = Tag::all();
        }
        return view('dashboard.tag.index', compact('tag'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_tag' => 'required|string|max:100',
        ], [
            'nama_tag.required' => 'Nama Tag harus diisi.',
        ]);

        Tag::create([
            'nama_tag' => $request->nama_tag,
        ]);

        return redirect()->route('tag.index')->with('success', 'Tag berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('dashboard.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_tag' => 'required|string|max:100',
        ], [
            'nama_tag.required' => 'Nama Tag harus diisi.',
        ]);

        $tag = Tag::findOrFail($id);

        $tag->nama_tag = $validatedData['nama_tag'];

        $tag->save();

        return redirect()->route('tag.index')->with('success', 'Tag berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        $tag->delete();

        return redirect()->route('tag.index')->with('success', 'Tag berhasil dihapus.');
    }
}
