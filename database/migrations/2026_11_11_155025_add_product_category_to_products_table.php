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
        Schema::table('products', function (Blueprint $table) {
            // On ajoute la colonne product_category après le prix
            // On la met en 'nullable' pour éviter les erreurs si un ancien produit n'a pas de catégorie
            $table->integer('product_category')->nullable()->after('product_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Si on annule la migration, on supprime la colonne
            $table->dropColumn('product_category');
        });
    }
};