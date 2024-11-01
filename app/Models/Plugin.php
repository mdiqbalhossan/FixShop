<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'version',
        'code',
        'status',
    ];

    public function isActive()
    {
        return $this->status == 'active';
    }
}
