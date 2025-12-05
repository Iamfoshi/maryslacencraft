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
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->nullable();
            $table->string('title_line1');
            $table->string('title_line2')->nullable();
            $table->text('lead_paragraph')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('stat_number')->nullable();
            $table->string('stat_label')->nullable();
            $table->string('feature1_title')->nullable();
            $table->string('feature1_description')->nullable();
            $table->string('feature2_title')->nullable();
            $table->string('feature2_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_sections');
    }
};
