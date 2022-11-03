<?php

namespace Database\Seeders;

use App\Models\Mention;
use Illuminate\Database\Seeder;

class MentionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mention::factory(50)->create();
    }
}
