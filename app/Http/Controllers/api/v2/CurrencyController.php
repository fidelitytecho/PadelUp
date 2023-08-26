<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CurrencyRequest;
use App\Http\Resources\app\CurrencyResource;
use App\Repositories\Interfaces\CurrencyInterface;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    private $currency;

    /**
     * Create a new instance.
     *
     * @param CurrencyInterface $currency
     */
    public function __construct(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): CurrencyResource
    {
        return new CurrencyResource($this->currency->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyRequest $request): CurrencyResource
    {
        return new CurrencyResource($this->currency->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): CurrencyResource
    {
        return new CurrencyResource($this->currency->findByID($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): CurrencyResource
    {
        return new CurrencyResource($this->currency->update($id, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->currency->delete($id);
    }
}
