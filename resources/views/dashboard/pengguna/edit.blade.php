@extends('dashboard.app')

@section('title', 'Edit Pengguna')

@section('content')
    <a href="{{ route('user.index') }}" class="btn btn-dark mb-3">Kembali</a>
    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" class="form-control" id="nama" name="name"
                        value="{{ old('name', $user->name) }}" required>
                </div>
                @error('name')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $user->email) }}" required>
                </div>
                @error('email')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                @error('password')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="role">Role:</label>
                    <select class="form-control" name="role" id="role">
                        <option value="penulis" {{ $user->role == 'penulis' ? 'selected' : '' }}>Penulis</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-dark btn-block">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

@endsection
