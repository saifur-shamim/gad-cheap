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
        Schema::table('points', function (Blueprint $table) {
            $table->string('source')->nullable()->after('points'); // purchase, referral, first_order etc.
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null')->after('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            $table->dropForeign(['order_id']); 
            $table->dropColumn(['source', 'order_id']);
        });
    }
};
