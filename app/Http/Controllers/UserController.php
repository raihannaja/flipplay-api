<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCollectionRequest;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::withCount('media')->orderBy('id')->get());
    }

    public function show(User $user)
    {
        return new UserResource($user->load('media'));
    }

    public function addToCollection(AddToCollectionRequest $request, User $user)
    {
        $mediaId = $request->validated('media_id');

        if ($user->media()->where('media.id', $mediaId)->exists()) {
            return response()->json(['message' => 'Media sudah ada di koleksi'], 409);
        }

        $user->media()->attach($mediaId);

        return (new UserResource($user->load('media')))
            ->additional(['message' => 'Media berhasil ditambahkan ke koleksi'])
            ->response()
            ->setStatusCode(201);
    }

    public function removeFromCollection(User $user, Media $media)
    {
        if (! $user->media()->where('media.id', $media->id)->exists()) {
            return response()->json(['message' => 'Media tidak ada di koleksi'], 404);
        }

        $user->media()->detach($media->id);

        return response()->json(['message' => 'Media berhasil dihapus dari koleksi']);
    }
}
