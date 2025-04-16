@extends('dashboard.app')

@section('title', 'Edit Game')

@section('content')
    <a href="{{ route('game.index') }}" class="btn btn-dark mb-3">Kembali</a>
    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('game.update', $game->id_game) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_game">Nama Game:</label>
                    <input type="text" class="form-control" id="nama_game" name="nama_game"
                        value="{{ old('nama_game', $game->nama_game) }}" required>
                </div>
                @error('nama_game')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <button type="submit" class="btn btn-dark btn-block">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

@endsection
