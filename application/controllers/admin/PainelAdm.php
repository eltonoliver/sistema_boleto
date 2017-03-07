<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PainelAdm extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->helper("funcoes");
		if(!$this->session->userdata('admin')){

			redirect('Login/');
		}
	}

	public function index(){
		cb_boletoDeleteAntigos();
		$dados['dataXdias'] = cc_calcularDataHojeMenosXisDias(10);
		$query = $this->db->query('SELECT boletoId,dataVencimento,dataPagamento,valorTitulo,cpf_cnpj,nomeSacado,nossoNumero,numeroDocumento,bancoID FROM boleto $condicao ORDER BY boletoId');
		$dados['listaBoletos'] = $query->result();
		//echo "<pre>";
		//echo $dataXdias;
		//print_r($query->result());
		//exit;

		$this->load->view('admin/include-header');
		$this->load->view('admin/index',$dados);
		$this->load->view('admin/include-footer');
	}

	public function clientes(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/clientes');
		$this->load->view('admin/include-footer');
	}

	public function cadastroClientes(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/cadastro');
		$this->load->view('admin/include-footer');
	}

	public function arquivamentoCli(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/arquivado');
		$this->load->view('admin/include-footer');
	}

	public function envioArquivo(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/remessa');
		$this->load->view('admin/include-footer');
	}
	public function sair(){

		$this->session->sess_destroy();
		redirect('/Login');
	}

	public function manipulaArquivo(){
				$this->load->helper('boleto');
				$this->load->helper('historico');
				global $nome_arquivo;
				global $data_envio;
				global $status_envio;
				$arquivosEnviados = "";

			//INFO ARQUIVO
				$file 		= $_FILES['arquivo'];
				$numFile	= count(array_filter($file['name']));
				
				//PASTA
				$folder		= $_SERVER['DOCUMENT_ROOT'].'/novo_sistema/assets/temp';
				
				//REQUISITOS
				$permite 	= array( 'REM', 'RET', 'OLD', 'old', 'rem' , 'ret');
				$maxSize	= 1024 * 1024 * 10; // Max 10MB
				$msg = array();
				$errorMsg = array (
				0 => 'Não houve erro',
				1 => 'O arquivo no upload é maior do que o limite do PHP',
				2 => 'O arquivo ultrapassa o limite de tamanho especifiado no HTML',
				3 => 'O upload do arquivo foi feito parcialmente',
				4 => 'Não foi feito o upload do arquivo',

				);


			
				$file = $_FILES['arquivo'];
				$numFile = count(array_filter($file['name']));
				
			if ($numFile <= 0){
				echo "<script> alert('Selecione pelo menos um arquivo')</script>";
				redirect('admin/PainelAdm/envioArquivo');
			} else {
						
				$cont = 0;
				for($i =0 ; $i < $numFile ; $i++){
						$name 	= $file['name'][$i];
						$type	= $file['type'][$i];
						$size	= $file['size'][$i];
						$error	= $file['error'][$i];
						$tmp	= $file['tmp_name'][$i];
						
						$extensao = @end(explode('.', $name));
						$novoNome = rand().".$extensao";
						
						if($error != 0)
							echo "<b>$name :</b> ".$errorMsg[$error];
						else if(!in_array($extensao, $permite)){
							echo  "<b>$name :</b> Erro arquivo $extensao não suportado! <br>";
							//redirect('admin/PainelAdm/envioArquivo');
						} else if($size > $maxSize){
							echo "<b>$name :</b> Erro imagem ultrapassa o limite de 10MB <br>";
							//redirect('admin/PainelAdm/envioArquivo');
						} else {
							
							if(move_uploaded_file($tmp, $folder.'/'.$novoNome))
							{
								$arquivosEnviados .= "<b>$name : Upload Realizado com Sucesso! <br>";
								$cont++;
								$status_envio = 1;
								$data_envio = date("Y-m-d");
								$data_envio .= " ".date("H:i");
								$nome_arquivo = $novoNome;
								
								$text = file($_SERVER['DOCUMENT_ROOT']."/novo_sistema/assets/temp/$nome_arquivo");

								
								if(substr($text[0], 76, 3) == "237"){
										$nomeBanco = "BRADESCO";
										cb_boletoInsertBradesco();
										ch_historicoInsertArquivo($nomevBanco);
										// $html .= cb_boletoSelect();
								} elseif (substr($text[0], 0, 3) == "104"){								
										$nomeBanco = "CAIXA ECONOMICA";
										cb_boletoInsertCaixaEconomica();
										ch_historicoInsertArquivo($nomeBanco);								
										// $html .= cb_boletoSelect();
								}			
								 
								if (!unlink($_SERVER['DOCUMENT_ROOT']."/novo_sistema/assets/temp/".$novoNome)){
									echo " Erro ao deletar $novoNome ";
								}

							} else {
								$msg[] = "<b>$novoNome :</b> Desculpe! Ocorreu um erro...";
								echo " Não foi possível enviar o arquivo, tente novamente";
							}
					}//fim do else			
				} // fim do for
				if($cont != 0){
					echo "<center> $arquivosEnviados <br> <a href='?p=boleto'> <button> Visualizar Boletos</button></a></center>";			
				}
			} //fim do primeiro else 
		
		
	}

	public function historico(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/historico');
		$this->load->view('admin/include-footer');
	}

	public function relatorio(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/relatorio');
		$this->load->view('admin/include-footer');
	}

	public function adm(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/administrador');
		$this->load->view('admin/include-footer');
	}

	public function teste(){
		$this->load->helper('t2');
		$this->load->helper('t1');
		t();
		arquivo();

		echo $_SERVER['DOCUMENT_ROOT'];
	}
}