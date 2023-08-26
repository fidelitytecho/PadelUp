<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademyRequest;
use App\Http\Resources\app\AcademyResource;
use App\Repositories\Interfaces\AcademyInterface;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    private $academy;

    /**
     * Create a new instance.
     *
     * @param AcademyInterface $academy
     */
    public function __construct(AcademyInterface $academy)
    {
        $this->academy = $academy;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): AcademyResource
    {
        return new AcademyResource($this->academy->all(relationships: []));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademyRequest $request): AcademyResource
    {
        return new AcademyResource($this->academy->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): AcademyResource
    {
        return new AcademyResource($this->academy->findByID($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): AcademyResource
    {
        return new AcademyResource($this->academy->update($id, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->academy->delete($id);
    }
}
