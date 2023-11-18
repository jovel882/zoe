<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecurityType extends Model
{
    use HasFactory;

    protected $guarded  = [
        'id',
        'created_at',
        'updated_at',
    ];
    
    public function securities(): HasMany
    {
        return $this->hasMany(Security::class);
    }
}
