<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('barcode')->nullable();
        $table->bigInteger('category_id')->nullable();
        $table->string('others')->nullable();
        $table->bigInteger('sub_category_id')->nullable();
        $table->bigInteger('brand_id')->nullable();
        $table->bigInteger('unit_id')->nullable();
        $table->bigInteger('currency_id')->nullable();
        $table->string('image_path')->nullable();
        $table->string('image_path1')->nullable();
        $table->string('image_path2')->nullable();
        $table->string('image_path3')->nullable();
        $table->double('purchase_price')->nullable();
        $table->double('wholesale_price')->nullable();
        $table->double('retails_price')->nullable();
        $table->decimal('intl_retails_price', 10, 2)->nullable();
        $table->double('regular_price')->nullable();
        $table->string('discount_type')->nullable();
        $table->double('discount')->nullable();
        $table->decimal('intl_discount', 10, 2)->nullable();
        $table->text('description')->nullable();
        $table->text('warrenty')->nullable();
        $table->double('quantity')->nullable();
        $table->bigInteger('discount_id')->nullable();
        $table->bigInteger('warranty_id')->nullable();
        $table->tinyInteger('deleted')->default(0);
        $table->bigInteger('user_id')->nullable();
        $table->timestamps();
        $table->bigInteger('product_id')->nullable();
        $table->bigInteger('kitchen_id')->nullable();
        $table->tinyInteger('have_variant')->default(0);
        $table->tinyInteger('have_product_variant')->default(0);
        $table->text('serial')->nullable();
        $table->string('color')->nullable();
        $table->integer('warranties_count')->nullable();
        $table->date('manufactory_date')->nullable();
        $table->date('expiry_date')->nullable();
        $table->tinyInteger('is_variable_weight')->default(0);
        $table->integer('minimum_stock')->nullable();
        $table->enum('product_type', ['standard', 'service'])->default('standard');
        $table->tinyInteger('is_ecommerce')->default(0);
        $table->string('color_code')->nullable();
        $table->tinyInteger('is_specification')->default(0);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
