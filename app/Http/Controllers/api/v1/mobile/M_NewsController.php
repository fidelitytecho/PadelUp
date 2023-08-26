<?php

namespace App\Http\Controllers\api\v1\mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Repositories\Interfaces\NewsInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class M_NewsController extends Controller
{
    private $news;

    /**
     * Create a new instance.
     *
     * @param NewsInterface $news
     */
    public function __construct(NewsInterface $news)
    {
        $this->news = $news;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        return NewsResource::collection($this->news->all());
    }
}
