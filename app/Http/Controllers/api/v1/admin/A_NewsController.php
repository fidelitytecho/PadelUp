<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Repositories\Interfaces\NewsInterface;
use App\Services\admin\A_CreateNewsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class A_NewsController extends Controller
{
    private $news, $newsService;

    /**
     * Create a new instance.
     *
     * @param NewsInterface $news
     * @param A_CreateNewsService $newsService
     */
    public function __construct(NewsInterface $news, A_CreateNewsService $newsService)
    {
        $this->news = $news;
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return NewsResource::collection($this->news->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewsRequest $request
     * @return NewsResource
     */
    public function store(NewsRequest $request)
    {
        return new NewsResource($this->newsService->create($request->validated(), $request->file('image') ?? null));
    }
}
