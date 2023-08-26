<?php

namespace App\Http\Controllers\api\v1\mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\app\CategoryResource;
use App\Services\FetchCategoryAvailableTimesService;
use App\Services\FetchCategoryService;
use App\Services\NextAvailableDayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class M_CategoryController extends Controller
{
    private $categoryService;
    private $availableTimesService;
    private $nextAvailableDayService;

    /**
     * Create a new instance.
     * @param FetchCategoryService $categoryService
     * @param FetchCategoryAvailableTimesService $availableTimesService
     * @param NextAvailableDayService $nextAvailableDayService
     */
    public function __construct(FetchCategoryService $categoryService,
                                FetchCategoryAvailableTimesService $availableTimesService,
                                NextAvailableDayService $nextAvailableDayService)
    {
        $this->categoryService = $categoryService;
        $this->availableTimesService = $availableTimesService;
        $this->nextAvailableDayService = $nextAvailableDayService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return CategoryResource|JsonResponse
     */
    public function index()
    {
        return $this->categoryService->fetchCategory(1);
    }

    /**
     * Category Available Dates
     *
     * @param string $date
     * @param int $duration
     * @return JsonResponse
     */
    public function availableTimes(string $date, int $duration): JsonResponse
    {
        return $this->availableTimesService->fetchAvailableTime($date, $duration);
    }

    /**
     * Next Available Day Function
     *
     * @param string $date
     * @param int $duration
     * @return JsonResponse
     */
    public function nextAvailableDay(string $date, int $duration): JsonResponse
    {
        return $this->nextAvailableDayService->nextAvailableDay($date, $duration);
    }
}
