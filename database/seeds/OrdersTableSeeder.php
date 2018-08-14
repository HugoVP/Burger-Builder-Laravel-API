<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    App\User::chunk(10, function ($users) {
      $users->each(function ($user) {
        factory(App\Order::class, random_int(0, 10))->create([
          'user_id' => $user->id,
        ]);
      });
    });
  }
}
