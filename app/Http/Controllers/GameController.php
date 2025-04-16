<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $game = Game::where('nama_game', 'LIKE', "%$search%")
                ->orWhere('id_game', 'LIKE', "%$search%")
                ->get();
        } else {
            $game = Game::all();
        }
        return view('dashboard.game.index', compact(var_name: 'game'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.game.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_game' => 'required|string|max:100',
        ], [
            'nama_game.required' => 'Nama Game harus diisi.',
        ]);

        Game::create([
            'nama_game' => $request->nama_game,
        ]);

        return redirect()->route('game.index')->with('success', 'Game berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $game = Game::findOrFail($id);
        return view('dashboard.game.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_game' => 'required|string|max:100',
        ], [
            'nama_game.required' => 'Nama Game harus diisi.',
        ]);

        $game = Game::findOrFail($id);

        $game->nama_game = $validatedData['nama_game'];

        $game->save();

        return redirect()->route('game.index')->with('success', 'Game berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $game = Game::findOrFail($id);

        $game->delete();

        return redirect()->route('game.index')->with('success', 'Game berhasil dihapus.');
    }
}
