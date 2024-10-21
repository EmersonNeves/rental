<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Models\PaymentPlanAttribute;

class PaymentPlan extends Model
{
    protected $table = 'payment_plans';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'value',
        'description',
        'validity_date',
        'active',
        'client_type',
    ];

    public static function getAll()
    {
        $cacheKey = config('cache.prefix') . '.payment_plans';
        return Cache::rememberForever($cacheKey, function () {
            return self::all();
        });
    }

    
    public function payment_plan_attributes()
    {
        return $this->hasMany(PaymentPlanAttribute::class);
    }
}