<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\A_CreateEventRequest;
use App\Http\Resources\admin\A_EventResource;
use App\Repositories\admin\Interfaces\A_CreateEventInterface;
use Illuminate\Http\Request;

class A_EventController extends Controller
{
    private $createEvent;

    /**
     * Create a new instance.
     *
     * @param A_CreateEventInterface $createEvent
     */
    public function __construct(A_CreateEventInterface $createEvent)
    {
        $this->createEvent = $createEvent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param A_CreateEventRequest $request
     * @return A_EventResource
     */
    public function store(A_CreateEventRequest $request): A_EventResource
    {
        return new A_EventResource($this->createEvent->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
