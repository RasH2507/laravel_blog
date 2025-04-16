@extends('dashboard.app')

@section('title', 'Pengguna')

@section('content')
    @if ($success = Session::get('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <p>{{ $success }}</p>
        </div>
    @endif
    <a href="{{ route('user.create') }}" class="btn btn-dark mb-3">Tambah Pengguna</a>
    <div class="table-responsive">
        <table class="table table-hover shadow">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user as $pengguna)
                    <tr>
                        <td>{{ $pengguna->id }}</td>
                        <td>{{ $pengguna->name }}</td>
                        <td>{{ $pengguna->email }}</td>
                        <td>{{ $pengguna->role }}</td>
                        <td class="d-flex align-items-center">
                            <a href="{{ route('user.edit', $pengguna->id) }}"
                                class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2">
                                Edit
                            </a>
                            <span class="mx-1">|</span>
                            <form action="{{ route('user.destroy', $pengguna->id) }}" method="POST" class="d-inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
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
