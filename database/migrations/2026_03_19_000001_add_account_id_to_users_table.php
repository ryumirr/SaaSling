<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) nullable로 먼저 추가 — 기존 행이 있어도 실패하지 않음
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // 2) 기존 users 행 backfill — account_id 없는 유저에게 기본 Account 할당
        if (DB::table('users')->whereNull('account_id')->exists()) {
            $accountId = DB::table('accounts')->insertGetId([
                'name'       => 'Default Account',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('users')->whereNull('account_id')->update(['account_id' => $accountId]);
        }

        // 3) backfill 완료 후 NOT NULL로 변경
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });
    }
};
