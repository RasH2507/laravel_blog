@extends('dashboard.app')

@section('title', 'Artikel')

@section('content')
    @if ($success = Session::get('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <p>{{ $success }}</p>
        </div>
    @endif
    <a href="{{ route('artikel.create') }}" class="btn btn-dark mb-3">Tambah Artikel</a>
    <div class="table-responsive">
        <table class="table table-hover shadow">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID User</th>
                    <th>ID Game</th>
                    <th>Judul</th>
                    <th>Konten</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($artikel as $artikels)
                    <tr>
                        <td>{{ $artikels->id_artikel }}</td>
                        <td>{{ $artikels->user->id }} | {{ $artikels->user->name }}</td>
                        <td>{{ $artikels->game->id_game }} | {{ $artikels->game->nama_game }}</td>
                        <td>{{ $artikels->judul }}</td>
                        <td>{{ $artikels->konten }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $artikels->image) }}" alt="Gambar Artikel" width="150">
                        </td>
                        <td>
                            <span class="badge badge-{{ $artikels->status == 'confirmed' ? 'success' : ($artikels->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ $artikels->status }}
                            </span>
                        </td>
                        <td>
                            @if ($artikels->status === 'pending')
                                <div class="btn-group" role="group">
                                    <form action="{{ route('artikel.confirm', $artikels->id_artikel) }}" method="POST" class="me-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Konfirmasi
                                        </button>
                                    </form>

                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal-{{ $artikels->id_artikel }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </div>

                                <!-- Modal Penolakan -->
                                <div class="modal fade" id="rejectModal-{{ $artikels->id_artikel }}" tabindex="-1"
                                    aria-labelledby="rejectModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Artikel</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('artikel.reject', $artikels->id_artikel) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <label for="reason-{{ $artikels->id_artikel }}">Alasan Penolakan</label>
                                                    <textarea name="reason" id="reason-{{ $artikels->id_artikel }}" class="form-control" rows="3" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($artikels->status === 'rejected')
                                <span class="text-muted">Artikel ditolak</span>
                            @else
                                <span class="text-muted">Artikel dikonfirmasi</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection