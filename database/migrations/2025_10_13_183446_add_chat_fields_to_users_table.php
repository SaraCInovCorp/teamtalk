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
            $table->string('avatar')->nullable()->after('profile_photo_path');
            $table->boolean('is_active')->default(true)->after('email_verified_at');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('status_message')->nullable()->after('bio');
            $table->timestamp('last_seen_at')->nullable()->after('status_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'is_active', 'bio', 'status_message', 'last_seen_at']);
        });
    }
};
