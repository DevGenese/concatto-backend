<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->date('date')->after('id');
                $table->renameColumn('start_date', 'start_time');
                $table->renameColumn('end_date', 'end_time');
            });
        });

        DB::statement('ALTER TABLE schedules MODIFY start_time TIME NULL');
        DB::statement('ALTER TABLE schedules MODIFY end_time TIME NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('start_time', 'start_date');
            $table->renameColumn('end_time', 'end_date');
            $table->dropColumn('date');
        });

        DB::statement('ALTER TABLE schedules MODIFY start_date DATETIME NULL');
        DB::statement('ALTER TABLE schedules MODIFY end_date DATETIME NULL');
    }
};
