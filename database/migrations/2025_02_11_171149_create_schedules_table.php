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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->unsignedBigInteger('cooperative_id');
            $table->unsignedBigInteger('locality_id');  // Localidade
            $table->string('advice_type', 255);
            $table->text('observations');
            $table->unsignedBigInteger('location_id');     // Município
            $table->boolean('finished')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('cooperative_id')->references('id')->on('cooperatives');
            $table->foreign('locality_id')->references('id')->on('localities');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
