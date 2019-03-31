<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    private $categories = [
		'Ciclo de palestras',
		'Conferências',
		'Congressos',
		'Fórum',
		'Mesa redonda',
		'Painel',
		'Reunião',
		'Semana',
		'Seminário',
		'Simpósio',
		'Workshop',
		'Convenção',
		'Feira',
		'Posses',
		'Assinaturas',
		'Inaugurações',
		'Homenagens e premiações',
		'Condecorações',
		'Visitas institucionais',
		'Exposição',
		'Mostra',
		'Concursos',
		'Formaturas',
		'Torneio',
		'Sociais',
		'Profissionais',
		'Oficiais',
		'Técnicos',
		'Artísticos',
		'Colturais',
		'Outros',
	];

    public function run()
    {
        foreach ($this->categories as $category) {
			Category::create([
				'name' => $category
			]);
		}
    }
}
