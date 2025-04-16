<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaGame - Dashboard Penulis</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
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
                <li><a href="{{ route('penulis.dashboard') }}" class="active">Dashboard</a></li>
                <li><a href="{{ route('penulis.myarticles') }}">Artikel Saya</a></li>
                <li><a href="{{ route('penulis.artikel.create') }}">Buat Artikel</a></li>
                <li><a href="/">Favorit</a></li>
                <li><a href="/">Komentar</a></li>
                <li><a href="{{ route('profile') }}">Pengaturan Akun</a></li>
            </ul>
        </div>

        <div class="dashboard-content">
            <div class="dashboard-header">
                <h2>Selamat datang kembali, {{ $user->name }}!</h2>
                <span>Login terakhir: {{ $user->last_login ?? date('M d, Y') }}</span>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $artikels->count() }}</div>
                    <div class="stat-label">Artikel</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $totalComments ?? 0 }}</div>
                    <div class="stat-label">Komentar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $totalLikes ?? 0 }}</div>
                    <div class="stat-label">Suka</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $user->view_count ?? 0 }}</div>
                    <div class="stat-label">Dilihat</div>
                </div>
            </div>

            <div class="recent-activity">
                <h3 class="activity-title">Aktivitas Terbaru</h3>
                <ul class="activity-list">
                    @forelse ($recentActivities as $activity)
                        <li class="activity-item">
                            <div class="activity-icon">
                                @if(strpos($activity['activity_type'], 'article_') === 0)
                                    @if ($activity['status'] == 'draft')
                                        📝
                                    @elseif ($activity['status'] == 'pending')
                                        ⏳
                                    @elseif ($activity['status'] == 'confirmed')
                                        ✅
                                    @elseif ($activity['status'] == 'rejected')
                                        ❌
                                    @endif
                                @elseif($activity['activity_type'] == 'like')
                                    ❤️
                                @elseif($activity['activity_type'] == 'comment')
                                    💬
                                @endif
                            </div>
                            <div class="activity-details">
                                <div class="activity-text">
                                    @if(strpos($activity['activity_type'], 'article_') === 0)
                                        @if ($activity['status'] == 'draft')
                                            Anda menyimpan draft: "{{ $activity['article_title'] }}"
                                        @elseif ($activity['status'] == 'pending')
                                            Anda mengirim untuk ditinjau: "{{ $activity['article_title'] }}"
                                        @elseif ($activity['status'] == 'confirmed')
                                            Artikel Anda telah dipublikasikan: "{{ $activity['article_title'] }}"
                                        @elseif ($activity['status'] == 'rejected')
                                            Artikel Anda ditolak: "{{ $activity['article_title'] }}"
                                        @endif
                                    @elseif($activity['activity_type'] == 'like')
                                        <strong>{{ $activity['actor_name'] }}</strong> menyukai artikel Anda "{{ $activity['article_title'] }}"
                                    @elseif($activity['activity_type'] == 'comment')
                                        <strong>{{ $activity['actor_name'] }}</strong> mengomentari artikel Anda "{{ $activity['article_title'] }}"
                                    @endif
                                </div>
                                <div class="activity-time">{{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}</div>
                            </div>
                        </li>
                    @empty
                        <li class="activity-item">
                            <div class="activity-icon">📝</div>
                            <div class="activity-details">
                                <div class="activity-text">Anda belum memiliki aktivitas apa pun.</div>
                                <div class="activity-time">Mulai menulis hari ini!</div>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="dashboard-grid">
                <div class="dashboard-articles">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 class="activity-title">Artikel Saya</h3>
                        <a href="{{ route('penulis.artikel.create') }}" class="btn create-btn">Buat Baru</a>
                    </div>

                    @forelse ($artikels as $artikel)
                        <div class="article-row">
                            <span class="article-title">
                                {{ $artikel->judul }}
                                <span class="status-badge status-{{ $artikel->status }}">
                                    @if ($artikel->status == 'draft')
                                        Draft
                                    @elseif ($artikel->status == 'pending')
                                        Menunggu
                                    @elseif ($artikel->status == 'confirmed')
                                        Dipublikasi
                                    @elseif ($artikel->status == 'rejected')
                                        Ditolak
                                    @else
                                        {{ ucfirst($artikel->status) }}
                                    @endif
                                </span>
                                <div class="article-stats">
                                    <span class="article-stat">
                                        <span class="stat-icon">❤️</span> {{ $artikel->likes_count }}
                                    </span>
                                    <span class="article-stat">
                                        <span class="stat-icon">💬</span> {{ $artikel->comments_count }}
                                    </span>
                                </div>
                            </span>
                            <div class="article-actions">
                                <a href="{{ route('penulis.artikel.edit', $artikel->id_artikel) }}"
                                    class="btn action-btn edit-btn">Edit</a>
                                <form action="{{ route('penulis.artikel.destroy', $artikel->id_artikel) }}"
                                    method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn action-btn delete-btn"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="article-row">
                            <span class="article-title">Tidak ada artikel ditemukan</span>
                        </div>
                    @endforelse
                </div>

                <div class="dashboard-sidebar">
                    <div class="dashboard-widget">
                        <h3 class="activity-title">Status Artikel</h3>
                        <ul class="notifications-list">
                            <li class="notification-item">
                                Draft: {{ $artikels->where('status', 'draft')->count() }} artikel
                            </li>
                            <li class="notification-item">
                                Menunggu Tinjauan: {{ $artikels->where('status', 'pending')->count() }} artikel
                            </li>
                            <li class="notification-item">
                                Dipublikasikan: {{ $artikels->where('status', 'confirmed')->count() }} artikel
                            </li>
                            <li class="notification-item">
                                Ditolak: {{ $artikels->where('status', 'rejected')->count() }} artikel
                            </li>
                        </ul>
                    </div>
                    
                    <div class="dashboard-widget">
                        <h3 class="activity-title">Interaksi Pembaca</h3>
                        <ul class="notifications-list">
                            <li class="notification-item">
                                <span class="icon">❤️</span> Total Suka: {{ $totalLikes ?? 0 }}
                            </li>
                            <li class="notification-item">
                                <span class="icon">💬</span> Total Komentar: {{ $totalComments ?? 0 }}
                            </li>
                            <li class="notification-item">
                                <span class="icon">📊</span> Rata-rata suka per artikel: 
                                {{ $artikels->count() > 0 ? round($totalLikes / $artikels->count(), 1) : 0 }}
                            </li>
                        </ul>
                    </div>
                </div>
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

    <style>
        .article-stats {
            display: inline-flex;
            margin-left: 10px;
            vertical-align: middle;
        }
        .article-stat {
            font-size: 0.9em;
            margin-right: 10px;
            color: #666;
        }
        .stat-icon {
            margin-right: 3px;
        }
        .dashboard-widget .icon {
            margin-right: 5px;
        }
    </style>

    <script>
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('show');
        });
    </script>
</body>

</html>