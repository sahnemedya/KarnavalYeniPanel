<?php

namespace Database\Seeders;

use App\Models\Contacts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contacts::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'Sahne Medya',
            'email' => 'info@sahnemedya.com',
            'email2' => null,
            'phone' => '05442621505',
            'phone2' => null,
            'address' => 'Reşatbey, Berk Apt. 62017 Sk. No: 1 Kat: 2 Daire: 2',
            'country' => 'Türkiye',
            'city' => 'ADANA',
            'state' => 'Seyhan',
            'person' => null,
            'map' => null,
            'map_link' => null,
            'hit' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
