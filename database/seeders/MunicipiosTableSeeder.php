<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MunicipiosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('municipios')->insert([
        ['nombre' => 'Albiol'],
        ['nombre' => 'Aleixar'],
        ['nombre' => 'Alforja'],
        ['nombre' => 'Almoster'],
        ['nombre' => 'Arbolí'],
        ['nombre' => 'Argentera'],
        ['nombre' => 'Les Borges del Camp'],
        ['nombre' => 'Botarell'],
        ['nombre' => 'Cambrils'],
        ['nombre' => 'Capafonts'],
        ['nombre' => 'Castellvell del Camp'],
        ['nombre' => 'Colldejou'],
        ['nombre' => 'Duesaigües'],
        ['nombre' => 'La Febró'],
        ['nombre' => 'Maspujols'],
        ['nombre' => 'Mont-roig del Camp'],
        ['nombre' => 'Montbrió del Camp'],
        ['nombre' => 'Prades'],
        ['nombre' => 'Pratdip'],
        ['nombre' => 'Reus'],
        ['nombre' => 'Riudecanyes'],
        ['nombre' => 'Riudecols'],
        ['nombre' => 'Riudoms'],
        ['nombre' => 'La Selva del Camp'],
        ['nombre' => 'Vandellòs i l\'Hospitalet de l\'Infant'],
        ['nombre' => 'Vilanova d\'Escornalbou'],
        ['nombre' => 'Vilaplana'],
        ['nombre' => 'Vinyols i els Arcs'],
    ]);
}

}
