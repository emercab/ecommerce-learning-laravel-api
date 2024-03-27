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
    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('icon');
      $table->string('image')->nullable()->default(null);
      $table->bigInteger('department_id')->unsigned()->nullable()->default(null);
      $table->bigInteger('category_id')->unsigned()->nullable()->default(null);
      $table->integer('position')->unsigned()->default(1);
      $table->tinyInteger('type_category')->unsigned()->default(1);
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('categories');
  }
};
