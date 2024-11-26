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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bakery_id')->constrained('bakeries')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('address', 255)->nullable();
            $table->string('opening_hours', 100)->nullable();
            $table->string('profile_picture', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branchs');
    }
};
