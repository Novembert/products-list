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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('vat_rate', 3, 2)->check('vat_rate >= 0.00 AND vat_rate <= 1.00');
            $table->float('position')->default(0);
            $table->foreignId('tag_id')->nullable()->constrained('tags')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
