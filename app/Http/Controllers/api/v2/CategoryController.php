<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CategoryRequest;
use App\Http\Requests\CategoryRelationRequest;
use App\Http\Resources\app\CategoryResource;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    private $category;

    /**
     * Create a new instance.
     *
     * @param CategoryInterface $category
     */
    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): CategoryResource
    {
        return new CategoryResource($this->category->all(relationships: ['Courts', 'Images', 'Services', 'Reviews']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): CategoryResource
    {
        return new CategoryResource($this->category->create($request->validated()['category']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): CategoryResource
    {
        return new CategoryResource($this->category->findByID($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): CategoryResource
    {
        return new CategoryResource($this->category->update($id, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->category->delete($id);
    }

    public function relation(CategoryRelationRequest $req, string $id)
    {

        if($req->hasFile('image.image_url')) {
            $image = $req->file('image.image_url');
            // $dir = storage_path('app/public') . '/images/news/';
            $fileName = hexdec(crc32($id)).'.' . $image->getClientOriginalExtension();
                // $fileName = $image->getClientOriginalName();
                // $uploadedFile = $file->move($dir, $fileName);
            $pathName = 'image/users/';
            $uploadedFile = \Storage::disk('local')->put($pathName, $image);
            if($uploadedFile) {
                $req->validated('image')['image_url'] = $pathName.''.$fileName;
            }
        }
        return $this->category->createRelationship($id, image: $req->validated()['image'] ?? [], review: $req->validated()['review'] ?? []);
    }
}
