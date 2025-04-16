<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaGame - Artikel Saya</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/my-articles.css') }}">
</head>

<body>
    <header>
        <div class="container">
            @include('frontend.nav')
        </div>
    </header>

    <div class="dashboard">
        <div class="sidebar-menu">
            <div class="user-profile">
                <img src="/api/placeholder/100/100" alt="Profil Pengguna" class="profile-img">
                <h3 class="user-name">{{ $user->name }}</h3>
                <span class="user-level">{{ ucfirst($user->role) }}</span>
                <a href="{{ route('profile') }}" class="btn">Edit Profil</a>
            </div>
            <ul class="menu-items">
                <li><a href="{{ route('penulis.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('penulis.myarticles') }}" class="active">Artikel Saya</a></li>
                <li><a href="{{ route('penulis.artikel.create') }}">Buat Artikel</a></li>
                <li><a href="/">Favorit</a></li>
                <li><a href="/">Komentar</a></li>
                <li><a href="{{ route('profile') }}">Pengaturan Akun</a></li>
            </ul>
        </div>

        <div class="dashboard-content">
            <div class="dashboard-header">
                <h2>Artikel Saya</h2>
                <a href="{{ route('penulis.artikel.create') }}" class="btn create-btn">Buat Artikel Baru</a>
            </div>


            <form id="filter-form" action="{{ route('penulis.myarticles') }}" method="GET">
                <div class="article-filters">
                    <div class="filter-group">
                        <label for="status-filter">Filter berdasarkan Status:</label>
                        <select id="status-filter" name="status" class="form-control" onchange="document.getElementById('filter-form').submit()">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dipublikasikan</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="sort-by">Urutkan Berdasarkan:</label>
                        <select id="sort-by" name="sort" class="form-control" onchange="document.getElementById('filter-form').submit()">
                            <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Judul A-Z</option>
                        </select>
                    </div>
                    <div class="filter-group search-filter">
                        <input type="text" name="search" placeholder="Cari artikel..." class="form-control" value="{{ request('search') }}">
                        <button type="submit" class="btn search-btn">Cari</button>
                    </div>
                </div>
            </form>

            <div class="articles-container">
                <div class="articles-header">
                    <div class="header-title">Judul</div>
                    <div class="header-game">Game</div>
                    <div class="header-date">Terakhir Diperbarui</div>
                    <div class="header-status">Status</div>
                    <div class="header-actions">Aksi</div>
                </div>

                @forelse ($artikels as $artikel)
                    <div class="article-item">
                        <div class="article-title">
                            <h4>{{ $artikel->judul }}</h4>
                            <p class="article-preview">{{ Str::limit($artikel->konten, 100) }}</p>
                        </div>
                        <div class="article-game">{{ $artikel->game->nama_game ?? 'N/A' }}</div>
                        <div class="article-date">
                            {{ $artikel->updated_at->timezone('Asia/Jakarta')->format('M d, Y') }}</div>
                        <div class="article-status">
                            <span class="status-badge status-{{ $artikel->status }}">
                                @if($artikel->status == 'draft')
                                    Draft
                                @elseif($artikel->status == 'pending')
                                    Menunggu Review
                                @elseif($artikel->status == 'confirmed')
                                    Dipublikasikan
                                @elseif($artikel->status == 'rejected')
                                    Ditolak
                                @else
                                    {{ ucfirst($artikel->status) }}
                                @endif
                            </span>
                        </div>
                        <div class="article-actions">
                            <a href="{{ route('penulis.artikel.show', $artikel->id_artikel) }}"
                                class="btn action-btn view-btn">Lihat</a>
                            <a href="{{ route('penulis.artikel.edit', $artikel->id_artikel) }}"
                                class="btn action-btn edit-btn">Edit</a>
                            <form action="{{ route('penulis.artikel.destroy', $artikel->id_artikel) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn action-btn delete-btn"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="no-articles">
                        <div class="empty-state">
                            <div class="empty-icon">📝</div>
                            <h3>Tidak Ada Artikel</h3>
                            <p>Anda belum membuat artikel apapun. Mulai tulis artikel pertama Anda sekarang!</p>
                            <a href="{{ route('penulis.artikel.create') }}" class="btn">Buat Artikel</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="pagination-container">
                {{ $artikels->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; {{ date('Y') }} MetaGame. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('show');
        });

        document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('filter-form').submit();
            }
        });
    </script>
</body>

</html>