<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Livro;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\File;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Tibonilab\Pdf\PdfServiceProvider;
use Barryvdh\DomPDF\Facade as PDF;

class LivrosController extends Controller
{
	/**
     * @OA\Info(
     *     version="1.0",
     *     title="API de Livros",
     *     description="Swagger do Microserviço de Livros",
     *      @OA\Contact(
     *          email="admin@admin.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     * *     * @OA\Server(
     *      url="http://localhost:8000",
     *      description="Demo API Server"
     *      )
     *
     *     @OA\Tag(
     *     name="Livros",
     *     description="API Endpoints - Livros"
     *     )
     */


 


	protected $livro;
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Livro $livro)
    {
        $this->livro = $livro;
		$this->codigo = 0;
    }

    /**
     * @OA\Get(
     *     tags={"livros"},
     *     summary="Retorna uma lista de livros",
     *     description="Retorna um array de livros",
     *     path="/api/v1/livros",
     *     @OA\Response(response="200", description="Uma lista de livros disponíveis",
     *     @OA\JsonContent(
     *    @OA\Property(property="data", type="array", @OA\Items(
     *
 *                 @OA\Property(
 *                     property="nome",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="codigo_autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="ano",
 *                     type="integer"
 *                 ),
 *                 example={"nome": "a3fb6", "categoria": "Jessica Smith", "autor": "Teste", "codigo_autor":"teste", "ano": 111}
 *            
     *    ))
     *     )
     *     ),
     *     @OA\Response(response=422, description="Faltam informações")
     * ),
     */

    public function index(Request $request) {
       
        $livros = $this->livro->all();
		//Log::error('Estou na rota livros');
		//Apenas para fins de testes - imprimir no terminal
		//Desativar ao colocar em producao
		$out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$out->writeln("Output Terminal");
		$out->writeln($request->header('authorization'));
		return $livros;
    }

   /**
     * @OA\Get(
     *     tags={"livros"},
     *     summary="Retorna um livro",
     *     description="Retorna um objeto livros",
     *     path="/api/v1/livros/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          description="ID do livro",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *    @OA\Response(
 *    response=200,
 *    description="Retorna o livro selecionado",
 *    @OA\JsonContent(
 *                  @OA\Property(
 *                     property="nome",
 *                     type="string",
 *                     example="Nome do Livro"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="codigo_autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="ano",
 *                     type="integer"
 *                 )
 *    )
 * ),
     *     @OA\Response(response=422, description="Faltam informações")
     * ),
     */

    public function show($id) {
       
        $livro = $this->livro->find($id);

        if (empty($livro)) {
               return "Não foram encontrados resultados";
        }
       
        return $livro;
    }

    /**
 * @OA\Post(
 *     tags={"livros"},
 *     path="/api/v1/livros",
 *     summary="Criar um novo livro",
 *     description="Criar um novo livro",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="nome",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="codigo_autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="ano",
 *                     type="integer"
 *                 ),
 *                 example={"nome": "a3fb6", "categoria": "Jessica Smith", "autor": "Teste", "codigo_autor":"teste", "ano": 111}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *          @OA\JsonContent(
 *                  @OA\Property(
 *                     property="nome",
 *                     type="string",
 *                     example="Nome do Livro"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="codigo_autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="ano",
 *                     type="integer"
 *                 )
 *          )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Missing Data"
 *     )
 * )
 */
    
    public function store(Request $request) {
       
        // Validate 
        $this->validate($request, [
            'nome' => 'required|string',
            'categoria' => 'required|string',
            'autor' => 'required|string',
            'codigo_autor' => 'required|string',
            'ano' => 'required|integer'
           ]);

        // Create 
        $livro = $this->livro->create([
            'nome' => $request->input('nome'),
            'categoria' =>  $request->input('categoria'),
            'autor' =>  $request->input('autor'),
            'codigo_autor' =>  $request->input('codigo_autor'),
            'ano' =>  $request->input('ano')
        ]);
		return $livro;
    }

    /**
 * @OA\Put(
 *     tags={"livros"},
 *     path="/api/v1/livros/{id}",
 *     summary="Atualizar um livro",
 *     description="Atualizar um livro",
 *    @OA\Parameter(
 *          name="id",
 *          description="ID do livro",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="nome",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="codigo_autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="ano",
 *                     type="integer"
 *                 ),
 *                 example={"nome": "a3fb6", "categoria": "Jessica Smith", "autor": "Teste", "codigo_autor":"teste", "ano": 111}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *                  @OA\Property(
 *                     property="nome",
 *                     type="string",
 *                     example="Nome do Livro"
 *                 ),
 *                 @OA\Property(
 *                     property="categoria",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="codigo_autor",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="ano",
 *                     type="integer"
 *                 )
 *    )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Missing Data"
 *     )
 * )
 */

    public function update(Request $request, $id) {
       
        // Validate if the input for each field is correct 
        $this->validate($request, [
            'nome' => 'required|string',
            'categoria' => 'required|string',
            'autor' => 'required|string',
            'codigo_autor' => 'required|string',
            'ano' => 'required|integer',
           ]);

        // Find the player you want to update
        $livro = $this->livro->find($id);

        // Return error if not found
        if (empty($livro)) {
            return "Nao foi localizado";
        }

        // Update
        $livro->update([
            'nome' => $request->input('nome'),
            'categoria' =>  $request->input('categoria'),
            'autor' =>  $request->input('autor'),
            'codigo_autor' =>  $request->input('codigo_autor'),
            'ano' =>  $request->input('ano'),
        ]);

        return $livro;
    }

     /**
     * @OA\Delete(
     *     tags={"livros"},
     *     summary="Exclui um livro",
     *     description="Exclui um livro",
     *     path="/api/v1/livros/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          description="ID do livro",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Livro excluído"),
     *     @OA\Response(response=422, description="Faltam informações")
     * ),
     */

    public function destroy($id) {
       
       $livro = $this->livro->find($id);

        if (empty($livro)) {
            return "Nao foi localizado";
        }

        $livro->delete();

        return;
    }
	
	
	  /**
 * @OA\Post(
 *     tags={"livros"},
 *     path="/api/v1/contato",
 *     summary="Enviar e-mail",
 *     description="Enviar e-mail",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="nome",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="telefone",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="mensagem",
 *                     type="string"
 *                 ),
 *                 example={"nome": "a3fb6", "email": "teste@teste.com.br", "telefone": "11-11111111", "mensagem":"teste"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Missing Data"
 *     )
 * )
 */
	
	public function contact(Request $request) {
       
        $this->validate($request, [
            "nome"=>"required",
            "email"=>"required|email",
            "telefone"=>"required",
			"mensagem"=>"required"
            ]
        );
		
        Mail::to('bins.br@gmail.com')->send(new SendEmail($request->input('nome'), $request->input('email'), $request->input('telefone'), $request->input('mensagem')));
        return;
    }
	
	  /**
     * @OA\Post(
     *     tags={"livros"},
     *     summary="Upload de capa de um livro",
     *     description="Upload de capa de um livro",
     *     path="/api/v1/livros/upload/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          description="ID do livro",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Livro excluído"),
     *     @OA\Response(response=422, description="Faltam informações")
     * ),
     */
	
	//Multipart form data
	public function upload2(Request $request, $id) {
		$this->validate($request, [
            "nome"=>"required"
            ]
        );
		$picName = $request->file('cover')->getClientOriginalName();
        $picName = uniqid() . '_' . $picName;
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'cover' . DIRECTORY_SEPARATOR;
        $destinationPath = rtrim(app()->basePath('public/' . $path),DIRECTORY_SEPARATOR) ; // upload path
        File::makeDirectory($destinationPath, 0777, true, true);
        $request->file('cover')->move($destinationPath, $picName);
		return;
	}
	
	//Base64 string
	public function upload(Request $request, $id) {
		$this->validate($request, [
            "nome"=>"required",
			"cover"=>"required"
            ]
        );
		
		$picName = uniqid() . '_cover.jpg';
		$image = base64_decode($request->input('cover'));
		$path = 'uploads' . DIRECTORY_SEPARATOR . 'cover' . DIRECTORY_SEPARATOR . $picName;
        $destinationPath = rtrim(app()->basePath('public/' . $path),DIRECTORY_SEPARATOR) ; // upload path
        File::put($destinationPath, $image);
		return;
	}
	
	
	 /**
     * @OA\Get(
     *     tags={"livros"},
     *     summary="Retorna uma capa de livro",
     *     description="Retorna uma capa de livro",
     *     path="/api/v1/livros/upload/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          description="ID do livro",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Capa de livro",   
	 *		@OA\JsonContent(
     *                  @OA\Property(
     *                     property="data",
     *                     type="string",
     *                     example="String com a imagem em formato base64"
     *                 )
     *   		 )
     *     ),
     *     @OA\Response(response=422, description="Faltam informações")
     * ),
     */
	
	public function image($id){
		$folder = 'uploads' . DIRECTORY_SEPARATOR . 'cover' . DIRECTORY_SEPARATOR . 'teste.jpg';
        $path = rtrim(app()->basePath('public/' . $folder),DIRECTORY_SEPARATOR); 
		$arquivo = File::get($path);
		$arquivo = base64_encode($arquivo);
		return response()->json([
            'data' => $arquivo
        ], 200);
	}
	
	 /**
     * @OA\Get(
     *     tags={"livros"},
     *     summary="Exporta os dados dos livros para um arquivo",
     *     description="Exporta os dados dos livros para um arquivo",
     *     path="/api/v1/livros/exportar/{formato}",
     *     @OA\Parameter(
     *          name="formato",
     *          description="Formato de exportação",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Response(
 *    response=200,
 *    description="Retorna o relatorio selecionado",
 *    @OA\JsonContent(
 *                  @OA\Property(
 *                     property="arquivo",
 *                     type="string",
 *                     example="Nome do arquivo"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="string"
 * 					   example="Conteudo do arquivo no formato Base64"     
 *                 )
 *    )
 * ),
     *     @OA\Response(response=422, description="Faltam informações")
     * ),
     */
	
	public function export($formato) {
		$output = "";
		$arquivo = "";
		$conteudo = $this->livro->all();
        if (strtoupper($formato)=="CSV"){
			$arquivo =  uniqid() . '_relatorio.csv';
			$output = implode(",", array('nome', 'categoria', 'autor', 'codigo autor', 'ano')) . "\r\n";

			foreach ($conteudo as $row) {
				$output .=  implode(",", array($row['nome'], $row['categoria'], $row['autor'], $row['codigo_autor'], $row['ano'])) . "\r\n";
			}
			$output = rtrim($output, "\n");
			$output = base64_encode($output);
		}
		if (strtoupper($formato)=="PDF"){
			$arquivo =  uniqid() . '_relatorio.pdf';
			$html = '<html><body>';
			
			$html .= '<table border="1"><tr><th>Nome</th><th>Categoria</th><th>Autor</th><th>Codigo Ano</th><th>Nome</th></tr>';
			foreach ($conteudo as $row) {
				$html .= '<tr><td>' . $row['nome'] . '</td><td>' . $row['categoria'] . '</td><td>' . $row['autor'] . '</td><td>' . $row['codigo_autor'] . '</td><td>' . $row['ano'] . '</td></tr>';
			}
			$html .= '</table></body></html>';
			$path = 'uploads' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $arquivo;
			$destinationPath = rtrim(app()->basePath('public' . DIRECTORY_SEPARATOR . $path),DIRECTORY_SEPARATOR) ; 
			
			//Para salvar em disco
			PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save($destinationPath);
			$output = File::get($destinationPath);
			$output = base64_encode($output);		
			
			//Para salvar como string
			//$output = PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->output();
		}
		if (strtoupper($formato)=="EXCEL"){
			$arquivo =  uniqid() . '_relatorio.xlsx';
			$path = 'uploads' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $arquivo;
			$destinationPath = rtrim(app()->basePath('public' . DIRECTORY_SEPARATOR . $path),DIRECTORY_SEPARATOR) ; 
			$writer = SimpleExcelWriter::create($destinationPath);
			foreach ($conteudo as $row) {
				$writer->addRow(array("nome"=>$row['nome'], "categoria"=>$row['categoria'], "autor"=>$row['autor'], "codigo_autor"=>$row['codigo_autor'], "ano"=>$row['ano']));
			}
			$writer->close();
			$output = File::get($destinationPath);
			$output = base64_encode($output);		
			
		}
		return response()->json([
			'arquivo' => $arquivo,
            'data' => $output
        ], 200);
    }
	
	public function docs() {
        $path = rtrim(app()->basePath('public/swagger.json')); 
		$arquivo = File::get($path);
		return response($arquivo,200)->header('Content-Type', 'application/json');
    }
}
