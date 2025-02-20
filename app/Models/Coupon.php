<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'discount', 'valid_from', 'valid_until'];

    protected $dates = ['valid_from', 'valid_until'];

    // Ensure valid_from and valid_until are treated as Carbon dates
    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];
    // Check if coupon is valid
    public function isValid()
    {
        $now = Carbon::now();
//        dd($now);
        return (!$this->valid_from || $now->greaterThanOrEqualTo($this->valid_from))
            && (!$this->valid_until || $now->lessThanOrEqualTo($this->valid_until));
    }
}
