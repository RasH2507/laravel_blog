@extends('dashboard.app')

@section('title', 'Tambah Komentar')

@section('content')
    <a href="{{ route('komentar.index') }}" class="btn btn-dark mb-3">Kembali</a>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('komentar.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="id_artikel">Artikel:</label>
                    <select class="form-control" id="id_artikel" name="id_artikel" required>
                        <option value="">Pilih Artikel...</option>
                        @foreach ($artikel as $artikels)
                            <option value="{{ $artikels->id_artikel }}" {{ old('id_artikel') == $artikels->id_artikel ? 'selected' : '' }}>
                                {{ $artikels->id_artikel }} | {{ $artikels->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_user">User:</label>
                    <select class="form-control" id="id_user" name="id_user" required>
                        <option value="">Pilih User...</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                {{ $user->id }} | {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="isi_komentar">Isi Komentar:</label>
                    <textarea class="form-control" name="isi_komentar" rows="5" required>{{ old('isi_komentar') }}</textarea>
                </div>

        
                <div class="form-group">
                    <button type="submit" class="btn btn-dark btn-block">Tambah</button>
                </div>
            </form>
        </div>
    </div>

@endsection
