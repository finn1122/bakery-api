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
        Schema::create('product_types', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT por defecto
            $table->string('name', 50);
            $table->boolean('active')->default(true);
            $table->timestamps(); // Crea created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_types');
    }
};
