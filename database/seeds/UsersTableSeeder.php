<?php

use Illuminate\Database\Seeder;
// use DB;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
      factory(User::class, 50)->create();
    }
  }
