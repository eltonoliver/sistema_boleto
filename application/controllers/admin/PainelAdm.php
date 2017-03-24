<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PainelAdm extends CI_Controller {

	public function __construct(){
		error_reporting(0);
		parent::__construct();
		$this->load->helper("funcoes");
		if(!$this->session->userdata('admin')){

			redirect('Login/');
		}
	}

	public function index(){
	
			cb_boletoDeleteAntigos();
			$dados['dataXdias'] = cc_calcularDataHojeMenosXisDias(10);
			$query = $this->db->query('SELECT boletoId,dataVencimento,dataPagamento,valorTitulo,cpf_cnpj,nomeSacado,nossoNumero,numeroDocumento,bancoID FROM boleto ORDER BY boletoId');
			$dados['listaBoletos'] = $query->result();
	

		$this->load->view('admin/include-header');
		$this->load->view('admin/index',$dados);
		$this->load->view('admin/include-footer');
	}

	public function boletoPorUsuario($cpf_cnpj){
			cb_boletoDeleteAntigos();
			$dados['dataXdias'] = cc_calcularDataHojeMenosXisDias(10);
		$query = $this->db->query('SELECT boletoId,dataVencimento,dataPagamento,valorTitulo,cpf_cnpj,nomeSacado,nossoNumero,numeroDocumento,bancoID FROM boleto WHERE cpf_cnpj = "'.$cpf_cnpj.'"  ORDER BY boletoId');
			$dados['listaBoletos'] = $query->result();

		

		$this->load->view('admin/include-header');
		$this->load->view('admin/index',$dados);
		$this->load->view('admin/include-footer');


	}
	public function clientes(){

		$this->db->where('status_usuario !=',NULL);
		$this->db->where('status_usuario !=',0);	
		$query = $this->db->get('usuario')->result();
		$dados['listaUsuarioCli'] = $query;

		$this->load->view('admin/include-header');
		$this->load->view('admin/clientes',$dados);
		$this->load->view('admin/include-footer');
	}

	public function editarClientes($id){
		$this->db->where('usuarioId',$id);
		$dados['listaDados'] = $this->db->get('usuario')->result();
		$this->load->view('admin/include-header');
		$this->load->view('admin/editar-clientes',$dados);
		$this->load->view('admin/include-footer');


	}

	public function visualizarCliente($id){

		$this->db->where('usuarioId',$id);
		$dados['listaDados'] = $this->db->get('usuario')->result();
		$this->load->view('admin/include-header');
		$this->load->view('admin/dados-clientes',$dados);
		$this->load->view('admin/include-footer');
	}

	public function editar($id){

		
		$data = array(
        'nome' 				=> $this->input->post('nome'),
        'email' 			=> $this->input->post('email'),
        'cpf_cnpj'  		=> $this->input->post('cpf_cnpj'),
        'contato'  			=> $this->input->post('contato'),
        'endereco'   		=> $this->input->post('endereco'),
        'cep'  				=> $this->input->post('cep'),
        'senha'     		=> md5($this->input->post('senha')),
        'status_password' => 1	
		);


		$this->db->where('usuarioId',$id);
		if($this->db->update('usuario',$data)){
			$this->session->set_flashdata('mensagemSucesso', 'Dados Alterados com Sucesso');
			redirect('admin/PainelAdm/editarClientes/'.$id);
		}

	}

	public function cadastroClientes(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/cadastro');
		$this->load->view('admin/include-footer');
	}

	public function arquivarCliente($id){

		$data = array(
    
        'status_usuario' => 0
		);

		$this->db->where('usuarioId',$id);
		if($this->db->update('usuario',$data)){
			$this->session->set_flashdata('mensagemSucesso', 'Cliente arquivado com Sucesso');
			redirect('admin/PainelAdm/clientes/'.$id);
		}

	}

	public function arquivamentoCli(){

		$this->db->where('status_usuario',0);
		$this->db->where('status_usuario','NULL');
		$dados['listaUsuarioArq'] = $this->db->get('usuario')->result();

		$this->load->view('admin/include-header');
		$this->load->view('admin/arquivado',$dados);
		$this->load->view('admin/include-footer');
	}

	public function desArquivaMentoCli($id){

		$data = array(
    
        'status_usuario' => 1
		);

		$this->db->where('usuarioId',$id);
		if($this->db->update('usuario',$data)){
			$this->session->set_flashdata('mensagemSucesso', 'Cliente desarquivado com Sucesso');
			redirect('admin/PainelAdm/arquivamentoCli/');
		}

	}

	public function envioArquivo(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/remessa');
		$this->load->view('admin/include-footer');
	}
	public function sair(){

		$this->session->sess_destroy();
		redirect('admin');
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
					echo "<center> $arquivosEnviados <br> <a href='".base_url()."admin/PainelAdm'> <button> Visualizar Boletos</button></a></center>";			
				}
			} //fim do primeiro else 
		
		
	}

	public function historico(){

		$dados['listaHistoricoAcesso'] = $this->db->get('historicousuario')->result();

		$this->load->view('admin/include-header');
		$this->load->view('admin/historico',$dados);
		$this->load->view('admin/include-footer');
	}

	public function relatorio(){
		$ano = gmdate('Y');
		$mesSeguinte = gmdate('m');
		$dia1MesAtual = $ano."-".$mesSeguinte."-01";
		$mesSeguinte = $mesSeguinte+1;
	
		if($mesSeguinte > 12){
			$mesSeguinte = 1;
			$ano = $ano +1;
		}

		$dia1MesSeguinte = $ano."-".$mesSeguinte."-01";

		$sqlPagosEsteMes    = $this->db->query("SELECT count(boletoId) AS Pagos FROM boleto WHERE dataVencimento>'$dia1MesAtual' AND dataVencimento<'$dia1MesSeguinte' and descricaoBoleto='Pago!' ")->result();
		$sqlPendentesEsteMes = $this->db->query("SELECT count(boletoId) AS Pendentes FROM boleto WHERE dataVencimento>'$dia1MesAtual' AND dataVencimento<'$dia1MesSeguinte' and descricaoBoleto='Em aberto' ")->result();

		//Calcula a data do dia 1 do mes passado ate o dia 1 do mes atual
		$mesPassado = gmdate('m');
		$mesPassado = $mesPassado-1;

		if($mesPassado < 1){
			$mesPassado = "12";
			$ano = $ano-1;
		}

		$dia1MesPassado = $ano."-".$mesPassado."-01";

		$sqlPagosMesPassado = $this->db->query("SELECT count(boletoId) AS Pagos FROM boleto WHERE dataVencimento>'$dia1MesPassado' AND dataVencimento<'$dia1MesAtual' and descricaoBoleto='Pago!' ")->result();
		$sqlPendentesMesPassado = $this->db->query("SELECT count(boletoId) AS Pendentes FROM boleto WHERE dataVencimento>'$dia1MesPassado' AND dataVencimento<'$dia1MesAtual' and descricaoBoleto='Em aberto' ")->result();

		if( ($sqlPagosEsteMes) and  ($sqlPendentesEsteMes) and  ($sqlPendentesMesPassado) and  ($sqlPagosMesPassado) ){

			$dados['qtdPagosAtual'] 		= $sqlPagosEsteMes[0]->Pagos;
			$dados['qtdPendentesAtual'] 	= $sqlPendentesEsteMes[0]->Pendentes;
			$dados['qtdPagosPassado'] 		= $sqlPendentesMesPassado[0]->Pendentes;
			$dados['qtdPendentesPassado']	= $sqlPagosMesPassado[0]->Pagos;
			$dados['totalAtual']			= $dados['qtdPagosAtual'] + $dados['qtdPendentesAtual'];
			$dados['totalPassado']			= $dados['qtdPagosPassado'] + $dados['qtdPendentesPassado'];

			if($dados['totalAtual'] == 0){
				$dados['porcentagemPagosAtual'] = 0;
				$dados['porcentagemPendentesAtual'] = 0;
			} else {
				$dados['porcentagemPagosAtual'] = $dados['qtdPagosAtual']*100/$dados['totalAtual'];
				$dados['porcentagemPendentesAtual'] = $dados['qtdPendentesAtual']*100/$dados['totalAtual'];			
			}

			if($dados['totalPassado'] == 0){
	 			$dados['porcentagemPendentesPassado'] = 0;
	 			$dados['porcentagemPagosPassado'] = 0;
			} else {
				$dados['porcentagemPagosPassado'] = $dados['qtdPagosPassado']*100/$dados['totalPassado'];
				$dados['porcentagemPendentesPassado'] = $dados['qtdPendentesPassado']*100/$dados['totalPassado'];			
			}

		}



		$this->load->view('admin/include-header');
		$this->load->view('admin/relatorio',$dados);
		$this->load->view('admin/include-footer');
	}

	public function adm(){
		$dados['listaAdmin'] = $this->db->get('usuarioadmin')->result();	
		$this->load->view('admin/include-header');
		$this->load->view('admin/administrador',$dados);
		$this->load->view('admin/include-footer');
	}

	public function addAdmin(){

		$this->load->view('admin/include-header');
		$this->load->view('admin/add-admin');
		$this->load->view('admin/include-footer');	
	}

	public function cadastraAdmin(){

	
		$nome 		= $this->input->post('nome');
		$email 		= $this->input->post('email');
		$nContato 	= $this->input->post('contato');
		$senha		= $this->input->post('senha');
		$confSenha  = $this->input->post('confSenha');
		$permissoes = $this->input->post('adm');

		
		if($senha == $confSenha){

			if(!empty($email) && !empty($senha) && !empty($nome)){

				$data = array(
	       				 'nome' => $nome,
	       				 'senha' => md5($senha),
	       				 'email' => $email,
	       				 'contato' => $nContato
					);

					$this->db->insert('usuarioadmin', $data);
					$id = $this->db->insert_id();
					for($i = 0 ; $i < count($permissoes); $i++){

						$data = array(
		       				 
		       				 'id_permissao' => $permissoes[$i],
		       				 'id_usuadmin' => $id
						);

						$this->db->insert('permissao_admin', $data);
					}	

					


					$this->session->set_flashdata('msg', 'Dados Cadastrados com sucesso!');
					redirect('admin/PainelAdm/addAdmin/');

			}else{

				
					$this->session->set_flashdata('msg', 'E-mail, Senha e Nome São Obrigatórios!');
					redirect('admin/PainelAdm/addAdmin/');
				
			}

		}else{

			
				$this->session->set_flashdata('msg', 'Digite a mesma senha nos dois campos!');
				redirect('admin/PainelAdm/addAdmin/');
			
		}


	}

	public function editarAdmin($id){
		$this->db->where('adminId',$id);
		$dados['listaAdmin'] = $this->db->get('usuarioadmin')->result();

		$this->db->where('id_usuadmin',$id);
		$dados['listaPermissao'] = $this->db->get('permissao_admin')->result();

		$this->load->view('admin/include-header');
		$this->load->view('admin/edit-admin',$dados);
		$this->load->view('admin/include-footer');	
	}

	public function editarInforAdm($idAdmin){

		$nome 		= $this->input->post('nome');
		$email 		= $this->input->post('email');
		$nContato 	= $this->input->post('contato');
		$senha		= $this->input->post('senha');
		$confSenha  = $this->input->post('confSenha');
		$permissoes = $this->input->post('adm');

		

			if(!empty($email) && !empty($nome)){

			 try{

			 		if(!empty($confSenha)){

			 			if($senha == $confSenha){

			 				$data = array(
		       				 'nome' => $nome,
		       				 'senha' => md5($senha),
		       				 'email' => $email,
		       				 'contato' => $nContato
							);

			 			}else{

			 					$this->session->set_flashdata('erro', 'A confirmação de senha está diferente da senha!');
								redirect('admin/PainelAdm/editarAdmin/'.$idAdmin);
			 			}
			 		}else{

			 			$data = array(
	       				 'nome' => $nome,
	       				 'email' => $email,
	       				 'contato' => $nContato
					);
			 		}	

			 }catch(Exception $e){



			 }		
				

					$this->db->where('id_usuadmin',$idAdmin);
					$this->db->delete('permissao_admin');

					$this->db->where('adminId', $idAdmin);
					$this->db->update('usuarioadmin', $data);
	
					for($i = 0 ; $i < count($permissoes); $i++){

						$data = array(
		       				 
		       				 'id_permissao' => $permissoes[$i],
		       				 'id_usuadmin' => $idAdmin
						);

						$this->db->insert('permissao_admin', $data);
					}	

					


					$this->session->set_flashdata('msg', 'Dados Alterados com sucesso!');
					redirect('admin/PainelAdm/editarAdmin/'.$idAdmin);

			}else{

				
					$this->session->set_flashdata('msg', 'E-mail, Senha e Nome São Obrigatórios!');
					redirect('admin/PainelAdm/editarAdmin/'.$idAdmin);
				
			}

		

	}

	public function deleteAdmin($id){
			/*DELETA AS PERMISSOES*/
			$this->db->where('id_usuadmin',$id);
			$this->db->delete('permissao_admin');


		   $this->db->where('adminId',$id);
		if($this->db->delete('usuarioadmin')){
			
			$this->session->set_flashdata('msg', 'Usuário Excluído com Sucesso!');
			redirect('admin/PainelAdm/adm/');
		}

	}

	public function processaBoleto($boleto,$boletoID,$nossoNumero){

			if($boleto == "caixa"){
					$id = $boletoID; 
					$nossoNumeroId = $nossoNumero;
					$this->db->where('boletoID',$id);
					$this->db->where('nossoNumero',$nossoNumeroId);
					$row = $this->db->get('boleto')->result();


					$res = $this->db->query("SELECT * FROM boleto WHERE boletoId = '$id' AND nossoNumero = '$nossoNumeroId'");
					$row = $res->row();
					
				
						
						$valorTituloDB 		= $row->valorTitulo;
						$nossoNumeroDB 		= $row->nossoNumero;
						$dataVencimentoDB	= $row->dataVencimento;
						$dataDocumentoDB	= $row->dataDocumento;
						$numeroDocumentoDB	= $row->numeroDocumento;
						$nomeSacadoDB		= $row->nomeSacado;	
						$enderecoCompletoDB	= $row->enderecoCompleto;
						$cidadeDB			= $row->cidade;
						$estadoDB			= $row->estado;
						$cepDB				= $row->cep;
						$mensagem1DB		= $row->mensagem1;
						$mensagem2DB		= $row->mensagem2;	
						$aceiteDB			= $row->aceite;
						$especieTituloDB	= $row->especieTitulo;
						$agenciaDB			= $row->agencia;
						$contaCorrenteDB	= $row->contaCorrente;
						$codigoCedenteDB	= $row->codigoCedente;
						$valorPagoDB		= $row->valorPago;
						

					// valores no banco sao inteiros: tornar decimal
					$valorTituloDB = $valorTituloDB / 100;

					// DADOS DO BOLETO PARA O SEU CLIENTE
					$dias_de_prazo_para_pagamento = 5;
					//$taxa_boleto = 2.95;
					$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";

					//$valor_boleto = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

					// Composição Nosso Numero - CEF SIGCB
					$dadosboleto["nosso_numero1"] = substr($nossoNumeroDB,0 ,3); // tamanho 3
					$dadosboleto["nosso_numero_const1"] = "1"; //constanto 1 , 1=registrada , 2=sem registro
					$dadosboleto["nosso_numero2"] = substr($nossoNumeroDB,3 ,3); // tamanho 3
					$dadosboleto["nosso_numero_const2"] = "1"; //constanto 2 , 4=emitido pelo proprio cliente
					$dadosboleto["nosso_numero3"] = substr($nossoNumeroDB,6 ,9); // tamanho 9

					// Montagem data Padrao d/m/y
					$diaVencimento = substr($dataVencimentoDB,8,2);
					$mesVencimento = substr($dataVencimentoDB,5,2);
					$anoVencimento = substr($dataVencimentoDB,0,4);
					$dataVencimento = $diaVencimento."/".$mesVencimento."/".$anoVencimento;

					$diaDataDocumento = substr($dataDocumentoDB,8,2);
					$mesDataDocumento = substr($dataDocumentoDB,5,2);
					$anoDataDocumento = substr($dataDocumentoDB,0,4);
					$dataDocumento = $diaDataDocumento."/".$mesDataDocumento."/".$anoDataDocumento;
					//fim Montagem Data Vencimento




					$dadosboleto["numero_documento"] = $numeroDocumentoDB;	// Num do pedido ou do documento
					$dadosboleto["data_vencimento"] = $dataVencimento; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
					$dadosboleto["data_documento"] = $dataDocumento; // Data de emissão do Boleto
					$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
					$dadosboleto["valor_boleto"] = $valorTituloDB; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

					// DADOS DO SEU CLIENTE
					$dadosboleto["sacado"] = $nomeSacadoDB;
					$dadosboleto["endereco1"] = $enderecoCompletoDB;
					$dadosboleto["endereco2"] = "{$cidadeDB} - {$estadoDB} -  CEP: {$cepDB}";

					// INFORMACOES PARA O CLIENTE
					// $dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Nonononono";
					// $dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
					// $dadosboleto["demonstrativo3"] = "CIVILCORP - http://www.civilcorp.com.br";

					// INSTRUÇÕES PARA O CAIXA
					$dadosboleto["instrucoes1"] = "- Sr. Caixa, COBRAR {$mensagem1DB}";
					$dadosboleto["instrucoes2"] = "- {$mensagem2DB}";
					// $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: xxxx@civilcorp.com.br";
					// $dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

					// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
					$dadosboleto["quantidade"] = "1";
					$dadosboleto["valor_unitario"] = $valorTituloDB;
					$dadosboleto["aceite"] = $aceiteDB;		
					$dadosboleto["especie"] = "R$";
					$dadosboleto["especie_doc"] = $especieTituloDB;


					// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


					// DADOS DA SUA CONTA - CEF
					$dadosboleto["agencia"] = substr($agenciaDB,1,4); // Num da agencia, sem digito
					$dadosboleto["conta"] = substr($contaCorrenteDB,1,6);	// Num da conta, sem digito
					$dadosboleto["conta_dv"] = "0";	// Digito do Num da conta

					// DADOS PERSONALIZADOS - CEF
					$dadosboleto["conta_cedente"] = $codigoCedenteDB; // Código Cedente do Cliente, com 6 digitos (Somente Números)
					$dadosboleto["carteira"] = "RG";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

					// SEUS DADOS
					$dadosboleto["identificacao"] = "CIVILCORP INCORPORACOES LTDA";
					$dadosboleto["cpf_cnpj"] = "013.040.140/0001-35";
					$dadosboleto["endereco"] = "AV. ANDRE ARAUJO, ALEIXO";
					$dadosboleto["cidade_uf"] = "MANAUS - AM";
					$dadosboleto["cedente"] = "CIVILCORP  INCORPORACOES LTDA - SISTEMA DE BOLETOS";
					if ($valorPagoDB == "") {
							// se boleto estiver vencido, calcular juros e multa
							$dataLocal = gmdate("Y-m-d");
							if ($dataVencimentoDB < $dataLocal) {//if ($row['dataVencimento'].' 20:45:00' < gmdate("Y-m-d H:i:s"))
								/*
								$hoje = mktime(0,0,0,date("m"),date("d"),date("Y"));
								$vencimento = mktime(0,0,0,substr($row['dataVencimento'],5,2),substr($row['dataVencimento'],8,2),substr($row['dataVencimento'],0,4));
								$dias = ($hoje-$vencimento)/86400;
								
								//Retorna o dia da semana de uma determinada data.
								$diaSemana = diasemana($row['dataVencimento']);
								if ($diaSemana == 0) {
									$dias = $dias-1;
								} else if ($diaSemana == 6) {
									$dias = $dias-2;
								}
											
								$valorBoleto = $dadosboleto["valor_boleto"];
								$multa = 1.02 * $valor_boleto;
								$jurosDias = (pow(1+0.00033,$dias) * $valor_boleto) - $valor_boleto;
								$total = $multa + $jurosDias;
								$dadosboleto["valor_boleto"] = intval($total);
								$dadosboleto["valor_boleto"] = str_replace(".", "", $dadosboleto["valor_boleto"]);
								$total = $dadosboleto["valor_boleto"];
								
								//multa e juros
								$totalMulta = ($total/100) - (intval($valor_boleto)/100);			
								*/
								if ($ajustes = cb_valorMulta($valorTituloDB, $dataVencimentoDB)) {
									//number_format($totalMulta, 2, ",", ".");
									$dadosboleto["valor_boleto"] = $ajustes['valorFinal'];
									$totalMulta = number_format($ajustes['acrescimo'], 2, ",", ".");
									$dadosboleto["data_vencimento"] = ohs_formataData($ajustes['vencimento'], '', br);
								} else {
									$dadosboleto["valor_boleto"] = $valorTituloDB;
									$totalMulta = '';
								}
							} else {
								$dadosboleto["valor_boleto"] = $valorTituloDB;
								$totalMulta = '';
							}
						} else {
							$dadosboleto["valor_boleto"] = $valorTituloDB;
							$totalMulta = '';
						}

					// NÃO ALTERAR!
					//echo "<center>";
					//	include("../inc/boletophp/funcoes_cef_sigcb.php"); 
					//	include("../inc/boletophp/layout_cef.php");
					//echo "</center>";

					// Traz o dia da semana para qualquer data informada
						$codigobanco = "104";
						$codigo_banco_com_dv = $this->geraCodigoBanco($codigobanco);
						$nummoeda = "9";
						$fator_vencimento = $this->fator_vencimento($dadosboleto["data_vencimento"]);

						//valor tem 10 digitos, sem virgula
						$valor = $this->formata_numero($dadosboleto["valor_boleto"]*100, 10, 0, "valor");
						//agencia é 4 digitos
						$agencia = $this->formata_numero($dadosboleto["agencia"],4,0);
						//conta é 5 digitos
						$conta = $this->formata_numero($dadosboleto["conta"],5,0);
						//dv da conta
						$conta_dv = $this->formata_numero($dadosboleto["conta_dv"],1,0);
						//carteira é 2 caracteres
						$carteira = $dadosboleto["carteira"];

						//conta cedente (sem dv) com 6 digitos
						$conta_cedente = $this->formata_numero($dadosboleto["conta_cedente"],6,0);
						//dv da conta cedente
						$conta_cedente_dv = $this->digitoVerificador_cedente($conta_cedente);

						//campo livre (sem dv) é 24 digitos
						$campo_livre = $conta_cedente . $conta_cedente_dv . $this->formata_numero($dadosboleto["nosso_numero1"],3,0) . $this->formata_numero($dadosboleto["nosso_numero_const1"],1,0) . $this->formata_numero($dadosboleto["nosso_numero2"],3,0) . $this->formata_numero($dadosboleto["nosso_numero_const2"],1,0) . $this->formata_numero($dadosboleto["nosso_numero3"],9,0);
						//dv do campo livre
						$dv_campo_livre = $this->digitoVerificador_nossonumero($campo_livre);
						$campo_livre_com_dv ="$campo_livre$dv_campo_livre";

						//nosso número (sem dv) é 17 digitos
						$nnum = $this->formata_numero($dadosboleto["nosso_numero_const1"],1,0).$this->formata_numero($dadosboleto["nosso_numero_const2"],1,0).$this->formata_numero($dadosboleto["nosso_numero1"],3,0).$this->formata_numero($dadosboleto["nosso_numero2"],3,0).$this->formata_numero($dadosboleto["nosso_numero3"],9,0);
						//nosso número completo (com dv) com 18 digitos
						$nossonumero = $nnum . $this->digitoVerificador_nossonumero($nnum);

						// 43 numeros para o calculo do digito verificador do codigo de barras
						$dv = $this->digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campo_livre_com_dv", 9, 0);
						// Numero para o codigo de barras com 44 digitos
						$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campo_livre_com_dv";

						$agencia_codigo = $agencia." / ". $conta_cedente ."-". $conta_cedente_dv;

						$dadosboleto["codigo_barras"] = $linha;
						$dadosboleto["linha_digitavel"] = $this->monta_linha_digitavel($linha);
						$dadosboleto["agencia_codigo"] = $agencia_codigo;
						$dadosboleto["nosso_numero"] = $nossonumero;
						$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

						/**********************************************************************/

						include $_SERVER['DOCUMENT_ROOT'].'/novo_sistema/assets/inc/boletophp/layout_cef.php';
					}else{

						echo 'Erro no sistema consulte o administrador Nº BRD';
					}
		
	}

	function digitoVerificador_nossonumero($numero) {
	$resto2 = $this->modulo_11($numero, 9, 1);
     $digito = 11 - $resto2;
     if ($digito == 10 || $digito == 11) {
        $dv = 0;
     } else {
        $dv = $digito;
     }
	 return $dv;
}


function digitoVerificador_cedente($numero) {
  $resto2 = $this->modulo_11($numero, 9, 1);
  $digito = 11 - $resto2;
  if ($digito == 10 || $digito == 11) $digito = 0;
  $dv = $digito;
  return $dv;
}

function digitoVerificador_barra($numero) {
	$resto2 = $this->modulo_11($numero, 9, 1);
     if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
        $dv = 1;
     } else {
        $dv = 11 - $resto2;
     }
	 return $dv;
}


// FUNÇÕES
// Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		retira as virgulas
		formata o numero
		preenche com zeros
		*/
    $numero = str_replace(",","",$numero);
    $numero = str_replace(".","",$numero);
		while (strlen($numero) < $loop) {
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}


function fbarcode($valor){

$fino = 1 ;
$largo = 3 ;
$altura = 50 ;

  $barcodes[0] = "00110" ;
  $barcodes[1] = "10001" ;
  $barcodes[2] = "01001" ;
  $barcodes[3] = "11000" ;
  $barcodes[4] = "00101" ;
  $barcodes[5] = "10100" ;
  $barcodes[6] = "01100" ;
  $barcodes[7] = "00011" ;
  $barcodes[8] = "10010" ;
  $barcodes[9] = "01010" ;
  for($f1=9;$f1>=0;$f1--){ 
    for($f2=9;$f2>=0;$f2--){  
      $f = ($f1 * 10) + $f2 ;
      $texto = "" ;
      for($i=1;$i<6;$i++){ 
        $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      }
      $barcodes[$f] = $texto;
    }
  }


//Desenho da barra


//Guarda inicial
?><img src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
<?php
$texto = $valor ;
if((strlen($texto) % 2) <> 0){
	$texto = "0" . $texto;
}

// Draw dos dados
while (strlen($texto) > 0) {
  $i = round(esquerda($texto,2));
  $texto = direita($texto,strlen($texto)-2);
  $f = $barcodes[$i];
  for($i=1;$i<11;$i+=2){
    if (substr($f,($i-1),1) == "0") {
      $f1 = $fino ;
    }else{
      $f1 = $largo ;
    }
?>
    src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/p.png width=<?php echo $f1?> height=<?php echo $altura?> border=0><img 
<?php
    if (substr($f,$i,1) == "0") {
      $f2 = $fino ;
    }else{
      $f2 = $largo ;
    }
?>
    src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/b.png width=<?php echo $f2?> height=<?php echo $altura?> border=0><img 
<?php
  }
}

// Draw guarda final
?>
src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/p.png width=<?php echo $largo?> height=<?php echo $altura?> border=0><img 
src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src=<?php echo base_url(); ?>assets/inc/boletophp/imagens/p.png width=<?php echo 1?> height=<?php echo $altura?> border=0> 
  <?php
} //Fim da função

function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

function fator_vencimento($data) {
  if ($data != "") {
	$data = explode("/",$data);
	$ano = $data[2];
	$mes = $data[1];
	$dia = $data[0];
    return(abs(($this->_dateToDays("1997","10","07")) - ($this->_dateToDays($ano, $mes, $dia))));
  } else {
    return "0000";
  }
}

function _dateToDays($year,$month,$day) {
    $century = substr($year, 0, 2);
    $year = substr($year, 2, 2);
    if ($month > 2) {
        $month -= 3;
    } else {
        $month += 9;
        if ($year) {
            $year--;
        } else {
            $year = 99;
            $century --;
        }
    }
    return ( floor((  146097 * $century)    /  4 ) +
            floor(( 1461 * $year)        /  4 ) +
            floor(( 153 * $month +  2) /  5 ) +
                $day +  1721119);
}

function modulo_10($num) { 
		$numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo (falor 10)
            $temp = $numeros[$i] * $fator; 
            $temp0=0;
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }
		
        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }
		
        return $digito;
		
}

function modulo_11($num, $base=9, $r=0)  {
    /**
     *   Autor:
     *           Pablo Costa <pablo@users.sourceforge.net>
     *
     *   Função:
     *    Calculo do Modulo 11 para geracao do digito verificador 
     *    de boletos bancarios conforme documentos obtidos 
     *    da Febraban - www.febraban.org.br 
     *
     *   Entrada:
     *     $num: string numérica para a qual se deseja calcularo digito verificador;
     *     $base: valor maximo de multiplicacao [2-$base]
     *     $r: quando especificado um devolve somente o resto
     *
     *   Saída:
     *     Retorna o Digito verificador.
     *
     *   Observações:
     *     - Script desenvolvido sem nenhum reaproveitamento de código pré existente.
     *     - Assume-se que a verificação do formato das variáveis de entrada é feita antes da execução deste script.
     */                                        

    $soma = 0;
    $fator = 2;

    /* Separacao dos numeros */
    for ($i = strlen($num); $i > 0; $i--) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num,$i-1,1);
        // Efetua multiplicacao do numero pelo falor
        $parcial[$i] = $numeros[$i] * $fator;
        // Soma dos digitos
        $soma += $parcial[$i];
        if ($fator == $base) {
            // restaura fator de multiplicacao para 2 
            $fator = 1;
        }
        $fator++;
    }

    /* Calculo do modulo 11 */
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }
        return $digito;
    } elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}

function monta_linha_digitavel($codigo) {
		
		// Posição 	Conteúdo
        // 1 a 3    Número do banco
        // 4        Código da Moeda - 9 para Real
        // 5        Digito verificador do Código de Barras
        // 6 a 9   Fator de Vencimento
		// 10 a 19 Valor (8 inteiros e 2 decimais)
        // 20 a 44 Campo Livre definido por cada banco (25 caracteres)

        // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
        // do campo livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 0, 4);
        $p2 = substr($codigo, 19, 5);
        $p3 = $this->modulo_10("$p1$p2");
        $p4 = "$p1$p2$p3";
        $p5 = substr($p4, 0, 5);
        $p6 = substr($p4, 5);
        $campo1 = "$p5.$p6";

        // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 24, 10);
        $p2 = $this->modulo_10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo2 = "$p4.$p5";

        // 3. Campo composto pelas posicoes 16 a 25 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 34, 10);
        $p2 = $this->modulo_10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo3 = "$p4.$p5";

        // 4. Campo - digito verificador do codigo de barras
        $campo4 = substr($codigo, 4, 1);

        // 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
        // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
		$p1 = substr($codigo, 5, 4);
		$p2 = substr($codigo, 9, 10);
		$campo5 = "$p1$p2";

        return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
}

function geraCodigoBanco($numero) {
    $parte1 = substr($numero, 0, 3);
    $parte2 = $this->modulo_11($parte1);
    return $parte1 . "-" . $parte2;
}
}