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
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan', 50)->default('Free Plan')->after('email');
            $table->string('avatar_url')->nullable()->after('plan');
            $table->string('timezone', 60)->default('Europe/Rome')->after('avatar_url');
            $table->string('preferred_view', 20)->default('month')->after('timezone');
            $table->text('bio')->nullable()->after('preferred_view');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'avatar_url', 'timezone', 'preferred_view', 'bio']);
        });
    }
};
