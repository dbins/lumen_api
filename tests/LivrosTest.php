<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Livro;

class LivrosTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('api/v1/livros')
        ->seeStatusCode(200)
        ->seeJsonStructure([
            '*' => [
                'id',
                'nome',
                'categoria',
                'autor',
                'codigo_autor',
                'ano',
                'created_at',
                'updated_at',
            ]
        ]);
    }
	
	public function testCreate() {
		$parameters = [
			'nome'=>'Livro 7',
			'categoria'=>'Categoria 7',
			'autor'=>'Autor 7',
			'codigo_autor'=>'AT7',
			'ano'=>2021	
		];
		$this->post("api/v1/livros", $parameters, []);
		$this->seeStatusCode(200);
	}
	
	public function testUpdate() {
		$parameters = [
			'nome'=>'Livro 7',
			'categoria'=>'Categoria 7',
			'autor'=>'Autor 7',
			'codigo_autor'=>'AT7',
			'ano'=>2021	
		];
		$this->put("api/v1/livros/99", $parameters, []);
		$this->seeStatusCode(200);
	}
	
	public function testShow()
    {
        $dados = Livro::first();
		$this->get('api/v1/livros/1', []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
		 	 [
			   'id',
                'nome',
                'categoria',
                'autor',
                'codigo_autor',
                'ano',
                'created_at',
                'updated_at',
			 ]
		 
		);
    }
	
	public function testDelete() {
		$this->delete("api/v1/livros/99", [], []);
		$this->seeStatusCode(200);
	}
}
