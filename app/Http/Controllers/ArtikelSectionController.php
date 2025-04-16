<?php

namespace App\Http\Controllers;

use App\Models\ArtikelSection;
use Illuminate\Http\Request;

class ArtikelSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artikelSection= ArtikelSection::with('artikel')->get();
        return view('dashboard.artikel_section.index', compact('artikelSection'));
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
    public function show(ArtikelSection $artikelSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArtikelSection $artikelSection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArtikelSection $artikelSection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArtikelSection $artikelSection)
    {
        //
    }
}
