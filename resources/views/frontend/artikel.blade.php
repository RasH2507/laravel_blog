<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaGame - Artikel Gaming</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('frontend/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/artikel.css') }}">
</head>

<body>
    <!-- Artikel Page -->
    <div id="artikel-page">
        <header>
            <div class="container">
                @include('frontend.nav')
            </div>
        </header>

        <!-- Articles Section -->
        <section class="articles-section">
            <div class="container">
                <h2 class="section-title">Semua Artikel</h2>
                
                <!-- Filter Section -->
                <div class="filter-container">
                    <form action="{{ route('artikel.page') }}" method="GET" class="filter-form">
                        <div class="filter-group">
                            <label for="game-filter">Game:</label>
                            <select name="game" id="game-filter" class="filter-select">
                                <option value="">Semua Game</option>
                                @foreach ($games as $game)
                                    <option value="{{ $game->id_game }}" {{ request('game') == $game->id_game ? 'selected' : '' }}>
                                        {{ $game->nama_game }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="tag-filter">Tag:</label>
                            <select name="tag" id="tag-filter" class="filter-select">
                                <option value="">Semua Tag</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id_tag }}" {{ request('tag') == $tag->id_tag ? 'selected' : '' }}>
                                        {{ $tag->nama_tag }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="sort-filter">Urutkan:</label>
                            <select name="sort" id="sort-filter" class="filter-select">
                                <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                            </select>
                        </div>
                        
                        <div class="filter-group search-filter">
                            <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}" class="filter-search">
                        </div>
                        
                        <button type="submit" class="filter-button">Filter</button>
                    </form>
                </div>
                
                <!-- Active Filters -->
                @if(request('game') || request('tag') || request('search') || request('sort'))
                    <div class="active-filters">
                        <span>Filter aktif:</span>
                        @if(request('game'))
                            <span class="active-filter">
                                Game: {{ $games->find(request('game'))->nama_game }}
                                <a href="{{ route('artikel.page', array_merge(request()->except('game'), ['page' => 1])) }}" class="remove-filter">×</a>
                            </span>
                        @endif
                        
                        @if(request('tag'))
                            <span class="active-filter">
                                Tag: {{ $tags->find(request('tag'))->nama_tag }}
                                <a href="{{ route('artikel.page', array_merge(request()->except('tag'), ['page' => 1])) }}" class="remove-filter">×</a>
                            </span>
                        @endif
                        
                        @if(request('search'))
                            <span class="active-filter">
                                Pencarian: "{{ request('search') }}"
                                <a href="{{ route('artikel.page', array_merge(request()->except('search'), ['page' => 1])) }}" class="remove-filter">×</a>
                            </span>
                        @endif
                        
                        @if(request('sort') && request('sort') != 'newest')
                            <span class="active-filter">
                                Urutan: {{ request('sort') == 'oldest' ? 'Terlama' : 'Terpopuler' }}
                                <a href="{{ route('artikel.page', array_merge(request()->except('sort'), ['page' => 1])) }}" class="remove-filter">×</a>
                            </span>
                        @endif
                        
                        <a href="{{ route('artikel.page') }}" class="clear-all-filters">Hapus semua filter</a>
                    </div>
                @endif

                <!-- Article Grid -->
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
                                <p class="article-excerpt">Coba ubah filter pencarian atau cek kembali nanti untuk artikel terbaru.</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="pagination-container">
                    {{ $artikels->links() }}
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
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
        
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                const searchInput = document.querySelector('.filter-search');
                
                if (!searchInput.value || this.id !== 'sort-filter') {
                    this.form.submit();
                }
            });
        });
        
        const searchInput = document.querySelector('.filter-search');
        let searchTimer;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            
            if (this.value.trim() === '') {
                this.form.submit();
                return;
            }
            
            searchTimer = setTimeout(() => {
                if (this.value.trim().length >= 3) {
                    this.form.submit();
                }
            }, 800);
        });
        
        const searchFilter = document.querySelector('.search-filter');
        if (searchInput.value) {
            const clearButton = document.createElement('button');
            clearButton.type = 'button';
            clearButton.className = 'search-clear-btn';
            clearButton.innerHTML = '×';
            clearButton.setAttribute('aria-label', 'Clear search');
            clearButton.style.position = 'absolute';
            clearButton.style.right = '10px';
            clearButton.style.top = '50%';
            clearButton.style.transform = 'translateY(-50%)';
            clearButton.style.background = 'none';
            clearButton.style.border = 'none';
            clearButton.style.color = '#a0a0a0';
            clearButton.style.fontSize = '18px';
            clearButton.style.cursor = 'pointer';
            
            searchFilter.style.position = 'relative';
            
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.form.submit();
            });
            
            searchFilter.appendChild(clearButton);
        }
    });
    </script>
</body>
</html>