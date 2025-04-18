<?php

namespace App\Services;

use App\Exceptions\AppError;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsService
{

    public function store(array $data, Request $request): JsonResource
    {
        $user = $request->user();


        if (!empty($data['is_draft']) && $data['is_draft'] === true) {
            $data['is_active'] = false;
        } else {

            if (!empty($data['is_fixed'])) {
                $data['is_active'] = true;
            } else {

                $data['is_active'] = true;
            }
        }

        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $news = $user->news()->create($data);
        $news->categories()->sync($categories);
        $news->load('user', 'categories');

        return new NewsResource($news);
    }

    public function get(): AnonymousResourceCollection
    {
        $news = News::with(['categories', 'user'])
            ->orderByDesc('is_fixed')
            ->orderByDesc('is_draft')
            ->orderByDesc('id')
            ->get();

        return NewsResource::collection($news);
    }

    public function retrieve(int $id): JsonResource
    {
        $news = News::with('categories', 'user')->find($id);

        if (!$news) {
            throw new AppError("News not found.", 404);
        }

        return new NewsResource($news);
    }

    public function update(int $id, array $data): JsonResource
    {
        $news = News::find($id);

        if (!$news) {
            throw new AppError("News not found.", 404);
        }

        if (array_key_exists("categories", $data)) {
            $categories = $data["categories"] ?? [];
            unset($data["categories"]);
            $news->categories()->sync($categories);
        }

        if (!empty($data['is_draft']) && $data['is_draft'] === true) {
            $data['is_active'] = false;
        } else {

            if (!empty($data['is_fixed'])) {
                $data['is_active'] = true;
            } else {

                $data['is_active'] = true;
            }
        }


        $news->update($data);

        $news->load('user', 'categories');

        return new NewsResource($news);
    }

    public function destroy(int $id): void
    {
        $news = News::find($id);

        if (!$news) {
            throw new AppError("News not found.", 404);
        }

        $news->delete();
    }

    public function getNewsByCategory(int $id): AnonymousResourceCollection
    {
        $news = News::with('categories', 'user')
            ->whereHas('categories', function ($query) use ($id) {
                $query->where('category_id', $id);
            })
            ->orderByDesc('is_fixed')
            ->orderByDesc('is_draft')
            ->orderByDesc('id')
            ->paginate(10);

        if ($news->isEmpty()) {
            throw new AppError("News not found.", 404);
        }

        return NewsResource::collection($news);
    }

    public function getNewsByUser(Request $request, int $userId): AnonymousResourceCollection
    {
        $user = $request->user();

        if ($user->is_admin) {
            $news = News::with('categories', 'user')->where('user_id', $userId)->get();

            if ($news->isEmpty()) {
                throw new AppError("News not found.", 404);
            }

            return NewsResource::collection($news);
        }

        if ($user->id !== $userId) {
            throw new AppError("Unauthorized", 401);
        }

        $news = $user->news()->with('categories', 'user')->get();

        if ($news->isEmpty()) {
            throw new AppError("News not found.", 404);
        }

        return NewsResource::collection($news);
    }

    public function getNewsByTitle(Request $request): AnonymousResourceCollection
    {
        $title = $request->query('title', '');

        $news = News::with(['categories', 'user'])
            ->when($title, function ($query, $title) {
                $query->where("title", "LIKE", "%$title%");
            })
            ->orderByDesc('is_fixed')
            ->orderByDesc('is_draft')
            ->orderByDesc('id')
            ->paginate(10);

        return NewsResource::collection($news);
    }

    public function getNewsPaginate(): AnonymousResourceCollection
    {
        $news = News::with(['categories', 'user'])
            ->orderByDesc('is_fixed')
            ->orderByDesc('is_draft')
            ->orderByDesc('id')
            ->paginate(10);
        return NewsResource::collection($news);
    }
}
