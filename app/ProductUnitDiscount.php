<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUnitDiscount extends Model
{
    protected $table = 'product_unit_discounts';
    protected $fillable = ['product_id','variation_id','unit_id','discount_type','discount_value'];
    protected $casts = [
        'product_id' => 'integer', 'variation_id' => 'integer', 'unit_id' => 'integer',
        'discount_value' => 'decimal:4',
    ];

    public function scopeForProductUnit($q, int $productId, ?int $variationId, int $unitId) {
        return $q->where('product_id',$productId)
                 ->where('unit_id',$unitId)
                 ->where(function($qq) use($variationId){
                     if (is_null($variationId)) $qq->whereNull('variation_id');
                     else $qq->where('variation_id',$variationId)->orWhereNull('variation_id');
                 })
                 ->orderByRaw('variation_id IS NULL'); // spesifik dulu
    }

    public function computeAmount(float $packBase): float {
        $v = (float)$this->discount_value;
        return $this->discount_type === 'percent' ? max(0,$packBase*$v/100) : max(0,$v);
    }
}
