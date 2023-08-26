<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository implements PaymentInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Payment $model
     */
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    /**
     * All Payments
     * @return Builder[]|Collection
     */
    public function all()
    {
        return $this->model->whereHas('Purchases', function ($q) {
            $q->whereHas('PurchaseAttempt', function ($q) {
                $q->where('payment_status', true);
            });
        })->with(['Booking' => function($q) {
            $q->with(['Customer.User', 'Currency', 'Court', 'Payments' => function($q) {
                $q->whereHas('Purchases', function ($q) {
                    $q->whereHas('PurchaseAttempt', function ($q) {
                        $q->where('payment_status', true);
                    });
                });
            }, 'Payments.Purchases' => function($q) {
                $q->whereHas('PurchaseAttempt', function ($q) {
                    $q->where('payment_status', true);
                });
            }, 'Payments.PaymentMode']);
        }, 'Purchases'])->orderBy('updated_at', 'desc')->get();
    }

    /**
     * Create Booking Payment
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update Booking Payment
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $itemData = $this->model->find($id);
        $itemData->update($data);
        return $itemData;
    }

    /**
     * Find Booking Payment
     * @param int $id
     * @return mixed
     */
    public function findByID(int $id)
    {
        return $this->model->find($id);
    }
}
