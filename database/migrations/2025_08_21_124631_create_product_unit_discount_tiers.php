<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
    Schema::create('product_unit_discount_tiers', function (Blueprint $t) {
      $t->increments('id');
      $t->unsignedInteger('product_id');
      $t->unsignedInteger('variation_id')->nullable();
      $t->unsignedInteger('unit_id');               // sub-unit target (mis. Lusin)
      $t->decimal('min_qty', 22, 6);                // berlaku jika QTY >= min_qty
      $t->enum('discount_type', ['fixed','percent'])->default('fixed');
      $t->decimal('discount_value', 22, 4);
      $t->timestamps();

      $t->unique(['product_id','variation_id','unit_id','min_qty'], 'uniq_prod_var_unit_minqty');
      $t->index(['product_id','unit_id','min_qty']);
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_unit_discount_tiers');
    }
};
