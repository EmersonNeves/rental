<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesBedsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::dropIfExists('properties_beds');

    Schema::create('properties_beds', function (Blueprint $table)
    {
      $table->increments('id');
      $table->integer('property_id');
      $table->tinyInteger('beds')->nullable();
      $table->integer('bed_temp_id');
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
    Schema::drop('properties_beds');
  }
}
