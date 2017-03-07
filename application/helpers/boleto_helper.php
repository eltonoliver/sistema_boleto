<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Este arquivo contém as funçoes do BOLETO.
*/

/*
Pessoa responsável pelos boletos na Civilcorp - Ligia Telefone para contato: (3194-2154)
*/


/**
 * exibe listagem de boletos para um nosso número fornecido via $_POST['nossoNumero']
 * @return string $html
 */
function cb_boletoBuscarNossonumero()
{
	$html .="
		<script language='Javascript'>
			function confirmacaoImprimir(id) { 
				window.open('boleto_bradesco.php?id='+id, 'Boleto','width=1080,height=900');
			}
		</script>

		<script language='Javascript'>
			function confirmacaoDeletar(id) { 
				var resposta = confirm('Deseja remover esse Boleto?');
				if (resposta == true) { 
					window.location.href = '?p=boleto&q=excluir&id='+id;
				}
			}
		</script>
		";
	$html .= cb_boletoFormularioBusca();

	$nossoNumero = mysql_real_escape_string(strip_tags($_POST['nossoNumero']));
	
	if ($result = mysql_query("SELECT * FROM boleto WHERE (nossoNumero) LIKE '%$nossoNumero%'")) {
		if ($rows = mysql_num_rows($result)) {
			$html .= "
				<table>
					<tr>
						<td>ID</td>
						<td>N&ordm; do Documento</td>
						<td width='200'>Nome Sacado</td>
						<td>CPF</td>
						<td>Nosso N&uacute;mero</td>
						<td>Vencimento</td>
						<td>Valor</td>
						<td>Status</td>
						<td>Op&ccedil;&otilde;es</td>
					</tr>
			";
			while ($boleto = mysql_fetch_array($result)) {

				if (strlen(intval($boleto['cpf_cnpj'])) >= 12) {
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),0,2).".";
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),2,3).".";
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),5,3)."/";
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),8,4)."-";
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),12,2);
				} elseif (strlen(intval($boleto['cpf_cnpj'])) == 11) {
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),0,3).".";
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),3,3).".";
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),6,3)."-";
					$cpf_cnpj = substr(intval($boleto['cpf_cnpj']),9,2);
				} 

				$html.= "
					<tr>
						<td>".$boleto['boletoId']."</td>
						<td>".$boleto['numeroDocumento']."</td>
						<td>".$boleto['nomeSacado']."</td>
						<td>$cpf_cnpj</td>
						<td>".$boleto['nossoNumero']."</td>
						<td>".substr($boleto['dataVencimento'],8,2)."/".substr($boleto['dataVencimento'],5,2)."/".substr($boleto['dataVencimento'],0,4)."</td>
						<td>".number_format((intval($boleto['valorTitulo'])/100), 2, ",", ".")."</td>
						<td>".$boleto['descricaoBoleto']."</td> 
						<td>
				";
				if ($boleto['nossoNumero'] != "00000000000") {
					$html.= "
						<a href='javascript:func() '  onclick=confirmacaoImprimir(".$boleto['boletoId'].")>
							<img src='imagens/imprimir.gif' width='20' height='20'>
						</a>
					";
				}
				$html.= "
							<a href='javascript:func()' onclick=confirmacaoDeletar(".$boleto['boletoId'].")><img src='imagens/deletar.jpeg' width='20' height='20'></a>
						</td>
					</tr>
				";
			}
		} else {
			$html .= "
				Nenhum resultado encontrado
			";
		}

	} else {
		$html .= "
			Nenhum resultado encontrado
		";
	}

	$html.= "</table>";
	return $html;
}


/**
 * exibe o formulario de busca
 * @return string $html
 */
function cb_boletoFormularioBusca()
{
	$html = "
		<div class='busca-boleto'>
			<form action='?p=boleto&q=buscar' method='POST'>
				<div>
					<table>
						<tr>
							<td><input type='text' name='buscaTxt' placeholder='Digite aqui'  class='tamanho' /></td>
							<td>
								<select name='tipoFiltro'> 
									<option value='1'>Nosso Número</option>
									<option value='2'>Nome do Sacado</option>
									<option value='3'>CPF/CNPJ</option>
								</select>
							</td>
							<td><input type='submit' name='botaoEnviar' value='Pesquisar Boletos' class='bt' /></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	";
	return $html;
}

/**
 * exibe o formulario de busca
 * @return string $html
 */
function cb_boletoDelete()
{
	$id = $_GET['id']; 
	$query = 'DELETE FROM boleto WHERE boletoId='.$id;
	
	if ($res = mysql_query($query)) {
		$html .= "
			<script>
				alert('Registro Excluído com sucesso!!!!!');
			</script>
		";
		$html .= cb_boletoSelect();
	} else {
		$html .= "
			<script>
				alert('Falhar ao excluir registro');
			</script>
	 	";
	}
	return $html;
}
/**
 * [cb_boletoSubstring description]
 * @param  [type] $bancoId     [description]
 * @param  [type] $tipoArquivo [description]
 * @return [type]              [description]
 */
function cb_boletoSubstring($bancoId, $tipoArquivo)
{
	switch($tipoArquivo) {
		case 'remessa':
			$subArray[237] = array(
				'bancoID'				=> 237,
				'empresaID' 			=> array(20,17),
				'numeroControle' 		=> array(37,25),
				'codigoCedente' 		=> "",
				'campoVerificaMulta'	=> array(65,1),
				'nossoNumeroDigito'		=> array(81,1),
				'descontos'				=> array(82,10),
				'numeroDocumento'		=> array(110,10),
				'dataVencimentoAno'		=> array(124,2),
				'dataVencimentoMes'		=> array(122,2),
				'dataVencimentoDia'		=> array(120,2),
				'valorTitulo'			=> array(126,13),
				'especieTitulo'			=> array(147,2),
				'aceite'				=> array(149,1),
				'dataEmissaoAno'		=> array(154,2),
				'dataEmissaoMes'		=> array(152,2),
				'dataEmissaoDia'		=> array(150,2),
				'valorAtraso'			=> array(160,13),
				'cpf_cnpj'				=> array(220,14),
				'nomeSacado'			=> array(234,40),
				'enderecoCompleto'		=> array(274,40),
				'msg1'					=> array(314,12),
				'msg2'					=> array(334,60),
				'cep'					=> array(326,8),
				'mensagem1'				=> array(1,80),
				'mensagem2'				=> array(81,80),
				'mensagem3'				=> array(161,80),
				'mensagem4'				=> array(241,80),
				'agencia'				=> array(369,5),
				'contaCorrente'			=> array(374,8),
				'nossoNumero'			=> array(382,12),	
				); 

			$subArray[104] = array(
				'bancoID'				=> 104,           
				'empresaID' 			=> array(18,15),  
				'numeroControle' 		=> array(195,25), 
				'codigoCedente' 		=> array(23,6),	  
				'multa'					=> array(74,13),   			
				'descontos'				=> "", 
				'numeroDocumento'		=> array(62,11), 
				'dataVencimentoDia'		=> array(77,2),  
				'dataVencimentoMes'		=> array(79,2),	 
				'dataVencimentoAno'		=> array(81,4),  
				'valorTitulo'			=> array(85,15), 
				'especieTitulo'			=> array(106,2), 
				'aceite'				=> array(108,1), 
				'dataEmissaoDia'		=> array(109,2), 
				'dataEmissaoMes'		=> array(111,2), 
				'dataEmissaoAno'		=> array(113,4), 
				'valorAtraso'			=> array(126,13),
				'cpf_cnpj'				=> array(19,14), 
				'nomeSacado'			=> array(33,40), 
				'enderecoCompleto'		=> array(73,40), 
				'msg1'					=> "",
				'msg2'					=> "", 
				'cep'					=> array(128,8), 
				'mensagem1'				=> array(103,40), 
				'mensagem2'				=> array(143,40), 
				'mensagem3'				=> "",
				'mensagem4'				=> "",
				'agencia'				=> array(53,5),  				
				'nossoNumeroModalidade'	=> array(40,2),
				'nossoNumero' 			=> array(41,15),  
				'nossoNumeroDigito'		=> array(56,1),
				'tipoArquivo' 			=> array(142,1),
				'carteira'	 			=> array(57,1),				
				'cidade'	 			=> array(136,15),
				'estado'				=> array(151,2),				
				'segmento' 				=> array(13,1) 				
				);  
			break;
		
		case 'retorno':
				$subArray[237] = array(				
				'numeroControle' 		=> array(37,25),
				'nossoNumero' 			=> array(70,11),				
				'numeroDocumento'		=> array(116,10),
				'carteira' 				=> array(57,1),
				'dataPagamentoDia'		=> array(295,2),
				'dataPagamentoMes'		=> array(297,2), 
				'dataPagamentoAno'		=> array(299,2),
				'valor'					=> array(253,13),				
				); 

				$subArray[104] = array(
				'segmento'		 		=> array(13,1), 
				'numeroControle' 		=> array(105,25), 
				'nossoNumeroModalidade'	=> array(40,2),
				'nossoNumero' 			=> array(41,15),  
				'nossoNumeroDigito'		=> array(56,1),   
				'numeroDocumento'		=> array(58,11),  
				'carteira' 				=> array(57,1),  
				'dataProcessamentoDia'		=> array(137,2),  
				'dataProcessamentoMes'		=> array(141,2),  
				'dataProcessamentoAno'		=> array(143,2),   
				'dataPagamentoDia'		=> array(145,2),  
				'dataPagamentoMes'		=> array(147,2),  
				'dataPagamentoAno'		=> array(149,4),  				  				
				'valor'					=> array(77,13),  		
				); 
			break;
	}

	if (isset($subArray[$bancoId])) {
		return $subArray[$bancoId];
	} else {
		return false;
	}
}

/**
 * Faz o insert das informacoes do boleto (BRADESCO) no banco de dados.
 * @return string $html
 **/
function cb_boletoInsertBradesco()
{	
	$CI =& get_instance();	
	global $nome_arquivo;
	global $nomeSacado;
	global $cpf_cnpj;
	global $cep;
	$msg1 = "";
	$msg2 = "";
	$mensagem3 = "";
	$mensagem4 = "";

	$text = file($_SERVER['DOCUMENT_ROOT']."/novo_sistema/assets/temp/$nome_arquivo");
	if(substr($text[0],2,7) == "REMESSA"){
		$tem1 = 0;
		$tem2 = 0;

		if (!$substr = cb_boletoSubstring(237, 'remessa')) {
			return 'Falha ao receber substrings!';
		}
		
		
		foreach ($text as $key => $value) {
			if ($key > 0 ) {
				switch (substr($text[$key],0,1)) {
					case 1:
						$tem1 = 1;						
						$bancoID = $substr['bancoID'];
						$empresaID = substr($text[$key], $substr['empresaID'][0], $substr['empresaID'][1]);
						$numeroControle = substr($text[$key], $substr['numeroControle'][0], $substr['numeroControle'][1]);
						if (substr($text[$key], $substr['campoVerificaMulta'][0], $substr['campoVerificaMulta'][1]) == 2) {
							$multa = substr($text[$key], $substr['multa'][0], $substr['multa'][1]);
						}
						$nossoNumero = substr($text[$key], $substr['nossoNumero'][0], $substr['nossoNumero'][1]);
						$nossoNumeroDigito = substr($key[$key], $substr['nossoNumeroDigito'][0], $substr['nossoNumeroDigito'][1]);
						$descontos = substr($text[$key], $substr['descontos'][0], $substr['descontos'][1]);
						$numeroDocumento = substr($text[$key], $substr['numeroDocumento'][0], $substr['numeroDocumento'][1]);
						$dataVencimentoAno = "20".substr($text[$key], $substr['dataVencimentoAno'][0], $substr['dataVencimentoAno'][1]);
						$dataVencimentoMes = substr($text[$key], $substr['dataVencimentoMes'][0], $substr['dataVencimentoMes'][1]);
						$dataVencimentoDia = substr($text[$key], $substr['dataVencimentoDia'][0], $substr['dataVencimentoDia'][1]);
						$dataVencimento = $dataVencimentoAno.$dataVencimentoMes.$dataVencimentoDia;
						//Muda a data do vencimento para segunda feira, caso vença no sabado ou domingo.
						$dataVencimento = mudarDataParaSegunda($dataVencimento);
						$valorTitulo = substr($text[$key], $substr['valorTitulo'][0], $substr['valorTitulo'][1]);
						switch (substr($text[$key], $substr['especieTitulo'][0], $substr['especieTitulo'][1])) {
							case 01: $especieTitulo = "D"; break;
							case 02: $especieTitulo = "NP"; break;
							case 03: $especieTitulo = "NS"; break;
							case 04: $especieTitulo = "CS"; break;
							case 05: $especieTitulo = "R"; break;
							case 10: $especieTitulo = "LC"; break;
							case 11: $especieTitulo = "ND"; break;
							case 12: $especieTitulo = "DS"; break;
							case 99: $especieTitulo = "O"; break;
						}
						$aceite = substr($text[$key], $substr['aceite'][0], $substr['aceite'][1]);
						$dataEmissaoAno = "20".substr($text[$key], $substr['dataEmissaoAno'][0], $substr['dataEmissaoAno'][1]);
						$dataEmissaoMes = substr($text[$key], $substr['dataEmissaoMes'][0], $substr['dataEmissaoMes'][1]);
						$dataEmissaoDia = substr($text[$key], $substr['dataEmissaoDia'][0], $substr['dataEmissaoDia'][1]);
						$dataEmissao = $dataEmissaoAno.$dataEmissaoMes.$dataEmissaoDia;
						$valorAtraso = substr($text[$key], $substr['valorAtraso'][0], $substr['valorAtraso'][1]);
						$cpf_cnpj = substr($text[$key], $substr['cpf_cnpj'][0], $substr['cpf_cnpj'][1]);						
						$nomeSacado = substr($text[$key], $substr['nomeSacado'][0], $substr['nomeSacado'][1]);
						$enderecoCompleto = substr($text[$key], $substr['enderecoCompleto'][0], $substr['enderecoCompleto'][1]);
						$msg1 = substr($text[$key], $substr['msg1'][0], $substr['msg1'][1]);
						$msg2 = substr($text[$key], $substr['msg2'][0], $substr['msg2'][1]);
						$cep = substr($text[$key], $substr['cep'][0], $substr ['cep'][1]);
						break;

					case 2:
						$tem2 = 1;
						$mensagem1 = substr($text[$key], $substr['mensagem1'][0], $substr['mensagem1'][1]);
						$mensagem2 = substr($text[$key], $substr['mensagem2'][0], $substr['mensagem2'][1]);
						$mensagem3 = substr($text[$key], $substr['mensagem3'][0], $substr['mensagem3'][1]);
						$mensagem4 = substr($text[$key], $substr['mensagem4'][0], $substr['mensagem4'][1]);
						$agencia   = substr($text[$key], $substr['agencia'][0], $substr ['agencia'][1]);
						$carteira = 0;
						$contaCorrente = substr($text[$key], $substr['contaCorrente'][0], $substr['contaCorrente'][1]);
						$nossoNumero = substr($text[$key], $substr['nossoNumero'][0], $substr['nossoNumero'][1]);
						break;
				}//fim switch

				// cada boleto é composto de 2 linhas do arquivo remessa (1- dados 2-mensagem)
				if ($tem1 == $tem2) {
					cb_clienteSalvar();
					$sql = $CI->db->query("INSERT INTO boleto (bancoID,nomeSacado, dataProcessamento, dataDocumento, dataVencimento, dataPagamento , carteira, numeroDocumento, nossoNumero, valorTitulo, agencia, contaCorrente,descricaoBoleto, cpf_cnpj, enderecoCompleto, cep, mensagem1, mensagem2, mensagem3, mensagem4, numeroControle, nossoNumeroDigito, msg1, msg2, identificacao, especieTitulo, aceite, valorPago) VALUES ('$bancoID','$nomeSacado', '$dataEmissao', '$dataEmissao', '$dataVencimento', '', '$carteira', '$numeroDocumento', '$nossoNumero', '$valorTitulo', '$agencia', '$contaCorrente', 'Em aberto', '$cpf_cnpj', '$enderecoCompleto', '$cep', '$mensagem1', '$mensagem2', '$mensagem3', '$mensagem4', '$numeroControle', '$nossoNumeroDigito', '$msg1', '$msg2', '$msg2', '$especieTitulo', '$aceite', '')");

					$sql = $CI->db->query("INSERT INTO boleto_historico (bancoID,nomeSacado, dataProcessamento, dataDocumento, dataVencimento, dataPagamento , carteira, numeroDocumento, nossoNumero, valorTitulo, agencia, contaCorrente,descricaoBoleto, cpf_cnpj, enderecoCompleto, cep, mensagem1, mensagem2, mensagem3, mensagem4, numeroControle, nossoNumeroDigito, msg1, msg2, identificacao, especieTitulo, aceite, valorPago) VALUES ('$bancoID','$nomeSacado', '$dataEmissao', '$dataEmissao', '$dataVencimento', '', '$carteira', '$numeroDocumento', '$nossoNumero', '$valorTitulo', '$agencia', '$contaCorrente', 'Em aberto', '$cpf_cnpj', '$enderecoCompleto', '$cep', '$mensagem1', '$mensagem2', '$mensagem3', '$mensagem4', '$numeroControle', '$nossoNumeroDigito', '$msg1', '$msg2', '$msg2', '$especieTitulo', '$aceite', '')");
					// echo $sql."<br>";
					if (!$sql->db->error()) {
						/*
						@todo('Erro na consulta!'); // Erro Mysql: registro duplicado porque foi inserido índice Unique no campo numeroControle: melhorar script
							*/
					}
					// reinicia flags que indicam novo boleto a ser inserido
					$tem1 = 0;
					$tem2 = 0;
				}
			}//fim if($key)
		}//fim foreach
	} elseif (substr($text[0],2,7) == "RETORNO") {
		if (!$substr = cb_boletoSubstring(237, 'retorno')) {
			return 'Falha ao receber substrings!';
		}
		$quantidade = 0;
		foreach ($text as $key => $value) {
			if ($key>0) {
				if (substr($text[$key],0,1) == 1) {
					$numeroControle =  substr($text[$key], $substr['numeroControle'][0], $substr['numeroControle'][1]);
					$nossoNumero =  substr($text[$key], $substr['nossoNumero'][0], $substr['nossoNumero'][1]);
					$nossoNumeroDigito =  substr($text[$key], $substr['nossoNumeroDigito'][0], $substr['nossoNumeroDigito'][1]);
					$numeroDocumento =  substr($text[$key], $substr['numeroDocumento'][0], $substr['numeroDocumento'][1]);
					$valor =  substr($text[$key], $substr['valor'][0], $substr['valor'][1]);
					$carteira =  substr($text[$key], $substr['carteira'][0], $substr['carteira'][1]);
				    $dataPagamentoDia =  substr($text[$key], $substr['dataPagamentoDia'][0], $substr['dataPagamentoDia'][1]);
					$dataPagamentoMes =  substr($text[$key], $substr['dataPagamentoMes'][0], $substr['dataPagamentoMes'][1]);
					$dataPagamentoAno = "20". substr($text[$key], $substr['dataPagamentoAno'][0], $substr['dataPagamentoAno'][1]);
					$dataPagamento = "$dataPagamentoAno-$dataPagamentoMes-$dataPagamentoDia";
					if($valor == 0){
						$valor="";

					}
					$sql = $CI->db->query("
						UPDATE boleto SET nossoNumero = '$nossoNumero', carteira = '$carteira', dataPagamento = '$dataPagamento', nossoNumeroDigito = '$nossoNumeroDigito', valorPago='$valor'
						WHERE numeroDocumento LIKE '$numeroDocumento' AND numeroControle LIKE '$numeroControle'
					");		

					$sql = $CI->db->query("
						UPDATE boleto_historico SET nossoNumero = '$nossoNumero', carteira = '$carteira', dataPagamento = '$dataPagamento', nossoNumeroDigito = '$nossoNumeroDigito', valorPago='$valor'
						WHERE numeroDocumento LIKE '$numeroDocumento' AND numeroControle LIKE '$numeroControle'
					");				
					
					if(!empty($valor) && !empty($dataPagamento)){

						$CI->db->where('valorPago',$valor);
						$query = $CI->db->get('boleto')->result();

						$sql = $CI->db->query("");


					}	

				}//fim do if
			}//fim do if da key
		}//fim do foreach'
	}//fim do else
	
}

/**
* Faz o insert das informacoes do boleto (Caixa Economica Federal) no banco de dados.
**/
function cb_boletoInsertCaixaEconomica()
{
	/*
		PROBLEMAS ENCONTRADOS NESSE SCRIPT - MANAUS/AM 13/06/2014 AUTOR: RODRIGO CARVALHO SILVA
			- Numero da conta corrente não encontrado
			- Código Cedente está sendo inserido de forma manual
			- As informações disponibilizadas pela funcionária da CIVILCORP não batem com as fornecidas no arquivo Remessa e Retorno		
	*/	
	$CI =& get_instance();
	global $nome_arquivo;
	global $nomeSacado;
	global $cpf_cnpj;
	global $cep;
	$msg1 = "";
	$msg2 = "";
	$mensagem3 = "";
	$mensagem4 = "";

	// Array que contém a relação entre Codigo Cedente e Conta Corrente
	$arrayContaCorrente = array (
		'444775' => '13355', // CD Nascentes do Tarumã
		'467018' => '13410', // CD Passaredo
		'467031' => '13479', // CD Quinta das Marinas
		'467041' => '13495', // CD MARINA RIO BELLO, CD TIRADENTES, CD TOTAL VILLE E CD PRAIA DOS PASSARINHO
		'499540' => '14173', // CD Altos do Tarumã
		'467063' => '13509',  // CD VERTENTES
		'519900' => '1181',  // CD Acquarelle
		'566883' => '16206', // CD Morada dos Pássaros
		'590844' => '1417', // 20150626: Ricardo: Altos do Taruma???
		'633507' => '1595' // 20151031: Ricardo:  Cd. Vivenda das Marinas. A conta é 1595-1 cedente 633507, CNPJ 03.187.301/0001-64.
		);

	$text = file($_SERVER['DOCUMENT_ROOT']."/novo_sistema/assets/temp/$nome_arquivo");
	//Posição 143 guarda o tipo do arquivo, se for 1 é remessa se é 2 retorno
	if (substr($text[0], 142, 1) == 1) {
		$tem2 = 0;
		$tem3 = 0;

			if (!$substr = cb_boletoSubstring(104, 'remessa')) {
				return 'Falha ao receber substrings!';
			}		
		
		$bancoID = $substr['bancoID'];
		foreach ($text as $key => $value) {
			if ($key > 0 ) {
				switch (substr($text[$key],7,1)) {
					case 1:
						
						$tem1= 1;
							
						$mensagem1 = substr($text[$key], $substr['mensagem1'][0], $substr['mensagem1'][1]);
						$mensagem2 = substr($text[$key], $substr['mensagem2'][0], $substr['mensagem2'][1]);
						$empresaID = substr($text[$key], $substr['empresaID'][0], $substr['empresaID'][1]);
						$agencia = substr($text[$key], $substr['agencia'][0], $substr['agencia'][1]);						
						break;

					case 3:						
						// VERIFICA SE O SEGMENTO É do TIPO 'P'
						if( substr($text[$key], $substr['segmento'][0], $substr['segmento'][1]) == "P" ){
						//EXTRAI AS SUBSTRINGS
							$tem2 = 1;

							$nossoNumero = substr($text[$key], $substr['nossoNumero'][0], $substr['nossoNumero'][1]);
							//echo "ELTON >>>>>>>>".$text[$key].' - '. $key.' >> '.$substr['nossoNumero'][0].' >> '.$substr['nossoNumero'][1];
							$carteira = substr($text[$key], $substr['carteira'][0], $substr['carteira'][1]);
							$nossoNumeroDigito = substr($text[$key], $substr['nossoNumeroDigito'][0], $substr['nossoNumeroDigito'][1]);
							$numeroDocumento = substr($text[$key], $substr['numeroDocumento'][0], $substr['numeroDocumento'][1]);
							$numeroControle = substr($text[$key], $substr['numeroControle'][0], $substr['numeroControle'][1]);
							$dataVencimentoDia = substr($text[$key], $substr['dataVencimentoDia'][0], $substr['dataVencimentoDia'][1]);
							$dataVencimentoMes = substr($text[$key], $substr['dataVencimentoMes'][0], $substr['dataVencimentoMes'][1]);
							$dataVencimentoAno = substr($text[$key], $substr['dataVencimentoAno'][0], $substr['dataVencimentoAno'][1]);
							$dataVencimento = $dataVencimentoAno.$dataVencimentoMes.$dataVencimentoDia;
							//Muda a data do vencimento para segunda feira, caso vença no sabado ou domingo.
							$dataVencimento = mudarDataParaSegunda($dataVencimento);
							$valorTitulo = substr($text[$key], $substr['valorTitulo'][0], $substr['valorTitulo'][1]);
							$especieTitulo = substr($text[$key], $substr['especieTitulo'][0], $substr['especieTitulo'][1]);
							$aceite = substr($text[$key], $substr['aceite'][0], $substr['aceite'][1]);
							$codigoCedente = substr($text[$key], $substr['codigoCedente'][0], $substr['codigoCedente'][1]);
							$contaCorrente = $arrayContaCorrente[$codigoCedente];
							
							//  VERIFICA SE O CODIGO CEDENTE CONSTA NO ARRAY ($arrayContaCorrente) caso não existe ele dispara um email.

							if (!array_key_exists($codigoCedente, $arrayContaCorrente)) {
								$message = "Código Cedente: $codigoCedente inexistente no sistema de boletos da Civilcorp";
								cb_mail("erroboletos@civilcorp.com.br", 'Erro Boleto Civilcorp', $message);
							}

							switch (substr($text[$key], $substr['especieTitulo'][0], $substr['especieTitulo'][1])) {
								case 01: $especieTitulo = "CH"; break;
								case 02: $especieTitulo = "DM"; break;
								case 03: $especieTitulo = "DMI"; break;
								case 04: $especieTitulo = "DS"; break;
								case 05: $especieTitulo = "DSI"; break;
								case 06: $especieTitulo = "DR"; break;
								case 07: $especieTitulo = "LC"; break;
								case 08: $especieTitulo = "NCC"; break;
								case 09: $especieTitulo = "NCE"; break;
								case 10: $especieTitulo = "NCI"; break;
								case 11: $especieTitulo = "NCR"; break;
								case 12: $especieTitulo = "NP"; break;								
								case 13: $especieTitulo = "NPR"; break;
								case 14: $especieTitulo = "TM"; break;
								case 15: $especieTitulo = "TS"; break;
								case 16: $especieTitulo = "NS"; break;
								case 17: $especieTitulo = "RC"; break;
								case 18: $especieTitulo = "FAT"; break;
								case 19: $especieTitulo = "ND"; break;
								case 20: $especieTitulo = "AP"; break;
								case 21: $especieTitulo = "ME"; break;
								case 22: $especieTitulo = "PC"; break;
								case 23: $especieTitulo = "NF"; break;
								case 24: $especieTitulo = "DD"; break;
								case 25: $especieTitulo = "CPR"; break;
								case 99: $especieTitulo = "OU"; break;
							}
							$dataEmissaoDia = substr($text[$key], $substr['dataEmissaoDia'][0], $substr['dataEmissaoDia'][1]);
							$dataEmissaoMes = substr($text[$key], $substr['dataEmissaoMes'][0], $substr['dataEmissaoMes'][1]);
							$dataEmissaoAno = substr($text[$key], $substr['dataEmissaoAno'][0], $substr['dataEmissaoAno'][1]);
							$dataEmissao = $dataEmissaoAno.$dataEmissaoMes.$dataEmissaoDia;
							$valorAtraso = substr($text[$key], $substr['valorAtraso'][0], $substr['valorAtraso'][1]);										
						} elseif ( substr($text[$key], $substr['segmento'][0], $substr['segmento'][1]) == "Q") {
							$tem3 = 1;
						
							$nomeSacado = substr($text[$key], $substr['nomeSacado'][0], $substr['nomeSacado'][1]);
							$enderecoCompleto = substr($text[$key], $substr['enderecoCompleto'][0], $substr['enderecoCompleto'][1]);
							$cpf_cnpj = substr($text[$key], $substr['cpf_cnpj'][0], $substr['cpf_cnpj'][1]);
							$cep = substr($text[$key], $substr['cep'][0], $substr['cep'][1]);
							$estado = substr($text[$key], $substr['estado'][0], $substr['estado'][1]);
							$cidade = substr($text[$key], $substr['cidade'][0], $substr['cidade'][1]);
						} elseif (substr($text[$key], $substr['segmento'][0], $substr['segmento'][1]) == "R") {						
							$multa = substr($text[$key], $substr['multa'][0], $substr['multa'][1]);
						}
						break;
				}//fim switch

				// cada boleto é composto de por 3 linhas do arquivo remessa
				if ( $tem3 == 1 && $tem2 == 1) {
					cb_clienteSalvar();
					
					 if(!$CI->db->query("INSERT INTO boleto (bancoID,nomeSacado, dataProcessamento, dataDocumento, dataVencimento, dataPagamento , carteira, numeroDocumento, nossoNumero, valorTitulo, agencia, contaCorrente,descricaoBoleto, cpf_cnpj, enderecoCompleto, cep, mensagem1, mensagem2, mensagem3, mensagem4, numeroControle, nossoNumeroDigito, msg1, msg2, identificacao, especieTitulo, aceite, valorPago, cidade, estado, codigoCedente) VALUES ('$bancoID','$nomeSacado', '$dataEmissao', '$dataEmissao', '$dataVencimento', '0000-00-00', '$carteira', '$numeroDocumento', '$nossoNumero','$valorTitulo', '$agencia', '$contaCorrente', 'Em aberto', '$cpf_cnpj', '$enderecoCompleto', '$cep', '$mensagem1', '$mensagem2', '$mensagem3', '$mensagem4', '$numeroControle', '$nossoNumeroDigito', '$msg1', '$msg2', '$msg2', '$especieTitulo', '$aceite', '', '$cidade','$estado','$codigoCedente')")){

					 	echo "Error";
					 }

					 $CI->db->query("INSERT INTO boleto_historico (bancoID,nomeSacado, dataProcessamento, dataDocumento, dataVencimento, dataPagamento , carteira, numeroDocumento, nossoNumero, valorTitulo, agencia, contaCorrente,descricaoBoleto, cpf_cnpj, enderecoCompleto, cep, mensagem1, mensagem2, mensagem3, mensagem4, numeroControle, nossoNumeroDigito, msg1, msg2, identificacao, especieTitulo, aceite, valorPago, cidade, estado, codigoCedente) VALUES ('$bancoID','$nomeSacado', '$dataEmissao', '$dataEmissao', '$dataVencimento', '0000-00-00', '$carteira', '$numeroDocumento', '$nossoNumero','$valorTitulo', '$agencia', '$contaCorrente', 'Em aberto', '$cpf_cnpj', '$enderecoCompleto', '$cep', '$mensagem1', '$mensagem2', '$mensagem3', '$mensagem4', '$numeroControle', '$nossoNumeroDigito', '$msg1', '$msg2', '$msg2', '$especieTitulo', '$aceite', '', '$cidade','$estado','$codigoCedente')");
					// echo $sql."<br>";
										
						//@todo('Erro na consulta!'); // Erro Mysql: registro duplicado porque foi inserido índice Unique no campo numeroControle: melhorar script
				
					// reinicia flags que indicam novo boleto a ser inserido					
					$tem2 = 0;
					$tem3 = 0;
				}				
			}//fim if($key)
		}//fim foreach		
	} elseif (substr($text[0], 142, 1) == 2) {
		if (!$substr = cb_boletoSubstring(104, 'retorno')) {
			return 'Falha ao receber substrings!';
		}		
		$tem1 = 0;
		$tem2 = 0;

		foreach ($text as $key => $value) {
			if ($key>1) {
				switch (substr($text[$key],7,1)) {
					case '3':
						if(substr($text[$key], $substr['segmento'][0], $substr['segmento'][1]) == "T"){
							$tem1=1;
							$numeroControle =  substr($text[$key], $substr['numeroControle'][0], $substr['numeroControle'][1]);
							$nossoNumero =  substr($text[$key], $substr['nossoNumero'][0], $substr['nossoNumero'][1]);							
							$nossoNumeroDigito =  substr($text[$key], $substr['nossoNumeroDigito'][0], $substr['nossoNumeroDigito'][1]);
							$numeroDocumento =  substr($text[$key], $substr['numeroDocumento'][0], $substr['numeroDocumento'][1]);
							$carteira =  substr($text[$key], $substr['carteira'][0], $substr['carteira'][1]);
													
						} elseif (substr($text[$key], $substr['segmento'][0], $substr['segmento'][1]) == "U") {
						    $tem2=1;
						    $dataPagamentoDia =  substr($text[$key], $substr['dataPagamentoDia'][0], $substr['dataPagamentoDia'][1]);
							$dataPagamentoMes =  substr($text[$key], $substr['dataPagamentoMes'][0], $substr['dataPagamentoMes'][1]);
							$dataPagamentoAno =  substr($text[$key], $substr['dataPagamentoAno'][0], $substr['dataPagamentoAno'][1]);
							$dataPagamento = "$dataPagamentoAno-$dataPagamentoMes-$dataPagamentoDia";
							if($dataPagamento =="--") {
								$dataPagamento = "0000-00-00";
							}	
							$dataProcessamentoDia =  substr($text[$key], $substr['dataProcessamentoDia'][0], $substr['dataProcessamentoDia'][1]);
							$dataProcessamentoMes =  substr($text[$key], $substr['dataProcessamentoMes'][0], $substr['dataProcessamentoMes'][1]);
							$dataProcessamentoAno =  substr($text[$key], $substr['dataProcessamentoAno'][0], $substr['dataProcessamentoAno'][1]);
							$dataProcessamento = "$dataProcessamentoAno-$dataProcessamentoMes-$dataProcessamentoDia";
							if($dataProcessamento =="--") {
								$dataProcessamento = "0000-00-00";
							}	
							$valor =  substr($text[$key], $substr['valor'][0], $substr['valor'][1]);
							if($valor == 0) {
								$valor = "";	
							}
						}
						break;
				}//FIM SWITCH
				if ( $tem1 == 1 && $tem2 == 1) {
					cb_clienteSalvar();

					if(!$CI->db->query("
							UPDATE boleto SET nossoNumero = '$nossoNumero', dataProcessamento = '$dataProcessamento', carteira = '$carteira', dataPagamento = '$dataPagamento', nossoNumeroDigito = '$nossoNumeroDigito', valorPago='$valor'
							WHERE numeroDocumento LIKE '$numeroDocumento' AND numeroControle LIKE '$numeroControle'
						")){

						echo "Error";
					}	

					$CI->db->query("
							UPDATE boleto_historico SET nossoNumero = '$nossoNumero', dataProcessamento = '$dataProcessamento', carteira = '$carteira', dataPagamento = '$dataPagamento', nossoNumeroDigito = '$nossoNumeroDigito', valorPago='$valor'
							WHERE numeroDocumento LIKE '$numeroDocumento' AND numeroControle LIKE '$numeroControle'
						");	
					// echo $sql."<br>";
										
						//@todo('Erro na consulta!'); // Erro Mysql: registro duplicado porque foi inserido índice Unique no campo numeroControle: melhorar script
				
					// reinicia flags que indicam novo boleto a ser inserido					
					$tem2 = 0;
					$tem1 = 0;
				}					
			}//fim do if da key
		}//fim do foreach'
	}//fim do else
	
}
/**
 * faz a insercao de todos os clientes a partir do arquivo remessa.
 * @return string $html
 **/
function cb_clienteSalvar()
{	
	$CI =& get_instance();
	global $nome_arquivo;
	global $nomeSacado;
	global $cpf_cnpj;
	global $cep;
	$senhaUsuario = "";

	$text = file($_SERVER['DOCUMENT_ROOT']."/novo_sistema/assets/temp/$nome_arquivo");
		$nomeUsuario = $nomeSacado;
		$cpf_cnpjUsuario = $cpf_cnpj;
		$emailUsuario = "";
		$cepUsuario = $cep;
		for($i = 0; $i < 6; $i++){
			$senhaUsuario .= rand(1,9);
		}

		$CI->db->where('cpf_cnpj',$cpf_cnpjUsuario);
		$sql = $CI->db->get('usuario')->result();
		if(!$sql){

		$CI->db->query("INSERT INTO usuario (nome,email,cpf_cnpj,senha,cep,status_usuario) VALUES ('$nomeUsuario','$emailUsuario','$cpf_cnpjUsuario','$senhaUsuario','$cepUsuario','1')");
		}
}
/**
 * faz o select de todos os boletos que estao no BD.
 * @return string $html
 */
function cb_boletoSelect()
{
	global $config;

	cb_boletoDeleteAntigos();
	
	$html = '';
	$linkPaginacao = '?p=boleto';
	$dataXdias = cc_calcularDataHojeMenosXisDias(10);//cc_calcularData21dias();


	if ($_REQUEST['tipoFiltro']) {
		$buscaTxt = mysql_real_escape_string(strip_tags($_REQUEST['buscaTxt']));
		$tipoFiltro = mysql_real_escape_string(strip_tags($_REQUEST['tipoFiltro']));
		
		if ($tipoFiltro == 1) {
			$condicao = "WHERE (nossoNumero) LIKE '%$nossoNumero%'";
		} elseif ($tipoFiltro == 2){
			$condicao = "WHERE (nomeSacado) LIKE '%$buscaTxt%'";
		} elseif ($tipoFiltro == 3){ 
			$buscaTxt = cb_limparLogin($buscaTxt);
			$buscaTxt = str_pad($buscaTxt, 14, "0", STR_PAD_LEFT);
			$condicao = "WHERE (cpf_cnpj) = '$buscaTxt'";
		} else {
			$html = "Por favor digite algo para que seja realizada a busca";
		}		
		$linkPaginacao .= "&nossoNumero=$nossoNumero";
	} else {
		$condicao = '';
	}

	$qtdRegistros = mysql_result(mysql_query("SELECT COUNT(boletoId) AS qtd FROM boleto $condicao"),0,'qtd');

	// define parâmetros de paginacao para esta lista
	$paginacao = cb_Paginacao($linkPaginacao,$qtdRegistros);//$paginacao = ['inicio','pagina','listaLinksPaginacao']
	
	if ($qtdRegistros == 0) {
		$html .= "<center>Não há boletos cadastrados</center>";
	} else {	
		$html .= "
			<script language='Javascript'>
				function confirmacaoDeletar(id) { 
					var resposta = confirm('Deseja remover esse boleto?');
					if (resposta == true) { 
						window.location.href = '?p=boleto&q=excluir&id='+id;
					}
				}
			</script>
		";

		$formBusca = cb_boletoFormBusca();

		$html .= "
			$formBusca

			<table border='0' cellspacing='10' cellpadding='10'>
				<tr>
					<th>Documento</th>
					<th>Nome Sacado / CPF</th>
					<th>Nosso N&uacute;mero</th>
					<th>Venc</th>
					<th>Valor</th>
					<th>Status</th>
					<th>Opções</th>	
				</tr>
		";

		$sql = "SELECT boletoId,dataVencimento,dataPagamento,valorTitulo,cpf_cnpj,nomeSacado,nossoNumero,numeroDocumento,bancoID FROM boleto $condicao ORDER BY boletoId DESC LIMIT {$paginacao['inicio']},{$config['qtdPaginacao']}";
		if ($result = mysql_query($sql)) {
			while($boletos = mysql_fetch_array($result)){
				
				if($boletos['dataVencimento'] < $dataXdias && $boletos['dataPagamento'] == '0000-00-00'){
					$classe = "class='boletoStatusLaranja' ";
				} else {
					$classe = "";
				}

				$boletos['dataPagamento'] == '0000-00-00' ? $status = 'Em aberto' : $status = 'Pago!';

				$boletos['cpf_cnpj'] = cb_cpfCnpjFormatar($boletos['cpf_cnpj']);
				//NOSSO NUMERO SO PODE ESTAR INDISPONIVEL QUANDO AINDA NAO FOI REALIZADO O UPLOAD DO ARQUIVO RETORNO, E REJEITADO, QUANDO O BANCO REJEITA O MESMO
				if ($boletos['nossoNumero'] == "00000000000") {
					$nossoNumeroBoleto = "BOLETO INDISPONIVEL / REJEITADO";
				} else {
					$nossoNumeroBoleto = $boletos['nossoNumero'];
				}

				$html .= "
					<tr $classe>
						<td>".$boletos['numeroDocumento']."</td>
						<td><span style='color:#00a;'>".$boletos['nomeSacado']."</span><br>
							{$boletos['cpf_cnpj']}
						</td>
						<td>".$nossoNumeroBoleto."</td>
						<td>".substr($boletos['dataVencimento'],8,2)."/".substr($boletos['dataVencimento'],5,2)."/".substr($boletos['dataVencimento'],0,4)."</td>
						<td align='right' style='color:#f00;'>".number_format(((intval($boletos['valorTitulo']))/100), 2, ",", ".")."</td>
						<td>$status</td> 
						<td>
					";
					//<td>".$boletos['boletoId']."</td>
				if ($boletos['nossoNumero'] != "00000000000") {
					if($boletos['bancoID'] == 104){
						$html .= "
						<a href='../cliente/boleto_cef_sigcb.php?id={$boletos['boletoId']}&nosso=$nossoNumeroBoleto' target='_blank'>
							<img src='../inc/icones/barcode.png' width='16' height='16'>
						</a>";
					} elseif ($boletos['bancoID'] == 237) {
					$html .= "
						<a href='../cliente/boleto_bradesco.php?id={$boletos['boletoId']}&nosso=$nossoNumeroBoleto' target='_blank'>
							<img src='../inc/icones/barcode.png' width='16' height='16'>
						</a>";
					} else {
						$html.= "<span style='float:left;width:23px;height:20px;'></span>";
					}

				} else {
					$html .= "
						<span style='float:left;width:23px;height:20px;'></span>
					";
				}
				$html .= "
					&nbsp;&nbsp;&nbsp;
					<a href='javascript:func()' onclick=confirmacaoDeletar(".$boletos['boletoId'].")>
						<img src='http://clientecivilcorp.com.br/ohs/pub/3rdparty/icons/cross.png' width='16' height='16'>
					</a>
				</td>
			</tr>
				";
			}//fim while
		}//fim if

		$html .= "
			</table>

			<!-- paginacao -->
			{$paginacao['listaLinksPaginacao']}
		";
	}
	return $html;
}

/**
 * formulario para exibir HTML da busca de boletos
 * @return string $html
 */
function cb_boletoFormBusca()
{
	return "
		<!-- busca boleto -->
		<div class='container busca-boleto-clientes'>
			<form action='?p=boleto&amp;q=buscar' method='POST'>
				<div><input type='text' name='buscaTxt' placeholder='Digite aqui' class='tamanho'></div>
				<div>
					<select name='tipoFiltro'>
						<option value='1'>Nosso Número</option>
						<option value='2'>Nome do Sacado</option>
						<option value='3'>CPF/CNPJ</option>
					</select>
				</div>
				<div><input type='submit' name='botaoEnviar' value='Pesquisar Boletos' class='bt'></div>
			</form>
		</div>
		<!-- busca boleto fim -->
		";
}

?>