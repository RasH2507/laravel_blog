@extends('dashboard.app')

@section('title', 'Game')

@section('content')
    @if ($success = Session::get('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <p>{{ $success }}</p>
        </div>
    @endif
    <a href="{{ route('game.create') }}" class="btn btn-dark mb-3">Tambah Game</a>
    <div class="table-responsive">
        <table class="table table-hover shadow">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Game</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($game as $games)
                    <tr>
                        <td>{{ $games->id_game }}</td>
                        <td>{{ $games->nama_game }}</td>
                        <td class="d-flex align-items-center">
                            <a href="{{ route('game.edit', $games->id_game) }}"
                                class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2">
                                Edit
                            </a>
                            <span class="mx-1">|</span>
                            <form action="{{ route('game.destroy', $games->id_game) }}" method="POST" class="d-inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus game ini?')">
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
