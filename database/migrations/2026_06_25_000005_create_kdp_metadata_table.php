<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kdp_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->json('keywords_json')->nullable();
            $table->json('categories_json')->nullable();
            $table->integer('price')->nullable();
            $table->string('royalty_plan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kdp_metadata');
    }
};
