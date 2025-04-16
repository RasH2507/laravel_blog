<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $artikel->judul }} - MetaGame</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/artikel-detail.css') }}">
</head>

<body>
    <header>
        <div class="container">
            @include('frontend.nav')
        </div>
    </header>

    <div class="page-container">
        <!-- LEFT SIDEBAR -->
        <aside class="sidebar sidebar-left">
            <div class="sidebar-box trending-articles">
                <h3 class="sidebar-title">Artikel {{ $artikel->game->nama_game }}</h3>
                @if (count($trendingGameArticles) > 0)
                    @foreach ($trendingGameArticles as $trendingArticle)
                        <div class="article-item">
                            <h4 class="article-title">
                                <a href="{{ route('artikel.detail', $trendingArticle->id_artikel) }}">
                                    {{ $trendingArticle->judul }}
                                </a>
                            </h4>
                            <div class="article-meta">
                                <span>{{ $trendingArticle->created_at->format('d M, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>Belum ada artikel lain untuk game ini.</p>
                @endif
            </div>

            <div class="sidebar-box">
                <h3 class="sidebar-title">Iklan</h3>
                <div
                    style="background-color: #2a2a2a; height: 250px; display: flex; align-items: center; justify-content: center; border-radius: 4px; color: #888;">
                    Ruang Iklan
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="article-detail">
            <article>
                <div class="article-header">
                    <span class="article-game">{{ $artikel->game->nama_game }}</span>
                    <h1 class="article-title">{{ $artikel->judul }}</h1>
                    <div class="article-meta">
                        <span class="article-date">{{ $artikel->created_at->format('d F Y') }}</span>
                        <span class="article-author">Oleh {{ $artikel->user->name }}</span>
                    </div>
                    @if (count($artikel->tags) > 0)
                        <div class="article-tags">
                            @foreach ($artikel->tags as $tag)
                                <a href="/" class="tag">{{ $tag->nama_tag }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if ($artikel->image)
                    <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->judul }}"
                        class="article-featured-image">
                @endif

                <div class="article-content">
                    {!! $artikel->konten !!}
                </div>

                @if (count($artikel->sections) > 0)
                    @foreach ($artikel->sections as $section)
                        <div class="article-section">
                            <h2 class="section-title">{{ $section->sub_judul }}</h2>

                            @if ($section->media_type)
                                <div class="section-media">
                                    @if ($section->media_type == 'image' && $section->media_content)
                                        <img src="{{ asset('storage/' . $section->media_content) }}"
                                            alt="{{ $section->sub_judul }}">
                                    @elseif($section->media_type == 'embed' && $section->media_content)
                                        @php
                                            $mediaContent = $section->media_content;
                                            if (
                                                strpos($mediaContent, 'youtube.com/watch?v=') !== false ||
                                                strpos($mediaContent, 'youtu.be/') !== false
                                            ) {
                                                if (strpos($mediaContent, 'youtube.com/watch?v=') !== false) {
                                                    $parts = parse_url($mediaContent);
                                                    parse_str($parts['query'], $query);
                                                    $videoId = $query['v'] ?? '';
                                                } else {
                                                    $parts = explode('/', $mediaContent);
                                                    $videoId = end($parts);
                                                }

                                                if ($videoId) {
                                                    $embedCode =
                                                        '<div class="embed-container"><iframe src="https://www.youtube.com/embed/' .
                                                        $videoId .
                                                        '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                                                    $mediaContent = $embedCode;
                                                }
                                            }
                                        @endphp
                                        <div class="embed-container">
                                            {!! $mediaContent !!}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($section->deskripsi)
                                <div class="section-content">
                                    {!! $section->deskripsi !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <div class="article-likes">
                    <button id="likeButton" class="like-button" data-article-id="{{ $artikel->id_artikel }}">
                        <span class="heart-ripple"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            class="heart-icon" id="likeIcon">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="social-share">
                    <a href="#" title="Bagikan di Facebook">F</a>
                    <a href="#" title="Bagikan di Twitter">T</a>
                    <a href="#" title="Bagikan di LinkedIn">L</a>
                    <a href="#" title="Bagikan di WhatsApp">W</a>
                </div>
            </article>

            <section class="comments-section">
                <h2>Komentar ({{ count($artikel->comments) }})</h2>

                @if (count($artikel->comments) > 0)
                    @foreach ($artikel->comments as $comment)
                        <div class="comment-card">
                            <div class="comment-header">
                                <span class="comment-author">{{ $comment->user->name }}</span>
                                <span class="comment-date">{{ $comment->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="comment-body">
                                {{ $comment->isi_komentar }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                @endif

                @auth
                    <div class="comment-form">
                        <h3>Tinggalkan Komentar</h3>
                        <form action="{{ route('komentar.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_artikel" value="{{ $artikel->id_artikel }}">
                            <textarea name="isi_komentar" placeholder="Tulis komentar Anda di sini..." required></textarea>
                            <button type="submit" class="btn">Kirim Komentar</button>
                        </form>
                    </div>
                @else
                    <p>Silahkan <a href="{{ route('login') }}">Masuk</a> untuk meninggalkan komentar.</p>
                @endauth
            </section>
        </main>

        <!-- RIGHT SIDEBAR -->
        <aside class="sidebar sidebar-right">
            <div class="sidebar-box related-games">
                <h3 class="sidebar-title">Game Terkait</h3>
                @foreach ($games->take(5) as $game)
                    <div class="game-item">
                        <h4><a href="{{ route('game.articles', $game->id_game) }}">{{ $game->nama_game }}</a></h4>
                    </div>
                @endforeach
            </div>

            <div class="sidebar-box">
                <h3 class="sidebar-title">Tag Populer</h3>
                <div class="article-tags">
                    @foreach ($tags as $tag)
                        <a href="/" class="tag">{{ $tag->nama_tag }}</a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

    <div class="copyright">
        <p>&copy; {{ date('Y') }} MetaGame. All rights reserved.</p>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const articleId = $('#likeButton').data('article-id');
        let isLiked = false;
        let isProcessing = false;

        loadLikeStatus();

        $('#likeButton').click(function() {
            if (isProcessing) return; 

            isProcessing = true;

            $(this).addClass('clicked');

            setTimeout(() => {
                $(this).removeClass('clicked');
            }, 300);

            if (!isLiked) {
                $(this).addClass('liking').removeClass('unliking');
            } else {
                $(this).addClass('unliking').removeClass('liking');
            }

            const wasLiked = isLiked;
            isLiked = !isLiked;

            if (isLiked) {
                $(this).addClass('liked');
            } else {
                $(this).removeClass('liked');
            }

            $.ajax({
                url: `/artikel/${articleId}/like`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.action === 'liked' && !isLiked) {
                        $('#likeButton').addClass('liked liking').removeClass('unliking');
                        isLiked = true;
                    } else if (response.action === 'unliked' && isLiked) {
                        $('#likeButton').removeClass('liked').addClass('unliking')
                            .removeClass('liking');
                        isLiked = false;
                    }

                    isProcessing = false;
                },
                error: function(xhr) {
                    isLiked = wasLiked;
                    if (isLiked) {
                        $('#likeButton').addClass('liked').removeClass('unliking').addClass(
                            'liking');
                    } else {
                        $('#likeButton').removeClass('liked').removeClass('liking')
                            .addClass('unliking');
                    }

                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    }

                    isProcessing = false;
                }
            });
        });

        function loadLikeStatus() {
            $.ajax({
                url: `/artikel/${articleId}/like-count`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        isLiked = response.isLiked;
                        if (isLiked) {
                            $('#likeButton').addClass('liked');
                        } else {
                            $('#likeButton').removeClass('liked');
                        }
                    }
                }
            });
        }
    });
</script>

</html>
