<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_upload_examples', function (Blueprint $table) {
            $table->id('id_file_upload');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });

        Schema::create('file_upload_example_images', function (Blueprint $table) {
            $table->id('id_file_upload_image');
            $table->unsignedBigInteger('id_file_upload');
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->boolean('is_cover')->default(false);
            $table->timestamps();

            $table->foreign('id_file_upload')->references('id_file_upload')->on('file_upload_examples')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_upload_example_images');
        Schema::dropIfExists('file_upload_examples');
    }
};
