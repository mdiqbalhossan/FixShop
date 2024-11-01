<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    /**
     * Scope Active
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Relation with Product
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    /**
     * Scope Search
     */
    public function scopeSearch($query, $search)
    {
        if($search === null) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $search . '%');
    }
}