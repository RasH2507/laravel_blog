@extends('dashboard.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <a href="{{ route('user.index') }}" class="btn btn-dark mb-3">Kembali</a>
    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" class="form-control" placeholder="Nama..." id="name" name="name"
                        value="{{ old('name') }}" required>
                </div>
                @error('name')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" placeholder="Email..." id="email" name="email"
                        value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
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
                    <select class="form-control" name="role" id="role" required>
                        <option value="penulis" {{ old('role') == 'penulis' ? 'selected' : '' }}>Penulis</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                @error('role')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

                <div class="form-group">
                    <button type="submit" class="btn btn-dark btn-block">Tambah</button>
                </div>
            </form>
        </div>
    </div>
@endsection
