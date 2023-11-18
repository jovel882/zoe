<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Security extends Model
{
    use HasFactory;

    protected $guarded  = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function security_type()
    {
        return $this->belongsTo(SecurityType::class);
    }

    public function price(): HasOne
    {
        return $this->hasOne(SecurityPrice::class);
    }
}
