<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPropertiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('properties', function (Blueprint $table)
    {
      $table->dropColumn(['beds','bed_type']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('properties', function (Blueprint $table)
    {
      $table->tinyInteger('beds')->nullable();
      $table->integer('bed_type')->unsigned()->nullable();
    });
  }
}
