<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) nullable로 먼저 추가
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // 2) 기존 rows backfill — user의 account_id를 subscription에 복사
        DB::statement('
            UPDATE subscriptions
            SET account_id = (SELECT account_id FROM users WHERE users.id = subscriptions.user_id)
        ');

        // 3) NOT NULL로 변경
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });
    }
};
