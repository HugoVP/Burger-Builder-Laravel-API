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
      $table->string('country', 32);
      $table->string('delivery_method', 32);
      $table->string('email', 32);
      $table->string('name', 32);
      $table->string('street', 32);
      $table->string('zip_code', 32);
      $table->smallInteger('bacon');
      $table->smallInteger('cheese');
      $table->smallInteger('meat');
      $table->smallInteger('salad');
      $table->float('price', 8, 2);
      $table->timestamps();
    });

    Schema::table('orders', function (Blueprint $table) {
      $table
        ->foreign('user_id')->references('id')->on('users')
        ->onDelete('restrict')
        ->onUpdate('restrict');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('orders', function (Blueprint $table) {
      $table->dropForeign('orders_user_id_foreign');
    });

    Schema::dropIfExists('orders');
  }
}
