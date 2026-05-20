<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('capital_price', 10, 2)->default(0)->after('supplier_id');
            $table->decimal('sell_price', 10, 2)->default(0)->after('capital_price');
        });

        DB::table('items')->update(['sell_price' => DB::raw('price'), 'capital_price' => DB::raw('price')]);

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0)->after('supplier_id');
        });

        DB::table('items')->update(['price' => DB::raw('sell_price')]);

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['capital_price', 'sell_price']);
        });
    }
};
