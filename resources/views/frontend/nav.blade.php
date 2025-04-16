<nav>
    <div class="logo">Meta<span>Game</span></div>
    <form action="{{ request()->routeIs('artikel.page') ? route('artikel.page') : route('home') }}" method="GET"
        class="search-container">
        <input type="text" name="search" placeholder="Cari game, artikel..." value="{{ $search ?? '' }}">
        <button type="submit">🔍</button>
    </form>
    <ul class="nav-links">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
        <li class="dropdown">
            <a href="#">Game ▼</a>
            <div class="dropdown-content">
                @foreach ($games as $game)
                    <a href="{{ route('game.articles', $game->id_game) }}">{{ $game->nama_game }}</a>
                @endforeach
            </div>
        </li>
        <li><a href="{{ route('artikel.page') }}"
                class="{{ request()->routeIs('artikel.page') ? 'active' : '' }}">Artikel</a></li>
        <li><a href="{{ route('penulis.dashboard') }}"
                class="{{ request()->routeIs('penulis.dashboard') ? 'active' : '' }}">Dashboard</a></li>
        @guest
            <li><a href="{{ route('login') }}" class="btn">Masuk</a></li>
        @else
            <li><a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endguest
    </ul>
</nav>
