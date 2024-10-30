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
        Schema::create('bakeries', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT por defecto
            $table->string('name', 100);
            $table->string('address', 255)->nullable();
            $table->string('opening_hours', 100)->nullable();
            $table->string('profile_picture', 255)->nullable(); // URL de la foto de perfil
            $table->boolean('active')->default(true);
            $table->timestamps(); // Crea created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('bakeries');
    }
};
