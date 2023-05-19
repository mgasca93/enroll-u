<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class administratorSeeder extends Seeder
{
    protected $administrators = [];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->administrators = [
            [
                'name'  => 'Mario Gasca',
                'username' => 'mgasca',
                'password'  => '$2y$10$jN6MJPZrlvT/79x3307YSewku5lRLMlsB/IstzoAuLw.YszJAvmea',
                'rol'       => 'super_administrator'
            ],
            [
                'name'  => 'Juan Perez',
                'username' => 'jperez',
                'password'  => '$2y$10$jN6MJPZrlvT/79x3307YSewku5lRLMlsB/IstzoAuLw.YszJAvmea',
                'rol'       => 'administrator'
            ],
        ];
        DB::table('administrators')->insert($this->administrators);
    }
}
