<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_detail', function (Blueprint $table) {
            $table->bigIncrements('detail_id');
            $table->integer('sale_id');
            $table->integer('product_id');
            $table->string('product_description');
            $table->integer('quantity');
            $table->decimal('price_unit',11,2);
            $table->decimal('price_total',11,2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesdetail');
    }
}
