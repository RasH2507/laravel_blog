@extends('dashboard.app')

@section('title', 'Edit Tag')

@section('content')
    <a href="{{ route('tag.index') }}" class="btn btn-dark mb-3">Kembali</a>
    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('tag.update', $tag->id_tag) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_tag">Nama Tag:</label>
                    <input type="text" class="form-control" id="nama_tag" name="nama_tag"
                        value="{{ old('nama_tag', $tag->nama_tag) }}" required>
                </div>
                @error('nama_tag')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <button type="submit" class="btn btn-dark btn-block">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

@endsection
