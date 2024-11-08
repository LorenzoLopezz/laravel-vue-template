<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoCalificadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estado_calificador')->insert([
            ['id' => 1, 'nombre' => 'Generales'],
        ]);
        DB::statement('ALTER SEQUENCE estado_calificador_id_seq RESTART WITH 2');
    }
}
