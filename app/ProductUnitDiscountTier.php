<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUnitDiscountTier extends Model
{
    protected $table = 'product_unit_discount_tiers';
    protected $fillable = ['product_id','variation_id','unit_id','min_qty','discount_type','discount_value'];
    protected $casts = [
        'product_id'=>'integer','variation_id'=>'integer','unit_id'=>'integer',
        'min_qty'=>'decimal:6','discount_value'=>'decimal:4',
    ];

    public function scopeForProductUnit($q, int $productId, ?int $variationId, int $unitId) {
        return $q->where('product_id',$productId)
                 ->where('unit_id',$unitId)
                 ->where(function($qq) use($variationId){
                     if (is_null($variationId)) $qq->whereNull('variation_id');
                     else $qq->where('variation_id',$variationId)->orWhereNull('variation_id');
                 })
                 ->orderBy('min_qty','asc');
    }

    public function scopeApplicableToQty($q, float $qty) {
        return $q->where('min_qty','<=',$qty)->orderBy('min_qty','desc')->limit(1);
    }

    public function computeAmount(float $packBase): float {
        $v = (float)$this->discount_value;
        return $this->discount_type === 'percent' ? max(0,$packBase*$v/100) : max(0,$v);
    }
}
