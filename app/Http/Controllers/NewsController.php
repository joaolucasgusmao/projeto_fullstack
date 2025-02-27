<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NewsController extends Controller
{

    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        return response()->json($this->newsService->store($request->all(), $request), 201);
    }

    public function get(): JsonResponse
    {
        return response()->json($this->newsService->get(), 200);
    }

    public function retrieve(int $id): JsonResponse
    {
        return response()->json($this->newsService->retrieve($id), 200);
    }

    public function update(int $id, UpdateNewsRequest $request): JsonResponse
    {
        return response()->json($this->newsService->update($id, $request->all()), 200);
    }

    public function destroy(int $id): Response
    {
        return response($this->newsService->destroy($id), 204);
    }
}
