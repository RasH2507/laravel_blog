@extends('dashboard.app')

@section('title', 'Komentar')

@section('content')
    @if ($success = Session::get('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <p>{{ $success }}</p>
        </div>
    @endif
    <a href="{{ route('komentar.create') }}" class="btn btn-dark mb-3">Tambah Komentar</a>
    <div class="table-responsive">
        <table class="table table-hover shadow">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID User</th>
                    <th>ID Artikel</th>
                    <th>Isi Komentar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($komentar as $comment)
                    <tr>
                        <td>{{ $comment->id_komentar }}</td>
                        <td>{{ $comment->user->id }} | {{ $comment->user->name }}</td>
                        <td>{{ $comment->artikel->id_artikel }} | {{ $comment->artikel->judul }}</td>
                        <td>{{ $comment->isi_komentar }}</td>
                        <td class="d-flex align-items-center">
                            <a href="{{ route('komentar.edit', $comment->id_komentar) }}"
                                class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2">
                                Edit
                            </a>
                            <span class="mx-1">|</span>
                            <form action="{{ route('komentar.destroy', $comment->id_komentar) }}" method="POST" class="d-inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
