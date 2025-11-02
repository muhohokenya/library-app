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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->string('shelf_location')->nullable();
            $table->enum('book_status', ['available', 'issued', 'missing', 'damaged'])->default('available')->after('available_quantity');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->integer('available_quantity');
            $table->string('publisher')->nullable();
            $table->year('published_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
