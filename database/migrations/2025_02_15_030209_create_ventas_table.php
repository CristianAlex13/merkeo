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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            // Relación con usuarios, sin eliminar en cascada (para SQLite suele fallar si no existe la tabla)
            $table->unsignedBigInteger('user_id');
            $table->float('total_venta')->default(0);
            $table->timestamps();

            // Si tienes tabla users, puedes mantener esta línea
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
