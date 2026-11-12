<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // On supprime la colonne
            $table->dropColumn('product_description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Au cas où on veut annuler, on la recrée
            $table->text('product_description')->nullable();
        });
    }
};