<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\A_CreateEventRequest;
use App\Http\Requests\admin\A_UpdateEventRequest;
use App\Http\Resources\admin\A_EventResource;
use App\Repositories\admin\Interfaces\A_CreateEventInterface;
use Illuminate\Http\Request;

class A_EventController extends Controller
{
    private $event;

    /**
     * Create a new instance.
     *
     * @param A_CreateEventInterface $event
     */
    public function __construct(A_CreateEventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): A_EventResource
    {
        return new A_EventResource($this->event->all(relationships: ['Court']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param A_CreateEventRequest $request
     * @return A_EventResource
     */
    public function store(A_CreateEventRequest $request): A_EventResource
    {
        return new A_EventResource($this->event->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): A_EventResource
    {
        return new A_EventResource($this->event->findByID($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(A_UpdateEventRequest $request, $id): A_EventResource
    {
        return new A_EventResource($this->event->update($id, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->event->delete($id);
    }
}
