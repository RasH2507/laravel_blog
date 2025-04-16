<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Game;
use App\Models\Like;
use App\Models\Artikel;
use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $games = Game::all();
        $tags = Tag::all();

        $search = $request->input('search');

        $artikelsQuery = Artikel::with(['user', 'game'])
            ->where('status', 'confirmed');

        if ($search) {
            $artikelsQuery->where(function ($query) use ($search) {
                $query->where('judul', 'LIKE', "%$search%")
                    ->orWhereHas('game', function ($q) use ($search) {
                        $q->where('nama_game', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        $artikels = $artikelsQuery->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('frontend.home', compact('games', 'tags', 'artikels', 'search'));
    }

    public function writerDashboard()
    {
        $user = Auth::user();

        $artikels = Artikel::where('id_user', $user->id)
            ->with(['user', 'game'])
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get();

        $games = Game::all();

        $totalLikes = $artikels->sum('likes_count');
        $totalComments = $artikels->sum('comments_count');

        $recentActivities = $this->getRecentActivities($user->id, $artikels);

        return view('frontend.dashboard', compact(
            'artikels',
            'games',
            'user',
            'totalLikes',
            'totalComments',
            'recentActivities'
        ));
    }

    private function getRecentActivities($userId, $artikels)
    {
        $recentLikes = Like::join('artikel', 'likes.id_artikel', '=', 'artikel.id_artikel')
            ->join('users', 'likes.id_user', '=', 'users.id')
            ->where('artikel.id_user', $userId)
            ->where('likes.id_user', '!=', $userId) 
            ->select(
                'likes.created_at',
                'users.name as actor_name',
                'artikel.judul as article_title',
                'artikel.id_artikel',
                DB::raw("'like' as activity_type")
            )
            ->orderBy('likes.created_at', 'desc')
            ->take(10)
            ->get();

        $recentComments = Komentar::join('artikel', 'komentar.id_artikel', '=', 'artikel.id_artikel')
            ->join('users', 'komentar.id_user', '=', 'users.id')
            ->where('artikel.id_user', $userId)
            ->where('komentar.id_user', '!=', $userId)
            ->select(
                'komentar.created_at',
                'users.name as actor_name',
                'artikel.judul as article_title',
                'artikel.id_artikel',
                DB::raw("'comment' as activity_type")
            )
            ->orderBy('komentar.created_at', 'desc')
            ->take(10)
            ->get();

        $articleActivities = $artikels->take(10)->map(function ($artikel) {
            return [
                'created_at' => $artikel->updated_at,
                'actor_name' => null,
                'article_title' => $artikel->judul,
                'id_artikel' => $artikel->id_artikel,
                'activity_type' => 'article_' . $artikel->status,
                'status' => $artikel->status
            ];
        });

        $allActivities = $recentLikes
            ->concat($recentComments)
            ->concat($articleActivities)
            ->sortByDesc('created_at')
            ->take(10);

        return $allActivities;
    }

    public function artikelDetail($id)
    {
        $artikel = Artikel::with([
            'user',
            'game',
            'tags',
            'sections' => function ($query) {
                $query->orderBy('urutan', 'asc');
            },
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'comments.user'
        ])->findOrFail($id);

        if ($artikel->status !== 'confirmed') {
            abort(404);
        }

        $games = Game::all();
        $tags = Tag::all();

        $trendingGameArticles = Artikel::where('status', 'confirmed')
            ->where('id_artikel', '!=', $id)
            ->where('id_game', $artikel->id_game)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $relatedArticles = Artikel::where('status', 'confirmed')
            ->where('id_artikel', '!=', $id)
            ->where(function ($query) use ($artikel) {
                $query->where('id_game', $artikel->id_game)
                    ->orWhereHas('tags', function ($q) use ($artikel) {
                        $tagIds = $artikel->tags->pluck('id_tag');
                        $q->whereIn('tag.id_tag', $tagIds);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('frontend.artikel-detail', compact('artikel', 'games','tags', 'relatedArticles', 'trendingGameArticles'));
    }

    public function gameArticles($id)
    {
        $games = Game::all();
        $tags = Tag::all();
        $currentGame = Game::findOrFail($id);

        $artikels = Artikel::with(['user', 'game'])
            ->where('status', 'confirmed')
            ->where('id_game', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.home', compact('games', 'tags', 'artikels', 'currentGame'));
    }

    public function artikelPage(Request $request)
    {
        $games = Game::all();
        $tags = Tag::all();

        $search = $request->input('search');
        $gameFilter = $request->input('game');
        $tagFilter = $request->input('tag');
        $sortBy = $request->input('sort', 'newest');

        $artikelsQuery = Artikel::with(['user', 'game', 'tags', 'likes', 'comments'])
            ->where('status', 'confirmed');

        if ($search) {
            $artikelsQuery->where(function ($query) use ($search) {
                $query->where('judul', 'LIKE', "%$search%")
                    ->orWhere('konten', 'LIKE', "%$search%")
                    ->orWhereHas('game', function ($q) use ($search) {
                        $q->where('nama_game', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('tags', function ($q) use ($search) {
                        $q->where('nama_tag', 'LIKE', "%$search%");
                    });
            });
        }

        if ($gameFilter) {
            $artikelsQuery->where('id_game', $gameFilter);
        }

        if ($tagFilter) {
            $artikelsQuery->whereHas('tags', function ($query) use ($tagFilter) {
                $query->where('tag.id_tag', $tagFilter);
            });
        }

        switch ($sortBy) {
            case 'oldest':
                $artikelsQuery->orderBy('created_at', 'asc');
                break;
            case 'popular':
                $artikelsQuery->withCount('likes')
                    ->orderBy('likes_count', 'desc')
                    ->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $artikelsQuery->orderBy('created_at', 'desc');
                break;
        }

        $artikels = $artikelsQuery->paginate(9)->withQueryString();

        return view('frontend.artikel', compact('games', 'tags', 'artikels', 'search'));
    }
}
