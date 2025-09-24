<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $t) {
            $t->foreignId('cupom_id')->nullable()->constrained('cupons')->nullOnDelete();
            $t->decimal('desconto_total', 10, 2)->default(0);
        });
    }
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $t) {
            $t->dropConstrainedForeignId('cupom_id');
            $t->dropColumn('desconto_total');
        });
    }
};
