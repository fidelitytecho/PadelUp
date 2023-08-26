<?php

namespace App\Services;

use App\Http\Resources\app\CategoryResource;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FetchCategoryService
{
    private $category;

    /**
     * Create a new instance.
     * @param CategoryInterface $category
     */
    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    /**
     * Fetch Category Data
     * @param int $id
     * @return CategoryResource|JsonResponse
     */
    public function fetchCategory(int $id)
    {
        try {
            $categoryData = $this->category->findByID($id, ['Services', 'Courts', 'Images', 'Company.Currency']);
            if ($categoryData) {
                return new CategoryResource($categoryData);
            }
            $output = ([
                'success' => false,
                'message'=> 'Category Not Found',
            ]);
            return response()->json($output, 404);
        } catch (\Exception $ex) {
            $output = ([
                'success' => false,
                'message'=> 'Unable to Fetch Data',
            ]);
            return response()->json($output, 500);
        }
    }
}
