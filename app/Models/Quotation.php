<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number',
        'quotation_date',
        'customer_id',
        'warehouse_id',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'note',
    ];

    /**
     * Customer Relationship
     * 
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Warehouse Relationship
     * 
     */
    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class);
    }
    /**
     * Products Relationship
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price', 'total_price', 'variation_id', 'variation_value');
    }

    /**
     * Scope Search
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('quotation_number', 'like', '%' . $search . '%');
        }
    }
    
}
