<?php

namespace Database\Seeders; // Pastikan namespace-nya benar, biasanya ini

use Illuminate\Database\Seeder;
use App\Order;
use Illuminate\Support\Facades\DB; // <--- TAMBAHKAN BARIS INI

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this.call(KategoriSeeder::class);
        $this->call(KaryaSeeder::class);
        $this->call(ShipperSeeder::class);
        $this->call(ProdukSeeder::class);
        $this->call(KelengkapanSeeder::class);
        $this->call(AdminUserSeeder::class); // Ini seeder dari yang kita buat tadi 👍

        // import provinsi
        $provinsi = base_path('database/seeds/provinsi.sql');
        DB::unprepared(file_get_contents($provinsi)); // Ini sekarang akan berfungsi

        // import kabupaten
        $kabupaten = base_path('database/seeds/kabupaten.sql');
        DB::unprepared(file_get_contents($kabupaten)); // Ini sekarang akan berfungsi

        // import kecamatan
        $kecamatan = base_path('database/seeds/kecamatan.sql');
        DB::unprepared(file_get_contents($kecamatan)); // Ini sekarang akan berfungsi

        // factory(App\Order::class, 10)->create();
        // Order::factory()->count(3)->create();
    }
}
