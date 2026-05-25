<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('developer');
            $table->string('publisher');
            $table->decimal('price', 10, 2);
            $table->string('cover_image')->nullable();
            $table->string('trailer_url')->nullable();
            $table->json('screenshots')->nullable();
            $table->json('system_requirements')->nullable();
            $table->date('release_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(999);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
