<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::create([
            'name' => 'DKM Masjid Jami An-nur Penangisan',
            'address' => 'Desa Kempek Blok 3 Penangisan Kecamatan Gempol Kabupaten Cirebon',
        ]);
    }
}
