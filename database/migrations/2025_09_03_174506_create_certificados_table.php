<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('certificados', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('matricula_id');
            $table->string('codigo_verificacao')->unique();
            $table->string('url_certificado')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->timestamp('data_emissao')->useCurrent();
            $table->boolean('valido')->default(true);

            $table->foreign('matricula_id')->references('id')->on('matriculas')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('certificados');
    }
};
