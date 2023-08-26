<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlaysetResource;
use App\Models\PlaySet;
use App\Http\Requests\StorePlaySetRequest;
use App\Http\Requests\UpdatePlaySetRequest;
use App\Repositories\Interfaces\PlaysetInterface;

class PlaySetController extends Controller
{
    private $playset;

    /**
     * Create a new instance.
     *
     * @param PlaysetInterface $playset
     */
    public function __construct(PlaysetInterface $playset)
    {
        $this->playset = $playset;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new PlaysetResource($this->playset->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlaySetRequest $request)
    {
        return new PlaysetResource($this->playset->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new PlaysetResource($this->playset->findByID($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlaySetRequest $request, string $id)
    {
        return new PlaysetResource($this->playset->update($id, $request->validated()));
    }

    /**
    //  * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->playset->delete($id);
    }
}
