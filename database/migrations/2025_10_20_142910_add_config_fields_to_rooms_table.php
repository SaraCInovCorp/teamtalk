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
        Schema::table('rooms', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->boolean('allow_attachment')->default(true)->after('avatar');
            $table->boolean('allow_edit_description')->default(true)->after('description');
            $table->boolean('allow_send_messages')->default(true)->after('allow_edit_description');
            $table->integer('message_delete_days')->default(7)->after('allow_send_messages');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'allow_attachment',
                'allow_edit_description',
                'allow_send_messages',
                'message_delete_days'
            ]);
        });
    }
};
