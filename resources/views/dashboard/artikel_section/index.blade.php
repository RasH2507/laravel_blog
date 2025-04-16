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
                    <th>Urutan</th>
                    <th>Sub Judul</th>
                    <th>Media</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($artikelSection as $index => $section)
                    <tr>
                        <td>{{ $section->id_section }}</td>
                        <td>ID: {{ $section->artikel->id_artikel }}</td>
                        <td>{{ $section->urutan }}</td>
                        <td>{{ $section->sub_judul }}</td>
                        <td>
                            @if ($section->media_type == 'image')
                                <img src="{{ asset('storage/' . $section->media_content) }}" width="100">
                            @elseif ($section->media_type == 'video')
                                <video width="150" controls>
                                    <source src="{{ asset('storage/' . $section->media_content) }}" type="video/mp4">
                                </video>
                            @elseif ($section->media_type == 'embed')
                                <a href="{{ $section->media_content }}" target="_blank">Lihat Embed</a>
                            @else
                                Tidak ada media
                            @endif
                        </td>
                        <td>{{ $section->deskripsi }}</td>
                        <td>
                            <a
                                href="{{ route('artikel.edit', $section->artikel->id_artikel) }}"class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2">
                                Edit Artikel
                            </a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
