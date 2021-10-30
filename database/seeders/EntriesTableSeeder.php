<?php

namespace Database\Seeders;

use App\Models\Entry;
use Database\Factories\EntryFactory;
use Illuminate\Database\Seeder;

class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entry::truncate();

        $factory = new EntryFactory();

        for ($i = 0; $i < 50; $i++) {
            $factory->create();
            sleep(rand(0, 2));
        }
    }
}
