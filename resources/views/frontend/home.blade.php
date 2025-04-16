<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaGame - Komunitas Gaming Terbaik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('frontend/css/home.css') }}">
</head>

<body>
    <!-- Home Page -->
    <div id="home-page">
        <header>
            <div class="container">
                @include('frontend.nav')
            </div>
        </header>

        <div class="container">
            <div class="welcome-banner">
                <h2>Selamat Datang di MetaGame</h2>
                <p>Sumber terlengkap untuk berita game, ulasan, dan diskusi komunitas</p>
            </div>
        </div>

        <!-- Latest Articles Section -->
        <section class="articles-section">
            <div class="container">
                <h2 class="section-title">Artikel Terbaru</h2>
                @if (isset($currentGame))
                    <h3 class="game-filter">Menampilkan artikel untuk game: {{ $currentGame->nama_game }}</h3>
                @endif
                <div class="articles-grid">
                    @foreach ($artikels as $artikel)
                        <!-- Article Card -->
                        <div class="article-card">
                            <div class="article-img-container">
                                <img src="{{ $artikel->image ? asset('storage/' . $artikel->image) : '/api/placeholder/300/180' }}"
                                    alt="{{ $artikel->judul }}" class="article-img">
                                <div class="article-category">{{ $artikel->game->nama_game ?? 'UMUM' }}</div>
                            </div>
                            <div class="article-content">
                                <h3 class="article-title">{{ $artikel->judul }}</h3>
                                <div class="article-meta">
                                    <span class="article-date">{{ $artikel->created_at->format('d F Y') }}</span>
                                    <span class="article-author">By {{ $artikel->user->name ?? 'Anonim' }}</span>
                                </div>
                                <p class="article-excerpt">
                                    {{ Str::limit(strip_tags($artikel->konten), 120) }}</p>
                                <div class="article-footer">
                                    <a href="{{ route('artikel.detail', $artikel->id_artikel) }}"
                                        class="read-more">BACA SELENGKAPNYA</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($artikels->isEmpty())
                        <div class="article-card" style="grid-column: 1 / -1; text-align: center;">
                            <div class="article-content">
                                <h3 class="article-title">Tidak ada artikel ditemukan</h3>
                                <p class="article-excerpt">Nantikan artikel-artikel menarik yang akan datang.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="cta-section">
            <div class="container">
                <h2>Gabung Dengan Komunitas Gaming Kami</h2>
                <p>Terhubung dengan sesama gamers, bagikan pencapaian Anda, dan temukan pengalaman gaming baru.</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn">Daftar</a>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="copyright">
                    <p>&copy; {{ date('Y') }} MetaGame. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.querySelector('nav');
        const menuToggle = document.createElement('button');
        menuToggle.className = 'mobile-menu-toggle';
        menuToggle.innerHTML = '☰';
        menuToggle.setAttribute('aria-label', 'Tampilkan menu navigasi');
        nav.appendChild(menuToggle);

        const navLinks = document.querySelector('.nav-links');

        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            menuToggle.innerHTML = navLinks.classList.contains('active') ? '✕' : '☰';
        });

        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            const dropdownLink = dropdown.querySelector('a');

            if (window.innerWidth <= 768) {
                dropdownLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                });
            }
        });

        document.addEventListener('click', function(e) {
            if (!nav.contains(e.target) && navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
                menuToggle.innerHTML = '☰';
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                navLinks.classList.remove('active');
                menuToggle.innerHTML = '☰';

                dropdowns.forEach(dropdown => {
                    const dropdownLink = dropdown.querySelector('a');
                    dropdownLink.removeEventListener('click', function() {});
                });
            }
        });
    });
</script>

</html>
