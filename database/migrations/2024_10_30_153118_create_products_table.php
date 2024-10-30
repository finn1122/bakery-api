<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT por defecto
            $table->string('name', 100);
            $table->foreignId('type_id')->constrained('product_types')->onDelete('cascade'); // Relación con product_types
            $table->decimal('price', 10, 2);
            $table->boolean('active')->default(true);
            $table->timestamps(); // Crea created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
