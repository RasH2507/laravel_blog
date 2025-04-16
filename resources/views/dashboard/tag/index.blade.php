@extends('dashboard.app')

@section('title', 'Tag')

@section('content')
    @if ($success = Session::get('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <p>{{ $success }}</p>
        </div>
    @endif
    <a href="{{ route('tag.create') }}" class="btn btn-dark mb-3">Tambah Tag</a>
    <div class="table-responsive">
        <table class="table table-hover shadow">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Tag</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tag as $tags)
                    <tr>
                        <td>{{ $tags->id_tag }}</td>
                        <td>{{ $tags->nama_tag }}</td>
                        <td class="d-flex align-items-center">
                            <a href="{{ route('tag.edit', $tags->id_tag) }}"
                                class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2">
                                Edit
                            </a>
                            <span class="mx-1">|</span>
                            <form action="{{ route('tag.destroy', $tags->id_tag) }}" method="POST" class="d-inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus tag ini?')">
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
