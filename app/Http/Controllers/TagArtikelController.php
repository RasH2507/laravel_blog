<?php

namespace App\Http\Controllers;

use App\Models\TagArtikel;
use Illuminate\Http\Request;

class TagArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tagArtikel = TagArtikel::with(['artikel', 'tag'])->get();
        return view('dashboard.tag_artikel.index', compact('tagArtikel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TagArtikel $tagArtikel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TagArtikel $tagArtikel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TagArtikel $tagArtikel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TagArtikel $tagArtikel)
    {
        //
    }
}
