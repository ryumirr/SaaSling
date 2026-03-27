<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->foreignId('user_id')->constrained();   // 이력 보험용 중복
            $table->string('item_name');
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('quantity')->default(1);
            $table->unsignedTinyInteger('months');         // 구독 개월 수 (1/3/6/12)
            $table->enum('payment_status', ['READY', 'UNPAYED', 'PENDING', 'PAYED', 'CANCELED', 'INVALID'])
                  ->default('READY');
            $table->timestamp('created_at')->useCurrent();  // insert-only, updated_at 없음
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
