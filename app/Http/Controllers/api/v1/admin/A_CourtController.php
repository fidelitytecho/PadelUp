<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CourtRequest;
use App\Http\Resources\admin\A_CourtResource;
use App\Http\Resources\app\CourtResource;
use App\Repositories\Interfaces\CourtInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class A_CourtController extends Controller
{
    private $court;

    /**
     * Create a new instance.
     * @param CourtInterface $court
     */
    public function __construct(CourtInterface $court)
    {
        $this->court = $court;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return A_CourtResource::collection($this->court->all(relationships: []));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourtRequest $request): A_CourtResource
    {
        return new A_CourtResource($this->court->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): A_CourtResource
    {
        return new A_CourtResource($this->court->findByID($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourtRequest $request, $id): A_CourtResource
    {
        return new A_CourtResource($this->court->update($id, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->court->delete($id);
    }
}
