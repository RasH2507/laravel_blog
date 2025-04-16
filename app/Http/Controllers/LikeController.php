<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($id_artikel)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        $artikel = Artikel::findOrFail($id_artikel);
        $user_id = Auth::id();

        $existingLike = Like::where('id_artikel', $id_artikel)
            ->where('id_user', $user_id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $action = 'unliked';
        } else {
            Like::create([
                'id_artikel' => $id_artikel,
                'id_user' => $user_id
            ]);
            $action = 'liked';
        }

        $likeCount = Like::where('id_artikel', $id_artikel)->count();

        return response()->json([
            'success' => true,
            'action' => $action,
            'likeCount' => $likeCount
        ]);
    }


    public function getLikeCount($id_artikel)
    {
        $likeCount = Like::where('id_artikel', $id_artikel)->count();
        $isLiked = false;

        if (Auth::check()) {
            $isLiked = Like::where('id_artikel', $id_artikel)
                ->where('id_user', Auth::id())
                ->exists();
        }

        return response()->json([
            'success' => true,
            'likeCount' => $likeCount,
            'isLiked' => $isLiked
        ]);
    }
}
