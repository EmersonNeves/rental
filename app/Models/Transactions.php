<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
  protected $table = 'transactions';

  protected $fillable = [
    'booking_id',
    'transaction_id',
    'status',
    'created_at',
    'content'
  ];
}
