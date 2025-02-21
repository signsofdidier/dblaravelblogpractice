<?php

use App\Models\Blog;
use App\Models\Category;
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
        Schema::create('category_blog', function (Blueprint $table) {
            // GEEN $table->id(); → we gebruiken een samengestelde sleutel

            $table->foreignIdFor(Blog::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();

            // Zorgt ervoor dat een gebruiker geen twee keer dezelfde rol krijgt
            $table->unique(['blog_id', 'category_id']);

            // Voeg een index toe voor snelle zoekopdrachten
            $table->index(['blog_id', 'category_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_blog');
    }
};
