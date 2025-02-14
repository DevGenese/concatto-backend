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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_type_id'); // Tipo de despesa
            $table->unsignedBigInteger('schedule_id');     // Relação com a agenda
            $table->decimal('amount', 10, 2);             // Valor da despesa
            $table->string('observation', 255)->nullable(); // Observação
            $table->text('attachment')->nullable();       // Anexo em Base64
            $table->timestamps();

            // Foreign keys
            $table->foreign('expense_type_id')->references('id')->on('expense_types');
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
