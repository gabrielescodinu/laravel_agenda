<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('type')->default('Generale')->after('title');
            $table->time('start_time')->nullable()->after('due_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('color', 20)->default('lime')->after('end_time');
            $table->string('icon', 50)->default('book-open')->after('color');
            $table->json('participants')->nullable()->after('icon');
        });

        DB::table('tasks')
            ->whereNull('participants')
            ->update([
                'participants' => json_encode([
                    ['name' => 'Alex', 'avatar' => 'https://i.pravatar.cc/80?img=12'],
                    ['name' => 'Marta', 'avatar' => 'https://i.pravatar.cc/80?img=22'],
                ]),
                'start_time' => DB::raw("COALESCE(start_time, '10:00')"),
                'end_time' => DB::raw("COALESCE(end_time, '11:00')"),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['type', 'start_time', 'end_time', 'color', 'icon', 'participants']);
        });
    }
};
