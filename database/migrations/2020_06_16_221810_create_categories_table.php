<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->comment('Name');
            $table->string('icon')->nullable()->comment('Icon');
            $table->text('description')->nullable()->comment('Description');
            $table->integer('post_count')->default(0)->comment('Number of Articles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
