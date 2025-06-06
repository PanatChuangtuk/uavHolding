<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_content', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('about_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('language_id')->constrained()->onDelete('cascade'); 
            $table->string('name'); 
            $table->text('description'); 
            $table->longText('content'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('about_content');
    }
};
