<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use Illuminate\Http\Request;

class PaymentPlansController extends Controller
{
    public function viewPlans()
    {
        $paymentPlans = PaymentPlan::with('payment_plan_attributes')->orderBy('value', 'asc')->get();
        return view('paymentPlans.viewPlans', compact('paymentPlans'));
    }
}