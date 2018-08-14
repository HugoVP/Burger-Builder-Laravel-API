<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('orders', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->string('country', 100);
      $table->enum('delivery_method', ['fastest', 'cheapest']);
      $table->string('email', 100);
      $table->string('name', 100);
      $table->string('street', 100);
      $table->string('zip_code', 5);
      $table->tinyInteger('bacon')->unsigned();
      $table->tinyInteger('cheese')->unsigned();
      $table->tinyInteger('meat')->unsigned();
      $table->tinyInteger('salad')->unsigned();
      $table->float('price', 8, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('orders');
  }
}
