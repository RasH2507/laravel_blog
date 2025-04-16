@extends('dashboard.app')

@section('title', 'Artikel Section')

@section('content')

    <a href="{{ route('artikel.index') }}" class="btn btn-dark mb-3">Kembali ke Artikel</a>

    <div class="table-responsive">
        <table class="table table-hover shadow">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Artikel</th>
                    <th>Tag</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tagArtikel as $tagS)
                        <tr>
                            <td>{{ $tagS->id_tag_artikel }}</td>
                            <td>ID: {{ $tagS->artikel->id_artikel }} | {{ $tagS->artikel->judul }}</td>
                            <td>ID: {{ $tagS->tag->id_tag }} | {{ $tagS->tag->nama_tag }}</td>
                            <td>
                                    <a href="{{ route('artikel.edit', $tagS->artikel->id_artikel) }}"class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2">
                                        Edit Artikel
                                    </a>
                                
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
@endsection
