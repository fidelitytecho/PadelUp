<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseAcademyRequest;
use App\Http\Requests\PurchasePlaysetRequest;
use App\Http\Requests\PurchaseProductRequest;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private $purchase;
    public function __construct(PurchaseService $purchase)
    {
        $this->purchase = $purchase;
    }
    public function product(PurchaseProductRequest $req)
    {
        return $this->purchase->purchaseProduct($req->validated());
    }

    public function playset(PurchasePlaysetRequest $req)
    {
        return $this->purchase->purchasePlayset($req->validated());
    }

    public function academy(PurchaseAcademyRequest $req)
    {
        return $this->purchase->purchaseAcademy($req->validated());
    }
}
