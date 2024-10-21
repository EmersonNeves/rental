<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlanAttribute extends Model
{
    protected $table = 'payment_plan_attributes';

    protected $fillable = ['attribute'];

    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }
}