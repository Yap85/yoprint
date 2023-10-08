<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('UNIQUE_KEY')->primary();
            $table->string('PRODUCT_TITLE');
            $table->text('PRODUCT_DESCRIPTION');
            $table->string('STYLE#');
            $table->string('AVAILABLE_SIZES');
            $table->string('BRAND_LOGO_IMAGE');
            $table->string('THUMBNAIL_IMAGE');
            $table->string('COLOR_SWATCH_IMAGE');
            $table->string('PRODUCT_IMAGE');
            $table->string('SPEC_SHEET');
            $table->text('PRICE_TEXT');
            $table->decimal('SUGGESTED_PRICE', 8, 2);
            $table->string('CATEGORY_NAME');
            $table->string('SUBCATEGORY_NAME');
            $table->string('COLOR_NAME');
            $table->string('COLOR_SQUARE_IMAGE');
            $table->string('COLOR_PRODUCT_IMAGE');
            $table->string('COLOR_PRODUCT_IMAGE_THUMBNAIL');
            $table->string('SIZE');
            $table->integer('QTY');
            $table->decimal('PIECE_WEIGHT', 8, 2);
            $table->decimal('PIECE_PRICE', 8, 2);
            $table->decimal('DOZENS_PRICE', 8, 2);
            $table->decimal('CASE_PRICE', 8, 2);
            $table->string('PRICE_GROUP');
            $table->string('CASE_SIZE');
            $table->integer('INVENTORY_KEY');
            $table->integer('SIZE_INDEX');
            $table->integer('SANMAR_MAINFRAME_COLOR');
            $table->integer('MILL');
            $table->string('PRODUCT_STATUS');
            $table->string('COMPANION_STYLES');
            $table->decimal('MSRP', 8, 2);
            $table->string('MAP_PRICING')->nullable();
            $table->string('FRONT_MODEL_IMAGE_URL');
            $table->string('BACK_MODEL_IMAGE');
            $table->string('FRONT_FLAT_IMAGE');
            $table->string('BACK_FLAT_IMAGE');
            $table->string('PRODUCT_MEASUREMENTS');
            $table->string('PMS_COLOR');
            $table->bigInteger('GTIN'); // use floatval
            $table->timestamps();
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