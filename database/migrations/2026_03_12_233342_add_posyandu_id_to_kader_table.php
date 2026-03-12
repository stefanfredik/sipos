<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kader', function (Blueprint $table) {
            $table->char('posyandu_id', 26)->nullable()->after('user_id');
            $table->foreign('posyandu_id')->references('id')->on('posyandu')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kader', function (Blueprint $table) {
            $table->dropForeign(['posyandu_id']);
            $table->dropColumn('posyandu_id');
        });
    }
};
