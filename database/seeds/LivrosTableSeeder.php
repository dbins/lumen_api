<?php

use Illuminate\Database\Seeder;

class LivrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')
		->table('livros')
		->insert([
			'nome'=>'Livro 1',
			'categoria'=>'Categoria 1',
			'autor'=>'Autor 1',
			'codigo_autor'=>'AT1',
			'ano'=>2019
		]);
		
		app('db')
		->table('livros')
		->insert([
			'nome'=>'Livro 2',
			'categoria'=>'Categoria 1',
			'autor'=>'Autor 2',
			'codigo_autor'=>'AT2',
			'ano'=>2019	
		]);
		
		app('db')
		->table('livros')
		->insert([
			'nome'=>'Livro 3',
			'categoria'=>'Categoria 2',
			'autor'=>'Autor 1',
			'codigo_autor'=>'AT1',
			'ano'=>2020	
		]);
		
		app('db')
		->table('livros')
		->insert([
			'nome'=>'Livro 4',
			'categoria'=>'Categoria 2',
			'autor'=>'Autor 2',
			'codigo_autor'=>'AT2',
			'ano'=>2020	
		]);
		
		app('db')
		->table('livros')
		->insert([
			'nome'=>'Livro 5',
			'categoria'=>'Categoria 3',
			'autor'=>'Autor 3',
			'codigo_autor'=>'AT3',
			'ano'=>2021	
		]);
		
		app('db')
		->table('livros')
		->insert([
			'nome'=>'Livro 6',
			'categoria'=>'Categoria 3',
			'autor'=>'Autor 3',
			'codigo_autor'=>'AT3',
			'ano'=>2021	
		]);
    }
}
