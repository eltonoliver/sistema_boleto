<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PainelCli extends CI_Controller {

	public function __construct(){
		error_reporting(0);
		parent::__construct();
		$this->load->helper("funcoes");
		if(!$this->session->userdata('cliente')){

			redirect('Login/');
		}
				 $this->db->where('cpf_cnpj',$_SESSION['cliente']); 	
		$query = $this->db->get('usuario')->result();

		if($query[0]->email == ''){

			$this->meusdados();
		}
	}

	public function index(){

		cb_boletoDeleteAntigos();
		$tam = strlen($_SESSION['cliente']); 

		if($tam == 11) {
			$_SESSION['cliente'] = "000".$_SESSION['cliente'];
		}
		$qtdRegistros = $this->db->query("SELECT COUNT(boletoId) AS qtd FROM boleto WHERE cpf_cnpj = {$_SESSION['cliente']}");

		if($qtdRegistros->num_rows() == 0){

			$msg = "Neste momento não existem boletos cadastrados.";
		}else {
			$xDias = cc_calcularDataHojeMenosXisDias(10);
			// seleciona apenas boletos (a) do cliente logado, (b) pendentes e (c) com o máximo de 21 dias de atraso		
			$sql = $this->db->query("SELECT boletoId,bancoId,dataVencimento,dataPagamento,valorTitulo,cpf_cnpj,nomeSacado,nossoNumero,numeroDocumento,msg2 FROM boleto WHERE cpf_cnpj = {$_SESSION['cliente']}  AND dataVencimento > '$xDias'  AND dataPagamento = '0000-00-00' AND nossoNumero <> '000000000000000'");

			if($sql->num_rows() > 0 ){

				

				$dados['listaData'] = $sql->result();
			}

		}



		$this->load->view('cliente/include-header');	
		$this->load->view('cliente/index',$dados);
		$this->load->view('cliente/include-footer');
	}


	public function meusDados(){

		$this->db->where('usuarioId',$this->session->userdata('userId'));
		$dados['listaDados'] = $this->db->get('usuario')->result();
		
		$this->load->view('cliente/include-header');		
		$this->load->view('cliente/meusdados',$dados);
		$this->load->view('cliente/include-footer');
	}

	public function cadastraDadosCliente($id){

		
		$data = array(
        'nome' 		=> $this->input->post('nome'),
        'email' 	=> $this->input->post('email'),
        'cpf_cnpj'  => $this->input->post('cpf'),
        'contato'   => $this->input->post('numero'),
        'endereco'   => $this->input->post('endereco'),
        'cep'  		 => $this->input->post('cep'),
		);


		$this->db->where('usuarioId',$id);
		if($this->db->update('usuario',$data)){
			$this->session->set_flashdata('mensagemSucesso', 'Dados Alterados com Sucesso');
			redirect('cliente/PainelCli/meusDados/');
		}

	}

	public function alteraSenha($id){

				$this->db->where('usuarioId',$_SESSION['userId']);
		$verificaStatusSenha = $this->db->get('usuario')->result();
		/*
		Caso a verificação seja nula a senha nunca foi alterada
		*/
		if($verificaStatusSenha[0]->status_password == NULL || $verificaStatusSenha[0]->status_password == 0){


				$senhaAntiga = $this->input->post('senhaAntiga');

				         $this->db->where('senha',$senhaAntiga);
				$query = $this->db->get('usuario')->result();
				
				if(isset($query[0]->senha) && !empty($query[0]->senha)){

					$senha1 = $this->input->post('novasenha1');
					$senha2 = $this->input->post('novasenha2');
					if($senha1 == $senha2){

						$senhaNova = md5($senha1);

						$data = array(
				        'senha' 		=> $senhaNova,
				        'status_password' => 1
				      
						);


					$this->db->where('senha',$senhaAntiga);
					if($this->db->update('usuario',$data)){
						$this->session->set_flashdata('mensagemSucesso', 'Senha Alterada com Sucesso!');
						redirect('cliente/PainelCli/meusDados/');
					}



					}else{

						$this->session->set_flashdata('msgSenhaAntigaErrada', 'Erro na Confirmação da senha!');
						redirect('cliente/PainelCli/meusDados/');
					}
					
				}else{

					$this->session->set_flashdata('msgSenhaAntigaErrada', 'Sua senha antiga está incorreta!');
					redirect('cliente/PainelCli/meusDados/');
					
				}

		}else{


				$senhaAntiga = md5($this->input->post('senhaAntiga'));

				         $this->db->where('senha',$senhaAntiga);
				$query = $this->db->get('usuario')->result();
				
				if(isset($query[0]->senha) && !empty($query[0]->senha)){

					$senha1 = $this->input->post('novasenha1');
					$senha2 = $this->input->post('novasenha2');
					if($senha1 == $senha2){

						$senhaNova = md5($senha1);

						$data = array(
				        'senha' 		=> $senhaNova
				      
						);


					$this->db->where('senha',$senhaAntiga);
					if($this->db->update('usuario',$data)){
						$this->session->set_flashdata('mensagemSucesso', 'Senha Alterada com Sucesso!');
						redirect('cliente/PainelCli/meusDados/');
					}



					}else{

						$this->session->set_flashdata('msgSenhaAntigaErrada', 'Erro na Confirmação da senha!');
						redirect('cliente/PainelCli/meusDados/');
					}
					
				}else{

					$this->session->set_flashdata('msgSenhaAntigaErrada', 'Sua senha antiga está incorreta!');
					redirect('cliente/PainelCli/meusDados/');
					
				}


		}


	}

	public function historico(){

	
		$tam = strlen($_SESSION['cliente']); 

		if($tam == 11) {
			$_SESSION['cliente'] = "000".$_SESSION['cliente'];
		}
		$qtdRegistros = $this->db->query("SELECT COUNT(boletoId) AS qtd FROM boleto WHERE cpf_cnpj = {$_SESSION['cliente']}");

		if($qtdRegistros->num_rows() == 0){

			$msg = "Neste momento não existem boletos cadastrados.";
		}else {
			$xDias = cc_calcularDataHojeMenosXisDias(10);
			// seleciona apenas boletos (a) do cliente logado, (b) pendentes e (c) com o máximo de 21 dias de atraso		
			$sql = $this->db->query("SELECT boletoId,bancoId,dataVencimento,dataPagamento,valorTitulo,cpf_cnpj,nomeSacado,nossoNumero,numeroDocumento,msg2 FROM boleto_historico WHERE cpf_cnpj = {$_SESSION['cliente']} AND dataPagamento <> '0000-00-00'");

			if($sql->num_rows() > 0 ){

				$dados['listaData'] = $sql->result();
			}
		}		
		$this->load->view('cliente/include-header');		
		$this->load->view('cliente/historico',$dados);
		$this->load->view('cliente/include-footer');
	}

	public function sair(){

		$this->session->sess_destroy();
		redirect('/Login');
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


	public function processaBoletoHistorico($boleto,$boletoID,$nossoNumero){

		if($boleto == "caixa"){
					$id = $boletoID; 
					$nossoNumeroId = $nossoNumero;
					$this->db->where('boletoID',$id);
					$this->db->where('nossoNumero',$nossoNumeroId);
					$row = $this->db->get('boleto')->result();


					$res = $this->db->query("SELECT * FROM boleto_historico WHERE boletoId = '$id' AND nossoNumero = '$nossoNumeroId'");
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